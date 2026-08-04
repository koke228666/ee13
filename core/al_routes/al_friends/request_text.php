<?php
// stubbed until better times
$message = nl2br(htmlspecialchars($_REQUEST['message']));
return $ee13vars->ajax(0, [$message]);