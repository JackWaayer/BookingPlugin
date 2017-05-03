<?php
	ob_start();
	session_start();
?>

<?php

//simple variable debug function
//usage: pr($avariable);
if (!function_exists('pr')) {
  function pr($var) { echo '<pre>Diagnostics: '; var_dump($var); echo '</pre>';}
}


function CJ_login_form(){
    global $wpdb, $page_id;

    $credentials = '';

    echo   '<form action="" method="POST">
                <input type="text" name="email" placeholder="Email"><br />
                <input type="password" name="password" placeholder="Password"><br />
                <button type="submit">Sign In</button>
            </form>
            <br />
            <br />';


    echo '<a href="?page_id='.$page_id.'&cmd=rooms">Back to Room types</a>';
}



function CJ_login($data, $msg){
    global $wpdb;
	
	echo '<h2>'.$msg.'</h2>
	<br >
	<br >';
    echo '<h1>Login</h1>';
    CJ_login_form();
	
    

    if(isset($data["email"])){
		
		$email = $data["email"];
		$password = $data["password"];
		
		//Prepare Query
        $qry = $wpdb->prepare("SELECT * FROM wp_users WHERE user_login = %s",$email);
        
		//Use query to get the users record via the email given
        $rec = $wpdb->get_results($qry);
		
		echo '<p>'.$data["email"].'</p>';
        echo '<p>'.$rec[0]->user_pass.'</p>';
		
		if(!$password == '' && $password == $rec[0]->user_pass){
			$_SESSION['uid'] = $rec[0]->ID;
			$_SESSION['name'] = $rec[0]->user_login;
			$_SESSION['status'] = $rec[0]->user_status;
			header('Location:?page_id='.$page_id.'&cmd=bookings');
		}
    }
    
    ob_end_clean;

    
    
}



?>