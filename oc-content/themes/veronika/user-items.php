<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="<?php echo str_replace('_', '-', osc_current_user_locale()); ?>">
<head>
  <?php osc_current_web_theme_path('head.php') ; ?>
  <meta name="robots" content="noindex, nofollow" />
  <meta name="googlebot" content="noindex, nofollow" />
</head>
<body id="body-user-items" class="body-ua">
  <?php osc_current_web_theme_path('header.php') ; ?>
  <div class="content user_account">
    <div id="sidebar" class="sc-block">
      <?php echo veronika_user_menu(); ?>
    </div>


    <?php
      $item_type = Params::getParam('itemType');

      if($item_type == 'active') {
        $title = __('Active listings', 'veronika');
        $status = __('active', 'veronika');
      } else if ($item_type == 'pending_validate') {
        $title = __('Not validated listings', 'veronika');
        $status = __('not validated', 'veronika');
      } else if ($item_type == 'expired') {
        $title = __('Expired listings', 'veronika');
        $status = __('expired', 'veronika');
      } else {
        $title = __('Your listings', 'veronika');
        $status = '';
      }


      // IN CASE ITEMS ARE NOT PROPERLY SHOWN, USE THIS FUNCTION BLOCK 
      // $active_items = Item::newInstance()->findItemTypesByUserID(osc_logged_user_id(), 0,null, $item_type); 
      // View::newInstance()->_exportVariableToView('items', $active_items); 
    ?>




    <div id="main" class="items">
      <div class="inside">
        <?php if(osc_count_items() > 0) { ?>
          <?php while(osc_has_items()) { ?>
            <div class="card item round3 tr1">
              <?php
                if($item_type == '') {
                  if(osc_item_is_expired()) {
                    $type = __('Expired', 'veronika');
                    $type_raw = 'expired';
                  } else if (osc_item_is_inactive()) {
                    $type = __('Not validated', 'veronika');
                    $type_raw = 'pending_validate';
                  } else if (osc_item_is_active()) {
                    $type = __('Active', 'veronika');
                    $type_raw = 'active';
                  } else {
                    $type = '';
                    $type_raw = '';
                  }
                } else {
                  $type = '';
                }
              ?>

              <?php if($item_type == '') { ?>
                <div class="type <?php echo $type_raw; ?>"><span class="round2"><?php echo $type; ?></span></div>
              <?php } ?>

              <?php if(osc_images_enabled_at_items()) { ?>
                <div class="image round2">
                  <span class="image-count round2"><i class="fa fa-camera"></i> <?php echo osc_count_item_resources(); ?>x</span>

                  <?php if(osc_count_item_resources() > 0) { ?>
                    <img src="<?php echo osc_resource_thumbnail_url(); ?>" title="<?php echo osc_esc_html(osc_item_title()); ?>" alt="<?php echo osc_esc_html(osc_item_title()); ?>" />
                  <?php } else { ?>
                    <img src="<?php echo osc_current_web_theme_url('images/no-image.png'); ?>" title="<?php echo osc_esc_html(osc_item_title()); ?>" alt="<?php echo osc_esc_html(osc_item_title()); ?>" />
                  <?php } ?>
                </div>
              <?php } ?>

              <?php if(osc_item_is_premium()) { ?>
                <div class="ua-premium" title="<?php _e('This listing is premium', 'veronika'); ?>"><i class="fa fa-star"></i></div>
              <?php } ?>

              <div class="title"><?php echo osc_highlight(osc_item_title(), 80); ?></div>

              <?php if( osc_price_enabled_at_items() ) { ?>
                <div class="price"><?php echo osc_item_formated_price(); ?></div>
              <?php } ?>
         
              <div class="stats">
                <?php
                  $db_prefix = DB_TABLE_PREFIX;
                  $query = "SELECT sum(s.i_num_views) as views, sum(s.i_num_premium_views) as premium_views, sum(coalesce(e.i_num_phone_clicks, 0)) as phone_clicks FROM {$db_prefix}t_item_stats s LEFT OUTER JOIN {$db_prefix}t_item_stats_veronika e ON (s.fk_i_item_id = e.fk_i_item_id AND s.dt_date = e.dt_date) WHERE s.fk_i_item_id = " . osc_item_id() . ";";
                  $result = ItemStats::newInstance()->dao->query( $query );

                  if( !$result ) { 
                    $stats = array(); 
                  } else {
                    $stats = $result->row();
                  }
                ?>

                <div class="tr1" title="<?php _e('Normal views', 'veronika'); ?>"><?php echo $stats['views'] <> '' ? $stats['views'] : 0; ?>x <?php _e('views', 'veronika'); ?></div>
                <div class="tr1" title="<?php _e('Premium views - listing shown as premium', 'veronika'); ?>"><?php echo $stats['premium_views'] <> '' ? $stats['premium_views'] : 0; ?>x <?php _e('p. views', 'veronika'); ?></div>
                <div class="tr1" title="<?php _e('Phone clicks - people that shown contact phone', 'veronika'); ?>"><?php echo $stats['phone_clicks'] <> '' ? $stats['phone_clicks'] : 0; ?>x <?php _e('phone', 'veronika'); ?></div>
              </div>


              <div class="category round2"><i class="fa fa-cog"></i> <?php echo osc_item_category(); ?></div>

              <div class="location round2"><i class="fa fa-map-marker"></i> <?php echo veronika_location_format(osc_item_country(), osc_item_region(), osc_item_city()); ?></div>

              <div class="item-description"><?php echo osc_highlight(osc_item_description(), 120); ?></div>

              <div class="dates">
                <span><span class="label"><?php _e('Expire', 'veronika'); ?>:</span> <?php echo (date('Y', strtotime(osc_item_field('dt_expiration'))) > 3000 ? __('Never', 'veronika') : date('Y/m/d', strtotime(osc_item_field('dt_expiration')))); ?></span>
                <span><span class="label"><?php _e('Published', 'veronika'); ?>:</span> <?php echo date('Y/m/d', strtotime(osc_item_pub_date())); ?></span>
                <span>
                  <?php if(osc_item_mod_date() <> '') { ?>
                    <span class="label"><?php _e('Modified', 'veronika'); ?>:</span> <?php echo date('Y/m/d', strtotime(osc_item_mod_date())); ?>
                  <?php } ?>
                </span>
              </div>

              <div class="buttons">
                <a class="edit round2 tr1" target="_blank" href="<?php echo osc_item_edit_url(); ?>" rel="nofollow"><i class="fa fa-pencil"></i> <?php _e('Edit', 'veronika'); ?></a>
                <a class="view round2 tr1" target="_blank" href="<?php echo osc_item_url(); ?>"><i class="fa fa-eye"></i> <?php _e('View', 'veronika'); ?></a>

                <?php if(osc_item_is_inactive()) {?>
                  <a class="activate round2 tr1" target="_blank" href="<?php echo osc_item_activate_url(); ?>"><i class="fa fa-check-square-o"></i> <?php _e('Validate', 'veronika'); ?></a>
                <?php } else { ?>
                  <?php $item_extra = veronika_item_extra( osc_item_id() ); ?>
                  <?php 
                    if (osc_rewrite_enabled()) { 
                      if( $item_extra['i_sold'] == 0 ) {
                        $sold_url = '?itemId=' . osc_item_id() . '&markSold=1&secret=' . osc_item_field('s_secret') . '&itemType=' . $item_type;
                        $reserved_url = '?itemId=' . osc_item_id() . '&markSold=2&secret=' . osc_item_field('s_secret') . '&itemType=' . $item_type;
                      } else {
                        $sold_url = '?itemId=' . osc_item_id() . '&markSold=0&secret=' . osc_item_field('s_secret') . '&itemType=' . $item_type;
                        $reserved_url = '?itemId=' . osc_item_id() . '&markSold=0&secret=' . osc_item_field('s_secret') . '&itemType=' . $item_type;
                      }
                    } else {
                      if( $item_extra['i_sold'] == 0 ) {
                        $sold_url = '&itemId=' . osc_item_id() . '&markSold=1&secret=' . osc_item_field('s_secret') . '&itemType=' . $item_type;
                        $reserved_url = '&itemId=' . osc_item_id() . '&markSold=2&secret=' . osc_item_field('s_secret') . '&itemType=' . $item_type;
                      } else {
                        $sold_url = '&itemId=' . osc_item_id() . '&markSold=0&secret=' . osc_item_field('s_secret') . '&itemType=' . $item_type;
                        $reserved_url = '&itemId=' . osc_item_id() . '&markSold=0&secret=' . osc_item_field('s_secret') . '&itemType=' . $item_type;
                      }
                    }
                  ?>

                  <?php if(!in_array(osc_item_category_id(), veronika_extra_fields_hide())) { ?>
                    <a class="sold round2 tr1" href="<?php echo osc_user_list_items_url() . $sold_url; ?>"><i class="fa fa-handshake-o"></i> <?php echo ($item_extra['i_sold'] == 1 ? __('Not sold', 'veronika') : __('Sold', 'veronika')); ?></a>
                    <a class="reserved round2 tr1" href="<?php echo osc_user_list_items_url() . $reserved_url; ?>"><i class="fa fa-flag-o"></i> <?php echo ($item_extra['i_sold'] == 2 ? __('Unreserve', 'veronika') : __('Reserve', 'veronika')); ?></a>
                  <?php } ?>                  

                <?php } ?>

                <?php if(function_exists('republish_link_raw') && republish_link_raw(osc_item_id())) { ?>
                  <a class="republish round2 tr1" href="<?php echo republish_link_raw(osc_item_id()); ?>" rel="nofollow"><i class="fa fa-refresh"></i> <?php _e('Republish', 'veronika'); ?></a>
                <?php } ?>

                <a class="delete round2 tr1" onclick="return confirm('<?php echo osc_esc_js(__('Are you sure you want to delete this listing? This action cannot be undone.', 'veronika')); ?>')" href="<?php echo osc_item_delete_url(); ?>" rel="nofollow"><i class="fa fa-trash-o"></i> <?php _e('Delete', 'veronika'); ?></a>
              </div>

            </div>
          <?php } ?>
        <?php } else { ?>
          <div class="ua-items-empty"><img src="<?php echo osc_current_web_theme_url('images/search-empty.png'); ?>"/> <span><?php echo sprintf(__('You have no %s listings.', 'veronika'), $status); ?></span></div>
        <?php } ?>


        <div class="paginate">
          <?php for($i = 0 ; $i < osc_list_total_pages() ; $i++) { ?>
            <a class="<?php if($i == osc_list_page()) { ?>searchPaginationSelected<?php } else { ?>searchPaginationNonSelected<?php } ?>" href="<?php echo osc_user_list_items_url($i + 1) . '&itemType=' . $item_type; ?>"><?php echo ($i + 1); ?></a>
          <?php } ?>
        </div>
      </div>

    </div>
  </div>

  <?php osc_current_web_theme_path('footer.php') ; ?>
</body>
</html>