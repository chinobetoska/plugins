<?php

/**
 * Template for extra services settings in the plugin setting page.
 *
 * @link       https://wptravelengine.com/
 * @since      1.0.0
 *
 * @package    Extra_Services_Wp_Travel_Engine
 * @subpackage Extra_Services_Wp_Travel_Engine/admin/partials
 */

$wte_settings = get_option( 'wp_travel_engine_settings', false );
?>
<div class="wp-travel-engine-fields-settings">
	<div class="wpte-field wpte-text wpte-floated">
		<label class="wpte-field-label"><?php _e( 'Extra service title', 'wte-extra-services' ); ?></label>
		<input type="text" 
			name="wp_travel_engine_settings[extra_service_title]"
			value="<?php echo isset( $wte_settings['extra_service_title'] ) ? esc_attr( $wte_settings['extra_service_title'] ) : ''; ?>"
			placeholder="<?php _e( 'Enter title', 'wte-extra-services' ); ?>" />
		<span class="wpte-tooltip"><?php _e( 'Title For the Extra Service section.', 'wte-extra-services' ); ?></span>
	</div>
	<?php if ( apply_filters( 'use_legacy_extra_services', false ) ) : ?>
		<div class="wpte-repeater-wrap">
			<div class="wpte-repeater-block-holder extra-service-holder">
			<?php
			$num_of_extra_services = 0;
			if ( isset( $wte_settings['extra_service'] ) ) {
				$num_of_extra_services = count( $wte_settings['extra_service_cost'] );
				for ( $index = 0; $index < $num_of_extra_services; $index++ ) {
					?>
					<div class="wpte-repeater-block extra-service-repeater" data-id="<?php echo $index; ?>">
						<div class="wpte-form-block-wrap">
							<div class="wpte-form-block">
								<div class="wpte-title-wrap">
									<h2 class="wpte-title wpte-header-title-extra-service"><?php echo ! empty( $wte_settings['extra_service'][ $index ] ) ? esc_attr( $wte_settings['extra_service'][ $index ] ) : __( 'Extra Service', 'wte-extra-services' ); ?></h2>
									<button class="wpte-delete delete-extra-service"></button>
								</div>
								<div class="wpte-form-content">
									<div class="wpte-floated">
										<div class="wpte-field wpte-text wpte-col2">
											<label class="wpte-field-label wpte-field-title-extra-service" for="wp_travel_engine_settings[extra_service][<?php echo esc_attr( $index ); ?>]"><?php _e( 'Title', 'wte-extra-services' ); ?></label>
											<input 
												type="text" 
												required
												class="wpte-field-title-extra-service"
												name="wp_travel_engine_settings[extra_service][<?php echo esc_attr( $index ); ?>]"
												id="wp_travel_engine_settings[extra_service][<?php echo esc_attr( $index ); ?>]"
												value="<?php echo esc_attr( $wte_settings['extra_service'][ $index ] ); ?>"
												placeholder="<?php _e( 'Service Name', 'wte-extra-services' ); ?>" /> 
										</div>

										<div class="wpte-field wpte-number wpte-col4">
											<label class="wpte-field-label" for="wp_travel_engine_settings[extra_service_cost][<?php echo $index; ?>]"><?php _e( 'Service Cost', 'wte-extra-services' ); ?></label>
											<div class="wpte-floated">
												<input 
													type="number"
													required
													min="0"
													step=".1"
													name="wp_travel_engine_settings[extra_service_cost][<?php echo esc_attr( $index ); ?>]"
													id="wp_travel_engine_settings[extra_service_cost][<?php echo esc_attr( $index ); ?>]"
													value="<?php echo esc_attr( $wte_settings['extra_service_cost'][ $index ] ); ?>"
													placeholder="<?php _e( 'Price per person', 'wte-extra-services' ); ?>" />
												<span class="wpte-sublabel"><?php _e( $wte_settings['currency_code'], 'wte-extra-services' ); ?></span>
											</div>
										</div>

										<div class="wpte-field wpte-select wpte-col4">
											<label class="wpte-field-label"><?php _e( 'Service Unit', '' ); ?>
												
											</label>
											<select name="wp_travel_engine_settings[extra_service_unit][<?php echo esc_attr( $index ); ?>]">
												<option value="unit" <?php selected( $wte_settings['extra_service_unit'][ $index ], 'unit' ); ?>>
													<?php _e( 'Per Unit', 'wte-extra-services' ); ?>
												</option>
												<option value="traveler" <?php selected( $wte_settings['extra_service_unit'][ $index ], 'traveler' ); ?>>
													<?php _e( 'Per Traveler', 'wte-extra-services' ); ?>
												</option>
											</select>
										</div>
									</div>

									<div class="wpte-field wpte-textarea">
										<label class="wpte-field-label" for="wp_travel_engine_settings[extra_service_desc][<?php echo esc_attr( $index ); ?>]"><?php _e( 'Service Description', 'wte-extra-services' ); ?></label>
										<textarea name="wp_travel_engine_settings[extra_service_desc][<?php echo esc_attr( $index ); ?>]"
											id="wp_travel_engine_settings[extra_service_desc][<?php echo esc_attr( $index ); ?>]"
											placeholder="<?php _e( 'Service Description', 'wte-extra-services' ); ?>"><?php echo esc_html( $wte_settings['extra_service_desc'][ $index ] ); ?></textarea>
									</div>
								</div>
							</div> <!-- .wpte-form-block -->
						</div> <!-- .wpte-form-block-wrap -->
					</div> <!-- .wpte-repeater-block -->
					<?php
				}
			}
			?>
			</div>
			<div class="wpte-add-btn-wrap extra-service-submit">
				<button class="wpte-add-btn add-extra-service"><?php _e( 'Add Extra Service', 'wte-extra-services' ); ?></button>
			</div>
		</div>

		<script type="text/html" id="tmpl-wte-extra-services-settings-repeater">

			<?php
			if ( false === $wte_settings || ! isset( $wte_settings['currency_code'] ) ) {
				$wte_settings['currency_code'] = 'USD';
			}
			$index = '{{data.index}}';
			?>
			<div class="wpte-repeater-block extra-service-repeater" data-id="<?php echo $index; ?>">
				<div class="wpte-form-block-wrap">
					<div class="wpte-form-block">
						<div class="wpte-title-wrap">
							<h2 class="wpte-title wpte-header-title-extra-service"><?php echo ! empty( $wte_settings['extra_service'][ $index ] ) ? esc_attr( $wte_settings['extra_service'][ $index ] ) : __( 'Extra Service', 'wte-extra-services' ); ?></h2>
							<button class="wpte-delete delete-extra-service"></button>
						</div>
						<div class="wpte-form-content">
							<div class="wpte-floated">
								<div class="wpte-field wpte-text wpte-col2">
									<label class="wpte-field-label wpte-field-title-extra-service" for="wp_travel_engine_settings[extra_service][<?php echo esc_attr( $index ); ?>]"><?php _e( 'Title', 'wte-extra-services' ); ?></label>
									<input 
										type="text" 
										required
										class="wpte-field-title-extra-service"
										name="wp_travel_engine_settings[extra_service][<?php echo esc_attr( $index ); ?>]"
										id="wp_travel_engine_settings[extra_service][<?php echo esc_attr( $index ); ?>]"
										placeholder="<?php _e( 'Service Name', 'wte-extra-services' ); ?>" /> 
								</div>

								<div class="wpte-field wpte-number wpte-col4">
									<label class="wpte-field-label" for="wp_travel_engine_settings[extra_service_cost][<?php echo $index; ?>]"><?php _e( 'Service Cost', 'wte-extra-services' ); ?></label>
									<div class="wpte-floated">
										<input 
											type="number"
											required
											min="0"
											step=".1"
											name="wp_travel_engine_settings[extra_service_cost][<?php echo esc_attr( $index ); ?>]"
											id="wp_travel_engine_settings[extra_service_cost][<?php echo esc_attr( $index ); ?>]"
											placeholder="<?php _e( 'Price per person', 'wte-extra-services' ); ?>" />
										<span class="wpte-sublabel"><?php _e( $wte_settings['currency_code'], 'wte-extra-services' ); ?></span>
									</div>
								</div>

								<div class="wpte-field wpte-select wpte-col4">
									<label class="wpte-field-label"><?php _e( 'Service Unit', '' ); ?>
										
									</label>
									<select name="wp_travel_engine_settings[extra_service_unit][<?php echo esc_attr( $index ); ?>]">
										<option value="unit">
											<?php _e( 'Per Unit', 'wte-extra-services' ); ?>
										</option>
										<option value="traveler">
											<?php _e( 'Per Traveler', 'wte-extra-services' ); ?>
										</option>
									</select>
								</div>
							</div>

							<div class="wpte-field wpte-textarea">
								<label class="wpte-field-label" for="wp_travel_engine_settings[extra_service_desc][<?php echo esc_attr( $index ); ?>]"><?php _e( 'Service Description', 'wte-extra-services' ); ?></label>
								<textarea name="wp_travel_engine_settings[extra_service_desc][<?php echo esc_attr( $index ); ?>]"
									id="wp_travel_engine_settings[extra_service_desc][<?php echo esc_attr( $index ); ?>]"
									placeholder="<?php _e( 'Service Description', 'wte-extra-services' ); ?>"></textarea>
							</div>
						</div>
					</div> <!-- .wpte-form-block -->
				</div> <!-- .wpte-form-block-wrap -->
			</div> <!-- .wpte-repeater-block -->
		</script>
		<script type="text/javascript">
			var index = <?php echo esc_html( $num_of_extra_services ); ?>;
			jQuery(document).ready(function($) {
				$(document).on('click', '.add-extra-service', function(event) {
				var template = wp.template( 'wte-extra-services-settings-repeater' );
				$el = $('.extra-service-holder');
				$el.append( template( { index: index } ) );
				++index;
				});
			});
		</script>
	<?php else : ?>
		<div class="wpte-info-block">
			<p>
			<?php
			echo wp_kses(
				sprintf( __( 'The extra services have been moved from here to separate post-type for advanced features. %1$sView Extra Services%2$s', 'wte-extra-services' ), '<a href=" '. get_admin_url() . 'edit.php?post_type=wte-services">', '</a>' ),
				array(
					'a' => array(
						'href'   => array(),
						'target' => array(),
						'title'  => array(),
					),
				)
			);
			?>
			</p>
		</div>
	<?php endif; ?>
</div>
