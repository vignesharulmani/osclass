    <?php
      osc_show_widgets('footer');
      $sQuery = __('Search in', 'veronika') . ' ' . osc_total_active_items() . ' ' .  __('listings', 'veronika');
    ?>
  </div>
</div>


<?php osc_run_hook('footer') ; ?>

<?php if ( veronika_is_demo() ) { ?>
  <div id="piracy" class="noselect" title="Click to hide this box">This theme is ownership of MB Themes and can be bought only on <a href="https://osclasspoint.com/graphic-themes/general/veronika-osclass-theme_i63">OsclassPoint.com</a>. When bought on other site, there is no support and updates provided. Do not support stealers, support developer!</div>
  <script>$(document).ready(function(){ $('#piracy').click(function(){ $(this).fadeOut(200); }); });</script>
<?php } ?>


<?php if(osc_is_home_page() || osc_is_search_page() || osc_is_ad_page()) { ?>
  <a class="mobile-post is767" href="<?php echo osc_item_post_url(); ?>"><i class="fa fa-plus"></i></a>
<?php } ?>

<?php if(osc_is_search_page()) { ?>
  <?php osc_get_latest_searches() ?>
  <?php if(osc_count_latest_searches() > 0) { ?>
    <div id="latest-search">
      <div class="inside">
        <span><?php _e('Recent Searches', 'veronika'); ?>:</span>

        <?php while( osc_has_latest_searches() ) { ?>
          <a href="<?php echo osc_search_url(array('page' => 'search', 'sPattern' => osc_latest_search_text())); ?>"><?php echo osc_latest_search_text(); ?></a>
        <?php } ?>
      </div>
    </div>
  <?php } ?>
<?php } ?>



<!-- PARTNERS SECTION IN FOOTER -->
<div id="footer-partner" class="not767">
  <div class="inside">
    <div id="partner">
      <div class="lead"><?php _e('Payment Methods', 'veronika'); ?></div>

      <?php 
        $partner_path = osc_base_path() . 'oc-content/themes/' . osc_current_web_theme() . '/images/partner-logos'; 
        $partner_url = osc_base_url() . 'oc-content/themes/' . osc_current_web_theme() . '/images/partner-logos'; 
        $partner_images = scandir($partner_path);

        if(isset($partner_images) && !empty($partner_images) && $partner_images <> '') {
          foreach($partner_images as $img) {
            $ext = strtolower(pathinfo($partner_path . '/' . $img, PATHINFO_EXTENSION));
            $allowed_ext = array('png', 'jpg', 'jpeg', 'gif');

            if(in_array($ext, $allowed_ext)) {
              echo '<img class="partner-image" src="' . $partner_url . '/' . $img . '" alt="' . __('Our partner logo', 'veronika') . '" />';
            }
          }
        }
      ?>
    </div>
  </div>
</div>



<div id="footer">
  <div class="inside">
    <div class="left">
      <div class="links">
        <?php osc_reset_static_pages(); ?>
        <?php while(osc_has_static_pages()) { ?>
          <span class="link-span"><a href="<?php echo osc_static_page_url(); ?>" title="<?php echo osc_esc_html(osc_static_page_title()); ?>"><?php echo ucfirst(osc_static_page_title());?></a></span>
        <?php } ?>

        <span class="link-span contact"><a href="<?php echo osc_contact_url(); ?>"><?php _e('Contact', 'veronika'); ?></a></span>
      </div>

      <?php if(osc_get_preference('site_info', 'veronika_theme') <> '') { ?>
        <div class="info">
          <?php echo osc_get_preference('site_info', 'veronika_theme'); ?>
        </div>
      <?php } ?>

      <div class="about">
        <span><a href="<?php echo osc_contact_url(); ?>"><?php _e('Contact', 'veronika'); ?></a></span>

        <?php if(osc_get_preference('footer_email', 'veronika_theme') <> '') { ?>
          <span><?php _e('Mail us to', 'veronika'); ?> <a href="mailto:<?php echo osc_esc_html(osc_get_preference('footer_email', 'veronika_theme')); ?>"><?php echo osc_get_preference('footer_email', 'veronika_theme'); ?></a></span>
        <?php } ?>

        <?php if(osc_get_preference('footer_link', 'veronika_theme')) { ?>
          <span class="powered"><a href="https://osclasspoint.com">Osclass Themes</a></span>
          <span class="created"><a href="https://mb-themes.com/">MB themes</a></span>
        <?php } ?> 

        <!--<span class="copy">&copy; <?php echo date("Y"); ?> <?php echo osc_esc_html( osc_get_preference('website_name', 'veronika_theme') ); ?></span>-->
      </div>
    </div>

    <div class="right">
      <div class="location">
        <?php $regions = RegionStats::newInstance()->listRegions('%%%%', '>', 'i_num_items DESC'); ?>
        <?php $i = 1; ?>
        <?php foreach($regions as $r) { ?>
          <?php if($i <= 12) { ?>
            <span class="region-span"><a href="<?php echo osc_search_url(array('page' => 'search', 'sRegion' => $r['pk_i_id']));?>"><?php echo $r['s_name']; ?></a></span>
            <?php $i++; ?>
          <?php } ?>
        <?php } ?>
      </div>

      <div class="share">
        <?php
          osc_reset_resources();

          if(osc_is_ad_page()) {
            $share_url = osc_item_url();
          } else {
            $share_url = osc_base_url();
          }

          $share_url = urlencode($share_url);
        ?>

        <div class="header"><?php _e('Follow us', 'veronika'); ?></div>

        <?php if(osc_is_ad_page()) { ?>
          <span class="whatsapp"><a href="whatsapp://send?text=<?php echo $share_url; ?>" data-action="share/whatsapp/share"><i class="fa fa-whatsapp"></i></a></span>
        <?php } ?>

        <span class="facebook"><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $share_url; ?>" title="<?php echo osc_esc_html(__('Share us on Facebook', 'veronika')); ?>" target="_blank"><i class="fa fa-facebook"></i></a></span>
        <span class="pinterest"><a href="https://pinterest.com/pin/create/button/?url=<?php echo $share_url; ?>&media=<?php echo osc_base_url(); ?>oc-content/themes/<?php echo osc_current_web_theme(); ?>/images/logo.jpg&description=" title="<?php echo osc_esc_html(__('Share us on Pinterest', 'veronika')); ?>" target="_blank"><i class="fa fa-pinterest"></i></a></span>
        <span class="twitter"><a href="https://twitter.com/home?status=<?php echo $share_url; ?>%20-%20<?php _e('your', 'veronika'); ?>%20<?php _e('classifieds', 'veronika'); ?>" title="<?php echo osc_esc_html(__('Tweet us', 'veronika')); ?>" target="_blank"><i class="fa fa-twitter"></i></a></span>
        <span class="google-plus"><a href="https://plus.google.com/share?url=<?php echo $share_url; ?>" title="<?php echo osc_esc_html(__('Share us on Google+', 'veronika')); ?>" target="_blank"><i class="fa fa-google-plus"></i></a></span>
        <span class="linkedin"><a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $share_url; ?>&title=<?php echo osc_esc_html(__('My', 'veronika')); ?>%20<?php echo osc_esc_html(__('classifieds', 'veronika')); ?>&summary=&source=" title="<?php echo osc_esc_html(__('Share us on LinkedIn', 'veronika')); ?>" target="_blank"><i class="fa fa-linkedin"></i></a></span>
      </div>
    </div>
  </div>
</div>




<!-- MOBILE BLOCKS -->
<div id="menu-cover"></div>

<div id="menu-user" class="header-slide closed non-resp is767">
  <div class="body">
    <?php if( !osc_is_web_user_logged_in() ) { ?>
      <a href="<?php echo osc_user_login_url(); ?>"><i class="fa fa-sign-in"></i><?php _e('Log in', 'veronika'); ?></a>

      <?php
        if (osc_rewrite_enabled()) {
          $reg_url = '?move=register';
        } else {
          $reg_url = '&move=register';
        }
      ?>

      <a href="<?php echo osc_register_account_url() . $reg_url; ?>"><i class="fa fa-wpforms"></i><?php _e('Register new account', 'veronika'); ?></a>
    <?php } else { ?>
      <?php veronika_user_menu(); ?>
    <?php } ?>
  </div>
</div>

<div id="menu-options" class="header-slide closed non-resp is767">
  <div class="body">
    <div class="elem publish">
      <a class="publish round3" href="<?php echo osc_item_post_url(); ?>"><i class="fa fa-plus-circle"></i> <?php _e('Add new listing', 'veronika'); ?></a>
    </div>

    <a href="<?php echo osc_base_url(); ?>"><i class="fa fa-home"></i><?php _e('Home', 'veronika'); ?></a>
    <a href="<?php echo osc_contact_url(); ?>"><i class="fa fa-envelope-o"></i><?php _e('Mail us', 'veronika'); ?></a>

    <?php if(osc_get_preference('phone', 'veronika_theme') <> '') { ?>
      <a href="tel:<?php echo osc_esc_html( osc_get_preference('phone', 'veronika_theme') ); ?>"><i class="fa fa-phone"></i><?php _e('Call us', 'veronika'); ?></a>
    <?php } ?>

    <?php osc_reset_static_pages(); ?>
    <?php while(osc_has_static_pages()) { ?>
      <a class="gray" href="<?php echo osc_static_page_url(); ?>" title="<?php echo osc_esc_html(osc_static_page_title()); ?>"><?php echo ucfirst(osc_static_page_title());?></a>
    <?php } ?>



    <?php if ( osc_count_web_enabled_locales() > 1) { ?>
      <?php $current_locale = mb_get_current_user_locale(); ?>

      <?php osc_goto_first_locale(); ?>

      <div class="elem gray">
        <div class="lang">
          <?php while ( osc_has_web_enabled_locales() ) { ?>
            <a id="<?php echo osc_locale_code() ; ?>" href="<?php echo osc_change_language_url ( osc_locale_code() ) ; ?>" <?php if (osc_locale_code() == $current_locale['pk_c_code'] ) { ?>class="current"<?php } ?>>
              <span><?php echo osc_locale_field('s_short_name'); ?></span>
              <?php if (osc_locale_code() == $current_locale['pk_c_code']) { ?>
                <i class="fa fa-check"></i>
              <?php } ?>
            </a>
          <?php } ?>
        </div>
      </div>
    <?php } ?>

  </div>
</div>


<div id="menu-search" class="header-slide closed non-resp is767">
  <div class="inside-wrap">
    <form action="<?php echo osc_base_url(true); ?>" method="get" class="nocsrf search-mobile">
      <input type="hidden" name="page" value="search" />
      <input type="hidden" name="cookieActionMobile" id="cookieActionMobile" value="" />
      <input type="hidden" name="sOrder" value="<?php echo osc_search_order(); ?>" />
      <input type="hidden" name="iOrderType" value="<?php $allowedTypesForSorting = Search::getAllowedTypesForSorting() ; echo isset($allowedTypesForSorting[osc_search_order_type()]) ? $allowedTypesForSorting[osc_search_order_type()] : ''; ?>" />
      <input type="hidden" name="sCountry" id="sCountry" class="sCountry" value="<?php echo Params::getParam('sCountry'); ?>"/>
      <input type="hidden" name="sRegion" id="sRegion" class="sRegion" value="<?php echo Params::getParam('sRegion'); ?>"/>
      <input type="hidden" name="sCity" id="sCity" class="sCity" value="<?php echo Params::getParam('sCity'); ?>"/>

      <fieldset class="body">
        <div class="sort">
          <i class="fa fa-sort-alpha-asc"></i>
          <?php echo veronika_simple_sort(); ?>
        </div>

        <div class="elem">
          <label><?php _e('Keyword', 'veronika'); ?></label>

          <div class="input-box">
            <i class="fa fa-pencil"></i>
            <input type="text" name="sPattern" id="query" value="<?php echo osc_esc_html(osc_search_pattern()); ?>" />
          </div>
        </div>


        <div class="elem">
          <label><?php _e('Location', 'veronika'); ?></label>

          <div id="location-picker" class="location-picker">
            <input type="text" name="term" id="term" class="term" placeholder="<?php _e('Country, Region or City', 'veronika'); ?>" value="<?php echo veronika_get_term(Params::getParam('term'), Params::getParam('sCountry'), Params::getParam('sRegion'), Params::getParam('sCity')); ?>" autocomplete="off"/>
            <div class="shower-wrap">
              <div class="shower" id="shower">
                <div class="option service min-char"><?php _e('Type country, region or city', 'veronika'); ?></div>
              </div>
            </div>

            <div class="loader"></div>
          </div>
        </div>


        <div class="elem">
          <label><?php _e('Category', 'veronika'); ?></label>

          <div class="input-box">
            <?php
              $cat_id = Params::getParam('sCategory');
              $cat = array('pk_i_id' => $cat_id); 
            ?>

            <i class="fa fa-gear"></i>
            <?php echo veronika_simple_category(true); ?>
          </div>
        </div>



        <div class="more-info">
          <div class="head sc-click ellipsis-h">
            <span class="_1"></span><span class="_2"></span><span class="_3"></span>
          </div>

          <div class="more-body sc-block hooks">
            <div class="elem">
              <label><?php _e('Show items as gallery / list', 'veronika'); ?></label>

              <div class="input-box">
                <?php $def_view = osc_get_preference('def_view', 'veronika_theme') == 0 ? 'gallery' : 'list'; ?>
                <?php $old_show = Params::getParam('sShowAs') == '' ? $def_view : Params::getParam('sShowAs'); ?>

                <i class="fa <?php if($old_show == 'gallery') { ?>fa-th-large<?php } else { ?>fa-th-list<?php } ?>"></i>


                <select name="sShowAs" id="sShowAs">
                  <option value="gallery" <?php echo ($old_show == 'gallery' ? 'selected="selected"' : ''); ?>><?php _e('Gallery view', 'veronika'); ?></option>
                  <option value="list" <?php echo ($old_show == 'list' ? 'selected="selected"' : ''); ?>><?php _e('List view', 'veronika'); ?></option>
                </select>
              </div>
            </div>

            <div class="elem">
              <label><?php _e('Transaction', 'veronika'); ?></label>

              <div class="input-box">
                <i class="fa fa-exchange"></i>
                <?php echo veronika_simple_transaction(true); ?>
              </div>
            </div>

            <div class="elem">
              <label><?php _e('Condition', 'veronika'); ?></label>

              <div class="input-box">
                <i class="fa fa-tag"></i>
                <?php echo veronika_simple_condition(true); ?>
              </div>
            </div>

            <div class="elem">
              <label><?php _e('Period', 'veronika'); ?></label>

              <div class="input-box">
                <i class="fa fa-calendar-o"></i>
                <?php echo veronika_simple_period(true); ?>
              </div>
            </div>

            <div class="elem">
              <label><?php _e('Seller', 'veronika'); ?></label>

              <div class="input-box">
                <i class="fa fa-user"></i>
                <?php echo veronika_simple_seller(true); ?>
              </div>
            </div>


            <?php if( osc_price_enabled_at_items() ) { ?>
              <div class="elem price">
                <label><?php _e('Price', 'veronika'); ?></label>

                <div class="input-box min-price">
                  <input type="number" id="priceMin" name="sPriceMin" value="<?php echo Params::getParam('sPriceMin'); ?>" size="6" maxlength="6" />
                  <span class="currency"><?php echo osc_get_preference('def_cur', 'veronika_theme'); ?></span>
                </div>

                <span class="price-del"><i class="fa fa-arrows-h"></i></span>

                <div class="input-box max-price">
                  <input type="number" id="priceMax" name="sPriceMax" value="<?php echo Params::getParam('sPriceMax'); ?>" size="6" maxlength="6" />
                  <span class="currency"><?php echo osc_get_preference('def_cur', 'veronika_theme'); ?></span>
                </div>
              </div>
            <?php } ?>


            <?php if( osc_images_enabled_at_items() ) { ?>
              <div class="elem">
                <div class="input-box-check">
                  <input type="checkbox" name="bPic" id="withPicture" value="1" <?php echo (osc_search_has_pic() ? 'checked="checked"' : ''); ?> />
                  <label for="withPicture" class="with-pic-label"><?php _e('Only listings with picture', 'veronika') ; ?></label>
                </div>
              </div>
            <?php } ?>


            <div class="sidebar-hooks">
              <?php GLOBAL $search_hooks; ?>
              <?php echo $search_hooks; ?>
            </div>
          </div>
        </div>


        <div class="buttons">
          <button type="submit" id="blue"><?php _e('Search', 'veronika') ; ?></button>
        </div>

      </fieldset>
    </form>

    <div class="buttons-bottom">
      <?php if(osc_is_search_page()) { osc_alert_form(); } ?>
      <a href="<?php echo osc_search_url(array('page' => 'search'));?>" class="clear-search clear-cookie"><i class="fa fa-eraser"></i> <?php _e('Clean fields', 'veronika'); ?></a>
    </div>
  </div>
</div>


<script>
  $(document).ready(function(){

    // JAVASCRIPT AJAX LOADER FOR LOCATIONS 
    var termClicked = false;
    var currentCountry = "<?php echo veronika_ajax_country(); ?>";
    var currentRegion = "<?php echo veronika_ajax_region(); ?>";
    var currentCity = "<?php echo veronika_ajax_city(); ?>";


    // On first click initiate loading
    $('body').on('click', '#location-picker .term', function() {
      if( !termClicked ) {
        $(this).keyup();
      }

      termClicked = true;
    });


    // Create delay
    var delay = (function(){
      var timer = 0;
      return function(callback, ms){
        clearTimeout (timer);
        timer = setTimeout(callback, ms);
      };
    })();


    //$(document).ajaxStart(function() { 
      //$("#location-picker, .location-picker").addClass('searching');
    //});

    $(document).ajaxSend(function(evt, request, settings) {
      var url = settings.url;

      if (url.indexOf("ajaxLoc") >= 0) {
        $("#location-picker, .location-picker").addClass('searching');
      }
    });

    $(document).ajaxStop(function() {
      $("#location-picker, .location-picker").removeClass('searching');
    });



    $('body').on('keyup', '#location-picker .term', function(e) {
      delay(function(){
        var min_length = 3;
        var elem = $(e.target);
        var term = encodeURIComponent(elem.val());

        // If comma entered, remove characters after comma including
        if(term.indexOf(',') > 1) {
          term = term.substr(0, term.indexOf(','));
        }

        // If comma entered, remove characters after - including (because city is shown in format City - Region)
        if(term.indexOf(' - ') > 1) {
          term = term.substr(0, term.indexOf(' - '));
        }

        var block = elem.closest("#location-picker");
        var shower = elem.closest("#location-picker").find(".shower");

        shower.html('');

        if(term != '' && term.length >= min_length) {
          // Combined ajax for country, region & city
          $.ajax({
            type: "POST",
            url: baseAjaxUrl + "&ajaxLoc=1&term=" + term,
            dataType: 'json',
            success: function(data) {
              var length = data.length;
              var result = '';
              var result_first = '';
              var countCountry = 0;
              var countRegion = 0;
              var countCity = 0;


              if(shower.find('.service.min-char').length <= 0) {
                for(key in data) {

                  // Prepare location IDs
                  var id = '';
                  var country_code = '';
                  if( data[key].country_code ) {
                    country_code = data[key].country_code;
                    id = country_code;
                  }

                  var region_id = '';
                  if( data[key].region_id ) {
                    region_id = data[key].region_id;
                    id = region_id;
                  }

                  var city_id = '';
                  if( data[key].city_id ) {
                    city_id = data[key].city_id;
                    id = city_id;
                  }
                    

                  // Count cities, regions & countries
                  if (data[key].type == 'city') {
                    countCity = countCity + 1;
                  } else if (data[key].type == 'region') {
                    countRegion = countRegion + 1;
                  } else if (data[key].type == 'country') {
                    countCountry = countCountry + 1;
                  }


                  // Find currently selected element
                  var selectedClass = '';
                  if( 
                    data[key].type == 'country' && parseInt(currentCountry) == parseInt(data[key].country_code) 
                    || data[key].type == 'region' && parseInt(currentRegion) == parseInt(data[key].region_id) 
                    || data[key].type == 'city' && parseInt(currentCity) == parseInt(data[key].city_id) 
                  ) { 
                    selectedClass = ' selected'; 
                  }


                  // For cities, get region name
                  var nameTop = '';
                  if(data[key].name_top ) {
                    nameTop = ' <span>' + data[key].name_top + '</span>';
                  }


                  if(data[key].type != 'city_more') {

                    // When classic city, region or country in loop and same does not already exists
                    if(shower.find('div[data-code="' + data[key].type + data[key].id + '"]').length <= 0) {
                      result += '<div class="option ' + data[key].type + selectedClass + '" data-country="' + country_code + '" data-region="' + region_id + '" data-city="' + city_id + '" data-code="' + data[key].type + id + '" id="' + id + '">' + data[key].name + nameTop + '</div>';
                    }

                  } else {

                    // When city counter and there is more than 12 cities for search
                    if(shower.find('.more-city').length <= 0) {
                      if( parseInt(data[key].name) > 0 ) {
                        result += '<div class="option service more-pick more-city city">... ' + (data[key].name) + ' <?php echo osc_esc_js(__('more cities, specify your location', 'veronika')); ?></div>';
                      }
                    }
                  }
                }


                // No city, region or country found
                if( countCountry == 0 && shower.find('.empty-country').length <= 0 && shower.find('.service.min-char').length <= 0) {
                  shower.find('.option.country').remove();
                  result_first += '<div class="option service empty-pick empty-country country"><?php echo osc_esc_js(__('No country match to your criteria', 'veronika')); ?></div>';
                }

                if( countRegion == 0 && shower.find('.empty-region').length <= 0 && shower.find('.service.min-char').length <= 0) {
                  shower.find('.option.region').remove();
                  result_first += '<div class="option service empty-pick empty-region region"><?php echo osc_esc_js(__('No region match to your criteria', 'veronika')); ?></div>';
                }

                if( countCity == 0 && shower.find('.empty-city').length <= 0 && shower.find('.service.min-char').length <= 0) {
                  shower.find('.option.city').remove();
                  result_first += '<div class="option service empty-pick empty-city city"><?php echo osc_esc_js(__('No city match to your criteria', 'veronika')); ?></div>';
                }

              }

              shower.html(result_first + result);
            }
          });

        } else {
          // Term is not length enough
          shower.html('<div class="option service min-char"><?php echo osc_esc_js(__('Enter at least', 'veronika')); ?> ' + (min_length - term.length) + ' <?php echo osc_esc_js(__('more letter(s)', 'veronika')); ?></div>');
        }
      }, 500 );
    });




    <?php if(osc_get_preference('item_ajax', 'veronika_theme') == 1) { ?>
      // JAVASCRIPT AJAX LOADER FOR ITEMS AUTOCOMPLETE
      var patternClicked = false;

      // On first click initiate loading
      $('body').on('click', '#item-picker .pattern', function() {
        if( !patternClicked ) {
          $(this).keyup();
        }

        patternClicked = true;
      });


      // Create delay
      var delay2 = (function(){
        var timer2 = 0;
        return function(callback, ms){
          clearTimeout (timer2);
          timer2 = setTimeout(callback, ms);
        };
      })();


      //$(document).ajaxStart(function() { 
        //$("#item-picker, .item-picker").addClass('searching');
      //});

      $(document).ajaxSend(function(evt, request, settings) {
        var url = settings.url;

        if (url.indexOf("ajaxItem") >= 0) {
          $("#item-picker, .item-picker").addClass('searching');
        }
      });

      $(document).ajaxStop(function() {
        $("#item-picker, .item-picker").removeClass('searching');
      });


      $('body').on('keyup', '#item-picker .pattern', function(e) {
        delay(function(){
          var min_length = 3;
          var elem = $(e.target);
          var pattern = elem.val();

          var block = elem.closest("#item-picker");
          var shower = elem.closest("#item-picker").find(".shower");

          shower.html('');

          if(pattern != '' && pattern.length >= min_length) {
            // Combined ajax for country, region & city
            $.ajax({
              type: "POST",
              url: baseAjaxUrl + "&ajaxItem=1&pattern=" + pattern,
              dataType: 'json',
              success: function(data) { 
                var length = data.length;
                var result = '';

                if(shower.find('.service.min-char').length <= 0) {
                  for(key in data) {
                  
                    // When item already is not in shower
                    if(shower.find('div[data-item-id="' + data[key].pk_i_id + '"]').length <= 0) {
                      result += '<a class="option" data-item-id="' + data[key].pk_i_id + '" href="' + data[key].item_url + '" title="<?php echo osc_esc_js(__('Click to open listing', 'veronika')); ?>">'
                      result += '<div class="left"><img src="' + data[key].image_url + '"/></div>';
                      result += '<div class="right">';
                      result += '<div class="top">' + data[key].s_title + '</div>';
                      result += '<div class="bottom">' + data[key].i_price + '</div>';
                      result += '</div>';
                      result += '</a>';
                    }
                  }


                  // No city, region or country found
                  if( length <= 0) {
                    shower.find('.option').remove();
                    result = '<div class="option service empty-pick"><?php echo osc_esc_js(__('No listing match to your criteria', 'veronika')); ?></div>';
                  }
                }

                shower.html(result);
              }
            });

          } else {
            // Term is not length enough
            shower.html('<div class="option service min-char"><?php echo osc_esc_js(__('Enter at least', 'veronika')); ?> ' + (min_length - pattern.length) + ' <?php echo osc_esc_js(__('more letter(s)', 'veronika')); ?></div>');
          }
        }, 500 );
      });
    <?php } ?>


    // PLACEHOLDERS
    $('#yourName, #authorName, #comment_form #authorName, #s_name').attr('placeholder', '<?php echo osc_esc_js(__('Your name or nick...', 'veronika')); ?>');
    $('#yourEmail, #authorEmail, #comment_form #authorEmail, #s_email').attr('placeholder', '<?php echo osc_esc_js(__('Mail to contact you...', 'veronika')); ?>');
    $('#login #email').attr('placeholder', '<?php echo osc_esc_js(__('Registration email...', 'veronika')); ?>');
    $('#login #password').attr('placeholder', '<?php echo osc_esc_js(__('Password...', 'veronika')); ?>');
    $('#register #s_password').attr('placeholder', '<?php echo osc_esc_js(__('At least 6 characters...', 'veronika')); ?>');
    $('#register #s_password2').attr('placeholder', '<?php echo osc_esc_js(__('Repeat password...', 'veronika')); ?>');
    $('#phoneNumber').attr('placeholder', '<?php echo osc_esc_js(__('Phone to call you...', 'veronika')); ?>');
    $('#s_phone_mobile').attr('placeholder', '<?php echo osc_esc_js(__('Mobile phone to call you...', 'veronika')); ?>');
    $('#s_phone_land').attr('placeholder', '<?php echo osc_esc_js(__('Land phone to call you...', 'veronika')); ?>');
    $('#message, #body, #body').attr('placeholder', '<?php echo osc_esc_js(__('I want to ask...', 'veronika')); ?>');
    $('#sendfriend #message').attr('placeholder', '<?php echo osc_esc_js(__('I want to share this item...', 'veronika')); ?>');
    $('#comment_form #body').attr('placeholder', '<?php echo osc_esc_js(__('I want to ask/share...', 'veronika')); ?>');
    $('#title, #comment_form #title').attr('placeholder', '<?php echo osc_esc_js(__('Short title...', 'veronika')); ?>');
    $('#subject').attr('placeholder', '<?php echo osc_esc_js(__('Message subject...', 'veronika')); ?>');
    $('#friendName').attr('placeholder', '<?php echo osc_esc_js(__('Friend\'s name or nick...', 'veronika')); ?>');
    $('#friendEmail').attr('placeholder', '<?php echo osc_esc_js(__('Friend\'s email...', 'veronika')); ?>');
    $('input[name="sPattern"]').attr('placeholder', '<?php echo osc_esc_js(__('Samsung S7 Edge...', 'veronika')); ?>');
    $('#priceMin').attr('placeholder', '<?php echo osc_esc_js(__('min', 'veronika')); ?>');
    $('#priceMax').attr('placeholder', '<?php echo osc_esc_js(__('max', 'veronika')); ?>');
    $('.add_item input[name^="title"]').attr('placeholder', '<?php echo osc_esc_js(__('Short title for listing...', 'veronika')); ?>');
    $('.add_item textarea[name^="description"]').attr('placeholder', '<?php echo osc_esc_js(__('Detail description of listing...', 'veronika')); ?>');
    $('.add_item #contactName').attr('placeholder', '<?php echo osc_esc_js(__('Your name or nick...', 'veronika')); ?>');
    $('.add_item #cityArea').attr('placeholder', '<?php echo osc_esc_js(__('City area of listing...', 'veronika')); ?>');
    $('.add_item #sPhone').attr('placeholder', '<?php echo osc_esc_js(__('Mobile phone to contact you...', 'veronika')); ?>');
    $('.add_item #zip').attr('placeholder', '<?php echo osc_esc_js(__('Zip code...', 'veronika')); ?>');
    $('.add_item #contactEmail').attr('placeholder', '<?php echo osc_esc_js(__('Mail to contact you...', 'veronika')); ?>');
    $('.add_item #address').attr('placeholder', '<?php echo osc_esc_js(__('Street and location of item...', 'veronika')); ?>');
    $('textarea[id^="s_info"]').attr('placeholder', '<?php echo osc_esc_js(__('Information about you or your company...', 'veronika')); ?>');
    $('.modify_profile #cityArea').attr('placeholder', '<?php echo osc_esc_js(__('Your city area...', 'veronika')); ?>');
    $('.modify_profile #address').attr('placeholder', '<?php echo osc_esc_js(__('Your address...', 'veronika')); ?>');
    $('.modify_profile #zip').attr('placeholder', '<?php echo osc_esc_js(__('Your Zip code...', 'veronika')); ?>');
    $('.modify_profile #s_website').attr('placeholder', '<?php echo osc_esc_js(__('Your website...', 'veronika')); ?>');
    $('.modify_profile #new_email').attr('placeholder', '<?php echo osc_esc_js(__('Your new contact email...', 'veronika')); ?>');
    $('.modify_profile #password').attr('placeholder', '<?php echo osc_esc_js(__('Your current password...', 'veronika')); ?>');
    $('.modify_profile #new_password').attr('placeholder', '<?php echo osc_esc_js(__('Your new password...', 'veronika')); ?>');
    $('.modify_profile #new_password2').attr('placeholder', '<?php echo osc_esc_js(__('Repeat new password...', 'veronika')); ?>');

  });
</script>


<?php if (1==2) { 
  $cat = osc_search_category_id();
  $cat = $cat[0];

  echo 'Page: ' . Params::getParam('page') . '<br />';
  echo 'Param Country: ' . Params::getParam('sCountry') . '<br />';
  echo 'Param Region: ' . Params::getParam('sRegion') . '<br />';
  echo 'Param City: ' . Params::getParam('sCity') . '<br />';
  echo 'Param Locator: ' . Params::getParam('sLocator') . '<br />';
  echo 'Param Category: ' . Params::getParam('sCategory') . '<br />';
  echo 'Search Region: ' . osc_search_region() . '<br />';
  echo 'Search City: ' . osc_search_city() . '<br />';
  echo 'Search Category: ' . $cat . '<br />';
  echo 'Param Locator: ' . Params::getParam('sLocator') . '<br />';
  echo '<br/> ------------------------------------------------- </br>';
  echo 'Cookie Category: ' . mb_get_cookie('veronika-sCategory') . '<br />';
  echo 'Cookie Country: ' . mb_get_cookie('veronika-sCountry') . '<br />';
  echo 'Cookie Region: ' . mb_get_cookie('veronika-sRegion') . '<br />';
  echo 'Cookie City: ' . mb_get_cookie('veronika-sCity') . '<br />';
  echo '<br/> ------------------------------------------------- </br>';

  echo '<br/>';
  echo '<br/>';
  echo 'end<br/>';

}
?>	