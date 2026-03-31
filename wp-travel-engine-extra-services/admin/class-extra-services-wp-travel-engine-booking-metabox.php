<?php

/**
 * Add metaboxes for booking post type.
 *
 * @link       https://wptravelengine.com/
 * @since      1.0.0
 *
 * @package    Extra_Services_Wp_Travel_Engine
 * @subpackage Extra_Services_Wp_Travel_Engine/admin
 */

/**
 * Add metaboxes for booking post type.
 *
 * @package    Extra_Services_Wp_Travel_Engine
 * @subpackage Extra_Services_Wp_Travel_Engine/admin
 * @author     WP Travel Engine <info@wptravelengine.com>
 */
class Extra_Services_Wp_Travel_Engine_Booking_Metabox {

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
	 *
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
	 * @param      string $plugin_name The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		// Initialize.
		$this->init();
	}

	/**
	 * Initialize
	 *
	 * @since 1.0.0
	 */
	private function init() {
		// Intialize hooks.
		$this->init_hooks();

		// Allow 3rd party unhook.
		add_action( 'wte_extra_services_booking_metabox_unhook', $this );
		// add_filter( 'wp_travel_engine_admin_trip_meta_tabs', array($this, 'wtees_add_extra_services_trips') );
	}

	function wtees_add_extra_services_trips() {

		$trip_meta_tabs['wpte-extra-services'] =
		array(
			'tab_label'         => __( 'Extra Services', 'wte-extra-services' ),
			'tab_heading'       => __( 'Extra Services', 'wte-extra-services' ),
			'content_path'      => WTE_EXTRA_SERVICE_PATH . '/admin/partials/extra-services-wp-travel-engine-admin-metabox.php',
			'callback_function' => 'wpte_edit_trip_tab_wpte_extra_services',
			'content_key'       => 'wpte-tab wpte-extra-services',
			'current'           => false,
			'content_loaded'    => false,
			'priority'          => 75,
		);
		return $trip_meta_tabs;

	}
	/**
	 * Initialize hooks.
	 */
	private function init_hooks() {
		// Add metabox.
		// add_action( 'admin_menu', array( $this, 'add_extra_services_metabox' ) );

		if ( version_compare( WP_TRAVEL_ENGINE_VERSION, '5.0.0', '<' ) ) {
			add_action( 'wp_travel_engine_booking_screen_after_personal_details', array( $this, 'display_extra_services_metabox' ) );
		}
	}

	/**
	 * Add metabox to the booking post type.
	 *
	 * @since 1.0.0
	 */
	public function add_extra_services_metabox() {
		add_meta_box(
			'wte_extra_services_metabox',
			__( 'Extra Services', '' ),
			array( $this, 'display_extra_services_metabox' ),
			'booking',
			'normal',
			'default'
		);
	}


	/**
	 * Display content of extra service metabox in booking post type.
	 *
	 * @since 1.0.0
	 */
	public function display_extra_services_metabox() {
		global $post;
		$booked_extra_services = get_post_meta( $post->ID, 'wp_travel_engine_booking_extra_services', true );

		if ( ! empty( $booked_extra_services ) && ! isset( $booked_extra_services['extra_service'] ) ) {

			require_once WTE_EXTRA_SERVICE_PATH . '/admin/partials/extra-services-wp-travel-engine-admin-metabox-new.php';

			return;
		}

		// Bail early if no extra services are found.
		if ( ! isset( $booked_extra_services['extra_service'] ) || 0 === count( $booked_extra_services['extra_service'] ) ) {
			?>
			<div class="wpte-block-wrap">
				<div class="wpte-block">
					<div class="wpte-title-wrap">
						<h4 class="wpte-title"><?php _e( 'Extra Services', 'wte-extra-services' ); ?></h4>
					</div>
					<div class="wpte-block-content">
						<span class="wpte-tooltip"><?php _e( 'Extra Services info not available.', 'wte-extra-services' ); ?></span>
					</div>
				</div>
			</div>
			<?php
			return;
		}

		// Get the WP Travel Engine settings.
		$wte_settings = get_option( 'wp_travel_engine_settings' );

		// Get the currency code.
		$currency_code = 'USD';
		if ( isset( $wte_settings['currency_code'] ) ) {
			$currency_code = $wte_settings['currency_code'];
		}

		$extra_services       = $booked_extra_services['extra_service'];
		$extra_services_count = $booked_extra_services['extra_service_count'];
		$extra_services_cost  = $booked_extra_services['extra_service_cost'];

		// Get number of extra services.
		$num_of_extra_serivices = count( $extra_services );

		$grand_total                     = 0.0;
		$single_extra_service_total_cost = array();
		// Calculate each extra service total.
		for ( $index = 0; $index < $num_of_extra_serivices; $index++ ) {
			$cost = floatval( $extra_services_cost[ $index ] ) * floatval( $extra_services_count[ $index ] );
			array_push( $single_extra_service_total_cost, $cost );
			$grand_total += $cost;
		}

		require_once WTE_EXTRA_SERVICE_PATH . '/admin/partials/extra-services-wp-travel-engine-admin-metabox.php';
	}

}
