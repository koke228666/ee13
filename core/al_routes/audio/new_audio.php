<?php
ob_start();
?>
<div id="audio_new_cont" class="audio_new">
  <div class="error" id="audio_new_error"></div>
  <div id="restrictions" class="restrictions"><?php echo tr('limits'); ?></div>
  <ul class="listing">
    <li><span><?php echo tr("audio_requirements", 1, 30, 25); ?></span></li>
    <li><span><?php echo tr('audio_requirements_2'); ?></span></li>
  </ul>
  <div id="audio_choose_upload" class="audio_choose_upload"></div>
</div>

<div id="choose_audio_dropbox" class="dropbox">
  <div class="dropbox_wrap">
    <div class="dropbox_area">
      <div class="dropbox_label"><?php echo tr('drag_files_here'); ?></div>
    </div>
  </div>
</div>
<?php
$new_audio = ob_get_clean();
ob_start();
?>

cur.loadAudioDone = function(i, obj) {
  if (cur.module != 'audio') {
    if (cur.boxClose) {
      nav.go('/audios' + obj[0], false, {onDone: function() {
        cur.__phinputs = cur.__phinputs || [];
        globalHistoryDestroy(nav.objLoc[0]);
      }});
    }
    return;
  }

  setTimeout(function(){
    var all_list = cur.audiosList['all'];
    if (all_list && all_list.length) {
      obj._order = all_list[0]._order - 1;
      cur.audiosList['all'].splice(0,0,obj);
    } else {
      obj._order = 0;
      cur.audiosList['all'] = [obj];
    }
    cur.audios[obj[1]] = obj;
    cur.audiosIndex.add(obj);
    removeClass(ge('audio_wrap'), 'audio_no_recs');
    show('audio_create_album');
    cur.ignoreEqual = 1;
    Audio.updateList({force: true});
  }, 0);
  if (cur.audiosLoaded) {
    cur.audiosLoaded++;
  } else {
    cur.audiosLoaded = 1;
  }
  if (cur.boxClose) {
    delete(cur.boxClose);
    var msg = (cur.audiosLoaded > 1) ? 'Все аудиозаписи успешно загружены.' : '<?php echo tr('audio_successfully_uploaded'); ?>';
    // var msg = (cur.audiosLoaded > 1) ? 'Все аудиозаписи успешно загружены.' : 'Аудиозапись успешно загружена.';
    box.setOptions({title: 'Загрузка завершена', bodyStyle: 'padding: 16px 14px;'});
    box.content(msg);
    hide(box.progress);
    setTimeout(function(){box.hide(200);}, 2000)
    delete(cur.audiosLoaded);
    if (_tbLink && _tbLink.loc) {
      cur.__phinputs = cur.__phinputs || [];
      globalHistoryDestroy(_tbLink.loc);
    }
  }
};
cur.loadAudioFailed = function(i, code) {
  hide(box.progress);
  Upload.embed(i);
  var err = ge('audio_new_error');
  var msg = code.split('|');
  code = msg[0];
  msg = msg[1];
  err.innerHTML = msg ? msg : 'Возникла ошибка, код ошибки: {code}'.replace('{code}', intval(code));
  show(err);
};

Upload.init('audio_choose_upload', '/badbrowser.php', {
  "target": "upload.php",
  "act": "load_audio",
  "aid": 0,
  "gid": 0,
  "mid": 216946,
  "server": "",
  "hash": "<?php echo $csrfToken ?>",
  "rhash": "rhash",
  "vk": 1,
  "upldr": 1
}, {
  file_name: 'file',
  file_size_limit: 20971520,
  file_types_description: 'Audio files (*.mp3)',
  file_types: '*.mp3;*.MP3',
  accept: 'audio/mpeg',

  lang: {"button_browse":"Выбрать файл",
  "switch_mode":"Если у Вас возникли проблемы с загрузкой файла, воспользуйтесь {link}стандартным{\/link} загрузчиком.",
  "cannot_upload_title":"Ошибка",
  "cannot_upload_text":"Загрузка аудиозаписей в данный момент недоступна. Попробуйте повторить операцию позже.",
  "max_files_warning":"Вы не можете загрузить более 20 аудиозаписей за раз."},

  onUploadStart: function(i, res) {
    if (Upload.types[i] == 'form') {
      show(box.progress);
    }
    if (Upload.types[i] == 'form' || Upload.types[i] == 'fileApi') geByClass1('file', ge('audio_choose_upload')).disabled = true;
    curBox().changed = true;
  },
  onUploadProgress: function(i, bytesLoaded, bytesTotal) {
    if (!ge('form'+i+'_progress')) {
      var obj = Upload.obj[i], objHeight = getSize(obj)[1], tm = objHeight / 2 + 10;
      var node = obj.firstChild;
      while (node) {
        if (node.nodeType == 1) {
          if (node.id == 'uploader'+i && browser.msie) {
            setStyle(node, {position: 'relative', left: '-5000px'});
          } else {
            setStyle(node, {visibility: 'hidden'});
          }
        }
        node = node.nextSibling;
      }
      obj.appendChild(ce('div', {innerHTML: '<div class="audio_progress_wrap"><div id="form' + i + '_progress" class="audio_progress" style="width: 0%;"></div></div></div>'
      }, {height: tm + 'px', marginTop: -tm + 'px'}));
    }
    var percent = intval(bytesLoaded / bytesTotal * 100);
    setStyle(ge('form' + i + '_progress'), {width: percent + '%'});
  },
  onUploadComplete: function(i, res) {
    var obj;
    res = res.replace(/\+/g, ' ');
    try {
      obj = eval('(' + res + ')');
    } catch(e) {
      obj = q2ajx(res);
    }
    obj.title = decodeURIComponent(obj.title);
    obj.artist = decodeURIComponent(obj.artist);
    if (obj.code) {
      cur.loadAudioFailed(i, obj.code);
    } else {
      ajax.post(Audio.address, extend(obj, {act: 'done_add', upldr: 1}), {
        onDone: function(res) {
          cur.loadAudioDone(i, res);
        },
        onFail: function(res) {
          cur.loadAudioFailed(i, res);
          return true;
        }
      });
    }
  },
  onUploadCompleteAll: function(i, res) {
    cur.boxClose = true;
    // show(box.progress);
  },
  onUploadError: cur.loadAudioFailed,

  clear: 1,
  multiple: 1,
  force_max_files: true,
  max_files: 20,
  type: 'audio',
  max_attempts: 3,
  reverse_files: 1,
  server: '',
  error: 1,
  error_hash: '<?php echo $csrfToken ?>',
  dropbox: 'choose_audio_dropbox'
});
// box.setControlsText('<a href="/audios">Добавить из поиска</a>');
box.setControlsText('<a href="/audios"><?php tr('audio_search'); ?></a>');

<?php
$new_audio_script = ob_get_clean();

return $ee13vars->ajax(0, [tr('select_audio'), $new_audio, $new_audio_script], ['newStatic' => 'audio.css,audio.js,upload.js']);