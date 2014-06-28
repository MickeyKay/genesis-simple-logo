<?php
/**
 * Genesis_Simple_Logo_Customize_Number_Control Class.
 *
 * @package      Genesis Simple Logo
 * @author       Robert Neu http://flagshipwp.com
 * @copyright    Copyright (c) 2014, Flagship
 * @license      GPL-2.0+
 *
 */

// Exit if accessed directly.
if ( ! defined( 'WPINC' ) ) {
	die;
}
// Do nothing if the WP_Customize_Control class doesn't exist.
if ( ! class_exists( 'WP_Customize_Control' ) ) {
	return;
}

/**
 * Creates Customizer control for input[type=number] field
 *
 * @since	1.0.2
 */
class Genesis_Simple_Logo_Customize_Number_Control extends WP_Customize_Control {

	public $type = 'number';

	public function render_content() {
	?>
		<label>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
		</label>
		<input type="number" <?php $this->link(); ?> value="<?php echo intval( $this->value() ); ?>" />
		<span class="suffix">px</span>
	<?php
	}

}
