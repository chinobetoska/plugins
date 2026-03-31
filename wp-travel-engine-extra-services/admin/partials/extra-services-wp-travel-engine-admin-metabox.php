<?php
/**
 * Template for displaying extra services in the metabox of
 * booking post type.
 *
 * @since 1.0.0
 */
?>
<div class="wpte-block-wrap">
	<div class="wpte-block">
		<div class="wpte-title-wrap">
			<h4 class="wpte-title"><?php _e( 'Extra Services', 'wte-extra-services' ); ?></h4>
		</div>
		<div class="wpte-block-content wpte-floated">
			<div class="wte-extra-services-meta-holder">
				<?php for ( $index = 0; $index < $num_of_extra_serivices; $index++ ) : ?>
				<div class="wte-extra-sevices-meta-item">
					<span class="service-title"><?php echo esc_html( $extra_services[ $index ] ); ?></span>
					<div class="cost-per">
						<span><?php echo esc_html( wp_travel_engine_get_formated_price_with_currency_code( $extra_services_cost[ $index ] ) ); ?></span>
						<span>X</span> 
						<span><?php echo esc_html( $extra_services_count[ $index ] ); ?></span> =
					</div>
					<div class="total-amount">
						<span><?php echo esc_html( wp_travel_engine_get_formated_price_with_currency_code( $single_extra_service_total_cost[ $index ] ) ); ?></span>    
					</div>
				</div>
				<?php endfor; ?>
				<div class="grand-total">
					<span><?php _e( 'Total Service Cost:', 'wte-extra-services' ); ?> </span>
					<span><?php echo esc_html( wp_travel_engine_get_formated_price_with_currency_code( $grand_total ) ); ?></span>
				</div>
			</div>
		</div>
	</div> <!-- .wpte-block -->
</div> <!-- .wpte-block-wrap -->
<?php
