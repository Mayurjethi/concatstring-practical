<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.linkedin.com/in/mayur-jethi/
 * @since      1.0.0
 *
 * @package    Wp_Advanced_Book_Listing
 * @subpackage Wp_Advanced_Book_Listing/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wp_Advanced_Book_Listing
 * @subpackage Wp_Advanced_Book_Listing/includes
 * @author     Mayur Jethi <mayur.jethi@yahoo.com>
 */
class Wp_Advanced_Book_Listing_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		Wp_Advanced_Book_Listing_CPT::register();
		flush_rewrite_rules();
	}

}
