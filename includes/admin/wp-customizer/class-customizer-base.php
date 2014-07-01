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

// Exit if accessed directly.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Add the Genesis Customizer Base class if it doesn't already exist.
if ( ! class_exists( 'Genesis_Customizer_Base' ) ) :

/**
 * Genesis Customizer Base class.
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
			_doing_it_wrong( 'Genesis_Customizer_Base', __( 'When extending Genesis_Customizer_Base, you must create a register method.', 'genesis-simple-logo' ) );
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

}

endif; // End class exists check.
