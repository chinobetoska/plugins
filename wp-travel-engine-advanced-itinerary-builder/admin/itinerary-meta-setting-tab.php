<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' ); ?>
<?php
global $post;
// Get post ID.
if ( ! is_object( $post ) && defined( 'DOING_AJAX' ) && DOING_AJAX ) {
	$post_id  = isset( $_POST['post_id'] ) && ! empty( $_POST['post_id'] ) ? $_POST['post_id'] : '';
	$next_tab = isset( $_POST['next_tab'] ) && ! empty( $_POST['next_tab'] ) ? $_POST['next_tab'] : '';
} else {
	$post_id = $post->ID;
}
$wp_travel_engine_tabs = get_post_meta( $post_id, 'wp_travel_engine_setting', true );

$chart_data = get_post_meta( $post_id, 'trip_itinerary_chart_data', true );
$chart_data = is_array( json_decode( $chart_data, true ) ) ? $chart_data : '[]';

$trip_itinerary_title = isset( $wp_travel_engine_tabs['trip_itinerary_title'] ) ? $wp_travel_engine_tabs['trip_itinerary_title'] : __( 'Itinerary', 'wte-advanced-itinerary' );

?>
<span class="settings-note">
	<?php _e( 'You can add, edit and delete sleep modes via', 'wte-advanced-itinerary' ); ?>
	<b><?php _e( 'WP Travel Engine &gt; Settings &gt; Extensions &gt; Advanced Itinerary Builder.', 'wte-advanced-itinerary' ); ?></b>
</span>

<ul id="itinerary-list">
	<div class="wpte-field wpte-text wpte-floated">
		<label class="wpte-field-label"><?php _e( 'Section Title', 'wte-advanced-itinerary' ); ?></span></label>
		<input type="text" name="wp_travel_engine_setting[trip_itinerary_title]"
			value="<?php echo esc_attr( $trip_itinerary_title ); ?>" placeholder="Enter Here">
		<span
			class="wpte-tooltip"><?php _e( 'Enter title for the trip itinerary section tab content.', 'wte-advanced-itinerary' ); ?></span>
	</div>
	<input type="hidden" name="wp_travel_engine_setting[itinerary]" value="false">
	<?php
	if ( isset( $wp_travel_engine_tabs['itinerary'] ) && ! empty( $wp_travel_engine_tabs['itinerary'] ) && is_array( $wp_travel_engine_tabs['itinerary'] ) ) {
		$wte_advanced_itinerary = get_post_meta( $post_id, 'wte_advanced_itinerary', true );
		$maxlen                 = max( array_keys( $wp_travel_engine_tabs['itinerary']['itinerary_title'] ) );
		$arr_keys               = array_keys( $wp_travel_engine_tabs['itinerary']['itinerary_title'] );
		$i                      = 1;
		foreach ( $arr_keys as $key => $value ) {
			if ( array_key_exists( $value, $wp_travel_engine_tabs['itinerary']['itinerary_title'] ) ) {
				$iti_title = isset( $wp_travel_engine_tabs['itinerary']['itinerary_title'][ $value ] ) ? $wp_travel_engine_tabs['itinerary']['itinerary_title'][ $value ] : '';

				if ( isset( $wp_travel_engine_tabs['itinerary']['itinerary_days_label'][ $value ] ) && ! empty( $wp_travel_engine_tabs['itinerary']['itinerary_days_label'][ $value ] ) ) {
					$itinerary_days_label = esc_attr( $wp_travel_engine_tabs['itinerary']['itinerary_days_label'][ $value ] );
				} elseif ( ! isset( $wp_travel_engine_tabs['itinerary']['itinerary_days_label'][ $value ] ) ) {
					$itinerary_days_label = esc_attr__( 'Day', 'wte-advanced-itinerary' ) . ' ' . esc_attr( $i );
				} else {
					$itinerary_days_label = '';
				}
				?>
	<li id="itinerary-tabs<?php echo esc_attr( $value ); ?>" data-id="<?php echo esc_attr( $value ); ?>"
		class="itinerary-row">
		<!-- <span class="tabs-handle"><span></span></span>
		<i class="dashicons dashicons-no-alt wte-ai-del-li delete-icon" data-id="<?php echo esc_attr( $value ); ?>"></i> -->
		<div class="itinerary-holder">
			<a class="accordion-tabs-toggle" href="javascript:void(0);">
				<span class="itinerary-section-header faq-count">
					<?php
					if ( isset( $wp_travel_engine_tabs['itinerary']['itinerary_days_label'][ $value ] ) && ! empty( $wp_travel_engine_tabs['itinerary']['itinerary_days_label'][ $value ] ) ) {
						echo $itinerary_days_label_header = esc_attr( $wp_travel_engine_tabs['itinerary']['itinerary_days_label'][ $value ] );
					} else {
						echo $itinerary_days_label_header = esc_attr__( 'Day', 'wte-advanced-itinerary' ) . ' ' . esc_attr( $i );
					}
					?>
				</span>
			</a>
			<div class="itinerary-content">
				<div class="wpte-field wpte-floated">
					<input type="text"
						name="wp_travel_engine_setting[itinerary][itinerary_title][<?php echo esc_attr( $value ); ?>]"
						value="<?php echo esc_attr( $iti_title ); ?>"
						placeholder="<?php esc_attr_e( 'Itinerary Title', 'wte-advanced-itinerary' ); ?>">
				</div>
				<div class="itinerary-content-top-wrap">
					<div class="wpte-field wpte-floated">
						<input type="text" class="itinerary_days_label_field"
							name="wp_travel_engine_setting[itinerary][itinerary_days_label][<?php echo esc_attr( $value ); ?>]"
							value="<?php echo $itinerary_days_label; ?>"
							placeholder="<?php esc_attr_e( 'Days Label', 'wte-advanced-itinerary' ); ?>">
					</div>
					<div class="duration">
						<input type="text" class="itinerary-duration"
							name="wte_advanced_itinerary[advanced_itinerary][itinerary_duration][<?php echo esc_attr( $value ); ?>]"
							id="wte_advanced_itinerary[advanced_itinerary][itinerary_duration][<?php echo esc_attr( $value ); ?>]"
							value="<?php echo ( isset( $wte_advanced_itinerary['advanced_itinerary']['itinerary_duration'][ $value ] ) ? esc_attr( $wte_advanced_itinerary['advanced_itinerary']['itinerary_duration'][ $value ] ) : '' ); ?>"
							placeholder="<?php esc_attr_e( 'Duration', 'wte-advanced-itinerary' ); ?>">
					</div>
					<div class="type">
						<select class="itinerary-duration-type"
							name="wte_advanced_itinerary[advanced_itinerary][itinerary_duration_type][<?php echo esc_attr( $value ); ?>]"
							id="wte_advanced_itinerary[advanced_itinerary][itinerary_duration_type][<?php echo esc_attr( $value ); ?>]">
							<option value="hour"
								<?php echo ( isset( $wte_advanced_itinerary['advanced_itinerary']['itinerary_duration_type'][ $value ] ) && ( $wte_advanced_itinerary['advanced_itinerary']['itinerary_duration_type'][ $value ] ) == 'hour' ) ? 'selected="selected"' : ''; ?>>
								<?php _e( 'Hour(s)', 'wte-advanced-itinerary' ); ?>
							</option>
							<option value="minute"
								<?php echo ( isset( $wte_advanced_itinerary['advanced_itinerary']['itinerary_duration_type'][ $value ] ) && ( $wte_advanced_itinerary['advanced_itinerary']['itinerary_duration_type'][ $value ] ) == 'minute' ) ? 'selected="selected"' : ''; ?>>
								<?php _e( 'Minute(s)', 'wte-advanced-itinerary' ); ?>
							</option>
						</select>
					</div>
				</div>
				<div class="content">
					<div class="wte-ai-input-wrap delay">
						<div id="wp-acf-editor-<?php echo $value; ?>"
							class="wte-ai-editor-wrap wp-core-ui wp-editor-wrap">
							<div id="wp-editor-container-<?php echo $value; ?>" class="wp-editor-container">
								<div class="wte-ai-editor-notice">
									<?php _e( 'Click to initialize Rich Editor', 'wte-advanced-itinerary' ); ?></div>
								<textarea placeholder="<?php _e( 'Itinerary Content:', 'wte-advanced-itinerary' ); ?>"
									name="wp_travel_engine_setting[itinerary][itinerary_content][<?php echo $value; ?>]"
									class="wte-ai-editorarea wp-editor-area"
									id="wte-ai-editor-<?php echo $value; ?>"><?php echo isset( $wp_travel_engine_tabs['itinerary']['itinerary_content'][ $value ] ) ? html_entity_decode( esc_attr( $wp_travel_engine_tabs['itinerary']['itinerary_content'][ $value ] ) ) : ''; ?></textarea>
							</div>
						</div>
					</div>
				</div>
				<div class="itinerary-content-mid-wrap">
					<div class="itinerary-mode">
						<?php
							$options = get_option( 'wp_travel_engine_settings', true );
						?>
						<select class="itinerary-mode"
							name="wte_advanced_itinerary[advanced_itinerary][sleep_modes][<?php echo esc_attr( $value ); ?>]"
							id="wte_advanced_itinerary[advanced_itinerary][sleep_modes][<?php echo esc_attr( $value ); ?>]">
							<option value=""><?php _e( 'Please Select Sleep Mode', 'wte-advanced-itinerary' ); ?></option>
							<?php
							if ( isset( $options['wte_advance_itinerary']['itinerary_sleep_mode_fields'] ) && ! empty( $options['wte_advance_itinerary']['itinerary_sleep_mode_fields'] ) ) {
								foreach ( $options['wte_advance_itinerary']['itinerary_sleep_mode_fields'] as $key => $val ) {
									?>
							<option
								value="<?php echo isset( $val['field_text'] ) && ! empty( $val['field_text'] ) ? esc_attr( $val['field_text'] ) : ''; ?>"
									<?php echo isset( $wte_advanced_itinerary['advanced_itinerary']['sleep_modes'][ $value ] ) && $wte_advanced_itinerary['advanced_itinerary']['sleep_modes'][ $value ] == $val['field_text'] ? 'selected="selected"' : ''; ?>>
									<?php echo isset( $val['field_text'] ) && ! empty( $val['field_text'] ) ? esc_attr( $val['field_text'] ) : 'Untitled'; ?>
							</option>
									<?php
								}
							} elseif ( ! isset( $options['wte_advance_itinerary']['itinerary_sleep_mode_fields'] ) && isset( $wte_advanced_itinerary['advanced_itinerary']['sleep_modes'][ $value ] ) && ! empty( $wte_advanced_itinerary['advanced_itinerary']['sleep_modes'][ $value ] ) ) {
								?>
							<option
								value="<?php echo esc_attr( $wte_advanced_itinerary['advanced_itinerary']['sleep_modes'][ $value ] ); ?>"
								selected="selected">
								<?php echo esc_attr( $wte_advanced_itinerary['advanced_itinerary']['sleep_modes'][ $value ] ); ?>
							</option>
								<?php
							} else {
								?>
							<option disabled="disabled"><?php _e( 'No Field Added', 'wte-advanced-itinerary' ); ?>.
							</option>
								<?php
							}
							?>
						</select>
					</div>
				</div>
				<div class="itinerary-mode-description">
					<label
						for="wp_travel_engine_setting-itinerary-content-<?php echo $value; ?>"><?php _e( 'Sleep Mode Additional Info', 'wte-advanced-itinerary' ); ?></label>
					<div class="wte-ai-input-wrap delay">
						<div id="wp-acf-editor-<?php echo $value; ?>"
							class="wte-ai-editor-wrap wp-core-ui wp-editor-wrap">
							<div id="wp-editor-container-<?php echo $value; ?>" class="wp-editor-container">
								<div class="wte-ai-editor-notice">
									<?php _e( 'Click to initialize RichEditor', 'wte-advanced-itinerary' ); ?></div>
								<textarea
									placeholder="<?php _e( 'Sleep Mode Content Additional Info', 'wte-advanced-itinerary' ); ?>:"
									name="wte_advanced_itinerary[advanced_itinerary][itinerary_sleep_mode_description][<?php echo $value; ?>]"
									class="wte-ai-editorarea wp-editor-area"
									id="wte-ai-add-info-editor-<?php echo $value; ?>"><?php echo isset( $wte_advanced_itinerary['advanced_itinerary']['itinerary_sleep_mode_description'][ $value ] ) ? html_entity_decode( esc_attr( $wte_advanced_itinerary['advanced_itinerary']['itinerary_sleep_mode_description'][ $value ] ) ) : ''; ?></textarea>
							</div>
						</div>
					</div>
				</div>
				<div class="itinerary-content-mid-wrap">
					<div class="itinerary-image">
						<table class='form-table'>
							<tr>
								<td>
									<a class='feat-itinerary-img-gallery-add button' href='#'
										data-uploader-title='<?php _e( 'Add image to gallery', 'wte-advanced-itinerary' ); ?>'
										data-uploader-button-text='<?php _e( 'Add image', 'wte-advanced-itinerary' ); ?>'><i
											class="fas fa-plus"></i>
										<?php _e( 'Add image', 'wte-advanced-itinerary' ); ?></a>
									<ul id='feat-itinerary-img-gallery-metabox-list'
										class="feat-itinerary-img-gallery-metabox-list">
										<?php
										$itinerary_galleries_ids = isset( $wte_advanced_itinerary['advanced_itinerary']['itinerary_image'][ $value ] ) && ! empty( $wte_advanced_itinerary['advanced_itinerary']['itinerary_image'][ $value ] ) ? $wte_advanced_itinerary['advanced_itinerary']['itinerary_image'][ $value ] : '';
										if ( isset( $itinerary_galleries_ids ) && is_array( $itinerary_galleries_ids ) && ! empty( $itinerary_galleries_ids ) ) :
											$keyArray = array();
											foreach ( $itinerary_galleries_ids as $keys => $id ) :
												$keyArray[ $keys ] = $keys;
												$image             = wp_get_attachment_image_src( $id );
												?>
										<li>
											<input type='hidden'
												name='wte_advanced_itinerary[advanced_itinerary][itinerary_image][<?php echo esc_attr( $value ); ?>][<?php echo $keys; ?>]'
												value='<?php echo $id; ?>'>
											<img class='image-preview' src='<?php echo $image[0]; ?>'>
											<div class="wteai-field-action-wrap">
												<button data-uploader-button-text="Replace Image"
													data-uploader-title="Replace image"
													class="wpte-change wteai-custom-change-image"></button>
												<button class="wpte-delete wteai-remove-image"></button>
											</div>
										</li>
												<?php
											endforeach;
											endif;
										$max_key = ( isset( $keyArray ) && ! empty( $keyArray ) ) ? array_keys( $keyArray, max( $keyArray ) ) : array( '0' => '0' );
										?>
									</ul>
									<input type="hidden"
										name="wte_advanced_itinerary[advanced_itinerary][itinerary_image_max_count][<?php echo esc_attr( $value ); ?>]"
										value="<?php echo $max_key[0]; ?>" class="wte-max-img-count" />
								</td>
							</tr>
						</table>
					</div>
					<div class="itinerary-meal-mode">
							<?php
							$meals_included_arr = array();
							if ( isset( $wte_advanced_itinerary['advanced_itinerary']['meals_included'][ $value ] ) ) {
								foreach ( $wte_advanced_itinerary['advanced_itinerary']['meals_included'][ $value ] as $kkey => $vval ) {
									$meals_included_arr[] = strtolower( $vval );
								}
							}
							?>
						<label><?php _e( 'Meals Included', 'wte-advanced-itinerary' ); ?>:</label>
						<div class="wteai-check-wrap">
							<input type="checkbox"
								name="wte_advanced_itinerary[advanced_itinerary][meals_included][<?php echo esc_attr( $value ); ?>][]"
								id="wteai-itinerary-check-breakfast-<?php echo $value; ?>" class="checkbox"
								<?php echo ( isset( $meals_included_arr ) && in_array( 'breakfast', $meals_included_arr ) ) ? 'checked="checked"' : ''; ?>
								value="breakfast" data-multiple="true">
							<label
								for="wteai-itinerary-check-breakfast-<?php echo $value; ?>"><?php _e( 'Breakfast', 'wte-advanced-itinerary' ); ?></label>
						</div>
						<div class="wteai-check-wrap">
							<input type="checkbox"
								name="wte_advanced_itinerary[advanced_itinerary][meals_included][<?php echo esc_attr( $value ); ?>][]"
								id="wteai-itinerary-check-lunch-<?php echo $value; ?>" class="checkbox"
								<?php echo ( isset( $meals_included_arr ) && in_array( 'lunch', $meals_included_arr ) ) ? 'checked="checked"' : ''; ?>
								value="lunch" data-multiple="true">
							<label
								for="wteai-itinerary-check-lunch-<?php echo $value; ?>"><?php _e( 'Lunch', 'wte-advanced-itinerary' ); ?></label>
						</div>
						<div class="wteai-check-wrap">
							<input type="checkbox"
								name="wte_advanced_itinerary[advanced_itinerary][meals_included][<?php echo esc_attr( $value ); ?>][]"
								id="wteai-itinerary-check-dinner-<?php echo $value; ?>" class="checkbox"
								<?php echo ( isset( $meals_included_arr ) && in_array( 'dinner', $meals_included_arr ) ) ? 'checked="checked"' : ''; ?>
								value="dinner" data-multiple="true">
							<label
								for="wteai-itinerary-check-dinner-<?php echo $value; ?>"><?php _e( 'Dinner', 'wte-advanced-itinerary' ); ?></label>
						</div>
						<input type="button" class="button button-small etaeai-check-all"
							value="<?php _e( 'Tick All', 'wte-advanced-itinerary' ); ?>">
					</div>
				</div>
				<div class="itinerary-content-bottom-wrap itinenary-chart-data">
					<div class="wte-field-section-label">
						<label><?php esc_html_e( 'Location Info:', 'wte-advanced-itinerary' ); ?></label>
					</div>
					<div class="wte-ai-multiple-input-wrap">
						<div class="wte-ai-input-wrap">
							<input type="text" class="wte-itinerary-on-chart" value="<?php echo isset( $wte_advanced_itinerary['advanced_itinerary']['overnight'][ $value ]['at'] ) ? esc_attr( $wte_advanced_itinerary['advanced_itinerary']['overnight'][ $value ]['at'] ) : ''; ?>" name="wte_advanced_itinerary[advanced_itinerary][overnight][<?php echo esc_attr( $value ); ?>][at]"  placeholder="<?php _e( 'Location', 'wte-advanced-itinerary' ); ?>" />
						</div>
						<div class="wte-ai-input-wrap">
							<div class="wte-ai-input-group">
								<input type="text" class="wte-itinerary-on-chart" value="<?php echo isset( $wte_advanced_itinerary['advanced_itinerary']['overnight'][ $value ]['altitude'] ) ? esc_attr( $wte_advanced_itinerary['advanced_itinerary']['overnight'][ $value ]['altitude'] ) : ''; ?>" name="wte_advanced_itinerary[advanced_itinerary][overnight][<?php echo esc_attr( $value ); ?>][altitude]" placeholder="<?php _e( 'Altitude', 'wte-advanced-itinerary' ); ?>" />
								<div class="wte-input-group-append">
									<span>
										<?php
										$settings = get_option( 'wp_travel_engine_settings', array() );
										$units    = array(
											'm'  => __( 'Meter', 'wte-advanced-itinerary' ),
											'ft' => __( 'foot', 'wte-advanced-itinerary' ),
										);
										echo ! empty( $settings['wte_advance_itinerary']['chart']['alt_unit'] ) ? $units[ $settings['wte_advance_itinerary']['chart']['alt_unit'] ] : __( 'Meter', 'wte-advanced-itinerary' );
										?>
									</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<i class="dashicons dashicons-trash wte-ai-del-li delete-icon" data-id="<?php echo esc_attr( $value ); ?>"></i>
	</li>
				<?php
			}
			$i++;
		}
	}
	?>
</ul>

<span id="itinerary-holder"></span>
<div class="wpte-add-btn-wrap">
	<button class="wpte-add-btn wte-ai-add-itinerary"><?php _e( 'Add Itinerary', 'wte-advanced-itinerary' ); ?></button>
</div>
<?php do_action( 'wp_travel_engine_trip_custom_info' ); ?>
<?php if ( $next_tab ) : ?>
<div class="wpte-field wpte-submit">
	<input data-tab="itinerary" data-post-id="<?php echo esc_attr( $post_id ); ?>"
		data-nonce="<?php echo esc_attr( wp_create_nonce( 'wpte_tab_trip_save_and_continue' ) ); ?>"
		data-next-tab="<?php echo esc_attr( $next_tab['callback_function'] ); ?>" class="wpte_save_continue_link"
		type="submit" name="wpte_trip_tabs_save_continue"
		value="<?php _e( 'Save &amp; Continue', 'wte-advanced-itinerary' ); ?>">
</div>
	<?php
endif;
