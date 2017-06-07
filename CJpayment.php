<?php 

function CJ_payment($data){
	global $wpdb, $page_id;

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

		<?php
		if(null !== $data['chosenExtras']){
		?>
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
		<?php
		}
		?>
		<br />
		<br />

		<h3>Total: $<?php echo $total ?></h3>

        <form method="POST" action="?page_id='.$page_id.'&cmd=paymentInserts">
	<?php
	

	
    foreach($data['days'] as $day){
	?>
		<input type="hidden" name="days[]" value= <?php echo $day ?>>
	<?php
    }

    if(null !== $data['chosenExtras']){
        foreach($data['chosenExtras'] as $ce){
        ?>
            <input type="hidden" name="chosenExtras[]" value= <?php echo $ce ?>>
        <?php
        }
    }
    
    ?>
		<input type="hidden" name="roomID" value= <?php echo $data['roomID'] ?>>
		<input type="hidden" name="type" value= <?php echo $data['type'] ?>>
		<input type="hidden" name="selectedMonth" value= <?php echo $data['selectedMonth'] ?>>
		<input type="hidden" name="selectedYear" value= <?php echo $data['selectedYear'] ?>>

		<button type="submit" name="submit" value="submit" style="margin-left: 40%;" class="btn btn-primary">Confirm Payment</button>
		</form>
	<?php

}


function CJ_paymentInserts($data){
    global $wpdb;
    $success = true;
    


	foreach($data['days'] as $day){
        
        $formattedDate = $data['selectedYear'].'/'.$data['selectedMonth'].'/'.$day;

		$qry3 = $wpdb->prepare('SELECT id FROM cj_booking WHERE date_booked = %s',$formattedDate);
		$oldBooking = $wpdb->get_results($qry3);

		if($oldBooking[0]->id !== null){
			$results = $wpdb->query($wpdb->prepare("DELETE FROM cj_booking WHERE id=%s",$oldBooking[0]->id));
			//Confirms deletion
			/*if ($results) {
				echo "<div class='alert alert-success'>Delete Success!</div>";
			}*/
		}

        $uid = get_current_user_id();

        $qry1 = $wpdb->prepare('SELECT * FROM cj_account WHERE user_id = %s',$uid);
        $account = $wpdb->get_results($qry1);

        if(!
        $wpdb->insert('cj_booking',
				array(
				'account_id'=>($account[0]->id),
				'room_id'=>($data['roomID']),
				'date_booked'=>($formattedDate),
				'type'=>($data['type']),
				'status'=>0),
				array( '%s', '%s', '%s', '%s', '%s' ))
        ){
            $success = false;
        }

        $qry2 = $wpdb->prepare('SELECT * FROM cj_booking WHERE date_booked = %s',$formattedDate);
        $booking = $wpdb->get_results($qry2);

        if(null !== $data['chosenExtras']){
            foreach($data['chosenExtras'] as $extra){
                if(!
                    $wpdb->insert('cj_booking_extra',
                            array(
                            'booking_id'=>($booking[0]->id),
                            'extra_id'=>$extra),
                            array( '%s', '%s' ))
                ){
                    $success = false;
                }
            }
        }

    }

    if($success){
        ?>
            <div class="alert alert-success">Payment Successful!</div>
        <?php
    }else{
        ?>
            <div class="alert alert-danger">Something went terribly wrong.</div>
        <?php
    }
}


?> 