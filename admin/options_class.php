<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

/*-----------------------------------------------------------------------------------*/
/* Settings page */
/*-----------------------------------------------------------------------------------*/
class Tour_booking_options {

	private $page_title;
	private $page_subtitle;
	private $author_email;

	function __construct() {
		global $wpdb;

		add_action( 'admin_enqueue_scripts', array($this, 'admin_scripts' ) );
		add_action( 'admin_menu', array($this, 'admin_menu') );
		add_action( 'admin_init', array($this, 'tourbooking_options_init') );
	}

	function admin_scripts() {
		$gmap_id = tourbooking_get_options( 'gmap_id' );
		$dependencies = array( 'jquery', 'wp-color-picker' );

    wp_enqueue_style( 'tourbooking_admin_css', plugins_url( 'css/admin_style.css', __FILE__ ), 
    	false, '1.0' );

    if ( !empty( $gmap_id ) ) {
	    wp_enqueue_script( 'tourbooking_google_maps', 
	    	"https://maps.googleapis.com/maps/api/js?key={$gmap_id}&libraries=places" );
	    $dependencies[] = 'tourbooking_google_maps';
    }

    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'tourbooking_admin_js', plugins_url( 'js/admin_scripts.js', __FILE__ ), $dependencies, '1.0', true );
	}

	function admin_menu() {
		$this->page_title = __( 'Tour Booking Plugin Options', 'tourbooking' );
		$this->author_site = 'mailto:esanditskaya@gmail.com';
		$this->page_subtitle = sprintf( __( "Please set up your Tour Booking settings according to your needs. If you have any questions, need support or would like to customise the plugin, <a href='%s'>contact us</a>", 'tourbooking'), $this->author_site );

		add_options_page(
			$this->page_title,
			__( 'Tour Booking Settings', 'es-calc' ),
			'manage_options',
			'options_page_slug',
			array(
				$this,
				'settings_page'
			)
		);
	}

	function settings_page() {
		include_once('templates/options_page.php');
	}

	function tourbooking_options_init(){
	 	register_setting( 'tourbooking_options', 'tourbooking_options');
	 	register_setting( 'tourbooking_colors', 'tourbooking_colors');
	 	register_setting( 'tourbooking_messages', 'tourbooking_messages');
	}
}

