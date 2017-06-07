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

    ?>
    <form action="" method="POST">
        <input type="text" name="log" placeholder="Username" style="margin: 5px 0;">
        <br />
        <input type="password" name="pwd" placeholder="Password" style="margin: 5px 0;">
        <br />
        <button type="submit" style="margin: 10px 0;" class="btn btn-info">Sign In</button>
    </form>
    <br />
    <br />
    <?php
}



function CJ_login($data){
    global $wpdb;
	
    ?>
        <h1>Login</h1>
    <?php
    
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