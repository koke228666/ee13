<?php
$dark = $_REQUEST['dark'];
if ($dark === '' || $dark === '1') {
    $ee13vars->cookie("pv_dark", ($dark === '') ? '0' : '1');
    return $ee13vars->ajax(0);
} else {
    return $ee13vars->ajax(8, ["ты ахуел"]);
}