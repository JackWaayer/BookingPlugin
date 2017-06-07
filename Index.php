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
	require_once __DIR__ . '/CJmy_profile.php';
	require_once __DIR__ . '/CJregister.php';
	require_once __DIR__ . '/CJhome.php';
	require_once __DIR__ . '/CJpayment.php';
	require_once __DIR__ . '/CJcontact.php';
	require_once __DIR__ . '/CJdash_index.php';
	



	//Current database version for the Booking Plugin
	$CJ_dbversion = "0.7";
	
	
	add_action( 'wp_enqueue_scripts', 'WAD_load_scripts' );
	function WAD_load_scripts() {	
		//Bootstrap
		wp_register_style('prefix_bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css');
		wp_enqueue_style('prefix_bootstrap');

		//add in jquery for the AJAX
		wp_enqueue_style( 'jquery-ui', plugins_url('css/jquery-ui.css',__FILE__));	
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui-datepicker');
		wp_enqueue_script( 'jquery_validate',plugins_url('js/jquery.validate.js',__FILE__) );
		wp_enqueue_script( 'jquery_forms',plugins_url('js/jquery.form.js',__FILE__) );
		wp_enqueue_script( 'json2' ); //required for AJAX to work with JSON	
	}
	
	
	
	
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
		
				$sql = '
				CREATE TABLE CJ_booking (
				id int(11) NOT NULL auto_increment,
				account_id int(11) NOT NULL,
				room_id int(11) NOT NULL,
				date_booked date NOT NULL,
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
				description varchar(1000) NOT NULL,
				price float(3,2) NOT NULL,
				PRIMARY KEY (id)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;
				
				CREATE TABLE CJ_review (
				id int(11) NOT NULL auto_increment,
				rating int(1) NOT NULL,
				description varchar(200) NOT NULL,
				account_id int(11) NOT NULL,
				room_id int(11) NOT NULL,
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
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;
				';

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

			if($cmd != "logout"){
				set_html_temp();
			}
			/*Diagnostics
			pr($data);*/
			switch ($cmd) {
				case "home":
					CJ_home();
					break;
				case "myProfile":
					CJ_my_profile();
					break;
				case "login":
					CJ_login($data);
					break;
				case "register":
					$msg = CJ_register($data);
					break;
				case "logout":
					wp_logout();
					wp_set_current_user(0);
					$msg = '<h2>You have been logged out!</h2>';
					set_html_temp();
					CJ_login($data);
					break;
				case "rooms":
					CJ_list_rooms();
					break;
				case "makeBooking":
					CJ_booking_calendar($data);
					break;
				case "confirm":
					CJ_confirm_booking($data);
					break;
				case "payment":
					CJ_payment($data);
					break;
				case "paymentInserts":
					CJ_paymentInserts($data);
					break;
				case "removeBookings":
					CJ_removeBooking($data);
					break;
				case "contact":
					CJ_contact($data);
					break;
				default:
					CJ_home(); //catch random commands
			}
		} else {
			set_html_temp();
			CJ_home();
		}

		

		echo '<p>'.$msg.'</p>';
	}
	
	
	
	//index html
	function set_html_temp(){
		
		echo '
		<nav class="navbar navbar-inverse">
			<div class="container-fluid">
				<ul class="nav navbar-nav" style="height: 20px;">
					<li><a href="?page_id='.$page_id.'&cmd=home">Home</a></li>
					<li><a href="?page_id='.$page_id.'&cmd=rooms">Rooms</a></li>
					<li><a href="?page_id='.$page_id.'&cmd=makeBooking">Booking</a></li>';
					
					if(is_user_logged_in()){
					echo '
						<li><a href="?page_id='.$page_id.'&cmd=myProfile">Profile</a></li>
					</ul>
						<ul class="nav navbar-nav navbar-right" style="height: 20px;">
							<li><a href="?page_id='.$page_id.'&cmd=logout"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
						</ul>
					';
					}
					else if(!is_user_logged_in()){
					echo '
					</ul>
						<ul class="nav navbar-nav navbar-right" style="height: 20px;">
							<li><a href="?page_id='.$page_id.'&cmd=register"><span class="glyphicon glyphicon-user"></span> Register</a></li>
							<li><a href="?page_id='.$page_id.'&cmd=login"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
						</ul>';
					}

			echo '
			</div>
		</nav>
		';
	}
	
	







?>