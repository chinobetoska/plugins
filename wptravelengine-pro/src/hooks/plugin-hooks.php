<?php
/**
 * Plugin hook.
 */

add_filter( 'wptravelengine_pro_is_active', '__return_true' );

register_activation_hook( WPTRAVELENGINE_PRO_FILE__, 'wptravelengine_pro_is_activated' );
function wptravelengine_pro_is_activated() {
}

register_deactivation_hook( WPTRAVELENGINE_PRO_FILE__, 'wptravelengine_pro_is_deactivated' );
function wptravelengine_pro_is_deactivated() {
	wptravelengine_pro_remove_cron_jobs();
}

/**
 * @return void
 * @since 1.0.6
 */
function wptravelengine_pro_remove_cron_jobs() {
	$timestamp = wp_next_scheduled( 'wptravelengine_pro_cron_check_version' );
	if ( $timestamp ) {
		wp_unschedule_event( $timestamp, 'wptravelengine_pro_cron_check_version' );
	}
	$timestamp = wp_next_scheduled( 'wptravelengine_pro_cron_license_check' );
	if ( $timestamp ) {
		wp_unschedule_event( $timestamp, 'wptravelengine_pro_cron_license_check' );
	}
}
