<?php
use openvk\Web\Models\Repositories\Audios;

if (!$thisUser) {
    return $ee13vars->ajax(8, [$ee13vars->get_lang("user_reqiured")]);
}

if ($_REQUEST['lid']) {
    $lyrics_id = $_REQUEST['lid'];

    $audio = (new Audios())->get($lyrics_id);

    if (!$audio || !$audio->getLyrics()) {
        return $ee13vars->ajax(8, [$ee13vars->get_lang("not_found")]);
    }

    if (!$audio->canBeViewedBy($thisUser)) {
        return $ee13vars->ajax(8, [$ee13vars->get_lang("access_denied")]);
    }

    $cleaned_lyrics = nl2br($audio->getLyrics());

    return $ee13vars->ajax(0, [$cleaned_lyrics]);
} else {
    return $ee13vars->ajax(7, ["lyrics id not set"]);
}