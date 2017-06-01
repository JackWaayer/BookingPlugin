<?php
//master CRUD selector
function WAD_admin2_CRUD() {

?>

  <h1>Users</H1>

  <h2>Add New User </h2>
  <form id = "addNewExtra">
    <p>First Name<p/><input type = "text" minlength = "2" maxlength = "25">
    <p>Last Name<p/><input type = "text" minlength = "2" maxlength = "25"></input>
    <p>Home Number<p/><input type = "number" min = "0" max = "999999999999"></input>
    <p>Mobile Number</p><input type = "number" min = "0" max = "999999999999"></input>
    <p>Email Address</p><input type = "email"></input>
    <p>Password</p><input type = "password"></input>
    <br><button> Confirm  </button>
    <button> Cancel </button>
  </form>


  <h2>Current Users</h2>
  <table>
    <tr>
      <th>First Name</th>
      <th>Last Name</th>
      <th>Home Number</th>
      <th>Mobile Number</th>
      <th>E-mail Address</th>
      <th>Password</th>
      <th>CRUD</th>
    </tr>
    <tr>
      <td></td>
      <td></td>
      <td></td>
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

WAD_admin2_CRUD();
?>