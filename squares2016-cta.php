<?php
/**
 * Plugin Name: Squares 2016 Call To Action
 * Plugin URI: https://github.com/norcross/squares2016-cta
 * Description: An example plugin for the WordPress Plugin workshop at SquaresConf 2016.
 * Author: Andrew Norcross
 * Author URI: http://reaktivstudios.com/
 * Version: 0.0.1
 * Text Domain: squares2016-cta
 * Domain Path: languages
 * License: MIT
 * GitHub Plugin URI: https://github.com/norcross/squares2016-cta
 */

// Set our defined base.
if ( ! defined( 'SQS2016_BASE ' ) ) {
	define( 'SQS2016_BASE', plugin_basename( __FILE__ ) );
}

// Set our defined directory.
if ( ! defined( 'SQS2016_DIR' ) ) {
	define( 'SQS2016_DIR', plugin_dir_path( __FILE__ ) );
}

// Set our defined version.
if ( ! defined( 'SQS2016_VER' ) ) {
	define( 'SQS2016_VER', '0.0.1' );
}

/**
 * Set up and load our class.
 */
class SQS2016_Core
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
		load_plugin_textdomain( 'squares2016-cta', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Call our files in the appropriate place.
	 *
	 * @return void
	 */
	public function load_files() {

		// Load our back end.
		if ( is_admin() ) {
			require_once( SQS2016_DIR . 'lib/admin.php' );
			require_once( SQS2016_DIR . 'lib/settings.php' );
			require_once( SQS2016_DIR . 'lib/postmeta.php' );
		}

		// Load our front-end.
		if ( ! is_admin() ) {
			require_once( SQS2016_DIR . 'lib/display.php' );
		}

		// Load our helper file.
		require_once( SQS2016_DIR . 'lib/helper.php' );
	}

	// End the class.
}

// Instantiate our class.
$SQS2016_Core = new SQS2016_Core();
$SQS2016_Core->init();
