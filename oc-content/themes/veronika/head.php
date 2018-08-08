<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<title><?php echo meta_title(); ?></title>
<meta name="title" content="<?php echo osc_esc_html(meta_title()); ?>" />

<?php if( meta_description() != '' ) { ?>
  <meta name="description" content="<?php echo osc_esc_html(meta_description()); ?>" />
<?php } ?>

<?php if( osc_get_canonical() != '' ) { ?>
  <link rel="canonical" href="<?php echo osc_get_canonical(); ?>"/>
<?php } ?>


<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache" />
<meta http-equiv="Expires" content="Mon, 01 Jul 1970 00:00:00 GMT" />
<?php if( !osc_is_search_page() )  { ?>
<meta name="robots" content="index, follow" />
<meta name="googlebot" content="index, follow" />
<?php } ?>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

<?php osc_current_web_theme_path('head-favicon.php') ; ?>

<?php $current_locale = osc_get_current_user_locale(); ?>
<?php $dimNormal = explode('x', osc_get_preference('dimNormal', 'osclass')); ?>
<?php 
  if(osc_is_ad_page()) {
    osc_get_item_resources();
    $image_count = max(osc_count_item_resources(), 1); 
  } else {
    $image_count = 1; 
  }

  $ios = false;
  if(strstr($_SERVER['HTTP_USER_AGENT'],'iPhone') || strstr($_SERVER['HTTP_USER_AGENT'],'iPod')) {
    $ios = true;
  }
?>

<script type="text/javascript">
  var veronikaCurrentLocale = '<?php echo osc_esc_js($current_locale['s_name']); ?>';
  var fileDefaultText = '<?php echo osc_esc_js(__('No file selected', 'veronika')); ?>';
  var fileBtnText     = '<?php echo osc_esc_js(__('Choose File', 'veronika')); ?>';
  var veronikaHeaderImg = '<?php echo osc_base_url() . 'oc-content/themes/' . osc_current_web_theme() . '/images/header-icons.png'; ?>';
  var baseDir = "<?php echo osc_base_url(); ?>";
  var baseSearchUrl = '<?php echo osc_search_url(array('page' => 'search')); ?>';
  var baseAjaxUrl = '<?php echo zara_ajax_url(); ?>';
  var baseAdminDir = '<?php echo osc_admin_base_url(true); ?>';
  var currentLocation = '<?php echo osc_get_osclass_location(); ?>';
  var currentSection = '<?php echo osc_get_osclass_section(); ?>';
  var adminLogged = '<?php echo osc_is_admin_user_logged_in() ? 1 : 0; ?>';
  var veronikaItemStick = '<?php echo (osc_get_preference('stick_item', 'veronika_theme') == 1 ? '1' : '0'); ?>';
  var veronikaSearchStick = '<?php echo (osc_get_preference('stick_search', 'veronika_theme') == 1 ? '1' : '0'); ?>';
  var veronikaLazy = '<?php echo ($ios ? '0' : '1'); ?>';
  var veronikaBxSlider = '<?php echo (osc_is_ad_page() ? '1' : '0'); ?>';
  var veronikaBxSliderSlides = '<?php echo min(osc_get_preference('item_images', 'veronika_theme'), $image_count); ?>';
  var veronikaMasonry = '<?php echo osc_get_preference('force_aspect_image', 'osclass') == 1 ? 1 : 0; ?>';
  var dimNormalWidth = <?php echo $dimNormal[0]; ?>;
  var dimNormalHeight = <?php echo $dimNormal[1]; ?>;
  var searchRewrite = '/<?php echo osc_get_preference('rewrite_search_url', 'osclass'); ?>';
  var ajaxSearch = '<?php echo (osc_get_preference('search_ajax', 'veronika_theme') == 1 ? '1' : '0'); ?>';
  var ajaxForms = '<?php echo (osc_get_preference('forms_ajax', 'veronika_theme') == 1 ? '1' : '0'); ?>';
  var veronikaClickOpen = '<?php echo osc_esc_js(__('Click to open listing', 'veronika')); ?>';
  var veronikaNoMatch = '<?php echo osc_esc_js(__('No listing match to your criteria', 'veronika')); ?>';
</script>



<?php
osc_enqueue_style('style', osc_current_web_theme_url('css/style.css?v=' . rand(1, 1000)));
osc_enqueue_style('fancy', osc_current_web_theme_js_url('fancybox/jquery.fancybox.css'));
osc_enqueue_style('responsive', osc_current_web_theme_url('css/responsive.css'));
osc_enqueue_style('font-awesome', osc_current_web_theme_url('fonts/fa/css/font-awesome.min.css'));
osc_enqueue_style('jquery-ui', osc_current_web_theme_url('css/jquery-ui.min.css'));   // For price slider

if(veronika_is_rtl()) {
  osc_enqueue_style('rtl', osc_current_web_theme_url('css/rtl.css')  . '?v=' . rand(0,1000));
}


if(!osc_is_search_page() && !osc_is_home_page() && !osc_is_ad_page()) {
  osc_enqueue_style('tabs', osc_current_web_theme_url('css/tabs.css'));
}

if(osc_is_ad_page()) {
  osc_enqueue_style('bxslider', osc_current_web_theme_url('css/bxslider/jquery.bxslider.css'));
}


if(veronika_ajax_image_upload() && (osc_is_publish_page() || osc_is_edit_page())) {
  osc_enqueue_style('fine-uploader-css', osc_assets_url('js/fineuploader/fineuploader.css'));
}


osc_remove_style('font-open-sans');
osc_remove_style('open-sans');
osc_remove_style('fi_font-awesome');
osc_remove_style('font-awesome44');
osc_remove_style('font-awesome45');
osc_remove_style('responsiveslides');
osc_remove_style('cookiecuttr-style');


osc_register_script('jquery-drag', osc_current_web_theme_js_url('jquery.drag.min.js'), 'jquery');
osc_register_script('global', osc_current_web_theme_js_url('global.js?v=' . rand(0,1000)));
osc_register_script('fancybox', osc_current_web_theme_url('js/fancybox/jquery.fancybox.pack.js'), array('jquery'));
osc_register_script('validate', osc_current_web_theme_js_url('jquery.validate.min.js'), array('jquery'));
osc_register_script('date', osc_base_url() . 'oc-includes/osclass/assets/js/date.js');
osc_register_script('priceFormat', osc_current_web_theme_js_url('jquery.priceFormat.js'));
osc_register_script('bxslider', osc_current_web_theme_js_url('jquery.bxslider.js'));
osc_register_script('lazyload', osc_current_web_theme_js_url('jquery.lazyload.js'));
osc_register_script('sticky', osc_current_web_theme_js_url('jquery.sticky-kit.min.js'));
osc_register_script('google-maps', 'https://maps.google.com/maps/api/js?key='.osc_get_preference('maps_key', 'google_maps'));
osc_register_script('images-loaded', osc_current_web_theme_js_url('jquery.imagesloaded.pkgd.min.js'));
osc_register_script('masonry', osc_current_web_theme_js_url('jquery.masonry.pkgd.min.js'));


osc_enqueue_script('jquery');
osc_enqueue_script('fancybox');
osc_enqueue_script('priceFormat');
osc_enqueue_script('sticky');


if(osc_get_preference('force_aspect_image', 'osclass') <> 1) {
  osc_enqueue_script('lazyload');
}

if(!osc_is_search_page() && !osc_is_home_page()) {
  osc_enqueue_script('validate');
}

if(osc_is_publish_page() || osc_is_edit_page() || osc_is_search_page()) {
  osc_enqueue_script('date');
}

if(osc_is_publish_page() || osc_is_edit_page()){
  osc_enqueue_script('date');
}


if( osc_is_ad_page() ) {
  osc_enqueue_script('bxslider');
}

if( osc_is_ad_page() && function_exists('google_maps_location') && osc_get_preference('include_maps_js', 'google_maps') != '0' ) {
  osc_enqueue_script('google-maps');
}

if( osc_get_preference('force_aspect_image', 'osclass') == 1 ) {
  osc_enqueue_script('masonry');
  osc_enqueue_script('images-loaded');
}

if(!osc_is_search_page() && !osc_is_home_page() && !osc_is_ad_page()) {
  osc_enqueue_script('tabber');
}

if(veronika_ajax_image_upload() && (osc_is_publish_page() || osc_is_edit_page())) {
  osc_enqueue_script('jquery-fineuploader');
}


osc_enqueue_script('jquery-ui');
osc_enqueue_script('global');

?>


<?php 
  if(osc_get_preference('search_cookies', 'veronika_theme') == 1) {
    veronika_manage_cookies(); 
  }
?>

<?php osc_run_hook('header'); ?>