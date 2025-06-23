<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.linkedin.com/in/mayur-jethi/
 * @since      1.0.0
 *
 * @package    Wp_Advanced_Book_Listing
 * @subpackage Wp_Advanced_Book_Listing/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wp_Advanced_Book_Listing
 * @subpackage Wp_Advanced_Book_Listing/includes
 * @author     Mayur Jethi <mayur.jethi@yahoo.com>
 */
class Wp_Advanced_Book_Listing_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wp-advanced-book-listing',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
