<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

function tb_show_tour_shortinfo( $tour, $tour_meta, $currency ) {
	$thumbnail_url = get_the_post_thumbnail_url($tour->ID);

	$hours_label = __( 'hrs', 'tourbooking' );
	$from_label = __( 'From', 'tourbooking' );
	$permalink = get_permalink($tour->ID);
	$button_label = __( 'Info & Booking', 'tourbooking' );

	return 
	"<li class='tb_tour_short' style='background-image: url(\"$thumbnail_url\")'>
		<div class='tb_tour_city'>{$tour_meta['tour_city'][0]}</div>

		<div class='tb_tour_info'>
			<h1 class='tb_tour_title'>{$tour->post_title}</h1>
			<div class='tb_tour_excerpt'>{$tour->post_excerpt}</div>		

			<div class='tb_tour_duration tb_transport_{$tour_meta['tour_transport'][0]}'>
				{$tour_meta['tour_duration'][0]} $hours_label
			</div>

			<div class='tb_tour_price'>$from_label $currency {$tour_meta['tour_price'][0]}</div>
		</div>

	 	<a class='tb_tour_overlay' href='$permalink'>
			<button>$button_label</button>
		</a>
	</li>";
}
