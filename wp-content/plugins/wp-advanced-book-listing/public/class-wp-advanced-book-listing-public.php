<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.linkedin.com/in/mayur-jethi/
 * @since      1.0.0
 *
 * @package    Wp_Advanced_Book_Listing
 * @subpackage Wp_Advanced_Book_Listing/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wp_Advanced_Book_Listing
 * @subpackage Wp_Advanced_Book_Listing/public
 * @author     Mayur Jethi <mayur.jethi@yahoo.com>
 */
class Wp_Advanced_Book_Listing_Public
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		// Register shortcode
		add_shortcode('advanced_books', array($this, 'shortcode_output'));

		// Enqueue scripts
		add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));

		// AJAX actions
		add_action('wp_ajax_wab_filter_books', array($this, 'ajax_filter_books'));
		add_action('wp_ajax_nopriv_wab_filter_books', array($this, 'ajax_filter_books'));
		add_action('rest_api_init', array($this, 'register_books_rest_endpoint'));
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Advanced_Book_Listing_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Advanced_Book_Listing_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/wp-advanced-book-listing-public.css', array(), $this->version, 'all');

		wp_enqueue_style(
			$this->plugin_name . '-public',
			plugin_dir_url(__FILE__) . 'css/wp-advanced-book-listing-public.css',
			array(),
			$this->version,
			'all'
		);
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Advanced_Book_Listing_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Advanced_Book_Listing_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/wp-advanced-book-listing-public.js', array('jquery'), $this->version, false);

		wp_enqueue_script(
			$this->plugin_name . '-ajax',
			plugin_dir_url(__FILE__) . 'js/wp-advanced-book-listing-ajax.js',
			array('jquery'),
			$this->version,
			true
		);

		// AJAX params (we'll add more for AJAX later)
		wp_localize_script(
			$this->plugin_name . '-ajax',
			'WAB_Books',
			array(
				'ajax_url' => admin_url('admin-ajax.php'),
				'nonce'    => wp_create_nonce('wab_books_filter')
			)
		);
	}


	public function shortcode_output($atts)
	{
		ob_start();
?>
		<div class="wab-books-filters-wrap">
			<form class="wab-books-filters" onsubmit="return false;">
				<div class="wab-filter-group">
					<label for="wab-filter-author-letter">
						<?php esc_html_e('Author Initial', 'wp-advanced-book-listing'); ?>
					</label>
					<select id="wab-filter-author-letter" class="wab-filter-input">
						<option value=""><?php esc_html_e('All', 'wp-advanced-book-listing'); ?></option>
						<?php foreach (range('A', 'Z') as $letter): ?>
							<option value="<?php echo $letter; ?>"><?php echo $letter; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="wab-filter-group">
					<label for="wab-filter-author">
						<?php esc_html_e('Author Name', 'wp-advanced-book-listing'); ?>
					</label>
					<select id="wab-filter-author" class="wab-filter-input">
						<option value=""><?php esc_html_e('All', 'wp-advanced-book-listing'); ?></option>
						<?php foreach ($this->get_distinct_authors() as $author): ?>
							<option value="<?php echo esc_attr($author); ?>"><?php echo esc_html($author); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="wab-filter-group">
					<label for="wab-filter-price">
						<?php esc_html_e('Price', 'wp-advanced-book-listing'); ?>
					</label>
					<select id="wab-filter-price" class="wab-filter-input">
						<option value=""><?php esc_html_e('All', 'wp-advanced-book-listing'); ?></option>
						<?php foreach ($this->get_price_buckets(50) as $bucket): list($min, $max) = $bucket; ?>
							<option value="<?php echo $min . '-' . $max; ?>">
								<?php echo esc_html('$' . $min . ' - $' . $max); ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="wab-filter-group">
					<label for="wab-filter-sort">
						<?php esc_html_e('Sort By', 'wp-advanced-book-listing'); ?>
					</label>
					<select id="wab-filter-sort" class="wab-filter-input">
						<option value="newest"><?php esc_html_e('Newest', 'wp-advanced-book-listing'); ?></option>
						<option value="oldest"><?php esc_html_e('Oldest', 'wp-advanced-book-listing'); ?></option>
					</select>
				</div>
			</form>
		</div>
		<div id="wab-books-list" class="wab-books-list-wrap">
			<div class="wab-loader" style="display:none;">
				<div class="wab-spinner"></div>
			</div>
			<?php echo $this->get_books_list(); ?>
		</div>
		<?php
		return ob_get_clean();
	}

	/** Get unique authors */
	public function get_distinct_authors()
	{
		$transient_key = 'wab_authors_list';
		$authors = get_transient($transient_key);
		if ($authors !== false) {
			return $authors;
		}
		global $wpdb;
		$meta_key = '_wab_book_author';
		$results = $wpdb->get_col(
			$wpdb->prepare("
			   SELECT DISTINCT meta_value 
			   FROM {$wpdb->postmeta}
			   WHERE meta_key = %s
			   AND meta_value != ''
		    ", $meta_key)
		);
		sort($results, SORT_NATURAL | SORT_FLAG_CASE);
		set_transient($transient_key, $results, HOUR_IN_SECONDS); // cache for 1 hour
		return $results;
	}

	/** Get price buckets */
	public function get_price_buckets($gap = 50)
	{
		$transient_key = 'wab_price_buckets_' . intval($gap);
		$buckets = get_transient($transient_key);
		if ($buckets !== false) {
			return $buckets;
		}
		global $wpdb;
		$meta_key = '_wab_book_price';
		$prices = $wpdb->get_col(
			$wpdb->prepare("
			   SELECT meta_value 
			   FROM {$wpdb->postmeta}
			   WHERE meta_key = %s
			   AND meta_value != ''
		    ", $meta_key)
		);
		$prices = array_map('floatval', $prices);
		if (empty($prices)) return [];
		$min = floor(min($prices));
		$max = ceil(max($prices));
		$buckets = [];
		$start = $min;
		while ($start < $max) {
			$end = min($start + $gap, $max);
			$buckets[] = [$start, $end];
			$start = $end;
		}
		set_transient($transient_key, $buckets, HOUR_IN_SECONDS); // cache for 1 hour
		return $buckets;
	}

	/**
	 * Get books list HTML (called by shortcode and AJAX)
	 */
	public function get_books_list($args = array())
	{
		$paged = !empty($args['paged']) ? intval($args['paged']) : 1;
		$author = !empty($args['author']) ? $args['author'] : '';
		$author_letter = !empty($args['author_letter']) ? strtoupper($args['author_letter']) : '';
		$price_range   = !empty($args['price_range']) ? $args['price_range'] : '';
		$sort_by       = !empty($args['sort_by']) ? $args['sort_by'] : 'newest';

		// Build a unique cache key based on filters and page
		$cache_key = 'wab_books_list_' . md5(serialize([
			'paged'         => $paged,
			'author'        => $author,
			'author_letter' => $author_letter,
			'price_range'   => $price_range,
			'sort_by'       => $sort_by,
		]));

		// Attempt to get cached HTML
		$cached = get_transient($cache_key);
		if ($cached !== false) {
			return $cached;
		}

		$meta_query = array('relation' => 'OR'); // OR for author filters

		// Build meta_query for authors
		$author_query = [];
		if ($author) {
			$author_query[] = array(
				'key'     => '_wab_book_author',
				'value'   => $author,
				'compare' => '=',
			);
		}
		if ($author_letter) {
			$author_query[] = array(
				'key'     => '_wab_book_author',
				'value'   => '^' . $author_letter,
				'compare' => 'REGEXP',
			);
		}

		// If both filters set, relation OR, otherwise just single filter or nothing
		if (count($author_query) > 1) {
			$meta_query = array('relation' => 'OR', ...$author_query);
		} elseif (count($author_query) === 1) {
			$meta_query = $author_query;
		} else {
			$meta_query = [];
		}

		// Price range filter (AND on top of author filter(s))
		if ($price_range) {
			list($min, $max) = explode('-', $price_range);
			$price_query = array(
				'key'     => '_wab_book_price',
				'value'   => array(floatval($min), floatval($max)),
				'type'    => 'NUMERIC',
				'compare' => 'BETWEEN',
			);
			// If no author filter
			if (empty($meta_query)) {
				$meta_query[] = $price_query;
			}
			// If meta_query already has filters, wrap with AND
			else {
				$meta_query = array(
					'relation' => 'AND',
					$meta_query,
					$price_query
				);
			}
		}

		// Sorting
		$orderby = 'meta_value';
		$meta_key = '_wab_book_publish_date';
		$order = ($sort_by === 'oldest') ? 'ASC' : 'DESC';

		$query_args = array(
			'post_type'      => 'book',
			'post_status'    => 'publish',
			'posts_per_page' => 6,
			'paged'          => $paged,
			'meta_query'     => $meta_query,
			'orderby'        => 'meta_value',
			'meta_key'       => $meta_key,
			'order'          => $order,
		);

		$books = new WP_Query($query_args);

		ob_start();
		if ($books->have_posts()) {
			echo '<div class="wab-books-grid">';
			while ($books->have_posts()) : $books->the_post();
				$author_val = get_post_meta(get_the_ID(), '_wab_book_author', true);
				$price = get_post_meta(get_the_ID(), '_wab_book_price', true);
				$publish_date = get_post_meta(get_the_ID(), '_wab_book_publish_date', true);
		?>
				<div class="wab-book-item">
					<?php if (has_post_thumbnail()): ?>
						<div class="wab-book-thumb"><?php the_post_thumbnail('medium'); ?></div>
					<?php endif; ?>
					<h3 class="wab-book-title"><?php the_title(); ?></h3>
					<div class="wab-book-meta">
						<span class="wab-book-author"><?php echo esc_html($author_val); ?></span><br>
						<span class="wab-book-price"><?php echo esc_html($price ? '$' . number_format($price, 2) : ''); ?></span><br>
						<span class="wab-book-date"><?php echo esc_html($publish_date); ?></span>
					</div>
				</div>
<?php
			endwhile;
			echo '</div>';

			// Pagination (basic, for now)
			$max_pages = $books->max_num_pages;
			if ($max_pages > 1) {
				echo '<div class="wab-books-pagination">';
				for ($i = 1; $i <= $max_pages; $i++) {
					echo '<a href="#" class="wab-books-page' . ($i == $paged ? ' active' : '') . '" data-page="' . $i . '">' . $i . '</a>';
				}
				echo '</div>';
			}
		} else {
			esc_html_e('No books found.', 'wp-advanced-book-listing');
		}
		wp_reset_postdata();
		$html = ob_get_clean();

		// Store to transient for 5 minutes
		set_transient($cache_key, $html, 5 * MINUTE_IN_SECONDS);

		return $html;
	}

	public function ajax_filter_books()
	{
		check_ajax_referer('wab_books_filter', 'nonce');
		$paged         = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$author        = isset($_POST['author']) ? sanitize_text_field($_POST['author']) : '';
		$author_letter = isset($_POST['author_letter']) ? sanitize_text_field($_POST['author_letter']) : '';
		$price_range   = isset($_POST['price_range']) ? sanitize_text_field($_POST['price_range']) : '';
		$sort_by       = isset($_POST['sort_by']) ? sanitize_text_field($_POST['sort_by']) : 'newest';

		echo $this->get_books_list(array(
			'paged'         => $paged,
			'author'        => $author,
			'author_letter' => $author_letter,
			'price_range'   => $price_range,
			'sort_by'       => $sort_by,
		));
		wp_die();
	}

	/**
	 * Register REST API endpoint for books list.
	 */
	public function register_books_rest_endpoint()
	{
		register_rest_route('books/v1', '/list', array(
			'methods'  => 'GET',
			'callback' => array($this, 'rest_books_list'),
			'permission_callback' => '__return_true', // Public endpoint
			'args'     => array(
				'author'        => array('sanitize_callback' => 'sanitize_text_field'),
				'author_letter' => array('sanitize_callback' => 'sanitize_text_field'),
				'price_range'   => array('sanitize_callback' => 'sanitize_text_field'),
				'sort_by'       => array('sanitize_callback' => 'sanitize_text_field'),
				'paged'         => array('sanitize_callback' => 'absint'),
			),
		));
	}

	/**
	 * REST API callback: return HTML and data array for books.
	 */
	public function rest_books_list($request)
	{
		$args = array(
			'paged'         => $request->get_param('paged') ?: 1,
			'author'        => $request->get_param('author'),
			'author_letter' => $request->get_param('author_letter'),
			'price_range'   => $request->get_param('price_range'),
			'sort_by'       => $request->get_param('sort_by'),
		);
		// HTML output (with caching)
		$html = $this->get_books_list($args);

		// Structured array output (no HTML)
		$books_data = array();
		$query = $this->build_books_query($args);
		$books = new WP_Query($query);
		if ($books->have_posts()) {
			while ($books->have_posts()) : $books->the_post();
				$books_data[] = array(
					'id'           => get_the_ID(),
					'title'        => get_the_title(),
					'author'       => get_post_meta(get_the_ID(), '_wab_book_author', true),
					'price'        => get_post_meta(get_the_ID(), '_wab_book_price', true),
					'publish_date' => get_post_meta(get_the_ID(), '_wab_book_publish_date', true),
					'thumbnail'    => get_the_post_thumbnail_url(get_the_ID(), 'medium'),
					'permalink'    => get_permalink(),
				);
			endwhile;
			wp_reset_postdata();
		}

		return rest_ensure_response(array(
			'html'  => $html,
			'books' => $books_data,
		));
	}

	/**
	 * Helper: Build WP_Query args from filters (for both HTML and REST use)
	 */
	public function build_books_query($args = array())
	{
		$paged = !empty($args['paged']) ? intval($args['paged']) : 1;
		$author = !empty($args['author']) ? $args['author'] : '';
		$author_letter = !empty($args['author_letter']) ? strtoupper($args['author_letter']) : '';
		$price_range   = !empty($args['price_range']) ? $args['price_range'] : '';
		$sort_by       = !empty($args['sort_by']) ? $args['sort_by'] : 'newest';

		$meta_query = array('relation' => 'OR'); // OR for author filters

		// Build meta_query for authors
		$author_query = [];
		if ($author) {
			$author_query[] = array(
				'key'     => '_wab_book_author',
				'value'   => $author,
				'compare' => '=',
			);
		}
		if ($author_letter) {
			$author_query[] = array(
				'key'     => '_wab_book_author',
				'value'   => '^' . $author_letter,
				'compare' => 'REGEXP',
			);
		}

		// If both filters set, relation OR, otherwise just single filter or nothing
		if (count($author_query) > 1) {
			$meta_query = array('relation' => 'OR', ...$author_query);
		} elseif (count($author_query) === 1) {
			$meta_query = $author_query;
		} else {
			$meta_query = [];
		}

		// Price range filter (AND on top of author filter(s))
		if ($price_range) {
			list($min, $max) = explode('-', $price_range);
			$price_query = array(
				'key'     => '_wab_book_price',
				'value'   => array(floatval($min), floatval($max)),
				'type'    => 'NUMERIC',
				'compare' => 'BETWEEN',
			);
			// If no author filter
			if (empty($meta_query)) {
				$meta_query[] = $price_query;
			}
			// If meta_query already has filters, wrap with AND
			else {
				$meta_query = array(
					'relation' => 'AND',
					$meta_query,
					$price_query
				);
			}
		}

		// Sorting
		$orderby = 'meta_value';
		$meta_key = '_wab_book_publish_date';
		$order = ($sort_by === 'oldest') ? 'ASC' : 'DESC';

		$query_args = array(
			'post_type'      => 'book',
			'post_status'    => 'publish',
			'posts_per_page' => 6,
			'paged'          => $paged,
			'meta_query'     => $meta_query,
			'orderby'        => 'meta_value',
			'meta_key'       => $meta_key,
			'order'          => $order,
		);

		return $query_args;
	}
}
