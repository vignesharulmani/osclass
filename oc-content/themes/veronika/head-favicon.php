<?php
  if (!defined('ABS_PATH')) {
    define('ABS_PATH', dirname(dirname(dirname(dirname(__FILE__)))) . '/');
  }

  require_once ABS_PATH . 'oc-load.php';
  require_once ABS_PATH . 'oc-content/themes/veronika/functions.php';

  $path = osc_base_path() . 'oc-content/themes/veronika/images/favicons/';
  $url = osc_base_url() . 'oc-content/themes/veronika/images/favicons/';
?>
<?php if(file_exists($path . 'favicon.ico')) { ?>
<link rel="shortcut icon" type="image/x-icon" href="<?php echo $url; ?>favicon.ico" />
<?php } ?>
<?php if(file_exists($path . 'favicon-16x16.png')) { ?>
<link rel="icon" href="<?php echo $url; ?>favicon-16x16.png" sizes="16x16" type="image/png" />
<?php } ?>
<?php if(file_exists($path . 'favicon-32x32.png')) { ?>
<link rel="icon" href="<?php echo $url; ?>favicon-32x32.png" sizes="32x32" type="image/png" />
<?php } ?>
<?php if(file_exists($path . 'favicon-72x72.png')) { ?>
<link rel="icon" href="<?php echo $url; ?>favicon-72x72.png" sizes="72x72" type="image/png" />
<?php } ?>
<?php if(file_exists($path . 'favicon-96x96.png')) { ?>
<link rel="icon" href="<?php echo $url; ?>favicon-96x96.png" sizes="96x96" type="image/png" />
<?php } ?>
<?php if(file_exists($path . 'favicon-144x144.png')) { ?>
<link rel="icon" href="<?php echo $url; ?>favicon-144x144.png" sizes="144x144" type="image/png" />
<?php } ?>
<?php if(file_exists($path . 'favicon-192x192.png')) { ?>
<link rel="icon" href="<?php echo $url; ?>favicon-192x192.png" sizes="192x192" type="image/png" />
<?php } ?>
<?php if(file_exists($path . 'apple-touch-icon-60x60.png')) { ?>
<link rel="apple-touch-icon" href="<?php echo $url; ?>apple-touch-icon-60x60.png" />
<?php } ?>
<?php if(file_exists($path . 'apple-touch-icon-72x72.png')) { ?>
<link rel="apple-touch-icon" href="<?php echo $url; ?>apple-touch-icon-72x72.png" sizes="72x72" />
<?php } ?>
<?php if(file_exists($path . 'apple-touch-icon-76x76.png')) { ?>
<link rel="apple-touch-icon" href="<?php echo $url; ?>apple-touch-icon-76x76.png" sizes="76x76" />
<?php } ?>
<?php if(file_exists($path . 'apple-touch-icon-114x114.png')) { ?>
<link rel="apple-touch-icon" href="<?php echo $url; ?>apple-touch-icon-114x114.png" sizes="114x114" />
<?php } ?>
<?php if(file_exists($path . 'mstile-144x144.png')) { ?>
<meta name="msapplication-tileimage" content="<?php echo $url; ?>mstile-144x144.png" />
<?php } ?>
<?php if(file_exists($path . 'apple-touch-icon-120x120.png')) { ?>
<link rel="apple-touch-icon" href="<?php echo $url; ?>apple-touch-icon-120x120.png" sizes="120x120" />
<?php } ?>
<?php if(file_exists($path . 'apple-touch-icon-144x144.png')) { ?>
<link rel="apple-touch-icon" href="<?php echo $url; ?>apple-touch-icon-144x144.png" sizes="144x144" />
<?php } ?>
<?php if(file_exists($path . 'apple-touch-icon-152x152.png')) { ?>
<link rel="apple-touch-icon" href="<?php echo $url; ?>apple-touch-icon-152x152.png" sizes="152x152" />
<?php } ?>