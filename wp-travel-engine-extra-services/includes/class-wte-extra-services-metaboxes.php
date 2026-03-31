<?php

/**
 * Metaboxes controller.
 *
 * @since 2.0.4
 */


class WTE_Extra_Services_metaboxes {



	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		// add_action( 'save_post_wte-services', array( $this, 'save_meta' ), 11, 2 );
		add_action( 'save_post', array( $this, 'save_meta' ), 11, 2 );
	}
	
	/**
	 * Per tag label
	 *
	 * @since 2.1.2
	 */
	public static function get_service_unit_labels() {
		$labels = array(
			'unit'     => array(
				'label'               => __( 'Unit', 'wte-extra-services' ),
				'select_option_label' => __( 'Per Unit', 'wte-extra-services' ),
			),
			'traveler' => array(
				'label'               => __( 'Traveler', 'wte-extra-services' ),
				'select_option_label' => __( 'Per Traveler', 'wte-extra-services' ),
			),
		);

		return apply_filters( 'wte_es_service_unit_labels', $labels );
	}

	public function add_meta_boxes() {
		add_meta_box(
			'wte_extra_services',
			__( 'Extra Services', 'wte-extra-services' ),
			function () {
				function wte_es_show( $current, $saved ) {
					if ( $current !== $saved ) {
						echo ' style="display:none"';
					}
				}
				function wte_es_readonly( $current, $saved ) {
					if ( $current === $saved ) {
						echo ' readonly';
					}
				}
				global $post;
				wp_enqueue_style( 'wp-travel-engine_core_ui' );
				wp_enqueue_style( 'wte-extra-services' );
				wp_enqueue_script( 'wte-services_edit' );
				$services         = get_post_meta( $post->ID, 'wte_services', true );
				$cost             = wtees_get( $services, 'service_cost', '' );
				$service_type     = wtees_get( $services, 'service_type', 'default' );
				$service_required = wtees_get( $services, 'service_required', false );
				$field_type       = wtees_get( $services, 'field_type', 'select' );
				$pricing_type     = wtees_get( $services, 'pricing_type', 'single-price' );
				$unit             = wtees_get( $services, 'service_unit', 'unit' );
				$options          = wtees_get( $services, 'options', array( '' ) );
				$prices           = wtees_get( $services, 'prices', array() );
				$descriptions     = wtees_get( $services, 'descriptions', array() );
				$attributes       = wtees_get( $services, 'attributes', array() );
				$single_price     = wtees_get( $services, 'single_price', '' );
				$units            = wtees_get( $services, 'unit', array() );
				/**
				 * Adds new meta key for descriptions
				 *
				 * @since 2.1.7
				 */
				$default_descriptions = wtees_get( $services, 'default_descriptions', '' );
				?>
			<div id="wte-extra-services-metabox">
				<?php if ( version_compare( WP_TRAVEL_ENGINE_VERSION, '5.7.4', '>=' ) ) { ?>
					<div class="wpte-field">
						<label class="wpte-field-label" for="service-required"><?php esc_html_e( 'Required', 'wte-extra-services' ); ?> </label>
						<div class="wpte-field-control">
							<div class="wpte-field-group">
								<input class="as-switch" id="service-required" type="checkbox" name="wte_services[service_required]" value="1" <?php checked( true, $service_required ); ?>>
								<label for="service-required"><?php echo esc_html__( 'Required', 'wte-extra-services' ); ?></label>
							</div>
							<p class="wpte-help-text">
								<?php echo esc_html__( 'When this feature is enabled, a minimum of one selection is required during the booking process.', 'wte-extra-services' ); ?>
							</p>
						</div>
					</div>
				<?php } ?>
				<div class="wpte-field">
					<label class="wpte-field-label" for="service-type"><?php esc_html_e( 'Choose Service Type', 'wte-extra-services' ); ?> </label>
					<div class="wpte-field-control">
						<div class="wpte-subfields inline">
							<div class="wpte-field-group">
								<input id="service-type-1" type="radio" name="wte_services[service_type]" value="default" <?php checked( 'default', $service_type ); ?>>
								<label for="service-type-1"><?php echo esc_html__( 'Default', 'wte-extra-services' ); ?></label>
							</div>
							<div class="wpte-field-group">
								<input id="service-type-2" type="radio" name="wte_services[service_type]" value="custom" <?php checked( 'custom', $service_type ); ?>>
								<label for="service-type-2"><?php echo esc_html__( 'Advanced', 'wte-extra-services' ); ?></label>
							</div>
						</div>
					</div>
				</div>
				<div class="wte-field" id="wte-es-field-default" data-wte-es-template="default" <?php wte_es_show( $service_type, 'default' ); ?>>
					<div class="wpte-field">
						<label class="wpte-field-label" for="service-cost"><?php echo esc_html__( 'Service Cost', 'wte-extra-services' ); ?></label>
						<div class="wpte-field-control">
							<div class="wpte-subfields inline">
								<div class="wpte-field-group">
									<input name="wte_services[service_cost]" type="number" value="<?php echo esc_attr( $cost ); ?>" />
								</div>
								<div class="wpte-field-group">
									<select name="wte_services[service_unit]" id="service-unit">
										<?php
										$label_options = self::get_service_unit_labels();
										foreach ( $label_options as $label_value => $label_option ) :
											?>
											<option value="<?php echo esc_attr( $label_value ); ?>" <?php selected( $unit, $label_value ); ?>><?php echo esc_html( $label_option['select_option_label'] ); ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
						</div>
					</div>
					<div class="wpte-field">
						<label class="wpte-field-label" for="service-description"><?php echo esc_html__( 'Service Description', 'wte-extra-services' ); ?></label>
						<div class="wpte-field-control">
							<textarea 
								name="wte_services[default_descriptions]" 
								class="wte_services-description widefat"
								data-dt-max-height="175"
							><?php echo isset( $default_descriptions ) ? esc_textarea( $default_descriptions ) : ''; ?></textarea>
						</div>
					</div>
				</div>
				<div class="wte-extra-service-fields wte-es-field-custom" id="wte-es-field-custom" data-wte-es-template="custom" <?php wte_es_show( $service_type, 'custom' ); ?>>
					<div class="wpte-field">
						<label class="wpte-field-label" for="service-field-type"><?php echo esc_html__( 'Field Types', 'wte-extra-services' ); ?></label>
						<div class="wpte-field-control">
							<div class="wpte-subfields">
								<div class="wpte-field-group">
									<input id="field-type-1" type="radio" name="wte_services[field_type]" value="select" <?php checked( $field_type, 'select' ); ?> />
									<label for="field-type-1"><?php echo esc_html__( 'User can select one service option while booking.', 'wte-extra-services' ); ?></label>
								</div>
								<div class="wpte-field-group">
									<input id="field-type-2" type="radio" name="wte_services[field_type]" value="checkbox" <?php checked( $field_type, 'checkbox' ); ?> />
									<label for="field-type-2"><?php echo esc_html__( 'User can select multiple service options while booking.', 'wte-extra-services' ); ?></label>
								</div>
							</div>
							<p class="wpte-help-text"><?php esc_html_e( 'The field types defines the type of the service you are providing.', 'wte-extra-services' ); ?></p>
						</div>
					</div>
					<div class="wpte-field">
						<label class="wpte-field-label" for="service-pricing-type"><?php echo esc_html__( 'Pricing', 'wte-extra-services' ); ?></label>
						<div class="wpte-field-control">
							<div class="wpte-subfields">
								<div class="wpte-field-group">
									<input id="pricing-type-1" type="radio" name="wte_services[pricing_type]" value="single-price" <?php checked( $pricing_type, 'single-price' ); ?> />
									<label for="pricing-type-1"><?php echo esc_html__( 'Options have same price.', 'wte-extra-services' ); ?></label>
								</div>
								<input type="number" id="service-single-price" <?php wte_es_show( $pricing_type, 'single-price' ); ?> value="<?php echo esc_attr( $single_price ); ?>" name="wte_services[single_price]" step=".1" min="0" placeholder="<?php echo esc_attr( 'Service Price' ); ?>" style="max-width: 320px;" />
								<div class="wpte-field-group">
									<input id="pricing-type-2" type="radio" name="wte_services[pricing_type]" value="multiple-price" <?php checked( $pricing_type, 'multiple-price' ); ?> />
									<label for="pricing-type-2"><?php echo esc_html__( 'Options have different prices.', 'wte-extra-services' ); ?></label>
								</div>
							</div>
							<p class="wpte-help-text"><?php esc_html_e( 'The Pricing types controls if options below will have same price or different price.', 'wte-extra-services' ); ?></p>
						</div>
					</div>
					<div class="wpte-field" <?php wte_es_show( ( count( $options ) > 0 || in_array( $field_type, array( 'select', 'checkbox' ), true ) ), true ); ?>>
						<div class="wpte-field-control">
							<h3 class="wpte-field-title"><?php echo esc_html__( 'Service Options', 'wte-extra-services' ); ?></h3>
							<div class="wpte-extra-services-table-wrapper">
								<table class="wpte-extra-services-table">
									<thead>
										<tr>
											<th><?php echo esc_html__( 'Service Options', 'wte-extra-services' ); ?></th>
											<th style="width: 160px"><?php echo esc_html__( 'Service Costs', 'wte-extra-services' ); ?></th>
											<th style="width: 160px"><?php echo esc_html__( 'Service Unit', 'wte-extra-services' ); ?></th>
											<th><?php echo esc_html__( 'Service Descriptions', 'wte-extra-services' ); ?></th>
											<th style="width: 50px">&nbsp;</th>
										</tr>
									</thead>
									<tbody class="wpte-repeator-block wte-es-option">
										<?php
										foreach ( $options as $index => $option ) :
											$option_price       = isset( $prices[ $index ] ) ? $prices[ $index ] : '';
											$option_description = isset( $descriptions[ $index ] ) ? $descriptions[ $index ] : '';
											$option_attributes  = ! empty( $attributes[ $index ] ) ? $attributes[ $index ] : array();
											$option_unit        = isset( $units[ $index ] ) ? $units[ $index ] : '';
											?>
											<tr class="wpte-field-option-row">
												<td>
													<input type="text" class="wte_services-option" id="wte_services-option-<?php echo +$index; ?>" name="wte_services[options][<?php echo +$index; ?>]" value="<?php echo esc_attr( $option ); ?>" />
												</td>
												<td>
													<input type="number" class="wte_services-price" <?php wte_es_readonly( $pricing_type, 'single-price' ); ?> id="wte_services-price-<?php echo +$index; ?>" name="wte_services[prices][<?php echo +$index; ?>]" value="<?php echo esc_attr( $option_price ); ?>" />
												</td>
												<td>
													<input type="text" class="wte_services-unit" id="wte_services-unit-<?php echo +$index; ?>" name="wte_services[unit][<?php echo +$index; ?>]" value="<?php echo esc_attr( $option_unit ); ?>" placeholder="<?php echo esc_attr( 'unit' ); ?>" />
												</td>
												<td>
													<input type="text" data-dt-max-height="175" class="wte_services-description" id="wte_services-description-<?php echo +$index; ?>" name="wte_services[descriptions][<?php echo +$index; ?>]" value="<?php echo esc_attr( $option_description ); ?>">
												</td>
												<td>
													<button class="wpte-delete wpte-remove-es-option" data-delete data-target=".wpte-field-option-row" <?php wte_es_show( $index > 0, true ); ?>></button>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>
							<button class="add-extra-service wte-add-service-option"><?php echo esc_html__( 'Add Service Option', 'wte-extra-services' ); ?></button>
						</div>
					</div>
				</div>
			</div>
				<?php
			},
			'wte-services'
		);
	}

	public function save_meta( int $post_id, WP_Post $post ) {

		if (empty($_REQUEST['wte_services'])) { // phpcs:ignore
			return;
		}
		$services  = wp_unslash($_REQUEST['wte_services']); // phpcs:ignore
		$meta_sub_keys = array(
			'service_cost'         => '',
			'service_type'         => 'default',
			'service_required'     => false,
			'service_unit'         => 'unit',
			'field_type'           => 'text',
			'pricing_type'         => 'single-text',
			'single_price'         => 'text',
			'options'              => array(),
			'prices'               => array(),
			'descriptions'         => array(),
			'unit'                 => array(),
			'attributes'           => array(),
			'default_descriptions' => '',
		);

		$meta_value = array();
		if ( isset( $services['unit'] ) ) {
			if ( is_array( $services['unit'] ) ) {
				$services['unit'] = array_map( function( $value ) {
					return str_replace( '/', '', $value );
				}, $services['unit'] );
			} else {
				$services['unit'] = str_replace( '/', '', $services['unit'] );
			}
		}
		$actions    = array(
			'default' => function () use ( $meta_sub_keys, $post_id, $post, $services ) {
				$meta_value = array();
				foreach ( $meta_sub_keys as $meta_sub_key => $default ) {
					if ( isset( $services[ $meta_sub_key ] ) ) {
						$meta_value[ $meta_sub_key ] = $services[ $meta_sub_key ];
					}
				}
				update_post_meta( $post_id, 'wte_services', $meta_value );
			},
			'custom'  => function () use ( $meta_sub_keys, $post_id, $post, $services ) {
				$meta_value = array();
				foreach ( $meta_sub_keys as $meta_sub_key => $default ) {
					if ( isset( $services[ $meta_sub_key ] ) ) {
						if ( 'attributes' === $meta_sub_key ) {
							$meta_value[ $meta_sub_key ] = array_map(
								function ( $sm ) {
									return json_decode( $sm );
								},
								$services[ $meta_sub_key ]
							);
							continue;
						}
						$meta_value[ $meta_sub_key ] = $services[ $meta_sub_key ];
					} else {
						$meta_value[ $meta_sub_key ] = $default;
					}
				}
				update_post_meta( $post_id, 'wte_services', $meta_value );
			},
		);

		if ( isset( $services['service_type'] ) && in_array( $services['service_type'], array_keys( $actions ) ) ) {
			$actions[ $services['service_type'] ]();
		}
	}
}

new WTE_Extra_Services_metaboxes();
