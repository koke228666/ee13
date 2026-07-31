<?php
$cancel = tr('cancel');
return $ee13vars->ajax(0, [tr('caution'), '<div class="profile_photo_prev">' . $ee13vars->get_lang('sure_delete_profile_photo') . '</div>',
"box.removeButtons().addButton('$cancel', box.hide, 'no');
box.addButton('Удалить', function() {
  ajax.post('al_profile.php', {
    act: 'delete_photo',
    hash: cur.options.photo_hash
  }, {
    showProgress: box.showProgress,
    hideProgress: box.hideProgress
  });
  nav.go(cur.options.loc, {}, { noback: true });
  curBox().hide();
});
"]);