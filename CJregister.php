<?php

//simple variable debug function
//usage: pr($avariable);
if (!function_exists('pr')) {
  function pr($var) { echo '<pre>Diagnostics: '; var_dump($var); echo '</pre>';}
}



function CJ_register_form(){
	global $page_id;
	
	?>
	<form action="" method="POST">
		<label style="display:inline-block; width: 25%">Username</label><input required type="text" name="username"><br />
		<label style="display:inline-block; width: 25%">Password</label><input required type="password" name="password" placeholder="******"><br />
		<label style="display:inline-block; width: 25%">Confirm Password</label><input required type="password" name="confirmPassword" placeholder="******"><br />
		<label style="display:inline-block; width: 25%">First Name</label><input required type="text" name="first_name" placeholder=""><br />
		<label style="display:inline-block; width: 25%">Last Name</label><input required type="text" name="last_name" placeholder=""><br />
		<label style="display:inline-block; width: 25%">Home Phone</label><input required type="text" name="home_phone" placeholder="060000000"><br />
		<label style="display:inline-block; width: 25%">Mobile Phone</label><input required type="text" name="mobile_phone" placeholder="0270000000"><br />
		<label style="display:inline-block; width: 25%">Email</label><input required type="text" name="email" placeholder="example@mail.com"><br /><br />
		<button type="submit" class="btn btn-info">Sign Up</button>
    </form>
	<br />
	<?php		
	
}





function CJ_register($data){
	global $wpdb;
	
	echo '<h1>Register</h1>';
	
	CJ_register_form();
	
	if (isset($data['first_name'])){
		
		//Validate data in $data
		if($data['password'] == $data['confirmPassword']){

			//insert user
			if	(	wp_create_user(
						$data['username'],
						$data['password'],
						$data['email']
					)
				)
			{
				$insertUser = true;
			}
			else
			{
				$insertUser = false;
			}
			
			
			//get the users ID to link the account
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
				$msg = '<div class="alert alert-success">Your account has been successfully created!</div>';
			}
			else
			{
				$msg = '<div class="alert alert-danger">Something went terribly wrong!</div>';
			}
		}
		else{
			$msg = '<div class="alert alert-danger">The confirm password you entered did not match</div>';
		}
		
		
		
		return $msg;
		
		
		
	}
}




?>