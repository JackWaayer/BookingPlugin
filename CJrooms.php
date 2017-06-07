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
            <td style='padding: 50px 5px 0px 5px; text-align: center'><?php echo $rec->room_name ?></td>
            <td style='padding: 50px 5px 0px 5px; text-align: center'><?php echo $rec->description ?></td>
            <td style='padding: 50px 5px 0px 5px; text-align: center'>$<?php echo $rec->price ?></td>
            <td><image src='<?php echo $imgSrcs[$imgCounter]; $imgCounter++ ?>' alt="Image of room" style="width:250px;height:100px;"></image></td>
            <td>
                <form method="POST" action="?page_id='.$page_id.'&cmd=writeReview">
                    <input type="text" name="roomID" value="<?php echo $rec->id ?>" style="visibility: hidden;">
                    <button type="submit" class='btn btn-info' style='display: block; width: 150px; margin: 20px 0 0 0;'>Reviews</button>
                </form>
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


function CJ_review($data){
    global $wpdb;

    $qry2 = $wpdb->prepare('SELECT room_name FROM cj_room WHERE id = %s',$data['roomID']);
    $roomName = $wpdb->get_results($qry2);

    ?>
    <h1>Reviews</h1>

    <h5>Write a review for <?php echo $roomName[0]->room_name ?></h5>

        <form method="POST">
            <select name="rating" style="display: block; margin-bottom: 10px;" required>
                <option value="" selected disabled>Choose rating</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
            <textarea required maxlength="500" rows=5 cols=60 name="review" placeholder="Write your review here..."></textarea>
            <input type="text" name="roomID" value="<?php echo $data['roomID'] ?>" style="visibility: hidden;">
            <button type="submit" class="btn btn-info">Submit</button>
        </form>
        <br />
        <br />
    <?php



    if(isset($data['review']) && isset($data['rating'])){
        $uid = get_current_user_id();
        $qry1 = $wpdb->prepare('SELECT id from cj_account WHERE user_id = %s',$uid);
        $accountID = $wpdb->get_results($qry1);
        if(
            $wpdb->insert('cj_review',
				array(
				'account_id'=>($accountID[0]->id),
				'room_id'=>($data['roomID']),
				'rating'=>($data['rating']),
				'description'=>$data['review']),
				array( '%s', '%s', '%s', '%s' ))
        ){
            ?>
                <div class="alert alert-success">Review successfully submitted</div>
            <?php

            
        }
    }


    CJ_list_reviews($data['roomID']);

}


function CJ_list_reviews($roomID){
    global $wpdb;

    $qry = $wpdb->prepare('SELECT * FROM cj_review WHERE room_id = %s',$roomID);
    $review = $wpdb->get_results($qry);

    if($review[0] !== null){
        ?>
        <h4>List of Reviews</h4>

        <table style="width: 80%;">
            <col width="100">
            <col width="50">
            <tr>
                <th>Username</th>
                <th>Rating</th>
                <th>Review</th>
            </tr>
        <?php
        foreach($review as $oneReview){
            $qry2 = $wpdb->prepare('SELECT first_name FROM cj_account WHERE id = %s',$oneReview->account_id);
            $name = $wpdb->get_results($qry2);
            ?>
                    <tr>
                        <td><?php echo $name[0]->first_name ?></td>
                        <td><?php echo $oneReview->rating ?></td>
                        <td><?php echo $oneReview->description ?></td>
                    </tr>
            <?php
        }
        ?>
            </table>
        <?php
    }

}






?>