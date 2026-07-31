<?php
$requests_count = $thisUser->getRequestsCount();
$requests = $thisUser->getRequests(1, 6);

$html = '<div id="pad_title_wrap" class="clear_fix">
    <a id="pad_title_right" class="fl_r" href="/friends' . $thisUser->getId() . '">
        ' . tr("all_friends") . '
    </a>
    <div id="pad_title">
        ' . tr("req", $requests_count) . '
    </div>
</div>
<div id="pad_content">
    <div id="pad_rows">';

foreach ($requests as $request) {
    $id = $request->getId();
    $url = htmlspecialchars($request->getURL());
    $name = htmlspecialchars($request->getFullName());

    $html .= '<div id="pad_fr' . $id . '" class="pad_fr clear_fix">
    <div class="pad_fr_phwrap fl_l">
        <a href="' . $url . '">
            <img class="pad_fr_img" src="' . $request->getAvatarUrl('miniscule') . '" alt="' . $name . '">
        </a>
    </div>

    <div class="pad_fr_info">
        <div class="pad_fr_name">
            <a class="pad_fr_lnk" href="' . $url . '">' . $name . '</a>
        </div>

        <div class="pad_fr_btns">
            <div id="request_controls_' . $id . '">
                <div class="button_blue fl_l">
                    <button id="accept_request_' . $id . '" onclick="Pads.frProcess(this, ' . $id . ', 1); return false;">'
                    . tr('friends_add') . '</button>
                </div>
                <div class="button_cancel pad_decl fl_l">
                    <div class="button" id="deny_request_' . $id . '" onclick="Pads.frProcess(this, ' . $id . ', -1); return false;">'
                    . tr('decline_suggested') . '</div>
                </div>
            </div>
        </div>
    </div>
</div>';
}

$html .= '</div>
</div>

<div id="pad_footer_wrap">
    <div id="pad_footer" class="clear_fix">
        <div class="button_gray pad_close_btn fl_r">
            <button onclick="Pads.hide(); return false;">
                ' . tr('close') . '
            </button>
        </div>
    </div>
</div>';

$script = "
window.lang = extend(window.lang || {}, {
  global_photo_full_size: 'Увеличить'
});
_pads.hash = '{$csrfToken}';
";

return $ee13vars->ajax(0, [
    $html,
    $script,
    (object) [],
], ['newStatic' => 'pads.css,pads.js,notifier.js,friends.css']);