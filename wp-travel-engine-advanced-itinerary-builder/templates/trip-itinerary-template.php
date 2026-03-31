<?php
	global $post;
	$wp_travel_engine_tabs   = get_post_meta( $post->ID, 'wp_travel_engine_setting', true );
	$itinerary_info          = wptravelengine_advanced_itinerary_get_info( $post->ID );
	$itinerary_info_position = wptravelengine_advanced_itinerary_info_position( $post->ID );
?>
	<div class="wte-itinerary-header-wrapper">
		<?php
		do_action( 'wte_before_itinerary_content' );
		?>
		<?php
		ob_start();
			$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings' );
			$enabled_expand_all        = ( isset( $wp_travel_engine_settings['wte_advance_itinerary']['enable_expand_all'] ) ) ? 'enabled' : '';
		?>
		<div class="wp-travel-engine-itinerary-header">
			<?php
			/**
			 * Hook - Display tab content title, left for themes.
			 */
			do_action( 'wte_itinerary_tab_title' );
			?>
			<div class="aib-button-toggle toggle-button expand-all-button">
				<label for="itinerary-toggle-button" class="aib-button-label"><?php echo esc_html__( 'Expand all', 'wte-advanced-itinerary' ); ?></label>
				<input id="itinerary-toggle-button" type="checkbox" class="checkbox" <?php echo ! empty( $enabled_expand_all ) ? 'checked' : ''; ?>>
			</div>

		</div>
		<?php
		/**
		 * Added to do stuffs after Itinerary header.
		 * Itinerary chart rendering hooked in to this.
		 *
		 * @since 2.0.7
		 */
		do_action( 'wte_after_itinerary_header' );

		$itinerary_trip_header_section = ob_get_contents();
		ob_end_clean();
		echo apply_filters( 'advanced_itinerary_trip_header_section', $itinerary_trip_header_section );
		?>
	</div>
	<div class="post-data itinerary">
		<?php

		$wte_advanced_itinerary = get_post_meta( $post->ID, 'wte_advanced_itinerary', true );
		$maxlen                 = max( array_keys( $wp_travel_engine_tabs['itinerary']['itinerary_title'] ) );
		$arr_keys               = array_keys( $wp_travel_engine_tabs['itinerary']['itinerary_title'] );
		foreach ( $arr_keys as $key => $value ) {
			if ( array_key_exists( $value, $wp_travel_engine_tabs['itinerary']['itinerary_title'] ) ) {
				?>
		<div id="advanced-itinerary-tabs<?php echo esc_attr( $value ); ?>" data-id="<?php echo esc_attr( $value ); ?>"
			class="itinerary-row advanced-itinerary-row <?php echo ! empty( $enabled_expand_all ) ? 'active' : ''; ?>">
			<div class="wte-itinerary-head-wrap">
				<div class="title">
					<?php if ( $wp_travel_engine_tabs['trip_duration_unit'] === 'days' || ! empty( $wp_travel_engine_tabs['itinerary']['itinerary_days_label'][ $value ] ?? '' ) ) : ?>
						<span class="itinerary-day">
							<?php
							if ( ! empty( $wp_travel_engine_tabs['itinerary']['itinerary_days_label'][ $value ] ?? '' ) ) {
								echo $itinerary_days_label_header = esc_attr( $wp_travel_engine_tabs['itinerary']['itinerary_days_label'][ $value ] );
							} else {
								_e( 'Day ', 'wte-advanced-itinerary' ) . ' ' . _e( esc_attr( str_pad( $value, 2, '0', STR_PAD_LEFT ) ), 'wte-advanced-itinerary' );
							}
							echo ' : ';
							?>
						</span>
					<?php endif; ?>
				</div>
				<span class="accordion-tabs-toggle <?php echo ! empty( $enabled_expand_all ) ? 'active' : ''; ?>">
					<span class="dashicons dashicons-arrow-down custom-toggle-tabs rotator <?php echo ! empty( $enabled_expand_all ) ? 'open' : ''; ?>"></span>
					<div class="itinerary-title">
						<span><?php echo ( isset( $wp_travel_engine_tabs['itinerary']['itinerary_title'][ $value ] ) ? esc_attr( $wp_travel_engine_tabs['itinerary']['itinerary_title'][ $value ] ) : '' ); ?></span>
					</div>
				</span>
			</div>

				<?php echo ! empty( $enabled_expand_all ) ? '<style id="itinerary-content-show"> .itinerary-content{ disply:block!important; } </style>' : ''; ?>

			<div class="itinerary-content <?php echo ! empty( $enabled_expand_all ) ? 'show' : ''; ?>" <?php echo ! empty( $enabled_expand_all ) ? 'style="display:block;"' : ''; ?>>
				<div class="content">
					<?php
					if ( $itinerary_info_position === 'below_title' ) :
						include WTEAD_FILE_ROOT_DIR . '/templates/trip-itinerary-info-template.php';
					endif;
					?>
					<p>
					<?php
					if ( isset( $wp_travel_engine_tabs['itinerary']['itinerary_content_inner'][ $value ] ) && $wp_travel_engine_tabs['itinerary']['itinerary_content_inner'][ $value ] != '' ) {
								$content_itinerary = wpautop( $wp_travel_engine_tabs['itinerary']['itinerary_content_inner'][ $value ] );
					} else {
						$content_itinerary = wpautop( $wp_travel_engine_tabs['itinerary']['itinerary_content'][ $value ] );
					}
								echo apply_filters( 'the_content', html_entity_decode( $content_itinerary, 3, 'UTF-8' ) );
					?>
							</p>
				</div>
				<?php
				if ( $itinerary_info_position === 'below_description' ) :
					include WTEAD_FILE_ROOT_DIR . '/templates/trip-itinerary-info-template.php';
				endif;
				?>
				<?php
						$itinerary_galleries_ids = isset( $wte_advanced_itinerary['advanced_itinerary']['itinerary_image'][ $value ] ) && ! empty( $wte_advanced_itinerary['advanced_itinerary']['itinerary_image'][ $value ] ) ? $wte_advanced_itinerary['advanced_itinerary']['itinerary_image'][ $value ] : '';
				if ( isset( $itinerary_galleries_ids ) && is_array( $itinerary_galleries_ids ) && ! empty( $itinerary_galleries_ids ) ) {
					?>
				<div class="itenary-detail-gallery">
					<?php
					foreach ( $itinerary_galleries_ids as $keys => $id ) :
							$image_thumbnail = wp_get_attachment_image_src( $id, 'wteai-gallery-thumbnail' );
							$image_full      = wp_get_attachment_image_src( $id, 'large' );
						if ( ! empty( $image_thumbnail ) ) :
							?>
					<a class="itinerary-gallery-link" href="<?php echo esc_url( $image_full[0] ); ?>" data-fancybox="itinerary-gallery">
						<img alt="<?php echo esc_attr( get_post_meta( $id, '_wp_attachment_image_alt', true ) ?? get_the_title( $id ) ); ?>" class='itinerary-indv-image' src='<?php echo $image_thumbnail[0]; ?>'>
					</a>
							<?php
							endif;
								endforeach;
					?>
				</div>
				<?php } ?>

				<?php
				if ( isset( $wte_advanced_itinerary['advanced_itinerary']['itinerary_duration'][ $value ] ) && ! empty( $wte_advanced_itinerary['advanced_itinerary']['itinerary_duration'][ $value ] )
					|| ( isset( $wte_advanced_itinerary['advanced_itinerary']['meals_included'][ $value ] ) )
					|| ( isset( $wte_advanced_itinerary['advanced_itinerary']['sleep_modes'][ $value ] ) && ! empty( $wte_advanced_itinerary['advanced_itinerary']['sleep_modes'][ $value ] ) )
					|| ( isset( $wte_advanced_itinerary['advanced_itinerary']['itinerary_image'][ $value ] ) && ! empty( $wte_advanced_itinerary['advanced_itinerary']['itinerary_image'][ $value ] ) )
					) {
					?>
				<div class="itinerary-detail-additional-info">
					<?php
					if ( isset( $wte_advanced_itinerary['advanced_itinerary']['itinerary_duration'][ $value ] ) && ! empty( $wte_advanced_itinerary['advanced_itinerary']['itinerary_duration'][ $value ] ) ) {
						if ( isset( $wte_advanced_itinerary['advanced_itinerary']['itinerary_duration'][ $value ] ) ) {
							$duration_type_text  = isset( $wte_advanced_itinerary['advanced_itinerary']['itinerary_duration_type'][ $value ] ) ? esc_attr( $wte_advanced_itinerary['advanced_itinerary']['itinerary_duration_type'][ $value ] ) : '';
							$duration_type_text .= ( $wte_advanced_itinerary['advanced_itinerary']['itinerary_duration'][ $value ] > 1 ) ? 's' : '';
						} else {
							$duration_type_text = '';
						}
						?>
					<div class="itinerary-duration">
						<span class="itinierary-icon-wrap">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M12 6.6V12L15.6 13.8M21 12C21 16.9705 16.9705 21 12 21C7.02943 21 3 16.9705 3 12C3 7.02943 7.02943 3 12 3C16.9705 3 21 7.02943 21 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
						</span>
						<span><?php echo ( isset( $wte_advanced_itinerary['advanced_itinerary']['itinerary_duration'][ $value ] ) ? esc_attr( $wte_advanced_itinerary['advanced_itinerary']['itinerary_duration'][ $value ] ) : '' ); ?>
							<?php echo $duration_type_text; ?></span>
					</div>
					<?php } ?>
					<?php if ( isset( $wte_advanced_itinerary['advanced_itinerary']['sleep_modes'][ $value ] ) && ! empty( $wte_advanced_itinerary['advanced_itinerary']['sleep_modes'][ $value ] ) ) { ?>
					<div class="itinerary-sleep-mode">
						<span class="itinierary-icon-wrap">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M22 17V14M22 14H2M22 14H12V9H19C19.7957 9 20.5587 9.31607 21.1213 9.87868C21.6839 10.4413 22 11.2044 22 12V14ZM2 8V17M5 9C5 9.53043 5.21071 10.0391 5.58579 10.4142C5.96086 10.7893 6.46957 11 7 11C7.53043 11 8.03914 10.7893 8.41421 10.4142C8.78929 10.0391 9 9.53043 9 9C9 8.46957 8.78929 7.96086 8.41421 7.58579C8.03914 7.21071 7.53043 7 7 7C6.46957 7 5.96086 7.21071 5.58579 7.58579C5.21071 7.96086 5 8.46957 5 9Z" stroke="currentColor" stroke-width="1.39" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
						</span>
						<span class="label">
							<?php
							if ( isset( $wte_advanced_itinerary['advanced_itinerary']['itinerary_sleep_mode_description'][ $value ] ) && $wte_advanced_itinerary['advanced_itinerary']['itinerary_sleep_mode_description'][ $value ] != '' ) {
								echo '<a href="JavaScript:void(0);">' . esc_attr( $wte_advanced_itinerary['advanced_itinerary']['sleep_modes'][ $value ] ) . '<span>';
								echo '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M20 10C20 15.5228 15.5228 20 10 20C4.47715 20 0 15.5228 0 10C0 4.47715 4.47715 0 10 0C15.5228 0 20 4.47715 20 10ZM10 5C9.44771 5 9 5.44772 9 6C9 6.55228 9.44771 7 10 7H10.01C10.5623 7 11.01 6.55228 11.01 6C11.01 5.44772 10.5623 5 10.01 5H10ZM11 10C11 9.44772 10.5523 9 10 9C9.44771 9 9 9.44772 9 10V14C9 14.5523 9.44771 15 10 15C10.5523 15 11 14.5523 11 14V10Z" fill="currentColor"/></svg>';
								echo '</span></a>';
							} else {
								echo esc_attr( $wte_advanced_itinerary['advanced_itinerary']['sleep_modes'][ $value ] );
							}
							?>
						</span>
					</div>
					<?php } ?>
					<?php if ( isset( $wte_advanced_itinerary['advanced_itinerary']['meals_included'][ $value ] ) ) : ?>
					<div class="itinerary-meals">
						<span class="itinierary-icon-wrap">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M5.60401 2.39992C4.85521 2.39992 4.19401 2.92552 4.05601 3.68152C3.87841 4.66312 3.60001 6.41032 3.60001 7.79992C3.60001 9.27712 4.36321 10.5755 5.51401 11.3231C5.83921 11.5355 6.00001 11.8091 6.00001 12.0431V12.5351C6.00001 12.5567 5.99881 12.5783 5.99641 12.5999C5.96401 12.9119 5.81521 14.3147 5.67481 15.7571C5.53681 17.1791 5.40001 18.6971 5.40001 19.1999C5.40001 19.8364 5.65286 20.4469 6.10295 20.897C6.55304 21.3471 7.16349 21.5999 7.80001 21.5999C8.43653 21.5999 9.04698 21.3471 9.49706 20.897C9.94715 20.4469 10.2 19.8364 10.2 19.1999C10.2 18.6959 10.0632 17.1791 9.92521 15.7571C9.82261 14.7047 9.71541 13.6526 9.60361 12.6011L9.60001 12.5351V12.0431C9.60001 11.8079 9.76081 11.5343 10.086 11.3231C10.6735 10.942 11.1563 10.42 11.4906 9.80467C11.8249 9.18934 12 8.50019 12 7.79992C12 6.41032 11.7216 4.66312 11.544 3.68152C11.4773 3.32025 11.2857 2.99394 11.0027 2.75965C10.7197 2.52537 10.3634 2.39804 9.99601 2.39992C9.58801 2.39992 9.21601 2.55232 8.93521 2.80312C8.61435 2.54233 8.21348 2.39995 7.80001 2.39992C7.36921 2.39992 6.97441 2.55112 6.66481 2.80312C6.37345 2.5426 5.99485 2.39899 5.60401 2.39992ZM7.20001 4.19992C7.20001 4.04079 7.26322 3.88818 7.37574 3.77566C7.48826 3.66314 7.64088 3.59992 7.80001 3.59992C7.95914 3.59992 8.11175 3.66314 8.22427 3.77566C8.33679 3.88818 8.40001 4.04079 8.40001 4.19992V7.79992C8.40001 7.95905 8.46322 8.11166 8.57574 8.22419C8.68826 8.33671 8.84088 8.39992 9.00001 8.39992C9.15914 8.39992 9.31175 8.33671 9.42427 8.22419C9.53679 8.11166 9.60001 7.95905 9.60001 7.79992V3.99592C9.60001 3.94392 9.61025 3.89243 9.63015 3.84438C9.65005 3.79634 9.67922 3.75268 9.71599 3.71591C9.75276 3.67914 9.79642 3.64997 9.84446 3.63007C9.89251 3.61017 9.944 3.59992 9.99601 3.59992C10.1844 3.59992 10.332 3.72952 10.3632 3.89632C10.5396 4.87192 10.8 6.52672 10.8 7.79992C10.8001 8.30037 10.6749 8.79287 10.436 9.2326C10.1971 9.67232 9.85192 10.0453 9.43201 10.3175C8.90521 10.6595 8.40001 11.2607 8.40001 12.0431V12.5351C8.40001 12.5991 8.40321 12.6631 8.40961 12.7271C8.44321 13.0367 8.59081 14.4359 8.73121 15.8735C8.87281 17.3315 9.00001 18.7715 9.00001 19.1999C9.00001 19.5182 8.87358 19.8234 8.64853 20.0485C8.42349 20.2735 8.11827 20.3999 7.80001 20.3999C7.48175 20.3999 7.17652 20.2735 6.95148 20.0485C6.72643 19.8234 6.60001 19.5182 6.60001 19.1999C6.60001 18.7715 6.72721 17.3315 6.86881 15.8735C7.00921 14.4359 7.15681 13.0367 7.19041 12.7271C7.19681 12.6631 7.20001 12.5991 7.20001 12.5351V12.0431C7.20001 11.2607 6.69481 10.6595 6.16801 10.3175C5.74809 10.0453 5.40295 9.67232 5.16402 9.2326C4.92508 8.79287 4.79995 8.30037 4.80001 7.79992C4.80001 6.52792 5.06041 4.87192 5.23681 3.89632C5.25324 3.81133 5.29927 3.73491 5.36672 3.68065C5.43417 3.62638 5.51867 3.59778 5.60521 3.59992C5.71002 3.60024 5.81044 3.6421 5.88444 3.71633C5.95845 3.79056 6.00001 3.8911 6.00001 3.99592V7.79992C6.00001 7.95905 6.06322 8.11166 6.17574 8.22419C6.28826 8.33671 6.44088 8.39992 6.60001 8.39992C6.75914 8.39992 6.91175 8.33671 7.02427 8.22419C7.13679 8.11166 7.20001 7.95905 7.20001 7.79992V4.19992ZM13.2 7.79992C13.2 6.36775 13.7689 4.99424 14.7816 3.98155C15.7943 2.96885 17.1678 2.39992 18.6 2.39992C18.7591 2.39992 18.9117 2.46314 19.0243 2.57566C19.1368 2.68818 19.2 2.84079 19.2 2.99992V11.3735L19.224 11.6423C19.3175 12.7205 19.4087 13.7989 19.4976 14.8775C19.6464 16.6859 19.8 18.6707 19.8 19.1999C19.8 19.8364 19.5471 20.4469 19.0971 20.897C18.647 21.3471 18.0365 21.5999 17.4 21.5999C16.7635 21.5999 16.153 21.3471 15.703 20.897C15.2529 20.4469 15 19.8364 15 19.1999C15 18.6719 15.1536 16.6859 15.3024 14.8775C15.3768 13.9643 15.4524 13.0811 15.5088 12.4271L15.5448 11.9999H15C14.5226 11.9999 14.0648 11.8103 13.7272 11.4727C13.3896 11.1351 13.2 10.6773 13.2 10.1999V7.79992ZM16.7976 11.4527L16.7724 11.7467L16.704 12.5303C16.6476 13.1831 16.572 14.0639 16.4976 14.9759C16.3464 16.8203 16.2 18.7343 16.2 19.1999C16.2 19.5182 16.3264 19.8234 16.5515 20.0485C16.7765 20.2735 17.0817 20.3999 17.4 20.3999C17.7183 20.3999 18.0235 20.2735 18.2485 20.0485C18.4736 19.8234 18.6 19.5182 18.6 19.1999C18.6 18.7343 18.4536 16.8203 18.3024 14.9759C18.2134 13.8993 18.1218 12.8229 18.0276 11.7467L18.0024 11.4539V11.4527L18 11.3999V3.64192C17.0002 3.78623 16.0859 4.28603 15.4248 5.04971C14.7636 5.81339 14.3997 6.78978 14.4 7.79992V10.1999C14.4 10.3591 14.4632 10.5117 14.5757 10.6242C14.6883 10.7367 14.8409 10.7999 15 10.7999H16.2C16.2832 10.7999 16.3656 10.8173 16.4417 10.8508C16.5179 10.8844 16.5863 10.9334 16.6425 10.9948C16.6987 11.0562 16.7415 11.1286 16.7682 11.2074C16.7949 11.2863 16.8049 11.3698 16.7976 11.4527Z" fill="currentColor"/>
							</svg>
						</span> 
						<?php
						// Empty for default case
						$before_meal_string = '';
						$before_meal_string = apply_filters( 'wte_filtered_advanced_itinerary_meal_before_text', $before_meal_string );
						echo $before_meal_string;
						?>
						<span>
							<?php
								$iti_meals_array = apply_filters(
									'wpte_ai_trip_meals_array',
									array(
										'breakfast' => __( 'Breakfast', 'wte-advanced-itinerary' ),
										'lunch'     => __( 'Lunch', 'wte-advanced-itinerary' ),
										'dinner'    => __( 'Dinner', 'wte-advanced-itinerary' ),
									)
								);

								$cloned_meals_inc = $wte_advanced_itinerary['advanced_itinerary']['meals_included'][ $value ];
								$count            = count( $cloned_meals_inc );
								$i                = 1;
								$selected_meals   = array_map( 'strtolower', $cloned_meals_inc );
							foreach ( $selected_meals as $kkey => $vval ) :
								if ( in_array( $vval, $cloned_meals_inc ) ) {
									echo $iti_meals_array[ $vval ];
									if ( $i < $count && $i !== $count ) :
										echo ' + ';
										endif;
								}
								++$i;
								endforeach;
							?>
						</span>
					</div>
					<?php endif; ?>
				</div>
				<?php } ?>
			</div>
				<?php if ( isset( $wte_advanced_itinerary['advanced_itinerary']['itinerary_sleep_mode_description'][ $value ] ) && $wte_advanced_itinerary['advanced_itinerary']['itinerary_sleep_mode_description'][ $value ] != '' ) { ?>
			<div class="content-additional-sleep-mode" id="content-additional-sleep-mode-<?php echo $value; ?>"
				style="display:none;">
				<div class="additional-sleep-mode-inner">
					<a href="javascript:void(0);"
						class="wte-ai-close-button"><?php _e( 'Close', 'wte-advanced-itinerary' ); ?></a>
					<div class="advanced-sleep-mode-content">
						<?php
						if ( isset( $wte_advanced_itinerary['advanced_itinerary']['itinerary_sleep_mode_description'][ $value ] ) && $wte_advanced_itinerary['advanced_itinerary']['itinerary_sleep_mode_description'][ $value ] != '' ) {
							$content_sleep_mode = $wte_advanced_itinerary['advanced_itinerary']['itinerary_sleep_mode_description'][ $value ];
						} else {
							$content_sleep_mode = '';
						}
						echo apply_filters( 'the_content', html_entity_decode( $content_sleep_mode, 3, 'UTF-8' ) );
						?>
					</div>
				</div>
			</div>
			<?php } ?>
		</div>
				<?php
			}
		}
		?>
	</div>
	<div class="wte-ai-overlay"></div>
	<?php
	do_action( 'wte_after_itinerary_content' );
	/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
