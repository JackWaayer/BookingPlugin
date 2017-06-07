<?php

echo '<h1>Reviews</H1>';
//master CRUD selector
function CJ_review_content() {

?>

<h2>All Reviews</h2>
<?php
  global $wpdb, $page_id;
  $query = "SELECT * FROM cj_review";
  $allrecs = $wpdb->get_results($query);
?>
<table>
    <tr>
      <th>Rating</th>
      <th>Description</th>
      <th>First Name</th>
      <th>Last name</th>
      <th>Room Name</th>
      <th>CRUD</th>
    </tr>

<?php
  foreach ($allrecs as $rec) {
    $userID = $rec->account_id;
    $roomID = $rec->room_id;

    $qryUser = $wpdb->prepare('SELECT * FROM CJ_account WHERE id = %s', $userID);
    $user = $wpdb->get_results($qryUser);

    $qryRoom = $wpdb->prepare('SELECT * FROM CJ_room WHERE id = %s', $roomID);
    $room = $wpdb->get_results($qryRoom);
?>

    <tr>
      <td><?php echo $rec->rating?></td>
      <td><?php echo $rec->description?></td>
      <td><?php echo $user[0]->first_name?></td>
      <td><?php echo $user[0]->last_name?></td>      
      <td><?php echo $room[0]->room_name?></td>
      <td>
        <button>Edit</button>
        <button >Delete</button>
        <?php//<button id = "delButton" onclick = "CJ_delete_review($rec->id);">Delete</button>?>
      </td>
    </tr>

  <?php
  }
  ?>
  </table>

<?php

}
?>
 <!--$('.delButton').click(function() {

 $.ajax({
  type: "POST",
  data: { id: $rec->id }
}).done(function( msg ) {
  alert( "Data Saved: " + msg );
});    

    });-->

<?php

function CJ_review_form(){
  ?>
  <h2>Create New Review</h2>

<form id = "addNewReview" method="POST">
	<p>Room ID<input type = "number" name = "room_id" min = "1" max = "99999999999" required></input></p>
	<p>User ID<input type = "number" name = "account_id" min = "1" max = "99999999999" required></input></p>
	<p>Rating<input type = "number" name = "rating" min = "0" max = "5" required></input></p>
  <p>Description</p><textarea name = "description" row = "10"  cols="50" minlength = "10" max = "200" required></textarea><br>
  	<button type="submit"> Confirm </button>
  	<button> Cancel </button>
</form>
<?php
}


function CJ_delete_review($id){

	global $wpdb;

	$wpdb->query($wpdb->prepare("DELETE FROM cj_review WHERE id=%s",$reviewID));

	if ($results) {
		echo "<p>Delete Success!</p>";
	}else{
		echo "<p>Delete Failed!</p>";
  }
}

function CJ_add_Review($data){

  CJ_review_form();

  		
  global $wpdb;

// Server Vaildation 

// should have querry that gets the highest ID number of both room and account. And use these values for the upper number vaildation

    if($data['room_id'] < 99999999999 && 
    $data['room_id'] > 0 && 
    !empty($data['room_id']) && 
    isset($data['room_id']) ){ // room_id

      if($data['account_id'] < 99999999999 && 
      $data['account_id'] > 0 && 
      !empty($data['account_id']) && 
      isset($data['account_id']) ){ // account_id

        if($data['rating'] < 6 && 
        $data['rating'] > 0 && 
        !empty($data['rating']) && 
        isset($data['rating']) ){ // rating

          if( strlen($data['description']) > 0 && 
          strlen($data['description']) < 200 && 
          !empty($data['description']) && 
          isset($data['description'])){

            //Insert after all vaildation passes

              if ($wpdb->insert('cj_review', 
                      array(
                      'room_id'=>($data['room_id']),
                      'account_id'=>($data['account_id']),
                      'rating'=>($data['rating']),
                      'description'=>$data['description']),
                      array( '%s', '%s', '%s', '%s' )
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
}
?>