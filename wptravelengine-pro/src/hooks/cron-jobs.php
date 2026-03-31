<?php
/**
 * Cron Jobs.
 *
 * @since 6.0.3
 */

add_action( 'wptravelengine_pro_cron_check_version', 'wptravelengine_pro_cron_check_version' );
add_action( 'wptravelengine_pro_cron_license_check', 'wptravelengine_pro_cron_license_check' );
add_action( 'wptravelengine_pro_cron_server_sync', 'wptravelengine_pro_cron_server_sync' );
add_action(
	'init',
	function () {
		if ( ! wp_next_scheduled( 'wptravelengine_pro_cron_check_version' ) ) {
			wp_schedule_event( time(), 'daily', 'wptravelengine_pro_cron_check_version' );
		}

		if ( ! wp_next_scheduled( 'wptravelengine_pro_cron_license_check' ) ) {
			wp_schedule_event( time(), 'daily', 'wptravelengine_pro_cron_license_check' );
		}

		$_sync = false;
		if ( ! wp_next_scheduled( 'wptravelengine_pro_cron_server_sync' ) ) {
			$_sync = true;
			wp_schedule_event( time(), 'weekly', 'wptravelengine_pro_cron_server_sync' );
		}

		if ( 'yes' !== get_option( 'wptravelengine_pro_sync' . WPTRAVELENGINE_PRO_VERSION ) ) {
			$_sync = true;
			update_option( 'wptravelengine_pro_sync' . WPTRAVELENGINE_PRO_VERSION, 'yes' );
		}

		if ( $_sync ) {
			wptravelengine_pro_cron_server_sync();
		}

		if ( 'yes' === get_option( 'wptravelengine_pro_cron_requires_server_sync', 'no' ) ) {
			wp_schedule_single_event( time(), 'wptravelengine_pro_cron_server_sync' );
			update_option( 'wptravelengine_pro_cron_requires_server_sync', 'no' );
		}

		if ( 'yes' === get_option( 'wptravelengine_site_tracking', 'no' ) && ! wp_next_scheduled( 'wptravelengine_pro_cron_server_sync' ) ) {
			wp_schedule_event( time(), 'weekly', 'wptravelengine_pro_cron_server_sync' );
		} elseif ( 'no' === get_option( 'wptravelengine_site_tracking', 'no' ) && wp_next_scheduled( 'wptravelengine_pro_cron_server_sync' ) ) {
			wp_unschedule_event( time(), 'wptravelengine_pro_cron_server_sync' );
		}
	}
);

function wptravelengine_pro_cron_check_version() {
	wptravelengine_pro_get_extensions_version( '', true );
}

/**
 * @since 1.0.15 Enhaned the
 * @return void
 */
function wptravelengine_pro_cron_license_check() {
	WPTravelEnginePro\License::batch_check( true );
}

/**
 * @return void
 * @since 1.0.7
 */
function wptravelengine_pro_cron_server_sync() {
	defined( 'WPTRAVELENGINE_SYNC_SERVER' ) || define( 'WPTRAVELENGINE_SYNC_SERVER', 'https://stats.wptravelengine.com/wp-json/wptravelengine-server/v1/sync' );

	global $wpdb;

	if ( is_multisite() ) {
		$site_url = network_site_url();
	} else {
		$site_url = home_url();
	}

	$tracking_data = array(
		'siteName'              => get_bloginfo( 'name' ),
		'siteURL'               => $site_url,
		'adminEmail'            => get_bloginfo( 'admin_email' ),
		'themeName'             => wp_get_theme()->get( 'Name' ),
		'themeVersion'          => wp_get_theme()->get( 'Version' ),
		'phpVersion'            => phpversion(),
		'wpVersion'             => get_bloginfo( 'version' ),
		'language'              => get_bloginfo( 'language' ),
		'usingSince'            => get_option( 'wptravelengine_since', WP_TRAVEL_ENGINE_VERSION ),
		'wptravelengineVersion' => defined( 'WP_TRAVEL_ENGINE_VERSION' ) ? WP_TRAVEL_ENGINE_VERSION : '',
		'numberOfTrips'         => $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}posts WHERE post_type = 'trip' AND post_status = 'publish'" ),
	);

	$extensions = array();

	$plugins = get_plugins();

	$licenses = json_decode( wptravelengine_pro_get_license_status( '_wptravelengine_license_status', '{}' ) ) ?? new stdClass();

	foreach ( $plugins as $plugin => $plugin_data ) {
		if ( empty( $plugin_data['WTE'] ) ) {
			continue;
		}

		list( $extension_id ) = explode( ':', $plugin_data['WTE'] );

		$license_status = '';
		foreach ( $licenses as $license ) {
			if ( $license->item_id === (int) $extension_id ) {
				$license_status = $license->status ?? $license->license;
			}
		}

		$extension = array(
			'id'             => $extension_id,
			'name'           => $plugin_data['Name'],
			'version'        => $plugin_data['Version'],
			'isActive'       => is_plugin_active( $plugin ),
			'license_status' => $license_status,
		);

		$extensions[] = $extension;
	}

	$tracking_data['extensions'] = $extensions;

	$license_data = wptravelengine_pro_get_license_option( 'wp_travel_engine_license', array() );
	$license_key  = $license_data['wptravelengine-pro'] ?? '';

	$args = array(
		'method'      => 'POST',
		'timeout'     => 45,
		'redirection' => 5,
		'blocking'    => true,
		'headers'     => array(
			'Content-Type'  => 'application/json',
			'Authorization' => 'Bearer ' . $license_key,
		),
		'body'        => wp_json_encode( $tracking_data ),
	);

	$response = wp_remote_post( WPTRAVELENGINE_SYNC_SERVER, $args );

	if ( is_wp_error( $response ) ) {
		function_exists( 'wte_log' ) && wte_log( 'POST request failed: ' . $response->get_error_message() );
		return;
	}

	$response_code = wp_remote_retrieve_response_code( $response );
	$response_data = json_decode( wp_remote_retrieve_body( $response ) );

	if ( 200 === $response_code && 'success' === $response_data->status ) {
		update_option( 'wptravelengine_server_sync_data', $response_data );
	}
}
