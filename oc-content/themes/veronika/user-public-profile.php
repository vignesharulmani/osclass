<?php
  $address = '';
  if(osc_user_address()!='') {
    $address = osc_user_address();
  }

  $location = veronika_get_full_loc(osc_user_field('fk_c_country_code'), osc_user_region_id(), osc_user_city_id());

  if(osc_user_zip() <> '') {
    $location .= ' ' . osc_user_zip();
  }

  $user_keep = osc_user();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="<?php echo str_replace('_', '-', osc_current_user_locale()); ?>">
<head>
  <?php osc_current_web_theme_path('head.php') ; ?>
  <meta name="robots" content="noindex, nofollow" />
  <meta name="googlebot" content="noindex, nofollow" />
  <script type="text/javascript" src="<?php echo osc_current_web_theme_js_url('jquery.validate.min.js') ; ?>"></script>
</head>
<body id="body-user-public-profile">
  <?php View::newInstance()->_exportVariableToView('user', $user_keep); ?>
  <?php osc_current_web_theme_path('header.php') ; ?>

  <div class="content user_public_profile">
    <!-- RIGHT BLOCK -->
    <div id="right-block">
      <!-- SELLER INFORMATION -->
      <div id="description">
        <?php if(function_exists('profile_picture_show')) { ?>
          <div class="pp-img"><?php profile_picture_show(120);?></div>
        <?php } ?>

        <ul id="user_data">
          <li class="name"><?php echo osc_user_name(); ?></li>
          <?php if ( osc_user_phone_mobile() != "" ) { ?><li><span class="left"><?php _e('Mobile', 'veronika'); ?>:</span><span class="right"><?php echo osc_user_phone_mobile() ; ?></span></li><?php } ?>
          <?php if ( osc_user_phone() != "" && osc_user_phone() != osc_user_phone_mobile() ) { ?><li><span class="left"><?php _e('Phone', 'veronika'); ?>:</span><span class="right"><?php echo osc_user_phone() ; ?></span></li><?php } ?>                    
          <?php if ($address != '') { ?><li><span class="left"><?php _e('Address', 'veronika'); ?>:</span><span class="right"><?php echo $address; ?></span></li><?php } ?>
          <?php if ($location != '') { ?><li><span class="left"><?php _e('Location', 'veronika'); ?>:</span><span class="right"><?php echo $location; ?></span></li><?php } ?>
          <?php if (osc_user_website() != '') { ?><li><span class="left"><?php _e('Website', 'veronika'); ?>:</span><span class="right"><a href="<?php echo osc_user_website(); ?>" target="_blank" rel="nofollow"><?php echo osc_user_website(); ?></a></span></li><?php } ?>
          <?php if (osc_user_info() <> '') { ?><li class="desc"><?php echo osc_user_info(); ?></li><?php } ?>
        </ul>
      </div>


      <!-- CONTACT SELLER BLOCK -->
      <div class="pub-contact-wrap">
        <div class="ins">
          <?php if(osc_user_id() == osc_logged_user_id()) { ?>
            <div class="empty"><?php _e('This is your public profile and therefore contact form is disabled for you', 'veronika'); ?></div>
          <?php } else { ?>
            <?php if(osc_reg_user_can_contact() && osc_is_web_user_logged_in() || !osc_reg_user_can_contact() ) { ?>
              <a id="pub-contact" href="<?php echo osc_item_send_friend_url(); ?>" class="btn btn-primary" rel="<?php echo osc_user_id(); ?>"><?php _e('Contact seller', 'veronika'); ?></a>
            <?php } ?>
          <?php } ?>
        </div>
      </div>
    </div>


    <!-- Item Banner #1 -->
    <?php //echo veronika_banner('public', 1); ?>


    <!-- LISTINGS OF SELLER -->
    <div id="public-items" class="white">
      <h1><?php _e('Latest items of seller', 'veronika'); ?></h1>

      <?php if( osc_count_items() > 0) { ?>
        <div class="block">
          <div class="wrap">
            <?php $c = 1; ?>
            <?php while( osc_has_items() ) { ?>
              <?php veronika_draw_item($c, 'gallery'); ?>
        
              <?php $c++; ?>
            <?php } ?>
          </div>
        </div>
      <?php } else { ?>
        <div class="empty"><?php _e('No listings posted by this seller', 'veronika'); ?></div>
      <?php } ?>
    </div>
  </div>


  <?php osc_current_web_theme_path('footer.php') ; ?>
</body>
</html>