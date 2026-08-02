<?php
use openvk\Web\Models\Repositories\Audios;

if (!$thisUser) {
    return $ee13vars->ajax(8, [$ee13vars->get_lang("user_reqiured")]);
}
if (!$ee13vars->check_csrf()) {
    return $ee13vars->ajax(8, [$ee13vars->get_lang("csrf_fail")]);
}

if ($_REQUEST['full_id']) {
    $id_split = explode("_", $_REQUEST['full_id']);
	$owner_id = (int)$id_split[0];
	$audio_id = (int)$id_split[1];

    if (isset($owner_id) && $audio_id) {
        $audios = new Audios();
        $audio = $audios->get($audio_id);
        if (!$audio) {
            $ee13vars->ajax(7, [$ee13vars->get_lang("not_found")]);
        } elseif (!$audio->canBeViewedBy($thisUser)) {
            $ee13vars->ajax(8, [$ee13vars->get_lang("access_denied")]);
        }

        $audio->listen($thisUser);
        return $ee13vars->ajax(0);
    }
}