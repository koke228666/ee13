<?php
ob_start();
?>
<div class="mott_wrap">
    <div class="mott_content">
        <div class="mott_header">
            <a onclick="mobilePromo()"><?php echo $ee13vars->get_lang("mobile_header"); ?></a>
        </div>
        <div class="mott_text"><?php echo $ee13vars->get_lang("mobile_text"); ?></div>
    </div>
</div>
<?php
$bobile_tt = ob_get_clean();
return $ee13vars->ajax(0, [$bobile_tt, 'stManager.add(["tooltips.css"])']);