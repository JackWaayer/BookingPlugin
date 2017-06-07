<?php
//master CRUD selector
function CJ_extras_content() {

?>

<?php

  global $wpdb, $page_id;
  $query = "SELECT * FROM cj_extra";
  $allrecs = $wpdb->get_results($query);

?>
  <h2>Current Extras</h2>
  <table>
          <tr>
            <th>Name</th>
            <th>Price ($)</th>
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

function CJ_extras_form(){

  ?>
  <h1>Room Extras</H1>

  <h2>Add New Extra </h2>
  <form id = "addNewExtra" method="POST">
  <p>Extra Name<input type = "text" name = "extra_name" minlength = "5" maxlength = "40"></p>
  <p>Extra Price<input type = "number" step = "0.01" name = "price" min = "0" max = "99999"></input></p><br>
  <button type = "submit"> Confirm  </button>
  <button> Cancel </button>
  </form>
<?php
}


function CJ_add_extra($data){

  CJ_extras_form();

  	global $wpdb;

// Server Vaildation 

    if ( !empty($data['extra_name']) && 
    isset($data['extra_name']) && 
    strlen($data['extra_name']) > 0 &&
     strlen($data['extra_name']) < 40 ){

      if(!empty($data['price']) && 
        isset($data['price']) && 
        $data['price'] > 0 && 
        $data['price'] < 10000 ){

        //Insert after all vaildation passes

          if ($wpdb->insert('cj_extra', 
             array(
            'extra_name'=>($data['extra_name']),
            'price'=>$data['price']),
             array( '%s', '%s' )
             ))
          {
            echo "Insert successful";
                
          }else{
                  
            echo "Insert failed";
          }
      }
    }
  }
?>