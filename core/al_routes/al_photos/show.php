<?php
$photos = new \openvk\Web\Models\Repositories\Photos();
$photo_id = explode("_", $_REQUEST['photo']);

// todo: возможно положить ХЕР на это и просто не возвращать альбом, если вот так вот получилось (хотя лучше не надо)
$photo = $photos->getByOwnerAndVID((int) $photo_id[0], (int) $photo_id[1]);
if (!$photo || $photo->isDeleted() || !$photo->canBeViewedBy($thisUser)) {
    return $ee13vars->ajax(8, [$ee13vars->get_lang("photo_privacy_error")]);
}

$ph_album = $photo->getAlbum();
$photosizes = $photo->getSizes();
$list_id = $_REQUEST['list'];
$count  = 1; // todo: реализовать нормальную подгрузку из альбома (!!!)
$offset = 0;
$data = [
    [
        'id'        => $_REQUEST['photo'],
        'hash'      => $csrfToken, // тут немного не тот хэш (вроде)
        'base'      => '',
        'x_'        => [$photo->getURLBySizeId('normal'), $photosizes['normal']->width ?? 0, $photosizes['normal']->height ?? 0],
        'y_'        => [$photo->getURLBySizeId('large'), $photosizes['large']->width ?? 0, $photosizes['large']->height ?? 0],
        'z_'        => [$photo->getURLBySizeId('larger'), $photosizes['larger']->width ?? 0, $photosizes['larger']->height ?? 0],
        
        'author'    => '<a class="mem_link" href="' . $photo->getOwner()->getUrl() . '">' . htmlspecialchars($photo->getOwner()->getCanonicalName()) . '</a>',
        'album'     => $ph_album ? '<a href="/album' . $ph_album->getId() . '">' . htmlspecialchars($ph_album->getName()) . '</a>' : '<a href="' . $photo->getOwner()->getUrl() . '">' . htmlspecialchars($photo->getOwner()->getCanonicalName()) . '</a>',
        'date'      => (string)$photo->getPublicationTime(),
        'desc'      => htmlspecialchars($photo->getDescription()),
        'comments'  => '',
        'commcount' => 0,
        'commshown' => 0,
        'likes'     => $photo->getLikesCount(),
        'liked'     => (int)$photo->hasLikeFrom($thisUser),
        'rotate'    => '',
        'actions'   => [
            'edit'  => (int)$photo->canBeModifiedBy($thisUser),
            'place' => 0,
            'del'   => (int)$photo->canBeModifiedBy($thisUser),
            'rot'   => 0,
            'comm'  => 0,
            'prof'  => 0
        ],
        'tags'      => [],
        'tagged'    => [],
        'tagshtml'  => ''
    ]
];

$opts = [
    'commlimit' => 16384,
    'maxtags'   => 0,
    'lang'      => [ // todo: перенести в ee13_vars
        'reply_to_post'              => 'Комментировать..',
        'wall_more_replies'          => ["", "еще %s комментарий", "еще %s комментария", "еще %s комментариев"],
        'wall_all_replies'           => 'все комментарии',
        'wall_M_replies_of_N'        => ["", "%s комментарий из {link}{all}{/link}", "%s последних комментария из {link}{all}{/link}", "%s последних комментариев из {link}{all}{/link}"],
        'wall_reply_as_group'        => 'от имени сообщества',
        'photos_X_comms'             => ["", "%s комментарий", "%s комментария", "%s комментариев"],
        'photos_onthisphoto'         => 'На этой фотографии',
        'photos_yourcomment'         => 'Ваш комментарий',
        'photos_delete_tag'          => 'Удалить отметку',
        'photos_tagperson'           => 'Отметить человека',
        'photos_select_tag_area'     => 'Выделите область, где изображен человек, и он будет подписан на фотографии.',
        'photos_typename'            => 'Введите имя',
        'photos_tags_me'             => 'Я',
        'photos_confirm_tag'         => 'Подтвердить',
        'photos_load_to_profile'     => 'Поместить на мою страницу',
        'photos_load_to_dialog'      => 'Сделать обложкой беседы',
        'photos_in_closed_album'     => 'Комментарии к этой фотографии скрыты настройками приватности.',
        'photos_edit'                => 'Редактировать',
        'photos_rotate'              => 'Повернуть:',
        'photos_album_name'          => 'Альбом:',
        'photos_author'              => 'Отправитель:',
        'photos_added'               => 'Добавлена',
        'photos_i_like'              => 'Мне нравится',
        'photos_download_hq'         => 'Загрузить оригинал на диск',
        'photos_larger'              => 'Увеличить фотографию',
        'photos_smaller'             => 'Уменьшить фотографию',
        'photos_photo_num_of_N'      => 'Фотография %s из %s',
        'photos_view_one_photo'      => 'Просмотр фотографии',
        'photos_show_prev_comments'  => ["", "Показать предыдущий %s комментарий", "Показать предыдущие %s комментария", "Показать предыдущие %s комментариев"],
        'photos_edit_desc'           => 'Редактировать описание',
        'photos_edit_desc_intro'     => 'Введите описание',
        'photos_share_from_view'     => 'Поделиться',
        'photos_send_to_fr'          => 'Отправить другу',
        'photos_save_to_alb'         => 'Сохранить к себе в альбом',
        'photos_repeat_album'        => 'Смотреть еще раз',
        'photos_place_label'         => 'Место:',
        'photos_edit_add_place'      => 'Указать место',
        'photos_fullscreen'          => 'На весь экран',
        'photos_slideshow'           => 'Показ слайдов',
        'photos_seconds'             => ["", "%s секунда", "%s секунды", "%s секунд"],
        'global_add_media'           => 'Прикрепить',
        'dont_attach'                => 'Не прикреплять',
        'profile_mention_not_found'  => 'Страница не найдена',
        'profile_mention_start_typing' => 'Начните вводить имя друга или название сообщества'
    ],
    'names' => [],
    'media' => [
        ["photo", "Фотографию"],
        ["video", "Видеозапись"],
        ["audio", "Аудиозапись"]
    ],
    'share' => [
        'url'      => '/badbrowser.php',
        'hash'     => $csrfToken,
        'rhash'    => '',
        'timehash' => ''
    ]
];

return $ee13vars->ajax(0, [$list_id, $count, $offset, $data, $opts]);