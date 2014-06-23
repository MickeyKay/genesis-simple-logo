<?php
/**
 * Genesis Simple Logo Customizer Options.
 *
 * @package      Genesis Simple Logo
 * @author       Robert Neu http://flagshipwp.com
 * @copyright    Copyright (c) 2014, Flagship
 * @license      GPL-2.0+
 *
 */

// Exit if accessed directly
defined( 'WPINC' ) or die;

if ( ! class_exists( 'Genesis_Customizer_Base' ) ) :
/**
 *
 */
abstract class Genesis_Customizer_Base {

	/**
	 * Define defaults, call the `register` method, add css to head.
	 */
	public function __construct() {

		//** Register new customizer elements
		if ( method_exists( $this, 'register' ) ) {
			add_action( 'customize_register', array( $this, 'register'), 15 );
		} else {
			_doing_it_wrong( 'Genesis_Customizer_Base', __( 'When extending Genesis_Customizer_Base, you must create a register method.', 'genlogo' ) );
		}

		//* Customizer scripts
		if ( method_exists( $this, 'scripts' ) ) {
			add_action( 'customize_preview_init', 'scripts' );
		}

	}

	protected function get_field_name( $name ) {
		return sprintf( '%s[%s]', $this->settings_field, $name );
	}

	protected function get_field_id( $id ) {
		return sprintf( '%s[%s]', $this->settings_field, $id );
	}

	protected function get_field_value( $key ) {
		return genesis_get_option( $key, $this->settings_field );
	}

	function sanitize_text( $input ) {
		return wp_kses_post( force_balance_tags( $input ) );
	}

}
endif; // End class exists check.

if ( class_exists( 'WP_Customize_Control' ) ) :

/**
 * Creates Customizer control for input[type=number] field
 *
 * @since	Theme_Customizer_Boilerplate 1.0
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

endif; // End class exists check.

/**
 * Customize Image Reloaded Class
 *
 * Extend WP_Customize_Image_Control allowing access to uploads made within
 * the same context
 */
class Genesis_Simple_Logo_Image_Control extends WP_Customize_Image_Control {
	/**
	 * Constructor.
	 *
	 * @since 3.4.0
	 * @uses WP_Customize_Image_Control::__construct()
	 *
	 * @param WP_Customize_Manager $manager
	 */
	public function __construct( $manager, $id, $args = array() ) {
		parent::__construct( $manager, $id, $args );
	}

	/**
	 * Search for images within the defined context
	 */
	public function tab_uploaded() {
		$my_context_uploads = get_posts( array(
		    'post_type'  => 'attachment',
		    'meta_key'   => '_wp_attachment_context',
		    'meta_value' => $this->context,
		    'orderby'    => 'post_date',
		    'nopaging'   => true,
		) );
		?>

		<div class="uploaded-target"></div>

		<?php
		if ( empty( $my_context_uploads ) )
		    return;

		foreach ( (array) $my_context_uploads as $my_context_upload ) {
		    $this->print_tab_image( esc_url_raw( $my_context_upload->guid ) );
		}
	}
}

/**
 *
 */
class Genesis_Simple_Logo_Customizer extends Genesis_Customizer_Base {

	/**
	 * Settings field.
	 */
	public $settings_field   = 'genlogo-settings';
	public $control_priority = 0;

	/**
	 *
	 */
	public function register( $wp_customize ) {
		$this->logo( $wp_customize );
	}

	private function logo( $wp_customize ) {

		$settings = genlogo_get_data();

		$wp_customize->add_section(
			'genlogo_custom_logo',
			array(
				'title'          => 'Logo',
				'description'    => 'Add a custom logo.',
				'priority'       => 25,
			)
		);

		$wp_customize->add_setting(
			$this->get_field_name( 'genlogo_logo' ),
			array(
				'default'    => $settings['genlogo_logo'],
				'capability' => 'edit_theme_options',
				'type'       => 'option',
			)
		);

		$wp_customize->add_control(
			new Genesis_Simple_Logo_Image_Control(
				$wp_customize,
				'genlogo_logo',
				array(
					'label'    => __( 'Upload a logo', 'genlogo' ),
					'section'  => 'genlogo_custom_logo',
					'settings' => $this->get_field_name( 'genlogo_logo' ),
					'context'  => 'genlogo_logo',
					'priority' => $this->control_priority++,
				)
			)
		);

		$wp_customize->add_setting(
			$this->get_field_name( 'genlogo_width' ),
			array(
				'default'    => $settings['width'],
				'capability' => 'edit_theme_options',
				'type'       => 'option',
			)
		);

		$wp_customize->add_control(
			new Genesis_Simple_Logo_Customize_Number_Control(
				$wp_customize,
				'genlogo_width',
				array(
					'label'    => __( 'Set Logo Width', 'genlogo' ),
					'section'  => 'genlogo_custom_logo',
					'settings' => $this->get_field_name( 'genlogo_width' ),
					'priority' => $this->control_priority++,
				)
			)
		);

		$wp_customize->add_setting(
			$this->get_field_name( 'genlogo_height' ),
			array(
				'default'    => $settings['height'],
				'capability' => 'edit_theme_options',
				'type'       => 'option',
			)
		);

		$wp_customize->add_control(
			new Genesis_Simple_Logo_Customize_Number_Control(
				$wp_customize,
				'genlogo_height',
				array(
					'label'    => __( 'Set Logo Height', 'genlogo' ),
					'section'  => 'genlogo_custom_logo',
					'settings' => $this->get_field_name( 'genlogo_height' ),
					'priority' => $this->control_priority++,
				)
			)
		);
	}
}

add_action( 'init', 'genlogo_customizer_init' );
/**
 *
 */
function genlogo_customizer_init() {
	new Genesis_Simple_Logo_Customizer;
}
