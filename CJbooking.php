<?php
session_start();


//simple variable debug function
//usage: pr($avariable);
if (!function_exists('pr')) {
  function pr($var) { echo '<pre>Diagnostics: '; var_dump($var); echo '</pre>';}
}




function CJ_list_bookings($accountID){
	
	global $wpdb, $page_id;
	$status = $_SESSION['user_status'];
	
	if($status == 0){
		$query = $wpdb->prepare("SELECT * FROM cj_booking");
	}
	else if($status == 1){
		$query = $wpdb->prepare("SELECT * FROM cj_booking WHERE account_id = %s",$accountID);
	}
	else{
		$query = "";
	}
	
	
	$allrecs = $wpdb->get_results($query);
	
    $buffer = '<hr />
                <table>
                    <th>Room Name</th>
					<th>Date Reserved</th>
                    <th>Date In</th>
                    <th>Date Out</th>';
    foreach ($allrecs as $rec) {
		$buffer .= '<tr>
						<td>'.CJ_get_room($rec->room_id)[0]->room_name.'</td>
						<td>'.$rec->date_reserved.'</td>
						<td>'.$rec->date_in.'</td>
						<td>'.$rec->date_out.'</td>
					</tr>';	
    }
    $buffer .= '</table>';
    
    echo $buffer;
	
}






?>