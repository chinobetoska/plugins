<?php
/**
 * Handles overall API functionality of Trip Edit settings page
 *
 * @link       https://wptravelengine.com
 * @since      2.2.4
 *
 * @package    WTE_Advanced_Itinerary
 */

namespace WTE_Advanced_Itinerary\Settings;

use WP_REST_Request;
use WPTravelEngine\Core\Controllers\RestAPI\V2\Trip as TripController;

class TripEdit {

	/**
	 * Global settings.
	 *
	 * @var array
	 */
	private $global_settings;

	/**
	 * Register hooks
	 *
	 * @return void
	 */
	public static function register_hooks() {
		$instance                  = new self();
		$instance->global_settings = wptravelengine_settings()->get();
		// add_filter( 'wptravelengine_tripedit:extensions:fields', array( $instance, 'add_trip_edit_fields' ), 11, 2 );
		add_filter( 'wptravelengine_trip_api_schema', array( $instance, 'trip_edit_schema' ), 11, 2 );
		add_filter( 'wptravelengine_rest_prepare_trip', array( $instance, 'trip_edit_prepare' ), 11, 3 );
		add_action( 'wptravelengine_api_update_trip', array( $instance, 'trip_edit_update' ), 9, 3 );
		add_filter( 'wp_travel_engine_admin_trip_meta_tabs', array( $instance, 'trip_fields' ), 11, 3 );
	}

	/**
	 * Add itinerary info fields to itinerary tab in trip edit page.
	 *
	 * @param array $trip_meta_tabs
	 * @return array
	 * @since v2.2.4
	 */
	public function trip_fields( $trip_meta_tabs ): array {
		$advanced_itinerary_settings = $this->global_settings['wte_advance_itinerary'] ?? false;
		$info_enable                 = wptravelengine_toggled( $advanced_itinerary_settings['enable_itinerary_info'] ?? 'no' );
		if ( $info_enable ) {
			$itinerary_infos = null;
			if ( isset( $trip_meta_tabs['wpte-itinerary']['fields'] ) ) {
				foreach ( $trip_meta_tabs['wpte-itinerary']['fields'] as &$field ) {
					if ( ( $field['field']['name'] ?? '' ) === 'itineraries' ) {
						$field['field']['itineraryInfos'] = array();
						$itinerary_infos = &$field['field']['itineraryInfos'];
						break;
					}
				}
			}

			/**
			 * Get itinerary info settings.
			 */
			if ( null !== $itinerary_infos ) {
				$itinerary_info_settings = $advanced_itinerary_settings['info_fields'] ?? false;
				foreach ( $itinerary_info_settings as $info_field ) {
					if ( $info_field['enable'] ) {
						$itinerary_infos[] = array(
							'id'    => $info_field['id'] ?? 0,
							'title' => $info_field['title'] ?? '',
							'icon'  => $info_field['icon'] ?? '',
						);
					}
				}
			}
		}

		return $trip_meta_tabs;
	}

	/**
	 * Get trip edit schema
	 *
	 * @param array          $schema
	 * @param TripController $controller
	 * @return array
	 * @since v2.2.4
	 */
	public function trip_edit_schema( array $schema, TripController $controller ): array {
		$schema['itineraries']['items']['properties'] = array_merge(
			$schema['itineraries']['items']['properties'],
			array(
				'period'         => array(
					'description' => __( 'Itinerary period.', 'wte-advanced-itinerary' ),
					'type'        => 'float',
				),
				'unit'           => array(
					'description' => __( 'Itinerary period unit.', 'wte-advanced-itinerary' ),
					'type'        => 'string',
					'enum'        => array( 'hour', 'minute' ),
				),
				'sleep_mode'     => array(
					'description' => __( 'Itinerary sleep mode.', 'wte-advanced-itinerary' ),
					'type'        => 'object',
					'properties'  => array(
						'field_id'    => array(
							'description' => __( 'Sleep mode field.', 'wte-advanced-itinerary' ),
							'type'        => 'string',
						),
						'description' => array(
							'description' => __( 'Sleep mode description.', 'wte-advanced-itinerary' ),
							'type'        => 'string',
						),
					),
				),
				'meals_included' => array(
					'description' => __( 'Itinerary meals included.', 'wte-advanced-itinerary' ),
					'type'        => 'array',
					'items'       => array(
						'type' => 'string',
					),
				),
				'images'         => array(
					'description' => __( 'Itinerary images.', 'wte-advanced-itinerary' ),
					'type'        => 'array',
					'items'       => array(
						'type'       => 'object',
						'properties' => array(
							'id'  => array(
								'description' => __( 'Image ID.', 'wte-advanced-itinerary' ),
								'type'        => 'integer',
							),
							'alt' => array(
								'description' => __( 'Image alt.', 'wte-advanced-itinerary' ),
								'type'        => 'string',
							),
							'url' => array(
								'description' => __( 'Image URL.', 'wte-advanced-itinerary' ),
								'type'        => 'string',
							),
						),
					),
				),
				'overnights'     => array(
					'description' => __( 'Itinerary overnights.', 'wte-advanced-itinerary' ),
					'type'        => 'object',
					'properties'  => array(
						'location' => array(
							'description' => __( 'Overnight location.', 'wte-advanced-itinerary' ),
							'type'        => 'string',
						),
						'altitude' => array(
							'description' => __( 'Overnight altitude.', 'wte-advanced-itinerary' ),
							'type'        => 'float',
						),
					),
				),
				'info'           => array(
					'description' => __( 'Itinerary info.', 'wte-advanced-itinerary' ),
					'type'        => 'array',
					'items'       => array(
						'type'       => 'object',
						'properties' => array(
							'id'    => array(
								'description' => __( 'Itinerary info ID.', 'wte-advanced-itinerary' ),
								'type'        => 'integer',
							),
							'title' => array(
								'description' => __( 'Itinerary info title.', 'wte-advanced-itinerary' ),
								'type'        => 'string',
							),
							'value' => array(
								'description' => __( 'Itinerary info value.', 'wte-advanced-itinerary' ),
								'type'        => 'string',
							),
						),
					),
				),
			)
		);
		return $schema;
	}

	/**
	 * Prepare trip edit data
	 *
	 * @param array           $data
	 * @param WP_REST_Request $request
	 * @param TripController  $controller
	 * @return array
	 * @since v2.2.4
	 */
	public function trip_edit_prepare( array $data, WP_REST_Request $request, TripController $controller ): array {

		$advanced_itinerary = (array) $controller->trip->search_in_meta( 'wte_advanced_itinerary.advanced_itinerary', array() );
		$all_overnights     = array();
		foreach ( $advanced_itinerary['overnight'] ?? array() as $key => $overnight ) {
			$all_overnights[ $key ] = array(
				'location' => (string) ( $overnight['at'] ?? '' ),
				'altitude' => (float) ( $overnight['altitude'] ?? '' ),
			);
		}
		$img = $controller->trip->search_in_meta( 'wte_advanced_itinerary.advanced_itinerary.itinerary_image', array() );
		foreach ( $advanced_itinerary['itinerary_duration'] ?? array() as $key => $value ) {
			if ( ! isset( $data['itineraries'][ $key - 1 ] ) ) {
				continue;
			}
			$temp_img       = empty( $img[ $key ] ) ? array() : array_values( array_unique( $img[ $key ] ) );
			$images         = array_values(
				array_filter(
					array_map(
						function ( $id ) {
							$id = (int) $id;
							if ( ! wp_attachment_is_image( $id ) ) {
									return null;
							}
							$alt = get_post_meta( $id, '_wp_attachment_image_alt', true );
							$url = wp_get_attachment_image_url( $id, 'full' );

							return compact( 'id', 'alt', 'url' );
						},
						$temp_img
					)
				)
			);
			$period         = (float) ( $advanced_itinerary['itinerary_duration'][ $key ] ?? 0 );
			$meals_included = (array) ( $advanced_itinerary['meals_included'][ $key ] ?? array() );
			$unit           = (string) ( $advanced_itinerary['itinerary_duration_type'][ $key ] ?? 'hour' );
			$sleep_mode     = array(
				'field_id'    => (string) ( $advanced_itinerary['sleep_modes'][ $key ] ?? '' ),
				'description' => (string) ( $advanced_itinerary['itinerary_sleep_mode_description'][ $key ] ?? '' ),
			);
			$info           = $advanced_itinerary['info'][ $key ] ?? array();
			$overnights     = array( (array) $all_overnights[ $key ] ?? array() );

			$data['itineraries'][ $key - 1 ] = array_merge( $data['itineraries'][ $key - 1 ] ?? array(), compact( 'period', 'unit', 'sleep_mode', 'meals_included', 'images', 'overnights', 'info' ) );
		}

		return $data;
	}

	/**
	 * Update trip edit data
	 *
	 * @param WP_REST_Request $request
	 * @param TripController  $controller
	 * @return void
	 * @since v2.2.4
	 */
	public function trip_edit_update( WP_REST_Request $request, TripController $controller ): void {
		if ( isset( $request['itineraries'] ) ) {
			$itineraries         = $request['itineraries'];
			$itinerary_arr_range = range( 1, count( $itineraries ) );
			$imgs                = array_column( $itineraries, 'images' );
			$sleep_modes         = array_column( $itineraries, 'sleep_mode' );
			$overnights          = array_column( $itineraries, 'overnights' );

			$info               = array_map(
				function ( $item ) {
					$trip_info = isset( $item['info'] ) ? $item['info'] : array();
					return wptravelengine_advanced_itinerary_validate_info( $trip_info );
				},
				$itineraries
			);
			$advanced_itinerary = array(
				'itinerary_duration'               => array_combine( $itinerary_arr_range, array_column( $itineraries, 'period' ) ),
				'itinerary_duration_type'          => array_combine( $itinerary_arr_range, array_column( $itineraries, 'unit' ) ),
				'sleep_modes'                      => array_combine( $itinerary_arr_range, array_column( $sleep_modes, 'field_id' ) ),
				'itinerary_sleep_mode_description' => array_combine( $itinerary_arr_range, array_column( $sleep_modes, 'description' ) ),
				'meals_included'                   => array_filter( array_combine( $itinerary_arr_range, array_column( $itineraries, 'meals_included' ) ) ),
				'itinerary_image_max_count'        => array_combine( $itinerary_arr_range, array_map( 'count', $imgs ) ),
				'info'                             => array_combine( $itinerary_arr_range, $info ),
			);

			$chart_data = array();
			foreach ( $itinerary_arr_range as $key => $val ) {
				$advanced_itinerary['itinerary_image'][ $val ] = array_column( $imgs[ $key ] ?? array(), 'id' );
				$advanced_itinerary['overnight'][ $val ]       = array(
					'at'       => $overnights[ $key ][0]['location'] ?? '',
					'altitude' => $overnights[ $key ][0]['altitude'] ?? 0,
				);
				if ( ! empty( $advanced_itinerary['overnight'][ $val ]['at'] ?? '' ) ) {
					$chart_data[ $val ] = $advanced_itinerary['overnight'][ $val ];
				}
			}

			$controller->trip->set_meta( 'wte_advanced_itinerary', array( 'advanced_itinerary' => $advanced_itinerary ) );

			if ( isset( $chart_data ) ) {
				$controller->trip->set_meta( 'trip_itinerary_chart_data', wp_unslash( wp_json_encode( $chart_data, JSON_UNESCAPED_UNICODE ) ) );
			}
		}
	}
}
