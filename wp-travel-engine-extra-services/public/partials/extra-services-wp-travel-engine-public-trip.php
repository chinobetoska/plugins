<?php

/**
 * Template for booking step in the single trip page.
 *
 * @link       https://wptravelengine.com/
 * @since      1.0.0
 *
 * @package    Extra_Services_Wp_Travel_Engine
 * @subpackage Extra_Services_Wp_Travel_Engine/public/partials
 */
global $post;
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
						<input type="text" name="add-member" 
							disabled
							value="0" min="0" max="999999999999" 
							data-unit = "<?php echo esc_attr( $extra_service_unit[ $index ] ); ?>"
							data-cost = "<?php echo esc_attr( $extra_service_cost[ $index ] ); ?>" />
						<button class="wpte-bf-plus">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path fill="currentColor" d="M368 224H224V80c0-8.84-7.16-16-16-16h-32c-8.84 0-16 7.16-16 16v144H16c-8.84 0-16 7.16-16 16v32c0 8.84 7.16 16 16 16h144v144c0 8.84 7.16 16 16 16h32c8.84 0 16-7.16 16-16V288h144c8.84 0 16-7.16 16-16v-32c0-8.84-7.16-16-16-16z"></path></svg>
						</button>
						<button class="wpte-bf-minus">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path fill="currentColor" d="M368 224H16c-8.84 0-16 7.16-16 16v32c0 8.84 7.16 16 16 16h352c8.84 0 16-7.16 16-16v-32c0-8.84-7.16-16-16-16z"></path></svg>
						</button>
					</div>
					<span><?php echo esc_html( $extra_service ); ?></span>
				<?php if ( ! empty( trim( $extra_service_desc[ $index ] ) ) ) : ?>
					<div class="wpte-bf-info-wrap">
						<span class="wpte-bf-info-icon">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 973.1 973.1"><path d="M502.29,788.199h-47c-33.1,0-60,26.9-60,60v64.9c0,33.1,26.9,60,60,60h47c33.101,0,60-26.9,60-60v-64.9 C562.29,815,535.391,788.199,502.29,788.199z"></path><path d="M170.89,285.8l86.7,10.8c27.5,3.4,53.6-12.4,63.5-38.3c12.5-32.7,29.9-58.5,52.2-77.3c31.601-26.6,70.9-40,117.9-40 c48.7,0,87.5,12.8,116.3,38.3c28.8,25.6,43.1,56.2,43.1,92.1c0,25.8-8.1,49.4-24.3,70.8c-10.5,13.6-42.8,42.2-96.7,85.9 c-54,43.7-89.899,83.099-107.899,118.099c-18.4,35.801-24.8,75.5-26.4,115.301c-1.399,34.1,25.8,62.5,60,62.5h49 c31.2,0,57-23.9,59.8-54.9c2-22.299,5.7-39.199,11.301-50.699c9.399-19.701,33.699-45.701,72.699-78.1 C723.59,477.8,772.79,428.4,795.891,392c23-36.3,34.6-74.8,34.6-115.5c0-73.5-31.3-138-94-193.4c-62.6-55.4-147-83.1-253-83.1 c-100.8,0-182.1,27.3-244.1,82c-52.8,46.6-84.9,101.8-96.2,165.5C139.69,266.1,152.39,283.5,170.89,285.8z"></path></svg>
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
					<span class="wpte-bf-pqty"><?php esc_html_e( 'per ', 'wte-extra-services' ); ?><?php echo esc_html( $perlabels[ $extra_service_unit[ $index ] ] ); ?></span>
				</div>
			</div>
		<?php endforeach; ?>
		</div>
	</div>
</div>
