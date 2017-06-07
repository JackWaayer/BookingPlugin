<h1>Users</h1>
<?php
//master CRUD selector
function CJ_user_content() {

  global $wpdb, $page_id;
  $query = "SELECT * FROM cj_account";
  $allrecs = $wpdb->get_results($query);
  ?>

  <h2>Current Users</h2>
 
  <table>
    <tr>
      <th>First Name</th>
      <th>Last Name</th>
      <th>E-mail Address</th>
      <th>Password</th>
      <th>Mobile Number</th>
      <th>Home Number</th>
      <th>CRUD</th>
    </tr>

  <?php
  foreach ($allrecs as $rec){
    $userID = $rec->user_id;
    $qryUser = $wpdb->prepare('SELECT * FROM wp_users WHERE ID = %s', $userID);
    $user = $wpdb->get_results($qryUser);
  ?>

    <tr>
      <td><?php echo $rec->first_name?></td>
      <td><?php echo $rec->last_name ?></td>
      <td><?php echo $user[0]->user_email ?></td>
      <td><?php echo $user[0]->user_pass ?></td>
      <td><?php echo $rec->mobile_number ?></td>
      <td><?php echo $rec->home_number ?></td>
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
?>