<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

add_action('wp_head', 'tourbooking_colors_css');
function tourbooking_colors_css() {
  require_once( 'front/css/colors.php' );
}

require('front/templates/tour_list_template.php');
function tourbooking_get_tours( $atts, $offset = 0 ) { // $offset = -1 => count posts
  $currency = tourbooking_get_options( 'currency' );

  if ( $offset == -1 ) {
    $tours_per_page = -1;  
  } else {
    $tours_per_page = empty( $atts ) || empty( $atts['tours'] ) ? tourbooking_get_options( 'tours_per_page' ) 
      : intval( sanitize_text_field( $atts['tours'] ) );
  }

  $meta_query = array( 'order' => array( 'key' => 'tour_order' ) );

  if ( !empty( $atts['city'] ) && !empty( $city = sanitize_text_field( $atts['city'] ) ) ) {
    $meta_query[] = array(
      'key' => 'tour_city',
      'value' => $city,
      'compare' => '=',
    );
  } 

  if ( !empty( $atts['transport'] ) && !empty( $transport = sanitize_text_field( $atts['transport'] ) ) ) {
    $meta_query[] = array(
      'key' => 'tour_transport',
      'value' => $transport,
      'compare' => '=',
    );
  } 

  $tours_args = array(
    'post_type' => 'tour',
    'orderby' => array( 'order' => 'ASC' ),
    'meta_query' => $meta_query, 
    'posts_per_page'    => $tours_per_page,
    'offset'            => $offset,
  );

  if ( !empty( $atts['category'] ) && !empty( $category = sanitize_text_field( $atts['category'] ) ) ) {
    $tours_args['category_name'] = $category;
  }

  if ( !empty( $atts['tag'] ) && !empty( $tag = sanitize_text_field( $atts['tag'] ) ) ) {
    $tours_args['tax_query'] = array(
      array(
        'taxonomy' => 'post_tag',
        'field' => 'slug',
        'terms' => explode( ', ', $tag ),
      )
    );
  }
  $tours = get_posts( $tours_args );

  if ( $offset == -1 ) {
    return count( $tours );
  } 

  $tours_info = '';
  foreach ( $tours as $tour ) {
    $tour_meta = get_post_meta($tour->ID);
    $tours_info .= tb_show_tour_shortinfo( $tour, $tour_meta, $currency );
  }
  return $tours_info;
}

function tourbooking_custom_post_types() {
  register_post_type( 'tour ', array(
    'description'   => __( 'Holds our tours and tour specific data', 'tourbooking' ),
    'has_archive'   => true,
    'labels'        => array(
      'name'               => _x( 'Tours', 'tourbooking' ),
      'singular_name'      => _x( 'Tour', 'tourbooking' ),
      'add_new'            => _x( 'Add New', 'Tour' ),
      'add_new_item'       => __( 'Add New Tour', 'tourbooking' ),
      'edit_item'          => __( 'Edit Tour', 'tourbooking' ),
      'new_item'           => __( 'New Tour', 'tourbooking' ),
      'all_items'          => __( 'All Tours', 'tourbooking' ),
      'view_item'          => __( 'View Tours', 'tourbooking' ),
      'search_items'       => __( 'Search Tour', 'tourbooking' ),
      'not_found'          => __( 'No Tours found', 'tourbooking' ),
      'not_found_in_trash' => __( 'No Tours found in the Trash', 'tourbooking' ),
      'parent_item_colon'  => '',
      'menu_name'          => __( 'Tours', 'tourbooking' ),
    ),
    'menu_position' => 20,
    'public'        => true,
    'rewrite'       => array( 'slug' => 'tour' ),
    'supports'      => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
    'taxonomies'    => array( 'category', 'post_tag' ),
  ) );

  register_post_type( 'tour_listing', array(
    'description'   => __( 'Holds our tour listings with specific options', 'tourbooking' ),
    'has_archive'   => false,
    'labels'        => array(
      'name'               => _x( 'Tour listingss', 'tourbooking' ),
      'singular_name'      => _x( 'Tour listing', 'tourbooking' ),
      'add_new'            => _x( 'Add New', 'Tour listing' ),
      'add_new_item'       => __( 'Add New Tour Listing', 'tourbooking' ),
      'edit_item'          => __( 'Edit Tour Listing', 'tourbooking' ),
      'new_item'           => __( 'New Tour Listing', 'tourbooking' ),
      'all_items'          => __( 'All Tour Listings', 'tourbooking' ),
      'view_item'          => __( 'View Tour Listings', 'tourbooking' ),
      'search_items'       => __( 'Search Tour Listing', 'tourbooking' ),
      'not_found'          => __( 'No Tour Listings found', 'tourbooking' ),
      'not_found_in_trash' => __( 'No Tour Listings found in the Trash', 'tourbooking' ),
      'parent_item_colon'  => '',
      'menu_name'          => __( 'Tour Listings', 'tourbooking' ),
    ),
    'menu_position' => 21,
    'public'        => true,
    'rewrite'       => array( 'slug' => 'tour_listing' ),
    'supports'      => array( 'title' ),
    'taxonomies'    => array( 'category', 'post_tag' ),
  ) );

  flush_rewrite_rules();
}
add_action( 'init', 'tourbooking_custom_post_types' );

function tourbooking_get_options( $field = '' ) {
  $default_options = array(
    'currency' => array( 
      'type' => 'select',
      'title' => __('Default currency', 'tourbooking'),
      'value' => '$',
      'settings' => array(
        'variants' => array(
          '$' => '$',
          '€' => '€',
        ),
      ),
    ),
    'default_city' => array( 
      'type' => 'text',
      'title' => __('Default city', 'tourbooking'),
      'value' => '',
      'settings' => array(),
    ),
    'tours_per_page' => array( 
      'type' => 'number',
      'title' => __('Tours per page', 'tourbooking'),
      'settings' => array(
        'min' => 1,
      ),
      'value' => 6,
    ),
    'gmap_id' => array( 
      'type' => 'text',
      'title' => __('Google maps ID (if any)', 'tourbooking'),
      'value' => '',
      'settings' => array(),
    ),
    'recaptcha_public' => array( 
      'type' => 'text',
      'title' => __('Google reCaptcha public key', 'tourbooking'),
      'value' => '',
      'settings' => array(),
    ),
    'recaptcha_secret' => array( 
      'type' => 'text',
      'title' => __('Google reCaptcha secret key', 'tourbooking'),
      'value' => '',
      'settings' => array(),
    ),
    'transport' => array( 
      'type' => 'collection',
      'title' => __('Transport available', 'tourbooking'),
      'value' => array(
        'walk' => __('Walking tour', 'tourbooking'),
        'car' => __('Car/Bus', 'tourbooking'),
        'bike' => __('Bike', 'tourbooking'),
        'boat' => __('Ship/Boat', 'tourbooking'),
        'horse' => __('Horse', 'tourbooking'),
        'quad' => __('Quad Bike', 'tourbooking'),
        'helicopter' => __('Helicopter', 'tourbooking'),
        'other' => __('Other transport', 'tourbooking'),
      ),
      'settings' => array(),
    ),
  );

  $options = get_option('tourbooking_options');

  if ( !empty( $field ) ) {
    if ( is_array( $field ) ) {
      $options_result = array();
      foreach ( $field as $key ) {
        $default_value = $default_options[$key];
        $options_result[$key] = empty( $options[$key] ) ? $default_value : array( 
            'type' => $default_value['type'],
            'title' => $default_value['title'],
            'settings' => $default_value['settings'],
            'value' => $options[$key],
           );
      }
      return apply_filters('tourbooking_options', $options_result );
    }
    $options_result = empty( $options ) || empty( $options[$field] ) ? $default_options[$field]['value'] : $options[$field];
    return apply_filters('tourbooking_options', $options_result );
  }

  $options_result = array();
  foreach ( $default_options as $key => $default_value ) {
    $options_result[$key] = empty( $options[$key] ) ? $default_value : array( 
        'type' => $default_value['type'],
        'title' => $default_value['title'],
        'settings' => $default_value['settings'],
        'value' => $options[$key],
       );
  }

  return apply_filters('tourbooking_options', $options_result );
}

function tourbooking_get_colors( $field = '' ) {
  $default_colors = array(
    'city' => array( 
      'title' => __( 'City text', 'tourbooking' ),
      'color' => '#FFFFFF',
    ),
    'city_background' => array( 
      'title' => __( 'City background', 'tourbooking' ),
      'color' => '#2280d8',
    ),
    'duration' => array( 
      'title' => __( 'Duration text', 'tourbooking' ),
      'color' => '#FFFFFF',
    ),
    'duration_background' => array( 
      'title' => __( 'Duration background', 'tourbooking' ),
      'color' => '#8A2BE2',
    ),
    'map' => array( 
      'title' => __( 'Map text', 'tourbooking' ),
      'color' => '#FFFFFF',
    ),
    'map_background' => array( 
      'title' => __( 'Map background', 'tourbooking' ),
      'color' => '#2280d8',
    ),
    'price' => array( 
      'title' => __( 'Price text', 'tourbooking' ),
      'color' => '#FFFFFF',
    ),
    'price_background' => array( 
      'title' => __( 'Price background', 'tourbooking' ),
      'color' => '#006400',
    ),
    'submit' => array( 
      'title' => __( 'Submit button text', 'tourbooking' ),
      'color' => '#FFFFFF',
    ),
    'submit_background' => array( 
      'title' => __( 'Submit button background', 'tourbooking' ),
      'color' => '#2280d8',
    ),
	);

	$options = get_option('tourbooking_colors');

  if ( !empty( $field ) ) {
    return empty( $options ) ? $default_colors[$field] : $options[$field];
  }

	$options_result = array();
  foreach ( $default_colors as $key => $default_value ) {
    $options_result[$key] = empty( $options[$key] ) ? $default_value : array( 
        'title' => $default_value['title'],
        'color' => $options[$key],
       );
  }

  return apply_filters('tourbooking_colors', $options_result );
}

function tourbooking_get_messages( $fields = '' ) {
  $default_options = array(
    'manager_email' => array( 
      'type' => 'email',
      'title' => __('Manager email', 'tourbooking'),
      'value' => get_option('admin_email'),
      'settings' => array(),
    ),
    'email_subscription' => array( 
      'type' => 'textarea',
      'title' => __('Email subscription', 'tourbooking'),
      'value' => 'Your truly, ',
      'settings' => array(),
    ),
    'booking_email_subject' => array( 
      'type' => 'text',
      'title' => __('Reply to booking (subject)', 'tourbooking'),
      'value' => 'Reply to your booking',
      'settings' => array(),
    ),
    'booking_email_message' => array( 
      'type' => 'textarea',
      'title' => __('Reply to booking (message)', 'tourbooking'),
      'value' => 'Dear %s! You booked tour "%s". Our manager will contact you as soon as possible',
      'settings' => array(),
    ),
    'booking_screen_message' => array( 
      'type' => 'textarea',
      'title' => __('Reply to booking (screen message)', 'tourbooking'),
      'value' => 'Congratulations! Your booking is accepted. Our manager will contact you as soon as possible.',
      'settings' => array(),
    ),
  );

  $options = get_option('tourbooking_messages');

  if ( !empty( $fields ) ) {
    if ( is_array( $fields ) ) {
      $options_result = array();
      foreach ( $fields as $field ) {
        $options_result[$field] = empty( $options ) ? $default_options[$field]['value'] : $options[$field];
      }
      return $options_result;
    }
    return empty( $options ) ? $default_options[$fields]['value'] : $options[$fields];
  }

  $options_result = array();
  foreach ( $default_options as $key => $default_value ) {
    $options_result[$key] = empty( $options[$key] ) ? $default_value : array( 
        'type' => $default_value['type'],
        'title' => $default_value['title'],
        'settings' => $default_value['settings'],
        'value' => $options[$key],
       );
  }

  return apply_filters('tourbooking_messages', $options_result );
}

function tourbooking_form_fields() {
  $fields = array(
    'tour' => array( 
      'type' => 'hidden',
      'value' => get_the_title(),
    ),
    'name' => array( 
      'type' => 'text',
      'placeholder' => __( 'Client name', 'tourbooking' ),
      'required' => 'required',
    ),
    'email' => array( 
      'type' => 'email',
      'placeholder' => __( 'Client email', 'tourbooking' ),
      'required' => 'required',
    ),
    'phone' => array( 
      'type' => 'phone',
      'placeholder' => __( 'Client phone number', 'tourbooking' ),
      'required' => 'required',
    ),
    'messenger' => array( 
      'type' => 'checkbox_group',
      'placeholder' => __( 'Is it your Viber/WatsUp number?', 'tourbooking' ),
      'required' => '',
      'options' => array(
        'viber' => __( 'Viber', 'tourbooking' ),
        'watsup' => __( 'WatsUp', 'tourbooking' ),
      ),
    ),
    'datetime' => array( 
      'type' => 'datetime',
      'placeholder' => __( 'Preferred date and time', 'tourbooking' ),
      'required' => '',
    ),
    'language' => array( 
      'type' => 'text',
      'placeholder' => __( 'Preferred lagguage', 'tourbooking' ),
      'required' => '',
    ),
    'guests_number' => array( 
      'type' => 'number',
      'placeholder' => __( 'Number of guests', 'tourbooking' ),
      'required' => '',
      'min' => 1,
    ),
    'message' => array( 
      'type' => 'textarea',
      'placeholder' => __( 'Additional wishes', 'tourbooking' ),
      'required' => '',
    ),
  );

  return apply_filters('tourbooking_get_form_fields', $fields );
}

function tourbooking_get_meta_values( $key = '', $type = 'tour', $status = 'publish' ) {
    global $wpdb;

    if( empty( $key ) )
        return;

    $r = $wpdb->get_col( $wpdb->prepare( "
        SELECT DISTINCT pm.meta_value FROM {$wpdb->postmeta} pm
        LEFT JOIN {$wpdb->posts} p ON p.ID = pm.post_id
        WHERE pm.meta_key = %s 
        AND p.post_status = %s 
        AND p.post_type = %s
    ", $key, $status, $type ) );

    return $r;
}

require_once('ajax.php');
require_once('tour-selector.php');
