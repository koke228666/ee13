<?php
ob_start();
?>
<div class="gifts_profile_block">
  <div class="gifts_profile_cont clear_fix">

    <div class="gifts_profile_list_wrap fl_l">
      <div class="gifts_profile_list clear_fix" id="gifts_profile_list">
        <a class="gifts_subtab1 active">
          <span class="gifts_subtab2">Все подарки</span>
        </a>

        <div class="clear_fix">
          <a class="gift_profile_cell fl_l">
            <img width="50" height="50" src="/gift1_1783638251.png">
          </a>

          <a class="gift_profile_cell fl_l">
            <img width="50" height="50" src="/gift1_1783638251.png">
          </a>

          <a class="gift_profile_cell fl_l">
            <img width="50" height="50" src="/gift1_1783638251.png">
          </a>
        </div>
      </div>

      <div class="list_shadow shadow_top"></div>
      <div class="list_shadow shadow_bottom"></div>
    </div>

    <div class="gifts_profile_send fl_l">
      <div class="gifts_profile_header">Send a gift</div>

      <div class="gifts_profile_gift">
        <img id="gifts_selected_img" width="100" height="100" src="/gift1_1783638251.png">
      </div>

      <div class="gifts_profile_price" id="gifts_selected_price"></div>

      <div class="gifts_profile_message">
        <textarea id="gifts_message"></textarea>
      </div>

      <div class="checkbox" id="receiver_only"
           onclick="checkbox(this);">
        <div></div>
        Only for the recipient
      </div>

      <div id="gifts_buttons">
        <div class="button_blue">
          <button id="gifts_submit"
                  onclick="return Profile.giftSend();">
            Send
          </button>
        </div>
      </div>
    </div>

  </div>

  <input type="hidden" id="gifts_selected_num" value="1">
  <input type="hidden" id="gifts_hash" value="">
</div>
<?php
$gift_tooltip = ob_get_clean();

$script = "
// TODO
";

return $ee13vars->ajax(0, [$gift_tooltip, $script]);