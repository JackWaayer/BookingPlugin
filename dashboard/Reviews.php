<?php
//master CRUD selector
function CJ_review_content() {

?>

<h1>Reviews</H1>

<h2>Create New Review</h2>

<form id = "addNewReview">
	<p>Room ID<input type = "numer" name = "room_id" min = "1" max = "99999999999" default = "505"></input></p>
	<p>User ID<input type = "number" min = "0" max = "99999999999"></input></p>
	<p>Rating<input type = "number" min = "0" max = "5"</input></p>
  <p>Description</p><textarea row = "10" cols="50" minlength = "10" max = "200"></textarea><br>
  	<button> Confirm </button>
  	<button> Cancel </button>
</form>

<?php 
'room_id'->$data['first'];
echo $data?>




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
        <button>Delete</button>
      </td>
    </tr>

  <?php
  }
  ?>
  </table>

<?php

}

CJ_review_content();
?>