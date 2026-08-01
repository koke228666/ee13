<?php
use openvk\Web\Models\Repositories\Audios;
use openvk\Web\Models\Repositories\Users;
use openvk\Web\Models\Repositories\Clubs;

if (!$thisUser) {
    return $ee13vars->ajax(8, [$ee13vars->get_lang("user_reqiured")]);
}

if ($_REQUEST['gid'] || $_REQUEST['id']) {
    $owner_id = 0;
    $page = 1;

    if ((int)$_REQUEST['gid'] != 0) {
        $owner_id = -1 * (int)$_REQUEST['gid'];
    } elseif ((int)$_REQUEST['id'] != 0) {
        $owner_id = (int)$_REQUEST['id'];
    } else {
        return $ee13vars->ajax(8, ["failed to detect owner type"]);
    }

    if ($owner_id < 0) {
        $entity = (new Clubs())->get($owner_id * -1);
        if (!$entity || $entity->isBanned()) {
            return $ee13vars->ajax(4, ["/audios" . $thisUser->getId()]);
        }

        $audios = (new Audios())->getByClub($entity, $page, 2147483647);
    } else {
        $entity = (new Users())->get($owner_id);
        if (!$entity || $entity->isDeleted() || $entity->isBanned()) {
            return $ee13vars->ajax(4, ["/audios" . $thisUser->getId()]);
        }

        if (!$entity->getPrivacyPermission("audios.read", $thisUser->identity)) {
            return $ee13vars->ajax(4, ["/audios" . $thisUser->getId()]);
        }

        $audios = (new Audios())->getByUser($entity, $page, 2147483647);
    }

    foreach ($audios as $audio) {
        $rows[] = [
            $audio->getOwner()->getId(),
            $audio->getVirtualId(),
            $audio->getOriginalURL(),
            $audio->getLength(),
            $audio->getFormattedLength(),
            htmlspecialchars($audio->getPerformer()),
            htmlspecialchars($audio->getTitle()),
            !empty($audio->getLyrics()) ? $audio->getId() : 0,
            $audio->getAlbumId() ?: 0,
            (int)(!$audio->isInLibraryOf($thisUser) && !$audio->isWithdrawn()),
            '',
            0,
            (int)$audio->isInLibraryOf($thisUser),
            0,
            (int)$audio->canBeModifiedBy($thisUser)
        ];
    }

    $data = [
        "all" => $rows,
    ];

    $opts = [
        "hashes" => [
            "add_hash"     => $csrfToken,
            "delete_hash"  => $csrfToken,
            "restore_hash" => $csrfToken,
        ],
        'summaryLang' => [
            'all_friend_title'  => tr('audios'),
            'all_friend_htitle' => tr('audios'),
            'all_club_htitle'   => tr('audios_group'),
        ],
    ];


    return $ee13vars->ajax(0, [
        json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        json_encode($opts, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
    ]);
}