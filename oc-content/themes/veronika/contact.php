<?php 
  // INTERNAL USE FOR AJAX. IF NO AJAX, REDIRECT USER TO REGISTER PAGE - AUTHENTIFICATION CENTRAL
  if(isset($_GET['ajaxRequest']) && $_GET['ajaxRequest'] == '1') {

    // GET LOCATIONS FOR LOCATION PICKER VIA AJAX
    if(isset($_GET['ajaxLoc']) && $_GET['ajaxLoc'] == '1' && isset($_GET['term']) && $_GET['term'] <> '') {
      $term = $_GET['term'];
      $max = 12;

      $sql = '
        (SELECT "country" as type, s_name as name, null as name_top, null as city_id, null as region_id, pk_c_code as country_code  FROM ' . DB_TABLE_PREFIX . 't_country WHERE s_name like "' . $term . '%")
        UNION ALL
        (SELECT "region" as type, s_name as name, null as name_top, null as city_id, pk_i_id  as region_id, fk_c_country_code as country_code  FROM ' . DB_TABLE_PREFIX . 't_region WHERE s_name like "' . $term . '%")
        UNION ALL
        (SELECT "city" as type, c.s_name as name, r.s_name as name_top, c.pk_i_id as city_id, c.fk_i_region_id as region_id, c.fk_c_country_code as country_code  FROM ' . DB_TABLE_PREFIX . 't_city c, ' . DB_TABLE_PREFIX . 't_region r WHERE c.s_name like "' . $term . '%" AND c.fk_i_region_id = r.pk_i_id limit ' . $max . ')
        UNION ALL
        (SELECT "city_more" as type, count(pk_i_id) - ' . $max . ' as name, null as name_top, null as city_id, null as region_id, null as country_code  FROM ' . DB_TABLE_PREFIX . 't_city WHERE s_name like "' . $term . '%")
      ';

      $result = City::newInstance()->dao->query($sql);

      if( !$result ) { 
        $prepare = array(); 
      } else {
        $prepare = $result->result();
      }

      echo json_encode($prepare);
      exit;
    }


    // CLEAR COOKIES VIA AJAX
    if(isset($_GET['clearCookieAll']) && $_GET['clearCookieAll'] == 'done') {
      mb_set_cookie('veronika-sCategory', '');
      //mb_set_cookie('veronika-sPattern', '');
      //mb_set_cookie('veronika-sPriceMin', '');
      //mb_set_cookie('veronika-sPriceMax', '');
      mb_set_cookie('veronika-sCountry', '');
      mb_set_cookie('veronika-sRegion', '');
      mb_set_cookie('veronika-sCity', '');
      exit;
    }



    // GET ITEMS FOR AUTOCOMPLETE VIA AJAX
    if(isset($_GET['ajaxItem']) && $_GET['ajaxItem'] == '1' && isset($_GET['pattern']) && $_GET['pattern'] <> '') {
      $pattern = $_GET['pattern'];
      $max = 12;

      $db_prefix = DB_TABLE_PREFIX;
      $sql = "
        SELECT i.pk_i_id, d.s_title, i.i_price, i.fk_c_currency_code, CONCAT(r.s_path, r.pk_i_id,'_thumbnail.',r.s_extension) as image_url
        FROM {$db_prefix}t_item i
        INNER JOIN {$db_prefix}t_item_description d
        ON d.fk_i_item_id = i.pk_i_id
        LEFT OUTER JOIN {$db_prefix}t_item_resource r
        ON r.fk_i_item_id = i.pk_i_id AND r.pk_i_id = (
          SELECT rs.pk_i_id
          FROM {$db_prefix}t_item_resource rs
          WHERE i.pk_i_id = rs.fk_i_item_id
          LIMIT 1
        )

        WHERE d.fk_c_locale_code = '" . osc_current_user_locale() . "' AND (s_title LIKE '%" . $pattern . "%' OR s_description LIKE '%" . $pattern . "%') AND b_active = 1 AND b_enabled = 1 AND b_spam = 0
        ORDER BY dt_pub_date DESC
        LIMIT " . $max . ";
      ";

      $result = Item::newInstance()->dao->query($sql);

      if( !$result ) { 
        $prepare = array(); 
      } else {
        $prepare = $result->result();
      }

      foreach( $prepare as $i => $p ) {
        $prepare[$i]['s_title'] = str_ireplace($pattern, '<b>' . $pattern . '</b>', $prepare[$i]['s_title']);
        $prepare[$i]['i_price'] = veronika_ajax_item_format_price($prepare[$i]['i_price'], $prepare[$i]['fk_c_currency_code']);
        $prepare[$i]['item_url'] = osc_item_url_ns($prepare[$i]['pk_i_id']);
        if($prepare[$i]['image_url'] <> '') {
          $prepare[$i]['image_url'] = osc_base_url() . $prepare[$i]['image_url'];
        } else {
          $prepare[$i]['image_url'] = osc_current_web_theme_url('images/no-image.png');
        }
      }

      echo json_encode($prepare);
      exit;
    }



    // INCREASE CLICK COUNT ON PHONE NUMBER
    if(isset($_GET['ajaxPhoneClick']) && $_GET['ajaxPhoneClick'] == '1' && isset($_GET['itemId']) && $_GET['itemId'] > 0) {
      veronika_increase_clicks($_GET['itemId'], $_GET['itemUserId']);
      echo 1;
      exit;
    }

 } else {
    // No ajax requested, show contact page
?>


  <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
  <html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="<?php echo str_replace('_', '-', osc_current_user_locale()); ?>">
  <head>
    <?php osc_current_web_theme_path('head.php') ; ?>
    <meta name="robots" content="noindex, nofollow" />
    <meta name="googlebot" content="noindex, nofollow" />
    <script type="text/javascript" src="<?php echo osc_current_web_theme_js_url('jquery.validate.min.js') ; ?>"></script>
  </head>

  <body id="body-contact">
    <?php osc_current_web_theme_path('header.php') ; ?>
    <div id="contact-wrap" class="content cont_us">
      <h1>&nbsp;</h1>

      <h2 class="contact">
        <span><?php _e("Contact us", 'veronika'); ?></span>
      </h2>

      <div id="contact-ins" class="inner round3">
        <div class="div-desc">
          <div class="wrap">
            <img src="<?php echo osc_current_web_theme_url('images/profile-support.png'); ?>" />
            <span><?php _e('Question? Need more info? Send us message...', 'veronika'); ?></span>
          </div>
        </div>

        <div class="clear"></div>

        <ul id="error_list"></ul>
        <form action="<?php echo osc_base_url(true) ; ?>" method="post" name="contact_form" id="contact" <?php if(osc_contact_attachment()) { echo 'enctype="multipart/form-data"'; };?>>
          <input type="hidden" name="page" value="contact" />
          <input type="hidden" name="action" value="contact_post" />

          <?php if(osc_is_web_user_logged_in()) { ?>
            <input type="hidden" name="yourName" value="<?php echo osc_esc_html( osc_logged_user_name() ); ?>" />
            <input type="hidden" name="yourEmail" value="<?php echo osc_logged_user_email();?>" />
          <?php } else { ?>
            <label for="yourName"><span><?php _e('Your name', 'veronika'); ?></span></label> 
            <span class="input-box"><i class="fa fa-user"></i><?php ContactForm::your_name(); ?></span>

            <label for="yourEmail"><span><?php _e('Your e-mail address', 'veronika'); ?></span><div class="req">*</div></label>
            <span class="input-box"><i class="fa fa-envelope"></i><?php ContactForm::your_email(); ?></span>
          <?php } ?>

          <label for="subject"><span><?php _e("Subject", 'veronika'); ?></span><div class="req">*</div></label>
          <span class="input-box"><i class="fa fa-pencil"></i><?php ContactForm::the_subject(); ?></span>

          <label for="message"><span><?php _e("Message", 'veronika'); ?></span><div class="req">*</div></label>
          <span class="input-box last"><?php ContactForm::your_message(); ?></span>


          <?php if(osc_contact_attachment()) { ?>
            <div class="attachment">
              <div class="att-box">
                <label class="status">
                  <span class="wrap"><i class="fa fa-paperclip"></i> <span><?php _e('Upload file', 'veronika'); ?></span></span>
                  <?php ContactForm::your_attachment(); ?>
                </label>
              </div>

              <div class="text"><?php _e('Allowed extensions:', 'veronika'); ?> <?php echo osc_allowed_extension(); ?>.</div>
              <div class="text"><?php _e('Maximum size:', 'veronika'); ?> <?php echo round(osc_max_size_kb()/1000, 1); ?>Mb.</div>
            </div>
          <?php } ?>

          <div class="req-what"><div class="req">*</div><div class="small-info"><?php _e('This field is required', 'veronika'); ?></div></div>

          <?php veronika_show_recaptcha(); ?>

          <button type="submit" id="blue"><?php _e('Send message', 'veronika'); ?></button>
        </fieldset>
        </form>
      </div>
    </div>


    <?php ContactForm::js_validation() ; ?>
    <?php osc_current_web_theme_path('footer.php') ; ?>
  </body>
  </html>

<?php } ?>