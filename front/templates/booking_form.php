<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

function tourbooking_render_form() {
	foreach ( tourbooking_form_fields() as $field => $options ) {
		switch ( $options['type'] ) {

			case 'checkbox_group':
				echo "<label>{$options['placeholder']}<ul>";
				foreach ( $options['options'] as $value => $placeholder ) {
					echo "
						<li>$placeholder
							<div class='tb_icon $value'></div><input type='checkbox' name='{$field}[$value]' value='{$value}' />
						</li>";
				}
				echo "</ul></label>";
				break;

			case 'datetime':
				echo "
					<label>
						<div>{$options['placeholder']}</div>
						<input type='date' name='{$field}[date]' min='" . date("Y-m-d") . "' {$options['required']}/>
						<input type='time' name='{$field}[time]' {$options['required']}/>
					</label>";
				break;

			case 'hidden':
				echo "<input type='hidden' name='$field' value='{$options['value']}' />";
				break;

			case 'email':
				echo "<label><input type='email' name='$field' placeholder='{$options['placeholder']}' {$options['required']}/></label>";
				break;

			case 'number':
				echo "
					<label>{$options['placeholder']}
						<input type='number' name='$field' min='{$options['min']}' {$options['required']}/>
					</label>";
				break;

			case 'phone':
				echo "<label><input type='phone' name='$field' placeholder='{$options['placeholder']}' {$options['required']}/></label>";
				break;

			case 'text':
				echo "<label><input type='text' name='$field' placeholder='{$options['placeholder']}' {$options['required']}/></label>";
				break;

			case 'textarea':
				echo "<label><textarea name='$field' placeholder='{$options['placeholder']}' {$options['required']}></textarea></label>";
				break;

			default:
				es_dump($field);
				es_dump($options);
				break;
		}
	}
}

?>
<form name="tourbooking_form" class="tb_booking_form" id="tb_booking_form">
	<?php tourbooking_render_form() ?>
	<input type="submit" value="<?php _e( 'Book this tour', 'tourbooking' )?>" />
</form>

<div class="tb_ajax_loader_overlay" id="tb_booking_form_ajax"></div>

<div class="tb_message_overlay" id="tb_message_overlay">
	<div class="tb_message">
		<div class="tb_close"></div>
		<?php echo tourbooking_get_messages( 'booking_screen_message' ) ?>
	</div>
</div>

<div class="tb_message_overlay" id="tb_message_error">
	<div class="tb_message">
		<div class="tb_close"></div>
	</div>
</div>