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






//usage: [displayBooking]
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
		<h2>Make a Booking <a href="?page=CJbooking&command=view" class="add-new-h2">Add New</a></h2>
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
      echo '<p><a href="?page=CJbooking"> back to the FAQ list </a></p> Message: '.$msg;      
	}
	echo '</div>';
}







//view all the detail for a single FAQ
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
		
		/* echo '<td><strong><a href="'.$edit_link.'" title="Edit Date In">' . $booking->date_in . '</a></strong>';
		echo '<div class="row-actions">';
		echo '<span class="edit"><a href="'.$edit_link.'" title="Edit this item">Edit</a></span> | ';
		echo '<span class="view"><a href="'.$view_link.'" title="View this item">View</a></span> | ';
		echo '<span class="trash"><a href="'.$delete_link.'" title="Move this item to Trash" onclick="return doDelete();">Trash</a></span>';
		echo '</div>';
		echo '</td>'; */
		
		
		echo '<td>' . $booking->id . '</td>';
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

?>