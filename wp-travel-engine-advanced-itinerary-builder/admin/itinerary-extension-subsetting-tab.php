<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' ); ?>

<?php
wp_enqueue_media();
$options = get_option( 'wp_travel_engine_settings', true );
?>
<div class="wpte-advanced-itinerary-settings wte-advanced-itinerary-fields-settings">
	<div id="trip_info" class="wpte-row">
		<div class="wpte-field wpte-checkbox advance-checkbox">
			<label class="wpte-field-label"
				for="wp_travel_engine_settings[wte_advance_itinerary][enable_expand_all]"><?php _e( 'Always Show  All Itinerary', 'wte-advanced-itinerary' ); ?></label>
			<div class="wpte-checkbox-wrap">
				<input type="checkbox" id="wp_travel_engine_settings[wte_advance_itinerary][enable_expand_all]"
					name="wp_travel_engine_settings[wte_advance_itinerary][enable_expand_all]" value="1"
					<?php echo isset( $options['wte_advance_itinerary']['enable_expand_all'] ) ? 'checked="checked"' : ''; ?>>
				<label for="wp_travel_engine_settings[wte_advance_itinerary][enable_expand_all]"
					class="checkbox-label"></label>
			</div>
			<span
				class="wpte-tooltip"><?php _e( 'Default: All hidden. Enable this option to always expand all itinerary on initial page load.', 'wte-advanced-itinerary' ); ?></span>
		</div>
		<div class="wpte-form-block">
			<div class="wpte-title-wrap">
				<h4 class="wpte-title"
					for="wp_travel_engine_settings[wte_advance_itinerary][itinerary_sleep_mode_fields]">
					<?php _e( 'Sleep Mode Fields for Itinerary', 'wte-advanced-itinerary' ); ?></h4>
			</div>
			<div class="wpte-form-content">
				<div class="wpte-repeater-wrap">
					<div class="wpte-repeater-h wpte-rep-heading">
						<label class="wpte-field-label"><?php _e( 'Option Text', 'wte-advanced-itinerary' ); ?><span
								class="required"> *</span></label>
						<span
							class="wpte-tooltip"><?php _e( 'Field option value to be displayed in Sleep Mode Select Field in trip page. This text will also be displayed in front as a sleeping mode in each itinerary.', 'wte-advanced-itinerary' ); ?></span>
					</div>
					<ul class="wte-ai-fields-wrap fields-accordion">
						<?php
						if ( isset( $options['wte_advance_itinerary']['itinerary_sleep_mode_fields'] ) ) {
							$i        = 0;
							$arr_keys = $options['wte_advance_itinerary']['itinerary_sleep_mode_fields'];
							foreach ( $arr_keys as $key => $value ) {
								?>

						<li id="trip_facts_template-<?php echo $key; ?>" data-id="<?php echo esc_attr( $key ); ?>"
							class="trip_facts wte-ai-trip-itinerary-sleep-mode wpte-sortable">
							<div class="form-builder">
								<div class="wpte-field wpte-field-gray wpte-text">
									<input type="text"
										name="wp_travel_engine_settings[wte_advance_itinerary][itinerary_sleep_mode_fields][<?php echo $key; ?>][field_text]"
										value="<?php echo isset( $options['wte_advance_itinerary']['itinerary_sleep_mode_fields'][ $key ]['field_text'] ) ? esc_attr( $options['wte_advance_itinerary']['itinerary_sleep_mode_fields'][ $key ]['field_text'] ) : ''; ?>"
										required>
								</div>
							</div>
							<a href="#" class="wte-ai-del-li"><i class="far fa-trash-alt"></i></a>
						</li>
								<?php
								$i++;
							}
						}
						?>
						<span id="writefacts"></span>
					</ul>
				</div>
			</div>
		</div>
		<div id="add_remove_fields">
			<?php
			$other_attributes = array( 'id' => 'wte_itinerary_add_remove_field' );
			submit_button( __( 'Add Field', 'wte-advanced-itinerary' ), '', '', true, $other_attributes );
			?>
		</div>
		<div class="wpte-info-block">
			<p><?php _e( "You can set various sleep modes on particular day's trip from above setting. You can add various means of accommodations such as hotel, tent, camping, homestay etc. for specific day.", 'wte-advanced-itinerary' ); ?>
			</p>
		</div>
		<?php
		$html_atts = array(
			'id'     => 'wteAltChart',
			'class'  => 'wte-alt-chart',
			'height' => 450,
			'width'  => '100%',
		);

		$html_atts            = implode(
			' ',
			array_map(
				function( $att_value, $att_key ) {
					return "$att_key=$att_value";
				},
				array_values( $html_atts ),
				array_keys( $html_atts )
			)
		);
		$elevation_unit_label = __( 'Altitude Unit:', 'wte-advanced-itinerary' );
		$labels               = array(
			'm'  => __( 'M.', 'wte-advanced-itinerary' ),
			'ft' => __( 'FT.', 'wte-advanced-itinerary' ),
		);
		$show_chart_on_trip_page = isset( $options['wte_advance_itinerary']['chart']['show'] ) ? $options['wte_advance_itinerary']['chart']['show'] : 'yes';
		?>
		<div class="wpte-form-block wpte-chart-settings-block">
			<div class="wpte-title-wrap">
				<h4 class="wpte-title"><?php _e( 'Itinerary Elevation Chart Settings', 'wte-advanced-itinerary' ); ?>
				</h4>
			</div>
			<div class="wpte-ai-row wpte-form-content">
				<div class="wpte-ai-col wpte-chart-settings-wrap">
					<div class="wpte-field wpte-checkbox advance-checkbox">
						<input type="hidden" name="wp_travel_engine_settings[wte_advance_itinerary][chart][show]" value="1" value="no" />
						<label class="wpte-field-label"
							for="wp_travel_engine_settings[wte_advance_itinerary][chart][show]"><?php _e( 'Show Chart on Trip Page', 'wte-advanced-itinerary' ); ?></label>
						<div class="wpte-checkbox-wrap">
							<input type="checkbox" id="wp_travel_engine_settings[wte_advance_itinerary][chart][show]"
								name="wp_travel_engine_settings[wte_advance_itinerary][chart][show]" value="yes"
								<?php checked( $show_chart_on_trip_page, 'yes' ); ?>>
							<label for="wp_travel_engine_settings[wte_advance_itinerary][chart][show]"
								class="checkbox-label"></label>
						</div>
						<span
							class="wpte-tooltip"><?php _e( 'Enable this option to show an elevation chart just before itinerary list.', 'wte-advanced-itinerary' ); ?></span>
					</div>
					<div class="wpte-field wpte-select wpte-floated">
						<label class="wpte-field-label"
							for="wp_travel_engine_settings[wte_advance_itinerary][chart][alt_unit]"><?php _e( 'Elevation input unit', 'wte-advanced-itinerary' ); ?></label>
						<div class="wpte-checkbox-wrap">
							<select id="wp_travel_engine_settings[wte_advance_itinerary][chart][alt_unit]" name="wp_travel_engine_settings[wte_advance_itinerary][chart][alt_unit]" class="wpte-enhanced-select">
								<?php $selected = isset( $options['wte_advance_itinerary']['chart']['alt_unit'] ) ? $options['wte_advance_itinerary']['chart']['alt_unit'] : 'm'; ?>
								<option value="m" <?php selected( $selected, 'm' ); ?>><?php echo esc_html__( 'Meter', 'wte-advanced-itinerary' ); ?></option>
								<option value="ft" <?php selected( $selected, 'ft' ); ?>><?php echo esc_html__( 'foot', 'wte-advanced-itinerary' ); ?></option>
							</select>
						</div>
						<span
							class="wpte-tooltip"><?php _e( 'Default: Meter. The unit of entered value on each itinerary will be the selected one and will be used for conversion.', 'wte-advanced-itinerary' ); ?></span>
					</div>
					<div class="wpte-field wpte-checkbox advance-checkbox">
						<label class="wpte-field-label"
							for="wp_travel_engine_settings[wte_advance_itinerary][chart][options][scales.xAxes.display]"><?php _e( 'Show X-Axis', 'wte-advanced-itinerary' ); ?></label>
						<div class="wpte-checkbox-wrap">
							<input class="wte-chart-config-input" data-settings="scales.xAxes.display" type="checkbox" id="wp_travel_engine_settings[wte_advance_itinerary][chart][options][scales.xAxes.display]"
								name="wp_travel_engine_settings[wte_advance_itinerary][chart][options][scales.xAxes.display]" value="1"
								<?php echo isset( $options['wte_advance_itinerary']['chart']['options']['scales.xAxes.display'] ) ? 'checked="checked"' : ''; ?>>
							<label for="wp_travel_engine_settings[wte_advance_itinerary][chart][options][scales.xAxes.display]"
								class="checkbox-label"></label>
						</div>
					</div>
					<div class="wpte-field wpte-checkbox advance-checkbox">
						<label class="wpte-field-label"
							for="wp_travel_engine_settings[wte_advance_itinerary][chart][options][scales.yAxes.display]"><?php _e( 'Show Y-Axis', 'wte-advanced-itinerary' ); ?></label>
						<div class="wpte-checkbox-wrap">
							<input class="wte-chart-config-input" data-settings="scales.yAxes.display" type="checkbox" id="wp_travel_engine_settings[wte_advance_itinerary][chart][options][scales.yAxes.display]"
								name="wp_travel_engine_settings[wte_advance_itinerary][chart][options][scales.yAxes.display]" value="1"
								<?php echo isset( $options['wte_advance_itinerary']['chart']['options']['scales.yAxes.display'] ) ? 'checked="checked"' : ''; ?>>
							<label for="wp_travel_engine_settings[wte_advance_itinerary][chart][options][scales.yAxes.display]"
								class="checkbox-label"></label>
						</div>
					</div>
					<div class="wpte-field wpte-checkbox advance-checkbox">
						<label class="wpte-field-label" for="wp_travel_engine_settings[wte_advance_itinerary][chart][data][datasets.data.fill]"><?php _e( 'Show line Graph', 'wte-advanced-itinerary' ); ?></label>
						<div class="wpte-checkbox-wrap">
							<input class="wte-chart-config-input" data-settings="datasets.data.fill" type="checkbox" id="wp_travel_engine_settings[wte_advance_itinerary][chart][data][datasets.data.fill]"
								name="wp_travel_engine_settings[wte_advance_itinerary][chart][data][datasets.data.fill]" value="1"
								<?php echo isset( $options['wte_advance_itinerary']['chart']['data']['datasets.data.fill'] ) ? 'checked="checked"' : ''; ?>>
							<label for="wp_travel_engine_settings[wte_advance_itinerary][chart][data][datasets.data.fill]"
								class="checkbox-label"></label>
						</div>
					</div>
					<div class="wpte-field">
						<label class="wpte-field-label" for="wp_travel_engine_settings[wte_advance_itinerary][chart][data][color]"><?php _e( 'Theme color', 'wte-advanced-itinerary' ); ?></label>
						<input class="wte-chart-config-input wpte-color-picker" data-settings="datasets.data.color" type="hidden" name="wp_travel_engine_settings[wte_advance_itinerary][chart][data][color]" id="wp_travel_engine_settings[wte_advance_itinerary][chart][data][color]" value="<?php echo isset( $options['wte_advance_itinerary']['chart']['data']['color'] ) ? $options['wte_advance_itinerary']['chart']['data']['color'] : '#147dfe'; ?>">
					</div>
					<div class="wpte-field">
						<label for="wp_travel_engine_settings[wte_advance_itinerary][chart][bg]" class="wpte-field-label"><?php esc_html_e( 'Chart Background Image', 'wte-advanced-itinerary' ); ?></label>
						<div class="wte-ai-btn-group">
							<button id="wte-btn-chart-bg-uploader" class="wte-ai-btn wte-btn-chart-bg-uploader"><?php esc_html_e( 'Choose Image', 'wte-advanced-itinerary' ); ?></button>
							<button id="wte-btn-chart-bg-delete" class="wte-ai-btn wte-ai-btn-danger wte-ai-chart-bg-remove"><svg class="svg-inline--fa fa-trash-alt fa-w-14" aria-hidden="true" data-prefix="far" data-icon="trash-alt" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg=""><path fill="currentColor" d="M192 188v216c0 6.627-5.373 12-12 12h-24c-6.627 0-12-5.373-12-12V188c0-6.627 5.373-12 12-12h24c6.627 0 12 5.373 12 12zm100-12h-24c-6.627 0-12 5.373-12 12v216c0 6.627 5.373 12 12 12h24c6.627 0 12-5.373 12-12V188c0-6.627-5.373-12-12-12zm132-96c13.255 0 24 10.745 24 24v12c0 6.627-5.373 12-12 12h-20v336c0 26.51-21.49 48-48 48H80c-26.51 0-48-21.49-48-48V128H12c-6.627 0-12-5.373-12-12v-12c0-13.255 10.745-24 24-24h74.411l34.018-56.696A48 48 0 0 1 173.589 0h100.823a48 48 0 0 1 41.16 23.304L349.589 80H424zm-269.611 0h139.223L276.16 50.913A6 6 0 0 0 271.015 48h-94.028a6 6 0 0 0-5.145 2.913L154.389 80zM368 128H80v330a6 6 0 0 0 6 6h276a6 6 0 0 0 6-6V128z"></path></svg></button>
						</div>
					</div>
				</div>
				<div class="wpte-ai-col wpte-chart-preview-wrap">
					<button id="wte-chart-preview-btn" class="wte-ai-btn chart-is-active"><?php echo esc_html__( 'Randomize', 'wte-advanced-itinerary' ); ?>
						<span><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 460.801 460.801" style="enable-background:new 0 0 460.801 460.801;" xml:space="preserve"><g><g><path d="M231.298,17.068c-57.746-0.156-113.278,22.209-154.797,62.343V17.067C76.501,7.641,68.86,0,59.434,0 S42.368,7.641,42.368,17.067v102.4c-0.002,7.349,4.701,13.874,11.674,16.196l102.4,34.133c8.954,2.979,18.628-1.866,21.606-10.82 c2.979-8.954-1.866-18.628-10.82-21.606l-75.605-25.156c69.841-76.055,188.114-81.093,264.169-11.252 s81.093,188.114,11.252,264.169s-188.114,81.093-264.169,11.252c-46.628-42.818-68.422-106.323-57.912-168.75 c1.653-9.28-4.529-18.142-13.808-19.796s-18.142,4.529-19.796,13.808c-0.018,0.101-0.035,0.203-0.051,0.304 c-2.043,12.222-3.071,24.592-3.072,36.983C8.375,361.408,107.626,460.659,230.101,460.8 c122.533,0.331,222.134-98.734,222.465-221.267C452.896,117,353.832,17.399,231.298,17.068z"/></g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg></span>
					</button>
					<div class="altitude-chart-container">
						<div class='altitude-unit-switcher'>
							<label class="elevation-unit-label"><?php echo esc_html( $elevation_unit_label ); ?></label>
							<div class="altitude-unit-switches">
								<span><input type='radio' checked value='m'
										name='elevation-unit'  id="elevation-unit-m" /><label for="elevation-unit-m"><?php echo esc_html( $labels['m'] ); ?></label></span>
								<span><input type='radio' value='ft'
										name='elevation-unit' id="elevation-unit-ft"/><label for="elevation-unit-ft"><?php echo esc_html( $labels['ft'] ); ?></label></span>
							</div>
						</div>
						<?php
						$attachment_id = isset( $options['wte_advance_itinerary']['chart']['bg'] ) ? $options['wte_advance_itinerary']['chart']['bg'] : '';
						$attachment_src = ! empty( $attachment_id ) ? wp_get_attachment_image_src( $attachment_id, 'full' )[0] : '';
						?>
						<input type="hidden" id="wp_travel_engine_settings[wte_advance_itinerary][chart][bg]" name="wp_travel_engine_settings[wte_advance_itinerary][chart][bg]" value="<?php echo esc_attr( $attachment_id ); ?>"/>
						<div id="altitiude-chart-screen-wrapper" class="screen-canvas-wrap" style="background-image:url(<?php echo esc_url( $attachment_src ); ?>)">
							<div id="altitude-chart-screen" style="height:450px;">
								<canvas <?php echo esc_html( $html_atts ); ?>></canvas>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
