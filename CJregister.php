<?php

//simple variable debug function
//usage: pr($avariable);
if (!function_exists('pr')) {
  function pr($var) { echo '<pre>Diagnostics: '; var_dump($var); echo '</pre>';}
}



function CJ_register_form(){
	global $page_id;
	
	echo   '<form action="" method="POST">
				Username<input type="text" name="username" placeholder="e.g. George"><br />
				Password<input type="password" name="password" placeholder="******"><br />
                First Name<input type="text" name="first_name" placeholder=""><br />
                Last Name<input type="text" name="last_name" placeholder=""><br />
				Home Phone<input type="text" name="home_phone" placeholder=""><br />
				Mobile Phone<input type="text" name="mobile_phone" placeholder=""><br />
				Email<input type="text" name="email" placeholder="example@mail.com"><br />
                <button type="submit">Sign Up</button>
            </form>';
			
	
}





function CJ_register($data){
	global $wpdb;
	
	echo '<h1>Register</h1>';
	
	CJ_register_form();
	
	if (isset($data['first_name'])){
		$wpdb->insert('wp_users',
						array(
						'user_login'=>($data['username']),
						'user_pass'=>($data['password']),
						'user_email'=>($data['email']),
						'user_status'=>1),
						array( '%s', '%s', '%s', '%s' )
						);
		
		$wpdb->insert('cj_account',
						array(
						'first_name'=>($data['first_name']),
						'last_name'=>($data['last_name']),
						'home_number'=>($data['home_phone']),
						'mobile_number'=>$data['mobile_phone']),
						array( '%s', '%s', '%s', '%s' )
						);
	}
}




?>