<?php
/*
Plugin Name: Scrolltop
Plugin URI: http://www.osclass.org/
Description: Remonter en haut de page
Version: 5.0
Author: Georges modified by MB-themes.com
Author URI: http://www.osclass.org/
Short Name: Scrolltop
*/

    function scrolltop() {
	
	echo //'<p id="back-top-left">
		//	<a href="#"><span></span>' . __('Haut de page', 'scrolltop') . '</a>
		 // </p>
		  '<p id="back-top-right">
			<a href="#"><span></span></a>
		  </p>';
    }
    
		
    function scrolltop_admin_menu() {
    echo '<h3><a href="#">Scrolltop</a></h3>
    <ul>
        <li><a href="'.osc_admin_render_plugin_url("scrolltop/help.php").'?section=types">&raquo; ' . __('F.A.Q. / Aide', 'scrolltop') . '</a></li>
    </ul>';
    }		


    /**
     *  HOOKS
     */
    osc_register_plugin(osc_plugin_path(__FILE__), '');
    osc_add_hook(osc_plugin_path(__FILE__) . '_uninstall', '');
	
	osc_add_hook('after_error_page', 'scrolltop');
	osc_add_hook('admin_menu', 'scrolltop_admin_menu');
    
?>
