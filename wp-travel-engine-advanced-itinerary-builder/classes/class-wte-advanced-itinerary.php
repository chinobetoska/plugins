<?php
/**
 * Class WTE_Advanced_Itinerary
 */

class WTE_Advanced_Itinerary {

	function __construct() {
		// add_action( 'admin_init', array( $this, 'check_parent_plugin' ) );
		// add_action( 'plugins_loaded', array( $this, 'wte_advanced_itinerary_call' ) );
		define('WTEAI_VERSION', WPTRAVELENGINE_ADVANCED_ITINERARY_VERSION);
		define('WTEAD_FILE_PATH', WPTRAVELENGINE_ADVANCED_ITINERARY_FILE_PATH);
		define('WTEAD_REQUIRES_AT_LEAST', WPTRAVELENGINE_ADVANCED_ITINERARY_REQUIRES_AT_LEAST);

		$this->wte_advanced_itinerary_call();

		add_action(
			'wpte_save_and_continue_additional_meta_data',
			function( $post_id, $request ) {
				if ( ! isset( $request['wte_advanced_itinerary']['advanced_itinerary']['overnight'] ) ) {
					return;
				}
				$location_data = array_filter(
					$request['wte_advanced_itinerary']['advanced_itinerary']['overnight'],
					function( $ld ) {
						return ! empty( $ld['at'] ) && ! empty( $ld['altitude'] );
					}
				);
				update_post_meta( $post_id, 'trip_itinerary_chart_data', wp_unslash( wp_json_encode( $location_data ) ) );
			},
			11,
			2
		);

		add_action(
			'save_post',
			function ( $post_id, $post ) {
				$request = $_REQUEST;
				if ( 'trip' !== $post->post_type || ! isset( $request['wte_advanced_itinerary']['advanced_itinerary']['overnight'] ) ) {
					return;
				}
				$location_data = array_filter(
					$request['wte_advanced_itinerary']['advanced_itinerary']['overnight'],
					function( $ld ) {
						return ! empty( $ld['at'] ) && ! empty( $ld['altitude'] );
					}
				);
				update_post_meta( $post_id, 'trip_itinerary_chart_data', wp_unslash( wp_json_encode( $location_data ) ) );
			},
			11,
			2
		);
	}

	function check_parent_plugin() {
		add_action( 'admin_notices', array( $this, 'show_message_for_parent_plugin' ) );
		if ( is_admin() && current_user_can( 'activate_plugins' ) && ! is_plugin_active( 'wp-travel-engine/wp-travel-engine.php' ) ) {
			deactivate_plugins( plugin_basename( __FILE__ ) );
			if ( isset( $_GET['activate'] ) ) {
				unset( $_GET['activate'] );
			}
		}
	}

	/**
	 * Function to check if parent plugin is enabled
	 */
	function show_message_for_parent_plugin() {
		if ( ! $this->meets_requirements() ) {
			echo '<div class="notice notice-error is-dismissable">';
			echo wp_kses_post( '<p><strong>WP Travel Engine - Advanced Itinerary Builder</strong> requires the <a href="https://wptravelengine.com" target="__blank">WP Travel Engine</a> <b>version - 4.0.0 or later</b> to work. Please install and activate the latest WP Travel Engine plugin first. <b>WP Travel Engine - Advanced Itinerary Builder will be deactivated now.</b></p>' );
			echo '</div>';
			deactivate_plugins( plugin_basename( __FILE__ ) );
		}
	}

	/**
	 * Check if all plugin requirements are met.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if requirements are met, otherwise false.
	 */
	private function meets_requirements() {
		return ( class_exists( 'WP_Travel_Engine' ) && defined( 'WP_TRAVEL_ENGINE_VERSION' ) && version_compare( WP_TRAVEL_ENGINE_VERSION, '4.0.0', '>=' ) );
	}

	/**
	 * Function to enqueue required JS and CSS
	 */
	function wte_advanced_itinerary_call() {
		$this->wte_advanced_itinerary_includes();
	}

	/**
	 * Call Advanced Itinerary Init Class
	 */
	function wte_advanced_itinerary_includes() {
		if ( $this->meets_requirements() ) {
			include WTEAD_CLASSES_DIR . '/class-wte-advanced-itineray-init.php';
			include WTEAD_CLASSES_DIR . '/helper.php';
			if ( is_admin() ) {
				require_once WTEAD_FILE_ROOT_DIR . '/updater/wte-advanced-itinerary-updater.php';
			}
		}
	}

	/**
	 * Execute Plugin.
	 *
	 * @return void
	 */
	public static function execute() {
		new WTE_Advanced_Itinerary();
	}

}
