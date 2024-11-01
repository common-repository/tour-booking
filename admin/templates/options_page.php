<?php 

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

$tourbooking_options = tourbooking_get_options();

function tourbooking_colors() {
	foreach ( tourbooking_get_colors() as $key => $option ) {
	?>
		<div class="tourbooking_option <?php echo $key ?>">
			<div class="tourbooking_option_name">
				<?php echo $option['title'] ?>
			</div>
			<div class="tourbooking_option_value">
				<input type="text" name="tourbooking_colors[<?php echo $key ?>]" value="<?php echo $option['color'] ?>" class="tourbooking_color" />
			</div>
		</div>
	<?php
	}
}

function tourbooking_fields( $fields, $options_group ) {
	foreach ( $fields as $key => $option ) {
	?>
		<div class="tourbooking_option <?php echo $key ?>">
			<div class="tourbooking_option_name">
				<?php echo $option['title'] ?>
			</div>
			<div class="tourbooking_option_value">
	<?php
		switch ( $option['type'] ) {
			case 'text':
				echo "<input type='text' name='{$options_group}[$key]' value='{$option['value']}'/>";
				break;
			
			case 'number':
				echo "<input type='number' min='{$option['settings']['min']}' name='{$options_group}[$key]' value='{$option['value']}'/>";
				break;
			
			case 'email':
				echo "<input type='email' name='{$options_group}[$key]' value='{$option['value']}'/>";
				break;
			
			case 'textarea':
				echo "<textarea name='{$options_group}[$key]'>{$option['value']}</textarea>";
				break;
			
			case 'select':
				$current_value = $option['value'];
				echo "<select name='{$options_group}[$key]'>";
				foreach ( $option['settings']['variants'] as $select_value => $select_title ) {
					$selected = $current_value == $select_value ? 'selected' : '';
					echo "<option value='$select_value' $selected >$select_title</option>";
				}
				echo "</select>";
				break;
			
			case 'collection':
				// TODO;
				break;
			
			default:
				break;
		}
	?>
			</div>
		</div>
	<?php
	}	
}
?>

<div class="tourbooking_options_container">
	<h1><?php echo $this->page_title ?></h1>
	<div class="tourbooking_options_subtitle"><?php echo $this->page_subtitle ?></div>

	<div class="tourbooking_options_tabs">
		<ul class="tourbooking_options_tabs_container">
			<li class="tourbooking_options_tab active" options_id="tourbooking_options">
				<?php _e( 'Options', 'tourbooking' ) ?>
			</li>

			<li class="tourbooking_options_tab" options_id="tourbooking_colors">
				<?php _e( 'Colors', 'tourbooking' ) ?>
			</li>

			<li class="tourbooking_options_tab" options_id="tourbooking_messages">
				<?php _e( 'Messages', 'tourbooking' ) ?>
			</li>
		</ul>

		<form method="post" action="options.php" class="tourbooking_option_groups active" id="tourbooking_options">
			<?php settings_fields( 'tourbooking_options' ); ?>
			<?php do_settings_sections( 'tourbooking_options' ); ?>

			<?php submit_button( __( 'Save options' ) ); ?>
			<div class="tourbooking_options_container">
				<?php tourbooking_fields( tourbooking_get_options(), 'tourbooking_options' ) ?>
			</div>
			<?php submit_button( __( 'Save options' ) ); ?>
		</form>	

 		<form method="post" action="options.php" class="tourbooking_option_groups" id="tourbooking_colors">
			<?php settings_fields( 'tourbooking_colors' ); ?>
			<?php do_settings_sections( 'tourbooking_colors' ); ?>

			<?php submit_button( __( 'Save colors' ) ); ?>
			<div class="tourbooking_options_container">
				<?php tourbooking_colors() ?>
			</div>
			<?php submit_button( __( 'Save colors' ) ); ?>
		</form>

		<form method="post" action="options.php" class="tourbooking_option_groups" id="tourbooking_messages">
			<?php settings_fields( 'tourbooking_messages' ); ?>
			<?php do_settings_sections( 'tourbooking_messages' ); ?>

			<?php submit_button( __( 'Save messages' ) ); ?>
			<div class="tourbooking_options_container">
				<?php tourbooking_fields( tourbooking_get_messages(), 'tourbooking_messages' ) ?>
			</div>
			<?php submit_button( __( 'Save messages' ) ); ?>
		</form>	
	</div>

</div>