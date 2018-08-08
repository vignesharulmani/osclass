<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="<?php echo str_replace('_', '-', osc_current_user_locale()); ?>">

<head>
  <?php osc_current_web_theme_path('head.php') ; ?>
  <meta name="robots" content="noindex, nofollow" />
  <meta name="googlebot" content="noindex, nofollow" />

  <?php 
    if(osc_images_enabled_at_items()) { 
      ItemForm::photos_javascript();
    }
  ?>
</head>

<?php $required_fields = strtolower(osc_get_preference('post_required', 'veronika_theme')); ?>

<body id="body-item-post">
  <h1 class="h1-error-fix"></h1>

  <?php osc_current_web_theme_path('header.php') ; ?>


  <div class="content add_item no-steps edit">
    <ul id="error_list" class="new-item"></ul>

    <form name="item" action="<?php echo osc_base_url(true);?>" method="post" enctype="multipart/form-data">
      <input type="hidden" name="action" value="item_edit_post" />
      <input type="hidden" name="page" value="item" />
      <input type="hidden" name="id" value="<?php echo osc_item_id(); ?>" /> 
      <input type="hidden" name="secret" value="<?php echo osc_item_secret(); ?>" />


      <fieldset class="general i-shadow round3" data-step-id="1">
        <h2>
          <span class="text"><?php _e('General', 'veronika'); ?></span>
          <span class="step round2"><?php _e('Step', 'veronika'); ?> 1 / <span class="total-steps">2</span></span>
        </h2>


        <!-- CATEGORY -->
        <?php //$category_type = osc_get_preference('publish_category', 'veronika_theme'); ?>
        <?php $category_type = 2; ?>

        <?php if($category_type == 1 || $category_type == '') { ?>

          <div class="row category flat">
            <label for="catId"><span><?php _e('Select Category', 'veronika'); ?></span><span class="req">*</span></label>
            <input id="catId" type="hidden" name="catId" value="<?php echo (veronika_get_session('sCategory') <> '' ? veronika_get_session('sCategory') : osc_item_category_id()); ?>">

            <div id="flat-cat">
              <?php echo veronika_flat_category_select(); ?>
            </div>

            <?php echo veronika_flat_categories(); ?>


          </div>

        <?php } else if($category_type == 2) { ?>

          <div class="row category multi">
            <label for="catId"><span><?php _e('Category', 'veronika'); ?></span><span class="req">*</span></label>
            <?php ItemForm::category_multiple_selects(null, Params::getParam('sCategory'), __('Select a category', 'veronika')); ?>
          </div>

        <?php } else if($category_type == 3) { ?>

          <div class="row category simple">
            <label for="catId"><span><?php _e('Category', 'veronika'); ?></span><span class="req">*</span></label>
            <?php ItemForm::category_select(null, Params::getParam('sCategory'), __('Select a category', 'veronika')); ?>
          </div>

        <?php } ?>


        <!-- TITLE & DESCRIPTION -->
        <div class="title-desc-box">
          <div class="row">
            <?php ItemForm::multilanguage_title_description(); ?>
          </div>
        </div>


        <!-- PRICE -->
        <?php if( osc_price_enabled_at_items() ) { ?>
          <div class="price-wrap">
            <div class="inside">
              <div class="enter">
                <div class="input-box">
                  <?php ItemForm::price_input_text(); ?>
                  <i class="fa fa-money"></i>
                  <?php echo veronika_simple_currency(); ?>
                </div>
              </div>
              
              <div class="selection">
                <?php echo veronika_simple_price_type(); ?>
              </div>
            </div>
          </div>
        <?php } ?>


        <!-- CONDITION & TRANSACTION -->
        <div class="status-wrap">
          <div class="transaction">
            <label for="sTransaction"><?php _e('Transaction', 'veronika'); ?></label>
            <?php echo veronika_simple_transaction(); ?>
          </div>

          <div class="condition">
            <label for="sCondition"><?php _e('Condition', 'veronika'); ?></label>
            <?php echo veronika_simple_condition(); ?>
          </div>
        </div>



        <?php 
          $item_extra = veronika_item_extra(osc_item_id());

          $prepare = array();
          $prepare['i_country'] = veronika_get_session('sCountry') <> '' ? veronika_get_session('sCountry') : osc_item_country_code();
          $prepare['i_region'] = veronika_get_session('sRegion') <> '' ? veronika_get_session('sRegion') : osc_item_region_id();
          $prepare['i_city'] = veronika_get_session('sCity') <> '' ? veronika_get_session('sCity') : osc_item_city_id();
          $prepare['s_phone'] = veronika_get_session('sPhone') <> '' ? veronika_get_session('sPhone') : $item_extra['s_phone'];
        ?>


        <!-- LOCATION -->
        <div class="location">
          <div class="row">
            <input type="hidden" name="countryId" id="sCountry" class="sCountry" value="<?php echo $prepare['i_country']; ?>"/>
            <input type="hidden" name="regionId" id="sRegion" class="sRegion" value="<?php echo $prepare['i_region']; ?>"/>
            <input type="hidden" name="cityId" id="sCity" class="sCity" value="<?php echo $prepare['i_city']; ?>"/>

            <label for="term">
              <?php _e('Location', 'veronika'); ?>
              <?php if(strpos($required_fields, 'location') !== false || strpos($required_fields, 'country') !== false || strpos($required_fields, 'region') !== false || strpos($required_fields, 'city') !== false) { ?>
                <span class="req">*</span>
              <?php } ?>
            </label>

            <div id="location-picker">
              <input type="text" name="term" id="term" class="term" placeholder="<?php _e('Country, Region or City', 'veronika'); ?>" value="<?php echo veronika_get_term(veronika_get_session('term'), veronika_get_session('sCountry'), $prepare['i_region'], $prepare['i_city']); ?>" autocomplete="off"/>
              <div class="shower-wrap">
                <div class="shower" id="shower">
                  <div class="option service min-char"><?php _e('Type country, region or city', 'veronika'); ?></div>
                </div>
              </div>

              <div class="loader"></div>
            </div>
          </div>

          <div class="row">
            <label for="address"><?php _e('City Area', 'veronika'); ?></label>
            <div class="input-box"><?php ItemForm::city_area_text(); ?><i class="fa fa-map-pin"></i></div>
          </div>

          <div class="row">
            <label for="address"><?php _e('ZIP', 'veronika'); ?></label>
            <div class="input-box"><?php ItemForm::zip_text(); ?><i class="fa fa-map-signs"></i></div>
          </div>

          <div class="row">
            <label for="address"><?php _e('Address', 'veronika'); ?></label>
            <div class="input-box"><?php ItemForm::address_text(); ?><i class="fa fa-home"></i></div>
          </div>
        </div>


        <!-- SELLER INFORMATION -->
        <div class="seller<?php if(osc_is_web_user_logged_in() ) { ?> logged<?php } ?>">
          <div class="row">
            <label for="contactName"><?php _e('Your Name', 'veronika'); ?><?php if(strpos($required_fields, 'name') !== false) { ?><span class="req">*</span><?php } ?></label>
            <div class="input-box"><?php ItemForm::contact_name_text(); ?><i class="fa fa-user"></i></div>
          </div>
        
          <div class="row">
            <label for="phone"><?php _e('Mobile Phone', 'veronika'); ?><?php if(strpos($required_fields, 'phone') !== false) { ?><span class="req">*</span><?php } ?></label>
            <div class="input-box"><input type="tel" id="sPhone" name="sPhone" value="<?php echo $prepare['s_phone']; ?>" /><i class="fa fa-phone"></i></div>
          </div>

          <div class="row user-email">
            <label for="contactEmail"><span><?php _e('E-mail', 'veronika'); ?></span><span class="req">*</span></label>
            <div class="input-box"><?php ItemForm::contact_email_text(); ?><i class="fa fa-at"></i></div>

            <div class="mail-show">
              <div class="input-box-check">
                <?php ItemForm::show_email_checkbox() ; ?>
                <label for="showEmail" class="label-mail-show"><?php _e('Show email on listing page', 'veronika'); ?></label>
              </div>
            </div>
          </div>
        </div>

        <div class="post-navigation">
          <div class="post-next btn btn-primary" data-current-step="1" data-next-step="2"><?php _e('Next step', 'veronika'); ?> <i class="fa fa-angle-right"></i></div>
        </div>
      </fieldset>




      <fieldset class="photos i-shadow round3" data-step-id="2">
        <h2>
          <span class="text"><?php _e('Pictures', 'veronika'); ?></span>
          <span class="step round2"><?php _e('Step', 'veronika'); ?> 2 / <span class="total-steps">2</span></span>
        </h2>

        <div class="box photos photoshow drag_drop">
          <div id="photos">
            <div class="photo-left">
              <label><?php _e('Click to upload', 'veronika'); ?></label>
              <span class="text"><?php _e('You can upload up to', 'veronika'); ?> <?php echo osc_max_images_per_item(); ?> <?php _e('pictures per listing', 'veronika'); ?></span>
            </div>

            <?php 
              if(osc_images_enabled_at_items()) { 
                if(veronika_ajax_image_upload()) { 
                  ItemForm::ajax_photos();
                } 
              } 
            ?>
          </div>
        </div>

        <div class="post-navigation">
          <div class="post-prev btn btn-secondary" data-current-step="2" data-prev-step="1"><i class="fa fa-angle-left"></i> <?php _e('Previous step', 'veronika'); ?></div>
          <div class="post-next btn btn-primary" data-current-step="2" data-next-step="3"><?php _e('Next step', 'veronika'); ?> <i class="fa fa-angle-right"></i></div>
        </div>
      </fieldset>
 



      <fieldset class="hook-block i-shadow round3" data-step-id="3">
        <h2>
          <span class="text"><?php _e('Details', 'veronika'); ?></span>
          <span class="step round2"><?php _e('Step', 'veronika'); ?> 3 / 3</span>
        </h2>

        <div id="post-hooks">
          <?php ItemForm::plugin_edit_item(); ?>
        </div>

        <div class="post-navigation">
          <div class="post-prev btn btn-secondary" data-current-step="3" data-prev-step="2"><i class="fa fa-angle-left"></i> <?php _e('Previous step', 'veronika'); ?></div>
        </div>
      </fieldset>


      <div class="buttons-block">
        <div class="inside">
          <div class="box">
            <div class="row">
              <?php veronika_show_recaptcha(); ?>
            </div>
          </div>

          <div class="clear"></div>

          <button type="submit" class="btn btn-primary"><?php _e('Save', 'veronika'); ?></button>
        </div>
      </div>
    </form>
  </div>



  <script type="text/javascript">
    // HIDE THEME EXTRA FIELDS (Transaction, Condition, Status) ON EXCLUDED CATEGORIES 

    var catExtraVeronikaHide = new Array();
    <?php 
      $e_cat = trim(osc_get_preference('post_extra_exclude', 'veronika_theme'));
      $e_array = explode(',', $e_cat);
      $e_array = array_map('trim', $e_array);
      $e_array = array_filter($e_array);

      if(!empty($e_array) && count($e_array) > 0) {
        foreach($e_array as $e) {
          if(is_numeric($e)) {
            echo 'catExtraVeronikaHide[' . $e . '] = 1;';
          }
        }
      }
    ?>


    <?php if(osc_is_web_user_logged_in() ) { ?>
      // SET READONLY FOR EMAIL AND NAME FOR LOGGED IN USERS
      $('input[name="contactName"]').attr('readonly', true);
      $('input[name="contactEmail"]').attr('readonly', true);
    <?php } ?>


    // LANGUAGE TABS
    tabberAutomatic();



    // JAVASCRIPT FOR PRICE ALTERNATIVES
    $(document).ready(function(){
      $('input#price').attr('autocomplete', 'off');         // Disable autocomplete for price field
      $('input#price').attr('placeholder', '<?php echo osc_esc_js(__('Price', 'veronika')); ?>');         

      $('body').on('click change', '.simple-price-type .option, .simple-price-type > select.text', function() {

        if( $('.simple-price-type > select.text').length ) {
          var type = $(this).val();
        } else {
          var type = $(this).attr('data-id');
        }

        if(type == 1 || type == 2) {
          $('.add_item .price-wrap .enter').addClass('disabled');
          $('.add_item .price-wrap .enter input#price').attr('readonly', true).attr('placeholder', '');;
          $('.add_item .price-wrap .enter .simple-select').addClass('disabled');

          if(type == 1) {
            $('input#price').val("0");
          } else {
            $('input#price').val("");
          }

        } else {
          $('.add_item .price-wrap .enter').removeClass('disabled');
          $('.add_item .price-wrap .enter input#price').attr('readonly', false).attr('placeholder', '<?php echo osc_esc_js(__('Price', 'veronika')); ?>');;
          $('.add_item .price-wrap .enter .simple-select').removeClass('disabled');
          $('input#price').val("");
        }
      });
    });


    // If no category loaded at start, hide hook section
    if( $('#catId').val() != "" && parseInt($('#catId').val()) > 0 ) {
      $('fieldset.hooks').show(0);
      $('span.total-steps').text('3');
    }


    // Trigger click when category selected via flat category select
    $('body').on('click change', 'input[name="catId"], select#catId', function() {
      var cat_id = $(this).val();
      var url = '<?php echo osc_base_url(); ?>index.php';
      var result = '';

      if(cat_id != '' && cat_id != 0) {
        if(catPriceEnabled[cat_id] == 1) {
          $(".add_item .price-wrap").fadeIn(200);
        } else {
          $(".add_item .price-wrap").fadeOut(200);
          $('#price').val('') ;
        }

        if(catExtraVeronikaHide[cat_id] == 1) {
          $(".add_item .status-wrap").fadeOut(200);
          $('input[name="sTransaction"], input[name="sCondition"]').val('');
          $('#sTransaction option, #sCondition option').prop('selected', function() {
            return this.defaultSelected;
          });

          $('.simple-transaction span.text span').text($('.simple-transaction .list .option.bold').text());
          $('.simple-condition span.text span').text($('.simple-condition .list .option.bold').text());
          $('.simple-transaction .list .option, .simple-condition .list .option').removeClass('selected');
          $('.simple-transaction .list .option.bold, .simple-condition .list .option.bold').addClass('selected');

        } else {
          $(".add_item .status-wrap").fadeIn(200);
        }

        $.ajax({
          type: "POST",
          url: url,
          data: 'page=ajax&action=runhook&hook=item_form&catId=' + cat_id,
          dataType: 'html',
          success: function(data){
            $("#plugin-hook").html(data);

            if (data.indexOf("input") >= 0 || data.indexOf("select") >= 0 || data.indexOf("textarea") >= 0) { 

              // There are some plugins hooked
              $('fieldset.hooks').show(0);
              $('span.total-steps').text('3');

            } else { 

              // There are no plugins hooked
              $('fieldset.hooks').hide(0);
              $('span.total-steps').text('2');

            }
          }
        });
      }
    });



    // ADD CAMERA ICON TO PICTURE BOX
    $(document).ready(function(){
      setInterval(function(){ 
        $('input[name="qqfile"]').prop('accept', 'image/*');
        $("img[src$='uploads/temp/']").closest('.qq-upload-success').remove();
        
        if( !$('#photos > .qq-upload-list > li').length ) {
          $('#photos > .qq-upload-list').remove();
          $('#photos > h3').remove();
        }
      }, 250);
      
      $('#photos .qq-upload-button > div').remove();
      $('#photos .qq-upload-button').append('<div class="sample-box-wrap"></div>');

      var max_images = Math.max(<?php echo osc_max_images_per_item(); ?> - $('.qq-upload-list .qq-upload-success').length, 1);

      for(i = 0; i < max_images; i++) {  
        $('#photos .qq-upload-button .sample-box-wrap').append('<div class="sample-box tr1"><div class="ins tr1"><i class="fa fa-camera tr1"></i></i></div></div>');
      }

      $('#photos .qq-upload-button .sample-box-wrap').live('click', function(){
        $('#photos .qq-upload-button input').click();
      });




      // TITLE REMAINING CHARACTERS
      var title_max = <?php echo osc_max_characters_per_title(); ?>;
      var check_inputs = $('.add_item .title input');

      check_inputs.attr('maxlength', title_max);
      check_inputs.after('<div class="title-max-char max-char"></div>');
      $('.title-max-char').html(title_max + ' ' + '<?php echo osc_esc_js(__('more', 'veronika')); ?>');

      $('ul.tabbernav li a').live('click', function(){
        var title_length = 0;

        check_inputs.each(function(){
          if( $(this).val().length > title_length ) {
            title_length = $(this).val().length;
          }
        });

        var title_remaining = title_max - title_length;

        $('.title-max-char').html(title_remaining + ' ' + '<?php echo osc_esc_js(__('more', 'veronika')); ?>');

        $('.title-max-char').removeClass('orange').removeClass('red');
        if(title_remaining/title_length <= 0.2 && title_remaining/title_length > 0.1) {
          $('.title-max-char').addClass('orange');
        } else if (title_remaining/title_length <= 0.1) {
          $('.title-max-char').addClass('red');
        }
      });

      check_inputs.keyup(function() {
        var title_length = $(this).val().length;
        var title_remaining = title_max - title_length;

        $('.title-max-char').html(title_remaining + ' ' + '<?php echo osc_esc_js(__('more', 'veronika')); ?>');

        $('.title-max-char').removeClass('orange').removeClass('red');
        if(title_remaining/title_length <= 0.2 && title_remaining/title_length > 0.1) {
          $('.title-max-char').addClass('orange');
        } else if (title_remaining/title_length <= 0.1) {
          $('.title-max-char').addClass('red');
        }
      });



      // DESCRIPTION REMAINING CHARACTERS
      var desc_max = <?php echo osc_max_characters_per_description(); ?>;
      var check_textareas = $('.add_item .description textarea');

      check_textareas.attr('maxlength', desc_max);
      check_textareas.after('<div class="desc-max-char max-char"></div>');
      $('.desc-max-char').html(desc_max + ' ' + '<?php echo osc_esc_js(__('more', 'veronika')); ?>');

      $('ul.tabbernav li a').live('click', function(){
        var desc_length = 0;

        check_textareas.each(function(){
          if( $(this).val().length > desc_length ) {
            desc_length = $(this).val().length;
          }
        });

        var desc_remaining = desc_max - desc_length;

        $('.desc-max-char').html(desc_remaining + ' ' + '<?php echo osc_esc_js(__('more', 'veronika')); ?>');

        $('.desc-max-char').removeClass('orange').removeClass('red');

        if(desc_remaining/desc_length <= 0.3 && desc_remaining/desc_length > 0.15) {
          $('.desc-max-char').addClass('orange');
        } else if (desc_remaining/desc_length <= 0.15) {
          $('.desc-max-char').addClass('red');
        }
      });

      check_textareas.keyup(function() {
        var desc_length = $(this).val().length;
        var desc_remaining = desc_max - desc_length;

        $('.desc-max-char').html(desc_remaining + ' ' + '<?php echo osc_esc_js(__('more', 'veronika')); ?>');

        $('.desc-max-char').removeClass('orange').removeClass('red');
        if(desc_remaining/desc_length <= 0.3 && desc_remaining/desc_length > 0.15) {
          $('.desc-max-char').addClass('orange');
        } else if (desc_remaining/desc_length <= 0.15) {
          $('.desc-max-char').addClass('red');
        }
      });
    });


    // CATEGORY CHECK IF PARENT
    <?php if(!osc_selectable_parent_categories()) { ?>
      $(document).ready(function(){
        if(typeof window['categories_' + $('#catId').val()] !== 'undefined'){
          if(eval('categories_' + $('#catId').val()) != '') {
            $('#catId').val('');
          }
        }
      });

      $('#catId').live('change', function(){
        if(typeof window['categories_' + $(this).val()] !== 'undefined'){
          if(eval('categories_' + $(this).val()) != '') {
            $(this).val('');
          }
        }
      });
    <?php } ?>



    // Set forms to active language
    $(document).ready(function(){

      var post_timer = setInterval(veronika_check_lang, 250);

      function veronika_check_lang() {
        if($('.tabbertab').length > 1 && $('.tabbertab.tabbertabhide').length) {
          var l_active = veronikaCurrentLocale;
          l_active = l_active.trim();

          $('.tabbernav > li > a:contains("' + l_active + '")').click();

          clearInterval(post_timer);
          return;
        }
      }



      // Code for form validation
      $("form[name=item]").validate({
        rules: {
          "title[<?php echo osc_current_user_locale(); ?>]": {
            required: true,
            minlength: 5
          },

          "description[<?php echo osc_current_user_locale(); ?>]": {
            required: true,
            minlength: 10
          },

          <?php if(strpos($required_fields, 'location') !== false) { ?>
          term: {
            required: true,
            minlength: 3
          },
          <?php } ?>

          <?php if(strpos($required_fields, 'country') !== false) { ?>
          countryId: {
            required: true
          },
          <?php } ?>

          <?php if(strpos($required_fields, 'region') !== false) { ?>
          regionId: {
            required: true
          },
          <?php } ?>

          <?php if(strpos($required_fields, 'city') !== false) { ?>
          cityId: {
            required: true
          },
          <?php } ?>

          <?php if(function_exists('ir_get_min_img')) { ?>
          ir_image_check: {
            required: true,
            min: <?php echo ir_get_min_img(); ?>
          },
          <?php } ?>

          catId: {
            required: true,
            digits: true
          },

          "photos[]": {
            accept: "png,gif,jpg,jpeg"
          },

          <?php if(strpos($required_fields, 'name') !== false) { ?>
          contactName: {
            required: true,
            minlength: 3
          },
          <?php } ?>

          <?php if(strpos($required_fields, 'phone') !== false) { ?>
          sPhone: {
            required: true,
            minlength: 6
          },
          <?php } ?>

          contactEmail: {
            required: true,
            email: true
          }
        },

        messages: {
          "title[<?php echo osc_current_user_locale(); ?>]": {
            required: '<?php echo osc_esc_js(__('Title: this field is required.', 'veronika')); ?>',
            minlength: '<?php echo osc_esc_js(__('Title: enter at least 5 characters.', 'veronika')); ?>'
          },

          "description[<?php echo osc_current_user_locale(); ?>]": {
            required: '<?php echo osc_esc_js(__('Description: this field is required.', 'veronika')); ?>',
            minlength: '<?php echo osc_esc_js(__('Description: enter at least 10 characters.', 'veronika')); ?>'
          },

          <?php if(strpos($required_fields, 'location') !== false) { ?>
          term: {
            required: '<?php echo osc_esc_js(__('Location: select country, region or city.', 'veronika')); ?>',
            minlength: '<?php echo osc_esc_js(__('Location: enter at least 3 characters to get list.', 'veronika')); ?>'
          },
          <?php } ?>

          <?php if(strpos($required_fields, 'country') !== false) { ?>
          countryId: {
            required: '<?php echo osc_esc_js(__('Location: select country from location field.', 'veronika')); ?>'
          },
          <?php } ?>

          <?php if(strpos($required_fields, 'region') !== false) { ?>
          regionId: {
            required: '<?php echo osc_esc_js(__('Location: select region from location field.', 'veronika')); ?>'
          },
          <?php } ?>

          <?php if(strpos($required_fields, 'city') !== false) { ?>
          cityId: {
            required: '<?php echo osc_esc_js(__('Location: select city from location field.', 'veronika')); ?>'
          },
          <?php } ?>

          <?php if(function_exists('ir_get_min_img')) { ?>
          ir_image_check: {
            required: '<?php echo osc_esc_js(__('Pictures: you need to upload pictures.', 'veronika')); ?>',
            min: '<?php echo osc_esc_js(__('Pictures: upload at least.') . ' ' . ir_get_min_img() . ' ' . __('picture(s).')); ?>'
          },
          <?php } ?>

          catId: '<?php echo osc_esc_js(__('Category: this field is required.', 'veronika')); ?>',

          "photos[]": {
             accept: '<?php echo osc_esc_js(__('Photo: must be png,gif,jpg,jpeg.', 'veronika')); ?>'
          },

          <?php if(strpos($required_fields, 'phone') !== false) { ?>
          sPhone: {
            required: '<?php echo osc_esc_js(__('Phone: this field is required.', 'veronika')); ?>',
            minlength: '<?php echo osc_esc_js(__('Phone: enter at least 6 characters.')); ?>'
          },
          <?php } ?>

          <?php if(strpos($required_fields, 'name') !== false) { ?>
          contactName: {
            required: '<?php echo osc_esc_js(__('Your Name: this field is required.', 'veronika')); ?>',
            minlength: '<?php echo osc_esc_js(__('Your Name: enter at least 3 characters.')); ?>'
          },
          <?php } ?>

          contactEmail: {
            required: '<?php echo osc_esc_js(__('Email: this field is required.', 'veronika')); ?>',
            email: '<?php echo osc_esc_js(__('Email: invalid format of email address.', 'veronika')); ?>'
          }
        },

        ignore: ":disabled",
        ignoreTitle: false,
        errorLabelContainer: "#error_list",
        wrapper: "li",
        invalidHandler: function(form, validator) {
          $('html,body').animate({ scrollTop: $('h1').offset().top }, { duration: 250, easing: 'swing'});
        },
        submitHandler: function(form){
          $('button[type=submit], input[type=submit]').attr('disabled', 'disabled');
          form.submit();
        }
      });
    });

  </script>


  <?php osc_current_web_theme_path('footer.php') ; ?>
</body>
</html>	