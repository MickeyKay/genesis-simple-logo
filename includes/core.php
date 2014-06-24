<?php
/**
 * Core Plugin Functions.
 *
 * @package      Genesis Simple Logo
 * @author       Robert Neu http://flagshipwp.com
 * @copyright    Copyright (c) 2014, Flagship
 * @license      GPL-2.0+
 *
 */

// Exit if accessed directly
defined( 'WPINC' ) or die;

class Genesis_Simple_Logo_Core {

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 * @var      object
	 */
	protected static $instance = null;

	/**
	* Return an instance of this class.
	*
	* @since 1.0.0
	*
	* @return object A single instance of this class.
	*/
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Check whether we are currently viewing the site via the WordPress Customizer.
	 *
	 * @since 1.0.4
	 *
	 * @global $wp_customize Customizer.
	 *
	 * @return boolean Return true if viewing page via Customizer, false otherwise.
	 */
	function is_customizer() {
		global $wp_customize;
		return is_a( $wp_customize, 'WP_Customize_Manager' ) && $wp_customize->is_preview();
	}

	/**
	 * Helper function to make getting the genesis simple logo options less verbose.
	 *
	 * @param  $option the option value to check.
	 * @return $output the returned option value.
	 * @uses   genesis_get_option()
	 * @uses   is_customizer()
	 * @since  1.0.1
	 */
	function get_option( $option, $use_cache = true ) {
		if ( self::is_customizer() ) {
			$use_cache = false;
		}
		$output = genesis_get_option( $option, 'genlogo-settings', $use_cache );
		return $output;
	}

	/**
	 * Helper function to grab the genesis simple logo settings as an array.
	 *
	 * @return $settings the returned option values in an array.
	 * @uses   get_option()
	 * @since  1.0.1
	 */
	function get_data() {
		$settings = array(
			'logo'   => self::get_option( 'genlogo_logo' ),
			'height' => self::get_option( 'genlogo_height' ),
			'width'  => self::get_option( 'genlogo_width' ),
		);
		return $settings;
	}

	/**
	 * Helper function to determine if the user has added any values to be displayed.
	 *
	 * @return bool returns true if we have data, false if we don't.
	 * @uses   get_data()
	 * @since  1.0.1
	 */
	function has_logo() {
		$settings = self::get_data();
		if ( $settings['logo'] ) {
			return true;
		}
		return false;
	}

	/**
	 * Takes an array of new settings, merges them with the old settings, and pushes them into the database.
	 *
	 * @since 1.0.0
	 *
	 * @param string|array $new     New settings. Can be a string, or an array.
	 * @param string       $setting Optional. Settings field name. Default is genlogo-settings.
	 */
	function update_settings( $new = '', $setting = 'genlogo-settings' ) {
		return update_option( $setting, wp_parse_args( $new, get_option( $setting ) ) );
	}

	/**
	 * Deactivate the plugin if the parent theme isn't Genesis.
	 *
	 * @since    1.0.1
	 */
	function deactivate_if_not_genesis(){
		$theme_info = wp_get_theme();

		$genesis_flavors = array(
			'genesis',
			'genesis-trunk',
		);

		if ( ! in_array( $theme_info->Template, $genesis_flavors ) ) {
			deactivate_plugins( GENLOGO_FILE ); // Deactivate ourself
			//wp_die('Sorry, you can\'t activate unless you have installed <a href="http://www.studiopress.com/themes/genesis">Genesis</a>');
		}
	}
}
