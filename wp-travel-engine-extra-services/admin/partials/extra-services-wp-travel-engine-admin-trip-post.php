<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://wptravelengine.com/
 * @since      1.0.0
 *
 * @package    Extra_Services_Wp_Travel_Engine
 * @subpackage Extra_Services_Wp_Travel_Engine/admin/partials
 */
?>

<?php
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
?>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
	<div style="margin-bottom: 40px;" class="wpte-info-block">
		<b><?php _e( 'Note:', 'wte-extra-services' ); ?></b>
		<p><?php _e( 'You can add, edit and delete the global extra services via <b>WP Travel Engine > Settings > Extensions > Extra Services</b>.', 'wte-extra-services' ); ?></p>
	</div>
	<div class="extra-service-submit wpte-field wpte-select wpte-floated">
		<?php
		if ( isset( $wte_option_settings['extra_service_cost'] ) ) :
			$num_of_extra_services = count( $wte_option_settings['extra_service_cost'] );
			if ( $num_of_extra_services ) :
				?>
					<label class="wpte-field-label" for="select-extra-service"><?php _e( 'Select Extra Service', 'wte-extra-services' ); ?></label>
					<select name="" id="select-extra-service">
						<option value="0">
							<?php _e( 'Choose Global Extra Services', 'wte-extra-services' ); ?>
						</option>
					<?php for ( $index = 0; $index < $num_of_extra_services; ++$index ) : ?>
						<option value="<?php echo esc_attr( $wte_option_settings['extra_service'][ $index ] ); ?>">
						<?php echo esc_html( $wte_option_settings['extra_service'][ $index ] ); ?>
						</option>
					<?php endfor; ?>
					</select>
					<span class="wpte-tooltip"><?php _e( 'Choose and select the global Extra Service.', 'wte-extra-services' ); ?></span>
				<?php
				endif;
			endif;
		?>
	</div>
	<input type="hidden" name="wp_travel_engine_setting[extra_service]" value="false">
	<div class="wpte-repeater-wrap">
		<div class="wpte-repeater-block-holder extra-service-holder">
			<?php
			$trip_metas            = get_post_meta( $post_id, 'wp_travel_engine_setting', true );
			$num_of_extra_services = 0;
			if ( isset( $trip_metas['extra_service'] ) && is_array( $trip_metas['extra_service'] ) ) :
				$num_of_extra_services = count( $trip_metas['extra_service'] );
				for ( $index = 0; $index < $num_of_extra_services; ++$index ) :
					?>
					<div class="wpte-repeater-block extra-service-repeater" data-id="<?php echo $index; ?>">
						<div class="wpte-form-block-wrap">
							<div class="wpte-form-block">
								<div class="wpte-title-wrap">
									<h2 class="wpte-title wpte-header-title-extra-service"><?php echo ! empty( $trip_metas['extra_service'][ $index ] ) ? esc_attr( $trip_metas['extra_service'][ $index ] ) : __( 'Extra Service', 'wte-extra-services' ); ?></h2>
									<button class="wpte-delete delete-extra-service"></button>
								</div>
								<div class="wpte-form-content">
									<div class="wpte-floated">
										<div class="wpte-field wpte-text wpte-col2">
											<label class="wpte-field-label wpte-field-title-extra-service" for="wp_travel_engine_setting[extra_service][<?php echo esc_attr( $index ); ?>]"><?php _e( 'Title', 'wte-extra-services' ); ?></label>
											<input
												type="text"
												required
												class="wpte-field-title-extra-service"
												name="wp_travel_engine_setting[extra_service][<?php echo esc_attr( $index ); ?>]"
												id="wp_travel_engine_setting[extra_service][<?php echo esc_attr( $index ); ?>]"
												value="<?php echo esc_attr( $trip_metas['extra_service'][ $index ] ); ?>"
												placeholder="<?php _e( 'Service Name', 'wte-extra-services' ); ?>" />
										</div>

										<div class="wpte-field wpte-number wpte-col4">
											<label class="wpte-field-label" for="wp_travel_engine_setting[extra_service_cost][<?php echo $index; ?>]"><?php _e( 'Service Cost', 'wte-extra-services' ); ?></label>
											<div class="wpte-floated">
												<input
													type="number"
													required
													min="0"
													step=".1"
													name="wp_travel_engine_setting[extra_service_cost][<?php echo esc_attr( $index ); ?>]"
													id="wp_travel_engine_setting[extra_service_cost][<?php echo esc_attr( $index ); ?>]"
													value="<?php echo esc_attr( $trip_metas['extra_service_cost'][ $index ] ); ?>"
													placeholder="<?php _e( 'Price per person', 'wte-extra-services' ); ?>" />
												<span class="wpte-sublabel"><?php _e( $wte_option_settings['currency_code'], 'wte-extra-services' ); ?></span>
											</div>
										</div>

										<div class="wpte-field wpte-select wpte-col4">
											<label class="wpte-field-label"><?php _e( 'Service Unit', '' ); ?></label>
											<select name="wp_travel_engine_setting[extra_service_unit][<?php echo esc_attr( $index ); ?>]">
												<option value="unit" <?php selected( $trip_metas['extra_service_unit'][ $index ], 'unit' ); ?>>
													<?php _e( 'Per Unit', 'wte-extra-services' ); ?>
												</option>
												<option value="traveler" <?php selected( $trip_metas['extra_service_unit'][ $index ], 'traveler' ); ?>>
													<?php _e( 'Per Traveler', 'wte-extra-services' ); ?>
												</option>
											</select>
										</div>
									</div>

									<div class="wpte-field wpte-textarea">
										<label class="wpte-field-label" for="wp_travel_engine_setting[extra_service_desc][<?php echo esc_attr( $index ); ?>]"><?php _e( 'Service Description', 'wte-extra-services' ); ?></label>
										<textarea name="wp_travel_engine_setting[extra_service_desc][<?php echo esc_attr( $index ); ?>]"
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
		<div class="wpte-add-btn-wrap extra-service-submit">
			<button class="wpte-add-btn add-extra-service"><?php _e( 'Add Extra Service', 'wte-extra-services' ); ?></button>
		</div>
	</div>
		<div class="wpte-tooltip">
			<?php _e( 'You can create unique extra services for each trip using Add Extra Service.', 'wte-extra-services' ); ?>
		</div>

<?php if ( isset( $next_tab ) && ! empty( $next_tab ) ) : ?>
	<div class="wpte-field wpte-submit">
		<input data-tab="extra_service" data-post-id="<?php echo esc_attr( $post_id ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'wpte_tab_trip_save_and_continue' ) ); ?>" data-next-tab="<?php echo isset( $next_tab['callback_function'] ) ? esc_attr( $next_tab['callback_function'] ) : ''; ?>" class="wpte_save_continue_link" type="submit" name="wpte_trip_tabs_save_continue" value="<?php _e( 'Save &amp; Continue', 'wte-extra-services' ); ?>">
	</div>
<?php endif; ?>

<script type="text/html" id="tmpl-wte-extra-services-trip-repeater">
<?php
	$name     = 'wp_travel_engine_setting';
	$defaults = array(
		'extra_service'      => '',
		'extra_service_cost' => '',
		'extra_service_desc' => '',
		'extra_service_unit' => 'unit',
	);

	// If extra service is not found, extract defaults.
	if ( ! isset( $_POST['extra_service'] ) ) {
		extract( $defaults );
	}

	$extra_service_index = isset( $_POST['extra_service'] ) ? array_search( $_POST['extra_service'], $wte_option_settings['extra_service'] ) : false;
	if ( false !== $extra_service_index ) {
		$extra_service = array(
			'extra_service'      => $wte_option_settings['extra_service'][ $extra_service_index ],
			'extra_service_cost' => $wte_option_settings['extra_service_cost'][ $extra_service_index ],
			'extra_service_desc' => $wte_option_settings['extra_service_desc'][ $extra_service_index ],
			'extra_service_unit' => $wte_option_settings['extra_service_unit'][ $extra_service_index ],
		);
		$extra_service = wp_parse_args( $extra_service, $defaults );
		extract( $extra_service );
	} else {
		extract( $defaults );
	}
	?>
	<div class="wpte-repeater-block" data-id="{{data.index}}">
		<div class="wpte-form-block-wrap">
			<div class="wpte-form-block">
				<div class="wpte-title-wrap">
					<h2 class="wpte-title wpte-header-title-extra-service"><?php echo __( 'Extra Service', 'wte-extra-services' ); ?></h2>
					<button class="wpte-delete delete-extra-service"></button>
				</div>
				<div class="wpte-form-content">
					<div class="wpte-floated">
						<div class="wpte-field wpte-text wpte-col2">
							<label class="wpte-field-label" for="<?php echo esc_attr( $name ); ?>[extra_service][{{data.index}}]"><?php _e( 'Title', 'wte-extra-services' ); ?></label>
							<input
								type="text"
								required
								class="wpte-field-title-extra-service"
								name="<?php echo esc_attr( $name ); ?>[extra_service][{{data.index}}]"
								id="<?php echo esc_attr( $name ); ?>[extra_service][{{data.index}}]"
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
									name="<?php echo esc_attr( $name ); ?>[extra_service_cost][{{data.index}}]"
									id="<?php echo esc_attr( $name ); ?>[extra_service_cost][{{data.index}}]"
									value="<?php echo esc_attr( $extra_service_cost ); ?>"
									placeholder="<?php _e( 'Price per person', 'wte-extra-services' ); ?>" />
								<span class="wpte-sublabel"><?php _e( $wte_option_settings['currency_code'], 'wte-extra-services' ); ?></span>
							</div>
						</div>

						<div class="wpte-field wpte-select wpte-col4">
							<label class="wpte-field-label"><?php _e( 'Service Unit', '' ); ?>

							</label>
							<select name="<?php echo esc_attr( $name ); ?>[extra_service_unit][{{data.index}}]">
								<option value="unit" <?php selected( 'unit', $extra_service_unit ); ?>>
									<?php _e( 'Per Unit', 'wte-extra-services' ); ?>
								</option>
								<option value="traveler" <?php selected( 'traveler', $extra_service_unit ); ?>>
									<?php _e( 'Per Traveler', 'wte-extra-services' ); ?>
								</option>
							</select>
						</div>
					</div>

					<div class="wpte-field wpte-textarea">
						<label class="wpte-field-label" for="<?php echo esc_attr( $name ); ?>[extra_service_desc][{{data.index}}]"><?php _e( 'Service Description', 'wte-extra-services' ); ?></label>
						<textarea name="<?php echo esc_attr( $name ); ?>[extra_service_desc][{{data.index}}]"
							id="<?php echo esc_attr( $name ); ?>[extra_service_desc][{{data.index}}]"
							placeholder="<?php _e( 'Service Description', 'wte-extra-services' ); ?>"><?php echo esc_html( $extra_service_desc ); ?></textarea>
					</div>
				</div>
			</div> <!-- .wpte-form-block -->
		</div> <!-- .wpte-form-block-wrap -->
	</div>
</script>
<script>
jQuery(document).ready(function($) {
	 var index = $('.extra-service-repeater').length;
	$(document).on('click', '.add-extra-service', function() {
		var template = wp.template( 'wte-extra-services-trip-repeater' );
		$el = $('.extra-service-holder');
		$el.append( template( { index: index } ) );
		$el.find('.wpte-repeater-block:last input[type="text"]').focus();
		index++;
	});

	$(document).on('change', '#select-extra-service', function() {
		var extraService = $("#select-extra-service:selected").val();
		if ( '0' !== extraService ) {
			$.ajax({
				type: 'post',
				url: wteExtraServices.ajax_url,
				data: {
					action       : 'wte_extra_service_get_extra_service_template',
					name         : 'wp_travel_engine_setting',
					extra_service: extraService,
					index        : index
				},
				beforeSend: function() {
					$("#loader").fadeIn(500);
				}
			}).done(function(data){
				$(".extra-service-holder").append(data);
				$('.extra-service-holder').find('.wpte-repeater-block:last input[type="text"]').focus();
			}).complete(function(ata){
				$("#loader").fadeOut(500);
			});
		}
		index++;
	});
});
</script>
