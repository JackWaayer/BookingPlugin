<?php
//master CRUD selector
function CJextrasContent() {

?>

  <h1>Room Extras</H1>

  <h2>Add New Extra </h2>
  <form id = "addNewExtra">
  <p>Extra Name<input type = "text" minlength = "5" maxlength = "40"></p>
  <p>Extra Price<input type = "number" min = "0" max = "100000"></input></p><br>
  <button> Confirm  </button>
  <button> Cancel </button>
  </form>

<?php

  global $wpdb, $page_id;
  $query = "SELECT * FROM cj_extra";
  $allrecs = $wpdb->get_results($query);

?>
  <h2>Current Extras</h2>
  <table>
          <tr>
            <th>Name</th>
            <th>Price</th>
            <th>CRUD</th>
          </tr>
<?php
  foreach ($allrecs as $rec) {  
?>
          <tr>
            <td><?php echo $rec->extra_name ?></td>
            <td><?php echo $rec->price ?></td>
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
  CJextrasContent();
?>