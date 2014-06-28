<?php
/*
Plugin Name: Genesis Simple Logo
Description: Lets you easily add a logo to your Genesis website using the WordPress customizer.
Version: 1.0.3
License: GPL version 2 or any later version
Plugin URI: http://flagshipwp.com/plugins/genesis-simple-logo/
Author: Flagship
Author URI: http://flagshipwp.com/
Text Domain: genlogo

==========================================================================

Copyright 2013-2014 Flagship

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

// Exit if accessed directly
defined( 'WPINC' ) or die;

// Grab this directory
$_genlogo_dir = plugin_dir_path( __FILE__ );

// Include our core plugin files.
include( $_genlogo_dir . 'includes/install.php' );
include( $_genlogo_dir . 'includes/plugin.php' );

// Clean up
unset( $_genlogo_dir );

// Handy function for grabbing the plugin instance
function genesis_simple_logo() {
	return new Genesis_Genesis_Simple_Logo;
}

// Initialize the plugin
genesis_simple_logo();
