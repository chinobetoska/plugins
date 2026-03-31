<?php
/**
 * Easy Digital Downloads Plugin Updater
 *
 * @package WP_Travel_Engine_Trip_Fixed_Starting_Dates
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Includes the files needed for the plugin updater.
 *
 * @since 1.0.0
 */
if ( ! class_exists( 'EDD_SL_Plugin_Updater' ) ) {

	include dirname( __FILE__ ) . '/EDD_SL_Plugin_Updater.php';
}

/**
 * Download ID for the product in Easy Digital Downloads.
 *
 * @since 1.0.0
 */
define( 'WP_TRAVEL_ENGINE_TRIP_COUNTDOWN_ITEM_ID', 6695 );

/**
 * Setup the updater for the WTE Trip Fixed Starting Dates Countdown Add-on.
 *
 * @since 1.0.0
 */
function wte_trip_countdown_plugin_updater() {
	if ( ! defined( 'WP_TRAVEL_ENGINE_STORE_URL' ) ) {
		return;
	}

	// retrieve our license key from the DB
	$settings    = get_option( 'wp_travel_engine_license' );
	$license_key = isset( $settings['wte_trip_countdown_license_key'] ) ? esc_attr( $settings['wte_trip_countdown_license_key'] ) : '';

	// setup the updater
	$edd_updater = new EDD_SL_Plugin_Updater(
		WP_TRAVEL_ENGINE_STORE_URL,
		WP_TRAVEL_ENGINE_TRIP_COUNTDOWN_FILE_PATH,
		array(
			'version' => WTE_FSDATES_COUNTDOWN_VERSION,        // current version number
			'license' => $license_key,             // license key (used get_option above to retrieve from DB)
			'item_id' => WP_TRAVEL_ENGINE_TRIP_COUNTDOWN_ITEM_ID,       // ID of the product
			'author'  => 'WP Travel Engine', // author of this plugin
			'beta'    => false,
		)
	);

}
add_action( 'admin_init', 'wte_trip_countdown_plugin_updater', 0 );

/**
 * Add-ons name for plugin license page.
 *
 * @since 1.0.0
 */
function wte_trip_countdown_name( $array ) {
	$array['WP Travel Engine - Trip Fixed Starting Dates Countdown'] = 'wte_trip_countdown';
	return $array;
}
add_filter( 'wp_travel_engine_addons', 'wte_trip_countdown_name' );

/**
 * Add-ons Item ID for plugin license page.
 *
 * @since 1.0.0
 */
function wte_trip_countdown_id( $array ) {
	$array['wte_fixed_starting_dates_countdown'] = WP_TRAVEL_ENGINE_TRIP_COUNTDOWN_ITEM_ID;
	return $array;
}
add_filter( 'wp_travel_engine_addons_id', 'wte_trip_countdown_id' );

/**
 * Add-ons License details for showing updates in plugin license page.
 *
 * @since 1.0.0
 */
function wte_trip_countdown_license( $array ) {
	 $settings   = get_option( 'wp_travel_engine_license' );
	$license_key = isset( $settings['wte_trip_countdown_license_key'] ) ? esc_attr( $settings['wte_trip_countdown_license_key'] ) : '';// setup the updater

	$array[] = array(
		'version' => WTE_FSDATES_COUNTDOWN_VERSION,     // current version number
		'license' => $license_key,   // license key (used get_option above to retrieve from DB)
		'item_id' => WP_TRAVEL_ENGINE_TRIP_COUNTDOWN_ITEM_ID,   // id of this product in EDD
		'author'  => 'WP Travel Engine',  // author of this plugin
		'url'     => home_url(),
	);
	return $array;
}

$settings       = get_option( 'wp_travel_engine_license' );
$license_status = isset( $settings['wte_trip_countdown_license_status'] ) ? esc_attr( $settings['wte_trip_countdown_license_status'] ) : '';

if ( isset( $license_status ) && $license_status == 'valid' ) {
	add_filter( 'wp_travel_engine_licenses', 'wte_trip_countdown_license' );
}
