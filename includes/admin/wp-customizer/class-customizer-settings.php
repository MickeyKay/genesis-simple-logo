<?php
/**
 * Genesis Simple Logo Customizer Class.
 *
 * @package      Genesis Simple Logo
 * @author       Robert Neu http://flagshipwp.com
 * @copyright    Copyright (c) 2014, Flagship
 * @license      GPL-2.0+
 *
 */

// Exit if accessed directly.
defined( 'WPINC' ) or die;

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
				'description'    => 'Add a custom logo. Choose an image less than 400px wide for the best results.',
				'priority'       => 25,
			)
		);

		$wp_customize->add_setting(
			$this->get_field_name( 'genlogo_logo' ),
			array(
				'default'    => $settings['logo'],
				'capability' => 'edit_theme_options',
				'type'       => 'option',
			)
		);

		$wp_customize->add_control(
			new Genesis_Simple_Logo_Image_Control(
				$wp_customize,
				'genlogo_logo',
				array(
					'label'    => __( 'Upload a logo Image', 'genesis-simple-logo' ),
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
					'label'    => __( 'Set Logo Width', 'genesis-simple-logo' ),
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
					'label'    => __( 'Set Logo Height', 'genesis-simple-logo' ),
					'section'  => 'genlogo_custom_logo',
					'settings' => $this->get_field_name( 'genlogo_height' ),
					'priority' => $this->control_priority++,
				)
			)
		);

		$wp_customize->add_setting(
			$this->get_field_name( 'genlogo_margin_vertical' ),
			array(
				'default'    => $settings['margin_vertical'],
				'capability' => 'edit_theme_options',
				'type'       => 'option',
			)
		);

		$wp_customize->add_control(
			new Genesis_Simple_Logo_Customize_Number_Control(
				$wp_customize,
				'genlogo_margin_vertical',
				array(
					'label'    => __( 'Set Top & Bottom Margins', 'genesis-simple-logo' ),
					'section'  => 'genlogo_custom_logo',
					'settings' => $this->get_field_name( 'genlogo_margin_vertical' ),
					'priority' => $this->control_priority++,
				)
			)
		);

		$wp_customize->add_setting(
			$this->get_field_name( 'genlogo_margin_horizontal' ),
			array(
				'default'    => $settings['margin_horizontal'],
				'capability' => 'edit_theme_options',
				'type'       => 'option',
			)
		);

		$wp_customize->add_control(
			new Genesis_Simple_Logo_Customize_Number_Control(
				$wp_customize,
				'genlogo_margin_horizontal',
				array(
					'label'    => __( 'Set Left & Right Margins', 'genesis-simple-logo' ),
					'section'  => 'genlogo_custom_logo',
					'settings' => $this->get_field_name( 'genlogo_margin_horizontal' ),
					'priority' => $this->control_priority++,
				)
			)
		);

		$wp_customize->add_setting(
			$this->get_field_name( 'genlogo_center_logo' ),
			array(
				'default'    => $settings['center'],
				'capability' => 'edit_theme_options',
				'type'       => 'option',
			)
		);

		$choices  = array(
			'mobile' => __( 'Only Center on Mobile Devices', 'genesis-simple-logo' ),
			'always' => __( 'Always Center', 'genesis-simple-logo' ),
			'never'  => __( 'Never Center', 'genesis-simple-logo' ),
		);

		$wp_customize->add_control(
			'genlogo_center_logo',
			array(
				'label'    => __( 'Choose When to Center the Logo', 'genesis-simple-logo' ),
				'section'  => 'genlogo_custom_logo',
				'settings' => $this->get_field_name( 'genlogo_center_logo' ),
				'priority' => $this->control_priority++,
				'type'     => 'select',
				'choices'  => $choices
			)
		);
	}

}
