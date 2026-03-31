<?php

/**
 * Easy Digital Downloads Plugin Updater
 *
 * @package WTE Advanced Itinerary
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
define( 'WTE_ADVANCED_ITINERARY_ITEM_ID', 31567 );

/**
 * Setup the updater for the WTE Advanced Itinerary Add-on.
 *
 * @since 1.0.0
 */
function wte_advanced_itinerary_updater() {

	if ( defined( 'WP_TRAVEL_ENGINE_VERSION' ) && version_compare( WP_TRAVEL_ENGINE_VERSION, '4.3.8', '>=' ) ) {
		return; // Automatically done by core plugin if WTE comment header is present.
	}

	// retrieve our license key from the DB
	$settings    = get_option( 'wp_travel_engine_license' );
	$license_key = isset( $settings['wte_advanced_itinerary_license_key'] ) ? esc_attr( $settings['wte_advanced_itinerary_license_key'] ) : '';

	// setup the updater
	$edd_updater = new EDD_SL_Plugin_Updater(
		WP_TRAVEL_ENGINE_STORE_URL,
		WTEAD_FILE_PATH,
		array(
			'version' => WTEAI_VERSION, // current version number
			'license' => $license_key, // license key (used get_option above to retrieve from DB)
			'item_id' => WTE_ADVANCED_ITINERARY_ITEM_ID, // ID of the product
			'author'  => 'WP Travel Engine', // author of this plugin
			'beta'    => false,
		)
	);
}

add_action( 'admin_init', 'wte_advanced_itinerary_updater', 0 );

/**
 * Add-ons name for plugin license page.
 *
 * @since 1.0.0
 */
function wte_advanced_itinerary_name( $array ) {
	$array['WP Travel Engine - Advanced Itinerary Builder'] = 'wte_advanced_itinerary';
	return $array;
}

add_filter( 'wp_travel_engine_addons', 'wte_advanced_itinerary_name' );

/**
 * Add-ons Item ID for plugin license page.
 *
 * @since 1.0.0
 */
function wte_advanced_itinerary_id( $array ) {
	$array['wte_advanced_itinerary'] = WTE_ADVANCED_ITINERARY_ITEM_ID;
	return $array;
}

add_filter( 'wp_travel_engine_addons_id', 'wte_advanced_itinerary_id' );
