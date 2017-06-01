<?php
//master CRUD selector
function WAD_editor2_CRUD() {

?>

  <h2>Add New Extra </h2>
  <form id = "addNewExtra">
  <p>Extra Type<p/><input type = "text" minlength = "5" maxlength = "40">
  <p>Extra Description<p/><input type = "text" min = "0" maxlength = "300"></input>
  <p>Extra Price<p/><input type = "number" min = "0" max = "100000"></input><br>
  <button> Confirm  </button>
  <button> Cancel </button>
  </form>

  <h1>Room Extras</H1>
  <h2>Current Extras</h2>
  <table>
          <tr>
            <th>Name</th>
            <th>Description</th/>
            <th>Price</th>
            <th>CRUD</th>
          </tr>
          <tr>
            <td></td>
            <td></td>
            <td></td>
            <td>
              <button>Edit</button>
              <button>Delete</button>
            </td>
          </tr>
        </table>


  <?php
}

WAD_editor2_CRUD();
?>