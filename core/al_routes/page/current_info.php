<?php
if (!$ee13vars->check_csrf()) {
    return $GLOBALS['ee13']->get_lang("csrf_fail");
}

if ($_REQUEST['oid'] < 0 || !$_REQUEST['info']) {
    return "сори но нет";
}

if ($_REQUEST['info']) {
    $thisUser->setStatus($_REQUEST['info']);
    $thisUser->save();
    return $ee13vars->ajax(0);
}