<?php
/*
  Plugin Name: Favorite Items
  Plugin URI: http://www.mb-themes.com
  Description: Add options to users to favorite listings
  Version: 1.0.4
  Author: MB Themes
  Author URI: http://www.mb-themes.com
  Author Email: info@mb-themes.com
  Short Name: favorite_items
  Plugin update URI: favorite-items-plugin
 */


require_once 'model/ModelFI.php';
require_once 'functions.php';
require_once 'email.php';


function fi_call_after_install($reenable = NULL) {
  if($reenable == '') {
    ModelFI::newInstance()->import('favorite_items/model/struct.sql');
  }

  osc_set_preference('fi_quick_message', 1, 'plugin-fi', 'INTEGER');
  osc_set_preference('fi_max_per_list', 24, 'plugin-fi', 'INTEGER');
  osc_set_preference('fi_per_page', 8, 'plugin-fi', 'INTEGER');


  // UPLOAD EMAIL TEMPLATES
  if($reenable == '') {
    foreach(osc_listLocales() as $loc) {

      // fi_email_price_change template
      $des_price_change[$loc['code']]['s_title'] = '{WEB_TITLE} - Price on listing #{ITEM_ID} has changed';
      $des_price_change[$loc['code']]['s_text'] = '<p>Hi {CONTACT_NAME}!</p> <p>Let us inform you, that price on your favorite listing: <strong>{ITEM_TITLE} (#{ITEM_ID})</strong> has been just changed.</p> <p>Old price: <strong>{OLD_PRICE}</strong><br />New price: <strong>{NEW_PRICE}</strong></p> <p></p> <p>You can check this listing on following URL:<br /><strong><span style="color: #1400ff;">{ITEM_URL}</span></strong></p> <p></p> <p>Regards, <br />{WEB_TITLE}</p>';

      // fi_email_item_remove template
      $des_item_remove[$loc['code']]['s_title'] = '{WEB_TITLE} - Your favorite listing #{ITEM_ID} has been removed';
      $des_item_remove[$loc['code']]['s_text'] = '<p>Hi {CONTACT_NAME}!</p> <p>Let us inform you, that listing you has favorited: <strong>{ITEM_TITLE} (#{ITEM_ID})</strong> has just been removed.</p> <p>Price on this listing was: <strong>{PRICE}</strong></p> <p></p> <p>This listing has not been actual, it has been sold or it has expired.</p> <p></p> <p>Regards, <br />{WEB_TITLE}</p>';

    }
    
    Page::newInstance()->insert( array('s_internal_name' => 'fi_email_price_change', 'b_indelible' => '1'), $des_price_change );
    Page::newInstance()->insert( array('s_internal_name' => 'fi_email_item_remove', 'b_indelible' => '1'), $des_item_remove );
  }
}

function fi_call_after_uninstall() {
  ModelFI::newInstance()->uninstall();

  osc_delete_preference('fi_quick_message', 'plugin-fi');
  osc_delete_preference('fi_max_per_list', 'plugin-fi');
  osc_delete_preference('fi_per_page', 'plugin-fi');


  // get list of primary keys of static pages (emails) that should be deleted on uninstall
  $pages = ModelFI::newInstance()->getPages();  
  foreach($pages as $page) {
    if(isset($page['pk_i_id'])) {
      Page::newInstance()->deleteByPrimaryKey($page['pk_i_id']);
    }
  }
}

if(!function_exists('message_ok')) {
  function message_ok( $text ) {
    $final  = '<div style="padding: 1%;width: 98%;margin-bottom: 15px;" class="flashmessage flashmessage-ok flashmessage-inline">';
    $final .= $text;
    $final .= '</div>';
    echo $final;
  }
}

if(!function_exists('message_error')) {
  function message_error( $text ) {
    $final  = '<div style="padding: 1%;width: 98%;margin-bottom: 15px;" class="flashmessage flashmessage-error flashmessage-inline">';
    $final .= $text;
    $final .= '</div>';
    echo $final;
  }
}


// WHEN UNREGISTERED USER LOG IN, CHANGE HIS ID TO LOG ID AND ALSO ASSOCIATE WISHLIST WITH IT USER ACCOUNT
function fi_check_logged() {
  $old_id = mb_get_cookie('fi_user_id');

  if(osc_is_web_user_logged_in()) {
    if($old_id >= 0 and $old_id <> '') {
      if(osc_logged_user_id() > 0 && osc_logged_user_id() <> $old_id) {
        $list = ModelFI::newInstance()->getCurrentFavoriteListByUserId(osc_logged_user_id());
        $list_current = ModelFI::newInstance()->getCurrentFavoriteListByUserId($old_id);

        // IF THERE ALREADY EXISTS LISTS FOR THIS LOGGED USER AND HAS CREATED NEW AS UNLOGGED, MAKE IT CURRENT
        if(count($list) > 0 && count($list_current) > 0) {
          ModelFI::newInstance()->updateAllListCurrentByUserId(osc_logged_user_id(), 0);
        }

        ModelFI::newInstance()->updateListToLogged($old_id, osc_logged_user_id(), 1);

        mb_set_cookie('fi_user_id', osc_logged_user_id());
      }
    }
  }


  // MAKE SURE UNLOGGED USER HAS NOT SAVED ID OF PREVIOUSLY LOGGED USER IN COOKIES
  if(!osc_is_web_user_logged_in()) {
    if(mb_get_cookie('fi_user_id') < 1000000000000) {
      mb_drop_cookie('fi_user_id');
    }
  }
}

osc_add_hook('init', 'fi_check_logged');


// WHEN USER LOGOUT, DROP ID FROM COOKIES
function fi_user_logout() {
  mb_drop_cookie('fi_user_id');
}

osc_add_hook('logout', 'fi_user_logout');



// CHECK IF PRICE HAS CHANGED WHEN ITEM WAS EDITED
function fi_check_edit_price( $item ) {
  $item_id = isset($item['idItem']) ? $item['idItem'] : 0;
  $item_price_new = isset($item['price']) ? $item['price'] : '';

  $new_formatted_price = fi_price_format($item_price_new, isset($item['currency']) ? $item['currency'] : '');

  $old_item = Item::newInstance()->findByPrimaryKey( $item_id );
  $item_price_old = isset($old_item['i_price']) ? $old_item['i_price'] : 0;

  $old_formatted_price = fi_price_format($item_price_old, isset($old_item['fk_c_currency_code']) ? $old_item['fk_c_currency_code'] : '');

  if($item_price_old <> $item_price_new) {

    $user_list = ModelFI::newInstance()->getUserListByItemId( $item_id );     // get all user emails that has this listing in favorite list

    foreach($user_list as $u) {
      // SEND EMAIL THAT PRICE HAS CHANGED
      if(isset($u['user_email']) && isset($u['user_name'])) {
        fi_email_price_change($u['user_email'], $u['user_name'], $item_id, $old_formatted_price, $new_formatted_price);
      }
    }
  }
}

osc_add_hook('pre_item_edit', 'fi_check_edit_price');



// SEND EMAIL WHEN LISTING IS REMOVED
function fi_item_remove( $item_id ) {
  $item_id = $item_id;

  $item = Item::newInstance()->findByPrimaryKey( $item_id );

  $item_price = isset($item['i_price']) ? $item['i_price'] : '';

  $formatted_price = fi_price_format($item_price, isset($item['fk_c_currency_code']) ? $item['fk_c_currency_code'] : '');


  $user_list = ModelFI::newInstance()->getUserListByItemId( $item_id );     // get all user emails that has this listing in favorite list

  foreach($user_list as $u) {
    // SEND EMAIL THAT PRICE HAS CHANGED
    if(isset($u['user_email']) && isset($u['user_name'])) {
      fi_email_item_remove($u['user_email'], $u['user_name'], $item_id, $formatted_price);
    }
  }
}

osc_add_hook('before_delete_item', 'fi_item_remove');




// LOAD SCRIPTS
osc_enqueue_style('fi_front_css', osc_base_url() . 'oc-content/plugins/favorite_items/css/front.css');
osc_enqueue_style('fi_font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css');
osc_register_script('fi_front_js', osc_base_url() . 'oc-content/plugins/favorite_items/js/front.js', 'jquery');
osc_enqueue_script('fi_front_js');


// LOAD VARIABLE
function fi_load_var() {
  echo PHP_EOL . '<script type="text/javascript">' . PHP_EOL . 'var fi_favorite_url = "' . osc_ajax_plugin_url('favorite_items/ajax.php') . '";' . PHP_EOL . 'var fi_empty = "' . __('You do not have any favorite listings', 'favorite_items') . '";' . PHP_EOL . '</script>' . PHP_EOL;   
}

osc_add_hook('footer', 'fi_load_var');


// ADD FAVORITE ITEMS LINK TO USER MENU
function fi_favorite_user_menu(){
  if(osc_current_web_theme() == 'zara') {
    echo '<li class="opt_favorite_items"><a href="' . osc_route_url('favorite-lists', array('list-id' => '0', 'current-update' => '0', 'notification-update' => '0', 'list-remove' => '0', 'iPage' => '0')) .'" ><i class="fa fa-star-o"></i> '.__('Favorite', 'favorite_items').'</a></li>'; 
  } else {
    echo '<li><a href="' . osc_route_url('favorite-lists', array('list-id' => '0', 'current-update' => '0', 'notification-update' => '0', 'list-remove' => '0', 'iPage' => '0')) .'" >'.__('Favorite items', 'favorite_items').'</a></li>'; 
  }
}

osc_add_route('favorite-lists', 'favorite-lists/([0-9]+)/([0-9]+)/([0-9]+)/([0-9]+)/([0-9]+)', 'favorite-lists/{list-id}/{current-update}/{notification-update}/{list-remove}/{iPage}', osc_plugin_folder(__FILE__) . 'user/user_menu_lists.php', true);


if(osc_current_web_theme() == 'zara') {
  osc_add_hook('user_menu_items', 'fi_favorite_user_menu');
} else {
  osc_add_hook('user_menu', 'fi_favorite_user_menu');
}


// ADMIN NAVIGATION MENU
function fi_config_menu() {
  echo '<link href="' . osc_base_url() . 'oc-content/plugins/favorite_items/css/admin.css" rel="stylesheet" type="text/css" />';
  echo '<script src="' . osc_base_url() . 'oc-content/plugins/favorite_items/js/admin.js"></script>';

  $text  = '';
  $text  = '<fieldset class="round4" style="padding: 0px 10px;">';
  $text .= '<ul class="conf-menu">';
  $text .= '<li class="gray round3">Favorite Items</li>';
  $text .= '<li class="blue round3"><a href="' . osc_base_url() . 'oc-admin/index.php?page=plugins&action=renderplugin&file=favorite_items/admin/configure.php">' . __('global settings', 'favorite_items') . '</a></li>';
  $text .= '<li class="reenable"><a href="' . osc_base_url() . 'oc-admin/index.php?page=plugins&action=renderplugin&file=favorite_items/admin/configure.php&reenable=1" title="' . osc_esc_html(__('This action set all settings to default. In case you have upgraded from lower version, it will add also missing variables so reinstall is not required', 'favorite_items')) . '" onclick="return confirm(\'' . osc_esc_js(__('This action set all settings to default. In case you have upgraded from lower version, it will add also missing variables so reinstall is not required. Are you sure you want to continue?', 'favorite_items')) . '\')"><i class="fa fa-refresh"></i></a></li>';
  $text .= '</ul>';
  $text .= '</fieldset>';
  $text .= '<br /><br />';
  $text .= '<div style="clear:both"></div>';
  return $text;
}


// Add menu link to Plugin list
function fi_admin_menu() {
echo '<h3><a href="#" style="font-weight:bold">Favorite Items</a></h3>
<ul> 
  <li><a style="color:blue;font-weight:bold" href="' . osc_admin_render_plugin_url(osc_plugin_path(dirname(__FILE__)) . '/admin/configure.php') . '">&raquo; ' . __('global settings', 'favorite_items') . '</a></li>
</ul>';
}

// Display configure link
function fi_conf() {
  osc_admin_render_plugin( osc_plugin_path( dirname(__FILE__) ) . '/admin/configure.php' ) ;
}

// This is a hack to show a Configure link at plugins table (you could also use some other hook to show a custom option panel)
osc_add_hook( osc_plugin_path( __FILE__ ) . '_configure', 'fi_conf' ) ;	

//Adding sub menu to plugins menu in dashboard
osc_add_hook('admin_menu','fi_admin_menu');

// This is needed in order to be able to activate the plugin
osc_register_plugin(osc_plugin_path(__FILE__), 'fi_call_after_install') ;

// This is a hack to show a Uninstall link at plugins table (you could also use some other hook to show a custom option panel)
osc_add_hook(osc_plugin_path(__FILE__) . '_uninstall', 'fi_call_after_uninstall');

?>