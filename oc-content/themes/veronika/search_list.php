<div id="list-view" class="search-items-wrap">

  <?php 
    // PREMIUM ITEMS
    osc_get_premiums(osc_get_preference('premium_search_list_count', 'veronika_theme'));
    $c = 1;

    if(osc_count_premiums() > 0 && osc_get_preference('premium_search_list', 'veronika_theme') == 1) {
      echo '<div class="premium-list-block round3" ' . (osc_count_premiums() <= 2 ? 'style="height:auto!important;"' : '') . '>';
        echo '<h2>' .__('Premium listings', 'veronika') . '</h2>';
        echo '<div class="deli"><span></span></div>';

        while(osc_has_premiums()) {
          veronika_draw_item($c, 'list', true, 'premium-loop');
          $c++;
        }

      echo '</div>';

      if(osc_count_premiums() > 2) {
        echo '<div id="premium-more"><div class="push tr1"><i class="fa fa-arrow-circle-down"></i>' . __('Show more', 'veronika') . '</div></div>';
      }
    }
  ?>



  <?php $c = 1; ?>
  <?php while(osc_has_items()) { ?>

    <?php veronika_draw_item($c, 'list'); ?>

    <?php if($c == 3) { ?>
      <?php echo veronika_banner('search_list'); ?>
    <?php } ?>

    <?php $c++; ?>
  <?php } ?>
  <p align="right"><?php watchlist();?><p>
</div>


