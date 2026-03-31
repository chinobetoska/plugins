<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' ); ?>

<?php
$screen  = get_current_screen();
$options = get_option( 'wp_travel_engine_settings', true );
?>
<div id="wte-ai-itinerary-template">
	<li id="itinerary-tabs{{aiindex}}" data-id="{{aiindex}}" class="itinerary-row">
		<div class="itinerary-holder">
			<a class="accordion-tabs-toggle" href="javascript:void(0);">
				<span class="itinerary-section-header faq-count">
					<?php _e( 'Day', 'wte-advanced-itinerary' ); ?>
				</span>
			</a>
			<div class="itinerary-content">
				<div class="wpte-field wpte-floated">
						<input type="text" name="wp_travel_engine_setting[itinerary][itinerary_title][{{aiindex}}]" placeholder="<?php esc_attr_e( 'Itinerary Title', 'wte-advanced-itinerary' ); ?>">
				</div>
				<div class="itinerary-content-top-wrap">
					<div class="wpte-field wpte-floated">
						<input type="text" class="itinerary_days_label_field" name="wp_travel_engine_setting[itinerary][itinerary_days_label][{{aiindex}}]" placeholder="<?php esc_attr_e( 'Days Label', 'wte-advanced-itinerary' ); ?>">
					</div>
					<div class="duration">
						<input type="text" class="itinerary-duration"
							name="wte_advanced_itinerary[advanced_itinerary][itinerary_duration][{{aiindex}}]"
							id="wte_advanced_itinerary[advanced_itinerary][itinerary_duration][{{aiindex}}]" placeholder="<?php _e( 'Duration', 'wte-advanced-itinerary' ); ?>">
					</div>
					<div class="type">
						<select class="itinerary-duration-type"
							name="wte_advanced_itinerary[advanced_itinerary][itinerary_duration_type][{{aiindex}}]"
							id="wte_advanced_itinerary[advanced_itinerary][itinerary_duration_type][{{aiindex}}]">
							<option value="hour"><?php _e( 'Hour(s)', 'wte-advanced-itinerary' ); ?></option>
							<option value="minute"><?php _e( 'Minute(s)', 'wte-advanced-itinerary' ); ?></option>
						</select>
					</div>
				</div>
				<div class="content">
					<div class="wte-ai-input-wrap delay">
						<div id="wp-acf-editor-{{aiindex}}" class="wte-ai-editor-wrap wp-core-ui wp-editor-wrap">
							<div id="wp-editor-container-{{aiindex}}" class="wp-editor-container">
								<div class="wte-ai-editor-notice">
									<?php _e( 'Click to initialize RichEditor', 'wte-advanced-itinerary' ); ?></div>
								<textarea placeholder="<?php _e( 'Itinerary Content:', 'wte-advanced-itinerary' ); ?>"
									name="wp_travel_engine_setting[itinerary][itinerary_content][{{aiindex}}]"
									class="wte-ai-editorarea wp-editor-area" id="wte-ai-editor-{{aiindex}}"></textarea>
							</div>
						</div>
					</div>
				</div>
				<div class="itinerary-content-mid-wrap">
					<div class="itinerary-mode">
						<select class="itinerary-mode"
							name="wte_advanced_itinerary[advanced_itinerary][sleep_modes][{{aiindex}}]"
							id="wte_advanced_itinerary[advanced_itinerary][sleep_modes][{{aiindex}}]">
							<option value=""><?php _e( 'Please Select Sleep Mode', 'wte-advanced-itinerary' ); ?></option>
							<?php
							if ( isset( $options['wte_advance_itinerary']['itinerary_sleep_mode_fields'] ) && ! empty( $options['wte_advance_itinerary']['itinerary_sleep_mode_fields'] ) ) {
								foreach ( $options['wte_advance_itinerary']['itinerary_sleep_mode_fields'] as $key => $val ) {
									?>
							<option
								value="<?php echo isset( $val['field_text'] ) && ! empty( $val['field_text'] ) ? esc_attr( $val['field_text'] ) : ''; ?>
								">
									<?php
									echo isset( $val['field_text'] ) && ! empty( $val['field_text'] ) ? esc_attr( $val['field_text'] ) : '';
									?>
							</option>
									<?php
								}
							} else {
								?>
							<option disabled="disabled"><?php _e( 'No Field Added', 'wte-advanced-itinerary' ); ?></option>
								<?php
							}
							?>
						</select>
					</div>
				</div>
				<div class="itinerary-mode-description">
					<label
						for="wte_advanced_itinerary-itinerary-content-{{aiindex}}"><?php _e( 'Sleep Mode Additional Info', 'wte-advanced-itinerary' ); ?></label>
					<div class="wte-ai-input-wrap delay">
						<div id="wp-acf-editor-{{aiindex}}" class="wte-ai-editor-wrap wp-core-ui wp-editor-wrap">
							<div id="wp-editor-container-{{aiindex}}" class="wp-editor-container">
								<div class="wte-ai-editor-notice">
									<?php _e( 'Click to initialize RichEditor', 'wte-advanced-itinerary' ); ?></div>
								<textarea
									placeholder="<?php _e( 'Sleep Mode Content Additional Info:', 'wte-advanced-itinerary' ); ?>"
									name="wte_advanced_itinerary[advanced_itinerary][itinerary_sleep_mode_description][{{aiindex}}]"
									class="wte-ai-editorarea wp-editor-area"
									id="wte-ai-add-info-editor-{{aiindex}}"></textarea>
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
											class="fas fa-plus"></i> <?php _e( 'Add image', 'wte-advanced-itinerary' ); ?></a>
									<ul id='feat-itinerary-img-gallery-metabox-list' class="feat-itinerary-img-gallery-metabox-list">
									</ul>
									<input type="hidden"
										name="wte_advanced_itinerary[advanced_itinerary][itinerary_image_max_count][{{aiindex}}]"
										value="0" class="wte-max-img-count" />
								</td>
							</tr>
						</table>
					</div>
					<div class="itinerary-meal-mode">
						<label><?php _e( 'Meals Included', 'wte-advanced-itinerary' ); ?>:</label>
						<div class="wteai-check-wrap">
							<input type="checkbox"
								data-multiple="true"
								name="wte_advanced_itinerary[advanced_itinerary][meals_included][{{aiindex}}][]"
								id="wteai-itinerary-check-breakfast-{{aiindex}}" class="checkbox" value="breakfast">
							<label for="wteai-itinerary-check-breakfast-{{aiindex}}">
								<?php _e( 'Breakfast', 'wte-advanced-itinerary' ); ?>
							</label>
						</div>
						<div class="wteai-check-wrap">
							<input type="checkbox" data-multiple="true"
								name="wte_advanced_itinerary[advanced_itinerary][meals_included][{{aiindex}}][]"
								id="wteai-itinerary-check-lunch-{{aiindex}}" class="checkbox" value="lunch">
							<label for="wteai-itinerary-check-lunch-{{aiindex}}">
								<?php _e( 'Lunch', 'wte-advanced-itinerary' ); ?>
							</label>
						</div>
						<div class="wteai-check-wrap" >
							<input type="checkbox" data-multiple="true"
								name="wte_advanced_itinerary[advanced_itinerary][meals_included][{{aiindex}}][]"
								id="wteai-itinerary-check-dinner-{{aiindex}}" class="checkbox" value="dinner">
							<label for="wteai-itinerary-check-dinner-{{aiindex}}">
								<?php _e( 'Dinner', 'wte-advanced-itinerary' ); ?>
							</label>
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
							<input type="text" class="wte-itinerary-on-chart" name="wte_advanced_itinerary[advanced_itinerary][overnight][{{aiindex}}][at]"  placeholder="<?php _e( 'Location', 'wte-advanced-itinerary' ); ?>" />
						</div>
						<div class="wte-ai-input-wrap">
                            <div class="wte-ai-input-group">
                                <input type="text" class="wte-itinerary-on-chart" name="wte_advanced_itinerary[advanced_itinerary][overnight][{{aiindex}}][altitude]" placeholder="<?php _e( 'Altitude', 'wte-advanced-itinerary' ); ?>" />
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
		  <i class="dashicons dashicons-trash wte-ai-del-li delete-icon" data-id="{{aiindex}}"></i>
	</li>
</div>
<style type="text/css">
#wte-ai-itinerary-template {
	display: none !important;
}
</style>
