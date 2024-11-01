<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

get_header();

$tour_id = get_the_ID();
$post = get_post( $tour_id );
$tour_meta = get_post_meta( $tour_id );

$options = tourbooking_get_options( array( 'currency', 'gmap_id' ) );
$show_map = !empty( $options['gmap_id']['value'] );
the_post();
?>

<div class="tb_single_tour">

  <div class="tb_tour_content_container">
    <div class="tb_tour_thumbnail"
         style="background-image: url('<?php echo get_the_post_thumbnail_url(); ?>');">
      <div class="tb_tour_city"><?php echo $tour_meta['tour_city'][0]; ?></div>

      <div class="tb_tour_info">
        <div class="tb_tour_duration tb_transport_<?php echo $tour_meta['tour_transport'][0] ?>">
          <?php echo "{$tour_meta['tour_duration'][0]} "; _e('hrs', 'touragency') ?>
        </div>

        <div class="tb_tour_price">
          <?php _e('From', 'touragency'); echo " {$options['currency']['value']} {$tour_meta['tour_price'][0]}" ?>
        </div>
      </div>
    </div>

    <h2 class="tb_tour_title"><?php the_title() ?></h2>
    <div class="tb_tour_content"> 
      <?php the_content(); ?>
    </div>

    <div class="tb_tour_route">
      <div class="tb_tour_point" id="tb_tour_start">
        <div class="tb_route_title"><?php _e('From', 'tourbooking') ?></div>
        <div class="tb_tour_address" id="tour_start_address">
          <?php echo $tour_meta['tour_start'][0]?>
        </div>
        <?php if ( $show_map ) : ?>
          <div class="tb_tour_route_map" id="tour_start_map"></div>
        <?php endif; ?>
      </div>

      <div class="tb_tour_point" id="tour_finish">
        <div class="tb_route_title"><?php _e('To', 'tourbooking') ?></div>
        <div class="tb_tour_address" id="tour_finish_address">
          <?php echo $tour_meta['tour_finish'][0]?>
        </div>
        <?php if ( $show_map ) : ?>
          <div class="tb_tour_route_map" id="tour_finish_map"></div>
        <?php endif; ?>
      </div>
    </div>

<!--     <div class="tb_map_container" id="tour_start"><?php echo $tour_meta['tour_start'][0]; ?></div>
    <div class="tb_map_container" id="tour_finish"><?php echo $tour_meta['tour_finish'][0]; ?></div> -->

    <div class="tb_post_tags">
      <?php echo tourbooking_build_tag_list( get_the_tags() ) ?>
    </div>
  </div>

  <div class="tb_book_tour">
    <h5><?php _e('Book a tour', 'tourbooking') ?></h5>
    <h2 class="tb_post_title"><?php the_title() ?></h2>
    <?php require_once( 'booking_form.php' ) ?>
  </div>
</div>

<?php get_footer(); ?>
