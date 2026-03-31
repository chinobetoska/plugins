<?php
/**
 * Extensions Advanced Itinerary Builder Tab Settings.
 *
 * @since v2.2.4
 */

return array(
	'title'  => __( 'Advanced Itinerary Builder', 'wte-advanced-itinerary' ),
	'order'  => 5,
	'id'     => 'extension-advanced-itinerary-builder',
	'fields' => array(
		array(
			'field_type' => 'TAB',
			'tabs'       => array(
				array(
					'title'  => __( 'General', 'wte-advanced-itinerary' ),
					'id'     => 'general',
					'fields' => array(
						array(
							'label'      => __( 'Always Show All Itinerary', 'wte-advanced-itinerary' ),
							'help'       => __( 'Default: All hidden. Enable this option to always expand all itinerary on initial page load.', 'wte-advanced-itinerary' ),
							'field_type' => 'SWITCH',
							'name'       => 'advanced_itinerary.enable_all_itinerary',
						),
					),
				),
				array(
					'title'  => __( 'Sleep Mode Fields', 'wte-advanced-itinerary' ),
					'id'     => 'sleep-mode-fields',
					'fields' => array(
						array(
							'field_type'  => 'FIELD_HEADER',
							'label'       => __( 'Option Text<span class="required">*</span>', 'wte-advanced-itinerary' ),
							'description' => __( 'Field option value to be displayed in Sleep Mode Select Field in trip page. This text will also be displayed in front as a sleeping mode in each itinerary.', 'wte-advanced-itinerary' ),
						),
						array(
							'field_type' => 'SLEEP_MODE',
							'name'       => 'advanced_itinerary.sleep_mode_fields',
						),
						array(
							'field_type' => 'ALERT',
							'content'    => __( 'You can set various sleep modes on particular day\'s trip from above setting. You can add various means of accommodations such as hotel, tent, camping, homestay etc. for specific day.', 'wte-advanced-itinerary' ),
						),
					),
				),
				array(
					'title'  => __( 'Elevation Chart', 'wte-advanced-itinerary' ),
					'id'     => 'elevation-chart',
					'fields' => array(
						array(
							'field_type' => 'ITINERARY_CHART',
							'name'       => 'advanced_itinerary.chart',
						),
					),
				),
				array(
					'title'  => __( 'Additional Itinerary Fields', 'wte-advanced-itinerary' ),
					'id'     => 'additional-itinerary-fields',
					'isNew'  => version_compare( WTEAI_VERSION, '2.2.5', '<' ),
					'fields' => array(
						array(
							'label'      => __( 'Show Itinerary Info', 'wte-advanced-itinerary' ),
							'help'       => __( 'Enable this to show structured day-by-day itinerary details (e.g., Walking Distance, Meals, Activities) for each trip, presented in a clear, organized layout, giving travelers a quick view of daily plans.', 'wte-advanced-itinerary' ),
							'divider'    => true,
							'field_type' => 'SWITCH',
							'name'       => 'advanced_itinerary.enable_itinerary_info',
						),
						array(
							'condition'  => 'advanced_itinerary.enable_itinerary_info === true',
							'label'      => __( 'Itinerary Info Display Position ', 'wte-advanced-itinerary' ),
							'help'       => __( 'Choose whether the itinerary info fields appear below the day title or below the day description.', 'wte-advanced-itinerary' ),
							'field_type' => 'SELECT_BUTTON',
							'name'       => 'advanced_itinerary.info_display_position',
							'divider'    => true,
							'options'    => array(
								array(
									'label' => __( 'Below Title', 'wte-advanced-itinerary' ),
									'value' => 'below_title',
								),
								array(
									'label' => __( 'Below Description', 'wte-advanced-itinerary' ),
									'value' => 'below_description',
								),
							),
						),
						array(
							'condition'  => 'advanced_itinerary.enable_itinerary_info === true',
							'label'      => __( 'Itinerary Info Fields', 'wte-advanced-itinerary' ),
							'field_type' => 'ITINERARY_INFO',
							'name'       => 'advanced_itinerary.info_fields',
						),
					),
				),
			),
		),
	),
);
