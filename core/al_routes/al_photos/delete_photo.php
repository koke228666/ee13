<?php
use openvk\Web\Models\Repositories\Photos as PhotosRepo;

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
        return $ee13vars->ajax(7, [$ee13vars->get_lang("ratelim_temp")]);
}

if ($_REQUEST['photo']) {
    $id_split = explode("_", $_REQUEST['photo']);
    $owner_id = $id_split[0];
    $photo_id = $id_split[1];

    $photo = (new PhotosRepo())->getByOwnerAndVID($owner_id, $photo_id);
    if (!$photo || $photo->isDeleted() || !$photo->canBeModifiedBy($thisUser)) {
        return $ee13vars->ajax(8, [$ee13vars->get_lang("access_denied")]);
    }

    $photo->delete();

    return $ee13vars->ajax(0, [tr('photo_is_deleted') . '.' .
               ' <a onclick="Photoview.restorePhoto()">' . $ee13vars->get_lang("restore") . '</a>' .
               '<span id="pv_restore_progress" class="progress_inline" style="display:none;"></span>'
           ]);
}

