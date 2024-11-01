<?php
require_once('options_class.php');
require_once('custom_fields.php');

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

/*-----------------------------------------------------------------------------------*/
/* Custon Post Types */
/*-----------------------------------------------------------------------------------*/

$tourbooking = new Tour_booking_options;
?>