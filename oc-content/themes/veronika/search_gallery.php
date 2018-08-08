<div id="gallery-view" class="white search-items-wrap">
  <div class="block">
    <div class="wrap">

      <?php 
        // PREMIUM ITEMS
        osc_get_premiums(osc_get_preference('premium_search_gallery_count', 'veronika_theme'));
        $c = 1;

        if(osc_count_premiums() > 0 && osc_get_preference('premium_search_gallery', 'veronika_theme') == 1) {
          while(osc_has_premiums()) {
            veronika_draw_item($c, 'gallery', true, 'premium-loop');
            $c++;
          }
        }
      ?>


      <?php $c = 1; ?>
      <?php while( osc_has_items() ) { ?>
        <?php veronika_draw_item($c, 'gallery'); ?>
        <?php $c++; ?>
      <?php } ?>

    </div>
  </div>
 
  <?php View::newInstance()->_erase('items') ; ?>
</div>