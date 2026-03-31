<?php
/**
 * Plugin License page.
 */

//dd( $extensions, false );
?>
<div class="wpte-main-wrap wte-license-key">
	<div class="wpte-tab-sub wpte-horizontal-tab">
		<form method="post" action="options.php">
			<?php wp_nonce_field( 'wp_travel_engine_license_nonce', 'wp_travel_engine_license_nonce' ); ?>

			<?php settings_fields( 'wp_travel_engine_license' ); ?>
			<div class="wpte-tab-wrap">
				<a href="javascript:void(0);"
				   class="wpte-tab wte-addons current"><?php esc_html_e( 'WP Travel Engine Addons', 'wptravelengine-pro' ); ?></a>
			</div>

			<div class="wpte-tab-content-wrap">
				<div class="wpte-tab-content wte-addons-content current">
					<div class="wpte-title-wrap">
						<h2 class="wpte-title"><?php esc_html_e( 'License Keys', 'wptravelengine-pro' ); ?></h2>
						<div class="settings-note">
							<?php esc_html_e( 'All of the premium addon installed and activated on your website has been listed below. You can add/edit and manage your License keys for each addon individually.', 'wptravelengine-pro' ); ?>
						</div>
					</div> <!-- .wpte-title-wrap -->

					<div class="wpte-block-content">
						<input type="hidden" name="addon_name" class="addon_name" value="" />
						<div class="wptravelengine-pro_extension-license-fields" data-fields-container>
							<?php
							wptravelengine_pro_view(
								'components/content-license-fields',
								array( 'extensions' => wptravelengine_pro_get_extensions() )
							);
							?>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div><!-- .wpte-main-wrap -->
