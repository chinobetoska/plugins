<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wptravelengine.com/
 * @since      1.0.0
 *
 * @package    Extra_Services_Wp_Travel_Engine
 * @subpackage Extra_Services_Wp_Travel_Engine/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Extra_Services_Wp_Travel_Engine
 * @subpackage Extra_Services_Wp_Travel_Engine/admin
 * @author     WP Travel Engine <info@wptravelengine.com>
 */
class Extra_Services_Wp_Travel_Engine_Admin {

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
	 * Form fields.
	 *
	 * @access private
	 * @var string $fields Form fields in extra services.
	 */
	private $fields = array(
		'extra_service',
		'extra_service_cost',
		'extra_service_desc',
		'extra_service_unit',
	);

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Extra_Services_Wp_Travel_Engine_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Extra_Services_Wp_Travel_Engine_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		$screen = get_current_screen();

		wp_register_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/extra-services-wp-travel-engine-admin.css', array(), $this->version, 'all' );

		if ( $screen->post_type == 'trip' || $screen->post_type == 'booking' || isset( $_GET['page'] ) && $_GET['page'] == 'class-wp-travel-engine-admin.php' || $screen->id == 'trip_page_class-wp-travel-engine-admin' ) {

			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/extra-services-wp-travel-engine-admin.css', array(), $this->version, 'all' );
		}

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Extra_Services_Wp_Travel_Engine_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Extra_Services_Wp_Travel_Engine_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		$screen = get_current_screen();

		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/extra-services-wp-travel-engine-admin.js', array( 'jquery' ), $this->version, true );
		wp_register_script( 'wte-services_edit', plugin_dir_url( __FILE__ ) . 'js/wte-services-edit-admin.js', array( 'jquery' ), $this->version, true );

		$localize_data = array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'apiURL'   => get_rest_url(),
		);

		if ( $screen->post_type == 'trip' || $screen->post_type == 'booking' || isset( $_GET['page'] ) && $_GET['page'] == 'class-wp-travel-engine-admin.php' || $screen->id == 'trip_page_class-wp-travel-engine-admin' ) {
			wp_localize_script(
				$this->plugin_name,
				'wteExtraServices',
				$localize_data
			);
			wp_enqueue_script( $this->plugin_name );
		}
		wp_localize_script( 'wte-services_edit', 'wteExtraServices', $localize_data );

		$assets_path = plugin_dir_path( __FILE__ ) . '../dist/trip-edit-extra-services.asset.php';
		$assets = require_once $assets_path;
		wp_enqueue_script(
			'wptravelengine-trip-edit-extra-services',
			plugin_dir_url( __FILE__ ) . '../dist/trip-edit-extra-services.js',
			array_merge( $assets['dependencies'], array( 'wp-hooks', 'wptravelengine-exports' ) ),
			$assets['version'],
			true
		);
		
	}

	/**
	 * Add extra services in the settings page of the
	 * WP Travel Engine.
	 */
	public function add_extra_services() {
		$wte_settings = get_option( 'wp_travel_engine_settings' );

		// If not settings is found, it will be boolean convert it to array.
		if ( false === $wte_settings ) {
			$wte_settings = array();
		}

		$wte_settings['currency_code'] = isset( $wte_settings['currency_code'] ) ? $wte_settings['currency_code'] : 'USD';
		require_once WTE_EXTRA_SERVICE_PATH . '/admin/partials/extra-services-wp-travel-engine-admin-settings.php';
	}

	public function add_extra_services_tab( $trip_meta_tabs ) {

		unset( $trip_meta_tabs['wpte-extra-services-upsell'] );

		$trip_meta_tabs['wpte-extra-services'] =
			array(
				'tab_label'         => __( 'Extra Services', 'wte-extra-services' ),
				'tab_heading'       => __( 'Extra Services', 'wte-extra-services' ),
				'content_path'      => WTE_EXTRA_SERVICE_PATH . '/admin/partials/extra-services-wp-travel-engine-admin-trip-post.php',
				'callback_function' => 'wpte_edit_trip_tab_extra_service',
				'content_key'       => 'wpte-tab wpte-extra-services',
				'current'           => false,
				'content_loaded'    => false,
				'priority'          => 110,
			);
		return $trip_meta_tabs;
	}

	function wte_extra_services_extensions_tab_call( $sub_tabs ) {
		$sub_tabs['wte_extra_services'] = array(
			'label'        => __( 'Extra Services', 'wte-extra-services' ),
			'content_path' => WTE_EXTRA_SERVICE_PATH . '/admin/partials/extra-services-wp-travel-engine-admin-settings.php',
			'current'      => false,
		);
		return $sub_tabs;
	}

	/**
	 * Add extra services tab.
	 */
	// public function add_extra_services_tab() {
	// require WTE_EXTRA_SERVICE_PATH . '/admin/partials/extra-services-wp-travel-engine-admin-tab.php';
	// }

	/**
	 * Add extra services in the trip post page.
	 */
	function add_extra_services_trips() {
		global $post;
		if ( ! is_object( $post ) && defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			$post_id  = $_POST['post_id'];
			$next_tab = $_POST['next_tab'];
		} else {
			$post_id = $post->ID;
		}

		// Get WP Travel Engine settings.
		$wte_option_settings = get_option( 'wp_travel_engine_settings' );

		// Get trip metas.
		$trip_metas = get_post_meta( $post_id, 'wp_travel_engine_setting', true );

		include WTE_EXTRA_SERVICE_PATH . '/admin/partials/extra-services-wp-travel-engine-admin-trip-post.php';
	}

	/**
	 * Fix the array indices when extra services are deleted from settings page.
	 *
	 * @param mixed $new_value The new value.
	 * @param mixed $old_value The old value.
	 *
	 * @return mixed The modified value.
	 */
	public function fix_array_indices_in_options( $new_value, $old_value ) {

		// Fix the indices of WP Travel Engine Extra Services.
		$new_value = $this->fix_array_indices( $new_value );
		// Return the modified value.
		return $new_value;
	}

	/**
	 * Fix array indices when extra services are deleted from Trip post.
	 *
	 * @param int    $meta_id    ID of metadata entry to update.
	 * @param int    $object_id  Post ID.
	 * @param string $meta_key   Meta key.
	 * @param mixed  $meta_value Meta value. This will be a PHP-serialized string representation of the value if the value is an array, an object, or itself a PHP-serialized string.
	 */
	public function fix_array_indices_in_trip_meta( $meta_id, $object_id, $meta_key, $meta_value ) {

		// Bail early if the post type is not trip.
		if ( 'trip' !== get_post_type( $object_id ) ) {
			return;
		}

		// Bail early if the meta_key is not wp_travel_engine_setting
		if ( 'wp_travel_engine_setting' !== $meta_key ) {
			return;
		}

		// Unserialize meta value.
		$meta_value = maybe_unserialize( $meta_value );
		// Fix array indices.
		$meta_value = $this->fix_array_indices( $meta_value );

		// Remove hook to prevent recursion calls.
		remove_action( 'updated_postmeta', array( $this, 'fix_array_indices_in_trip_meta' ), 10 );

		// Update the post meta.
		$res = update_post_meta( $object_id, $meta_key, $meta_value );

		// Add the hook back.
		add_action( 'updated_postmeta', array( $this, 'fix_array_indices_in_trip_meta' ), 10, 4 );
	}

	/**
	 * Fix array indices.
	 *
	 * From Array ( [0] => apple [2] => mango ) to Array ( [0] => apple [1] => mango )
	 *
	 * @param array $meta_value Array whose indices need to be fixed.
	 */
	private function fix_array_indices( $meta_value ) {
		// Fix the array indices.
		foreach ( $this->fields as $field ) {
			if ( isset( $meta_value[ $field ] ) && is_array( $meta_value[ $field ] ) ) {
				$meta_value[ $field ] = array_values( $meta_value[ $field ] );
			}
		}

		return $meta_value;
	}

	/**
	 * Save extra services in the booking post type.
	 *
	 * @since 1.0.0
	 * @modified 1.0.4 Added 3 hooks to make compatible with currency converter.
	 *
	 * @param int     $post_ID    Post ID.
	 * @param WP_Post $post       Post Object.
	 * @param bool    $update     Whether this is an existing post being updated or not.
	 */
	public function save_extra_services( $post_ID, $post, $update ) {
		// Bail early if there is no trip-id in session.
		global $wte_cart;

		$cart_items = $wte_cart->getItems();

		if ( empty( $cart_items ) ) {
			return;
		}

		foreach ( $cart_items as $key => $cart_item ) {

			$trip_id = $cart_item['trip_id'];

			// Get trip metas.
			$trip_metas = get_post_meta( $trip_id, 'wp_travel_engine_setting', true );

			// Bail early if the trip doesn't have extra services.
			if ( ! isset( $trip_metas['extra_service'] ) ) {
				continue;
			}

			if ( ! isset( $cart_item['trip_extras'] ) || empty( $cart_item['trip_extras'] ) ) {
				continue;
			}

			/**
			 * @since 1.0.4
			 */
			$order_meta_extra_services = apply_filters(
				'wte_es_before_save_extra_services_list',
				$cart_item['trip_extras'],
				$post_ID
			);

			// Remove the hook to prevent from recursive function calls.
			remove_action( 'save_post_booking', array( $this, 'save_extra_services' ), 11 );

			/**
			 * @since 1.0.4
			 */
			do_action( 'wte_es_before_save_extra_services', $post_ID );

			// Update the extra services in booking post type.
			update_post_meta( $post_ID, 'wp_travel_engine_booking_extra_services', $order_meta_extra_services );

			/**
			 * @since 1.0.4
			 */
			do_action( 'wte_es_before_save_extra_services', $post_ID );

			// Add the hook back,
			add_action( 'save_post_booking', array( $this, 'save_extra_services' ), 11, 3 );
		}

	}

	/**
	 * Add extra services in payment email tags.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function add_payment_email_tag() {
		echo '<h3>' . __( 'Extra Services', '' ) . '</h3>';
		echo '<ul>';
		echo '<li>' . __( '<strong>{extra_services}</strong> - Extra services', 'wte-extra-services' ) . '</li>';
		echo '</ul>';
	}

	/**
	 * Replace {extra_services} with extra service  while sending email.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function replace_extra_services_tag( $book_receipt, $booking_id ) {
		global $wte_cart;

		$cart_items = $wte_cart->getItems();

		if ( empty( $cart_items ) ) {
			return;
		}
		foreach ( $cart_items as $key => $cart_item ) {
			// Get the extra services.
			$extra_services_html = 'N/A';
			if ( isset( $cart_item['trip_extras'] ) && ! empty( $cart_item['trip_extras'] ) ) {
				$extra_services_html      = '';
				$total_extra_service_cost = 0.0;

				$code     = wp_travel_engine_get_currency_code();
				$currency = wp_travel_engine_get_currency_symbol();

				foreach ( $cart_item['trip_extras'] as $key => $trip_extra ) {

					$extra_service_cost        = floatval( $trip_extra['qty'] * $trip_extra['price'] );
					$total_extra_service_cost += $extra_service_cost;
					$formated_cost             = wp_travel_engine_get_formated_price_with_currency_code_symbol( $extra_service_cost );
					$extra_services_trip_cost  = wp_travel_engine_get_formated_price_with_currency_code_symbol( $trip_extra['price'] );
					$extra_services_html      .= '<div>';
					$extra_services_html      .= "<span>{$trip_extra['extra_service']}</span>, ";
					$extra_services_html      .= "<span>{$trip_extra['qty']}</span> X ";
					$extra_services_html      .= "<span>{$extra_services_trip_cost}</span> = ";
					$extra_services_html      .= "<span>{$formated_cost}</span>";

				}
				$total_extra_service_cost = wp_travel_engine_get_formated_price_with_currency_code_symbol( $total_extra_service_cost );
				$extra_services_html     .= "<div>Extra Services Cost = {$total_extra_service_cost}</div>";
				$extra_services_html     .= '</div>';
			}
		}

		$book_receipt = str_replace( '{extra_services}', $extra_services_html, $book_receipt );
		return $book_receipt;
	}

	/**
	 * Add array key to update trip meta value.
	 *
	 * @return void
	 */
	public function add_extra_service_array_key( $keys_array ) {
		array_push( $keys_array, 'extra_service' );
		return $keys_array;
	}

}
