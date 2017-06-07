<?php

//simple variable debug function
//usage: pr($avariable);
if (!function_exists('pr')) {
  function pr($var) { echo '<pre>'; var_dump($var); echo '</pre>';}
}
//======================================================================================= 

//refer to CJ2hooks.php regarding the action hooks
register_activation_hook(__FILE__,'CJ_plugin_install');
register_deactivation_hook( __FILE__, 'CJ_plugin_deactivate' );
register_uninstall_hook( __FILE__, 'CJ_faq_uninstall' );

add_action('plugins_loaded', 'CJ_pluging_loaded');
add_action('plugin_action_links_'.plugin_basename(__FILE__), 'CJ_plugin_settings_link' );  

add_action('admin_menu', 'CJ_plugin_menu');

//========================================================================================
//hook for the install function - used to create any tables for the plugin
function CJ_plugin_install () {  
	//refer   CJ8faq.php and CJ7install.php for example code
}  

function CJ_plugin_deactivate () {    
	//refer   CJ8faq.php and CJ7install.php for example code
}  

function CJ_plugin_uninstall() {
	//refer   CJ8faq.php and CJ7install.php for example code
}
 
function CJ_pluging_loaded() {
   //add code that needs to be called each time the plugin has loaded	
}

//========================================================================================
// add the 'settings' label to the plugin menu - it works like a filter by adding the settings URL to the URL list
function CJ_plugin_settings_link($links) { 
	//http://codex.wordpress.org/Function_Reference/admin_url
	//read the note above regarding the PLUGINSLUG page slug used for the rest of the plugin
    array_unshift($links, '<a href="'.admin_url('options-general.php?page=CJdash_indexoptions').'">Settings</a>'); 
   return $links; 
}

//========================================================================================
//The main menu driver
//this function indludes the files linked to by the menu
function CJ_plugin_menu_includes() {
		$data = $_POST;
        $current_page = isset($_REQUEST['page']) ? esc_html($_REQUEST['page']) : 'CJdash_index';
        switch ($current_page) {
            case 'CJdash_index': CJ_plugin_main();  //default
                break;
            case 'Rooms': include('dashboard/Room.php');
				CJ_add_room($data);
                CJ_room_content();
				break;
            case 'Users': include('dashboard/Users.php');
                CJ_register($data);
				CJ_user_content();
				break;
            case 'Bookings_and_Reservations': include('dashboard/Booking.php');
                
				
				CJ_list_bookings(0);

				break;
            case 'Room_Extras': include('dashboard/Extras.php');
                CJ_add_extra($data);
				CJ_extras_content();
				break;
			case 'Reviews': include('dashboard/Reviews.php');
                CJ_add_Review($data);
				CJ_review_content();
				break;
        }
}

function CJ_plugin_menu() {
	//refer to CJ1menus.php for details
		add_menu_page('CJ multi file page title', 'Booking Plugin', 'read','CJdash_index', 'CJ_plugin_main');
    
		add_submenu_page('CJdash_index','CJ submenu one', 'Rooms', 'manage_options','Rooms',  'CJ_plugin_menu_includes');	
    	add_submenu_page('CJdash_index','CJ submenu two', 'Users', 'manage_options','Users',  'CJ_plugin_menu_includes');	
    	add_submenu_page('CJdash_index','CJ submenu one', 'Bookings and Reservations', 'publish_pages','Bookings_and_Reservations', 'CJ_plugin_menu_includes');	
	    add_submenu_page('CJdash_index','CJ submenu two', 'Room Extras', 'publish_pages','Room_Extras', 'CJ_plugin_menu_includes');	
    	add_submenu_page('CJdash_index','CJ submenu one', 'Reviews',   'read',         'Reviews',   'CJ_plugin_menu_includes');		
	
	
//http://codex.wordpress.org/Function_Reference/add_options_page
//add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function);
	add_options_page('CJ menu Options', 'CJ menu options', 'read', 'CJdash_indexoptions', 'CJ_plugin_options');		
}

// HTML/Page Content
function CJ_plugin_main() {
	echo '<h1>CJ Booking Plugin</h1>';
	echo '<h2> What is this? </h2>';
	echo '<p>This menu is for the management of the booking plugin, using the related sub menus you can directly edit the database </p>';
	echo '<p>This is a single page application, for management of a hotel/motel booking system. The Sub-Menus allow changes by acting as a GUI for the Database.  </p>';
	echo '<h2> How do i use it?</h2>';
	echo '<p>To use this pluging: Navagate to the page you wish to be used as the booking system, and add the shortcode [plugin] </p>';
	echo '<p>The contact form for this plugin requires the "Contact Form 7" (https://contactform7.com/) plugin to be installed. The contact for to use should be titled: "CJ_Contact" </p>';
}

function CJ_plugin_options() {
	echo '<h1>Plugin options page</h1>';
	echo '<p>This page was called/accessed using the menu slug CJdash_indexoptions with the URL ?page=CJdash_indexoptions</p>';
	echo 'To have the contact page useable, please install the "Contact Form 7" plugin (https://contactform7.com/) and create a form with the title "CJ_Contact"';
}

?>
