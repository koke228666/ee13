<?php
ob_start();
?>
<div class="tail_wrap">
    <div class="tail"></div>
</div>
<div class="audio_status_wrap">
    <div class="hint_title">Трансляция аудиозаписей</div>
    <div class="hint_description">
        Выберите, куда Вы хотите транслировать проигрываемые аудиозаписи.
    </div>
    <div class="hint_clubs">
        <div class="checkbox status<?php echo $thisUser->getId() ?>" onclick="audioPlayer.updateStatus(this, <?php echo $thisUser->getId() ?>);">
            <div></div>На мою страницу
        </div>
    </div>
</div>
<div class="audio_share_cont" style="display: none;">
    <a class="audio_share_link" onclick="audioPlayer.shareMusic()">Отправить другу</a>
</div>
<?php
$status_tt = ob_get_clean();
return $ee13vars->ajax(0, [1, $status_tt], ['newStatic' => 'audio.js,audio.css,notifier.js']);