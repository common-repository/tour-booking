<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

if ( ! empty( $title ) ) {
	echo $args['before_title'] . $title . $args['after_title'];
}

$cities = tourbooking_get_meta_values( 'tour_city' );
$transport = tourbooking_get_meta_values( 'tour_transport' );

$categories = get_categories( array(
  'type' => 'tour',
  'orderby' => 'name',
  'order' => 'ASC',
) );

$tags = get_tags( array(
  'type' => 'tour',
  'orderby' => 'name',
  'order' => 'ASC',
) );
?>

<div class="tb_tour_selector_container">
	<form class="tb_tour_selector" id="tb_tour_selector">
		<select name="city">
			<option value=""><?php _e( 'Select city', 'tourbooking' ) ?></option>
			<?php foreach ( $cities as $city ) : ?>
				<option value="<?php echo $city ?>"><?php echo $city ?></option>
			<?php endforeach; ?>
		</select>

		<select name="transport">
			<option value=""><?php _e( 'Select transport', 'tourbooking' ) ?></option>
			<?php foreach ( $transport as $type ) : ?>
				<option value="<?php echo $type ?>"><?php echo $type ?></option>
			<?php endforeach; ?>
		</select>

		<select name="category">
			<option value=""><?php _e( 'Select tour category', 'tourbooking' ) ?></option>
			<?php foreach ( $categories as $category ) : ?>
				<option value="<?php echo $category->term_id ?>"><?php echo $category->name ?></option>
			<?php endforeach; ?>
		</select>

		<select name="tag">
			<option value=""><?php _e( 'Select tour tag(s)', 'tourbooking' ) ?></option>
			<?php foreach ( $tags as $tag ) : ?>
				<option value="<?php echo $tag->term_id ?>"><?php echo $tag->name ?></option>
			<?php endforeach; ?>
		</select>

		<input type="submit" value="<?php _e( 'Select', 'tourbooking' ) ?>"/>
	</form>	
	<div class="tb_ajax_loader_overlay" id="tb_booking_selector_ajax"></div>
</div>