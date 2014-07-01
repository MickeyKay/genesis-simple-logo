<?php
/**
 * Core plugin class.
 *
 * @package      Genesis Simple Logo
 * @author       Robert Neu http://flagshipwp.com
 * @copyright    Copyright (c) 2014, Flagship
 * @license      GPL-2.0+
 *
 */

// Exit if accessed directly
defined( 'WPINC' ) or die;

class Genesis_Simple_Logo {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 * @var     string
	 */
	const VERSION = '1.0.8';

	/**
	 * Unique identifier for the Genesis Simple Logo plugin.
	 *
	 *
	 * @since    1.0.0
	 * @var      string
	 */
	protected $plugin_slug = 'genesis-simple-logo';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     1.0.0
	 */
	function run() {
		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
		add_action( 'plugins_loaded', array( $this, 'load_plugin' ) );
	}

	/**
	 * Load all the main plugin functionality and files.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin() {
		self::define_constants();
		self::includes();
		add_action( 'after_switch_theme', array( $this, 'deactivate_if_not_genesis' ) );
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {
		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, basename( plugin_dir_path( dirname( __FILE__ ) ) ) . '/languages/' );
	}

	/**
	 * Define useful constants.
	 *
	 * @since    1.0.0
	 */
	public function define_constants() {
		// Plugin root file.
		if ( ! defined( 'GENLOGO_FILE' ) ) {
			define( 'GENLOGO_FILE', plugin_dir_path( dirname( __FILE__ ) ) . 'genesis-simple-logo.php' );
		}
		// Plugin directory URL.
		if ( ! defined( 'GENLOGO_URL' ) ) {
			define( 'GENLOGO_URL', plugin_dir_url( GENLOGO_FILE ) );
		}
		// Plugin directory path.
		if ( ! defined( 'GENLOGO_DIR' ) ) {
			define( 'GENLOGO_DIR', plugin_dir_path( GENLOGO_FILE ) );
		}
	}

	/**
	 * Include functions and libraries if the parent theme is Genesis.
	 *
	 *  @since    1.0.0
	 *  @return   null End early if the parent theme isn't Genesis.
	 */
	public function includes() {
		if ( ! $this->is_genesis() ) {
			return;
		}
		require_once( GENLOGO_DIR . 'includes/class-resize.php' );
		require_once( GENLOGO_DIR . 'includes/functions.php' );
		require_once( GENLOGO_DIR . 'includes/scripts.php' );
		if ( is_admin() ) {
			require_once( GENLOGO_DIR . 'includes/admin/functions.php' );
		}
		// Include some files after Genesis to avoid conflicts.
		add_action( 'genesis_setup', array( $this, 'include_after_genesis' ) );
	}

	/**
	 * Include the Genesis Simple Logo customizer class.
	 *
	 * @since    1.0.0
	 */
	public function include_after_genesis() {
		if ( genlogo_is_customizer() ) {
			foreach ( glob( GENLOGO_DIR . 'includes/admin/wp-customizer/*.php' ) as $file ) {
				require_once( $file );
			}
		}
		if ( is_admin() ) {
			require_once( GENLOGO_DIR . 'includes/admin/class-settings.php' );
		}
	}

	/**
	 * Hook into WordPress and run functions if the parent theme isn't Genesis.
	 *
	 * @since    1.0.1
	 */
	function deactivate_if_not_genesis() {
		add_action( 'admin_init', array( $this, 'maybe_deactivate' ) );
		add_action( 'admin_notices', array( $this, 'maybe_show_deactivate_notice' ) );
	}

	/**
	 * Helper function to determine whether or not Genesis is the parent theme.
	 *
	 * @since   1.0.4
	 * @uses    wp_get_theme()
	 * @return  bool true if the parent theme is Genesis
	 */
	function is_genesis() {
		$theme_info = wp_get_theme();

		$genesis_flavors = array(
			'genesis',
			'genesis-trunk',
		);

		if ( in_array( $theme_info->Template, $genesis_flavors ) ) {
			return true;
		}
		return false;
	}

	/**
	 * Deactivate the plugin if Genesis isn't the parent theme.
	 *
	 * @since   1.0.4
	 * @uses    is_genesis()
	 */
	function maybe_deactivate() {
		if ( $this->is_genesis() ) {
			return;
		}
		deactivate_plugins( GENLOGO_FILE );
	}

	/**
	 * Add a notice in the admin panel after deactivating the plugin.
	 *
	 * @since   1.0.4
	 * @uses    is_genesis()
	 */
	function maybe_show_deactivate_notice() {
		if ( $this->is_genesis() ) {
			return;
		}
		echo '<div class="updated">';
			echo '<p><strong>Genesis Simple Logo</strong> requires the Genesis Framework. The plugin has been <strong>deactivated</strong>.</p>';
		echo '</div>';
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}
	}
}
