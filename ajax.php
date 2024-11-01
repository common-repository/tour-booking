<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

add_action('wp_ajax_tb_book_tour', 'tb_book_tour');
add_action('wp_ajax_nopriv_tb_book_tour', 'tb_book_tour');
function tb_book_tour() {
	check_ajax_referer( 'tourbooking_form_nounce', 'security' );

	$check_captcha = tb_check_captcha( sanitize_text_field( $_REQUEST['token'] ) );
	if ( !$check_captcha ) {
		echo 'recaptcha_error';
		wp_die();
	}

	$email_options = tourbooking_get_messages( array( 
		'manager_email', 
		'email_subscription', 
		'booking_email_subject', 
		'booking_email_message', 
	) );

	$website = 'test.athome.in.ua';
	
	$headers  = "From: $website < manager@{$website} >\n";
  $headers .= "Cc: $website < manager@{$website} >\n"; 
  $headers .= "X-Sender: $website < manager@{$website} >\n";
  $headers .= 'X-Mailer: PHP/' . phpversion() . "\r\n";
  $headers .= "Return-Path: {$email_options['manager_email']}\n"; 
  $headers .= "MIME-Version: 1.0\r\n";
  $headers .= "Content-Type: text/html; charset=iso-8859-1\n";

	$message = sprintf( 
		$email_options['booking_email_message'], 
		sanitize_text_field( $_REQUEST['name'] ), 
		sanitize_text_field( $_REQUEST['tour'] ) );


	$admin_message = "<table>";
	foreach ( tourbooking_form_fields() as $key => $options ) {
		switch ( $key ) {
			case 'datetime':
				$date = sanitize_text_field( $_REQUEST[$key]['date'] );
				$time = sanitize_text_field( $_REQUEST[$key]['time'] );
				$admin_message .= "<tr><td>{$options['placeholder']}</td><td>$date $time</td></tr>";
				break;
			
			case 'messenger':
				$viber = sanitize_text_field( $_REQUEST[$key]['viber'] );
				$watsup = sanitize_text_field( $_REQUEST[$key]['watsup'] );
				$admin_message .= "<tr><td>{$options['placeholder']}</td><td>$viber $watsup</td></tr>";
				break;
			
			case 'action':
			case 'token':
				break;
			
			default:
				$value = sanitize_text_field( $_REQUEST[$key] );
				$admin_message .= "<tr><td>{$options['placeholder']}</td><td>$value</td></tr>";
				break;
		}
	}
	$admin_message .= "</table>";

	$email = sanitize_text_field( $_REQUEST['email'] );
	wp_mail( $email, $email_options['booking_email_subject'], $message, $headers );
	wp_mail( $email_options['manager_email'], __( 'New booking', 'tourbooking' ), $admin_message, $headers );
	
	wp_die();
}

function tb_check_captcha( $captcha ) {
  if ( !$captcha ) return false;
  $ip = $_SERVER['REMOTE_ADDR'];

  $url = 'https://www.google.com/recaptcha/api/siteverify';
  $data = array( 
  	'secret' => tourbooking_get_options( 'recaptcha_secret' ), 
  	'response' => $captcha,
	);

  $options = array(
    'http' => array(
      'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
      'method'  => 'POST',
      'content' => http_build_query( $data ),
    )
  );
  $context  = stream_context_create($options);
  $response = file_get_contents( $url, false, $context );
  $responseKeys = json_decode( $response, true );

  return $responseKeys["success"];
}

add_action('wp_ajax_tb_show_more_tours', 'tb_show_more_tours');
add_action('wp_ajax_nopriv_tb_show_more_tours', 'tb_show_more_tours');
function tb_show_more_tours() {
	$offset = intval( sanitize_text_field( $_REQUEST['offset'] ) );
	if ( empty( $offset ) || $offset == 0 ) wp_die();

	$atts = sanitize_text_field( $_REQUEST['atts'] );
	$atts['tours'] = tourbooking_get_options( 'tours_per_page' );
	echo tourbooking_get_tours( $atts, $offset );
	wp_die();
}

add_action('wp_ajax_tb_select_tours', 'tb_select_tours');
add_action('wp_ajax_nopriv_tb_select_tours', 'tb_select_tours');
function tb_select_tours() {
	check_ajax_referer( 'tourbooking_form_nounce', 'security' );

	$url = get_post_type_archive_link('tour');

	if ( !empty( $city = sanitize_text_field( $_REQUEST['city'] ) ) ) {
		$url = add_query_arg( 'city', $city, $url );
	}

	if ( !empty( $transport = sanitize_text_field( $_REQUEST['transport'] ) ) ) {
		$url = add_query_arg( 'transport', $transport, $url );
	}

	if ( !empty( $category = intval( sanitize_text_field( $_REQUEST['category'] ) ) ) ) {
		$url = add_query_arg( 'c', $category, $url );
	}

	if ( !empty( $tag = intval( sanitize_text_field( $_REQUEST['tag'] ) ) ) ) {
		$url = add_query_arg( 't', $tag, $url );
	}

	echo $url;
	wp_die();
}
