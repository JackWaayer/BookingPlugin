<?php
session_start();


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

$setDate = false;

function CJ_make_booking(){
	global $page_id;
	$rooms = CJ_get_all_rooms();
	
	
	?>

	<h1>Make a booking</h1>

				<form method="POST">
					<p>Please choose a room and a month to view available bookings.</p>

					<select name="selectRooms">
						<option selected disabled>Choose Room</option>
						<option value="1">Single Room</option>
						<option value="2">Executive Suite</option>
					</select>
					<br />

					<select name="chosenMonth">
						<option selected disabled>Choose Month</option>
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
					<br />

					<select name="chosenYear">
						<option selected value=2017>2017</option>
						<option value=2018>2018</option>
						<option value=2019>2019</option>
						<option value=2020>2020</option>
					</select>
					<br />

					<button type="Submit">View Bookings</button>
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
		extract(shortcode_atts(array('year' => '-', 'month' => $data['chosenMonth']), $shortcodeattributes));	

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
				if($d["month"] == $month && $d["day"] == $i+1){
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
			<input type="radio" name="type" value=0> Book
			<br />
			<input type="radio" name="type" value=1> Reserve
			<input type="text" name="month" value= <?php echo $month ?> style="visibility: hidden;" >
			<input type="text" name="year" value= <?php echo $year ?> style="visibility: hidden;" >
			<input type="text" name="roomID" value= <?php echo $roomID ?> style="visibility: hidden;" >

			<br />
			<br />

			<button type="submit" name="submit" value="submit">Place Booking/Reservation</button>
		</form>
		<?php
	}

	
	
}


function CJ_confirm_booking($data){
	global $wpdb;

	$type = $data["type"];
	$selectedDays = $data["selectedDays"];
	$selectedMonth = $data["month"];
	$selectedYear = $data["year"];
	$rSelections = $data["reservedSelectedDays"];
	$roomID = $data['roomID'];

	$qry = 'SELECT * FROM cj_extra';
	$extras = $wpdb->get_results($qry);

	echo '<h2>Add Extras</h2>';

	if($type[0] == 0){
		echo '<h5>Please confirm your booking dates and select any extras you wish to add</h5>';
	}else{
		echo '<h5>Please confirm your reservation dates and select any extras you wish to add</h5>';
	}

	echo '<ul>';
	foreach($selectedDays as $s){
		echo '<li style="height: 5px;">'.$room.' '.$s.'/'.$selectedMonth.'/'.$selectedYear.'</li><br />';
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

	foreach($selectedDays as $s){
		?>
			<input type="hidden" name="days[]" value= <?php echo $s ?>>
		<?php
	}

	?>
		<input type="hidden" name="roomID" value= <?php echo $roomID ?>>
		<input type="hidden" name="type" value= <?php echo $type ?>>
		<input type="hidden" name="selectedMonth" value= <?php echo $selectedMonth ?>>
		<input type="hidden" name="selectedYear" value= <?php echo $selectedYear ?>>
		<input type="hidden" name="rSelections" value= <?php echo $rSelections ?>>

		<button type="submit" name="submit" value="submit" style="margin-left: 40%;">Continue</button>
		<a href="?page_id='<?php echo $page_id ?>'&cmd=makeBooking"><button>Cancel</button></a>
		</form>
	<?php
}


function CJ_payment($data){
	global $wpdb;

	$qry = $wpdb->prepare('SELECT * FROM cj_room WHERE id = %s',$data['roomID']);
	$oneRoom = $wpdb->get_results($qry);
	$total =0.00;

	?>
		<h1>Payment</h1>
		<table>
		<tr>
			<th>Room</th>
			<th>Date</th>
			<th>Price</th>
		</tr>
		<?php
		foreach($data["days"] as $key => $value){
			$total = $total + $oneRoom[0]->price;
			?>
				<tr>
					<td><?php echo $oneRoom[0]->room_name ?></td>
					<td><?php echo $value.'/'.$data['selectedMonth'].'/'.$data['selectedYear'] ?></td>
					<td><?php echo $oneRoom[0]->price ?></td>
				</tr>
			<?php
		}
	?>
		</table>	

		<br />
		<br />

		<table>
		<tr>
			<th>Extra</th>
			<th>Price</th>
		</tr>
		<?php

		foreach($data["chosenExtras"] as $value){
			$query = $wpdb->prepare('SELECT * FROM cj_extra WHERE id = %s',$value);
			$extra = $wpdb->get_results($query);
			$total = $total + $extra[0]->price;
			?>
				<tr>
					<td><?php echo $extra[0]->extra_name ?></td>
					<td><?php echo $extra[0]->price ?></td>
				</tr>
			<?php
		}
	?>
		</table>

		<br />
		<br />

		<h3>Total: $<?php echo $total ?></h3>
	<?php
	

	

	?>
		<button action=<?php foreach($data["days"] as $key => $value){
							$formatedDate = $data['selectedYear'].'/'.$data['selectedMonth'].'/'.$value;
							$currentUser = get_current_user_id();

							$query = $wpdb->prepare('SELECT * FROM cj_account WHERE user_id = %s',$currentUser);
							$account = $wpdb->get_results($query);

							$wpdb->insert('cj_booking',
								array(
									'account_id'=>($account[0]->id),
									'room_id'=>($data['roomID']),
									'date_booked'=>($formatedDate),
									'type'=>($data['type']),
									'status'=>0),
								array( '%s', '%s', '%s', '%s', '%s'));
							
							$qry = $wpdb->prepare('SELECT * FROM cj_booking WHERE date_booked = %s',$formatedDate);
							$bookingID = $wpdb->get_results($qry);

							foreach($data['chosenExtras'] as $value){
								$wpdb->insert('cj_booking_extra',
									array(
										'booking_id'=>($bookingID[0]->id),
										'extra_id'=>$value),
									array( '%s', '%s'));
							}
						} 	
					?> 
		>Pay</button>
	<?php

	/*$wpdb->insert('cj_booking',
		array(
			'account_id'->($oneRoom[0]->id),
			'room_id'->($data),
			'date_booked'->(),
			'type'->(),
			'status'->()
		))*/
}


?>