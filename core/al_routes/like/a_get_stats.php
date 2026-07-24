<?php
if ($_REQUEST['object']) {
    if (preg_match('/^([a-zA-Z_]+)([\d\-_]+)$/', $_REQUEST['object'], $out)) {
        $type = $out[1];
        $id = $out[2];
        $id_split = explode("_", $id);

        switch($type) {
            case "photo": {
                $photos = new \openvk\Web\Models\Repositories\Photos();
                $photo = $photos->getByOwnerAndVID((int)$id_split[0], (int)$id_split[1]);
                $likers = $photo->getLikers(1, 6);
                $like_count = $photo->getLikesCount();
                $render_template = "likes";
                $likes_tpl_type = $type;
                $liked_by_me = $photo->hasLikeFrom($thisUser);
                $target_id = $id;
                return;
            }
        }
    } else {
        return "failed to split string" . htmlspecialchars($_REQUEST['object']);
    }
} else {
    return "не, это так не работает";
}