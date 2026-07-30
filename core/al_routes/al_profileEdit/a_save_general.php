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

// todo здесь был ии говнокод, я его убрал, потому что мне стало стыдно, я потом напишу сам

return $ee13vars->ajax(0, [
    [
        'only_name'     => false,
        'name_response' => '', // заменяет уведомление
        'status'        => 0, // семейное положение
        'partner'       => 0, // собсна партнёр
        'relation_text' => '', // текст отношений
        'family'        => []
    ]
]);
