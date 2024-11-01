<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

function tourbooking_get_atts_by_id( $id ) {
	$atts = array();

	$meta = get_post_meta( $id );
	if ( !empty( $meta['tour_city'] ) ) {
		$atts['city'] = $meta['tour_city'][0];
	}
	if ( !empty( $meta['tour_transport'] ) ) {
		$atts['transport'] = $meta['tour_transport'][0];
	}

	$tag = get_the_tags( $id );
	if ( !empty( $tag ) ) {
		$atts['tag'] = implode( ', ', wp_list_pluck( $tag, 'slug' ) );
	}

	$category = get_the_category( $id );
	if ( !empty( $category ) ) {
		$atts['category'] = implode( ', ', wp_list_pluck( $category, 'slug' ) );
	}

	return $atts;
}

function tourbooking_list_tours( $atts ) {
  ob_start();

  if ( !empty( $atts['id'] ) ) {
  	$atts = tourbooking_get_atts_by_id( $atts['id'] );
  }

	$count_tours = tourbooking_get_tours( $atts, -1 );
	$tours = tourbooking_get_tours( $atts );

	echo "<ul class='tb_tour_list' max='$count_tours' atts='" . json_encode($atts) . "'>$tours</ul>";
  echo "<button class='tb_more_tours'>" . __( 'More', 'tourbooking' ) 
     . "</button><div class='tb_ajax_loader_overlay tb_show_more_ajax'></div>";		

  return ob_get_clean();
}
add_shortcode( 'list_tours', 'tourbooking_list_tours' );

?>