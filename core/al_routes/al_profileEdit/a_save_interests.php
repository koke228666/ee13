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
        return $ee13vars->ajax(7, [tr("rate_limit_error_comment", OPENVK_ROOT_CONF["openvk"]["appearance"]["name"], $ee13vars->get_lang("ratelim_temp"))]);
}

// todo

return $ee13vars->ajax(0, [
    [
        'activities' => '',
        'interests'  => $thisUser->getInterests(),
        'music'      => $thisUser->getFavoriteMusic(),
        'movies'     => $thisUser->getFavoriteFilms(),
        'tv'         => $thisUser->getFavoriteShows(),
        'books'      => $thisUser->getFavoriteBooks(),
        'games'      => $thisUser->getFavoriteGames(),
        'quotes'     => $thisUser->getFavoriteQuote(),
        'about'      => $thisUser->getDescription()
    ],
]);
