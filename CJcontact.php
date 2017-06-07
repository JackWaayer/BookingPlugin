<?php

if (!function_exists('pr')) {
  function pr($var) { echo '<pre>Diagnostics: '; var_dump($var); echo '</pre>';}
}

function CJ_contact(){

    CJ_simContactForm();

}

function CJ_simContactForm(){
    if ( function_exists('wpcf7_plugin_path')) {
		echo do_shortcode('[contact-form-7 id="35" title="CJ_Contact"]');
	} else {
	  echo 'WARNING: Install and activate the "Contact-Form-7" wordpress plugin before trying to use this page. https://contactform7.com/';
    echo 'WARNING: Make sure to create a contact form named "CJ_Contact"';
	}
}

?>