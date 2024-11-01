<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

require_once('shortcodes.php');
 
function tourbooking_css() {		
	wp_enqueue_style( 'tourbooking_css', plugins_url( 'css/style.css', __FILE__ ), false, '1.0' );

	wp_enqueue_style( 'tourbooking_css_responsive', plugins_url( 'css/responsive.css', __FILE__ ), false, '1.0' );
}
add_action( 'wp_print_styles', 'tourbooking_css' );

function tourbooking_scripts() {
  $options = tourbooking_get_options( array( 'recaptcha_public', 'gmap_id' ) );
  $recaptcha_public = $options['recaptcha_public']['value'];
  $dependencies = array( 'jquery' );

  if ( !empty( $options['gmap_id']['value'] ) ) { 
    wp_enqueue_script( 'tourbooking_google_maps', 
      "https://maps.googleapis.com/maps/api/js?key={$options['gmap_id']['value']}&libraries=places" );
    $dependencies[] = 'tourbooking_google_maps';
  }

  if ( is_singular( 'tour' ) ) {
    wp_enqueue_script( 'tourbooking_recaptcha', "https://www.google.com/recaptcha/api.js?render=$recaptcha_public" );
    $dependencies[] = 'tourbooking_recaptcha';
  } 

	wp_enqueue_script( 'tourbooking_js', plugins_url( 'js/script.js', __FILE__ ), $dependencies, '1.0', true );

  wp_localize_script( 'tourbooking_js', 'tbHelper',
    array( 
      'ajax_url' => admin_url( 'admin-ajax.php' ),
      'ajax_nonce' => wp_create_nonce('tourbooking_form_nounce'),
      'recaptcha_public' => $recaptcha_public,
    ) 
  );
}
add_action( 'wp_enqueue_scripts', 'tourbooking_scripts' );

function tourbooking_single_tour_template( $single ) {
  global $post;

  if ( $post->post_type == 'tour' && file_exists( TOURBOOKING_PLUGIN_PATH . '/front/templates/single_tour.php' ) ) {
    return TOURBOOKING_PLUGIN_PATH . '/front/templates/single_tour.php';
  }

  return $single;
}
add_filter( 'single_template', 'tourbooking_single_tour_template' );

function tourbooking_tours_template( $archive_template ) {
  global $post;

  if ( $post->post_type == 'tour' ) {
    echo $post->post_type;
    if ( file_exists( TOURBOOKING_PLUGIN_PATH . '/front/templates/archive_tours.php' ) ) {
      return TOURBOOKING_PLUGIN_PATH . '/front/templates/archive_tours.php';
    }
  }

  return $archive_template;
}
add_filter( 'archive_template', 'tourbooking_tours_template' );

function tourbooking_build_tag_list($post_tags, $tag_class='') {

  if ( empty($post_tags) ) return '';

  $tag_list = array();
  foreach ( $post_tags as $tag ) {
    $tag_link = get_tag_link($tag->term_id);
    $tag_list[] = "<a class='$tag_class' href='$tag_link'>$tag->name</a>";
  }
  return implode(', ', $tag_list);
}
