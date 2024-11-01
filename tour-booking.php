<?php
/*
Plugin Name: Tour Booking
Description: A simple plugin for tour booking
Version: 1.0.1
Author: ElSand
Author URI: esanditskaya@gmail.com
License: GPL2
Domain Path: /languages
Text Domain: tourbooking
*/

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

define( 'TOURBOOKING_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'TOURBOOKING_PLUGIN_PATH', dirname( __FILE__ ) );

require_once('functions.php');
if ( is_admin() ) {
	require_once( 'admin/admin_functions.php' );
} else {
	require_once( 'front/front_functions.php' );
}

