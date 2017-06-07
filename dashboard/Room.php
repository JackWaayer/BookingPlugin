<?php

function CJ_room_content() {

?> 
<h1>Rooms</H1>

<h2>Add new room</h2>
<form id = "addNewRoom">
	<p>Room Name<input type = "text" minlength = "5" maxlength = "40"></input></p>
	<p>Room Description<input type = "text" min = "0" maxlength = "300"></input></p>
	<p>Room Price<input type = "number" min = "0" max = "100000"</input></p><br>
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



function  (){

}


function  (){

}



?>