<?php


require_once __DIR__ . '/CJlogin.php';


add_shortcode('rooms', 'listRooms');
function listRooms(){
    global $page_id; //required to determine the currently active page
    global $wpdb;

    //parse any incoming actions or commands from our page - can be placed in it's own function
	if (isset($_GET['cmd']) && !empty($_GET['cmd'])) {
		$cmd = $_GET['cmd']; 
		$bid = $_GET['bid'];
		$data = $_POST;
        /*Diagnostics
		pr($data);*/
		switch ($cmd) {
			case "rooms":
				CJ_room_details();
				break;
			case "login":
				CJ_login($data);
				break;
			default:
				CJ_room_details(null); //catch random commands
		}
	} else CJ_room_details();	
}










?>