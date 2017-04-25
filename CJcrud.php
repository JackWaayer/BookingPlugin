<?php

//simple variable debug function
//usage: pr($avariable);
if (!function_exists('pr')) {
  function pr($var) { echo '<pre>'; var_dump($var); echo '</pre>';}
}

//Current database version for the Booking Plugin
$CJ_dbversion = "0.1";


//Hooks
register_activation_hook( __FILE__, 'CJ_booking_install' );
register_uninstall_hook( __FILE__, 'CJ_booking_uninstall' );

add_action('plugins_loaded', 'CJ_update_db_check');
add_action('plugin_action_links_'.plugin_basename(__FILE__), 'bookingSettingsLink' );  

add_shortcode('displayBooking', 'CJdisplayBooking');
add_action('admin_menu', 'CJ_booking_menu');



//check to see if there is any update required for the database, 
//just in case we updated the plugin without reactivating it
function WAD_update_db_check() {
	global $CJ_dbversion;
	if (get_site_option('CJ_dbversion') != $CJ_dbversion) CJ_booking_install();  
}





//install or retrieve the latest database version 
function CJ_booking_install () {
	global $wpdb;
	global $CJ_dbversion;

	$currentversion = get_option( "CJ_dbversion" ); //retrieve the version of the database which has been installed.
	if ($CJ_dbversion > $currentversion) { //version still good?
		if($wpdb->get_var("show tables like 'CJ_booking'") != 'CJ_booking') {//check if the table already exists
	
			$sql = 'CREATE TABLE CJ_booking (
			id int(11) NOT NULL auto_increment,
			author_id int(11) NOT NULL,
			question_date date NOT NULL,
			question text NOT NULL,
			answer_date date NOT NULL,
			answer text NOT NULL,
			status tinyint(4) NOT NULL,
			PRIMARY KEY (id)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;';

			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
			//update the version of the database with the new one
			update_option( "CJ_dbversion", $CJ_dbversion );
			add_option("CJ_dbversion", $CJ_dbversion);
		}  
   }	
}   

//clean up and remove any settings than may be left in the wp_options table from our plugin
function CJ_booking_uninstall() {
	delete_site_option($CJ_dbversion);
	delete_option($CJ_dbversion);
}




//add in the FAQ menu entry under the plugins menu.
//notice the menu slug 'WADsimplefaq' - this is used through the FAQ demo in the URL's to identify this page in the WP dashboard
function WAD_faq_menu() {
		add_submenu_page( 'plugins.php', 'WAD 8. Simple FAQ', 'WAD Simple FAQ', 'manage_options', 'WADsimplefaq', 'WAD_faq_CRUD');
}



// add the 'settings' label to the plugin menu
// Add settings link on plugin page
function bookingSettingsLink($links) { 
//http://codex.wordpress.org/Function_Reference/admin_url
//refer the function WAD_faq_menu() above in regards to the menu slug 'CJbooking'
  array_unshift($links, '<a href="'.admin_url('plugins.php?page=CJbooking').'">Settings</a>'); 
  return $links; 
}







?>