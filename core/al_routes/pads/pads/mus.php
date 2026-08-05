<?php
use openvk\Web\Models\Repositories\Audios;

$pad_audios = [];

foreach ((new Audios())->getByUser($thisUser, 1, 2147483647) as $audio) {
  $pad_audios[] = [
      $audio->getOwner()->getId(),
      $audio->getId(),
      $audio->getOriginalURL(),
      $audio->getLength(),
      $audio->getFormattedLength(),
      htmlspecialchars($audio->getPerformer()),
      htmlspecialchars($audio->getTitle()),
      !empty($audio->getLyrics()) ? $audio->getId() : 0,
      $audio->getAlbumId() ?: 0,
      0, '', 0, 1, 0,
      (int) $audio->canBeModifiedBy($thisUser),
  ];
}

$pad_audios_json = json_encode($pad_audios, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

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
          <a id="pad_footer_link" href="/audios<?php echo $thisUser->getId(); ?>" onclick="return Pads.go(this, event);">
              Перейти к списку аудиозаписей
          </a>
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

window.lang = extend(window.lang || {}, {
audio_add_to_audio: 'Добавить в мои аудиозаписи',
audio_repeat_tooltip: 'Повторять',
audio_shuffle: 'Перемешать',
audio_show_recommendations: 'Показать похожие',
audio_export_tip: 'Транслировать на мою страницу'
});

extend(Pads, {
audioInited: true,
audioPerPage: 25,
audioShown: 0,
audioSearchStr: '',
audioSearchType: 0,
showAudios: function(reset) {
  var cont = ge('pad_playlist'), html = [], part, au;
  if (reset) {
    Pads.audioShown = 0;
    cont.innerHTML = '';
  }
  part = Pads.audioList.slice(Pads.audioShown, Pads.audioShown + Pads.audioPerPage);
each(part, function() {
  var aid = this.full_id || (this[0] + '_' + this[1]);
  html.push(rs(Pads.audioTpl, {
    rowclass: 'no_actions',
    audio_id: aid + '_pad',
    onclick: 'playAudioNew(\'' + aid + '\');',
    url: this[2],
    playtime: this[3],
    duration: this[4],
    attr: 'href="/search?section=audios&only_performers=on&q=' + encodeURIComponent(this[5]) + '"onclick="cur.cancelClick = true; Pads.hide(); return nav.go(this, event);"',
    performer: this[5],
    title: this[6],
    author: '',
    actions: ''
  }));
});
  au = ce('div', {innerHTML: html.join('')});
  while (au.firstChild) cont.appendChild(au.firstChild);
  Pads.audioShown += part.length;
  toggle('pad_more_audio', Pads.audioShown < Pads.audioList.length);

    audioPlayer.genPlaylist(Pads.audioList);
    if (currentAudioId()) {
      audioPlayer.setSongInfo();
      if (audioPlayer.lastSong) audioPlayer.showCurrentTrack();
    }
    Pads.update();
    ge('pad_footer_link').href = '/' + padAudioPlaylist().address;
},
audioShowMore: function() {
  Pads.showAudios();
},
audioSearch: function(el) {
  var q = trim(val(el)).toLowerCase();
  if (q === Pads.audioSearchStr) return;
  Pads.audioSearchStr = q;
  Pads.audioSearchCleared = !q;
  toggle('pad_audio_reset', !!q);
  Pads.audioList = [];
  each(Pads.audioAll, function() {
    var s = (Pads.audioSearchType ? this[5] : this[5] + ' ' + this[6]).toLowerCase();
    if (!q || s.indexOf(q) != -1) Pads.audioList.push(this);
  });
  audioPlayer.genPlaylist(Pads.audioList);
  Pads.showAudios(true);
},
audioClear: function() {
  val('pad_search', '');
  Pads.audioSearch(ge('pad_search'));
},
updateAudioPlaylist: function(pl) {
pl = pl || cur.nextPlaylist || padAudioPlaylist();
var id = pl && pl.start, list = [];
while (id) {
  pl[id].full_id = id;
  list.push(pl[id]);
  id = pl[id]._next;
  if (id == pl.start) break;
}
Pads.audioAll = Pads.audioList = list;
Pads.showAudios(true);
},
onAudioReorder: function() {}
});

audioPlayer.initEvents();
audioPlayer.registerPlayer('pd', {
container: ge('pd'),
performer: ge('pd_performer'),
title: ge('pd_title'),
titleWrap: ge('pd_name'),
duration: ge('pd_duration'),
load: ge('pd_load_line'),
progress: ge('pd_pr_line'),
progressArea: ge('pd_pr'),
volume: ge('pd_vol_line'),
volumeArea: ge('pd_vol'),
play: ge('pd_play'),
prev: ge('pd_prev'),
next: ge('pd_next'),
add: ge('pd_add'),
repeat: ge('pd_repeat'),
shuffle: ge('pd_shuffle'),
rec: ge('pd_rec'),
status: ge('pd_status'),
padPlaylist: true,
fixed: 1
});

cur.padSearchTypes = [{i: 0, l: '<?php echo tr('by_name'); ?>'}, {i: 1, l: '<?php echo tr('by_performer'); ?>'}];
cur.padSearchTypeMenu = new DropdownMenu(cur.padSearchTypes, {
target: ge('pad_search_type'),
value: 0,
containerClass: 'dd_menu_stype_act',
updateTarget: false,
offsetLeft: 0,
offsetTop: 0,
showHover: false,
fadeSpeed: 0,
onSelect: function(event) {
  var i = event.target.index || 0, text = cur.padSearchTypes[i].l + '<span class="arrow"></span>';
  Pads.audioSearchType = i;
  cur.padSearchTypeMenu.header.innerHTML = '<div>' + text + '</div>';
  ge('pad_search_type').innerHTML = text;
  Pads.audioSearchStr = null;
  Pads.audioSearch(ge('pad_search'));
}
});

hide('pad_audio_reset');
if (padAudioPlaylist()) {
Pads.updateAudioPlaylist(padAudioPlaylist());
} else {
Pads.audioAll = Pads.audioList = padAudios;
audioPlayer.genPlaylist(Pads.audioAll);
audioPlayer.setPadPlaylist(clone(cur.nextPlaylist));
Pads.showAudios(true);
}


<?php
$init_script = ob_get_clean();

$on_load_script = "
var prevNext = cur.nextPlaylist;
Pads.audioAll = Pads.audioList = {$pad_audios_json};
audioPlayer.genPlaylist(Pads.audioAll);
audioPlayer.setPadPlaylist(clone(cur.nextPlaylist));
cur.nextPlaylist = prevNext;
if (window.onPlaylistLoaded) { onPlaylistLoaded(); delete window.onPlaylistLoaded; }
";

return $ee13vars->ajax(0, [
    $player_layout,
    $init_script,
    (object)['onLoadScript' => $on_load_script]],
    ["newStatic" => "pads.css,pads.js,notifier.js,audioplayer.css,audioplayer.js,sorter.js,audio.js,audio.css,ui_controls.css,ui_controls.js,indexer.js"
]);
