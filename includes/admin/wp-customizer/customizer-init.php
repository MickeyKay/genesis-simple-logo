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
defined( 'WPINC' ) or die;

add_action( 'init', 'genlogo_customizer_init' );
/**
 * Instantiate the Genesis Simple Logo customizer settings.
 *
 * @since 1.0.5
 */
function genlogo_customizer_init() {
	new Genesis_Simple_Logo_Customizer;
}

add_action( 'customize_controls_enqueue_scripts', 'genlogo_enqueue_customizer_assets' );
/**
 * Add customimzer JS to live-refresh controls when logos are added or removed.
 *
 * @see add_action('customize_preview_init',$func)
 */
function genlogo_enqueue_customizer_assets() {
	wp_enqueue_script(
		'genlogo-customizer',
		GENLOGO_URL . 'assets/js/genlogo-customizer.js',
		array( 'customize-loader', 'jquery' )
	);
}
