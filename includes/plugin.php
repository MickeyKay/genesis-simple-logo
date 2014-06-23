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

class Genesis_Genesis_Simple_Logo {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 * @var     string
	 */
	const VERSION = '1.0.0';

	/**
	 * Unique identifier for the Genesis Simple Logo plugin.
	 *
	 *
	 * @since    1.0.0
	 * @var      string
	 */
	protected $plugin_slug = 'genlogo';

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
	function __construct() {
		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
		add_action( 'after_switch_theme', array( $this, 'deactivate_if_not_genesis' ) );
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
			define( 'GENLOGO_FILE', dirname( dirname( __FILE__ ) ) . '/genesis-simple-logo.php' );
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

	/**
	 * Include functions and libraries.
	 *
	 *  @since    1.0.0
	 */
	public function includes() {
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
			require_once( GENLOGO_DIR . 'includes/admin/customizer.php' );
		}
		if ( is_admin() ) {
			require_once( GENLOGO_DIR . 'includes/admin/class-settings.php' );
		}
	}
}
