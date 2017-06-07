<?php
//master CRUD selector
function CJreviewContent() {

?>

<h1>Reviews</H1>

<h2></h2>
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
    $userID = $rec->user_id;
    $roomID = $rec->room_id;

    $qryUser = $wpdb->prepare('SELECT * FROM wp_review WHERE user_id = %s', $userID);
    $user = $wpdb->get_results($qryUser);

    $qryRoom = $wpdb->prepare('SELECT * FROM wp_review WHERE room_id = %s', $roomID);
    $room = $wpdb->get_results($qryRoom);
?>

    <tr>
      <td><?php echo $rec->rating?></td>
      <td><?php echo $rec->description?></td>
      <td><?php echo $user->first_name?></td>
      <td><?php echo $user->last_name?></td>      
      <td><?php echo $room->room_name?></td>
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

CJreviewContent();
?>