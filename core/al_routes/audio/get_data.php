<?php
return $ee13vars->ajax(0, [
    $csrfToken,
    $thisUser->isBroadcastEnabled() ? (object)[$thisUser->getId() => 1] : (object)[],
    [], // локаль
]);