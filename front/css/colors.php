<?php 

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

$colors = tourbooking_get_colors(); 

?>

<style>
	.tb_booking_form input[type=submit] {
    background: <?php echo $colors['submit_background']['color'] ?>;
	  color: <?php echo $colors['submit']['color'] ?>;
	}

	.tb_route_title {
    background: <?php echo $colors['map_background']['color'] ?>;
	  color: <?php echo $colors['map']['color'] ?>;
	}

	.tb_more_tours,
	.tb_single_tour h5,
	.tb_tour_city {
	  background-color: <?php echo $colors['city_background']['color'] ?>;
	  color: <?php echo $colors['city']['color'] ?>;
	}

	.tb_tour_info .tb_tour_duration {
		background-color: <?php echo $colors['duration_background']['color'] ?>;
	  color: <?php echo $colors['duration']['color'] ?>;
	}

	.tb_tour_info .tb_tour_price {
		background-color: <?php echo $colors['price_background']['color'] ?>;
	  color: <?php echo $colors['price']['color'] ?>;
	}

	.tb_tour_overlay button {
	  background-color: #2280d8;
	  color: #FFFFFF;
  }
</style>