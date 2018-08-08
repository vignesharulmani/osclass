<?php $search_params = veronika_search_params(); ?>
<?php $search_params['sPriceMin'] = ''; ?>
<?php $search_params['sPriceMax'] = ''; ?>

<?php
  // CURRENT CATEGORY
  $search_cat_id = osc_search_category_id();
  $search_cat_id = isset($search_cat_id[0]) ? $search_cat_id[0] : 0;
  $search_cat_full = Category::newInstance()->findByPrimaryKey($search_cat_id);

  // ROOT CATEGORY
  $root_cat_id = Category::newInstance()->findRootCategory($search_cat_id);
  $root_cat_id = $root_cat_id['pk_i_id'];
   
  // HIERARCHY OF SEARCH CATEGORY
  $hierarchy = Category::newInstance()->toRootTree($search_cat_id);

  // SUBCATEGORIES OF SEARCH CATEGORY
  $subcats = Category::newInstance()->findSubcategoriesEnabled($search_cat_id);

  if(empty($subcats)) {
    $is_subcat = false;
    $subcats = Category::newInstance()->findSubcategoriesEnabled($search_cat_full['fk_i_parent_id']);
  } else {
    $is_subcat = true;
  }
?>


<div id="category-navigation">
  <?php if(osc_is_search_page()) { ?>
    <div class="top-cat-head sc-click non-resp is767">
      <i class="fa fa-ellipsis-h"></i> 
      <span>
        <?php if(osc_is_search_page() && $search_cat_id <> 0 && $search_cat_id <> '') { ?>
          <?php _e('Subcategories', 'veronika'); ?>
        <?php } else { ?>
          <?php _e('Categories', 'veronika'); ?>
        <?php } ?>
      </span>
    </div>
  <?php } ?>

  <div class="top-cat-wrap sc-block<?php if(osc_get_preference('search_box_home', 'veronika_theme') <> '1') { ?> border-top<?php } ?>">

    <?php if((osc_is_search_page() && ($search_cat_id <= 0 || $search_cat_id == '')) || !osc_is_search_page()) { ?>
      <div id="top-cat">
        <div class="cat-inside">
          <div class="top-cat-ul-wrap">
            <div class="left-arrow arrows tr1 noselect"><i class="fa fa-angle-left tr1"></i></div>

            <div class="ul-box">
              <ul <?php if(osc_is_search_page()) { ?>class="ul-search"<?php } ?> style="width:<?php echo osc_count_categories()*130; ?>px">
                <?php while ( osc_has_categories() ) { ?>
                  <?php $search_params['sCategory'] = osc_category_id(); ?>
                  <?php 
                    if($root_cat_id <> '' and $root_cat_id <> 0) {
                      if($root_cat_id <> osc_category_id()) { 
                        $cat_class = 'cat-gray';
                      } else {
                        $cat_class = 'cat-highlight';
                      }
                    } else {
                      $cat_class = '';
                    }

                    $color = veronika_get_cat_color(osc_category_id());
                  ?>

                  <li <?php if($cat_class <> '') { echo 'class="' . $cat_class . '"'; } ?>>

                    <?php ob_start(); // SAVE HTML OF ACTIVE CATEGORY ?>

                    <a 
                      id="cat-link"
                      rel="<?php echo osc_category_id(); ?>" 
                      <?php if(osc_is_home_page()) { ?>href="#ct<?php echo osc_category_id(); ?>"<?php } else { ?>href="<?php echo osc_search_url($search_params); ?>"<?php } ?>
                      <?php if(osc_is_home_page()) { ?>class="open-home-cat"<?php } ?>
                      <?php if(osc_is_home_page()) { ?>title="<?php _e('Show subcategories of', 'veronika'); ?> <?php echo osc_category_name(); ?>"<?php } ?>
                    >
                      <div class="img<?php if($color == '') { ?> no-color<?php } ?>">
                        <span <?php if($color <> '') { ?>style="background:<?php echo $color; ?>;"<?php } ?>></span>

                        <?php if(osc_get_preference('cat_icons', 'veronika_theme') == 1) { ?>
                          <i class="fa <?php echo veronika_get_cat_icon( osc_category_id(), true ); ?>" <?php if($color <> '') { ?>style="color:<?php echo $color; ?>;"<?php } ?>></i>
                        <?php } else { ?>
                          <img src="<?php echo osc_current_web_theme_url();?>images/small_cat/<?php echo osc_category_id();?>.png" />
                        <?php } ?>
                      </div>

                      <div class="name"><?php echo osc_category_name(); ?></div>
                    </a>

                    <?php $contents = ob_get_contents(); // GET HTML OF ACTIVE CATEGORY ?>
                  </li>

                  <?php if($cat_class == 'cat-highlight') { ?>
                    <?php $h_contents = $contents; ?>
                  <?php } ?>
                <?php } ?>

                <?php if(isset($h_contents) && $h_contents <> '') { ?>
                  <li class="cat-highlight resp is767">
                    <?php echo $h_contents; ?>
                  </li>
                <?php } ?>

              </ul>
            </div>

            <div class="right-arrow arrows tr1 noselect"><i class="fa fa-angle-right tr1"></i></div>
          </div>
        </div>
      </div>
    <?php } ?>

    <div 
      id="top-subcat"
      <?php if(!osc_is_home_page() && (!osc_is_search_page() || $search_cat_id == 0 || $search_cat_id == '')) { ?>style="display:none;"<?php } ?>
      <?php if(osc_is_search_page() && $search_cat_id <> 0 && $search_cat_id <> '') { ?>class="has-sub"<?php } ?>
    >
      <div class="subcat-inside">

        <?php if(osc_is_home_page()){ ?>
          <!-- HOME PAGE SUBCATEGORIES LIST -->

          <div>
            <?php osc_goto_first_category(); ?>
            <?php $search_params = veronika_search_params(); ?>
            <?php $search_params['sPriceMin'] = ''; ?>
            <?php $search_params['sPriceMax'] = ''; ?>

            <div id="home-cat" class="home-cat">
              <?php osc_goto_first_category(); ?>
              <?php while( osc_has_categories() ) { ?>
                <?php $search_params['sCategory'] = osc_category_id(); ?>

                <div id="ct<?php echo osc_category_id(); ?>" class="cat-tab">
                  <?php $cat_id = osc_category_id(); ?>

                  <div class="head">
                    <a href="<?php echo osc_search_url($search_params); ?>"><h2><?php echo osc_category_name(); ?></h2></a>

                    <span>
                      <?php if(osc_category_total_items() == '' or osc_category_total_items() == 0) { ?>
                         <?php _e('there are no listings yet', 'veronika'); ?>
                      <?php } else { ?>
                        <?php _e('browse in', 'veronika'); ?> <?php echo osc_category_total_items(); ?> <?php _e('listings', 'veronika'); ?>
                      <?php } ?>
                    </span>

                    <div class="add"><a href="<?php echo osc_item_post_url_in_category(); ?>"><?php _e('Add listing', 'veronika'); ?></a></div>
                  </div>

                  <div class="middle">
                    <?php while(osc_has_subcategories()) { ?>
                      <?php $search_params['sCategory'] = osc_category_id(); ?>
               
                      <a href="<?php echo osc_search_url($search_params); ?>">
                        <span>
                          <span class="icon"><?php echo veronika_get_cat_icon( osc_category_id()); ?></span>
                          <span class="name"><?php echo osc_category_name(); ?></span>
                        </span>
                      </a>
                    <?php } ?>
                  </div>
                </div>
              <?php } ?>
            </div>
          </div>
        <?php } ?>


        <?php if(osc_is_search_page() && $search_cat_id <> 0 && $search_cat_id <> '') { ?>
          <! -- SEARCH PAGE SUBCATEGORIES LIST -->

          <div>
            <?php osc_goto_first_category(); ?>
            <?php $search_params = veronika_search_params(); ?>
            <?php $search_params['sPriceMin'] = ''; ?>
            <?php $search_params['sPriceMax'] = ''; ?>

            <div class="cat-navigation">
              <?php
                unset($search_params['sCategory']);
                echo '<a id="cat-link" href="' . osc_search_url($search_params) . '">' . __('All categories', 'veronika') . '</a>';

                foreach($hierarchy as $h) {
                  $search_params['sCategory'] = $h['pk_i_id'];
                  echo '<a id="cat-link" href="' . osc_search_url($search_params) . '">' . $h['s_name'] . '</a>';
                }
              ?>
            </div>


            <div id="search-cat" class="search-cat">
              <div class="cat-tab">
                <?php $cat_id = osc_category_id(); ?>

                <?php if(!empty($subcats)) { ?>
                  <?php foreach($subcats as $s) { ?>
                    <?php $search_params['sCategory'] = $s['pk_i_id']; ?>

                    <div class="link-wrap">
                      <a href="<?php echo osc_search_url($search_params); ?>" id="cat-link" <?php echo ($s['pk_i_id'] == $search_cat_id ? 'class="bold"' : ''); ?>">
                        <span>
                          <span class="icon"><?php echo veronika_get_cat_icon($s['pk_i_id']); ?></span>
                          <span class="name"><?php echo $s['s_name']; ?> <strong><?php echo $s['i_num_items']; ?></strong></span>
                        </span>
                      </a>
                    </div>
                  <?php } ?>
                <?php } ?>
              </div>
            </div>
          </div>
        <?php } ?>

      </div>
    </div>
  </div>
</div>