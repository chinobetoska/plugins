<?php
/**
 * All date and pricing migration to new db tables.
 *
 * @package wp-travel-engine/upgrade
 * @since 2.0.4
 */

namespace WTE_EXTRA_SERVICES\Upgrades204;

/**
 * Migrates Trip Extras from option to New post type.
 *
 * @return void
 */
function wte_trip_migration() {
	$option = get_option( 'wp_travel_engine_settings', array() );

	$migrated_on = get_option( 'wte_trip_services_migration_204' );

	if ( ! ! $migrated_on ) {
		return;
	}

	if ( isset( $option[ 'extra_service' ] ) && is_array( $option[ 'extra_service' ] ) && ! $migrated_on ) {
		$indexes       = array_keys( $option[ 'extra_service' ] );
		$services      = $option[ 'extra_service' ];
		$services_cost = $option[ 'extra_service_cost' ];
		$services_unit = $option[ 'extra_service_unit' ];
		$services_desc = $option[ 'extra_service_desc' ];

		$postarr = array();
		foreach ( $indexes as $index ) {
			$postarr[ 'post_title' ]   = $services[ $index ];
			$postarr[ 'post_content' ] = isset( $services_desc[ $index ] ) ? $services_desc[ $index ] : '';
			$postarr[ 'post_status' ]  = 'publish';
			$postarr[ 'post_type' ]    = 'wte-services';

			$meta_input = array();
			foreach ( array( 'cost', 'unit', 'type' ) as $meta ) {
				$service_arr = "services_{$meta}";
				if ( isset( $$service_arr[ $index ] ) ) {
					$meta_input[ 'wte_services' ][ "service_{$meta}" ] = $$service_arr[ $index ];
				} else {
					$meta_input[ 'wte_services' ][ "service_{$meta}" ] = '';
				}
			}
			$postarr[ 'meta_input' ] = $meta_input;
			$service_id              = \wp_insert_post( $postarr, true );
		}
		update_option(
			'option_extra_service_backup',
			array(
				'extra_service'       => $option[ 'extra_service' ],
				'extra_service_title' => $option[ 'extra_service_title' ],
				'extra_service_cost'  => $option[ 'extra_service_cost' ],
				'extra_service_unit'  => $option[ 'extra_service_unit' ],
				'extra_service_desc'  => $option[ 'extra_service_desc' ],
			)
		);
		unset( $option[ 'extra_service' ] );
		unset( $option[ 'extra_service_title' ] );
		unset( $option[ 'extra_service_cost' ] );
		unset( $option[ 'extra_service_unit' ] );
		unset( $option[ 'extra_service_desc' ] );
		update_option( 'wp_travel_engine_settings', $option );
		update_option( 'wte_trip_services_migration_204', WTE_EXTRA_SERVICES_VERSION );
	}
}

add_action( 'admin_init', __NAMESPACE__ . '\wte_trip_migration' );
