<?php
if (!$thisUser) {
    return $ee13vars->ajax(8, [$ee13vars->get_lang("user_reqiured")]);
}
switch ($ee13vars->rate_limit()) {
    case 18:
        return $ee13vars->ajax(5, [$ee13vars->get_lang("ratelim_pizda")]);
    case 29:
        return $ee13vars->ajax(7, [$ee13vars->get_lang("ratelim_temp")]);
}

$friends = [
    1 => [
        "Nom" => [[0, ""]],
    ],
    2 => [
        "Nom" => [[0, ""]],
    ],
];

$friendsCount = $thisUser->getFriendsCount();

if ($friendsCount > 0) {
    foreach ($thisUser->getFriends(1, $friendsCount) as $friend) {
        $friends[1]["Nom"][] = [
            $friend->getId(),
            strip_tags($friend->getCanonicalName()),
        ];
    }
    $friends[2]["Nom"] = $friends[1]["Nom"];
}

return $ee13vars->ajax(0, [$friends]);