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



function CJ_login($data){
    global $wpdb;

    echo '<h2>Login</h2>';
    CJ_login_form();
    

    if(isset($data["email"])){
        echo '<p>'.$data["email"].'</p>';
        $query = 'SELECT * FROM wp_users WHERE user_email="'.$data["email"].'"';
        
        $rec = $wpdb->get_results($query);
        echo '<p>'.$rec[0]->user_pass.'</p>';
    }
    
    if($rec["user_pass"] == $data["password"]){
        echo '<p>YAY</p>';
    }



    
    
}



?>