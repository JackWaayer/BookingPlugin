<?php
/*========================================================================================
  This file is an example CRUD for our admin_page_1 - take note of the URLs
  ?page=admin_page_1&command=...

  You can duplicate the code in this file for each of the other files

  IMPORTANT Take note of the function CJ_admin1_CRUD and it is also called at the bottom of this page

*/
//master CRUD selector
function CJ_admin1_CRUD() {

?> 
<h1>Rooms</H1>

<h2>Add new room</h2>
<form id = "addNewRoom">
	<p>Room Name<p/><input type = "text" minlength = "5" maxlength = "40"></input>
	<p>Room Description<p/><input type = "text" min = "0" maxlength = "300"></input>
	<p>Room Price<p/><input type = "number" min = "0" max = "100000"</input><br>
  	<button> Confirm </button>
  	<button> Cancel </button>
</form>

<h2>Current Rooms</h2>

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
	CJ_list_rooms();
}

	CJ_admin1_CRUD();
?>