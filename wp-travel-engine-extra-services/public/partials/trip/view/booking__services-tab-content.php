<?php

/**
 * Template for booking step in the single trip page.
 *
 * @link       https://wptravelengine.com/
 * @since      1.0.0
 *
 * @package    Extra_Services_Wp_Travel_Engine
 * @subpackage /public/partials/trip/view
 */
global $post;

wp_enqueue_script( 'owl-carousel' );

$post_meta = get_post_meta( $post->ID, 'wp_travel_engine_setting', true );
$settings  = get_option( 'wp_travel_engine_settings' );

$extra_service_title = isset( $settings['extra_service_title'] ) ? $settings['extra_service_title'] : __( 'Extra Services', 'wte-extra-services' );
$extra_service       = isset( $post_meta['extra_service'] ) ? $post_meta['extra_service'] : array();
$extra_service_cost  = isset( $post_meta['extra_service_cost'] ) ? $post_meta['extra_service_cost'] : array();
$extra_service_desc  = isset( $post_meta['extra_service_desc'] ) ? $post_meta['extra_service_desc'] : array();
$extra_service_unit  = isset( $post_meta['extra_service_unit'] ) ? $post_meta['extra_service_unit'] : array();

$extra_service_cost = apply_filters( 'wte_es_cost', $extra_service_cost, $post->ID );
?>

<div class="wpte-bf-step-content wpte-bf-step-content-extra-services">
	<div class="wpte-bf-traveler-block-wrap">
		<div class="wpte-bf-block-title">
			<?php echo esc_html( $extra_service_title ); ?>
		</div>

		<div class="wpte-bf-traveler-member">
			<?php foreach ( $extra_service as $index => $extra_service ) : ?>
			<div class="wpte-bf-es-block">
				<div class="wpte-bf-traveler">
					<div class="wpte-bf-number-field">
						<input type="text" name="add-member" disabled value="0" min="0" max="999999999999"
							data-unit="<?php echo esc_attr( $extra_service_unit[ $index ] ); ?>"
							data-cost="<?php echo esc_attr( $extra_service_cost[ $index ] ); ?>" />
						<button class="wpte-bf-plus">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
								<path fill="currentColor"
									d="M368 224H224V80c0-8.84-7.16-16-16-16h-32c-8.84 0-16 7.16-16 16v144H16c-8.84 0-16 7.16-16 16v32c0 8.84 7.16 16 16 16h144v144c0 8.84 7.16 16 16 16h32c8.84 0 16-7.16 16-16V288h144c8.84 0 16-7.16 16-16v-32c0-8.84-7.16-16-16-16z">
								</path>
							</svg>
						</button>
						<button class="wpte-bf-minus">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
								<path fill="currentColor"
									d="M368 224H16c-8.84 0-16 7.16-16 16v32c0 8.84 7.16 16 16 16h352c8.84 0 16-7.16 16-16v-32c0-8.84-7.16-16-16-16z">
								</path>
							</svg>
						</button>
					</div>
					<span><?php echo esc_html( $extra_service ); ?></span>
					<?php if ( ! empty( trim( $extra_service_desc[ $index ] ) ) ) : ?>
					<div class="wpte-bf-info-wrap">
						<span class="wpte-bf-info-icon">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 973.1 973.1">
								<path
									d="M502.29,788.199h-47c-33.1,0-60,26.9-60,60v64.9c0,33.1,26.9,60,60,60h47c33.101,0,60-26.9,60-60v-64.9 C562.29,815,535.391,788.199,502.29,788.199z">
								</path>
								<path
									d="M170.89,285.8l86.7,10.8c27.5,3.4,53.6-12.4,63.5-38.3c12.5-32.7,29.9-58.5,52.2-77.3c31.601-26.6,70.9-40,117.9-40 c48.7,0,87.5,12.8,116.3,38.3c28.8,25.6,43.1,56.2,43.1,92.1c0,25.8-8.1,49.4-24.3,70.8c-10.5,13.6-42.8,42.2-96.7,85.9 c-54,43.7-89.899,83.099-107.899,118.099c-18.4,35.801-24.8,75.5-26.4,115.301c-1.399,34.1,25.8,62.5,60,62.5h49 c31.2,0,57-23.9,59.8-54.9c2-22.299,5.7-39.199,11.301-50.699c9.399-19.701,33.699-45.701,72.699-78.1 C723.59,477.8,772.79,428.4,795.891,392c23-36.3,34.6-74.8,34.6-115.5c0-73.5-31.3-138-94-193.4c-62.6-55.4-147-83.1-253-83.1 c-100.8,0-182.1,27.3-244.1,82c-52.8,46.6-84.9,101.8-96.2,165.5C139.69,266.1,152.39,283.5,170.89,285.8z">
								</path>
							</svg>
						</span>
						<div class="wpte-bf-info-txt">
							<?php echo esc_html( $extra_service_desc[ $index ] ); ?>
						</div>
					</div>
					<?php endif; ?>
				</div>
				<div class="wpte-bf-price">
					<ins>
						<b>
							<?php
							$extra_service_price = apply_filters( 'wte_es_price_in_bkg_row', $extra_service_cost[ $index ], $post->ID );
							echo wp_travel_engine_get_formated_price_with_currency_code_symbol( $extra_service_price );

							$perlabels = array(
								'unit'     => __( 'Unit', 'wte-extra-services' ),
								'traveler' => __( 'Traveler', 'wte-extra-services' ),
							);
							?>
						</b>
					</ins>
					<span
						class="wpte-bf-pqty"><?php esc_html_e( 'per ', 'wte-extra-services' ); ?><?php echo esc_html( $perlabels[ $extra_service_unit[ $index ] ] ); ?></span>
				</div>
			</div>
			<?php endforeach; ?>
			<?php
			$global_services_ids = ( isset( $post_meta['wte_services_ids'] ) ) ? $post_meta['wte_services_ids'] : '';
			$global_services_ids = empty( $global_services_ids ) ? array() : explode( ',', $global_services_ids );

			if ( ! empty( $global_services_ids ) ) {
				$services = get_posts(
					array(
						'post_type' => 'wte-services',
						'include'   => $global_services_ids,
					)
				);
				foreach ( $services as $service ) {
					$wte_service          = get_post_meta( $service->ID, 'wte_services', true );
					$service_type         = wtees_get( $wte_service, 'service_type', 'default' );
					$service_required     = wtees_get( $wte_service, 'service_required', false );
					$service_unit         = wtees_get( $wte_service, 'service_unit', 'unit' );
					$service_cost         = wtees_get( $wte_service, 'service_cost', 0 );
					$field_type           = wtees_get( $wte_service, 'field_type', 'select' );
					$service_options      = wtees_get( $wte_service, 'options', array() );
					$service_prices       = wtees_get( $wte_service, 'prices', array() );
					$service_descriptions = wtees_get( $wte_service, 'descriptions', array() );

					if ( 'default' === $service_type ) : // Default Service Type.
						?>
						<div class="wpte-bf-es-block">
							<div class="wpte-bf-traveler">
								<div class="wpte-bf-number-field">
									<input type="text" name="add-member" disabled value="0" min="0" max="999999999999"
										data-unit="<?php echo esc_attr( 'traveler' ); ?>"
										data-cost="<?php echo esc_attr( $service_cost ); ?>" />
									<button class="wpte-bf-plus">
										<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
											<path fill="currentColor"
												d="M368 224H224V80c0-8.84-7.16-16-16-16h-32c-8.84 0-16 7.16-16 16v144H16c-8.84 0-16 7.16-16 16v32c0 8.84 7.16 16 16 16h144v144c0 8.84 7.16 16 16 16h32c8.84 0 16-7.16 16-16V288h144c8.84 0 16-7.16 16-16v-32c0-8.84-7.16-16-16-16z">
											</path>
										</svg>
									</button>
									<button class="wpte-bf-minus">
										<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
											<path fill="currentColor"
												d="M368 224H16c-8.84 0-16 7.16-16 16v32c0 8.84 7.16 16 16 16h352c8.84 0 16-7.16 16-16v-32c0-8.84-7.16-16-16-16z">
											</path>
										</svg>
									</button>
								</div>
								<span><?php echo esc_html( $service->post_title ); ?></span>
								<?php if ( ! empty( $service->post_content ) ) : ?>
								<div class="wpte-bf-info-wrap">
									<span class="wpte-bf-info-icon">
										<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 973.1 973.1">
											<path
												d="M502.29,788.199h-47c-33.1,0-60,26.9-60,60v64.9c0,33.1,26.9,60,60,60h47c33.101,0,60-26.9,60-60v-64.9 C562.29,815,535.391,788.199,502.29,788.199z">
											</path>
											<path
												d="M170.89,285.8l86.7,10.8c27.5,3.4,53.6-12.4,63.5-38.3c12.5-32.7,29.9-58.5,52.2-77.3c31.601-26.6,70.9-40,117.9-40 c48.7,0,87.5,12.8,116.3,38.3c28.8,25.6,43.1,56.2,43.1,92.1c0,25.8-8.1,49.4-24.3,70.8c-10.5,13.6-42.8,42.2-96.7,85.9 c-54,43.7-89.899,83.099-107.899,118.099c-18.4,35.801-24.8,75.5-26.4,115.301c-1.399,34.1,25.8,62.5,60,62.5h49 c31.2,0,57-23.9,59.8-54.9c2-22.299,5.7-39.199,11.301-50.699c9.399-19.701,33.699-45.701,72.699-78.1 C723.59,477.8,772.79,428.4,795.891,392c23-36.3,34.6-74.8,34.6-115.5c0-73.5-31.3-138-94-193.4c-62.6-55.4-147-83.1-253-83.1 c-100.8,0-182.1,27.3-244.1,82c-52.8,46.6-84.9,101.8-96.2,165.5C139.69,266.1,152.39,283.5,170.89,285.8z">
											</path>
										</svg>
									</span>
									<div class="wpte-bf-info-txt">
										<?php echo wp_kses_post( $service->post_content ); ?>
									</div>
								</div>
								<?php endif; ?>
							</div>
							<div class="wpte-bf-price">
								<ins>
									<b>
										<?php
										// $extra_service_price = apply_filters( 'wte_es_price_in_bkg_row', $extra_service_cost[ $index ], $post->ID );
										echo wp_travel_engine_get_formated_price_with_currency_code_symbol( $service_cost );

										$perlabels = array(
											'unit'     => __( 'Unit', 'wte-extra-services' ),
											'traveler' => __( 'Traveler', 'wte-extra-services' ),
										);
										?>
									</b>
								</ins>
								<span
									class="wpte-bf-pqty"><?php esc_html_e( 'per ', 'wte-extra-services' ); ?><?php echo esc_html( $perlabels[ $service_unit ] ); ?></span>
							</div>
						</div>
						<?php
					elseif ( 'custom' === $service_type ) : // End Default Service Type.
						?>
						<div class="wpte-bf-es-block wpte-bf-es-custom-block"
							data-service-options="<?php echo esc_attr( wp_json_encode( $service_options ) ); ?>"
							data-service-title="<?php echo esc_attr( $service->post_title ); ?>"
							data-service-prices="<?php echo esc_attr( wp_json_encode( $service_prices ) ); ?>">
							<h4><?php echo esc_html( $service->post_title ); ?></h4>
							<?php
							if ( 'select' === $field_type ) : // Field Type - select.
								$service_option_price = isset( $service_prices[0] ) ? +$service_prices[0] : 0;
								?>
								<div class="wte-es-service-select">
									<div class="wpte-bf-traveler">
										<div class="wpte-bf-number-field">
											<input type="text" name="add-member" disabled value="0" min="0" max="999999999999"
												data-unit="<?php echo esc_attr( 'traveler' ); ?>"
												data-cost="<?php echo esc_attr( $service_option_price ); ?>" />
											<button class="wpte-bf-plus">
												<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
													<path fill="currentColor"
														d="M368 224H224V80c0-8.84-7.16-16-16-16h-32c-8.84 0-16 7.16-16 16v144H16c-8.84 0-16 7.16-16 16v32c0 8.84 7.16 16 16 16h144v144c0 8.84 7.16 16 16 16h32c8.84 0 16-7.16 16-16V288h144c8.84 0 16-7.16 16-16v-32c0-8.84-7.16-16-16-16z">
													</path>
												</svg>
											</button>
											<button class="wpte-bf-minus">
												<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
													<path fill="currentColor"
														d="M368 224H16c-8.84 0-16 7.16-16 16v32c0 8.84 7.16 16 16 16h352c8.84 0 16-7.16 16-16v-32c0-8.84-7.16-16-16-16z">
													</path>
												</svg>
											</button>
										</div>
										<span style="display:none" class="wpte-bf-service-name"><?php echo esc_html( $service->post_title . '(' . $service_options[0] . ')' ); ?></span>
										<?php
										echo '<select class="services-options-select">';
										foreach ( $service_options as $index => $s_option ) {
											$value = sanitize_title( $s_option );
											$price = isset( $service_prices[ $index ] ) ? wp_travel_engine_get_formated_price( $service_prices[ $index ] ) : '';
											echo "<option value=\"{$index}\">{$s_option}</option>";
										}
										echo '</select>';
										?>
									</div>
									<div class="wpte-bf-price">
										<ins>
											<b>
											<?php
											$extra_service_price = apply_filters( 'wte_es_price_in_bkg_row', $service_option_price, $post->ID );
											echo wp_travel_engine_get_formated_price_with_currency_code_symbol( $extra_service_price );

											$perlabels = array(
												'unit'     => __( 'Unit', 'wte-extra-services' ),
												'traveler' => __( 'Traveler', 'wte-extra-services' ),
											);
											?>
											</b>
										</ins>
										<span class="wpte-bf-pqty"><?php esc_html_e( 'per ', 'wte-extra-services' ); ?><?php echo esc_html( $perlabels[ $service_unit ] ); ?></span>
									</div>
								</div>
								<?php
							elseif ( 'checkbox' === $field_type ) : // If {$field_type} is checkbox.
								echo '<div class="wte-owl-carousel owl-carousel wte-es-with-multiple-options">';
								foreach ( $service_options as $index => $service_option ) : // Checkbox - ServiceOptions.
									$service_option_price       = isset( $service_prices[ $index ] ) ? +$service_prices[ $index ] : 0;
									$service_option_description = isset( $service_descriptions[ $index ] ) ? $service_descriptions[ $index ] : 0;
									?>
									<div class="wte-es-option-row item" title="<?php echo esc_attr( $service_option ); ?>">
										<div class="wpte-bf-traveler">
											<span><?php echo esc_html( $service_option ); ?></span>
											<div class="wpte-bf-number-field">
												<input type="text" name="add-member" disabled value="0" min="0" max="999999999999"
													data-unit="<?php echo esc_attr( 'traveler' ); ?>"
													data-cost="<?php echo esc_attr( $service_option_price ); ?>" />
												<button class="wpte-bf-plus">
													<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
														<path fill="currentColor"
															d="M368 224H224V80c0-8.84-7.16-16-16-16h-32c-8.84 0-16 7.16-16 16v144H16c-8.84 0-16 7.16-16 16v32c0 8.84 7.16 16 16 16h144v144c0 8.84 7.16 16 16 16h32c8.84 0 16-7.16 16-16V288h144c8.84 0 16-7.16 16-16v-32c0-8.84-7.16-16-16-16z">
														</path>
													</svg>
												</button>
												<button class="wpte-bf-minus">
													<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
														<path fill="currentColor"
															d="M368 224H16c-8.84 0-16 7.16-16 16v32c0 8.84 7.16 16 16 16h352c8.84 0 16-7.16 16-16v-32c0-8.84-7.16-16-16-16z">
														</path>
													</svg>
												</button>
											</div>
											<?php if ( ! empty( $service_option_description ) ) : ?>
											<div class="wpte-bf-info-wrap">
												<span class="wpte-bf-info-icon">
													<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 973.1 973.1"><path d="M502.29,788.199h-47c-33.1,0-60,26.9-60,60v64.9c0,33.1,26.9,60,60,60h47c33.101,0,60-26.9,60-60v-64.9 C562.29,815,535.391,788.199,502.29,788.199z"></path><path d="M170.89,285.8l86.7,10.8c27.5,3.4,53.6-12.4,63.5-38.3c12.5-32.7,29.9-58.5,52.2-77.3c31.601-26.6,70.9-40,117.9-40 c48.7,0,87.5,12.8,116.3,38.3c28.8,25.6,43.1,56.2,43.1,92.1c0,25.8-8.1,49.4-24.3,70.8c-10.5,13.6-42.8,42.2-96.7,85.9 c-54,43.7-89.899,83.099-107.899,118.099c-18.4,35.801-24.8,75.5-26.4,115.301c-1.399,34.1,25.8,62.5,60,62.5h49 c31.2,0,57-23.9,59.8-54.9c2-22.299,5.7-39.199,11.301-50.699c9.399-19.701,33.699-45.701,72.699-78.1 C723.59,477.8,772.79,428.4,795.891,392c23-36.3,34.6-74.8,34.6-115.5c0-73.5-31.3-138-94-193.4c-62.6-55.4-147-83.1-253-83.1 c-100.8,0-182.1,27.3-244.1,82c-52.8,46.6-84.9,101.8-96.2,165.5C139.69,266.1,152.39,283.5,170.89,285.8z"></path></svg>
												</span>
												<div class="wpte-bf-info-txt"><?php echo wp_kses_post( $service_option_description ); ?></div>
											</div>
											<?php endif; ?>
										</div>
										<div class="wpte-bf-price">
											<ins>
												<b>
												<?php
												$extra_service_price = apply_filters( 'wte_es_price_in_bkg_row', $service_option_price, $service->ID );
												echo wp_travel_engine_get_formated_price_with_currency_code_symbol( $extra_service_price );
												$perlabels = array(
													'unit' => __( 'Unit', 'wte-extra-services' ),
													'traveler' => __( 'Traveler', 'wte-extra-services' ),
												);
												?>
												</b>
											</ins>
											<span
												class="wpte-bf-pqty"><?php esc_html_e( 'per ', 'wte-extra-services' ); ?><?php echo esc_html( $perlabels[ $service_unit ] ); ?></span>
										</div>
									</div>
									<?php
								endforeach; // Checkbox - ServiceOptions.
								echo '</div>'
								?>
								<script>
								 (function() {
									window.addEventListener('load', function() {
										jQuery('.wte-owl-carousel').owlCarousel({
											autoplay: false,
											dots: false,
											loop: false,
											margin: 15,
											responsiveClass: true,
											autoHeight: true,
											autoplayTimeout: 7000,
											smartSpeed: 800,
											nav: true,
											items: 2
										});
									}) 
								})()
								</script>
								<?php
							endif; // If {$field_type} is checkbox.
							?>
						</div>
						<?php
					endif;
				}
			}

			?>
		</div>
	</div>
</div>
