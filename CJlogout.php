<?php
	session_start();
	
	
	//simple variable debug function
	//usage: pr($avariable);
	if (!function_exists('pr')) {
	  function pr($var) { echo '<pre>Diagnostics: '; var_dump($var); echo '</pre>';}
	}	
	
	
	function CJ_logout_success(){
		echo '<h1>You have successfully logged out</h1>';
		echo '<a href="?page_id='.$page_id.'&cmd=login">Login</a>';
	}
	
	
	
	
?>