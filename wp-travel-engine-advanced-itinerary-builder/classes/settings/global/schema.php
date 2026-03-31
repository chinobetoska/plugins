<?php
/**
 * Advanced Itinerary Schema.
 *
 * @since v2.2.4
 */

return array(
	'description' => __( 'Advanced Itinerary settings', 'wte-advanced-itinerary' ),
	'type'        => 'object',
	'properties'  => array(
		'enable_all_itinerary'  => array(
			'description' => __( 'Enable All Itinerary', 'wte-advanced-itinerary' ),
			'type'        => 'boolean',
		),
		'sleep_mode_fields'     => array(
			'description' => __( 'Sleep Mode Fields', 'wte-advanced-itinerary' ),
			'type'        => 'array',
			'items'       => array(
				'type' => 'string',
			),
		),
		'chart'                 => array(
			'description' => __( 'Chart', 'wte-advanced-itinerary' ),
			'type'        => 'object',
			'properties'  => array(
				'enable'            => array(
					'description' => __( 'Enable', 'wte-advanced-itinerary' ),
					'type'        => 'boolean',
				),
				'elevation_unit'    => array(
					'description' => __( 'Elevation Unit', 'wte-advanced-itinerary' ),
					'type'        => 'string',
				),
				'enable_x_axis'     => array(
					'description' => __( 'Enable X Axis', 'wte-advanced-itinerary' ),
					'type'        => 'boolean',
				),
				'enable_y_axis'     => array(
					'description' => __( 'Enable Y Axis', 'wte-advanced-itinerary' ),
					'type'        => 'boolean',
				),
				'enable_line_graph' => array(
					'description' => __( 'Enable Line Graph', 'wte-advanced-itinerary' ),
					'type'        => 'boolean',
				),
				'color'             => array(
					'description' => __( 'Color', 'wte-advanced-itinerary' ),
					'type'        => 'string',
				),
				'background_image'  => array(
					'description' => __( 'Background Image', 'wte-advanced-itinerary' ),
					'type'        => 'object',
					'properties'  => array(
						'id'  => array(
							'description' => __( 'ID', 'wte-advanced-itinerary' ),
							'type'        => 'integer',
						),
						'alt' => array(
							'description' => __( 'Alternative String', 'wte-advanced-itinerary' ),
							'type'        => 'string',
						),
						'url' => array(
							'description' => __( 'URL', 'wte-advanced-itinerary' ),
							'type'        => 'string',
						),
					),
				),
			),
		),
		'enable_itinerary_info' => array(
			'description' => __( 'Enable Itinerary Info', 'wte-advanced-itinerary' ),
			'type'        => 'boolean',
		),
		'info_display_position' => array(
			'description' => __( 'Itinerary Info Display Position', 'wte-advanced-itinerary' ),
			'type'        => 'string',
		),
		'info_fields'           => array(
			'description' => __( 'Itinerary Info Fields', 'wte-advanced-itinerary' ),
			'type'        => 'array',
			'items'       => array(
				'type'       => 'object',
				'properties' => array(
					'id'     => array(
						'description' => __( 'ID', 'wte-advanced-itinerary' ),
						'type'        => 'integer',
					),
					'title'  => array(
						'description' => __( 'Title', 'wte-advanced-itinerary' ),
						'type'        => 'string',
					),
					'icon'   => array(
						'description' => __( 'Icon', 'wte-advanced-itinerary' ),
						'type'        => 'string',
					),
					'enable' => array(
						'description' => __( 'Enabled or Not', 'wte-advanced-itinerary' ),
						'type'        => 'boolean',
						'NULL',
						'enum'        => array( true, false, 'NULL' ),
					),
				),
			),
		),
	),
);
