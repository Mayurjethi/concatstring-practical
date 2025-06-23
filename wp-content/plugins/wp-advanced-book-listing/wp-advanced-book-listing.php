<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.linkedin.com/in/mayur-jethi/
 * @since             1.0.0
 * @package           Wp_Advanced_Book_Listing
 *
 * @wordpress-plugin
 * Plugin Name:       WP Advanced Book Listing
 * Plugin URI:        https://www.linkedin.com/in/mayur-jethi/
 * Description:       Advanced Book Listing wordpress plugin with Custom Filters & AJAX Pagination.
 * Version:           1.0.0
 * Author:            Mayur Jethi
 * Author URI:        https://www.linkedin.com/in/mayur-jethi//
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-advanced-book-listing
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WP_ADVANCED_BOOK_LISTING_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-advanced-book-listing-activator.php
 */
function activate_wp_advanced_book_listing() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-advanced-book-listing-activator.php';
	Wp_Advanced_Book_Listing_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-advanced-book-listing-deactivator.php
 */
function deactivate_wp_advanced_book_listing() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-advanced-book-listing-deactivator.php';
	Wp_Advanced_Book_Listing_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_advanced_book_listing' );
register_deactivation_hook( __FILE__, 'deactivate_wp_advanced_book_listing' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-advanced-book-listing.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_advanced_book_listing() {

	$plugin = new Wp_Advanced_Book_Listing();
	$plugin->run();

}

add_action('save_post_book', 'wab_clear_book_transients');
add_action('deleted_post', 'wab_clear_book_transients');
add_action('trashed_post', 'wab_clear_book_transients');

function wab_clear_book_transients($post_id)
{
	if (get_post_type($post_id) !== 'book') return;
	delete_transient('wab_authors_list');
	delete_transient('wab_price_buckets_50');
}
run_wp_advanced_book_listing();
