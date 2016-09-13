<?php
/**
 * Example CTA Plugin - admin functions
 *
 * Contains more generic admin related functions.
 *
 * @package Example CTA Plugin
 */

/**
 * Set up and load our class.
 */
class EXCTA_Admin
{

	/**
	 * Load our hooks and filters.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'admin_enqueue_scripts',        array( $this, 'load_stylesheet'     )           );
		add_action( 'admin_enqueue_scripts',        array( $this, 'load_javascript'     )           );
	}

	/**
	 * Call our stylesheet for laying out the settings page.
	 *
	 * @param  string $hook  The admin page hook being called.
	 *
	 * @return void
	 */
	public function load_stylesheet( $hook ) {

		// Only load on our reading page or a single post editor.
		if ( ! in_array( $hook, array( 'options-reading.php', 'post.php' ) ) ) {
			return;
		}

		// Set a suffix for loading the minified or normal.
		$file   = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? 'examplecta.admin.css' : 'examplecta.admin.min.css';
		$vers   = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? time() : EXM_CTA_VER;

		// Load the CSS file itself.
		wp_enqueue_style( 'example-cta', plugins_url( '/css/' . $file, __FILE__ ), array(), $vers, 'all' );
	}

	/**
	 * Call our JS file for the single post CTAs.
	 *
	 * @param  string $hook  The admin page hook being called.
	 *
	 * @return void
	 */
	public function load_javascript( $hook ) {

		// Only load on a single post editor.
		if ( 'post.php' !== $hook ) {
			return;
		}

		// Set a suffix for loading the minified or normal.
		$file   = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? 'examplecta.admin.js' : 'examplecta.admin.min.js';
		$vers   = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? time() : EXM_CTA_VER;

		// Load the JS file itself.
		wp_enqueue_script( 'example-cta', plugins_url( '/js/' . $file, __FILE__ ) , array( 'jquery' ), $vers, true );
	}

	// End the class.
}

// Instantiate our class.
$EXCTA_Admin = new EXCTA_Admin();
$EXCTA_Admin->init();
