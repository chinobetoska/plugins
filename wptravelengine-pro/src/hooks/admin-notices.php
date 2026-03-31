<?php
/**
 * Admin Notices.
 */

use WPTravelEnginePro\AdminNotices;

add_action( 'admin_notices', function () {

	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$tracking = get_option( 'wptravelengine_site_tracking', false );

	if ( $tracking ) {
		return;
	}
	?>
	<div class="notice notice-error is-dismissible">
		<p><strong><?php esc_html_e( 'We\'re improving WP Travel Engine!' ); ?></strong></p>
		<p>
			<?php
			echo wp_kses_post(
				sprintf(
					__( 'We\'re working on improving WP Travel Engine and would like to collect some usage data. This will help us enhance performance, add new features, and improve support. Your decision won\'t affect plugin functionality. %s' ),
					'<a href="https://wptravelengine.com/share-usage-data/" target="_blank">' . esc_html__( 'Learn more', 'wptravelengine-pro' ) . '</a>'
				)
			);
			?>
		</p>
		<div style="display: flex;margin: 10px 0;gap:10px;">
			<form method="post">
				<?php wp_nonce_field( 'wptravelengine_site_tracking' ); ?>
				<input type="hidden" name="wptravelengine_site_tracking" value="yes">
				<input
					class="button button-primary"
					type="submit"
					value="<?php echo __( 'Accept Tracking' ); ?>" />
			</form>
			<form method="post">
				<?php wp_nonce_field( 'wptravelengine_site_tracking' ); ?>
				<input type="hidden" name="wptravelengine_site_tracking" value="no" />
				<input
					class="button"
					type="submit"
					value="<?php echo __( 'Decline Tracking' ); ?>" />
			</form>
		</div>
	</div>
	<?php
} );
