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
	
	?>
		<h1>My Profile</h1>
		<h3>Hello <?php echo $account[0]->first_name ?> welcome to the booking application.</h3>
	<?php
	
	CJ_list_bookings($account[0]->id);
	
	?>
		<p style='margin: 10px 0'>Have a problem? <a href="?page_id=<?php echo $page_id ?>&cmd=contact">Contact us</a> here!</p>
	<?php
}



function CJ_get_user_account(){
	global $wpdb;
	$uid = get_current_user_id();
	$query = $wpdb->prepare("SELECT * FROM cj_account WHERE user_id = %s",$uid);
	return $wpdb->get_results($query);
	
	
}


?>