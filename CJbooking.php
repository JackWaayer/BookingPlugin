<?php
session_start();


//simple variable debug function
//usage: pr($avariable);
if (!function_exists('pr')) {
  function pr($var) { echo '<pre>Diagnostics: '; var_dump($var); echo '</pre>';}
}




function CJ_list_bookings($accountID){
	
	global $wpdb, $page_id;

	if (current_user_can('manage_options')) {  //administrator capabilities
		$query = $wpdb->prepare("SELECT * FROM cj_booking");
	}  
    else if (current_user_can('read'))  { // subscriber capabilities
		$query = $wpdb->prepare("SELECT * FROM cj_booking WHERE account_id = %s",$accountID);
	}
    else if (!is_user_logged_in()) { //no capabilities or not logged in
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



function CJ_make_booking(){
	global $page_id;
	$rooms = CJ_get_all_rooms();
	
	echo '<h1>Make a booking</h1>';
	
	$buffer = 	'<form method="POST">
					<p>Please choose a room.</p>
					<select name="selectRooms">
						<option value="Single Room">Single Room</option>
						<option value="Executive Suite">Executive Suite</option>
					</select>
				</form>';
				
	echo $buffer;
	
	CJ_booking_calendar();
	
	
}



function CJ_booking_calendar(){
	
	date_default_timezone_set("Pacific/Auckland"); 
	//days of the week used for headings. This particular method is not particulary multilanguage friendly.
	$weekdays = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
	extract(shortcode_atts(array('year' => '-', 'month' => '-'), $shortcodeattributes));	

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
	echo '<main id="calendar"><div>'.$thedate.'</div><div class="th">';
	
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
		if (date("j") == $i+1)
		  echo "TODAY";
		echo '</div>';
	}
	
	//PART 3: last week with blank days (cells) or couple of days from next month
	$j = 1; //counter for next months days used to fill in the blank days at the end
  	for ($i=0; $i < $lastblankdays; $i++) 
		echo '<div data-date="'.$j++.'"></div>'; //!! this increments $j AFTER the value has been used
//close off the calendar	
	echo '</div></main>';
	
}




?>