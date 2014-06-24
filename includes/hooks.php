<?php
/**
 * Load JavaScript and CSS
 *
 * @package     Genesis Simple Logo
 * @subpackage  Genesis
 * @copyright   Copyright (c) 2013, Flagship
 * @license     GPL-2.0+
 * @since       1.0.0
 */

// Exit if accessed directly
defined( 'WPINC' ) or die;

class Genesis_Simple_Logo_Hooks extends Genesis_Simple_Logo_Core {

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     1.0.0
	 */
	function __construct() {
		self::hooks();
	}

	function hooks() {
		add_action( 'after_switch_theme', array( $this, 'deactivate_if_not_genesis' ) );
		add_action( 'customize_preview_init', array( $this, 'reset_image_dimensions' ) );
		add_action( 'customize_preview_init', array( $this,'get_image_dimensions' ) );
		add_action( 'after_setup_theme', array( $this, 'remove_custom_header_support' ), 20 );
		add_action( 'genesis_setup', array( $this,'add_simple_logo_support' ), 20 );
		add_action( 'genesis_meta', array( $this, 'filter_blog_title' ) );
	}

	/**
	 * Clear out the width and height settings when the current logo is removed.
	 *
	 * @since 1.0.0
	 *
	 * @uses genesis_update_settings()  Merges new settings with old settings and pushes them into the database.
	 * @uses has_logo()       Checks to see if a logo has been added.
	 */
	function reset_image_dimensions() {
		// Do nothing if the user hasn't added a logo.
		if ( $this->has_logo() ) {
			return;
		}
		//* Clear the height and width settings when the logo is removed.
		$this->update_settings( array(
			'height' => '',
			'width'  => '',
		) );
	}

	/**
	 * Get the dimensions of the current image and use them as the default width and height options.
	 *
	 * @since 1.0.0
	 *
	 * @uses genesis_update_settings()  Merges new settings with old settings and pushes them into the database.
	 * @uses has_logo()         Checks to see if a logo has been added.
	 * @uses get_data()         Grabs the plugin settings.
	 */
	function get_image_dimensions() {
		// Do nothing if the user hasn't added a logo.
		if ( ! $this->has_logo() ) {
			return;
		}
		$settings = $this->get_data();
		// End here if the user has alrady defined height or width settings.
		if ( $settings['height'] || $settings['width'] ) {
			return;
		}
		// Get the dimensions of the current logo.
		list( $width, $height, $type, $attr ) = getimagesize( $settings['logo'] );

		//* Update the logo dimensions to match the current logo.
		$this->update_settings( array(
			'height' => $height,
			'width'  => $width,
		) );
	}

	function remove_custom_header_support() {
		if ( ! current_theme_supports( 'custom-header' ) ) {
			return;
		}
		// Remove theme support for the WordPress custom header function.
		remove_theme_support( 'custom-header' );
	}

	function add_simple_logo_support() {
		if ( current_theme_supports( 'genesis-simple-logo' ) ) {
			return;
		}
		add_theme_support( 'genesis-simple-logo' );
		//add_image_size( $name, $width, $height, $crop );
	}

	/**
	 * Display the genesis simple logo content based on user input.
	 *
	 * @uses   genesis_pre_get_option_
	 * @since  1.0.1
	 */
	function filter_blog_title() {
		// Force Image Title.
		add_filter( 'genesis_pre_get_option_blog_title', array( $this, 'force_title' ) );
	}

	/**
	 * Force the image title option if a logo has been added, or the text option if not.
	 *
	 * @uses   has_logo()
	 * @since  1.0.1
	 */
	function force_title( $title ) {
		$title = 'text';
		if ( $this->has_logo() ) {
			$title = 'image';
		}
		return $title;
	}
}
