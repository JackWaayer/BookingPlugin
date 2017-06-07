<?php
//master CRUD selector
function CJ_booking_content() {
?>

  <h1>Booking and Reservations</h1>

  <?php CJ_make_booking(); ?>
  
  <h2>All Bookings/Reservations</h2>
	
	<?php CJ_list_bookings(0);
}

CJ_booking_content();
?>