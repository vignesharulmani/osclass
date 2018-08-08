<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="<?php echo str_replace('_', '-', osc_current_user_locale()); ?>">
<head>
  <?php osc_current_web_theme_path('head.php') ; ?>
  <?php if( osc_count_items() == 0 || Params::getParam('iPage') > 0 || stripos($_SERVER['REQUEST_URI'], 'search') )  { ?>
    <meta name="robots" content="noindex, nofollow" />
    <meta name="googlebot" content="noindex, nofollow" />
  <?php } else { ?>
    <meta name="robots" content="index, follow" />
    <meta name="googlebot" content="index, follow" />
  <?php } ?>
</head>

<body id="body-search">
<?php osc_current_web_theme_path('header.php') ; ?>

<div class="content-top"><span></span></div>

<div class="content list">
  <div id="main" class="search">

    <!-- TOP SEARCH TITLE -->
    <?php
      $search_cat_id = osc_search_category_id();
      $search_cat_id = isset($search_cat_id[0]) ? $search_cat_id[0] : '';
    ?>


    <!-- SEARCH FILTERS - SORT / COMPANY / VIEW -->
    <div id="search-sort" class="not767">
      <div class="user-company-change">
        <div class="all <?php if(Params::getParam('sCompany') == '' or Params::getParam('sCompany') == null) { ?>active<?php } ?>"><span><?php _e('All results', 'veronika'); ?></span></div>
        <div class="individual <?php if(Params::getParam('sCompany') == '0') { ?>active<?php } ?>"><span><?php _e('Personal', 'veronika'); ?></span></div>
        <div class="company <?php if(Params::getParam('sCompany') == '1') { ?>active<?php } ?>"><span><?php _e('Company', 'veronika'); ?></span></div>
      </div>

      <div class="list-grid">
        <?php $def_view = osc_get_preference('def_view', 'veronika_theme') == 0 ? 'gallery' : 'list'; ?>
        <?php $old_show = Params::getParam('sShowAs') == '' ? $def_view : Params::getParam('sShowAs'); ?>
        <?php $params['sShowAs'] = 'list'; ?>
        <a href="<?php echo osc_update_search_url($params); ?>" title="<?php echo osc_esc_html(__('Switch to list view', 'veronika')); ?>" <?php echo ($old_show == $params['sShowAs'] ? 'class="active"' : ''); ?>><i class="fa fa-th-list"></i></a>
        <?php $params['sShowAs'] = 'gallery'; ?>
        <a href="<?php echo osc_update_search_url($params); ?>" title="<?php echo osc_esc_html(__('Switch to grid view', 'veronika')); ?>" <?php echo ($old_show == $params['sShowAs'] ? 'class="active"' : ''); ?>><i class="fa fa-th"></i></a>
      </div>

      <div class="counter">
        <?php if(osc_search_total_items() == 0) { ?>
          <?php _e('No listings', 'veronika'); ?>
        <?php } else { ?>
          <?php echo osc_default_results_per_page_at_search()*(osc_search_page())+1;?> - <?php echo osc_default_results_per_page_at_search()*(osc_search_page()+1)+osc_count_items()-osc_default_results_per_page_at_search();?> <?php echo ' ' . __('of', 'veronika') . ' '; ?> <?php echo osc_search_total_items(); ?> <?php echo (osc_search_total_items() == 1 ? __('listing', 'veronika') : __('listings', 'veronika')); ?>                                                           
        <?php } ?>
      </div>

      <div class="sort-it">
        <div class="sort-title">
          <div class="title-keep noselect">
            <?php $orders = osc_list_orders(); ?>
            <?php $current_order = osc_search_order(); ?>
            <?php foreach($orders as $label => $params) { ?>
              <?php $orderType = ($params['iOrderType'] == 'asc') ? '0' : '1'; ?>
              <?php if(osc_search_order() == $params['sOrder'] && osc_search_order_type() == $orderType) { ?>
                <?php if($current_order == 'dt_pub_date') { ?>
                  <i class="fa fa-sort-numeric-asc"></i>
                <?php } else { ?>
                  <?php if($orderType == 0) { ?>
                    <i class="fa fa-sort-amount-asc"></i>
                  <?php } else { ?>
                    <i class="fa fa-sort-amount-desc"></i>
                  <?php } ?>
                <?php } ?>

                <span>
                  <span class="non-resp not1200"><?php echo $label; ?></span>
                  <span class="resp is1200"><?php _e('Sort', 'veronika'); ?></span>
                </span>
              <?php } ?>
            <?php } ?>
          </div>

          <div id="sort-wrap">
            <div class="sort-content">
              <div class="info"><?php _e('Select sorting', 'veronika'); ?></div>

              <?php $i = 0; ?>
              <?php foreach($orders as $label => $params) { ?>
                <?php $orderType = ($params['iOrderType'] == 'asc') ? '0' : '1'; ?>
                <?php if(osc_search_order() == $params['sOrder'] && osc_search_order_type() == $orderType) { ?>
                  <a class="current" href="<?php echo osc_update_search_url($params) ; ?>"><span><?php echo $label; ?></span></a>
                <?php } else { ?>
                  <a href="<?php echo osc_update_search_url($params) ; ?>"><span><?php echo $label; ?></span></a>
                <?php } ?>
                <?php $i++; ?>
              <?php } ?>
            </div>
          </div>
        </div>
      </div>
    </div>


    <!-- HELPERS FORS AJAX SEARCH -->
    <div id="ajax-help" style="display:none;">
      <input type="hidden" name="ajax-last-page-id" value="<?php echo ceil( osc_search_total_items() / osc_default_results_per_page_at_search() ); ?>" />

      <?php
        $search_cat_id = osc_search_category_id();
        $search_cat_id = isset($search_cat_id[0]) ? $search_cat_id[0] : '';

        $max = veronika_max_price($search_cat_id, Params::getParam('sCountry'), Params::getParam('sRegion'), Params::getParam('sCity'));
        $max_price = ceil($max['max_price']/50)*50;
        $max_currency = $max['max_currency'];
        $format_sep = osc_get_preference('format_sep', 'veronika_theme');
        $format_cur = osc_get_preference('format_cur', 'veronika_theme');

        if($format_cur == 0) {
          $format_prefix = $max_currency;
          $format_suffix = '';
        } else if ($format_cur == 1) {
          $format_prefix = '';
          $format_suffix = $max_currency;
        } else {
          $format_prefix = '';
          $format_suffix = '';
        }
      ?>

      <input type="hidden" name="ajax-price-format-prefix" value="<?php echo $format_prefix; ?>" />
      <input type="hidden" name="ajax-price-format-suffix" value="<?php echo $format_suffix; ?>" />
      <input type="hidden" name="ajax-price-format-sep" value="<?php echo $format_sep; ?>" />
      <input type="hidden" name="ajax-price-max-price" value="<?php echo $max_price; ?>" />
      <input type="hidden" name="ajax-price-min-price-search" value="<?php echo Params::getParam('sPriceMin'); ?>" />
      <input type="hidden" name="ajax-price-max-price-search" value="<?php echo Params::getParam('sPriceMax'); ?>" />

    </div>


    <div id="search-items" data-loading="<?php _e('Loading listings...', 'veronika'); ?>">                    
      <?php if(osc_count_items() == 0) { ?>
        <div class="list-empty round3" >
          <img src="<?php echo osc_current_web_theme_url('images/search-empty.png'); ?>"/>
          <div>
            <span><?php _e('Whooops, no listing match search criteria...', 'veronika'); ?></span>
          </div>
        </div>
      <?php } else { ?>
        <?php echo veronika_banner('search_top'); ?>

        <?php require($old_show == 'list' ? 'search_list.php' : 'search_gallery.php') ; ?>
      <?php } ?>

      <div class="paginate">
        <?php echo osc_search_pagination(); ?>
      </div>

      <?php echo veronika_banner('search_bottom'); ?>
    </div>
  </div>



  <div id="sidebar" class="noselect">
    <div id="sidebar-search" class="round3">
      <form action="<?php echo osc_base_url(true); ?>" method="get" onsubmit="" class="search-side-form nocsrf">
        <input type="hidden" name="page" value="search" />
        <input type="hidden" name="ajaxRun" value="" />
        <input type="hidden" name="cookieAction" id="cookieAction" value="" />
        <input type="hidden" name="sCategory" value="<?php echo Params::getParam('sCategory'); ?>" />
        <input type="hidden" name="sOrder" value="<?php echo osc_search_order(); ?>" />
        <input type="hidden" name="iOrderType" value="<?php $allowedTypesForSorting = Search::getAllowedTypesForSorting() ; echo isset($allowedTypesForSorting[osc_search_order_type()]) ? $allowedTypesForSorting[osc_search_order_type()] : ''; ?>" />
        <input type="hidden" name="sCompany" class="sCompany" id="sCompany" value="<?php echo Params::getParam('sCompany');?>" />
        <input type="hidden" id="priceMin" name="sPriceMin" value="<?php echo Params::getParam('sPriceMin'); ?>" size="6" maxlength="6" />
        <input type="hidden" id="priceMax" name="sPriceMax" value="<?php echo Params::getParam('sPriceMax'); ?>" size="6" maxlength="6" />
        <input type="hidden" name="sCountry" id="sCountry" value="<?php echo Params::getParam('sCountry'); ?>"/>
        <input type="hidden" name="sRegion" id="sRegion" value="<?php echo Params::getParam('sRegion'); ?>"/>
        <input type="hidden" name="sCity" id="sCity" value="<?php echo Params::getParam('sCity'); ?>"/>
        <input type="hidden" name="iPage" id="iPage" value=""/>
        <input type="hidden" name="sShowAs" id="sShowAs" value="<?php echo Params::getParam('sShowAs'); ?>"/>

        <?php foreach(osc_search_user() as $userId) { ?>
          <input type="hidden" name="sUser[]" value="<?php echo $userId; ?>" />
        <?php } ?>


        <div class="wrap i-shadow round3">
          <h3 class="head">
            <?php _e('Search', 'veronika'); ?>

            <div id="show-hide" class="closed"></div>
          </h3>

          <div class="search-wrap">
            <fieldset class="box location">
              <div class="row">
                <h4><?php _e('Keyword', 'veronika') ; ?></h4>                            
                <div class="input-box">
                  <i class="fa fa-pencil"></i>
                  <input type="text" name="sPattern" id="query" value="<?php echo osc_esc_html(osc_search_pattern()); ?>" placeholder="<?php echo osc_esc_html(__('Samsung S7 Edge...', 'veronika')); ?>" />
                </div>
              </div>

              <div class="row">
                <h4><?php _e('Location', 'veronika') ; ?></h4>                            

                <div class="box">
                  <div id="location-picker">
                    <input type="text" name="term" id="term" class="term" placeholder="<?php _e('Country, Region or City', 'veronika'); ?>" value="<?php echo veronika_get_term(Params::getParam('term'), Params::getParam('sCountry'), Params::getParam('sRegion'), Params::getParam('sCity')); ?>" autocomplete="off"/>
                    <div class="shower-wrap">
                      <div class="shower" id="shower">
                        <div class="option service min-char"><?php _e('Type country, region or city', 'veronika'); ?></div>
                      </div>
                    </div>

                    <div class="loader"></div>
                  </div>

                </div>
              </div>

              <div class="row">
                <h4><?php _e('Transaction', 'veronika') ; ?></h4>                            
                <div class="input-box">
                  <?php echo veronika_simple_transaction(); ?>
                </div>
              </div>

              <div class="row">
                <h4><?php _e('Condition', 'veronika') ; ?></h4>                            
                <div class="input-box">
                  <?php echo veronika_simple_condition(); ?>
                </div>
              </div>

              <div class="row">
                <h4><?php _e('Period', 'veronika') ; ?></h4>                            
                <div class="input-box">
                  <?php echo veronika_simple_period(); ?>
                </div>
              </div>

            </fieldset>


            <fieldset class="img-check">
              <?php if( osc_images_enabled_at_items() ) { ?>
                <div class="row checkboxes">
                  <div class="input-box-check">
                    <input type="checkbox" name="bPic" id="withPicture" value="1" <?php echo (osc_search_has_pic() ? 'checked="checked"' : ''); ?> />
                    <label for="withPicture" class="with-pic-label"><?php _e('Only items with picture', 'veronika'); ?></label>
                  </div>
                </div>
              <?php } ?>
            </fieldset>

            <?php if( veronika_check_category_price($search_cat_id) ) { ?>
              <fieldset class="price-box">
                <div class="row price">
                  <h4><?php _e('Price', 'veronika'); ?>:</h4>
                  <div id="amount-min"></div><div id="amount-del">-</div><div id="amount-max"></div>
                </div>

                <div class="slider-span">
                  <div id="slider-range"></div>
                </div>
              </fieldset>
            <?php } ?>

            <div class="sidebar-hooks">
              <?php 
                GLOBAL $search_hooks;

                ob_start(); // SAVE HTML

                if(osc_search_category_id()) { 
                  osc_run_hook('search_form', osc_search_category_id());
                } else { 
                  osc_run_hook('search_form');
                }

                //echo $search_hooks;
                $search_hooks = ob_get_contents();   // CAPTURE HTML OF SIDEBAR HOOKS FOR FOOTER (MOBILE VIEW)
              ?>
            </div>
          </div>
        </div>

        <div class="button-wrap">
          <button type="submit" class="btn btn-primary" id="search-button"><i class="fa fa-search"></i><?php _e('Search', 'veronika') ; ?></button>
        </div>
      </form>

      <?php osc_alert_form(); ?>

      <a href="<?php echo osc_search_url(array('page' => 'search'));?>" class="clear-search clear-cookie"><i class="fa fa-eraser"></i> <?php _e('Clean fields', 'veronika'); ?></a>
    </div>

    <div class="clear"></div>


    <?php echo veronika_banner('search_sidebar'); ?>

  </div>
</div>

<?php osc_current_web_theme_path('footer.php') ; ?>
</body>
</html>