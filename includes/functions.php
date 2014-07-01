<?php
/**
 * Misc Functions.
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
 * Check whether we are currently viewing the site via the WordPress Customizer.
 *
 * @since 1.0.4
 *
 * @global $wp_customize Customizer.
 *
 * @return boolean Return true if viewing page via Customizer, false otherwise.
 */
function genlogo_is_customizer() {
	global $wp_customize;
	return is_a( $wp_customize, 'WP_Customize_Manager' ) && $wp_customize->is_preview();
}

/**
 * Helper function to make getting the genesis simple logo options less verbose.
 *
 * @param  $option the option value to check.
 * @return $output the returned option value.
 * @uses   genesis_get_option()
 * @uses   genlogo_is_customizer()
 * @since  1.0.1
 */
function genlogo_get_option( $option, $use_cache = true ) {
	if ( genlogo_is_customizer() ) {
		$use_cache = false;
	}
	$output = genesis_get_option( $option, 'genlogo-settings', $use_cache );
	return $output;
}

/**
 * Helper function to determine if the current theme matches a specified name.
 *
 * @param  $name The name of the theme to check. Can be a string or an array.
 * @return bool true if the current theme matches a string or array of strings.
 * @uses   wp_get_theme()
 * @since  1.0.8
 */
function genlogo_is_theme( $names ) {
	$theme_info = wp_get_theme();
	if ( in_array( $theme_info->Name, (array) $names ) ) {
		return true;
	}
	return false;
}

/**
 * Helper function to grab the genesis simple logo settings as an array.
 *
 * @return $settings the returned option values in an array.
 * @uses   genlogo_get_option()
 * @since  1.0.1
 */
function genlogo_get_data() {
	$settings = array(
		'logo'              => genlogo_get_option( 'genlogo_logo' ),
		'height'            => genlogo_get_option( 'genlogo_height' ),
		'width'             => genlogo_get_option( 'genlogo_width' ),
		'margin_vertical'   => genlogo_get_option( 'genlogo_margin_vertical' ),
		'margin_horizontal' => genlogo_get_option( 'genlogo_margin_horizontal' ),
		'center'            => genlogo_get_option( 'genlogo_center_logo' ),
	);
	return $settings;
}

/**
 * Helper function to determine if the user has added any values to be displayed.
 *
 * @return bool returns true if we have data, false if we don't.
 * @uses   genlogo_get_data()
 * @since  1.0.1
 */
function genlogo_has_logo() {
	$settings = genlogo_get_data();
	if ( ! empty( $settings['logo'] ) ) {
		return true;
	}
	return false;
}

add_action( 'genesis_meta', 'genlogo_force_image' );
/**
 * Display the genesis simple logo content based on user input.
 *
 * @uses   genesis_pre_get_option_
 * @since  1.0.1
 */
function genlogo_force_image() {
	// Force Image Title.
	add_filter( 'genesis_pre_get_option_blog_title', 'genlogo_force_title' );
}

/**
 * Force the image title option if a logo has been added, or the text option if not.
 *
 * @uses   genlogo_has_logo()
 * @since  1.0.1
 */
function genlogo_force_title( $title ) {
	$title = 'text';
	if ( genlogo_has_logo() ) {
		$title = 'image';
	}
	return $title;
}
/**
 * Takes an array of new settings, merges them with the old settings, and pushes them into the database.
 *
 * @since 1.0.0
 *
 * @param string|array $new     New settings. Can be a string, or an array.
 * @param string       $setting Optional. Settings field name. Default is genlogo-settings.
 */
function genlogo_update_settings( $new = '', $setting = 'genlogo-settings' ) {
	return update_option( $setting, wp_parse_args( $new, get_option( $setting ) ) );
}

add_action( 'customize_preview_init', 'genlogo_reset_image_dimensions' );
/**
 * Clear out the width and height settings when the current logo is removed.
 *
 * @since 1.0.0
 *
 * @uses genesis_update_settings()  Merges new settings with old settings and pushes them into the database.
 * @uses genlogo_has_logo()       Checks to see if a logo has been added.
 */
function genlogo_reset_image_dimensions() {
	// Do nothing if the user hasn't added a logo.
	if ( genlogo_has_logo() ) {
		return;
	}
	//* Clear the height and width settings when the logo is removed.
	genlogo_update_settings( array(
		'genlogo_height' => '',
		'genlogo_width'  => '',
	) );
}

add_action( 'customize_preview_init', 'genlogo_get_image_dimensions' );
/**
 * Get the dimensions of the current image and use them as the default width and height options.
 *
 * @since 1.0.0
 *
 * @uses genesis_update_settings()  Merges new settings with old settings and pushes them into the database.
 * @uses genlogo_has_logo()         Checks to see if a logo has been added.
 * @uses genlogo_get_data()         Grabs the plugin settings.
 */
function genlogo_get_image_dimensions() {
	// Do nothing if the user hasn't added a logo.
	if ( ! genlogo_has_logo() ) {
		return;
	}
	$settings = genlogo_get_data();
	// End here if the user has alrady defined height or width settings.
	if ( ! empty( $settings['height'] ) || ! empty( $settings['width'] ) ) {
		return;
	}
	// Get the dimensions of the current logo.
	list( $width, $height, $type, $attr ) = getimagesize( $settings['logo'] );

	//* Update the logo dimensions to match the current logo.
	genlogo_update_settings( array(
		'genlogo_height' => $height,
		'genlogo_width'  => $width,
	) );
}

add_action( 'genesis_setup', 'genlogo_add_simple_logo_support', 20 );
/**
 * Add theme support for Genesis Simple Logo if it hasn't already been enabled in
 * the current child theme. Also adds a new image size to be used for the logos.
 *
 * @todo  Integrate this into the image output somehow. Customizer makes this difficult.
 *
 * @since 1.0.2
 *
 * @uses  current_theme_supports()
 * @uses  add_theme_support()
 * @uses  add_image_size()
 */
function genlogo_add_simple_logo_support() {
	if ( current_theme_supports( 'genesis-simple-logo' ) ) {
		return;
	}
	add_theme_support( 'genesis-simple-logo' );
	add_image_size( 'genesis-simple-logo', 500 );
}

add_action( 'after_setup_theme', 'genlogo_remove_custom_header_support', 20 );
/**
 * Remove support for the custom header feature to avoid confusion on HTML5
 * themes which used it as a logo uploader.
 *
 * @since 1.0.2
 *
 * @uses current_theme_supports()
 * @uses genesis_html5()
 */
function genlogo_remove_custom_header_support() {
	if ( ! current_theme_supports( 'custom-header' ) ) {
		return;
	}
	// Remove theme support for the WordPress custom header function.
	remove_theme_support( 'custom-header' );
}
