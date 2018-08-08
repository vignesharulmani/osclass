<?php
if(!defined('ABS_PATH')) {
  define('ABS_PATH', dirname(dirname(dirname(__FILE__))) . '/');
}


// COOKIES WORK
if(!function_exists('mb_set_cookie')) {
  function mb_set_cookie($name, $val) {
    Cookie::newInstance()->set_expires( 86400 * 30 );
    Cookie::newInstance()->push($name, $val);
    Cookie::newInstance()->set();
  }
}


if(!function_exists('mb_get_cookie')) {
  function mb_get_cookie($name) {
    return Cookie::newInstance()->get_value($name);
  }
}


if(!function_exists('mb_drop_cookie')) {
  function mb_drop_cookie($name) {
    Cookie::newInstance()->pop($name);
  }
}


function fi_make_favorite( $item_id = NULL ) {
  if($item_id == '') {
    $item_id = osc_item_id();
  }

  if($item_id == '') {
    $item_id = osc_premium_id();
  }


  if(osc_is_web_user_logged_in()) {
    $user_id = osc_logged_user_id();
  } else {
    $user_id = mb_get_cookie('fi_user_id');
  }


  $check = ModelFI::newInstance()->getFavoriteAll($item_id, $user_id);


  if(isset($check['record_id']) and $check['record_id'] <> '') {
    $class = ' is_favorite';
    $title = __('Favorited', 'favorite_items');
  } else {
    $class = '';
    $title = __('Make favorite', 'favorite_items');
  }

  $text  = '';
  $text .= '<a href="javascript://" class="fi_make_favorite fi_fav_' . $item_id . $class . '" rel="' . $item_id . '" title="' . osc_esc_html($title) . '">';
  $text .= '<span></span>';
  $text .= '</a>';

  return $text;
}
   

function fi_generate_rand_int($length = 18) {
  $characters = '0123456789';
  $charactersLength = strlen($characters);
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
    $randomString .= $characters[rand(0, $charactersLength - 1)];
  }

  return $randomString;
}


// IF USER IS NOT LOGGED IN, USE RANDOM USER_ID THAT IS SAVED IN USER COOKIES
if(mb_get_cookie('fi_user_id') == '' or mb_get_cookie('fi_user_id') <= 0) {
  mb_set_cookie('fi_user_id', fi_generate_rand_int());
} else {
  // KEEP ID IN COOKIES AS LONG AS POSSIBLE
  $current_id = mb_get_cookie('fi_user_id');
  mb_set_cookie('fi_user_id', $current_id);
}


// SHOW LISTINGS FROM CURRENTLY ACTIVE FAVORITE LIST
function fi_list_items( $list_id = NULL ) {
  if(osc_is_web_user_logged_in()) {
    $user_id = osc_logged_user_id();
  } else {
    $user_id = mb_get_cookie('fi_user_id');
  }

  if($list_id == '' or $list_id <= 0) {
    $list = ModelFI::newInstance()->getCurrentFavoriteListByUserId( $user_id );
  } else {
    $list = ModelFI::newInstance()->getFavoriteListById( $list_id );
  }


  // SEARCH ITEMS IN LIST AND CREATE ITEM ARRAY
  if(isset($list['list_id'])) {
    $iSearch = new Search();
    $iSearch->addConditions(sprintf("%st_favorite_items.list_id = %d", DB_TABLE_PREFIX, $list['list_id']));
    $iSearch->addConditions(sprintf("%st_favorite_items.item_id = %st_item.pk_i_id", DB_TABLE_PREFIX, DB_TABLE_PREFIX));
    $iSearch->addTable(sprintf("%st_favorite_items", DB_TABLE_PREFIX));
    $list_items = $iSearch->doSearch();
  }


  // EXPORT FAVORITE ITEMS TO VARIABLE
  GLOBAL $fi_global_items;
  $fi_global_items = View::newInstance()->_get('items') ;                 //save existing item array
  View::newInstance()->_exportVariableToView('items', $list_items);    //exporting our searched item array
    
  require_once 'user/list_items.php';
  
  GLOBAL $fi_global_items;                                                //calling stored item array
  View::newInstance()->_exportVariableToView('items', $fi_global_items);  //restore original item array
}



// SHOW LIST OF MOST FAVORITED LISTINGS
function fi_most_favorited_items( $limit = 5 ) {

  // SEARCH ITEMS IN LIST AND CREATE ITEM ARRAY
  $aSearch = new Search();
  $aSearch->addField(sprintf('count(%st_item.pk_i_id) as count_id', DB_TABLE_PREFIX) );
  $aSearch->addConditions(sprintf("%st_favorite_list.list_id = %st_favorite_items.list_id", DB_TABLE_PREFIX, DB_TABLE_PREFIX));
  $aSearch->addConditions(sprintf("%st_favorite_items.item_id = %st_item.pk_i_id", DB_TABLE_PREFIX, DB_TABLE_PREFIX));
  $aSearch->addConditions(sprintf("%st_favorite_list.user_id <> coalesce(%st_item.fk_i_user_id, 0)", DB_TABLE_PREFIX, DB_TABLE_PREFIX));
  $aSearch->addTable(sprintf("%st_favorite_items", DB_TABLE_PREFIX));
  $aSearch->addTable(sprintf("%st_favorite_list", DB_TABLE_PREFIX));
  $aSearch->addGroupBy(DB_TABLE_PREFIX.'t_item.pk_i_id');

  $aSearch->order('count(*)', 'DESC');

  $aSearch->limit( 0, $limit );
  $list_items = $aSearch->doSearch();


  // EXPORT FAVORITE ITEMS TO VARIABLE
  GLOBAL $fi_global_items2;
  $fi_global_items2 = View::newInstance()->_get('items') ;                 //save existing item array
  View::newInstance()->_exportVariableToView('items', $list_items);    //exporting our searched item array
    
  require_once 'user/most_favorited_items.php';

  
  GLOBAL $fi_global_items2;                                                //calling stored item array
  View::newInstance()->_exportVariableToView('items', $fi_global_items2);  //restore original item array
}


// FORMAT PRICE FOR NEED OF THIS PLUGIN
function fi_price_format($price, $symbol_code) {
  if ($price === null) { 
    $final_price = osc_apply_filter('item_price_null', __('Check with seller', 'favorite_items')); 
  } else if ($price == 0) {
    $final_price = osc_apply_filter('item_price_zero', __('Free', 'favorite_items')); 
  } else {

    $final_price = $price/1000000;

    $aCurrency = Currency::newInstance()->findByPrimaryKey($symbol_code);

    if(isset($aCurrency['s_description'])) {
      $symbol = $aCurrency['s_description'];
    }

    $currencyFormat = osc_locale_currency_format();
    $currencyFormat = str_replace('{NUMBER}', number_format($final_price, osc_locale_num_dec(), osc_locale_dec_point(), osc_locale_thousands_sep()), $currencyFormat);
    $currencyFormat = str_replace('{CURRENCY}', $symbol, $currencyFormat);

    $final_price = $currencyFormat;
  }

  return $final_price;
}

?>