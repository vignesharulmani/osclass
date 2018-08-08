<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="<?php echo str_replace('_', '-', osc_current_user_locale()) ; ?>">
<head>
  <?php osc_current_web_theme_path('head.php') ; ?>
</head>

<body id="body-home">
  <?php osc_current_web_theme_path('header.php') ; ?>
  <?php echo veronika_banner('home_top'); ?>


  <?php if(function_exists('osc_slider')) { ?>

    <!-- Slider Block -->
    <div class="home-container hc-slider">
      <div class="inner">
        <div id="home-slider">
          <?php osc_slider(); ?>
        </div>
      </div>
    </div>
  <?php } ?>



  <?php osc_get_premiums(osc_get_preference('premium_home_count', 'veronika_theme')); ?>
  <?php if( osc_count_premiums() > 0 && osc_get_preference('premium_home', 'veronika_theme') == 1) { ?>

    <!-- Extra Premiums Block -->
    <div class="home-container hc-premiums">
      <div class="inner">

        <div id="latest" class="white prem">
          <h2 class="home">
            <?php _e('Premium listings', 'veronika'); ?>
          </h2>

          <div class="block">
            <div class="wrap">
              <?php $c = 1; ?>
              <?php while( osc_has_premiums() ) { ?>
                <?php veronika_draw_item($c, 'gallery', true); ?>
                
                <?php $c++; ?>
              <?php } ?>
            </div>
          </div>


          <?php //View::newInstance()->_erase('items') ; ?>
        </div>
      </div>
    </div>
  <?php } ?>



  <!-- Latest Listings Block -->
  <div class="home-container hc-latest">
    <div class="inner">

      <div id="latest" class="white">
        <h2 class="home">
          <?php _e('Latest listings', 'veronika'); ?>
        </h2>

        <?php View::newInstance()->_exportVariableToView('latestItems', veronika_random_items()); ?>

        <?php if( osc_count_latest_items() > 0) { ?>
          <div class="block">
            <div class="wrap">
              <?php $c = 1; ?>
              <?php while( osc_has_latest_items() ) { ?>
                <?php veronika_draw_item($c, 'gallery'); ?>
                
                <?php $c++; ?>
              <?php } ?>
            </div>
          </div>
        
          <div class="home-see-all non-resp">
            <a href="<?php echo osc_search_url(array('page' => 'search'));?>"><?php _e('See all offers', 'veronika'); ?></a>
            <i class="fa fa-angle-down"></i>
          </div>

          <span class="show-more-latest"><i class="fa fa-ellipsis-h"></i></span>

        <?php } else { ?>
          <div class="empty"><?php _e('No latest listings', 'veronika'); ?></div>
        <?php } ?>

        <?php View::newInstance()->_erase('items') ; ?>
      </div>
    </div>
  </div>


  <?php echo veronika_banner('home_bottom'); ?>


  <?php osc_current_web_theme_path('footer.php') ; ?>
</body>
</html>	