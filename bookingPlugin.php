<?php
/*
Plugin Name: Booking Plugin
Plugin URI: http://localhost/
Description: Booking plugin for accomodation reservations
Author: Jack Waayer, Cameron
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



///////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////
//Dashboard / Menus
///////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////

//	simple variable debug function
//	usage: pr($avariable);
if (!function_exists('pr')) {
  function pr($var) { echo '<pre>'; var_dump($var); echo '</pre>';}
}


//----------------------------------------------------------------------------- 
//	http://codex.wordpress.org/Plugin_API/Action_Reference/admin_menu
//	add_action( $hook, $function_to_add, $priority, $accepted_args );
add_action('admin_menu', 'adminBookingMenuHook');
function adminBookingMenuHook() {
	//	http://codex.wordpress.org/Function_Reference/add_options_page
	//	add_options_page($page_title, $menu_title, $capability, $menu_slug, $function);
	add_options_page('Booking Menu Page', 'Booking Settings', 'manage_options', 'bookingMenu', 'adminBookingSettingsPage');
}


//-------------------------------------------------------------------------
/*	Runs when the template calls the wp_head() function. 
	This hook is generally placed near the top of a page template between <head> and </head>. 
	This hook does not take any parameters. */
//	http://codex.wordpress.org/Plugin_API/Action_Reference/wp_head
add_action('wp_head','headerHook');
function headerHook() {
	//Add custom CSS and Javascript in here.
}


//-------------------------------------------------------------------------
/*	Runs after the admin menu is printed to screens that aren't network- or user-admin screens.*/
//	http://codex.wordpress.org/Plugin_API/Action_Reference/admin_notices
add_action('admin_notices','adminNoticesHook');
function adminNoticesHook() {
	?>
		<div class="notice notice-success is-dismissible">
			<p><?php _e( 'Done!', 'default' ); ?></p>
		</div>
		
		<div class="notice notice-error is-dismissible">
			<p><?php _e( 'Error!', 'default' ); ?></p>
		</div>
		
		<div class="notice notice-info is-dismissible">
			<p><?php _e( 'Info', 'default' ); ?></p>
		</div>
		
		<div class="notice notice-warning is-dismissible">
			<p><?php _e( 'Warning', 'default' ); ?></p>
		</div>
    <?php
}


//-------------------------------------------------------------------------
/*	Runs after the admin menu is printed to screens that aren't network- or user-admin screens.*/
//	http://codex.wordpress.org/Plugin_API/Action_Reference/admin_head
add_action( 'admin_head', 'adminHeadHook' );
function adminHeadHook() {
	//????????????????????????
}



///////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////
//Plugin Settings
///////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////


//-------------------------------------------------------------------------
//	admin_init is triggered before any other hook when a user accesses the admin area. 
//	http://codex.wordpress.org/Plugin_API/Action_Reference/admin_init
add_action('admin_init','adminSetup', 1 );
function adminSetup($title_content){
	//	if not administrator, kill WordPress execution and provide a message
	//	http://codex.wordpress.org/Function_Reference/current_user_can
	if (!current_user_can('manage_options')) {
		wp_redirect( site_url('http://localhost/wordpress/') ); 
		exit;
	}
	
	//	http://codex.wordpress.org/Function_Reference/register_setting
	//register_setting( $option_group, $option_name, $sanitize_callback )
	register_setting( 'WADplugin_options', 'WADplugin_options','WADoptionsvalidate');
	//	http://codex.wordpress.org/Function_Reference/add_settings_section
	//add_settings_section( $id, $title, $callback, $page )
	add_settings_section('WADplugin_main', 'Booking Settings', 'WADpluginsectiontext', 'WADplugin5');
	//	http://codex.wordpress.org/Function_Reference/add_settings_field
	//add_settings_field( $id, $title, $callback, $page, $section, $args ) 	
	add_settings_field('WADplugintextone', 'Option one',   'WADpluginsettings', 'WADplugin5', 'WADplugin_main',array('id'=>'one'));
	add_settings_field('WADplugintexttwo', 'Option two',   'WADpluginsettings', 'WADplugin5', 'WADplugin_main',array('id'=>'two'));
	add_settings_field('WADplugintextthree', 'Option three', 'WADpluginsettings', 'WADplugin5', 'WADplugin_main',array('id'=>'three'));
	add_settings_field('WADplugintextfour', 'Option four', 'WADpluginsettings', 'WADplugin5', 'WADplugin_main',array('id'=>'four'));
	add_settings_field('WADplugintextfive', 'Option five', 'WADpluginsettings', 'WADplugin5', 'WADplugin_main',array('id'=>'five'));
}

//display the options form for each of the options - this is a consolidated function to reduce duplication. individual functions can be created for each option
function WADpluginsettings($args) {
//http://codex.wordpress.org/Function_Reference/get_option	
	$options = get_option('WADplugin_options');

	switch($args['id']) {
		case 'one': 	echo "<input id='WADplugintextone' name='WADplugin_options[text_one]' size='40' type='text' value='{$options['text_one']}' />";
						break;
						
		case 'two': 	echo "<input id='WADplugintexttwo' name='WADplugin_options[text_two]' size='40' type='text' value='{$options['text_two']}' />";
						break;
						
		case 'three': 	echo "<input id='WADplugintextthree' name='WADplugin_options[text_three]' size='40' type='text' value='{$options['text_three']}' />";
						break;	
						
		case 'four':	$chk = $options['text_four']?'checked="checked"':'';
						echo "<input id='WADplugintextfour' name='WADplugin_options[text_four]' type='checkbox' value='{$options['text_four']}' $chk />";
						break;	
						
		case 'five':	$chk = $options['text_five']?'checked="checked"':'';
						echo '<p><label for="WADplugintextfiveA">';
						echo '<input type="radio" id="WADplugintextfiveA" name="WADplugin_options[text_five]" value="0" '.$chk.'/>Off</label>';
						echo '<p><label for="WADplugintextfiveB">';				   
						echo '<input type="radio" id="WADplugintextfiveB" name="WADplugin_options[text_five]" value="1" '.$chk.'/>On</label></p>';
						break;			

	}	
}


//this function iterates through the options an performs some minor testing/error checking before saving any options.
//you need to add you own data validation code here
function WADoptionsvalidate($input) {
//the following array reference assumes you are using the manual provided with the EIT uniformserver distribution
//http://localhost/phpmanual/ref.array.html
	$newinput = array(); //empty array
	
//##using a foreach to iterate through an array	
	foreach ($input as $k => $ni) {
		$ni = trim($ni); //remove whitespace
		if (empty($ni)) $ni = '-'; //set a default if it is empty
		$newinput[$k] = $ni; //store the new value into a new array using the original key from the original array	
	}

	return $newinput; //return the new options list
}


function WADpluginsectiontext() {
	?>
		<p>Change settings for the booking plugin.</p>
	<?php
} 


function adminBookingSettingsPage() {
	
	//Creates a form tag to hold the data pulled from the database.
	?>
		<form action="options.php" method="post">
	<?php
	
	
	//	http://codex.wordpress.org/Function_Reference/settings_fields	
	//	Output action, and option_page fields for a settings page. 	
	settings_fields('WADplugin_options'); 		
	//	http://codex.wordpress.org/Function_Reference/do_settings_sections	
	//	Prints out all settings sections added to a particular settings page.	
	do_settings_sections('WADplugin5'); 
	
	
	//Additional inputs which are not in the database must be added here.
	?>
		<input name="Submit" type="submit" value="Save Changes" /></form>
	<?php
}







///////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////
//Shortcodes
///////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////

add_shortcode('shortcode', 'myShortcode');
function myShortcode(){
	?>
		<p>Look it's shortcode!</p>
	<?php
}

//-------------------------------------------------------------------------






///////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////
//Filters
///////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////

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