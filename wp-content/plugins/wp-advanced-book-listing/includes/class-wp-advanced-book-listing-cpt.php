<?php

/**
 * Register the 'book' custom post type and meta boxes for custom fields.
 *
 * @package    Wp_Advanced_Book_Listing
 * @subpackage Wp_Advanced_Book_Listing/includes
 */

class Wp_Advanced_Book_Listing_CPT
{
     public static function register()
     {
          $labels = [
               'name'                  => _x('Books', 'Post Type General Name', 'wp-advanced-book-listing'),
               'singular_name'         => _x('Book', 'Post Type Singular Name', 'wp-advanced-book-listing'),
               'menu_name'             => __('Books', 'wp-advanced-book-listing'),
               'all_items'             => __('All Books', 'wp-advanced-book-listing'),
               'add_new'               => __('Add New', 'wp-advanced-book-listing'),
               'add_new_item'          => __('Add New Book', 'wp-advanced-book-listing'),
               'edit_item'             => __('Edit Book', 'wp-advanced-book-listing'),
               'new_item'              => __('New Book', 'wp-advanced-book-listing'),
               'view_item'             => __('View Book', 'wp-advanced-book-listing'),
               'search_items'          => __('Search Books', 'wp-advanced-book-listing'),
               'not_found'             => __('No books found', 'wp-advanced-book-listing'),
               'not_found_in_trash'    => __('No books found in Trash', 'wp-advanced-book-listing'),
          ];

          $args = [
               'label'               => __('Books', 'wp-advanced-book-listing'),
               'labels'              => $labels,
               'public'              => true,
               'has_archive'         => true,
               'rewrite'             => ['slug' => 'books'],
               'supports'            => ['title', 'editor', 'thumbnail'],
               'show_in_rest'        => true,
               'menu_icon'           => 'dashicons-book',
          ];

          register_post_type('book', $args);
     }

     // Register meta boxes for custom fields
     public static function add_meta_boxes()
     {
          add_meta_box(
               'wab_book_fields',
               __('Book Details', 'wp-advanced-book-listing'),
               [__CLASS__, 'render_meta_box'],
               'book',
               'normal',
               'high'
          );
     }

     public static function render_meta_box($post)
     {
          // Nonce for security
          wp_nonce_field('wab_save_book_fields', 'wab_book_fields_nonce');

          $author = get_post_meta($post->ID, '_wab_book_author', true);
          $price = get_post_meta($post->ID, '_wab_book_price', true);
          $publish_date = get_post_meta($post->ID, '_wab_book_publish_date', true);

?>
          <p>
               <label for="wab_book_author"><strong><?php esc_html_e('Author Name:', 'wp-advanced-book-listing'); ?></strong></label>
               <input type="text" id="wab_book_author" name="wab_book_author" value="<?php echo esc_attr($author); ?>" class="widefat" />
          </p>
          <p>
               <label for="wab_book_price"><strong><?php esc_html_e('Price ($):', 'wp-advanced-book-listing'); ?></strong></label>
               <input type="number" step="0.01" id="wab_book_price" name="wab_book_price" value="<?php echo esc_attr($price); ?>" class="widefat" />
          </p>
          <p>
               <label for="wab_book_publish_date"><strong><?php esc_html_e('Publish Date:', 'wp-advanced-book-listing'); ?></strong></label>
               <input type="date" id="wab_book_publish_date" name="wab_book_publish_date" value="<?php echo esc_attr($publish_date); ?>" class="widefat" />
          </p>
<?php
     }

     // Save meta box fields
     public static function save_meta_box($post_id)
     {
          // Verify nonce
          if (!isset($_POST['wab_book_fields_nonce']) || !wp_verify_nonce($_POST['wab_book_fields_nonce'], 'wab_save_book_fields')) {
               return;
          }
          // Autosave check
          if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
               return;
          }
          // Permissions check
          if (isset($_POST['post_type']) && 'book' === $_POST['post_type']) {
               if (!current_user_can('edit_post', $post_id)) {
                    return;
               }
          }

          // Sanitize and save fields
          if (isset($_POST['wab_book_author'])) {
               update_post_meta($post_id, '_wab_book_author', sanitize_text_field($_POST['wab_book_author']));
          }
          if (isset($_POST['wab_book_price'])) {
               update_post_meta($post_id, '_wab_book_price', floatval($_POST['wab_book_price']));
          }
          if (isset($_POST['wab_book_publish_date'])) {
               update_post_meta($post_id, '_wab_book_publish_date', sanitize_text_field($_POST['wab_book_publish_date']));
          }
     }
}

// Register hooks
add_action('add_meta_boxes', ['Wp_Advanced_Book_Listing_CPT', 'add_meta_boxes']);
add_action('save_post', ['Wp_Advanced_Book_Listing_CPT', 'save_meta_box']);
