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
<ul>
    <li><a href="?page=admin_page_1&command=new" class="add-new-h2">Add New record</a></li>
    <li><a href="?page=admin_page_1&command=view" class="add-new-h2">View single</a></li>
    <li><a href="?page=admin_page_1&command=edit" class="add-new-h2">Edit single</a></li>
    <li><a href="?page=admin_page_1&command=delete" class="add-new-h2">Delete single</a></li>
    <li><a href="?page=admin_page_1&command=update" class="add-new-h2">Update after edit</a></li>
    <li><a href="?page=admin_page_1&command=insert" class="add-new-h2">Insert after new</a></li>
   
</ul> 

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
	echo  '<div id="msg" style="overflow: auto"></div>
		   <div class="wrap">
		   <h2>CJ 16. Multi file demo <a href="?page=admin_page_1&command=new" class="add-new-h2">Add New</a></h2>
		   <div style="clear: both"></div>';

// !!WARNING: there is no data validation conducted on the _REQUEST or _POST information. It is highly 
// recommend to parse ALL data/variables before using		
	if (isset($_POST) and !empty($_POST)) 
		$formdata = $_POST; 

	if (isset($_GET) and !empty($_GET)) 
		$getdata = $_GET; 

//current CRUD command		
	if (isset($getdata["command"]) and !empty($getdata["command"])) 
		$command = $getdata["command"]; 
	else 
		$command = '';


    switch ($command) {
        
	//operations access through the URL	
		case 'view':
			CJ_admin1_view($getdata);
		break;
		
		case 'edit':
			$msg = CJ_admin1_form('update', $getdata);
		break;

		case 'new':
		    //notice the null passed for the insert/new to the form. if the null was omitted the function 
			// will still use null as the default - refer to the CJ_admin1_form for more details
			$msg = CJ_admin1_form('insert',$getdata);
		break;
		
    //operations performing the various database tasks based on the previous CRUD command
		case 'delete':
			$msg = CJ_admin1_delete($getdata); 
			$command = '';
		break;

		case 'update':
			$msg = CJ_admin1_update($formdata);
			$command = '';
		break;

		case 'insert':	
			$msg = CJ_admin1_insert($formdata); 
			$command = '';
		break;
	}
	
//a simple catchall if the command is not found in the switch selector
	if (empty($command)) CJ_admin1_list();

//show any information messages	
	if (!empty($msg)) {
      echo '<p><a href="?page=admin_page_1"> back to the main page </a></p> Message: '.$msg;      
	}
	echo '</div>';
}

//========================================================================================
//view all the detail for a single record
function CJ_admin1_view($data) {
    //add in the retriev a single record code
}

function CJ_admin1_delete($data) {
   //refer to CJfaq8.php for the example code    
   return "Record deleted";
}

function CJ_admin1_update($data) {
   //refer to CJfaq8.php for the example code    
    return "Record updated";
}

function CJ_admin1_insert($data) {
   //refer to CJfaq8.php for the example code    
    return "Record saved";
}

function CJ_admin1_list() {
   //refer to CJfaq8.php for the example code
}

function CJ_admin1_form($command, $data) {
   //refer to CJfaq8.php for the example code    

    return $command." was executed for this form";
}

CJ_admin1_CRUD();
?>