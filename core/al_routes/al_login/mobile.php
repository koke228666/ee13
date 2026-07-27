<?php
ob_start();
?>
<div id="login_mobile_wrap">
    <div class="login_mobile_promo">
        <div class="login_loggedout_header">Бобильная версия</div>
        <div class="login_about_mobile">
            пиздец вконтакте для бобилок вот ведь кто-то угар придумал лол
        </div>
    </div>
</div>
<?php
$bobile = ob_get_clean();
return $ee13vars->ajax(0, ["", $bobile, 'stManager.add(["login.css"])']);