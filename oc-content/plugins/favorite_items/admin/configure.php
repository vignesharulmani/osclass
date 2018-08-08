<?php

$pluginInfo = osc_plugin_get_info('favorite_items/index.php');

$dao_preference = new Preference();


// REFRESH & FILL ALL VARIABLES
if(Params::getParam('reenable') == 1) {
  fi_call_after_install(1);
  message_ok(__('Variables has been successfully refreshed. Missing variables were created.', 'favorite_items'));
}


$max_per_list = '';
if(Params::getParam('max_per_list') != '' or Params::getParam('plugin_action') == 'done') {
  $max_per_list = Params::getParam('max_per_list');
} else {
  $max_per_list = (osc_get_preference('fi_max_per_list', 'plugin-fi') != '') ? osc_get_preference('fi_max_per_list', 'plugin-fi') : 24;
}


$per_page = '';
if(Params::getParam('per_page') != '' or Params::getParam('plugin_action') == 'done') {
  $per_page = Params::getParam('per_page');
} else {
  $per_page = (osc_get_preference('fi_per_page', 'plugin-fi') != '') ? osc_get_preference('fi_per_page', 'plugin-fi') : 8;
}


$quick_message = '';
if(Params::getParam('quick_message')=='on') {
  $quick_message = 1;
} else {
  if(Params::getParam('plugin_action')=='done') {
    $quick_message = 0;
  } else {
    $quick_message = (osc_get_preference('fi_quick_message', 'plugin-fi') != '') ? osc_get_preference('fi_quick_message', 'plugin-fi') : 1;
  }
}



// SAVE PARAMETER SETTINGS INTO DATABASE
if(Params::getParam('plugin_action')=='done') {
  $dao_preference->update(array("s_value" => $max_per_list), array("s_section" => "plugin-fi", "s_name" => "fi_max_per_list"));
  $dao_preference->update(array("s_value" => $per_page), array("s_section" => "plugin-fi", "s_name" => "fi_per_page"));
  $dao_preference->update(array("s_value" => $quick_message), array("s_section" => "plugin-fi", "s_name" => "fi_quick_message"));

  osc_reset_preferences();

  message_ok(__('Settings for Favorite Items Plugin saved.', 'favorite_items'));
}

unset($dao_preference);

?>

<div id="settings_form" class="items">
  <?php echo fi_config_menu(); ?>

  <form name="promo_form" id="promo_form" action="<?php echo osc_admin_base_url(true); ?>" method="POST" enctype="multipart/form-data" >
    <input type="hidden" name="page" value="plugins" />
    <input type="hidden" name="action" value="renderplugin" />
    <input type="hidden" name="file" value="<?php echo osc_plugin_folder(__FILE__); ?>configure.php" />
    <input type="hidden" name="plugin_action" value="done" />
    <br />
    
    <fieldset class="round4">
      <legend class="blue round2"><?php _e('Global Settings','favorite_items'); ?></legend>

      <div class="title"><?php _e('List settings', 'favorite_items'); ?></div>
      <div class="del" style="margin:1px 0 10px 0;"></div>

      <input type="checkbox" id="quick_message" name="quick_message" <?php echo ($quick_message == 1 ? 'checked' : ''); ?>/>
      <label for="quick_message" class="check"><?php _e('Enable quick messages', 'favorite_items'); ?> <sup class="sup-go go1">(1)</sup></label>

      <div class="clear" style="margin:16px 0;"></div>

      <label for="max_per_list" class="normal short"><?php _e('Maximum listings in favorite list:', 'favorite_items'); ?></label> <input type="text" class="normal short-inp" id="max_per_list" name="max_per_list" value="<?php echo $max_per_list; ?>"/> <sup class="sup-go go2">(2)</sup>

      <div class="clear" style="margin:6px 0;"></div>

      <label for="per_page" class="normal short"><?php _e('Listings shown in 1 page:', 'favorite_items'); ?></label> <input type="text" class="normal short-inp" id="per_page" name="per_page" value="<?php echo $per_page; ?>"/> <sup class="sup-go go3">(3)</sup>


      <div class="clear" style="margin:16px 0;"></div><br/><br/><br/>


      <!-- CODE IMPLEMENTATION INSTRUCTIONS -->
      <div class="instructions">
        <strong><?php _e('Make Favorite button', 'favorite_items'); ?></strong><br />
        <?php _e('This code must be entere inside item loop. That means it needs to be placed inside loop starting with while(osc_has_items()){ or while(osc_has_items()){ or while(osc_has_custom_items()){', 'favorite_items'); ?><br />
        <div class="code" style="background:none;border:none;">
          <strong>&lt;?php echo fi_make_favorite(); ?&gt;</strong>
        </div>
        <br />

        <?php _e('Note that you can use this function also outside item loop, but in this case you need to enter (optional) parameter with ID of listing. So i.e. if you want to make button for listing id = 95, you will call this function like fi_make_favorite(95)', 'favorite_items'); ?><br />
      </div>

      <div class="clear" style="margin:6px 0;"></div><br />

      <div class="instructions">
        <strong><?php _e('Show items in current list', 'favorite_items'); ?></strong><br />
        <?php _e('Using this code you can show listings that are in current user favorite list. You can enter this code anywhere.', 'favorite_items'); ?><br />
        <div class="code" style="background:none;border:none;">
          <strong>&lt;?php fi_list_items(); ?&gt;</strong>
        </div>
      </div>

      <div class="clear" style="margin:6px 0;"></div><br />

      <div class="instructions">
        <strong><?php _e('Show block with most favorited listings', 'favorite_items'); ?></strong><br />
        <?php _e('Using this code you can show list of most favorited listings on your site. You can enter this code anywhere.', 'favorite_items'); ?><br />
        <div class="code" style="background:none;border:none;">
          <strong>&lt;?php fi_most_favorited_items(); ?&gt;</strong>
        </div>
        <br />
        <?php _e('By default, 5 listings is shown. You can enter (optional) parameter to increase or reduce this number. So i.e. if you want to show 10 most favorited items, you will call this function like fi_most_favorited_items(10).', 'favorite_items'); ?><br />
      </div>
    </fieldset>
    <br />

    <button name="theButton" id="theButton" type="submit" style="float: left;" class="btn btn-submit"><?php _e('Save settings', 'favorite_items');?></button>
  </form>

  <div class="clear"></div>
  <br /><br />

  <div class="warn"><sup class="sup-go1">(1)</sup> <?php _e('If enabled, quick informal messages will be shown to user in right top corner when adding item to favorite list or removing listing from list.', 'favorite_items'); ?></div>
  <div class="warn"><sup class="sup-go2">(2)</sup> <?php _e('Set maximum count of listings that can be added into 1 favorite list. If user reach this quota and will try to add next listing, this user will be informed that there is limit that has been reached.', 'favorite_items'); ?></div>
  <div class="warn"><sup class="sup-go3">(3)</sup> <?php _e('Set how many listings in list are shown on 1 page in user menu. I.e. if you set this value to 8 and there is 24 listings in selected list, user will be able to browse this listings on 3 pages (pagination).', 'favorite_items'); ?></div>
  <div class="warn"><?php _e('In list of most favorited listings, those items are shown in descending order by count how many people has favorited that listing. Note that listings, that are favorited by user, that is also owner of those listings, are not counted.', 'favorite_items'); ?></div>
  <div class="warn"><?php _e('If you have any problem with plugin, please before contacting us check if there are no javascript errors on your site caused by other plugins or theme itself.', 'favorite_items'); ?></div>


  <div class="clear"></div>
  <br /><br />
  <div class="clear"></div>

  <?php echo $pluginInfo['plugin_name'] . ' | ' . __('Version', 'favorite_items') . ' ' . $pluginInfo['version'] . ' | ' . __('Author', 'favorite_items') . ': ' . $pluginInfo['author'] . ' | Cannot be redistributed | &copy; ' . date('Y') . ' <a href="' . $pluginInfo['plugin_uri'] . '" target="_blank">MB Themes</a>'; ?>             
</div>