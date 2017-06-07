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
	
	
	CJ_list_bookings($account[0]->id);
	
	echo '<p>Have a problem? <a href="?page_id='.$page_id.'&cmd=contact">Contact us</a> here!</p>';
}



function CJ_get_user_account(){
	global $wpdb;
	$uid = get_current_user_id();
	$query = $wpdb->prepare("SELECT * FROM cj_account WHERE user_id = %s",$uid);
	return $wpdb->get_results($query);
	
	
}


?>