<?php
session_start();


//simple variable debug function
//usage: pr($avariable);
if (!function_exists('pr')) {
  function pr($var) { echo '<pre>Diagnostics: '; var_dump($var); echo '</pre>';}
}


function CJ_my_profile() {
	global $wpdb, $page_id;
	$account = CJ_get_user_account();
	
	echo '<h2>My Profile</h2>';
	echo '<h3>Hello '.$account[0]->first_name.' welcome to the booking application.</h3>';
	echo '<p>This is a list of your current bookings/reservations</p>';
	
	CJ_list_bookings($account[0]->id);
	
	echo '<a href="?page_id='.$page_id.'&cmd=logout">Logout</a>';
	echo '<a href="?page_id='.$page_id.'&cmd=calender"><button>Make a booking</button></a>';
	echo '<a href="?page_id='.$page_id.'&cmd=rooms"><button>View Rooms</button></a>';
}



function CJ_get_user_account(){
	global $wpdb;
	$uid = $_SESSION['uid'];
	$query = $wpdb->prepare("SELECT * FROM cj_account WHERE user_id = %s",$uid);
	return $wpdb->get_results($query);
	
	
}


?>