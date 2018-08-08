<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="<?php echo str_replace('_', '-', osc_current_user_locale()); ?>">
<head>
  <?php osc_current_web_theme_path('head.php') ; ?>

  <?php 
    // GET IF PAGE IS LOADED VIA QUICK VIEW
    $content_only = (Params::getParam('contentOnly') == 1 ? 1 : 0);
  ?>

  <?php 
    if(osc_item_price() == '') {
      $og_price = __('Check with seller', 'veronika');
    } else if(osc_item_price() == 0) {
      $og_price = __('Free', 'veronika');
    } else {
      $og_price = osc_item_price(); 
    }
  ?>

  <?php
    $item_extra = veronika_item_extra( osc_item_id() );
  ?>


  <?php
    $ios = false;
    if(strstr($_SERVER['HTTP_USER_AGENT'],'iPhone') || strstr($_SERVER['HTTP_USER_AGENT'],'iPod')) {
      $ios = true;
    }
  ?>


  <?php
    $location_array = array(osc_item_country(), osc_item_region(), osc_item_city());
    $location_array = array_filter($location_array);
    $item_loc = implode(', ', $location_array);
  ?>


  <?php 
    if(osc_item_user_id() <> 0) {
      $item_user = User::newInstance()->findByPrimaryKey(osc_item_user_id());
    }

    $mobile_found = true;
    $mobile = $item_extra['s_phone'];

    if($mobile == '' && osc_item_user_id() <> 0) { $mobile = $item_user['s_phone_mobile']; }      
    if($mobile == '' && osc_item_user_id() <> 0) { $mobile = $item_user['s_phone_land']; }      
    if($mobile == '') { $mobile = __('No phone number', 'veronika'); }   
   
    if(trim($mobile) == '' || strlen(trim($mobile)) < 4) { 
      $mobile = __('No phone number', 'veronika');
      $mobile_found = false;
    }      
  ?> 


  <?php if($content_only == 0) { ?>

    <!-- FACEBOOK OPEN GRAPH TAGS -->
    <?php osc_get_item_resources(); ?>
    <meta property="og:title" content="<?php echo osc_esc_html(osc_item_title()); ?>" />
    <?php if(osc_count_item_resources() > 0) { ?><meta property="og:image" content="<?php echo osc_resource_url(); ?>" /><?php } ?>
    <meta property="og:site_name" content="<?php echo osc_esc_html(osc_page_title()); ?>"/>
    <meta property="og:url" content="<?php echo osc_item_url(); ?>" />
    <meta property="og:description" content="<?php echo osc_esc_html(osc_highlight(osc_item_description(), 500)); ?>" />
    <meta property="og:type" content="article" />
    <meta property="og:locale" content="<?php echo osc_current_user_locale(); ?>" />
    <meta property="product:retailer_item_id" content="<?php echo osc_item_id(); ?>" /> 
    <meta property="product:price:amount" content="<?php echo $og_price; ?>" />
    <?php if(osc_item_price() <> '' and osc_item_price() <> 0) { ?><meta property="product:price:currency" content="<?php echo osc_item_currency(); ?>" /><?php } ?>



    <!-- GOOGLE RICH SNIPPETS -->

    <span itemscope itemtype="http://schema.org/Product">
      <meta itemprop="name" content="<?php echo osc_esc_html(osc_item_title()); ?>" />
      <meta itemprop="description" content="<?php echo osc_esc_html(osc_highlight(osc_item_description(), 500)); ?>" />
      <?php if(osc_count_item_resources() > 0) { ?><meta itemprop="image" content="<?php echo osc_resource_url(); ?>" /><?php } ?>
    </span>
  <?php } ?>
</head>

<body id="body-item" class="page-body<?php if($content_only == 1) { ?> content_only<?php } ?><?php if ($ios) { ?> ios<?php } ?>">
  <?php if($content_only == 0) { ?>
    <?php osc_current_web_theme_path('header.php') ; ?>
    <?php if( osc_item_is_expired () ) { ?>
      <div class="exp-box">
        <div class="exp-mes round3"><?php _e('This listing is expired.', 'veronika'); ?></div>
      </div>
    <?php } ?>
  <?php } ?>


  <div id="listing" class="content list">
    <?php echo veronika_banner('item_top'); ?>

    <!-- LISTING BODY -->
    <div id="main">

      <!-- Image Block -->
      <div id="left" class="round3 i-shadow">

        <?php if($content_only == 0) { ?>
          <div class="top-details">
            <h2 class="top"><?php echo ucfirst(osc_item_title()); ?></h2>

            <div class="bot">
              <?php if(trim($item_loc) <> '') { ?>
                <div class="location"><?php echo $item_loc; ?></div>
              <?php } ?>

              <div class="published">
                <?php if (osc_item_mod_date() == '') { ?>
                  <?php _e('Published', 'veronika'); ?> <span title="<?php echo osc_esc_html(osc_format_date(osc_item_pub_date())); ?>"><?php echo veronika_smart_date(osc_item_pub_date()); ?></span>
                <?php } else { ?>
                  <?php _e('Last update', 'veronika'); ?> <span title="<?php echo osc_esc_html(osc_format_date(osc_item_mod_date())); ?>"><?php echo veronika_smart_date(osc_item_mod_date()); ?></span>
                <?php } ?>
              </div>   

              <div class="id"><?php _e('ID', 'veronika'); ?> #<?php echo osc_item_id(); ?></div>
            </div>

            <?php if (function_exists('show_qrcode')) { ?>
              <div class="qr-right noselect">
                <?php show_qrcode(); ?>
              </div>
            <?php } ?>
          </div>
        <?php } ?>


        <!-- IMAGE BOX -->
        <div id="images">
          <?php if($item_extra['i_sold'] == 1) { ?>
            <div class="flag sold"><i class="fa fa-gavel"></i> <?php _e('Item sold', 'veronika'); ?></div>
          <?php } else if($item_extra['i_sold'] == 2) { ?>
            <div class="flag reserved"><i class="fa fa-flag"></i> <?php _e('Item reserved', 'veronika'); ?></div>
          <?php } else if (osc_item_is_premium()) { ?>
            <div class="flag premium"><i class="fa fa-star"></i> <?php _e('Premium', 'veronika'); ?></div>
          <?php } ?>

          <?php if( osc_images_enabled_at_items() ) { ?> 
            <?php osc_get_item_resources(); ?>

            <?php if( osc_count_item_resources() > 0 ) { ?>  
              <?php $at_once = min(osc_get_preference('item_images', 'veronika_theme'), osc_count_item_resources()); ?>

              <div id="pictures" class="item-pictures">
                <ul class="item-bxslider">
                  <?php osc_reset_resources(); ?>
                  <?php for( $i = 0; osc_has_item_resources(); $i++ ) { ?>
                    <li>
                      <?php if($content_only == 0) { ?>
                        <a rel="image_group" href="<?php echo osc_resource_url(); ?>" title="<?php echo osc_esc_html(osc_item_title()); ?> - <?php _e('Image', 'veronika'); ?> <?php echo $i+1;?>/<?php echo osc_count_item_resources();?>">
                          <img src="<?php echo osc_resource_url(); ?>" alt="<?php echo osc_esc_html(osc_item_title()); ?> - <?php echo $i+1;?>/<?php echo osc_count_item_resources();?>"/>
                        </a>
                      <?php } else { ?>
                        <img src="<?php echo osc_resource_url(); ?>" alt="<?php echo osc_esc_html(osc_item_title()); ?> - <?php echo $i+1;?>/<?php echo osc_count_item_resources();?>"/>
                      <?php } ?>
                    </li>
                  <?php } ?>
                </ul>

                <div id="photo-count" class="round2">
                  <div class="top"><i class="fa fa-camera"></i></div>
                  <div class="bottom">
                    <?php if(osc_count_item_resources() == 1) { ?>
                      <span class="p-total"><?php echo osc_count_item_resources(); ?></span> <?php _e('photo', 'veronika'); ?>
                    <?php } else { ?>
                      <span class="p-from">1</span> <span class="p-del">-</span> <span class="p-to"><?php echo $at_once; ?></span> <?php _e('of', 'veronika'); ?> <span class="p-total"><?php echo osc_count_item_resources(); ?></span>
                    <?php } ?>
                  </div>
                </div>

                <?php if(osc_count_item_resources() > 1 && osc_get_preference('item_pager', 'veronika_theme') == 1) { ?>
                  <div id="item-bx-pager">
                    <?php osc_reset_resources(); ?>
                    <?php $c = 0; ?>
                    <?php for( $i = 1; osc_has_item_resources(); $i++ ) { ?>

                      <?php if($i - 1 + $at_once <= osc_count_item_resources()) { ?>
                        <a data-slide-index="<?php echo $c; ?>" href="" class="bx-navi<?php if($i - 1 + $at_once == osc_count_item_resources()) { ?> last<?php } ?>"<?php if($i - 1 + $at_once == osc_count_item_resources()) { ?> style="width:<?php echo $at_once * 128; ?>px"<?php } ?>>
                      <?php } ?>

                      <img src="<?php echo osc_resource_thumbnail_url(); ?>" alt="<?php echo osc_esc_html(osc_item_title()); ?> - <?php _e('Image', 'veronika'); ?> <?php echo $i;?>/<?php echo osc_count_item_resources();?>"/>

                      <?php if($i == osc_count_item_resources()) { ?>
                        </a>
                      <?php } ?>

                      <?php $c++; ?>
                    <?php } ?>
                  </div>
                <?php } ?>
              </div>
            <?php } else { ?>
              <div id="image-empty">
                <img class="round3" src="<?php echo osc_base_url(); ?>oc-content/themes/<?php echo osc_current_web_theme(); ?>/images/item-no-picture.png" alt="<?php echo osc_esc_html(__('Seller did not upload any pictures', 'veronika')); ?>" />
                <span><?php _e('Seller did not upload any pictures', 'veronika'); ?></span>
              </div>
            <?php } ?>
          <?php } ?>
        </div>


        <?php if($content_only == 0) { ?>
          <div id="swap" class="is767">
            <a href="#" class="details active"><?php _e('Details', 'veronika'); ?></a>
            <a href="#" class="contact"><?php _e('Contact', 'veronika'); ?></a>
          </div>


          <div class="is767">
            <div id="item-basics" class="is_detail">

              <div class="price round2"><?php echo osc_item_formated_price(); ?></div>

              <div class="clear"></div>

              <?php if($item_extra['i_condition'] <> '') { ?>
                <div class="condition"><?php _e('Condition', 'veronika'); ?>: <span><?php echo ($item_extra['i_condition'] == 1 ? __('New', 'veronika') : __('Used', 'veronika')); ?></span></div>
              <?php } ?>

              <?php if($item_extra['i_transaction'] <> '') { ?>
                <?php
                  $id = $item_extra['i_transaction'];

                  if ($id == 1) {
                    $transaction = __('Sell', 'veronika');
                  } else if ($id == 2) {
                    $transaction = __('Buy', 'veronika');
                  } else if ($id == 3) {
                    $transaction = __('Rent', 'veronika');
                  } else if ($id == 4) {
                    $transaction = __('Exchange', 'veronika');
                  }
                ?>

                <div class="transaction"><?php _e('Transaction', 'veronika'); ?>: <span><?php echo $transaction; ?></span></div>
              <?php } ?>


              <div class="title"><?php echo ucfirst(osc_item_title()); ?></div>

              <?php if(trim($item_loc) <> '') { ?>
                <div class="location"><?php echo $item_loc; ?>, </div>
              <?php } ?>

              <div class="published">
                <?php if (osc_item_mod_date() == '') { ?>
                  <span title="<?php echo osc_format_date(osc_item_pub_date()); ?>"><?php _e('Published', 'veronika'); ?> <?php echo veronika_smart_date(osc_item_pub_date()); ?></span>
                <?php } else { ?>
                  <span title="<?php echo osc_format_date(osc_item_pub_date()); ?>"><?php _e('Modified', 'veronika'); ?> <?php echo veronika_smart_date(osc_item_mod_date()); ?></span>
                <?php } ?>
              </div>   

            </div>
          </div>


          <div class="item-desc is_detail">
            <h2><?php _e('Description', 'veronika'); ?></h2>

            <div class="text">
              <?php echo osc_item_description(); ?>
              <span class="show-desc"><i class="fa fa-ellipsis-h"></i></span>
            </div>
          </div>


          <div class="details is_detail">

            <?php $has_custom = false; ?>
            <?php if( osc_count_item_meta() >= 1 ) { ?>
              <div id="custom_fields">
                <h3><span><?php _e('Additional information', 'veronika'); ?></span></h3>

                <div class="meta_list">
                  <?php $class = 'odd'; ?>
                  <?php while( osc_has_item_meta() ) { ?>
                    <?php if(osc_item_meta_value()!='') { ?>
                      <?php $has_custom = true; ?>
                      <div class="meta <?php echo $class; ?>">
                        <div class="ins">
                          <span><?php echo osc_item_meta_name(); ?>:</span> <?php echo osc_item_meta_value(); ?>
                        </div>
                      </div>
                    <?php } ?>

                    <?php $class = ($class == 'even') ? 'odd' : 'even'; ?>
                  <?php } ?>
                </div>

              </div>
            <?php } ?>

            <div id="plugin-details">
              <?php osc_run_hook('item_detail', osc_item() ); ?>  
            </div>
          </div>
        <?php } ?>
      </div>


      <?php echo veronika_banner('item_description'); ?>


      <?php if($content_only == 0) { ?>

        <!-- Block #3 - Description -->
        <div id="more-info" class="contact-seller round3 i-shadow is_contact">
          <h1>&nbsp;</h1>

          <h2><span><?php _e('Contact seller', 'veronika'); ?></span></h2>
      
          <div class="inside">
            <ul id="error_list"></ul>
            <?php ContactForm::js_validation(); ?>

            <form action="<?php echo osc_base_url(true) ; ?>" method="post" name="contact_form" id="contact_form" <?php if(osc_item_attachment()) { echo 'enctype="multipart/form-data"'; };?>>
              <input type="hidden" name="action" value="contact_post" />
              <input type="hidden" name="page" value="item" />
              <input type="hidden" name="id" value="<?php echo osc_item_id() ; ?>" />

              <?php osc_prepare_user_info() ; ?>

              <fieldset>
                <div class="phone-mail">
                  <div class="wrap">
                    <div class="phone<?php if(!$mobile_found) { ?> no-number<?php } ?>">
                      <a href="#" class="phone-block" data-item-id="<?php echo osc_item_id(); ?>" data-item-user-id="<?php echo osc_item_user_id(); ?>">
                        <i class="fa fa-phone"></i>
                        <span>
                          <?php 
                            if(strlen($mobile) >= 4 && $mobile <> __('No phone number', 'veronika')) {
                              echo substr($mobile, 0, strlen($mobile) - 4) . 'xxxx'; 
                            } else {
                              echo $mobile;
                            }
                          ?>
                        </span>
                      </a>

                      <?php if($mobile_found) { ?>
                        <a href="#" class="phone-show" data-item-id="<?php echo osc_item_id(); ?>" data-item-user-id="<?php echo osc_item_user_id(); ?>"><?php _e('Show', 'veronika'); ?></a>
                      <?php } ?>
                    </div>


                    <?php if(osc_item_show_email()) { ?>
                      <div class="mail">
                        <?php
                          $mail = osc_item_contact_email();
                          $mail_start = substr($mail, 0, 3);
                        ?>

                        <a href="#" class="mail-block" rel="<?php echo substr($mail, 3); ?>">
                          <i class="fa fa-at"></i>
                          <span>
                            <?php echo $mail_start . 'xxxx@xxxx.xxxx'; ?>
                          </span>
                        </a>

                        <a href="#" class="mail-show"><?php _e('Show', 'veronika'); ?></a>
                      </div>
                    <?php } ?>
                  </div>
                </div>


                <div id="seller-data" class="is767">
                  <?php if(function_exists('profile_picture_show')) { ?>
                    <?php if(osc_item_user_id() <> 0 and osc_item_user_id() <> '') { ?>
                      <a class="side-prof" href="<?php echo osc_user_public_profile_url(osc_item_user_id()); ?>" title="<?php echo osc_esc_html(__('Check profile of this user', 'veronika')); ?>">
                        <?php profile_picture_show(null, 'item', 200); ?>
                      </a>
                    <?php } else { ?>
                      <div class="side-prof">
                        <?php profile_picture_show(null, 'item', 200); ?>
                      </div>
                    <?php } ?>
                  <?php } else { ?>
                    <a class="side-prof" href="<?php echo osc_user_public_profile_url(osc_item_user_id()); ?>" title="<?php echo osc_esc_html(__('Check profile of this user', 'veronika')); ?>">
                      <img id="profile_picture_img" src="<?php echo osc_current_web_theme_url(); ?>images/profile-default.png"/>
                    </a>
                  <?php } ?>

                  <div class="name">
                    <?php if(osc_item_user_id() <> 0 and osc_item_user_id() <> '') { ?>
                      <a href="<?php echo osc_user_public_profile_url(osc_item_user_id()); ?>" title="<?php echo osc_esc_html(__('Check profile of this user', 'veronika')); ?>">
                        <?php echo (osc_item_contact_name() <> '' ? osc_item_contact_name() :  __('Anonymous', 'veronika')); ?>
                      </a>
                    <?php } else { ?>
                      <?php echo (osc_item_contact_name() <> '' ? osc_item_contact_name() :  __('Anonymous', 'veronika')); ?>
                    <?php } ?>
                  </div>

                  <?php if(osc_item_user_id() <> 0) { ?>
                    <div class="type">
                      <?php $user = User::newInstance()->findByPrimaryKey( osc_item_user_id() ); ?>
                      <?php if($user['b_company'] == 1) { ?>
                        <span><?php _e('Company', 'veronika'); ?></span>
                      <?php } else { ?>
                        <span><?php _e('Private person', 'veronika'); ?></span>
                      <?php } ?>
                    </div>
                  <?php } ?>

                  <div class="regdate">
                    <?php if(osc_item_user_id() <> 0) { ?>
                      <?php $get_user = User::newInstance()->findByPrimaryKey( osc_item_user_id() ); ?>

                      <?php if(isset($get_user['dt_reg_date']) AND $get_user['dt_reg_date'] <> '') { ?>
                        <?php echo __('Registered on', 'veronika') . ' ' . date('j. M Y',  strtotime($get_user['dt_reg_date']) ); ?>
                      <?php } else { ?>
                        <?php echo __('Unknown registration date', 'veronika'); ?>
                      <?php } ?>
                    <?php } else { ?>
                      <?php echo __('Unregistered user', 'veronika'); ?>
                    <?php } ?>
                  </div>
                </div>


                <div class="message-block">
                  <?php if( osc_item_is_expired () ) { ?>
                    <div class="empty">
                      <?php _e('This listing expired, you cannot contact seller.', 'veronika') ; ?>
                    </div>
                  <?php } else if( (osc_logged_user_id() == osc_item_user_id()) && osc_logged_user_id() != 0 ) { ?>
                    <div class="empty">
                      <?php _e('It is your own listing, you cannot contact yourself.', 'veronika') ; ?>
                    </div>
                  <?php } else if( osc_reg_user_can_contact() && !osc_is_web_user_logged_in() ) { ?>
                    <div class="empty">
                      <?php _e('You must log in or register a new account in order to contact the advertiser.', 'veronika') ; ?>
                    </div>
                  <?php } else { ?> 
                    <div class="row first">
                      <?php ContactForm::your_name(); ?>
                      <label><?php _e('Name', 'veronika') ; ?></label>
                    </div>

                    <div class="row second">
                      <?php ContactForm::your_email(); ?>
                      <label><span><?php _e('E-mail', 'veronika'); ?></span><span class="req">*</span></label>
                    </div>

                    <div class="row third">
                      <?php ContactForm::your_phone_number(); ?>
                      <label><span><?php _e('Phone', 'veronika'); ?></span></label>
                    </div>

                    <div class="row full">
                      <?php ContactForm::your_message(); ?>
                    </div>
                    
                    <?php if(osc_item_attachment()) { ?>
                      <div class="attachment">
                        <div class="att-box">
                          <label class="status">
                            <span class="wrap"><i class="fa fa-paperclip"></i> <span data-original="<?php echo osc_esc_html(__('Upload file', 'veronika')); ?>"><?php _e('Upload file', 'veronika'); ?></span></span>
                            <?php ContactForm::your_attachment(); ?>
                          </label>
                        </div>

                        <div class="text"><?php _e('Allowed extensions:', 'veronika'); ?> <?php echo osc_allowed_extension(); ?>.</div>
                        <div class="text"><?php _e('Maximum size:', 'veronika'); ?> <?php echo round(osc_max_size_kb()/1000, 1); ?>Mb.</div>
                      </div>
                    <?php } ?>


                    <?php osc_run_hook('item_contact_form', osc_item_id()); ?>
                    <?php osc_show_recaptcha(); ?>

                    <button type="<?php echo (osc_get_preference('forms_ajax', 'veronika_theme') == 1 ? 'button' : 'submit'); ?>" class="send" id="item-message"><i class="fa fa-envelope"></i> <?php _e('Send message', 'veronika') ; ?></button>
                  <?php } ?>
                </div>

                <div class="message-status message-sent">
                  <div class="icon"><i class="fa fa-check-circle"></i></div>
                  <div class="title"></div>
                  <div class="link"><a href="#" class="next-message"><?php _e('Send next message', 'veronika'); ?></a></div>
                </div>

                <div class="message-status message-not-sent">
                  <div class="icon"><i class="fa fa-times-circle"></i></div>
                  <div class="title"></div>
                  <div class="link"><a href="#" class="next-message"><?php _e('Send next message', 'veronika'); ?></a></div>
                </div>
              </fieldset>
            </form>
          </div>
        </div>


        <!-- Comments-->
        <div id="more-info" class="comments round3 i-shadow is_detail">
          <?php if( osc_comments_enabled()) { ?>
            <div class="item-comments">
              <h2 class="sc-click">
                <span><?php _e('Comments', 'veronika'); ?></span>
              </h2>


              <!-- LIST OF COMMENTS -->
              <div id="comments" class="sc-block">
                <div class="comments_list">
                  <div class="comment-wrap comment-empty">
                    <div class="ins">
                      <div class="comment-image tr1">
                        <img class="tr1" src="<?php echo osc_current_web_theme_url('images/profile-support.png'); ?>"/>
                      </div>

                      <div class="comment<?php if( osc_reg_user_post_comments () && !osc_is_web_user_logged_in() ) { ?> two-lines<?php } ?>">
                        <div class="body">
                          <span><?php _e('Question? Need more information? Want to ask question? Add new comment...', 'veronika'); ?></span>

                          <?php if( osc_reg_user_post_comments () && !osc_is_web_user_logged_in() ) { ?>
                            <a class="log" href="<?php echo osc_register_account_url(); ?>"><?php _e('You need to log in first.', 'veronika'); ?></a>
                          <?php } ?>
                        </div>
                      </div>
                    </div>
                  </div>

                  <?php $class = 'even'; ?>
                  <?php $i = 1; ?>
                  <?php while ( osc_has_item_comments() ) { ?>
                    <div class="comment-wrap <?php echo $class; ?>">
                      <div class="ins">
                        <div class="comment-image">
                          <?php if(function_exists('profile_picture_show')) { ?>
                            <?php profile_picture_show(100, 'comment', 100, $i); ?>
                          <?php } else { ?>
                            <img src="<?php echo osc_current_web_theme_url(); ?>images/profile-u<?php echo $i; ?>.png"/>
                          <?php } ?>
                        </div>

                        <div class="comment">
                          <h4><span class="bold"><?php if(osc_comment_title() == '') { _e('Review', 'veronika'); } else { echo osc_comment_title(); } ?></span> <?php _e('by', 'veronika') ; ?> <?php if(osc_comment_author_name() == '') { _e('Anonymous', 'veronika'); } else { echo osc_comment_author_name(); } ?>:</h4>
                          <div class="body"><?php echo osc_comment_body() ; ?></div>

                          <?php if ( osc_comment_user_id() && (osc_comment_user_id() == osc_logged_user_id()) ) { ?>
                            <a rel="nofollow" class="remove" href="<?php echo osc_delete_comment_url(); ?>" title="<?php echo osc_esc_html(__('Delete your comment', 'veronika')); ?>">
                              <span class="not767"><?php _e('Delete', 'veronika'); ?></span>
                              <span class="is767"><i class="fa fa-trash-o"></i></span>
                            </a>
                          <?php } ?>
                        </div>
                      </div>
                    </div>

                    <div class="clear"></div>
                    <?php $class = ($class == 'even') ? 'odd' : 'even'; ?>

                    <?php
                      $i++;
                      $i = $i % 3 + 1;
                    ?>
                  <?php } ?>

                  <div class="paginate"><?php echo osc_comments_pagination(); ?></div>
                </div>
              </div>

              <div class="care">
                <div class="text"><?php _e('Ask questions related to this listing only.', 'veronika'); ?></div>
                <div class="text"><?php _e('Comments are moderated.', 'veronika'); ?></div>
              </div>

              <?php if( osc_reg_user_post_comments () && osc_is_web_user_logged_in() || !osc_reg_user_post_comments() ) { ?>
                <a class="add-com btn tr1 round3" href="<?php echo osc_item_send_friend_url(); ?>"><i class="fa fa-commenting"></i><?php _e('Add new comment', 'veronika'); ?></a>
              <?php } ?>
            </div>
          <?php } ?>
        </div>
      <?php } ?>


      <?php echo veronika_banner('item_bottom'); ?>
    </div>


    <!-- RIGHT SIDEBAR -->
    <?php if($content_only == 0) { ?>
      <div id="side-right">
   
        <div id="price" class="round3 i-shadow<?php if(function_exists('multicurrency_add_prices') && osc_item_price() <> '' && osc_item_price() > 0) { ?> mc<?php } ?>">
          <i class="fa fa-tags"></i>
          <span class="long-price-fix"><?php echo osc_item_formated_price(); ?></span>
        </div>


        <?php if( !osc_item_is_expired() && (osc_logged_user_id() <> osc_item_user_id() || osc_logged_user_id() == 0 ) && ( !osc_reg_user_can_contact() || osc_is_web_user_logged_in() )) { ?>
          <div class="contact-button btn btn-primary tr1 round3 i-shadow<?php if($mobile_found) { ?> mobile-found<?php } ?>">
            <i class="fa fa-envelope"></i> 
            <span class="top"><?php _e('Contact seller', 'veronika') ; ?></span>

            <?php if($mobile_found && strlen($mobile) >= 4) { ?>
              <span class="bot"><?php echo substr($mobile, 0, strlen($mobile) - 4) . 'xxxx'; ?></span>
            <?php } ?>

          </div>
        <?php } ?>


        <?php echo veronika_banner('item_sidebar'); ?>


        <!- SELLER INFO -->
        <div id="seller" class="round3 i-shadow<?php if(osc_item_user_id() == 0) { ?> unreg<?php } ?>">
          <h2 class="sc-click">
            <?php if(osc_is_web_user_logged_in() && osc_item_user_id() == osc_logged_user_id()) { ?>
              <?php _e('Seller\'s tools', 'veronika'); ?>
            <?php } else { ?>
              <?php _e('Seller\'s info', 'veronika'); ?>
            <?php } ?>
          </h2>


          <div class="sc-block body">
            <div class="inside">

              <!-- IF USER OWN THIS LISTING, SHOW SELLER TOOLS -->
              <?php if(osc_is_web_user_logged_in() && osc_item_user_id() == osc_logged_user_id()) { ?>
                <div id="s-tools">
                  <a href="<?php echo osc_item_edit_url(); ?>" class="tr1 round2"><i class="fa fa-edit tr1"></i><span><?php _e('Edit', 'veronika'); ?></span></a>
                  <a href="<?php echo osc_item_delete_url(); ?>" class="tr1 round2" onclick="return confirm('<?php _e('Are you sure you want to delete this listing? This action cannot be undone.', 'veronika'); ?>?')"><i class="fa fa-trash-o tr1"></i><span><?php _e('Remove', 'veronika'); ?></span></a>

                  <?php 
                    if (osc_rewrite_enabled()) { 
                      if( $item_extra['i_sold'] == 0 ) {
                        $sold_url = '?itemId=' . osc_item_id() . '&markSold=1&secret=' . osc_item_field('s_secret') . '&itemType=active';
                        $reserved_url = '?itemId=' . osc_item_id() . '&markSold=2&secret=' . osc_item_field('s_secret') . '&itemType=active';
                      } else {
                        $sold_url = '?itemId=' . osc_item_id() . '&markSold=0&secret=' . osc_item_field('s_secret') . '&itemType=active';
                        $reserved_url = '?itemId=' . osc_item_id() . '&markSold=0&secret=' . osc_item_field('s_secret') . '&itemType=active';
                      }
                    } else {
                      if( $item_extra['i_sold'] == 0 ) {
                        $sold_url = '&itemId=' . osc_item_id() . '&markSold=1&secret=' . osc_item_field('s_secret') . '&itemType=active';
                        $reserved_url = '&itemId=' . osc_item_id() . '&markSold=2&secret=' . osc_item_field('s_secret') . '&itemType=active';
                      } else {
                        $sold_url = '&itemId=' . osc_item_id() . '&markSold=0&secret=' . osc_item_field('s_secret') . '&itemType=active';
                        $reserved_url = '&itemId=' . osc_item_id() . '&markSold=0&secret=' . osc_item_field('s_secret') . '&itemType=active';
                      }
                    }
                  ?>


                  <?php if(!in_array(osc_item_category_id(), veronika_extra_fields_hide())) { ?>
                    <a target="_blank" class="tr1 round2 sold" href="<?php echo osc_user_list_items_url() . $sold_url; ?>"><i class="fa fa-gavel"></i> <span><?php echo ($item_extra['i_sold'] == 1 ? __('Not sold', 'veronika') : __('Sold', 'veronika')); ?></span></a>
                    <a target="_blank" class="tr1 round2 reserved" href="<?php echo osc_user_list_items_url() . $reserved_url; ?>"><i class="fa fa-flag-o"></i> <span><?php echo ($item_extra['i_sold'] == 2 ? __('Unreserve', 'veronika') : __('Reserved', 'veronika')); ?></span></a>
                  <?php } ?>
                </div>
              <?php } else { ?>

                <!-- USER IS NOT OWNER OF LISTING -->
                <?php if(function_exists('profile_picture_show')) { ?>
                  <?php if(osc_item_user_id() <> 0 and osc_item_user_id() <> '') { ?>
                    <a class="side-prof" href="<?php echo osc_user_public_profile_url(osc_item_user_id()); ?>" title="<?php echo osc_esc_html(__('Check profile of this user', 'veronika')); ?>">
                      <?php profile_picture_show(null, 'item', 200); ?>
                    </a>
                  <?php } else { ?>
                    <div class="side-prof">
                      <?php profile_picture_show(null, 'item', 200); ?>
                    </div>
                  <?php } ?>
                <?php } ?>

                <div class="name">
                  <?php
                    $c_name = '';
                    if(osc_item_contact_name() <> '' and osc_item_contact_name() <> __('Anonymous', 'veronika')) {
                      $c_name = osc_item_contact_name();
                    }

                    if($c_name == '' and $item_user['s_name'] <> '') { 
                      $c_name = $item_user['s_name'];
                    }

                    if($c_name == '') {
                      $c_name = __('Anonymous', 'veronika');
                    }
                  ?>

                  <?php if(osc_item_user_id() <> 0 and osc_item_user_id() <> '') { ?>
                    <a href="<?php echo osc_user_public_profile_url(osc_item_user_id()); ?>" title="<?php echo osc_esc_html(__('Check profile of this user', 'veronika')); ?>">
                      <?php echo $c_name; ?>
                    </a>
                  <?php } else { ?>
                    <?php echo $c_name; ?>
                  <?php } ?>
                </div>


                <?php if(function_exists('show_feedback_overall')) { ?>
                  <div class="elem feedback"><?php echo show_feedback_overall(); ?></div>
                <?php } ?>

                <?php if(osc_item_user_id() <> 0) { ?>
                  <div class="elem type">
                    <?php $user = User::newInstance()->findByPrimaryKey( osc_item_user_id() ); ?>
                    <?php if($user['b_company'] == 1) { ?>
                      <span><i class="fa fa-users"></i> <?php _e('Company', 'veronika'); ?></span>
                    <?php } else { ?>
                      <span><i class="fa fa-user"></i> <?php _e('Private person', 'veronika'); ?></span>
                    <?php } ?>
                  </div>
                <?php } ?>

                <div class="elem regdate">
                  <?php if(osc_item_user_id() <> 0) { ?>
                    <?php $get_user = User::newInstance()->findByPrimaryKey( osc_item_user_id() ); ?>

                    <?php if(isset($get_user['dt_reg_date']) AND $get_user['dt_reg_date'] <> '') { ?>
                      <?php echo __('Registered on', 'veronika') . ' ' . osc_format_date( $get_user['dt_reg_date'] ); ?>
                    <?php } else { ?>
                      <?php echo __('Unknown registration date', 'veronika'); ?>
                    <?php } ?>
                  <?php } else { ?>
                    <?php echo __('Unregistered user', 'veronika'); ?>
                  <?php } ?>
                </div>

                <?php if(osc_item_user_id() <> 0) { ?>
                  <div class="seller-bottom">
                    <?php if(function_exists('seller_post')) { ?>
                      <?php seller_post(); ?>
                    <?php } ?>

                    <a href="<?php echo osc_user_public_profile_url(osc_item_user_id()); ?>"><?php _e('Dashboard', 'veronika'); ?></a>
                  </div>
                <?php } ?>
              <?php } ?>
            </div>


            <!-- ITEM BUTTONS - SEND TO FRIEND / PRINT / MAKE FAVORITE -->
            <div id="item-buttons">
              <?php if(function_exists('fi_make_favorite')) { echo fi_make_favorite(); } ?>

              <a id="send-friend" href="<?php echo osc_item_send_friend_url(); ?>" class="tr1" title="<?php echo osc_esc_html(__('Send this listing to your friend', 'veronika')); ?>"><i class="fa fa-users tr1"></i></a>

              <?php if (function_exists('print_ad')) { ?>
                <div id="item-print-box">
                  <?php print_ad(); ?>
                </div>
              <?php } ?>

              <?php if (function_exists('show_printpdf')) { ?>
                <a id="print_pdf" class="tr1" target="_blank" href="<?php echo osc_base_url(); ?>oc-content/plugins/printpdf/download.php?item=<?php echo osc_item_id(); ?>" title="<?php echo osc_esc_html(__('Show PDF sheet for this listing', 'veronika')); ?>"><i class="fa fa-file-pdf-o tr1"></i></a>
              <?php } ?>


              <div id="report" class="noselect tr1">
                <a href="#" title="<?php echo osc_esc_html(__('Report item', 'veronika')); ?>"><i class="fa fa-flag-o"></i></a>

                <div class="cont-wrap">
                  <div class="cont">
                    <a id="item_spam" class="reports" href="<?php echo osc_item_link_spam() ; ?>" rel="nofollow"><?php _e('spam', 'veronika') ; ?></a>
                    <a id="item_bad_category" class="reports" href="<?php echo osc_item_link_bad_category() ; ?>" rel="nofollow"><?php _e('misclassified', 'veronika') ; ?></a>
                    <a id="item_repeated" class="reports" href="<?php echo osc_item_link_repeated() ; ?>" rel="nofollow"><?php _e('duplicated', 'veronika') ; ?></a>
                    <a id="item_expired" class="reports" href="<?php echo osc_item_link_expired() ; ?>" rel="nofollow"><?php _e('expired', 'veronika') ; ?></a>
                    <a id="item_offensive" class="reports" href="<?php echo osc_item_link_offensive() ; ?>" rel="nofollow"><?php _e('offensive', 'veronika') ; ?></a>
                  </div>
                </div>
              </div>

            </div>

          </div>
        </div>


        <!-- ITEM LOCATION -->
        <div id="location" class="round3 i-shadow">
          <h2 class="sc-click">
            <?php _e('Listing location', 'veronika') ; ?>
          </h2>

          <div class="body sc-block">
            <div class="loc-text">
              <?php if(trim(osc_item_country() . osc_item_region() . osc_item_city()) == '') {?>
                <div class="box-empty">
                  <div class="img"><img src="<?php echo osc_current_web_theme_url('images/location-default.png'); ?>"/></div>
                  <span><?php _e('Seller did not specified location', 'veronika'); ?></span>
                </div>
              <?php } ?>


              <?php if($item_loc <> '') { ?>
                <div class="elem"><?php echo $item_loc; ?></div>
              <?php } ?>

              <?php if(osc_item_address() <> '') { ?>
                <div class="elem"><?php echo osc_item_address(); ?></div>
              <?php } ?>
            </div>

            <div class="map">
              <?php osc_run_hook('location') ; ?>
            </div>  
          </div>  
        </div>



        <!-- LISTING SHARE LINKS -->
        <div class="listing-share">
          <?php osc_reset_resources(); ?>
          <a class="single single-facebook" title="<?php echo osc_esc_html(__('Share on Facebook', 'veronika')); ?>" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo osc_item_url(); ?>"><i class="fa fa-facebook-square"></i></a> 
          <a class="single single-google-plus" title="<?php echo osc_esc_html(__('Share on Google Plus', 'veronika')); ?>" target="_blank" href="https://plus.google.com/share?url=<?php echo osc_item_url(); ?>"><i class="fa fa-google-plus-square"></i></a> 
          <a class="single single-twitter" title="<?php echo osc_esc_html(__('Share on Twitter', 'veronika')); ?>" target="_blank" href="https://twitter.com/home?status=<?php echo osc_esc_html(osc_item_title()); ?>"><i class="fa fa-twitter-square"></i></a> 
          <a class="single single-pinterest" title="<?php echo osc_esc_html(__('Share on Pinterest', 'veronika')); ?>" target="_blank" href="https://pinterest.com/pin/create/button/?url=<?php echo osc_item_url(); ?>&media=<?php echo osc_resource_url(); ?>&description=<?php echo htmlspecialchars(osc_item_title()); ?>"><i class="fa fa-pinterest-square"></i></a> 
        </div>

      </div>
    <?php } ?>
  </div>


  <?php if($content_only == 0) { ?>
    <div id="related-block">
      <?php if(function_exists('related_ads_start')) { related_ads_start(); } ?>
    </div>
  <?php } ?>


  <?php if($content_only == 0) { ?>
    <script type="text/javascript">
      $(document).ready(function(){
        // WRAP TEXT IN H2 & H3 IN ATTRIBUTES PLUGIN INTO SPAN
        $('#plugin-details h2, #plugin-details h3').each(function(){
          $(this).html('<span>' + $(this).html() + '</span>');
        });

        // SHOW PHONE NUMBER ON CLICK
        <?php if($mobile <> __('No phone number', 'veronika')) { ?>  
          $('.phone-show, .phone-block').click(function(e){
            e.preventDefault();
            var mobile = "<?php echo $mobile; ?>";

            if($('.phone-block').attr('href') == '#') {
              $('.phone-block, .phone-show').attr('href', 'tel:' + mobile).addClass('shown');
              $('.phone-block span').text(mobile).css('font-weight', 'bold');
              $('#side-right .btn.contact-button .bot').text(mobile);
              $('.phone-show').text('<?php echo osc_esc_js(__('Click to call', 'veronika')); ?>');

              return false;
            }
          });
        <?php } else { ?>
          $('.phone-show, .phone-block').click(function(){
            return false;
          });
        <?php } ?>


        // SHOW EMAIL
        <?php if(osc_item_show_email()) { ?>  
          $('.mail-show, .mail-block').click(function(){
            var mail_start = $('.mail-block > span').text();
            mail_start = mail_start.trim();
            mail_start = mail_start.substring(0, 3);
            var mail_end = $('.mail-block').attr('rel');
            var mail = mail_start + mail_end;

            if($('.mail-block').attr('href') == '#') {
              $('.mail-block, .mail-show').attr('href', 'mailto:' + mail);
              $('.mail-block span').text(mail).css('font-weight', 'bold');
              $('.mail-show').text('<?php echo osc_esc_js(__('Click to mail', 'veronika')); ?>');

              return false;
            }
          });
        <?php } else { ?>
          $('.phone-show, .phone-block').click(function(){
            return false;
          });
        <?php } ?>
      });
    </script>

       
    <!-- Scripts -->
    <script type="text/javascript">
    $(document).ready(function(){
      $('.comment-wrap').hover(function(){
        $(this).find('.hide').fadeIn(200);}, 
        function(){
        $(this).find('.hide').fadeOut(200);
      });

      $('.comment-wrap .hide').click(function(){
        $(this).parent().fadeOut(200);
      });

      $('#but-con').click(function(){
        $(".inner-block").slideToggle();
        $("#rel_ads").slideToggle();
      }); 

      
      <?php if(!$has_custom) { echo '$("#custom_fields").hide();';} ?>
    });
    </script>


    <!-- CHECK IF PRICE IN THIS CATEGORY IS ENABLED -->
    <script>
    $(document).ready(function(){
      var cat_id = <?php echo osc_item_category_id(); ?>;
      var catPriceEnabled = new Array();

      <?php
        $categories = Category::newInstance()->listAll( false );
        foreach( $categories as $c ) {
          if( $c['b_price_enabled'] != 1 ) {
            echo 'catPriceEnabled[ '.$c['pk_i_id'].' ] = '.$c[ 'b_price_enabled' ].';';
          }
        }
      ?>

      if(catPriceEnabled[cat_id] == 0) {
        $(".item-details .price.elem").hide(0);
      }
    });
    </script>
  <?php } ?>


  <?php if($content_only == 0) { ?>
    <?php osc_current_web_theme_path('footer.php') ; ?>
  <?php } ?>
</body>
</html>				