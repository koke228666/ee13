<?php
use openvk\Web\Models\Repositories\Photos as PhotosRepo;

if (!$thisUser) {
    return $ee13vars->get_lang("user_reqiured");
}

if ($_REQUEST["photo"]) {
    $photos = new PhotosRepo();
    $id_split = explode("_", $_REQUEST["photo"]);

    $photo = $photos->getByOwnerAndVID((int)$id_split[0], (int)$id_split[1]);
    
    if (!$photo || $photo->isDeleted() || !$photo->canBeModifiedBy($thisUser)) {
        return $ee13vars->ajax(8, [$ee13vars->get_lang("access_denied")]);
    }
    
    return $ee13vars->ajax(0, [$photo->getDescription()]);
} else {
    return $ee13vars->ajax(8, [$ee13vars->get_lang("photo_unknown")]);
}