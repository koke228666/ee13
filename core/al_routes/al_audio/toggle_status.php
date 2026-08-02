<?php
use openvk\Web\Models\Repositories\Audios;

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


$broadcast = false;
if ((int)$_REQUEST['exp'] == 1) {
    $broadcast = true;
}

$thisUser->setAudio_broadcast_enabled($broadcast);
$thisUser->save();

if ($_REQUEST['id']) {
    $id_split = explode("_", $_REQUEST['id']);
    $owner_id = (int)$id_split[0];
    $audio_id = (int)$id_split[1];
}

if ($owner_id && $audio_id && $broadcast) {
    $audios = new Audios();
    $audio = $audios->get($audio_id);
    if (!$audio) {
        return $ee13vars->ajax(7, [$ee13vars->get_lang("not_found")]);
    } elseif (!$audio->canBeViewedBy($thisUser)) {
        return $ee13vars->ajax(8, [$ee13vars->get_lang("access_denied")]);
    }
    $audio->listen($thisUser);
}

$audio_status = $thisUser->getCurrentAudioStatus();
$current_status = htmlspecialchars($thisUser->getStatus());
if ($audio_status && $broadcast) {
    $audio_name = htmlspecialchars($audio_status->getName());
    return $ee13vars->ajax(0, ['<span style="display: none;" class="my_current_info"><span class="current_text">' . $current_status . '</span></span>' .
                               '<a class="current_audio fl_l"><div class="label fl_l"></div>' . $audio_name . '</a>']);
} else {
    return $ee13vars->ajax(0, ['<span class="my_current_info"><span class="current_text">' . $current_status . '</span></span>']);
}