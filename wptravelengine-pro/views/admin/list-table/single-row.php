<?php

/**
 * Single row for the extensions list table.
 *
 * @since 1.0.0
 */

/**
 * @var stdClass $item
 * @var string $action_links
 * @var string $license_status
 * @var string $message
 * @var string $error
 */
$_license_status    = $license_status ?? 'check-status';
$masked_license_key = ! empty( $item->license_key ) && strlen( $item->license_key ) > 5 ? str_repeat( '*', strlen( $item->license_key ) - 5 ) . substr( $item->license_key, - 5 ) : '';
?>
<div class="plugin-card plugin-card-<?php echo esc_attr( $item->slug ); ?> wpte-addon__card"
	 data-license-status="<?php echo esc_attr( $_license_status ); ?>"
	 data-slug="<?php echo esc_attr( $item->slug ) ?>"
	 data-id="<?php echo esc_attr( $item->id ) ?>"
	 data-license="<?php echo esc_attr( $item->license_key ); ?>">
	<div class="plugin-card-top">
		<div class="wpte-addon__logo">
			<a href="<?php echo esc_url( $item->homepage ) ?>" target="_blank">
				<img src="<?php echo $item->thumbnail; ?>" alt="">
			</a>
		</div>
		<div class="wpte-addon__name">
			<a href="<?php echo esc_url( $item->homepage ) ?>" target="_blank" data-title>
				<?php echo esc_html( $item->name ); ?>
			</a>
		</div>
		<div class="desc column-description">
			<p data-description><?php echo esc_html( $item->short_description ); ?></p>
		</div>
		<div class="wpte-addon__license-key-wrap">
			<div class="wpte-addon__license-key"
				 data-mode="<?php echo esc_attr( ! empty( $item->license_key ) && $_license_status === 'valid' ? 'preview' : 'edit' ); ?>">
				<div class="license-key-preview">
					<input data-license-edit-trigger type="text" class="wptravelengine_preview-license_key"
						   value="<?php echo esc_html( $masked_license_key ); ?>" readonly>
				</div>
				<input type="text" class="wptravelengine_license_key"
					   placeholder="Enter your license key"
					   value="<?php echo esc_attr( $item->license_key ?? '' ) ?>"
					   name="wp_travel_engine_license[<?php echo esc_attr( $item->slug ); ?>]"
					   id="<?php echo esc_attr( "wptravelengine_{$item->slug}_license_key" ); ?>">
				<button
					type="button"
					class="button button-primary activate-license license-action"
					data-slug="<?php echo esc_attr( $item->slug ) ?>"
					data-id="<?php echo esc_attr( $item->id ) ?>"
					data-license-source="#<?php echo esc_attr( "wptravelengine_{$item->slug}_license_key" ); ?>"
					data-license="<?php echo esc_attr( $item->license_key ); ?>"
					data-action="activate_license"
					data-license-status="<?php echo esc_attr( $_license_status ); ?>"
					<?php echo esc_attr( in_array( $_license_status, array( 'expired', 'deactivated', 'failed', 'site_inactive' ), true ) ? '' : 'disabled'  ); ?>
				>
					<?php echo __( 'Activate' ); ?>
				</button>
				<?php if ( 'valid' === $_license_status ) : ?>
					<button
						type="button"
						class="button button-primary deactivate-license license-action"
						data-action="deactivate_license"
						data-license-source="#<?php echo esc_attr( "wptravelengine_{$item->slug}_license_key" ); ?>"
						data-license="<?php echo esc_attr( $item->license_key ); ?>"
						data-slug="<?php echo esc_attr( $item->slug ) ?>"
						data-id="<?php echo esc_attr( $item->id ) ?>"
					>
						<?php echo __( 'Deactivate' ); ?>
					</button>
				<?php endif; ?>
			</div>
			<?php if ( $error ) : ?>
				<div role="alert" class="wptravelengine-pro-notice notice-error">
					<p><?php echo wp_kses_post( $error ) ?></p>
				</div>
			<?php
			elseif ( $message ) : ?>
				<div role="alert" class="wptravelengine-pro-notice notice-success">
					<p><?php echo wp_kses_post( $message ) ?></p>
				</div>
			<?php
			endif;
			?>
		</div>
		<div class="action-links">
			<?php echo $action_links; ?>
			<?php if ( $item->homepage ) : ?>
				<a class="wpte-addon__link"
				   target="_blank"
				   href="<?php echo esc_url( $item->homepage ); ?>">
					<?php echo __( 'View Details' ); ?>
				</a>
			<?php endif; ?>
		</div>
	</div>
	<div class="plugin-card-bottom">
        <span
			class="wpte-addon__version">
			<?php echo sprintf( 'Version: %s', "<span>" . ( ! empty( $item->installed_version ) && version_compare( $item->installed_version, $item->version, '<' ) ? "$item->installed_version < $item->version" : $item->version ) . "</span>" ); ?>
		</span>
	</div>
</div>
