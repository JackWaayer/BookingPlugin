<?php
//master CRUD selector
function WAD_editor2_CRUD() {


  echo '<h1>Room Extras</H1>';
  echo '<h2>Current Extras</h2>';
  echo '<table>
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
        </table>';
  echo '<h2>Add New Extra </h2>';
  echo '<p>Extra Type<p/><input type = "text"></input>';
  echo '<p>Extra Description<p/><input type = "text"></input>';
  echo '<p>Extra Price<p/><input type = "text"></input><br>';
  echo '<button> Confirm  </button>';
  echo '<button> Cancel </button>';
}

WAD_editor2_CRUD();
?>