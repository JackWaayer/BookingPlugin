<?php
session_start();

//simple variable debug function
//usage: pr($avariable);
if (!function_exists('pr')) {
  function pr($var) { echo '<pre>Diagnostics: '; var_dump($var); echo '</pre>';}
}





//-------------------------------------------------------------------
/* add in the jquery library and AJAX support to our plugin 
 * add in our custom CSS and custom js for form validation
 * https://github.com/malsup/form
 * http://bassistance.de/jquery-plugins/jquery-plugin-validation/
 * http://docs.jquery.com/Plugins/Validation */
// add_action( 'wp_enqueue_scripts', 'WAD_load_scripts2' );
// function WAD_load_scripts2() {
// //custommmm styles
//     wp_enqueue_style( 'WAD11', plugins_url('css/WAD11.css',__FILE__));

// }




function CJ_list_rooms() {
    global $wpdb, $page_id;
	
    ?>
	    <h1>Room Details</h1>
    <?php
	

	//grab all the rooms from the database
    $query = "SELECT * FROM cj_room";
    $allrecs = $wpdb->get_results($query);

    $imgSrcs = array(
                'http://70.townhousehotels.com/var/ezdemo_site/storage/images/hotels/torino/townhouse-70/rooms-suites/single-rooms/gallery/single-room/2521-2-eng-GB/Single-Room.jpg',
                'http://www.grandxlhotels.com/wp-content/uploads/2015/12/h_694f4b8d43.jpg',
                'http://amari.azureedge.net/watergate/hotel-photos/executive-room-1.jpg'
    );
    $imgCounter = 0;
	
    ?>
    <hr />
        <table>
            <th>Room Name</th>
            <th>Desription</th>
            <th>Price</th>
            <th></th>
            <th>Reviews</th>
    <?php
    foreach ($allrecs as $rec) {
		?>
        <tr>
            <td style='padding: 80px 5px 0px 5px; text-align: center'><?php echo $rec->room_name ?></td>
            <td style='padding: 80px 5px 0px 5px; text-align: center'><?php echo $rec->description ?></td>
            <td style='padding: 80px 5px 0px 5px; text-align: center'>$<?php echo $rec->price ?></td>
            <td><image src='<?php echo $imgSrcs[$imgCounter]; $imgCounter++ ?>' alt="Image of room" style="width:200px;height:150px;"></image></td>
            <td>
                <a href="?page_id='.$page_id.'&cmd=home"><button class='btn btn-info' style='display: block; width: 150px; margin: 50px 0 50px 0;'>Browse Reviews</button></a>
                <button class='btn btn-success' style='display: block; width: 150px; margin: 50px 0 50px 0;'>Place a Review</button>
            </td>
        </tr>
    <?php
    }
    ?>
    </table>
    <?php

}



function CJ_get_room($roomID){
	global $wpdb;
	
	$qry = $wpdb->prepare("SELECT * FROM cj_room WHERE id = %s",$roomID);
	return $wpdb->get_results($qry);
}



function CJ_get_all_rooms(){
	global $wpdb;
	$query = "SELECT * FROM cj_room";
    return $wpdb->get_results($query);
}









?>