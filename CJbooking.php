<?php


//simple variable debug function
//usage: pr($avariable);
if (!function_exists('pr')) {
  function pr($var) { echo '<pre>Diagnostics: '; var_dump($var); echo '</pre>';}
}


wp_enqueue_style( 'bookingCalender', plugins_url('css/calendar.css',__FILE__));




function CJ_list_bookings($accountID){
	
	global $wpdb, $page_id;

	if (current_user_can('manage_options')) {  //administrator capabilities
		$query = "SELECT * FROM cj_booking";
	}  
    else if (current_user_can('read'))  { // subscriber capabilities
		$query = $wpdb->prepare("SELECT * FROM cj_booking WHERE account_id = %s",$accountID);
	}
    else if (!is_user_logged_in()) { //no capabilities or not logged in
		return;
	}
	
	
	$allrecs = $wpdb->get_results($query);
	
	if($allrecs[0] !== null){

		?>
		<p>This is a list of your current bookings/reservations</p>
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
			<button class='btn btn-danger' type='submit' style='float: right;'>Delete Selected</button>
			</form>
		<?php
	}else{
		?>
			<br />
			<h5>Go ahead, make a booking!</h5>
			<br />
			<br />
		<?php
	}
	
}


function CJ_removeBooking($data){
	global $wpdb;

	if($data['deleteBooking'] !== null){
		$results;
		foreach($data['deleteBooking'] as $del){
			$results = $wpdb->query($wpdb->prepare("DELETE FROM cj_booking WHERE id=%s",$del));
		}
		if ($results) {
				echo "<div class='alert alert-success'>Delete Success!</div>";
			}
	}
	else{
		?>
			<h5>No bookings were selected</h5>
		<?php
	}
}


function CJ_make_booking(){
	global $page_id;
	$rooms = CJ_get_all_rooms();
	
	
	?>

	<h1>Make a booking</h1>

				<form method="POST">
					<h5>Please choose a room and a month to view available bookings.</h5>

					<select name="selectRooms" required style="display: block; width: 20%; margin: 5px 0;">
						<option value="" selected disabled>Choose Room</option>
						<option value="1">Single Room</option>
						<option value="2">Executive Suite</option>
					</select>

					<select name="chosenMonth" required style="display: block; width: 20%; margin: 5px 0;">
						<option value="" selected disabled>Choose Month</option>
						<option value=1>January</option>
						<option value=2>February</option>
						<option value=3>March</option>
						<option value=4>April</option>
						<option value=5>May</option>
						<option value=6>June</option>
						<option value=7>July</option>
						<option value=8>August</option>
						<option value=9>September</option>
						<option value=10>October</option>
						<option value=11>November</option>
						<option value=12>December</option>
					</select>


					<select name="chosenYear" required style="display: block; width: 20%; margin: 5px 0;">
						<option selected value=2017>2017</option>
						<option value=2018>2018</option>
						<option value=2019>2019</option>
						<option value=2020>2020</option>
					</select>
					<br />

					<button type="Submit" class="btn btn-info">View Calendar</button>
				</form>
	
	<?php
}


function CJ_booking_calendar($data){
	global $wpdb;

	CJ_make_booking();
	
	if(isset($data["selectRooms"])){
		$roomID = $data["selectRooms"];

		$query2 = $wpdb->prepare("SELECT date_booked, type FROM cj_booking WHERE room_id = %s",$roomID);
		$bookings = $wpdb->get_results($query2);



		date_default_timezone_set("Pacific/Auckland"); 
		//days of the week used for headings. This particular method is not particulary multilanguage friendly.
		$weekdays = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
		extract(shortcode_atts(array('year' => $data['chosenYear'], 'month' => $data['chosenMonth']), $shortcodeattributes));	

		//default to the current month and year
		if ($month == '-') $month = date('m');	
		if ($year == '-') $year = date('Y');	

	//get the previous month's days - used to fill in the blank days at the start.	
		//make sure we roll over to december in the case of $month being January	
		if ($month == 1) //January?
		$prevmonth = 12; //December
		else 
		$prevmonth = $month; 
	//shortend, harder to read, version of the if ...else... above   
		$prevmonth = ($month == 1)?12:$month; 
		$prevdays = date('t',mktime(0,0,1,$prevmonth,1,$year));	//days in the previous month	
		
	//calculate a few date values for the current/selected month & year	
		$dow = date('w',mktime(0,0,1,$month,1,$year)); //day of the week
		$days = date('t',mktime(0,0,1,$month,1,$year));	//days in the month
		$lastblankdays = 7-(($dow+$days) % 7); //remaining days in the last week
		$lastblankdays = ($lastblankdays==7)?0:$lastblankdays;

	//calendar heading - note we are using flexbox for the styling
		$thedate = date('F Y',mktime(0,0,1,$month,1,$year));
		echo '<main id="calendar"><div><h3 style="text-align: center">'.$thedate.'</h3></div><div class="th">';
		
	//HEADING ROW: print out the week names	
		foreach ($weekdays as $wd) {
		echo '<span>'.$wd.'</span>';
		}		
		echo '</div>';
		
	//CALENDAR WEEKS: generate the calendar body
		//starting day of the previous month, used to fill the blank day slots
		$startday = $prevdays - ($dow-1); //calculate the number of days required from the prev month
		//PART 1: first week with initial blank days (cells) or previous month
		echo '<div class="week">';
		for ($i=0; $i < $dow; $i++) 
			//refer to lines 43-53 in the WADcalendar.css for information regarding the data-date styling
			echo '<div data-date="'.$startday++.'"></div>';//!! this increments $startday AFTER the value has been used
		
		//PART 2: main calendar calendar body
		for ($i=0; $i < $days; $i++) {
		
			//check for the week boundary - % returns the remainder of a division
			if (($i+$dow) % 7 == 0) { //no remainder means end of the week
			echo '</div><div class="week">';
			}
			
	//print the actual day (cell) with events
			echo '<div data-date="'.($i+1).'">'; //add 1 to the for loop variable as it starts at zero not one
			//..... insert your event code and such here
			$booked = false;

			?>
				<form method="POST" action="?page_id='.$page_id.'&cmd=confirm">
			<?php

			foreach($bookings as $b){
				$d = date_parse_from_format("Y-m-d", $b->date_booked);
				if($d["year"] == $year && $d["month"] == $month && $d["day"] == $i+1){
					if($b->type == 0){
						?>
							<p style="height: 5px; margin-left: 20px;">Booked</p>
						<?php
					}else if($b->type == 1){
						?>
							<input type="checkbox" name="reservedSelectedDays[]" value=<?php echo $i+1 ?> style="margin-left: 10px;"> Reserved
						<?php
					}
						
					$booked = true;
				}
			}

			if(!$booked){
				?>
					<input type="checkbox" name="selectedDays[]" value=<?php echo $i+1 ?> style="margin-left: 30px;">
				<?php
			}
				
			if (date("j") == $i+1 && $month == date('n'))
			echo "TODAY";
			echo '</div>';
		}
		
		//PART 3: last week with blank days (cells) or couple of days from next month
		$j = 1; //counter for next months days used to fill in the blank days at the end
		for ($i=0; $i < $lastblankdays; $i++) 
			echo '<div data-date="'.$j++.'"></div>'; //!! this increments $j AFTER the value has been used
	//close off the calendar	
		echo '</div></main>';

		?>
		<p style="font-size: 10px;">*You may only book a reserved room.</p>
		<label>Please choose whether to book or reserve the selected dates:</label>
		<br />
			<input type="radio" name="type" value=0 checked="checked"> Book
			<br />
			<input type="radio" name="type" value=1> Reserve
			<input type="text" name="month" value= <?php echo $month ?> style="visibility: hidden;" >
			<input type="text" name="year" value= <?php echo $year ?> style="visibility: hidden;" >
			<input type="text" name="roomID" value= <?php echo $roomID ?> style="visibility: hidden;" >

			<br />
			<br />

			<button type="submit" name="submit" value="submit" class="btn btn-primary">Place Booking/Reservation</button>
		</form>
		<?php
	}

	
	
}


function CJ_confirm_booking($data){
	global $wpdb;

	if(null!==($data['reservedSelectedDays']) && $data['type'] == 1){
		?>
		<div class='alert alert-danger'>
			<h4>You may only book a reserved room</h4>
		</div>
		<?php
	}
	else if(null == ($data['reservedSelectedDays']) && null == ($data['selectedDays'])){
		?>
		<div class='alert alert-danger'>
			<h4>Please select at least one date to book/reserve.</h4>
		</div>
		<?php
	}
	else{

	

		$type = $data["type"];
		$selectedDays = $data["selectedDays"];
		$selectedMonth = $data["month"];
		$selectedYear = $data["year"];
		$rSelections = $data["reservedSelectedDays"];
		$roomID = $data['roomID'];

		$qry = 'SELECT * FROM cj_extra';
		$extras = $wpdb->get_results($qry);

		$qry2 = $wpdb->prepare('SELECT room_name FROM cj_room WHERE id = %s',$roomID);
		$roomName = $wpdb->get_results($qry2);

		echo '<h2>Add Extras</h2>';

		if($type[0] == 0){
			echo '<h5>Please confirm your booking dates and select any extras you wish to add</h5>';
		}else{
			echo '<h5>Please confirm your reservation dates and select any extras you wish to add</h5>';
		}

		echo '<ul>';
		if(null !== $selectedDays){
			foreach($selectedDays as $s){
				echo '<li style="height: 5px;">Book the '.$roomName[0]->room_name.' on the '.$s.'/'.$selectedMonth.'/'.$selectedYear.'</li><br />';
			}
		}
		if(null !== $rSelections){
			foreach($rSelections as $rs){
				echo '<li style="height: 5px;">Reserve the '.$roomName[0]->room_name.' on the '.$rs.'/'.$selectedMonth.'/'.$selectedYear.'</li><br />';
			}
		}
		echo '</ul>';

		echo '<br />';
		echo '<h5>Select the extras you wish to add to your bookings/reservations:</h5>';

		echo '<form method="POST" action="?page_id='.$page_id.'&cmd=payment">';
		foreach($extras as $ex){
			echo '<input type="checkbox" name="chosenExtras[]" value='.$ex->id.'> 
			<label style="display:inline-block; width: 20%">'.$ex->extra_name.'</label>   
			<label style="display:inline-block; width: 20%">$'.$ex->price.'</label><br />';
		}

		if(null !== $selectedDays){
			foreach($selectedDays as $s){
				?>
					<input type="hidden" name="days[]" value= <?php echo $s ?>>
				<?php
			}
		}
		if(null !== $rSelections){
			foreach($rSelections as $rs){
				?>
					<input type="hidden" name="days[]" value= <?php echo $rs ?>>
				<?php
			}
		}

		?>
			<input type="hidden" name="roomID" value= <?php echo $roomID ?>>
			<input type="hidden" name="type" value= <?php echo $type ?>>
			<input type="hidden" name="selectedMonth" value= <?php echo $selectedMonth ?>>
			<input type="hidden" name="selectedYear" value= <?php echo $selectedYear ?>>

			<button type="submit" name="submit" value="submit" style="margin-left: 40%;" class="btn btn-primary">Continue</button>
			</form>
		<?php
	}
}




?>