<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

$atts = '';
if ( !empty( $_GET['city'] ) && !empty( $city = sanitize_text_field( $_GET['city'] ) ) ) {
	$atts .= " city='$city'";
}

if ( !empty( $_GET['transport'] ) && !empty( $transport = sanitize_text_field( $_GET['transport'] ) ) ) {
	$atts .= " transport='$transport'";
}

if ( !empty( $_GET['c'] ) && !empty( $category = intval( sanitize_text_field( $_GET['c'] ) ) ) ) {
	$atts .= " category='$category'";
}

if ( !empty( $_GET['t'] ) && !empty( $tag = intval( sanitize_text_field( $_GET['t'] ) ) ) ) {
	$atts .= " tag='$tag'";
}

get_header();

echo do_shortcode("[list_tours $atts]");

get_footer(); 
?>
