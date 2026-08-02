<?php
if (!$thisUser) {
    return $ee13vars->get_lang("user_reqiured");
}
if (!$ee13vars->check_csrf()) {
    return $GLOBALS['ee13']->get_lang("csrf_fail");
}
switch ($ee13vars->rate_limit()) {
    case 18:
        return $ee13vars->ajax(5, [$ee13vars->get_lang("ratelim_pizda")]);
    case 29:
        return $ee13vars->ajax(7, [tr("rate_limit_error_comment", OPENVK_ROOT_CONF["openvk"]["appearance"]["name"], $ee13vars->get_lang("ratelim_temp"))]);
}


if ($_REQUEST['oid'] < 0) {
    return "сори но нет";
}

if ($_REQUEST['info']) {
    $status = null;
    if ($_REQUEST['info']) {
        $status = $_REQUEST['info'];
    }
    $thisUser->setStatus($status);
    $thisUser->save();
    return $ee13vars->ajax(0);
}