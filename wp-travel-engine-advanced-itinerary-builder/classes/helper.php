<?php

/**
 * Check if the itinerary info is enabled.
 *
 * @param array $info The itinerary info.
 * @return boolean True if the itinerary info is enabled, false otherwise.
 * @since v2.2.4
 */
function wptravelengine_advanced_itinerary_info_enable() {
	$global_settings = wptravelengine_settings()->get();
	return wptravelengine_toggled( $global_settings['wte_advance_itinerary']['enable_itinerary_info'] ?? false );
}

/**
 * Get the position of the itinerary info fields.
 *
 * @param int $trip_id Trip ID.
 * @return string The position of the itinerary info fields.
 * @since v2.2.4
 */
function wptravelengine_advanced_itinerary_info_position( $trip_id ) {
	$global_settings = wptravelengine_settings()->get();
	return $global_settings['wte_advance_itinerary']['info_display_position'] ?? 'below_title';
}

/**
 * Validate and filter itinerary info fields against global settings.
 *
 * @param array $trip_info Trip info fields to validate.
 * @param bool  $include_icon Whether to include icon in the result.
 * @return array Validated info fields.
 * @since v2.2.4
 */
function wptravelengine_advanced_itinerary_validate_info( array $trip_info, bool $include_icon = false ): array {
	if ( empty( $trip_info ) ) {
		return array();
	}

	$global_settings = wptravelengine_settings()->get();

	$global_infos = $global_settings['wte_advance_itinerary']['info_fields'] ?? array();

	if ( empty( $global_infos ) ) {
		return array();
	}

	$enabled_global_infos = array_filter(
		$global_infos,
		function ( $info ) {
			return ! empty( $info['enable'] ) && ! empty( $info['id'] );
		}
	);

	$trip_info_lookup = array_column( $trip_info, null, 'id' );

	$validated_info = array();

	foreach ( $enabled_global_infos as $global_info ) {
		$info_id = $global_info['id'];

		if ( isset( $trip_info_lookup[ $info_id ] ) ) {
			$trip_info_item = $trip_info_lookup[ $info_id ];

			// Validate that title matches and value is not empty.
			if ( $trip_info_item['title'] === $global_info['title'] && $trip_info_item['value'] !== '' ) {
				$validated_item = array(
					'id'    => $info_id,
					'title' => $global_info['title'],
					'value' => $trip_info_item['value'] ?? '',
				);

				// Include icon if requested.
				if ( $include_icon ) {
					$validated_item['icon'] = $global_info['icon'] ?? '';
				}

				$validated_info[] = $validated_item;
			}
		}
	}

	return $validated_info;
}

/**
 * Get additional itinerary info fields valid from global and trip settings.
 *
 * @param int $trip_id Trip ID.
 * @return array Additional itinerary info fields.
 * @since v2.2.4
 */
function wptravelengine_advanced_itinerary_get_info( $trip_id ): array {
	$itinerary_info = array();

	if ( ! wptravelengine_advanced_itinerary_info_enable() ) {
		return $itinerary_info;
	}

	$trip_settings        = get_post_meta( $trip_id, 'wte_advanced_itinerary', true );
	$trip_itinerary_infos = $trip_settings['advanced_itinerary']['info'] ?? array();

	foreach ( $trip_itinerary_infos as $key => $trip_itinerary_info ) {
		$validated_info = wptravelengine_advanced_itinerary_validate_info( $trip_itinerary_info, true );
		if ( ! empty( $validated_info ) ) {
			$itinerary_info[ $key ] = $validated_info;
		}
	}

	return $itinerary_info;
}
