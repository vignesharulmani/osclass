<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="<?php echo str_replace('_', '-', osc_current_user_locale()); ?>">
<head>
  <?php osc_current_web_theme_path('head.php') ; ?>
  <meta name="robots" content="noindex, nofollow" />
  <meta name="googlebot" content="noindex, nofollow" />
</head>
<body id="body-user-dashboard" class="body-ua">
  <?php osc_current_web_theme_path('header.php'); ?>
  <div class="content user_account">
    <div id="sidebar" class="sc-block">
      <?php echo veronika_user_menu(); ?>
    </div>

    <div id="main" class="dashboard">
      <div id="chart"></div>

      <?php
        if (osc_rewrite_enabled()) {
          $s_active = '?itemType=active';
          $s_pending = '?itemType=pending_validate';
          $s_expired = '?itemType=expired';
        } else {
          $s_active = '&itemType=active';
          $s_pending = '&itemType=pending_validate';
          $s_expired = '&itemType=expired';
        }
      ?>

      <div class="inside">
        <a class="card active round3 tr1" href="<?php echo osc_user_list_items_url() . $s_active; ?>">
          <div class="icon">
            <i class="fa fa-check-square-o tr1"></i>
            <span class="count"><?php echo Item::newInstance()->countItemTypesByUserID(osc_logged_user_id(), 'active'); ?></span>
          </div>

          <div class="header"><?php _e('Active items', 'veronika'); ?></div>
          <div class="description"><?php _e('Listings that are visible in front and customer can view and share them.', 'veronika'); ?></div>
        </a>


        <a class="card not-validated round3 tr1" href="<?php echo osc_user_list_items_url() . $s_pending; ?>">
          <div class="icon">
            <i class="fa fa-stack-overflow tr1"></i>
            <span class="count"><?php echo Item::newInstance()->countItemTypesByUserID(osc_logged_user_id(), 'pending_validate'); ?></span>
          </div>

          <div class="header"><?php _e('Validation pending', 'veronika'); ?></div>
          <div class="description"><?php _e('Listings that are hidden and waiting for yours or administrator\'s validation.', 'veronika'); ?></div>
        </a>


        <a class="card expired round3 tr1" href="<?php echo osc_user_list_items_url() . $s_expired; ?>">
          <div class="icon">
            <i class="fa fa-times tr1"></i>
            <span class="count"><?php echo Item::newInstance()->countItemTypesByUserID(osc_logged_user_id(), 'expired'); ?></span>
          </div>

          <div class="header"><?php _e('Expired', 'veronika'); ?></div>
          <div class="description"><?php _e('Listings that are expired and are not visible in front. You can renew or recreate them.', 'veronika'); ?></div>
        </a>


        <a class="card alerts round3 tr1" href="<?php echo osc_user_alerts_url(); ?>">
          <div class="icon">
            <i class="fa fa-bullhorn tr1"></i>
            <span class="count"><?php echo count(Alerts::newInstance()->findByUser(osc_logged_user_id())); ?></span>
          </div>

          <div class="header"><?php _e('Alerts', 'veronika'); ?></div>
          <div class="description"><?php _e('Notifications you have subscribed to based on specific search criteria.', 'veronika'); ?></div>
        </a>


        <a class="card profile round3 tr1" href="<?php echo osc_user_profile_url(); ?>">
          <?php 
            $u = User::newInstance()->findByPrimaryKey(osc_logged_user_id());
            $c = 0;
            if($u['s_phone_land'] == '' && $u['s_phone_mobile'] == '') { $c++; }
            if($u['s_website'] == '') { $c++; }
            if($u['s_country'] == '' && $u['s_region'] == '' && $u['s_city'] == '') { $c++; }
            if($u['s_address'] == '') { $c++; }
          ?>

          <div class="icon">
            <i class="fa fa-file-text-o tr1"></i>
            <span class="count">
              <?php if($c == 0) { ?><i class="fa fa-check"></i><?php } else { ?><i class="fa fa-warning"></i><?php } ?>
            </span>
          </div>

          <div class="header"><?php _e('Profile', 'veronika'); ?></div>
          <div class="description">
            <?php if($c == 0) { ?>
              <?php _e('Your personal information, picture or avatar, location, business type and others', 'veronika'); ?>
            <?php } else { ?>
              <?php echo osc_esc_html( sprintf(__('Your profile is not complete, you did not filled %s or more information about you.', 'veronika'), $c) ); ?>
            <?php } ?>
          </div>
        </a>


        <?php if(function_exists('im_messages')) { ?>
          <a class="card messages round3 tr1" href="<?php echo osc_route_url( 'im-threads'); ?>">
            <?php     
              $count = ModelIM::newInstance()->countMessagesByUserId( osc_logged_user_id() );
              $count = $count['i_count'];
            ?>

            <div class="icon">
              <i class="fa fa-envelope-o tr1"></i>
              <span class="count"><?php echo $count; ?></span>
            </div>

            <div class="header"><?php _e('Messages', 'veronika'); ?></div>
            <div class="description"><?php _e('Instant messages you have recieved & sent to other users.', 'veronika'); ?></div>
          </a>
        </div>
      <?php } ?>
    </div>
  </div>


  <?php
    // CHART FUNCTIONALITY

    $db_prefix = DB_TABLE_PREFIX;
    $current_year = date("Y");
    $current_month = date("n");
    $limit_year = date('Y', strtotime(' -12 month', time()));
    $limit_month = date('n', strtotime(' -12 month', time()));

    $query = "SELECT (year(s.dt_date)*100 + month(s.dt_date)) as yearmonth, sum(s.i_num_views) as views, sum(s.i_num_premium_views) as premium_views, sum(coalesce(e.i_num_phone_clicks, 0)) as phone_clicks FROM {$db_prefix}t_item_stats s INNER JOIN {$db_prefix}t_item i ON s.fk_i_item_id = i.pk_i_id LEFT OUTER JOIN {$db_prefix}t_item_stats_veronika e ON (s.fk_i_item_id = e.fk_i_item_id AND s.dt_date = e.dt_date) WHERE i.fk_i_user_id = " . osc_user_id() . " AND (year(s.dt_date)*100 + month(s.dt_date)) >= " . ($limit_year*100 + $limit_month) . " GROUP BY (year(s.dt_date)*100 + month(s.dt_date)) ORDER BY (year(s.dt_date)*100 + month(s.dt_date)) asc;";
    $result = ItemStats::newInstance()->dao->query( $query );

    if( !$result ) { 
      $prepare = array(); 
    } else {
      $prepare = $result->result();
    }

    $date_string = '';
    $date_array = array();
    for ($date = date('Y-m-d', strtotime(' -12 month', time())); $date <= date("Y-m-d"); $date = date('Y-m-d', strtotime($date . ' + 1 month'))) {
      $date_array[] = date('Ym', strtotime($date));
      $date_string .= date('Ym', strtotime($date)) . ', '; 
    }

    $date_string = substr($date_string, 0, -2);


    $regular = '';
    $premium = '';
    $clicks = '';

    foreach( $date_array as $d ) {
      $found = false;
      foreach( $prepare as $p ) {
        if( $p['yearmonth'] == $d ) {
          $regular .= $p['views'] . ', '; 
          $premium .= $p['premium_views'] . ', '; 
          $clicks .= $p['phone_clicks'] . ', '; 
          $found = true;
        }
      }

      if(!$found) {
        $regular .= '0, '; 
        $premium .= '0, '; 
        $clicks .= '0, '; 
      }
    }

    $regular = substr($regular, 0, -2);
    $premium = substr($premium, 0, -2);
    $clicks = substr($clicks, 0, -2);
  ?>

  <script src="https://code.highcharts.com/highcharts.js"></script>

  <script>
    $(document).ready(function(){
      Highcharts.chart('chart', {
        title: {
          text: '<?php echo osc_esc_js(__('Your listing views in last 12 months', 'veronika')); ?>',
          x: -20 //center
        },

        responsive: {
          rules: [{
            condition: { maxWidth: 480 },
            chartOptions: {
              legend: { enabled: false },
              title: { text: '<?php echo osc_esc_js(__('Views in last 12 months', 'veronika')); ?>', style: {"font-size": "14px"}},
              xAxis: { labels: { enabled: false } },
              yAxis: { title: { text: null } }
            }
          }, {
            condition: { minWidth: 481, maxWidth: 1024 },
            chartOptions: {
              title: { style: {"font-size": "15px"}},
              yAxis: { title: { text: null } }
            }
          }]
        },

        xAxis: {
          categories: [<?php echo $date_string; ?>]
        },

        yAxis: [{
          title: {
            text: '<?php echo osc_esc_js(__('# views', 'veronika')); ?>'
          },
          plotLines: [{
            value: 0,
            width: 1,
            color: '#808080'
          }],
        }, {
          opposite: true,
          title: {
            text: '<?php echo osc_esc_js(__('# clicks', 'veronika')); ?>'
          },
          plotLines: [{
            value: 0,
            width: 1,
            color: '#808080'
          }]
        }],

        legend: {
          <?php if(veronika_is_rtl()) { ?>
            rtl: true,
          <?php } ?>
          layout: 'vertical',
          align: 'right',
          verticalAlign: 'middle',
          borderWidth: 0,
          itemStyle: {"fontWeight": "normal" }
        },

        colors: ['#03a9f4', '#F44336', '#8BC34A'],

        series: [{
          yAxis: 0,
          name: '<?php echo osc_esc_js(__('Normal', 'veronika')); ?>',
          data: [<?php echo $regular; ?>],
          tooltip: {
            valueSuffix: 'x <?php echo osc_esc_js(__('viewed', 'veronika')); ?>'
          }
        }, {
          yAxis: 0,
          name: '<?php echo osc_esc_js(__('Premium', 'veronika')); ?>',
          data: [<?php echo $premium; ?>],
          tooltip: {
            valueSuffix: 'x <?php echo osc_esc_js(__('viewed', 'veronika')); ?>'
          }
        }, {
          yAxis: 1,
          name: '<?php echo osc_esc_js(__('Phone Clicks', 'veronika')); ?>',
          data: [<?php echo $clicks; ?>],
          tooltip: {
            valueSuffix: 'x <?php echo osc_esc_js(__('clicked', 'veronika')); ?>'
          }
        }]
      });
    });
  </script>


  <?php osc_current_web_theme_path('footer.php') ; ?>
</body>
</html>