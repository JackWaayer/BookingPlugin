<h1>Rooms</H1> 
<?php
function CJ_room_content() {

	CJ_list_rooms();

}


function CJ_room_form (){
?>
	<h2>Add new room</h2>
<form id = "addNewRoom" method="POST">
	<p>Room Name<input type = "text" name = "room_name" minlength = "5" maxlength = "40" required></input></p>
	<p>Room Price<input type = "number" name = "price" step = "0.01" min = "1" max = "999.99" required></input></p>
	<p>Room Description</p><textarea = "text" name = "description" row = "10"  cols="50" min = "0" maxlength = "300" required></textarea><br>
  	<button> Confirm </button>
  	<button> Cancel </button>
</form>
<?php
}

/*function CJ_room_edit($roomID){

	global $wpdb
	$query = $wpdb->prepare("SELECT * FROM cj_room WHERE id = %s", $roomID);
	$allrecs = $wpdb->get_results($query);

?>
	<form id = "editRoom">
		<p>Room Name<input type = "text" name = "edit_name" minlength = "5" maxlength = "40"></input></p>
		<p>Room Price<input type = "number" name = "edit_price" step = "0.01" min = "1" max = "999.99"</input></p>
		<p>Room Description</p><textarea = "text" name = "edit_description" row = "10"  cols="50" min = "0" maxlength = "300"></textarea><br>
  		<button onclick = "<?php  ?>"> Confirm </button>
	</form>

<?php

	$wpdb->update('CJ_room',
		  array( 'id' => (['id']),
				 'room_name' => (['edit_name']),
				 'price' => (['edit_price']),
				 'description' => ['edit_description']),
		  array( 'id' => 'id']));


}*/

function CJ_room_delete($roomID){

	global $wpdb;

	$results = $wpdb->query($wpdb->prepare("DELETE FROM cj_room WHERE id=%s",$roomID));

	if ($results) {
		echo "<p>Delete Success!</p>";
	}else{
		echo "<p>Delete Failed!</p>";
	}

}


function CJ_add_room ($data) {

	CJ_room_form();

  	global $wpdb;

	
	if ( !empty($data['room_name']) && 
    isset($data['room_name']) && 
    strlen($data['room_name']) > 0 &&
     strlen($data['room_name']) < 40 ){

		if ( !empty($data['description']) && 
    		isset($data['description']) && 
    		strlen($data['description']) > 0 &&
     		strlen($data['description']) < 300 ){

				 if ( !empty($data['price']) && 
    				isset($data['price']) && 
    				 $data['price'] > 0 &&
    				 $data['price'] < 1000 ){

			//Insert after all vaildation passes

					if ($wpdb->insert('cj_room', 
						array(
						'room_name'=>($data['room_name']),
						'description'=>($data['description']),
						'price'=>$data['price']),
						array( '%s', '%s', '%s' )
						))
					{
						echo "Insert successful";
							
					}else{
							
						echo "Insert failed";
					}
				}
		}
	}
}
?>