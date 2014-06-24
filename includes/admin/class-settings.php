<?php
/**
 * Genesis Simple Logo Settings
 *
 * @package      Genesis Simple Logo
 * @author       Robert Neu http://flagshipwp.com
 * @copyright    Copyright (c) 2014, Flagship
 * @license      GPL-2.0+
 *
 */

// Exit if accessed directly
defined( 'WPINC' ) or die;

/**
 * Register a metabox and default settings for the Genesis Simple Logo.
 *
 * @package Genesis\Admin
 */
class Genesis_Simple_Logo_Settings extends Genesis_Admin_Boxes {

	/**
	 * Create an archive settings admin menu item and settings page for relevant custom post types.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		$settings_field = 'genlogo-settings';
		$page_id = 'genesis-simple-logo';
		$menu_ops = array(
			'submenu' => array(
				'parent_slug' => 'genesis',
				'page_title'  => __( 'Genesis Simple Logo', 'genlogo' ),
				'menu_title'  => __( 'Add Logo', 'genlogo' )
			)
		);
		$page_ops = array(); //* use defaults

		$default_settings = apply_filters(
			'genlogo_settings_defaults',
			array(
				'genlogo_logo'   => '',
				'genlogo_height' => '',
				'genlogo_width'  => '',
			)
		);

		$this->create( $page_id, $menu_ops, $page_ops, $settings_field, $default_settings );

		add_action( 'genesis_settings_sanitizer_init', array( $this, 'sanitizer_filters' ) );
	}

	/**
	 * Register each of the settings with a sanitization filter type.
	 *
	 * @since 1.0.0
	 *
	 * @uses genesis_add_option_filter() Assign filter to array of settings.
	 *
	 * @see \Genesis_Settings_Sanitizer::add_filter()
	 */
	public function sanitizer_filters() {

		genesis_add_option_filter(
			'absint',
			$this->settings_field,
			array(
				'genlogo_height',
				'genlogo_width',
			)
		);
		genesis_add_option_filter(
			'url',
			$this->settings_field,
			array(
				'genlogo_logo',
			)
		);
	}

	/**
	 * Register Metabox for the Genesis Simple Logo.
	 *
	 * @param string $_genesis_theme_settings_pagehook
	 * @uses  add_meta_box()
	 * @since 1.0.0
	 */
	function metaboxes() {
		add_meta_box( 'genlogo-settings', 'Customize', array( $this, 'settings_box' ), $this->pagehook, 'main', 'high' );
	}

	/**
	 * Create Metabox which links to and explains the WordPress customizer.
	 *
	 * @uses  wp_customize_url()
	 * @since 1.0.0
	 */
	function settings_box() {
		$customizer_link = wp_customize_url( get_stylesheet() );
		echo '<p>';
			_e( 'You can upload a logo and select the height and width by using the WordPress customizer.', 'genlogo' );
		echo '</p>';
		echo '<p>';
			echo '<a class="button" href="' . $customizer_link . '">' . __( 'Add Logo Now', 'genlogo' ) . '</a>';
		echo '</p>';
	}
}
