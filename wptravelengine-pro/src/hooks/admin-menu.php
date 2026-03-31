<?php
/**
 * Admin Menu.
 */
add_filter( 'wptravelengine-admin:boooking:submenus', function ( $menus ) {
	if ( isset( $_GET['tab'] ) && 'consent-settings' === sanitize_text_field( $_GET['tab'] ) ) {
		$menus['class-wp-travel-engine-admin.php']['callback'] = function() {
			$usage_site_tracking = get_option( 'wptravelengine_site_tracking', 'no' );
			?>
			<form method="post">
				<?php wp_nonce_field( 'wptravelengine_site_tracking' ); ?>
				<table class="form-table">
					<tbody>
					<tr>
						<th scope="row"><?php echo esc_html__( 'Usage Data Sharing' ); ?></th>
						<td>
							<fieldset>
								<legend class="screen-reader-text"><span>Help us improve WP Travel Engine by opting in to share non-sensitive plugin usage data. Learn More</span>
								</legend>
								<label for="usage_tracking">
									<input name="wptravelengine_site_tracking" checked type="hidden" value="no">
									<input
										<?php checked( $usage_site_tracking, 'yes' ); ?>
										name="wptravelengine_site_tracking" type="checkbox" id="usage_tracking" value="yes">
									<?php
									echo sprintf(
										__('Help us improve WP Travel Engine by opting in to share non-sensitive plugin usage data. %s' ),
										'<a href="https://wptravelengine.com/share-usage-data/" target="_blank">' . __( 'Learn More', 'wp-travel-engine' ) . '</a>'
									);
									?>
								</label>
							</fieldset>
						</td>
					</tr>
					</tbody>
				</table>
				<p class="submit">
					<input type="submit" name="submit" id="submit" class="button button-primary"
					       value="<?php echo __( 'Save Changes' ); ?>">
				</p>
			</form>
			<?php
		};
	}
	return $menus;
} );
