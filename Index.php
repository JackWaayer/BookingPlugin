<?php
	/*
	Plugin Name: Booking Plugin
	Plugin URI: http://localhost/
	Description: Booking plugin for accomodation reservations
	Author: Jack Waayer, Cameron Anderson
	Version: 1.1.1
	Author URI: http://
	Last update: 12 April 2017
	*/

	/* DEBUGGING NOTE
	 * Change the line 81 of the wp-config.php file in the Wordpress root folder
	 * from 	define('WP_DEBUG', false);
	 * to		define('WP_DEBUG', true);
	 * This will enable the debugging and any error messages.
	*/
	/* CHANGELOG
		12APR2017 - Initial release.
	*/


	require_once __DIR__ . '/CJlogin.php';
	require_once __DIR__ . '/CJlogout.php';
	require_once __DIR__ . '/CJadmin.php'; 
	require_once __DIR__ . '/CJrooms.php'; 
	require_once __DIR__ . '/CJbooking.php';
	



	//Current database version for the Booking Plugin
	$CJ_dbversion = "0.4";
	
	
	
	add_action('plugins_loaded', 'CJ_update_db_check');
	//check to see if there is any update required for the database, 
	//just in case we updated the plugin without reactivating it
	function CJ_update_db_check() {
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
				account_id int(11) NOT NULL,
				room_id int(11) NOT NULL,
				date_reserved date NOT NULL,
				date_in date NOT NULL,
				date_out date NOT NULL,
				type int(11) NOT NULL,
				status int(11) NOT NULL,
				PRIMARY KEY (id)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;
				
				CREATE TABLE CJ_account (
				id int(11) NOT NULL auto_increment,
				first_name varchar(30) NOT NULL,
				last_name varchar(30) NOT NULL,
				home_number varchar(20) NOT NULL,
				mobile_number varchar(20) NOT NULL,
				user_id int(11) NOT NULL,
				PRIMARY KEY (id)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;
				
				CREATE TABLE CJ_room (
				id int(11) NOT NULL auto_increment,
				room_name varchar(30) NOT NULL,
				description varchar(30) NOT NULL,
				price float(3,2) NOT NULL,
				PRIMARY KEY (id)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;
				
				CREATE TABLE CJ_review (
				id int(11) NOT NULL auto_increment,
				rating int(1) NOT NULL,
				description varchar(200) NOT NULL,
				PRIMARY KEY (id)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;
				
				CREATE TABLE CJ_extra (
				id int(11) NOT NULL auto_increment,
				extra_name varchar(20) NOT NULL,
				price float(3,2) NOT NULL,
				PRIMARY KEY (id)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;
				
				CREATE TABLE CJ_booking_extra (
				id int(11) NOT NULL auto_increment,
				booking_id int(11) NOT NULL,
				extra_id int(11) NOT NULL,
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
	
	
	
	
	
	//	simple variable debug function
	//	usage: pr($avariable);
	if (!function_exists('pr')) {
	  function pr($var) { echo '<pre>'; var_dump($var); echo '</pre>';}
	}
	
	
	//Basic routing
	add_shortcode('plugin', 'myRoute');
	function myRoute(){
    global $page_id; //required to determine the currently active page
    global $wpdb;

    //parse any incoming actions or commands from our page - can be placed in it's own function
	if (isset($_GET['cmd']) && !empty($_GET['cmd'])) {
		$cmd = $_GET['cmd'];
		$msg = $_GET['msg'];
		$data = $_POST;
        /*Diagnostics
		pr($data);*/
		switch ($cmd) {
			case "bookings":
				CJ_list_bookings($_SESSION['status']);
				break;
			case "login":
				CJ_login($data, $msg);
				break;
			case "logout":
				CJ_logout();
				break;
			default:
				CJ_list_rooms(); //catch random commands
		}
	} else CJ_list_rooms();	
}
	
	
	
	

	/*	Runs when the template calls the wp_head() function. 
		This hook is generally placed near the top of a page template between <head> and </head>. 
		This hook does not take any parameters. */
	//	http://codex.wordpress.org/Plugin_API/Action_Reference/wp_head
	add_action('wp_head','headerHook');
	function headerHook() {
		//Client specific header details e.g.(js / css scripts)
	}







?>