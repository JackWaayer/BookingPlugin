<?php



//simple variable debug function
//usage: pr($avariable);
if (!function_exists('pr')) {
  function pr($var) { echo '<pre>Diagnostics: '; var_dump($var); echo '</pre>';}
}


function CJ_home(){
    echo '<h1>Welcome to the Booking Plugin</h1>';
    echo '
      <br />
      <br />
      <h5>Login and make a booking, register or just take a look around.</h5>';
}







?>