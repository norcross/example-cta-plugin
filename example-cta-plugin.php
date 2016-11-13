<?php
/**
 * Plugin Name: Example CTA Plugin
 * Plugin URI: https://github.com/norcross/example-cta-plugin
 * Description: An example call to action plugin.
 * Author: Andrew Norcross
 * Author URI: http://reaktivstudios.com/
 * Version: 0.0.1
 * Text Domain: example-cta-plugin
 * Domain Path: languages
 * License: MIT
 * GitHub Plugin URI: https://github.com/norcross/example-cta-plugin
 */

// Set our defined base.
if ( ! defined( 'EXM_CTA_BASE ' ) ) {
	define( 'EXM_CTA_BASE', plugin_basename( __FILE__ ) );
}

// Set our defined directory.
if ( ! defined( 'EXM_CTA_DIR' ) ) {
	define( 'EXM_CTA_DIR', plugin_dir_path( __FILE__ ) );
}

// Set our defined version.
if ( ! defined( 'EXM_CTA_VER' ) ) {
	define( 'EXM_CTA_VER', '0.0.1' );
}

/**
 * Set up and load our class.
 */
class Example_CTA_Core
{

	/**
	 * Load our hooks and filters.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'plugins_loaded',               array( $this, 'textdomain'          )           );
		add_action( 'plugins_loaded',               array( $this, 'load_files'          )           );
	}

	/**
	 * Load textdomain for international goodness.
	 *
	 * @return void
	 */
	public function textdomain() {
		load_plugin_textdomain( 'example-cta-plugin', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Call our files in the appropriate place.
	 *
	 * @return void
	 */
	public function load_files() {

		// Load our helper file.
		require_once( EXM_CTA_DIR . 'lib/helper.php' );

		// Load our back end.
		if ( is_admin() ) {
			require_once( EXM_CTA_DIR . 'lib/admin.php' );
			require_once( EXM_CTA_DIR . 'lib/settings.php' );
			require_once( EXM_CTA_DIR . 'lib/postmeta.php' );
		}

		// Load our front-end.
		if ( ! is_admin() ) {
			require_once( EXM_CTA_DIR . 'lib/display.php' );
		}
	}

	// End the class.
}

// Instantiate our class.
$Example_CTA_Core = new Example_CTA_Core();
$Example_CTA_Core->init();
