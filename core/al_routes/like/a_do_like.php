<?php
use openvk\Web\Models\Repositories\Users as UsersRepo;
use openvk\Web\Models\Repositories\Posts as PostsRepo;
use openvk\Web\Models\Repositories\Comments as CommentsRepo;
use openvk\Web\Models\Repositories\Videos as VideosRepo;
use openvk\Web\Models\Repositories\Photos as PhotosRepo;
use openvk\Web\Models\Repositories\Notes as NotesRepo;

if (!$thisUser) {
    return $ee13vars->ajax(8, [$ee13vars->get_lang("user_reqiured")]);
}
if (!$ee13vars->check_csrf()) {
    return $ee13vars->ajax(8, [$ee13vars->get_lang("csrf_fail")]);
}
switch ($ee13vars->rate_limit()) {
    case 18:
        return $ee13vars->ajax(5, [$ee13vars->get_lang("ratelim_pizda")]);
    case 29:
        return $ee13vars->ajax(7, [tr("rate_limit_error_comment", OPENVK_ROOT_CONF["openvk"]["appearance"]["name"], $ee13vars->get_lang("ratelim_temp"))]);
}

if ($_REQUEST["object"]) {
    // сюда ещё передаётся $_REQUEST["object"], скорее всего в вк
    // использовалось для статистики
    if (preg_match('/^([a-zA-Z_]+)([\d\-_]+)$/', $_REQUEST['object'], $out)) {
        $type = $out[1];
        $id = $out[2];
        $id_split = explode("_", $id);
        $owner_id = $id_split[0];
        $item_id = $id_split[1];

        $postable = null;
        switch ($type) {
            case "wall":
                $post = (new PostsRepo())->getPostById($owner_id, $item_id);
                $postable = $post;
                break;
            case "comment":
                $comment = (new CommentsRepo())->get($item_id);
                $postable = $comment;
                break;
            case "video":
                $video = (new VideosRepo())->getByOwnerAndVID($owner_id, $item_id);
                $postable = $video;
                break;
            case "photo":
                $photo = (new PhotosRepo())->getByOwnerAndVID($owner_id, $item_id);
                $postable = $photo;
                break;
            case "note":
                $note = (new NotesRepo())->getNoteById($owner_id, $item_id);
                $postable = $note;
                break;
            default:
                return $ee13vars->ajax(8, [$ee13vars->get_lang("type_unknown")]);
        }

        if (is_null($postable) || $postable->isDeleted()) {
            return $ee13vars->ajax(8, [$ee13vars->get_lang("obj_not_found")]);
        }

        if (!$postable->canBeViewedBy($thisUser ?? null)) {
            return $ee13vars->ajax(8, [$ee13vars->get_lang("access_denied")]);
        }

        $postable->setLike(true, $thisUser);
        
        if ($type === "wall") {
            return $ee13vars->ajax(0, [[
                'like_my'    => (int)$postable->hasLikeFrom($thisUser),
                'like_num'   => $postable->getLikesCount(),
                'like_title' => tr('liked_by_x_people', ($like_count === 0) ? 1 : $postable->getLikesCount()),
                'share_my'    => 0,
                'share_num'   => 0,
                'share_title' => "поделилось 0 человек пиздец"
            ]]);
        } else {
            return $ee13vars->ajax(0, [
                $postable->getLikesCount(),
                tr('liked_by_x_people', ($like_count === 0) ? 1 : $postable->getLikesCount())
            ]);
        }
    } else {
        return $ee13vars->ajax(8, [$ee13vars->get_lang("type_unknown")]);
    }
} else {
    return $ee13vars->ajax(8, [$ee13vars->get_lang("type_unknown")]);
}