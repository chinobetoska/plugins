<?php
/**
 * Template for displaying extra services in the metabox of
 * booking post type.
 *
 * @since 1.0.0
 */
$grand_total = 0;
?>
<div class="wpte-block-wrap">
	<div class="wpte-block">
		<div class="wpte-title-wrap">
			<h4 class="wpte-title"><?php _e( 'Extra Services', 'wte-extra-services' ); ?></h4>
		</div>
		<div class="wpte-block-content wpte-floated">
			<div class="wte-extra-services-meta-holder">
				<?php
				foreach ( $booked_extra_services as $key => $service ) :
					$cost       = $service['price'];
					$qty        = $service['qty'];
					$service    = $service['extra_service'];
					$total_cost = $cost * $qty;

					$grand_total += $total_cost;
					?>
				<div class="wte-extra-sevices-meta-item">
					<span class="service-title"><?php echo esc_html( $service ); ?></span>
					<div class="cost-per">
						<span><?php echo wp_travel_engine_get_formated_price_with_currency_code_symbol( $cost ); ?></span>
						<span>X</span> 
						<span><?php echo esc_html( $qty ); ?></span> =
					</div>
					<div class="total-amount">
						<span><?php echo wp_travel_engine_get_formated_price_with_currency_code_symbol( $total_cost ); ?></span>    
					</div>
				</div>
				<?php endforeach; ?>
				<div class="grand-total">
					<span><?php _e( 'Total Service Cost:', 'wte-extra-services' ); ?> </span>
					<span><?php echo wp_travel_engine_get_formated_price_with_currency_code_symbol( $grand_total ); ?></span>
				</div>
			</div>
		</div>
	</div> <!-- .wpte-block -->
</div> <!-- .wpte-block-wrap -->
<?php
