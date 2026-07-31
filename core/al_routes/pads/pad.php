<?php
if (!$thisUser) {
    return $ee13vars->ajax(8, [$ee13vars->get_lang("user_reqiured")]);
}

if (isset($_REQUEST['act']) && $_REQUEST['act'] == "pad") {
    $act = preg_replace('/[^a-zA-Z0-9_]/', '', $_REQUEST['pad_id'] ?? '');

    $base_path = OPENVK_ROOT . "/themepacks/" . $theme->getId() . "/core/al_routes/pads/pads";
    $act_file = str_replace('/', DIRECTORY_SEPARATOR, "{$base_path}/{$act}.php");

    if (file_exists($act_file)) {
        $response = require $act_file; 
        return $response;
    } else {
        return $ee13vars->ajax(7, [$ee13vars->get_lang("pad_unknown_act") . "!!!<br>target: <b>" . htmlspecialchars($act) . "</b>"]);
    }
} else {
    return $ee13vars->ajax(8, [$ee13vars->get_lang("extreme_failure")]);
}