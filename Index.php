<?php
	/*
	Plugin Name: Booking Plugin
	Plugin URI: http://localhost/
	Description: Booking plugin for accomodation reservations
	Author: Jack Waayer, Cameron Anderson
	Version: 1.1.1
	Author URI: http://
	Last update: 12 April 2017
	*/

	/* DEBUGGING NOTE
	 * Change the line 81 of the wp-config.php file in the Wordpress root folder
	 * from 	define('WP_DEBUG', false);
	 * to		define('WP_DEBUG', true);
	 * This will enable the debugging and any error messages.
	*/
	/* CHANGELOG
		12APR2017 - Initial release.
	*/



	require_once __DIR__ . '/CJadmin.php'; 
	require_once __DIR__ . '/CJcrud.php'; 
	require_once __DIR__ . '/CJrooms.php'; 
	require_once __DIR__ . '/CJbooking.php';
	



	
	
	//	simple variable debug function
	//	usage: pr($avariable);
	if (!function_exists('pr')) {
	  function pr($var) { echo '<pre>'; var_dump($var); echo '</pre>';}
	}
	
	

	/*	Runs when the template calls the wp_head() function. 
		This hook is generally placed near the top of a page template between <head> and </head>. 
		This hook does not take any parameters. */
	//	http://codex.wordpress.org/Plugin_API/Action_Reference/wp_head
	add_action('wp_head','headerHook');
	function headerHook() {
		//Client specific header details e.g.(js / css scripts)
	}


	

	add_shortcode('shortcode', 'myShortcode');
	function myShortcode(){
		?>
			<p>Look it's shortcode!</p>
		<?php
	}



	/* 	applied to the post content retrieved from the database, prior to printing on the screen (also used in some other operations, such as trackbacks).
		try adding any of the bad words below into a post */
	//	add the filter hook  onto the post content
	add_filter( 'the_content', "filterContent");
	function filterContent($post_content) {
		//	array of some bad words
		$badlist= array("badword", "crap", "poop");
		//	replace all occurances of the badwords in the content of a post
		$newpost = str_replace($badlist, "****", $post_content);
		return $newpost;
	}





?>