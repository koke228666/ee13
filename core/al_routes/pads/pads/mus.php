<?php
ob_start();
?>
<div id="pad_controls">
    <div id="pd">
        <div class="ac_wrap clear_fix">
            <div id="pd_controls" class="fl_l">
                <div id="pd_play" class="fl_l"></div>
                <div class="next_prev fl_l">
                    <div id="pd_prev" class="ctrl_wrap">
                        <div class="prev ctrl"></div>
                    </div>

                    <div id="pd_next" class="ctrl_wrap">
                        <div class="next ctrl"></div>
                    </div>
                </div>
            </div>

            <div class="info fl_l">
                <div class="title_wrap clear_fix">
                    <div id="pd_name" class="ac_name fl_l">
                        <span id="pd_performer" class="performer"><?php echo tr('track_unknown'); ?></span>
                        &ndash;
                        <span id="pd_title" class="title"><?php echo tr('track_noname'); ?></span>
                    </div>

                    <span id="pd_duration" class="duration fl_r"></span>
                </div>

                <div class="player_wrap">
                    <div id="pd_line">
                        <div></div>
                    </div>

                    <div id="pd_player" class="ac_player" ondragstart="return false;" onselectstart="return false;">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tbody>
                                <tr>
                                    <td style="width: 100%;">
                                        <div id="pd_pr">
                                            <div id="pd_white_line" class="ac_white_line"></div>
                                            <div id="pd_back_line" class="ac_back_line"></div>
                                            <div id="pd_load_line" class="ac_load_line"></div>
                                            <div id="pd_pr_line" class="ac_progress_line">
                                                <div id="pd_pr_slider" class="ac_slider"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div id="pd_vol">
                                            <div id="pd_vol_white_line" class="ac_vol_white_line"></div>

                                            <div id="pd_vol_back_line" class="ac_load_line"></div>

                                            <div id="pd_vol_line" class="ac_progress_line">
                                                <div id="pd_vol_slider" class="ac_slider"></div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="extra_ctrls fl_r">
                <div style="display: none;" id="pd_add" class="ctrl_wrap clear_fix fl_l">
                    <div class="add ctrl"></div>
                </div>

                <div id="pd_repeat" class="ctrl_wrap clear_fix fl_l">
                    <div class="repeat ctrl"></div>
                </div>

                <div id="pd_shuffle" class="ctrl_wrap clear_fix fl_l">
                    <div class="shuffle ctrl"></div>
                </div>

                <div id="pd_rec" class="ctrl_wrap clear_fix fl_l">
                    <div class="rec ctrl"></div>
                </div>

                <div id="pd_status" class="ctrl_wrap clear_fix fl_l">
                    <div class="status ctrl"></div>
                </div>
            </div>
        </div>

        <div class="shadow">
            <div class="sh1"></div>
            <div class="sh2"></div>
            <div class="sh3"></div>
            <div class="sh4"></div>
        </div>
    </div>
</div>

<div id="pad_content">
    <div id="pad_rows" class="pad_mus_rows">
        <div id="pad_audio_search" class="clear_fix">
            <div class="fl_l">
                <input id="pad_search" class="pad_search text" type="text" value="" placeholder="<?php echo tr('audio_search'); ?>" onpaste="Pads.audioSearch(this);" onkeyup="Pads.audioSearch(this);">
            </div>

            <div class="pad_progress fl_l"></div>

            <div id="pad_audio_reset" class="pad_reset fl_l" onmouseover="if (isVisible(this)) animate(this, {opacity: 1}, 100);" onmouseout="if (isVisible(this)) animate(this, {opacity: .6}, 100);" onclick="Pads.audioClear(); return false;"></div>

            <div class="abutton_wrap fl_l">
                <div class="button_blue">
                    <button id="pad_search_type">
                        <?php echo tr('by_name'); ?><span class="arrow"></span>
                    </button>
                </div>
            </div>
        </div>

        <div id="pad_playlist" class="clear_fix"></div>

        <a id="pad_more_audio" onclick="Pads.audioShowMore(); return false;">
            <?php echo tr('show_more_audios'); ?>
        </a>
    </div>
</div>

<div id="pad_footer_wrap" class="music">
    <div id="pad_footer" class="clear_fix">
        <div class="pad_close_btn button_blue fl_r">
            <button onclick="Pads.hide(); return false;">
                <?php echo tr('close'); ?>
            </button>
        </div>
        <div id="pad_footer_text">
            <a id="pad_footer_link" href="friends1"></a>
        </div>
    </div>
</div>
<?php
$player_layout = ob_get_clean();
ob_start();
?>
Pads.audioTpl = '<div class="audio %rowclass% fl_l" id="audio%audio_id%" onmouseover="addClass(this, \'over\');" onmouseout="removeClass(this, \'over\');">' +
    '<a name="%audio_id%"></a>' +
    '<div class="area clear_fix" onclick="if (cur.cancelClick) { cur.cancelClick = false; return; } %onclick%">' +
        '<div class="play_btn fl_l">' +
            '<div class="play_btn_wrap"><div class="play_new" id="play%audio_id%"></div></div>' +
            '<input type="hidden" id="audio_info%audio_id%" value="%url%,%playtime%">' +
        '</div>' +
        '<div class="info fl_l">' +
            '<div class="title_wrap fl_l" onmouseover="setTitle(this);">' +
                '<b><a %attr%>%performer%</a></b> &ndash; ' +
                '<span class="title">%title%</span>' +
                '<span class="user" onclick="cur.cancelClick = true;">%author%</span>' +
            '</div>' +
            '<div class="actions">%actions%</div>' +
            '<div class="duration fl_r">%duration%</div>' +
        '</div>' +
    '</div>' +
'</div>';

// [ДАННЫЕ УДАЛЕНЫ]

<?php
$init_script = ob_get_clean();

return $ee13vars->ajax(0, [
    $player_layout,
    $init_script,
    (object)[]],
    ["newStatic" => "pads.css,pads.js,notifier.js,audioplayer.css,audioplayer.js,sorter.js,audio.js,audio.css,ui_controls.css,ui_controls.js,indexer.js"
]);
