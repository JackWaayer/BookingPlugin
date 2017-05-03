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
	require_once __DIR__ . '/CJcrud.php'; 
	require_once __DIR__ . '/CJrooms.php'; 
	require_once __DIR__ . '/CJbooking.php';
	



	
	
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