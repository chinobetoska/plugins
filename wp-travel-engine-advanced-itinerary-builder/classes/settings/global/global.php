<?php
/**
 * Handles overall API functionality of Global settings.
 *
 * @link       https://wptravelengine.com
 * @since      v2.2.4
 * @package    WTE_Advanced_Itinerary
 */

namespace WTE_Advanced_Itinerary\Settings;

use stdClass;
use WP_REST_Request;
use WPTravelEngine\Core\Controllers\RestAPI\V2\Settings;

class Globals {

	/**
	 * Register hooks
	 *
	 * @return void
	 */
	public static function register_hooks() {
		$instance = new self();
		// Global Settings
		add_filter( 'wptravelengine_settings:sub_tabs:extension-advanced-itinerary-builder', array( $instance, 'add_sub_tab' ) );
		add_filter( 'wptravelengine_settings_api_schema', array( $instance, 'global_schema' ), 11, 2 );
		add_filter( 'wptravelengine_rest_prepare_settings', array( $instance, 'prepare_global' ), 11, 3 );
		add_action( 'wptravelengine_api_update_settings', array( $instance, 'update_global' ), 9, 2 );
	}

	/**
	 * Inserts Markup
	 *
	 * @param array $tabs
	 * @return array
	 */
	public function add_sub_tab( array $tabs ): array {
		return require_once __DIR__ . '/builder.php';
	}

	/**
	 * Get global Settings Schemas
	 *
	 * @param array    $schema
	 * @param Settings $settings_controller
	 *
	 * @return array
	 */
	public function global_schema( array $schema, Settings $settings_controller ): array {
		$schema['advanced_itinerary'] = require_once __DIR__ . '/schema.php';
		return $schema;
	}

	/**
	 * Prepares addon Settings.
	 *
	 * @param array           $settings Settings data.
	 * @param WP_REST_Request $request Request object.
	 * @param Settings        $controller Settings controller instance.
	 *
	 * @return array
	 */
	public function prepare_global( array $settings, WP_REST_Request $request, Settings $controller ): array {
		$chart_data = $controller->plugin_settings->get( 'wte_advance_itinerary.chart', array() );

		$chart_bg_img = new stdClass();
		if ( ! empty( $chart_data['bg'] ?? '' ) ) {
			$chart_bg_img = $this->prepare_image_data( $chart_data['bg'] );
		}

		// Additional Itinerary Info Fields.
		$additional_itinerary_info_fields = array();
		foreach ( $controller->plugin_settings->get( 'wte_advance_itinerary.info_fields', array() ) as $itinerary_info_field ) {
			$additional_itinerary_info_fields[] = array(
				'id'        => $itinerary_info_field['id'],
				'field'     => 'wp_editor',
				'trashable' => true,
				'title'     => $itinerary_info_field['title'],
				'icon'      => $itinerary_info_field['icon'],
				'enable'    => $itinerary_info_field['enable'],
			);
		}

		$settings['advanced_itinerary'] = array(
			'enable_all_itinerary'  => wptravelengine_toggled( $controller->plugin_settings->get( 'wte_advance_itinerary.enable_expand_all' ) ),
			'sleep_mode_fields'     => array_column( $controller->plugin_settings->get( 'wte_advance_itinerary.itinerary_sleep_mode_fields', array() ), 'field_text' ),
			'chart'                 => array(
				'enable'            => wptravelengine_replace( $controller->plugin_settings->get( 'wte_advance_itinerary.chart.show', 'yes' ), 'yes', true, false ),
				'elevation_unit'    => (string) $controller->plugin_settings->get( 'wte_advance_itinerary.chart.alt_unit', 'm' ),
				'enable_x_axis'     => wptravelengine_toggled( $chart_data['options']['scales.xAxes.display'] ?? false ),
				'enable_y_axis'     => wptravelengine_toggled( $chart_data['options']['scales.yAxes.display'] ?? false ),
				'enable_line_graph' => wptravelengine_toggled( $chart_data['data']['datasets.data.fill'] ?? false ),
				'color'             => (string) $controller->plugin_settings->get( 'wte_advance_itinerary.chart.data.color', '#147dfe' ),
				'background_image'  => $chart_bg_img,
			),
			'enable_itinerary_info' => wptravelengine_toggled( $controller->plugin_settings->get( 'wte_advance_itinerary.enable_itinerary_info', 'no' ) ),
			'info_display_position' => (string) $controller->plugin_settings->get( 'wte_advance_itinerary.info_display_position', 'below_title' ),
			'info_fields'           => $additional_itinerary_info_fields,
		);

		return $settings;
	}

	/**
	 * Update Addons Settings.
	 *
	 * @param WP_REST_Request $request Request object.
	 * @param Settings        $settings_controller Settings controller instance.
	 *
	 * @return void
	 */
	public function update_global( WP_REST_Request $request, Settings $controller ): void {

		$advanced_itinerary = $request['advanced_itinerary'] ?? array();

		if ( empty( $advanced_itinerary ) ) {
			return;
		}

		if ( isset( $advanced_itinerary['enable_all_itinerary'] ) ) {
			$controller->plugin_settings->set( 'wte_advance_itinerary.enable_expand_all', wptravelengine_replace( $advanced_itinerary['enable_all_itinerary'], true, '1' ) );
		}

		if ( isset( $advanced_itinerary['sleep_mode_fields'] ) ) {
			$controller->plugin_settings->set( 'wte_advance_itinerary.itinerary_sleep_mode_fields', array_map( fn( $val ) => array( 'field_text' => $val ), $advanced_itinerary['sleep_mode_fields'] ) );
		}

		if ( isset( $advanced_itinerary['chart']['enable'] ) ) {
			$controller->plugin_settings->set( 'wte_advance_itinerary.chart.show', wptravelengine_replace( $advanced_itinerary['chart']['enable'], true, 'yes', '1' ) );
		}

		if ( isset( $advanced_itinerary['chart']['elevation_unit'] ) ) {
			$controller->plugin_settings->set( 'wte_advance_itinerary.chart.alt_unit', $advanced_itinerary['chart']['elevation_unit'] );
		}

		$chart_options = $controller->plugin_settings->get( 'wte_advance_itinerary.chart.options', array() );
		if ( isset( $advanced_itinerary['chart']['enable_x_axis'] ) ) {
			$chart_options['scales.xAxes.display'] = wptravelengine_replace( $advanced_itinerary['chart']['enable_x_axis'], true, '1' );
		}

		if ( isset( $advanced_itinerary['chart']['enable_y_axis'] ) ) {
			$chart_options['scales.yAxes.display'] = wptravelengine_replace( $advanced_itinerary['chart']['enable_y_axis'], true, '1' );
		}

		if ( isset( $advanced_itinerary['chart']['enable_x_axis'] ) || isset( $advanced_itinerary['chart']['enable_y_axis'] ) ) {
			$controller->plugin_settings->set( 'wte_advance_itinerary.chart.options', $chart_options );
		}

		$chart_data = $controller->plugin_settings->get( 'wte_advance_itinerary.chart.data', array() );
		if ( isset( $advanced_itinerary['chart']['enable_line_graph'] ) ) {
			$chart_data['datasets.data.fill'] = wptravelengine_replace( $advanced_itinerary['chart']['enable_line_graph'], true, '1' );
		}

		if ( isset( $advanced_itinerary['chart']['color'] ) ) {
			$chart_data['color'] = $advanced_itinerary['chart']['color'];
		}

		if ( isset( $advanced_itinerary['chart']['enable_line_graph'] ) || isset( $advanced_itinerary['chart']['color'] ) ) {
			$controller->plugin_settings->set( 'wte_advance_itinerary.chart.data', $chart_data );
		}

		if ( isset( $advanced_itinerary['chart']['background_image'] ) ) {
			$controller->plugin_settings->set( 'wte_advance_itinerary.chart.bg', $advanced_itinerary['chart']['background_image']['id'] ?? null );
		}

		if ( isset( $advanced_itinerary['enable_itinerary_info'] ) ) {
			$controller->plugin_settings->set( 'wte_advance_itinerary.enable_itinerary_info', wptravelengine_replace( $advanced_itinerary['enable_itinerary_info'], true, 'yes', 'no' ) );
		}

		if ( isset( $advanced_itinerary['info_display_position'] ) ) {
			$controller->plugin_settings->set( 'wte_advance_itinerary.info_display_position', $advanced_itinerary['info_display_position'] );
		}

		// Additional Itinerary Info Fields.
		if ( isset( $advanced_itinerary['info_fields'] ) ) {
			$additional_itinerary_info_fields = array();
			foreach ( $advanced_itinerary['info_fields'] as $itinerary_info_field ) {
				if ( empty( $itinerary_info_field['title'] ) ) {
					$controller->set_bad_request( 'invalid_parameter', __( 'Title is required.', 'wte-advanced-itinerary' ) );
					return;
				}

				$additional_itinerary_info_fields[] = array(
					'id'     => $itinerary_info_field['id'],
					'title'  => $itinerary_info_field['title'],
					'icon'   => $itinerary_info_field['icon'],
					'enable' => $itinerary_info_field['enable'],
				);
			}
			$controller->plugin_settings->set( 'wte_advance_itinerary.info_fields', $additional_itinerary_info_fields );
		}
	}

	/**
	 * repares image data for the given image id.
	 *
	 * @param numeric $id Image id.
	 * @since v2.2.4
	 * @return array|stdClass
	 */
	public function prepare_image_data( $id ) {
		if ( ! wp_get_attachment_metadata( $id ) || ! wp_attachment_is_image( $id ) ) {
			return new stdClass();
		}

		$id  = (int) $id;
		$alt = get_post_meta( $id, '_wp_attachment_image_alt', true );
		$url = wp_get_attachment_image_url( $id, 'full' );
		return compact( 'id', 'alt', 'url' );
	}
}
