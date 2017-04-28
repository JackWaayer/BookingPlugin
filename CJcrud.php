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
			customer_id int(11) NOT NULL,
			room_id int(11) NOT NULL,
			date_in text NOT NULL,
			date_out date NOT NULL,
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
//notice the menu slug 'CJbooking' - this is used through the FAQ demo in the URL's to identify this page in the WP dashboard
function CJ_booking_menu() {
	//add_submenu_page (string $parent_slug, string $page_title, string $menu_title, string $capability, string $menu_slug, callable $function = '')
	add_submenu_page( 'plugins.php', 'Booking Page', 'Booking', 'manage_options', 'CJbooking', 'CJ_booking_crud');
}





// add the 'settings' label to the plugin menu
// Add settings link on plugin page
function bookingSettingsLink($links) { 
//http://codex.wordpress.org/Function_Reference/admin_url
//refer the function CJ_booking_menu() above in regards to the menu slug 'CJbooking'
  array_unshift($links, '<a href="'.admin_url('plugins.php?page=CJbooking').'">Settings</a>'); 
  return $links; 
}





//Bad Query!!!!
/* //usage: [displayBooking]
//display all bookings by using a shortcode
function CJdisplayBooking() {
    global $wpdb;

    $query = "SELECT * FROM CJ_booking";
    $allBookings = $wpdb->get_results($query);
	//pr($allBookings); //uncomment this line to see the out of the query - it is typically an array of objects
    $buffer = '<ol>';
	//note the naming convention adopted here. results (array) from a query in plural and a singular result element from the array
    foreach ($allBookings as $booking) {
		$buffer .= '<li>'.$booking->date.'</li>';	
    }
    $buffer .= '</ol>';
    return $buffer;
} */

add_shortcode('booking', 'showBooking');
function showBooking(){
	CJ_booking_crud();
}







//basic CRUD selector
function CJ_booking_crud() {

 //--- some basic debugging for information purposes only
	echo '<h3>Contents of the POST data</h3>';
	pr($_POST); //show the contents of the HTTP POST response from a new/edit command from the form
	echo '<h3>Contents of the REQUEST data</h3>';
	pr($_REQUEST);	 //show the contents of any variables made with a GET HTTP request
//--- end of basic debugging  

	echo  '<div id="msg" style="overflow: auto"></div>
		<div class="wrap">
		<h2>Booking CRUD <a href="?page=CJbooking&command=new" class="add-new-h2">Add New</a></h2>
		<div style="clear: both"></div>';
		
	

// !!WARNING: there is no data validation conducted on the _REQUEST or _POST information. It is highly 
// recommend to parse ALL data/variables before using		
	$bookingData = $_POST; //our form data from the insert/update
	
//current booking id for delete/edit commands
	if (isset($_REQUEST['id'])) 
		$bookingID = $_REQUEST['id']; 
	else 
		$bookingID = '';

//current CRUD command		
	if (isset($_REQUEST["command"])) 
		$command = $_REQUEST["command"]; 
	else 
		$command = '';
		
//execute the respective function based on the command		
    switch ($command) {
		//operations access through the URL	
		case 'view':
			CJ_booking_view($bookingID);
		break;
		
		case 'edit':
			$msg = CJ_booking_form('update', $bookingID); //notice the $bookingID passed for the form for an update/edit
		break;

		case 'new':
		    //notice the null passed for the insert/new to the form. if the null was omitted the function 
			// will still use null as the default - refer to the CJ_booking_form for more details
			$msg = CJ_booking_form('insert',null);
		break;
		
		//operations performing the various database tasks based on the previous CRUD command
		case 'delete':
			$msg = CJ_booking_delete($bookingID); //remove a booking entry
			$command = '';
		break;

		case 'update':
			$msg = CJ_booking_update($bookingData); //update an existing faq
			$command = '';
		break;

		case 'insert':	
			$msg = CJ_booking_insert($bookingData); //prepare a blank form for adding a new faq entry
			$command = '';
		break;
	}
	
	//a simple catchall if the command is not found in the switch selector
	if (empty($command)) CJ_booking_list(); //display a list of the faqs if no command issued

	//show any information messages	
	if (!empty($msg)) {
      echo '<p><a href="?page=CJbooking"> back to the booking list </a></p> Message: '.$msg;      
	}
	echo '</div>';
}











//The main dashboard listing with the CRUD links. Take note of the styleing used to align with
//the Wordpress dashboard. Compare with the Wordpress Pages and Posts pages
function CJ_booking_list() {
   global $wpdb, $current_user;

   //prepare the query for retrieving the FAQ's from the database
   $query = "SELECT id, customer_id, room_id, date_in , date_out FROM CJ_booking";
   $allBookings = $wpdb->get_results($query);

   //prepare the table and use a default WP style - wp-list-table widefat and manage-column
   echo '<table class="wp-list-table widefat">
		<thead>
			<tr>
				<th scope="col" class="manage-column">ID</th>
				<th scope="col" class="manage-column">Customer</th>
				<th scope="col" class="manage-column">Room</th>
				<th scope="col" class="manage-column">Date In</th>
				<th scope="col" class="manage-column">Date Out</th>
			</tr>
		</thead>
		<tbody>';
    
    	foreach ($allBookings as $booking) {
		//if ($booking->author_id == 0) $booking->author_id = $current_user->ID;
		
		//use a WP function to retrieve user information based on the id
		//$user_info = get_userdata($booking->author_id);
	   
		//prepare the URL's for some of the CRUD - note again the use of the menu slug to maintain page location between operations	   
		$edit_link = '?page=CJbooking&id=' . $booking->id . '&command=edit';
		$view_link ='?page=CJbooking&id=' . $booking->id . '&command=view';
		$delete_link = '?page=CJbooking&id=' . $booking->id . '&command=delete';

		//use some inbuilt WP CSS to perform the hover effect for the edit/view/delete links
		
		echo '<tr>';
		
		// Edit, View and delete buttons
		echo '<td><strong><a href="'.$edit_link.'" title="Edit Booking">' . $booking->id . '</a></strong>';
		echo '<div class="row-actions">';
		echo '<span class="edit"><a href="'.$edit_link.'" title="Edit this item">Edit</a></span> | ';
		echo '<span class="view"><a href="'.$view_link.'" title="View this item">View</a></span> | ';
		echo '<span class="trash"><a href="'.$delete_link.'" title="Move this item to Trash" onclick="return doDelete();">Trash</a></span>';
		echo '</div>';
		echo '</td>';
		
		
		echo '<td>' . $booking->customer_id . '</td>';
		echo '<td>' . $booking->room_id . '</td>';
		echo '<td>' . $booking->date_in . '</td>';
		echo '<td>' . $booking->date_out . '</td>';
		
		
		//echo '<td>' . $user_info->user_login . '</td>';
	   
		//display the status in words depending on the current status value in the database - 0 or 1	   
		// $status = array('Draft', 'Published');
		// echo '<td>' . $status[$booking->status] . '</td>';
		echo '</tr>';  
    	}
	echo '</tbody></table>';
	
	//small piece of javascript for the delete confirmation	
	echo "<script type='text/javascript'>
			function doDelete() { if (!confirm('Are you sure?')) return false; }
		  </script>";
}









//view all the detail for a single Booking
function CJ_booking_view($id) {
	global $wpdb;

	//https://codex.wordpress.org/Class_Reference/wpdb#Protect_Queries_Against_SQL_Injection_Attacks
	//safer preferred method of passing values to an SQL query this is not a substitute for data validation
	//this method merely reduces the likelyhood of SQL injections
	$qry = $wpdb->prepare("SELECT * FROM CJ_booking WHERE id = %s",$id);
   
	//$qry = $wpdb->prepare("SELECT * FROM WAD_faq WHERE id = %s",array($id)); //alternative using an array
	//pr($qry); //uncomment this line to see the prepared query
	$row = $wpdb->get_row($qry);
   
	echo '<p>';
	echo "Booking ID:";
	echo '<br/>';
	echo $row->id;
   
	echo '<p>';
	echo "Customer ID:";
	echo '<br/>';
	echo $row->customer_id;
   
	echo '<p>';
	echo "Room ID:";
	echo '<br/>';
	echo $row->room_id;
   
	echo '<p>';
	echo "Arrive:";
	echo '<br/>';
	echo $row->date_in;
   
	echo '<p>';
	echo "Leave:";
	echo '<br/>';
	echo $row->date_out;
   
	echo '<p><a href="?page=CJbooking">&laquo; back to list</p>';
}








//remove an existing Booking from the database
function CJ_booking_delete($id) {
   global $wpdb;
   
//$wpdb->delete can also be used here instead of a query
//refer to the WAD_faq_view for details on the prepared query. 
//$wpdb->prepare can be omitted if the $wpdb->delete version is used
   $results = $wpdb->query($wpdb->prepare("DELETE FROM CJ_booking WHERE id=%s",$id));
   if ($results) {
      $msg = "Booking entry $id was successfully deleted.";
   }
   return $msg;
}






//add a new Booking to the database
function CJ_booking_insert($data) {
    global $wpdb, $current_user;

//add in data validation and error checking here before updating the database!!
    $wpdb->insert( 'CJ_booking',
		  array(
			'customer_id' => stripslashes_deep($data['customer_id']),
			'room_id' => ($data['room_id']),
			'date_in' => ($data['date_in']),
			'date_out' => $data['date_out']),
		  array( '%s', '%s', '%s', '%s', '%s' ) );
    $msg = "A Booking entry has been added";
    return $msg;
}





//update an existing booking in the database
function CJ_booking_update($data) {
    global $wpdb, $current_user;
	
//add in data validation and error checking here before updating the database!!
    $wpdb->update('CJ_booking',
		  array( 'id' => ($data['id']),
				 'customer_id' => ($data['customer_id']),
				 'room_id' => ($data['room_id']),
				 'date_in' => ($data['date_in']),
				 'date_out' => $data['date_out']),
		  array( 'id' => $data['id']));
    $msg = "Booking ".$data['id']." has been updated";
    return $msg;
}





//this is the form used for the insert as well as the edit/update of the FAQ data
//here we introduce default values for the function parameter list. if the second parameter 
//was omitted then the id will assume the value null (insert a new record - has no initial ID)
function CJ_booking_form($command, $id = null) {
    global $wpdb;

//if the current command is insert then clear the form variables to ensure we have a blank
//form before starting	
    if ($command == 'insert') {
      $booking->customer_id   = '';
	  $booking->room_id   = '';
	  $booking->date_in   = '';
	  $booking->date_out   = '';
    }
	
//if the current command was 'edit' then retrieve the booking based on the id pased to this function
//!!this SQL querey is open to potential injection attacks
	if ($command == 'update') {
        $booking = $wpdb->get_row("SELECT * FROM CJ_booking WHERE id = '$id'");
	}

//prepare the draft/published status for the HTML check boxes	
	/* if (isset($faq)) {
		$draftstatus = ($faq->status == 0)?"checked":"";
		$pubstatus   = ($faq->status == 1)?"checked":"";
	} */
	
//prepare the HTML form	
    echo '<form name="CJbookingForm" method="post" action="?page=CJbooking">
		
		<input type="hidden" name="command" value="'.$command.'"/>
		<input type="hidden" name="id" value="'.$id.'"/>
		
		<p>Customer ID:<br/>
		<input type="text" name="customer_id" value="'.$booking->customer_id.'"/>
		
		<p>Room ID:<br/>
		<input type="text" name="room_id" value="'.$booking->room_id.'"/>
		
		<p>Date In:<br/>
		<input type="date" name="date_in" value="'.$booking->date_in.'"/>
		
		<p>Date Out:<br/>
		<input type="date" name="date_out" value="'.$booking->date_out.'"/>
		</p>
		<hr />
		
		<p class="submit"><input type="submit" name="Submit" value="Save Changes" class="button-primary" /></p>
		</form>';
		
		//Status form info. May need this???
		/* <p>
		<label><input type="radio" name="status" value="0" '.$draftstatus.'> Draft</label> 
		<label><input type="radio" name="status" value="1" '.$pubstatus.'> Published</label> 
		</p> */
		
   echo '<p><a href="?page=CJbooking">&laquo; back to list</p>';		
}



?>