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
		<input type="hidden" name="roomID" value= <?php echo $roomID ?>>
		<input type="hidden" name="type" value= <?php echo $type ?>>
		<input type="hidden" name="selectedMonth" value= <?php echo $selectedMonth ?>>
		<input type="hidden" name="selectedYear" value= <?php echo $selectedYear ?>>

		<button type="submit" name="submit" value="submit" style="margin-left: 40%;">Confirm Payment</button>
		<a href="?page_id='<?php echo $page_id ?>'&cmd=makeBooking"><button>Cancel</button></a>
		</form>
	<?php

}


function CJ_paymentInserts($data){
	foreach($data['days'] as $day){
        echo $day;
    }
}



function CJ_paymentSuccess(){
	
}

?> 