<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

function tb_load_widgets() {
    register_widget( 'tb_tour_selector' );
}
add_action( 'widgets_init', 'tb_load_widgets' );

class tb_tour_selector extends WP_Widget {
 
	function __construct() {
		parent::__construct( 'tb_tour_selector', __('Tour Selector', 'tourbooking'), 
			array( 'description' => __( 'Selector for tours of different types, cities, etc.', 'tourbooking' ) ) );
	}

	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $args['before_widget'];
		require_once ( 'front/templates/tour_selector.php' );
		echo $args['after_widget'];
	}
	         
	public function form( $instance ) {
		$title = isset( $instance[ 'title' ] ) ? $title = $instance[ 'title' ] : __( 'New title', 'tourbooking' );

		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php 
	}
	     
	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		return $instance;
	}


}