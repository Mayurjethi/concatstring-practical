=== WP Advanced Book Listing ===
Contributors: mayurjethi
Donate link: https://www.linkedin.com/in/mayur-jethi//
Tags: books, ajax, custom-post-type, filter, pagination, rest-api
Requires at least: 5.0
Tested up to: 6.5
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A powerful WordPress plugin to showcase books with custom filters, AJAX pagination, and REST API support.

== Description ==

WP Advanced Book Listing is a modern, feature-rich WordPress plugin that lets you create, filter, and display a book catalog with ease. Designed for performance and usability, it includes custom fields, dynamic AJAX filtering, and a developer-friendly REST API.

**Features:**

* Registers a "Books" Custom Post Type (CPT) with custom fields:
    * Author Name
    * Price
    * Publish Date
* Shortcode `[advanced_books]` for displaying books dynamically.
* Advanced filtering:
    * Filter authors by initial (A-Z) and select specific authors
    * Filter by price range (50-100, 100-150, 150-200)
    * Sort by publish date (Newest/Oldest)
* AJAX-based pagination for seamless browsing and better UX.
* Efficient performance:
    * Transient caching of author lists, price buckets, and book list queries.
* Custom REST API endpoint:
    * `/wp-json/books/v1/list` supports all filters and returns both HTML and JSON data.
* Modern, responsive UI compatible with popular themes like Hello Elementor.
* Built for developers and content creators.

**Demo:**

Site name: Animated Hose  
URL: https://animatedhose.s6-tastewp.com  
Username: admin  
Password: ayywUiBbyaM

== Installation ==

1. Upload `wp-advanced-book-listing` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Use the `[advanced_books]` shortcode in any post, page, or widget.
4. Optionally, access the REST API at `/wp-json/books/v1/list`.

== Frequently Asked Questions ==

= How do I add a new book? =

Go to the WordPress admin, look for "Books" in the sidebar, and use "Add New". Fill the custom fields for Author, Price, and Publish Date.

= Can I display books anywhere? =

Yes! Use the `[advanced_books]` shortcode in any post, page, or widget.

= Does it work with Elementor and other themes? =

Yes, the output is responsive and works well in any modern theme, including Hello Elementor.

= How do I use the REST API? =

Send a GET request to `/wp-json/books/v1/list` with query parameters as needed (e.g. `author`, `author_letter`, `price_range`, `sort_by`, `paged`).

== Screenshots ==

1. Books grid with filters and AJAX pagination
2. Book custom post type admin screen
3. REST API JSON output example

== Changelog ==

= 1.0.0 =
* Initial release.
* Books CPT with custom fields.
* Shortcode with filters.
* AJAX pagination.
* Transient caching.
* REST API endpoint.

== Upgrade Notice ==

= 1.0.0 =
First public release with all main features and REST API.

== Arbitrary section ==

This plugin is built using the [WordPress Plugin Boilerplate](https://wppb.me/), following best practices and modern coding standards.

**For support or customizations, connect with [Mayur Jethi](https://www.linkedin.com/in/mayur-jethi/).**

== A brief Markdown Example ==

Ordered list:

1. Register Books CPT
2. Advanced filters (author, price, date)
3. AJAX pagination
4. REST API endpoint
5. Transient caching

Unordered list:

* Developer friendly
* Fast & efficient
* Clean, responsive UI

> Built with care for performance and user experience.

`<?php // Example shortcode: echo do_shortcode('[advanced_books]'); ?>`