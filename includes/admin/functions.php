<?php
/**
 * Genesis Simple Logo Admin Funcitons.
 *
 * @package      Genesis Simple Logo
 * @author       Robert Neu http://flagshipwp.com
 * @copyright    Copyright (c) 2014, Flagship
 * @license      GPL-2.0+
 *
 */

// Exit if accessed directly
defined( 'WPINC' ) or die;

add_action( 'genesis_admin_menu', 'genlogo_add_admin_menus' );
/**
 * Add Genesis top-level item in admin menu.
 *
 * Calls the `genesis_admin_menu hook` at the end - all submenu items should be attached to that hook to ensure
 * correct ordering.
 *
 * @since 1.0.5
 * @return null Returns null if Genesis menu is disabled, or disabled for current user
 */
function genlogo_add_admin_menus() {
	$_genlogo_settings = new Genesis_Simple_Logo_Settings;
	return $_genlogo_settings;
}

add_filter( 'plugin_action_links_' .  plugin_basename( GENLOGO_FILE ), 'genlogo_add_settings_link' );
/**
 * Add a link to the WordPress customizer to the plugin action links.
 *
 * @param  array $links default plugin action links
 * @return array $links modified plugin action links
 * @uses   wp_customize_url()
 * @since  1.0.2
 */
function genlogo_add_settings_link( $links ) {
	$customizer_link = wp_customize_url( get_stylesheet() );
    $settings_link = '<a href="' . $customizer_link . '">' . __( 'Customize', 'genlogo' ) . '</a>';
  	array_push( $links, $settings_link );
  	return $links;
}

add_action( 'genesis_admin_before_metaboxes', 'genlogo_remove_header_metabox' );
/**
 * Remove Header Metabox From Genesis Theme Settings Page.
 *
 * @param  array $hook default plugin action links
 * @uses   remove_meta_box()
 * @since  1.0.2
 */
function genlogo_remove_header_metabox( $hook ) {
	remove_meta_box( 'genesis-theme-settings-header', $hook, 'main' );
}
