<?php

/**
 * Extra Servicd template.
 *
 * @link       https://wptravelengine.com/
 * @since      1.0.0
 *
 * @package    Extra_Services_Wp_Travel_Engine
 * @subpackage Extra_Services_Wp_Travel_Engine/admin/partials
 */
?>

<div class="extra-service-repeater" data-id="<?php echo esc_attr( $index ); ?>">
	<div class="wpte-repeater-block">
		<div class="wpte-form-block-wrap">
			<div class="wpte-form-block">
				<div class="wpte-title-wrap">
					<h2 class="wpte-title wpte-header-title-extra-service"><?php echo ( isset( $extra_service ) && ! empty( $extra_service ) ) ? esc_attr( $extra_service ) : __( 'Extra Service', 'wte-extra-services' ); ?></h2>
					<button class="wpte-delete delete-extra-service"></button>
				</div>
				<div class="wpte-form-content">
					<div class="wpte-floated">
						<div class="wpte-field wpte-text wpte-col2">
							<label class="wpte-field-label" for="<?php echo esc_attr( $name ); ?>[extra_service][<?php echo esc_attr( $index ); ?>]"><?php _e( 'Title', 'wte-extra-services' ); ?></label>
							<input 
								type="text" 
								required
								class="wpte-field-title-extra-service"
								name="<?php echo esc_attr( $name ); ?>[extra_service][<?php echo esc_attr( $index ); ?>]"
								id="<?php echo esc_attr( $name ); ?>[extra_service][<?php echo esc_attr( $index ); ?>]"
								value="<?php echo esc_attr( $extra_service ); ?>"
								placeholder="<?php _e( 'Service Name', 'wte-extra-services' ); ?>" /> 
						</div>

						<div class="wpte-field wpte-number wpte-col4">
							<label class="wpte-field-label" for="<?php echo esc_attr( $name ); ?>[extra_service_cost]">
								<?php _e( 'Service Cost', 'wte-extra-services' ); ?>
							</label>
							<div class="wpte-floated">
								<input 
									type="number"
									required
									min="0"
									step=".1"
									name="<?php echo esc_attr( $name ); ?>[extra_service_cost][<?php echo esc_attr( $index ); ?>]"
									id="<?php echo esc_attr( $name ); ?>[extra_service_cost][<?php echo esc_attr( $index ); ?>]"
									value="<?php echo esc_attr( $extra_service_cost ); ?>"
									placeholder="<?php _e( 'Price per person', 'wte-extra-services' ); ?>" />
								<span class="wpte-sublabel"><?php _e( $wte_option_settings['currency_code'], 'wte-extra-services' ); ?></span>
							</div>
						</div>

						<div class="wpte-field wpte-select wpte-col4">
							<label class="wpte-field-label"><?php _e( 'Service Unit', '' ); ?>
								
							</label>
							<select name="<?php echo esc_attr( $name ); ?>[extra_service_unit][<?php echo esc_attr( $index ); ?>]">
								<option value="unit" <?php selected( 'unit', $extra_service_unit ); ?>>
									<?php _e( 'Per Unit', 'wte-extra-services' ); ?>
								</option>
								<option value="traveler" <?php selected( 'traveler', $extra_service_unit ); ?>>
									<?php _e( 'Per Traveler', 'wte-extra-services' ); ?>
								</option>
							</select>
						</div>
						<!-- <span class="wpte-tooltip wpte-col1">
							< ?php _e( 'Select Service Unit if the service cost is Per Unit or Per Traveler. This will be displayed in the booking form in the front-end.', 'wte-extra-services' ); ?>
						</span> -->
					</div>

						<div class="wpte-field wpte-textarea">
							<label class="wpte-field-label" for="<?php echo esc_attr( $name ); ?>[extra_service_desc][<?php echo esc_attr( $index ); ?>]"><?php _e( 'Service Description', 'wte-extra-services' ); ?></label>
							<textarea name="<?php echo esc_attr( $name ); ?>[extra_service_desc][<?php echo esc_attr( $index ); ?>]"
								id="<?php echo esc_attr( $name ); ?>[extra_service_desc][<?php echo esc_attr( $index ); ?>]"
								placeholder="<?php _e( 'Service Description', 'wte-extra-services' ); ?>"><?php echo esc_html( $extra_service_desc ); ?></textarea>
							<!-- <span class="wpte-tooltip">Tooltip here - Choose a template. Click Save Changes then Preview Purchase Receipt to see the new template.</span> -->
						</div>
					</div>
				</div> <!-- .wpte-form-block -->
			</div> <!-- .wpte-form-block-wrap -->
		</div> <!-- .wpte-repeater-block -->
	</div>
</div>
<?php
