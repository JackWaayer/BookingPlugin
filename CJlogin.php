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
    global $page_id;


    echo   '<form action="" method="POST">
                <input type="text" name="username" placeholder="Username"><br />
                <input type="password" name="password" placeholder="Password"><br />
                <button type="submit">Sign In</button>
            </form>
            <br />
            <br />';


    echo '<a href="?page_id='.$page_id.'&cmd=register">Register</a>';
}



function CJ_login($data){
    global $wpdb;
	
	echo '<h2>'.$msg.'</h2>
	<br >
	<br >';
    echo '<h1>Login</h1>';
    CJ_login_form();
	
    

    if(isset($data["username"])){
		
		$username = $data["username"];
		$password = $data["password"];
		
		//Prepare Query
        $qry = $wpdb->prepare("SELECT * FROM wp_users WHERE user_login = %s",$username);
        
		//Use query to get the users record via the email given
        $rec = $wpdb->get_results($qry);
		
		//echo '<p>'.$data["username"].'</p>';
        //echo '<p>'.$rec[0]->user_pass.'</p>';
		
		if(!$password == '' && $password == $rec[0]->user_pass){
			$_SESSION['uid'] = $rec[0]->ID;
			$_SESSION['user_status'] = $rec[0]->user_status;
			header('Location:?page_id='.$page_id.'&cmd=myProfile');
		}
    }
    
    ob_end_clean;

    
    
}



?>