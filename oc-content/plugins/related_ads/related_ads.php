<?php if( osc_count_items() > 0) { ?>
  <!-- Related listings -->
  <div id="related" class="white">
    <h2><?php _e('Related listings', 'related_ads'); ?></h2>

    <div class="block">
      <div class="wrap">
        <?php $c = 1; ?>
        <?php while( osc_has_items() ) { ?>
          <?php veronika_draw_item($c, 'gallery'); ?>
          
          <?php $c++; ?>
        <?php } ?>
      </div>
    </div>
  </div>
<?php } ?>  
