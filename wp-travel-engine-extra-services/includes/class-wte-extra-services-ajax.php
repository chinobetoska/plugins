<?php
/**
 * Ajax Calls.
 */

/**
 * WTE_Extra_Services_Ajax
 */
class WTE_Extra_Services_Ajax {

	/**
	 * Initialize.
	 *
	 * @return void
	 */
	public static function init() {
		foreach ( array( 'get_trip_services' ) as $action ) {
			if ( method_exists( __CLASS__, $action ) ) {
				add_action( "wp_ajax_{$action}", array( __CLASS__, $action ) );
				add_action( "wp_ajax_nopriv_{$action}", array( __CLASS__, $action ) );
			}
		}
	}

	/**
	 * Get Trip Services.
	 *
	 * @return void
	 */
	public static function get_trip_services() {
		$trip_services = get_posts(
			array(
				'post_type'      => 'wte-services',
				'posts_per_page' => -1,
				'post_status'    => 'publish',
			)
		);
		wp_send_json_success( array() );
		die;
	}


}

WTE_Extra_Services_Ajax::init();
