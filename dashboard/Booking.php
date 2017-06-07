<?php
//master CRUD selector
function CJbookingContent() {
?>

  <h1>Booking and Reservations</h1>

  <?php CJ_make_booking(); ?>
  
  <h2>All Bookings/Reservations</h2>
<?php

global $wpdb, $page_id;
$query = "SELECT * FROM cj_booking";
$allrecs = $wpdb->get_results($query);
	
	?>
    	<hr />
		<table>
			<th>Room Name</th>
			<th>Room Description</th>
			<th>Dates Booked/Reserved</th>
			<th>type</th>
			<th>Extras</th>
			<th></th>
	<?php
    foreach ($allrecs as $rec) {
		$room = CJ_get_room($rec->room_id);
		$bookingID = $rec->id;
		
		$qry1 = $wpdb->prepare('SELECT * FROM cj_booking_extra WHERE booking_id = %s',$bookingID);
		$bookingExtra = $wpdb->get_results($qry1);
	?>
		<tr>
			<td><?php echo $room[0]->room_name ?></td>
			<td><?php echo $room[0]->description ?></td>
			<td><?php echo $rec->date_booked ?></td>
			<td><?php if($rec->type == "1"){echo "Reservation";}else if($rec->type == 0){echo "Booking";}  ?></td>
			<td><?php foreach($bookingExtra as $extra){
							$extraID = $extra->extra_id;
							$qry2 = $wpdb->prepare('SELECT * FROM cj_extra WHERE id = %s',$extraID);
							$oneExtra = $wpdb->get_results($qry2);
							foreach($oneExtra as $oe){
								echo $oe->extra_name;
							}; ?> <br />
					<?php
					}
					?>
			</td>
			<form method="POST" action="?page_id=<?php echo $page_id ?> &cmd=removeBookings">
			<td><input type='checkbox' name='deleteBooking[]' value=<?php echo $bookingID ?>></td>
		</tr>
	<?php	
    }
	?>
    	</table>
		<br />
		<button class='btn btn-danger' type='submit' style=''>Delete</button>
		</form>
	<?php

}

CJbookingContent();
?>