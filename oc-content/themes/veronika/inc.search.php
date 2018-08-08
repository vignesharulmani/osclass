<div class="header-search-mobile is767">
  <form action="<?php echo osc_base_url(true); ?>" method="get" class="search nocsrf" >
    <input type="hidden" name="page" value="search" />

    <div class="input-box">
      <button type="submit"><i class="fa fa-search"></i></button>
      <input type="text" name="sPattern" id="query" value="<?php echo osc_esc_html(osc_search_pattern()); ?>" placeholder="<?php _e('Search...', 'veronika'); ?>" autocomplete="off" />
    </div>

    <i class="fa fa-ellipsis-v open-h-search"></i>
  </form>
</div>


<?php if(osc_get_preference('search_box_home', 'veronika_theme') == '1') { ?>
  <div id="header-search">
    <div class="inside">
      <div class="wrap round3">
        <form action="<?php echo osc_base_url(true); ?>" method="get" class="search nocsrf" >
          <input type="hidden" name="page" value="search" />
          <input type="hidden" name="cookieAction" id="cookieAction" value="" />
          <input type="hidden" name="sCountry" id="sCountry" value="<?php echo Params::getParam('sCountry'); ?>"/>
          <input type="hidden" name="sRegion" id="sRegion" value="<?php echo Params::getParam('sRegion'); ?>"/>
          <input type="hidden" name="sCity" id="sCity" value="<?php echo Params::getParam('sCity'); ?>"/>

          <div class="top">
            <div class="large">
              <div class="b1">
                <div class="label"><?php _e('What are you looking for?', 'veronika'); ?></div>
                <div class="box">
                  <?php if (osc_get_preference('item_ajax', 'veronika_theme') == 1) { ?>
                    <div id="item-picker">
                      <input type="text" name="sPattern" class="pattern" placeholder="<?php _e('Samsung S7 Edge...', 'veronika'); ?>" value="<?php echo Params::getParam('sPattern'); ?>" autocomplete="off"/>

                      <div class="shower-wrap">
                        <div class="shower" id="shower">
                          <div class="option service min-char"><?php _e('Type keyword', 'veronika'); ?></div>
                        </div>
                      </div>

                      <div class="loader"></div>
                    </div>
                  <?php } else { ?>
                    <input type="text" name="sPattern" placeholder="<?php _e('Samsung S7 Edge...', 'veronika'); ?>" value="<?php echo Params::getParam('sPattern'); ?>" autocomplete="off"/>
                  <?php } ?>
                </div>
              </div>

              <div class="b2">
                <div class="label"><?php _e('Location', 'veronika'); ?></div>
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

              <div class="b3">
                <div class="label">&nbsp;</div>
                <div class="box"><button type="submit" class="round3 tr1"><?php _e('Search', 'veronika'); ?></button></div>
              </div>
            </div>

            <div class="small">
              <div class="b1 price">
                <div class="label"><?php _e('Price', 'veronika'); ?></div>
                <div class="box">
                  <div class="input-wrap">
                    <input class="price-min" type="text" name="sPriceMin" placeholder="<?php echo osc_esc_html(__('min', 'veronika')); ?>" value="<?php echo Params::getParam('sPriceMin'); ?>"/>
                    <span class="currency"><?php echo osc_get_preference('def_cur', 'veronika_theme'); ?></span>
                  </div>

                  <span class="price-del"><i class="fa fa-arrows-h"></i></span> 

                  <div class="input-wrap">
                    <input class="price-max" type="text" name="sPriceMax" placeholder="<?php echo osc_esc_html(__('max', 'veronika')); ?>" value="<?php echo Params::getParam('sPriceMax'); ?>"/>
                    <span class="currency"><?php echo osc_get_preference('def_cur', 'veronika_theme'); ?></span>
                  </div>
                </div>
              </div>

              <div class="b2">
                <div class="label"><?php _e('Category', 'veronika'); ?></div>
                <div class="box">
                  <?php echo veronika_simple_category(); ?>
                </div>
              </div>

              <div class="b3">
                <div class="label"><?php _e('Transaction', 'veronika'); ?></div>
                <div class="box">
                  <?php echo veronika_simple_transaction(); ?>
                </div>
              </div>
            </div>
          </div>

          <div class="bot">
            <div class="left"><a href="#" class="clear-cookie clean"><?php _e('Clean search parameters', 'veronika'); ?></a></div>
            <div class="right"><a href="<?php echo osc_search_url(array('page' => 'search'));?>" class="publish"><?php _e('See all listings', 'veronika'); ?></a></div>
          </div>
        </div>
      </form>
    </div>
  </div>
<?php } ?>