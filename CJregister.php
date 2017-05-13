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
	
	echo '<a href="?page_id='.$page_id.'&cmd=login"><button>Login</button></a>';
	
	if (isset($data['first_name'])){
		
		//Validate data in $data
		
		
		//insert user
		if	(	$wpdb->insert('wp_users',
						array(
						'user_login'=>($data['username']),
						'user_pass'=>($data['password']),
						'user_email'=>($data['email']),
						'user_status'=>1),
						array( '%s', '%s', '%s', '%s' )
						)
			)
		{
			$insertUser = true;
		}
		else
		{
			$insertUser = false;
		}
		
		
		//need the users ID to link the account
		$qry = $wpdb->prepare('SELECT * FROM wp_users WHERE user_login = %s',$data['username']);
		$user = $wpdb->get_results($qry);
		
		
		
		//insert account linked to user
		if	($wpdb->insert('cj_account',
				array(
				'first_name'=>($data['first_name']),
				'last_name'=>($data['last_name']),
				'home_number'=>($data['home_phone']),
				'mobile_number'=>($data['mobile_phone']),
				'user_id'=>$user[0]->ID),
				array( '%s', '%s', '%s', '%s', '%s' )
			)
		)
		{
			$insertAccount = true;
		}		
		else
		{
			$insertAccount = false;
		}
		
		
		//success or error for insert
		if($insertUser && $insertAccount)
		{
			$msg = '<h2>Your account has been successfully created!</h2>';
		}
		else
		{
			$msg = '<h2>Something went terribly wrong!</h2>';
		}
		
		return $msg;
		
		
		
	}
}




?>