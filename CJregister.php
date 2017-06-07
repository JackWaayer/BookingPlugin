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
		<label style="display:inline-block; width: 25%">Username</label><input required maxlength="15" type="text" name="username"> *<br />
		<label style="display:inline-block; width: 25%">Password</label><input required maxlength="15" type="password" name="password" placeholder="******"> *<br />
		<label style="display:inline-block; width: 25%">Confirm Password</label><input required maxlength="15" type="password" name="confirmPassword" placeholder="******"> *<br />
		<label style="display:inline-block; width: 25%">First Name</label><input required maxlength="20" type="text" name="first_name" placeholder=""> *<br />
		<label style="display:inline-block; width: 25%">Last Name</label><input required maxlength="20" type="text" name="last_name" placeholder=""> *<br />
		<label style="display:inline-block; width: 25%">Home Phone</label><input required maxlength="11" type="text" name="home_phone" placeholder="060000000"> *<br />
		<label style="display:inline-block; width: 25%">Mobile Phone</label><input required maxlength="11" type="text" name="mobile_phone" placeholder="0270000000"> *<br />
		<label style="display:inline-block; width: 25%">Email</label><input required maxlength="30" type="text" name="email" placeholder="example@mail.com"> *<br /><br />
		<p>* required fields</p>
		<button type="submit" class="btn btn-info">Sign Up</button>
    </form>
	<br />
	<?php		
	
}





function CJ_register($data){
	global $wpdb;
	$noErr = true;
	
	echo '<h1>Register</h1>';
	
	CJ_register_form();
	
	if (isset($data['first_name'])){
		
		//Validate data in $data
		if($data['password'] !== $data['confirmPassword'] || strlen($data['password']) < 6){
			echo '<div class="alert alert-danger">The confirm password you entered did not match or was less than 6 characters</div>';
			$noErr = false;
		}
		if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
			echo '<div class="alert alert-danger">Invalid email</div>'; 
			$noErr = false;
		}
		if (!preg_match("/^[a-zA-Z ]*$/",$data['first_name'])) {
			echo '<div class="alert alert-danger">First name can only contain letters</div>'; 
			$noErr = false;
		}
		if (!preg_match("/^[a-zA-Z ]*$/",$data['last_name'])) {
			echo '<div class="alert alert-danger">Last name can only contain letters</div>'; 
			$noErr = false;
		}
		if (!preg_match("/^[a-zA-Z 0-9]*$/",$data['username'])) {
			echo '<div class="alert alert-danger">username can only contain letters and numbers</div>'; 
			$noErr = false;
		}
		if (!preg_match("/^[0-9]*$/",$data['home_phone'])) {
			echo '<div class="alert alert-danger">Home phone can only contain numbers</div>'; 
			$noErr = false;
		}
		if (!preg_match("/^[0-9]*$/",$data['mobile_phone'])) {
			echo '<div class="alert alert-danger">Mobile phone can only contain numbers</div>'; 
			$noErr = false;
		}
		

		if($noErr){

			$username = test_input($data['username']);
			$password = test_input($data['password']);
			$email = test_input($data['email']);

			//insert user
			if	(	wp_create_user(
						$username,
						$password,
						$email
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

			$firstName = test_input($data['first_name']);
			$lastName = test_input($data['last_name']);
			$homePhone = test_input($data['home_phone']);
			$mobPhone = test_input($data['mobile_phone']);
			
			
			//insert account linked to user
			if	($wpdb->insert('cj_account',
					array(
					'first_name'=>($firstName),
					'last_name'=>($lastName),
					'home_number'=>($homePhone),
					'mobile_number'=>($mobPhone),
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
		
		return $msg;
		
	}
}

//Cleanse the data
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}


?>