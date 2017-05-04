<?php
session_start();


//simple variable debug function
//usage: pr($avariable);
if (!function_exists('pr')) {
  function pr($var) { echo '<pre>Diagnostics: '; var_dump($var); echo '</pre>';}
}




function CJ_list_bookings($status){
	
	global $wpdb, $page_id;
	$uid = $_SESSION['uid'];
	$username = $_SESSION['name'];
	
	echo '<h2>Bookings</h2>';
	echo '<h3>Hello '.$username.' welcome to the booking application.</h3>';
	echo '<p>This is a list of your current bookings</p>';
	
	
	if($status == 0){
		$query = $wpdb->prepare("SELECT * FROM CJ_booking");
	}
	else if($status == 1){
		$query = $wpdb->prepare("SELECT * FROM cj_booking WHERE account_id = %s",$uid);
	}
	else{
		$query = "";
	}
	
	
	$allrecs = $wpdb->get_results($query);
	
    $buffer = '<hr />
                <table>
                    <th>Account ID</th>
                    <th>Room ID</th>
                    <th>Date In</th>
                    <th>Date Out</th>';
    foreach ($allrecs as $rec) {
		$buffer .= '<tr><td>'.$rec->account_id.'</td><td>'.$rec->room_id.'</td><td>'.$rec->date_in.'</td><td>'.$rec->date_out.'</td></tr>';	
    }
    $buffer .= '</table>';
    $buffer .= '<a href="?page_id='.$page_id.'&cmd=logout">Logout</a>';
    echo $buffer;
}






?>