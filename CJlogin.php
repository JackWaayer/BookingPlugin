<?php
	ob_start();
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
                <input type="text" name="log" placeholder="Username"><br />
                <input type="password" name="pwd" placeholder="Password"><br />
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
	
    

    if(isset($data["log"])){
		

        $creds = array();
	    $creds['user_login'] = $data['log'];
        $creds['user_password'] = $data['pwd'];
        $creds['remember'] = true;
        $user = wp_signon( $creds, false );
        if ( is_wp_error($user) ){
            echo $user->get_error_message();
        }
        if ( !is_wp_error($user) ){
            header('Location:?page_id='.$page_id.'&cmd=myProfile');
        }
		
	
    }

    ob_end_clean;

    
    
}



?>