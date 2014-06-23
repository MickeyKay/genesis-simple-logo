<?php
/**
 * Install functions.
 *
 * @package      Genesis Simple Logo
 * @author       Robert Neu http://flagshipwp.com
 * @copyright    Copyright (c) 2014, Flagship
 * @license      GPL-2.0+
 *
 */

// Exit if accessed directly
defined( 'WPINC' ) or die;

// Grab the plugin file.
$_genlogo_plugin_file = $_genlogo_dir . 'genesis-simple-logo.php';

/**
 * Install
 *
 * Runs on plugin install and checks to make sure Genesis is activated.
 *
 * @since 1.0
 * @return void
 */
function genlogo_install( $_genlogo_plugin_file ) {

	$theme_info = wp_get_theme();

	$genesis_flavors = array(
		'genesis',
		'genesis-trunk',
	);

	if ( ! in_array( $theme_info->Template, $genesis_flavors ) ) {
		deactivate_plugins( $_genlogo_plugin_file ); // Deactivate ourself
		wp_die('Sorry, you can\'t activate unless you have installed <a href="http://www.studiopress.com/themes/genesis">Genesis</a>');
	}
}
register_activation_hook( $_genlogo_plugin_file, 'genlogo_install' );

// Clean up
unset( $_genlogo_plugin_file );
