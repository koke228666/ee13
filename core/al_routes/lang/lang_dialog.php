<?php
if ($_REQUEST['act']) {
    if ($_REQUEST['act'] === 'lang_dialog') {
        if (!empty($_REQUEST['all']) && $_REQUEST['all'] == 1) {
            return $ee13vars->ajax(8, ["nope"]);
        } else {
    $cur_lang = getLanguage();
    ob_start();

    $langs = [
        'ru' => ['id' => 0, 'name' => 'Русский'],
        'en' => ['id' => 3, 'name' => 'English'],
        'uk' => ['id' => 1, 'name' => 'Українська']
    ];

    if ($cur_lang === 'en') {
        $order = ['en', 'ru', 'uk'];
    } elseif ($cur_lang === 'uk') {
        $order = ['uk', 'en', 'ru'];
    } else {
        $order = ['ru', 'en', 'uk'];
    }

    foreach ($order as $code) {
        if (!isLanguageAvailable($code)) {
            continue;
        }
        $lang = $langs[$code];
        ?>
<a href="#" class="<?php echo ($code === $cur_lang) ? "lang_box_row lang_selected" : "lang_box_row"; ?>" onclick="<?php echo ($code === $cur_lang) ? "return false;" : "return doChangeLang({$lang['id']}, '{$csrfToken}')"; ?>" style="background-image:url(<?php echo $ee13vars->get_resource($theme, "/images/lang_flags/{$lang['id']}.png?1"); ?>); background-size: 34px 26px;">  <?php echo $lang['name']; ?></a>
        <?php
    }
    ?>
<a href="#" class="lang_box_row" onclick="if (vk.al) { curBox().hide(); showBox('lang.php', {act: 'lang_dialog', all: 1}, {params: {dark: true, bodyStyle: 'padding: 0px'}, noreload: true}); } else { changeLang(1); } return false;">  Other languages »</a>

<?php
    $langbox_small = ob_get_clean();
            return $ee13vars->ajax(0, ["", $langbox_small, ""], ['newStatic' => 'uncommon.css']);
        }
    }
}