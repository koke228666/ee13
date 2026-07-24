## ee13
_experimental13_ - тема для [OpenVK](https://github.com/openvk/openvk), которая использует ресурсы и вёрстку ВКонтакте за ~2013 год.

#### как это ваще работает
в файле _[ee13_vars.php](core/ee13_vars.php)_ лежат функции для упрощения генерации ответов

_[badbrowser.php](tpl/About/BB.latte)_ был изменён под получение ajax запросов от скриптов

#### создание ответов
php обработчики ajax запросов лежат в `ee13/core/al_routes/$target/$act.php`

посмотреть значения для $target и $act вы можете в запросах на `/badbrowser.php` и в ошибках, которые появляются, если метод отсутствует:
```
неизвестное действие!!!
target: al_profile
act: delete_photo_box
```

типичный файл выглядит примерно так:
```
// /al_photos/viewer_color.php
<?php
$dark = $_REQUEST['dark'];
if ($dark === '' || $dark === '1') {
    $ee13vars->cookie("pv_dark", ($dark === '') ? '0' : '1');
    return $ee13vars->ajax(0); // пустой ответ с успехом
} else {
    return $ee13vars->ajax(8, ["ты ахуел"]); // произошла некоторая ошибка
}
```

в функцию `$ee13vars->ajax()` вы можете передать всё, что хотите, но учтите, что данные должны соответствовать тому, что хочет скрипт клиента.

типичные примеры:
```$ee13vars->ajax(0, []); // успех
$ee13vars->ajax(7, ["норм"]); // уведомление
$ee13vars->ajax(8, ["пиздец"]); // ошибка
```

больше кодов можно глянуть в _[ee13_vars.php](core/ee13_vars.php)_
