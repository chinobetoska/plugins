<?php
/**
 * Hooks - admin_init.
 */

use WPTravelEnginePro\Admin\Controllers\PluginInstall;
use WPTravelEnginePro\Extension;

add_action( 'current_screen', function ( WP_Screen $screen ) {
	if ( 'booking_page_extensions' === $screen->id || 'booking_page_wp_travel_engine_license_page' === $screen->id ) {
		wp_safe_redirect( add_query_arg( array( 'tab' => 'wptravelengine' ), admin_url( 'plugin-install.php' ) ) );
		exit;
	}

	if ( in_array( $screen->id, [ 'plugin-install', 'plugin-install-network' ], true ) ) {
		$pluginInstallController = PluginInstall::instance();
		$pluginInstallController->hooks();
	}
} );

add_action( 'admin_init', function () {
	$plugin_in_used_version = get_option( 'wptravelengine_pro_installed_since', false );
	if ( ! $plugin_in_used_version ) {
		set_transient( 'wptravelengine_pro_extension_usage_expiration', strtotime( "+5 days", time() ), 30 );
		update_option( 'wptravelengine_pro_installed_since', WPTRAVELENGINE_PRO_VERSION );
		wp_safe_redirect( add_query_arg( array( 'tab' => 'wptravelengine' ), admin_url( 'plugin-install.php' ) ) );
		exit;
	}
} );

add_filter( 'plugins_api', array( PluginInstall::class, 'plugins_api' ), 25, 3 );
add_filter( 'pre_set_site_transient_update_plugins', array( PluginInstall::class, 'check_update' ) );

add_filter( 'plugin_row_meta', function ( $plugin_meta, $plugin_file, $plugin_data ) {
	if ( empty( $plugin_data['WTE'] ) ) {
		return $plugin_meta;
	}

	// Get homepage data from store products list if missing.
	if ( empty( $plugin_data['homepage'] ) ) {
		$wte_data = $plugin_data['WTE'];

		if ( is_string( $wte_data ) && strpos( $wte_data, ':' ) !== false ) {
			$parts = explode( ':', $wte_data, 2 );
			$product_id = isset( $parts[0] ) ? absint( $parts[0] ) : 0;

			if ( $product_id > 0 && function_exists( 'wptravelengine_get_products_from_store' ) ) {
				$products = wptravelengine_get_products_from_store( 'addons' );

				if ( is_array( $products ) ) {
					foreach ( $products as $product ) {
						// Match by product ID.
						if ( isset( $product->info->id ) && absint( $product->info->id ) === $product_id ) {
							// Build homepage path from permalink.
							if ( ! empty( $product->info->permalink ) ) {
								$plugin_data['homepage'] = $product->info->permalink;
							}
							break;
						}
					}
				}
			}
		}
	}

	$plugin_url = '';

	if ( ! empty( $plugin_data['homepage'] ) ) {
		$base_url = $plugin_data['PluginURI'] ?? 'https://wptravelengine.com';
		$homepage = trim( $plugin_data['homepage'], '/' );

		// Build plugin URL based on base URL type.
		$plugin_url = strpos( $base_url, 'https://wptravelengine.com/plugins/' ) === 0
			? $base_url
			: trailingslashit( $base_url ) . $homepage;

		// Ensure URL has proper protocol.
		if ( strpos( $plugin_url, 'http://' ) !== 0 && strpos( $plugin_url, 'https://' ) !== 0 ) {
			$plugin_url = 'https://wptravelengine.com/' . ltrim( $plugin_url, '/' );
		}

		// Add UTM parameters.
		$plugin_url = add_query_arg(
			[
				'utm_source'   => 'free_plugin',
				'utm_medium'   => 'pro_addon',
				'utm_campaign' => 'upgrade_to_pro',
			],
			$plugin_url
		);
	}

	if ( ! empty( $plugin_url ) && filter_var( $plugin_url, FILTER_VALIDATE_URL ) ) {
		$plugin_name = $plugin_data['name'] ?? $plugin_data['Name'] ?? '';

		$plugin_meta[2] = sprintf(
			'<a href="%1$s" target="_blank" rel="noopener noreferrer" aria-label="%2$s">%3$s</a>',
			esc_url( $plugin_url ),
			esc_attr( sprintf( __( 'Visit plugin site for %s', 'wptravelengine-pro' ), $plugin_name ) ),
			esc_html__( 'Visit plugin site', 'wptravelengine-pro' )
		);
	}

	return $plugin_meta;
}, 10, 3 );

add_action( 'admin_init', function () {
	if ( isset( $_POST[ 'wptravelengine_site_tracking' ] ) ) {
		if ( ! check_admin_referer( 'wptravelengine_site_tracking' ) ) {
			return;
		}

		$site_tracking = sanitize_text_field( wp_unslash( $_POST[ 'wptravelengine_site_tracking' ] ) );

		update_option( 'wptravelengine_site_tracking', $site_tracking );
	}
} );

add_action( 'activated_plugin', function () {
	update_option( 'wptravelengine_pro_cron_requires_server_sync', 'yes', true );
} );

add_action( 'deactivated_plugin', function () {
	update_option( 'wptravelengine_pro_cron_requires_server_sync', 'yes', true );
} );
