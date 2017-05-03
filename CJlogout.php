<?php
	session_start();
	
	
	//simple variable debug function
	//usage: pr($avariable);
	if (!function_exists('pr')) {
	  function pr($var) { echo '<pre>Diagnostics: '; var_dump($var); echo '</pre>';}
	}
	
	function CJ_logout(){
		session_destroy();
		header('Location:?page_id='.$page_id.'&cmd=login&msg="You have successfully logged out"');
	}
	
	
	
	
	
?>