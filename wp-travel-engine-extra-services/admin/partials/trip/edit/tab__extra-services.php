<?php
/**
 * Trip Edit - Content for Extra Services Settings tab.
 * {edit.php?post_type=trip}
 *
 * @since 4.0.5
 *
 * @package admin/pratials/trip/edit
 */

$wte_option_settings = get_option( 'wp_travel_engine_settings', false );
if ( ! isset( $wte_option_settings['currency_code'] ) ) {
	$wte_option_settings['currency_code'] = 'USD';
}

if ( ! isset( $post ) || ! is_object( $post ) && defined( 'DOING_AJAX' ) && DOING_AJAX ) {
	$post_id  = $_POST['post_id'];
	$next_tab = $_POST['next_tab'];
} else {
	$post_id = $post->ID;
}

$trip_metas = get_post_meta( $post_id, 'wp_travel_engine_setting', true );

$wte_services_ids = isset( $trip_metas['wte_services_ids'] ) ? trim( $trip_metas['wte_services_ids'] ) : '';

?>
<div style="margin-bottom: 40px;" class="wpte-info-block">
	<b><?php _e( 'Note:', 'wte-extra-services' ); ?></b>
	<p><?php _e( 'You can add, edit and delete the global extra services via <b>WP Travel Engine > Extra Services</b>.', 'wte-extra-services' ); ?>
	</p>
</div>
<div class="extra-service-submit wpte-field wpte-select wpte-floated">
	<label class="wpte-field-label"
		for="select-extra-services"><?php _e( 'Select Extra Service', 'wte-extra-services' ); ?></label>
	<input type="hidden" id="select-extra-service-ids" name="wp_travel_engine_setting[wte_services_ids]"
		value="<?php echo isset( $trip_metas['wte_services_ids'] ) ? esc_attr( $trip_metas['wte_services_ids'] ) : ''; ?>">
	<select name="" id="select-extra-services" class="wpte-enhanced-select">
		<option value="0">
			<?php _e( 'Choose Global Extra Services', 'wte-extra-services' ); ?>
		</option>
	</select>
	<span
		class="wpte-tooltip"><?php _e( 'Choose and select the global Extra Service.', 'wte-extra-services' ); ?></span>
</div>
<div class="trip-extra-services wpte-field">
	<h2><?php echo esc_html__( 'Selected Extra Services for the trip.', 'wte-extra-services' ); ?></h2>
	<table id="trip-selected-services-table">
		<thead>
			<tr>
				<th><?php esc_html_e( 'Service Name', 'wte-extra-services' ); ?></th>
				<th><?php esc_html_e( 'Service Type', 'wte-extra-services' ); ?></th>
				<th><?php esc_html_e( 'Options', 'wte-extra-services' ); ?></th>
				<th></th>
			</tr>
		</thead>
		<tbody></tbody>
	</table>
</div>
<script>
(function() {
	var esSelect = document.getElementById('select-extra-services')
	window.jQuery && window.jQuery.fn.select2 && jQuery(esSelect).select2()
	var results = null;
	fetch('<?php echo esc_url( get_rest_url( null, 'wp/v2/wte-services?per_page=100' ) ); ?>')
			.then(function(response) {
				response.json()
					.then(function(result) {
						if (result) {
							var _result = {}
							for (var res of result) {
								if (wteExtraServices) {
									_result[res.id] = res
								}
								esSelect.appendChild(new Option(res.title && res.title.rendered, res.id, false, false))
							}
							wteExtraServices.tripExtras = _result
							wteExtraServices?.extraServices?.addServiceRows()
							jQuery(esSelect).trigger('change')
						}
					})
			})
})()
</script>
<input type="hidden" name="wp_travel_engine_setting[extra_service]" value="false">
<div class="wpte-repeater-wrap">
	<div class="wpte-repeater-block-holder extra-service-holder">
		<?php
		$num_of_extra_services = 0;
		if ( isset( $trip_metas['extra_service'] ) && is_array( $trip_metas['extra_service'] ) ) :
			$num_of_extra_services = count( $trip_metas['extra_service'] );
			for ( $index = 0; $index < $num_of_extra_services; ++$index ) :
				?>
				<div class="wpte-repeater-block extra-service-repeater" data-id="<?php echo $index; ?>">
					<div class="wpte-form-block-wrap">
						<div class="wpte-form-block">
							<div class="wpte-title-wrap">
								<h2 class="wpte-title wpte-header-title-extra-service">
							<?php echo ! empty( $trip_metas['extra_service'][ $index ] ) ? esc_attr( $trip_metas['extra_service'][ $index ] ) : __( 'Extra Service', 'wte-extra-services' ); ?>
								</h2>
								<button class="wpte-delete delete-extra-service"></button>
							</div>
							<div class="wpte-form-content">
								<div class="wpte-floated">
									<div class="wpte-field wpte-text wpte-col2">
										<label class="wpte-field-label wpte-field-title-extra-service"
											for="wp_travel_engine_setting[extra_service][<?php echo esc_attr( $index ); ?>]"><?php _e( 'Title', 'wte-extra-services' ); ?></label>
										<input type="text" required class="wpte-field-title-extra-service"
											name="wp_travel_engine_setting[extra_service][<?php echo esc_attr( $index ); ?>]"
											id="wp_travel_engine_setting[extra_service][<?php echo esc_attr( $index ); ?>]"
											value="<?php echo esc_attr( $trip_metas['extra_service'][ $index ] ); ?>"
											placeholder="<?php _e( 'Service Name', 'wte-extra-services' ); ?>" />
									</div>

									<div class="wpte-field wpte-number wpte-col4">
										<label class="wpte-field-label"
											for="wp_travel_engine_setting[extra_service_cost][<?php echo $index; ?>]"><?php _e( 'Service Cost', 'wte-extra-services' ); ?></label>
										<div class="wpte-floated">
											<input type="number" required min="0" step=".1"
												name="wp_travel_engine_setting[extra_service_cost][<?php echo esc_attr( $index ); ?>]"
												id="wp_travel_engine_setting[extra_service_cost][<?php echo esc_attr( $index ); ?>]"
												value="<?php echo esc_attr( $trip_metas['extra_service_cost'][ $index ] ); ?>"
												placeholder="<?php _e( 'Price per person', 'wte-extra-services' ); ?>" />
											<span
												class="wpte-sublabel"><?php _e( $wte_option_settings['currency_code'], 'wte-extra-services' ); ?></span>
										</div>
									</div>

									<div class="wpte-field wpte-select wpte-col4">
										<label class="wpte-field-label"><?php _e( 'Service Unit', '' ); ?></label>
										<select
											name="wp_travel_engine_setting[extra_service_unit][<?php echo esc_attr( $index ); ?>]">
											<option value="unit"
										<?php selected( $trip_metas['extra_service_unit'][ $index ], 'unit' ); ?>>
										<?php _e( 'Per Unit', 'wte-extra-services' ); ?>
											</option>
											<option value="traveler"
										<?php selected( $trip_metas['extra_service_unit'][ $index ], 'traveler' ); ?>>
										<?php _e( 'Per Traveler', 'wte-extra-services' ); ?>
											</option>
										</select>
									</div>
								</div>

								<div class="wpte-field wpte-textarea">
									<label class="wpte-field-label"
										for="wp_travel_engine_setting[extra_service_desc][<?php echo esc_attr( $index ); ?>]"><?php _e( 'Service Description', 'wte-extra-services' ); ?></label>
									<textarea
										name="wp_travel_engine_setting[extra_service_desc][<?php echo esc_attr( $index ); ?>]"
										id="wp_travel_engine_setting[extra_service_desc][<?php echo esc_attr( $index ); ?>]"
										placeholder="<?php _e( 'Service Description', 'wte-extra-services' ); ?>"><?php echo esc_html( $trip_metas['extra_service_desc'][ $index ] ); ?></textarea>
								</div>
							</div>
						</div> <!-- .wpte-form-block -->
					</div> <!-- .wpte-form-block-wrap -->
				</div> <!-- .wpte-repeater-block -->
				<?php
				endfor;
			endif;
		?>
	</div>
</div>


<?php
if ( isset( $next_tab ) && ! empty( $next_tab ) ) :
	$action = version_compare( \WP_TRAVEL_ENGINE_VERSION, '5.3.1', '>=' ) ? 'wpte_tab_trip_save_and_continue' : 'wpte-trip-tab-save-continue';
	?>
<div class="wpte-field wpte-submit">
	<input data-tab="extra_service" data-post-id="<?php echo esc_attr( $post_id ); ?>"
		data-nonce="<?php echo esc_attr( wp_create_nonce( 'wpte_tab_trip_save_and_continue' ) ); ?>"
		data-next-tab="<?php echo isset( $next_tab['callback_function'] ) ? esc_attr( $next_tab['callback_function'] ) : ''; ?>"
		class="wpte_save_continue_link" type="submit" name="wpte_trip_tabs_save_continue"
		value="<?php _e( 'Save &amp; Continue', 'wte-extra-services' ); ?>">
</div>
<?php endif; ?>
