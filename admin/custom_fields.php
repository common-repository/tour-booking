<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

function tourbooking_metaboxes( $name = '' ) {
	$currency = tourbooking_get_options( 'currency' );
	$metaboxes = array(
		'tour_price' => array(
			'type' => 'number',
			'post_types' => array( 'tour' ),
			'title' => __( 'Price', 'tourbooking' ),
			'default' => 0,
			'html' => "<input type='number' name='tour_price' value='%s' min='0' /> $currency",
			'place' => 'side',
		),

		'tour_duration' => array(
			'type' => 'number',
			'post_types' => array( 'tour' ),
			'title' => __( 'Duration', 'tourbooking' ),
			'default' => 3,
			'html' => "<input type='number' name='tour_duration' value='%s' min='0' /> " . __( 'hours', 'tourbooking' ),
			'place' => 'side',
		),

		'tour_city' => array(
			'type' => 'text',
			'post_types' => array( 'tour', 'tour_listing' ),
			'title' => __( 'City', 'tourbooking' ),
			'default' => tourbooking_get_options( 'default_city' ),
			'html' => "<input type='text' name='tour_city' value='%s'/>",
			'place' => 'advanced',
		),

		'tour_transport' => array(
			'type' => 'select',
			'post_types' => array( 'tour', 'tour_listing' ),
			'title' => __( 'Transport type', 'tourbooking' ),
			'default' => 'walk',
			'place' => 'advanced',
		),

		'tour_start' => array(
			'type' => 'map',
			'post_types' => array( 'tour' ),
			'title' => __( 'Start location', 'tourbooking' ),
			'default' => '',
			'html' => tourbooking_map_field( 'tour_start' ),
			'place' => 'advanced',
		),

		'tour_finish' => array(
			'type' => 'map',
			'post_types' => array( 'tour' ),
			'title' => __( 'Finish location', 'tourbooking' ),
			'default' => '',
			'html' => tourbooking_map_field( 'tour_finish' ),
			'place' => 'advanced',
		),

		// 'tour_is_hot' => array(
		// 	'type' => 'checkbox',
		// 'post_types' => array( 'tour' ),
		// 	'title' => __( 'Is hot', 'tourbooking' ),
		// 	'default' => '0',
		// 	'html' => "<input type='checkbox' name='tour_is_hot' value='0' %s />",
		// 	'place' => 'side',
		// ),

		'tour_order' => array(
			'type' => 'number',
			'post_types' => array( 'tour' ),
			'title' => __( 'Order', 'tourbooking' ),
			'default' => 1,
			'html' => "<input type='number' min='1' name='tour_order' value='%s' />",
			'place' => 'side',
		),

		'tour_shortcode' => array(
			'type' => 'hidden',
			'post_types' => array( 'tour_listing' ),
			'title' => __( 'Shortcode (copy to page)', 'tourbooking' ),
			'default' => '[list_tours id="%s"]',
			'html' => "<input type='text' name='tour_listing' value='%s' readonly />",
			'place' => 'advanced',
		),
	);

	return empty( $name ) ? $metaboxes : $metaboxes[$name];
}

// function tourbooking_render_checkbox( $args ) {
// 	es_dump($args);
// 	return '';
// }

function tourbooking_get_post_meta( $default, $key ) {
	if ( empty( $_GET['post'] ) ) {
		return $default;
	}

	$post_id = intval( sanitize_text_field( $_GET['post'] ) );
	return get_post_meta( $post_id, $key, true );
}

function tourbooking_render_hidden( $args ) {
	if ( empty( $_GET['post'] ) ) return '';
	$post_id = intval( sanitize_text_field( $_GET['post'] ) );

	$options = $args['options'];
	$value = sprintf( $options['default'], $post_id );
	return sprintf( $options['html'], $value );
}

function tourbooking_render_map( $args ) {
	$metabox_options = $args['options'];
	$metabox_value = tourbooking_get_post_meta( $metabox_options['default'], $args['key'] );
 	return sprintf( $metabox_options['html'], $metabox_value );
	return '';
}

function tourbooking_render_number( $args ) {
	$metabox_options = $args['options'];
	$metabox_value = tourbooking_get_post_meta( $metabox_options['default'], $args['key'] );
 	return sprintf( $metabox_options['html'], $metabox_value );
	return '';
}

function tourbooking_render_select( $args ) {
	$key = $args['key'];
	$metabox_value = tourbooking_get_post_meta( '', $args['key'] );

	$selector = "<select name='$key'>";
	foreach ( tourbooking_get_options( str_replace( 'tour_', '', $key ) ) as $name => $title ) {
		$selected = $metabox_value == $name ? 'selected' : '';
		$selector .= "<option value='$name' $selected >$title</option>";
	}
	$selected = empty( $metabox_value ) ? 'selected' : '';
	$selector .= "<option value='' $selected>" . __( 'None', 'tourbooking' ) . "</option>";
	$selector .= "</select>";

	return $selector;
}

function tourbooking_render_text( $args ) {
	$metabox_options = $args['options'];
	$metabox_value = tourbooking_get_post_meta( $metabox_options['default'], $args['key'] );
 	return sprintf( $metabox_options['html'], $metabox_value );
}

function tourbooking_map_field( $name ) {
	$show_map = empty( tourbooking_get_options( 'gmap_id' ) ) ? '' : "<div id='{$name}_map' class='tour_map'></div>";
	return "<input id='{$name}_address' placeholder=" . __('Enter your address', 'tourbooking') . " type='text' 
					name='$name' value='%s' />$show_map";
}

function tourbooking_metabox_showup( $post, $args ) { 
	$metabox_name = $args['id'];
	$metabox_options = tourbooking_metaboxes( $metabox_name );
	
	echo call_user_func( "tourbooking_render_{$metabox_options['type']}", 
		array( 'key' => $metabox_name, 'options' => $metabox_options ) );
}

function tourbooking_init() { 
	foreach ( tourbooking_metaboxes() as $name => $metabox_options ) {
		foreach ( $metabox_options['post_types'] as $post_type ) {
			add_meta_box( $name, $metabox_options['title'], 'tourbooking_metabox_showup', $post_type, $metabox_options['place'] ); 
		}
	}
} 
add_action('add_meta_boxes', 'tourbooking_init'); 

function tourbooking_save( $postID ) { 
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE || wp_is_post_revision( $postID ) ) return; 

	foreach ( tourbooking_metaboxes() as $name => $metabox_options ) {
		if ( !isset( $_POST[$name] ) ) continue;

		$data = sanitize_text_field( $_POST[$name] );
		if ( $metabox_options['type'] == 'number' ) {
			$data = intval( $data ); 
		}  
		update_post_meta( $postID, $name, $data ); 
	}
} 
add_action('save_post', 'tourbooking_save'); 
