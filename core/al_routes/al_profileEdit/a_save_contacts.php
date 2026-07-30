<?php
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

// todo

return $ee13vars->ajax(0, [
    [
        'email'   => $email,
        'mobile'  => '',
        'home'    => $home,
        'website' => $website,
        'skype'   => $skype,
        'country' => $country,
        'city'    => '',
    ],
    "<b>" . tr('changes_saved') . ".</b><br>" . tr('changes_saved_comment') . ".",
]);