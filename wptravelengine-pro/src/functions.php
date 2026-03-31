<?php
/**
 * Helper functions for WP Travel Engine Pro.
 *
 * @since 1.0.0
 */

use WPTravelEnginePro\Admin\EDDPluginsResponse;
use WPTravelEnginePro\EddAPI;
use WPTravelEnginePro\Extension;
use WPTravelEnginePro\ExtensionLoader;
use WPTravelEnginePro\Extensions;
use WPTravelEnginePro\Request;
use WPTravelEnginePro\Slug;
use WPTravelEnginePro\Store;

/**
 * @param string $view
 * @param array  $args
 *
 * @return void
 */
function wptravelengine_pro_view( string $view, array $args = array() ) {
	$view_path = WPTRAVELENGINE_PRO_DIR__ . "/views/{$view}.php";

	if ( file_exists( $view_path ) ) {
		extract( $args );

		include $view_path;
	}
}

/**
 * Get the version of WP Travel Engine.
 *
 * @return string
 * @since 1.0.0
 */
function wptravelengine_pro_core_version(): string {
	$plugin_meta = wptravelengine_pro_get_core_plugin_meta();

	return $plugin_meta->Version ?? '0.0.0';
}

function wptravelengine_pro_get_core_plugin_meta(): ?object {
	static $plugin_meta = null;

	if ( $plugin_meta ) {
		return $plugin_meta;
	}

	if ( ! function_exists( 'get_plugins' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}

	$plugins = get_plugins();

	$plugin = array_find(
		array_keys( $plugins ),
		function ( $plugin ) {
			return preg_match( '#/wp-travel-engine\.php$#', $plugin );
		}
	);

	$plugin_meta = (object) $plugins[ $plugin ];

	return $plugin_meta;
}

/**
 * Check if the core plugin is active.
 *
 * @return bool
 * @since 1.0.0
 */
function wptravelengine_pro_is_core_active(): bool {
	if ( is_multisite() ) {
		$active_plugins = array_keys( get_site_option( 'active_sitewide_plugins', array() ) );
	} else {
		$active_plugins = get_option( 'active_plugins', array() );
	}

	return (bool) array_find(
		$active_plugins,
		function ( $plugin ) {
			return preg_match( '#/wp-travel-engine\.php$#', $plugin );
		}
	);
}

if ( ! function_exists( 'array_find' ) ) {
	/**
	 * Find a value in an array.
	 *
	 * @param array    $array The array to search.
	 * @param callable $callback The callback function to use.
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	function array_find( array $array, callable $callback ) {
		foreach ( $array as $key => $value ) {
			if ( $callback( $value, $key ) ) {
				return $value;
			}
		}

		return null;
	}
}

/**
 * Get the PRO extensions.
 *
 * @param bool $refresh
 *
 * @return array
 * @since 1.0.0
 */
function wptravelengine_pro_get_extensions( bool $refresh = false ): array {
	return Extensions::instance()->get_extensions( $refresh );
}

/**
 * Load Extension.
 *
 * @param array $args
 *
 * @return Extension|null
 * @since 1.0.0
 */
function wptravelengine_pro_load_extension( array $args ): ?Extension {

	$extension = Extensions::instance()->get_extension( $args['id'] );

	if ( ! $extension instanceof Extension ) {
		$extension = Extension::from_plugin_meta( $args['file_path'] );
	}

	$extension_loader = new ExtensionLoader( $extension, $args );
	$extension_loader->load();

	return $extension;
}

/**
 * Create an object of Request class.
 *
 * @param string|null $method
 *
 * @return Request
 */
function wptravelengine_pro_create_request( ?string $method = null ): Request {
	$method = $method ?? $_SERVER['REQUEST_METHOD'];

	$request = new Request( $method );

	$request->set_body( file_get_contents( 'php://input' ) );
	$request->set_query_params( $_GET );
	$request->set_header( 'Content-Type', 'application/json' );
	$request->set_body_params( $_POST );
	$request->set_file_params( $_FILES );

	return $request;
}

/**
 * Get products from WP Travel Engine store.
 *
 * @param string $type The type of products to get.
 * @param array  $args The arguments to pass to the API.
 * @param string $store_url The store URL.
 * @param bool   $refresh Whether to refresh the cache.
 *
 * @return mixed
 */
function wptravelengine_pro_get_products_from_store( string $type = 'addons', array $args = array(), string $store_url = '', bool $refresh = false ) {

	if ( empty( $store_url ) ) {
		$store_url = WP_TRAVEL_ENGINE_STORE_URL;
	}

	$links_by_type = (object) array(
		'addons'   => 'add-ons',
		'themes'   => 'travel-wordpress-themes',
		'services' => 'services',
	);

	$args = wp_parse_args(
		$args,
		array(
			'category' => $links_by_type->{$type} ?? $type,
			'number'   => '10',
			'orderby'  => 'menu_order',
			'order'    => 'asc',
		)
	);

	$cache_key = 'wptravelengine_store_' . md5( $type . serialize( $args ) . $store_url );

	$products = $refresh ? false : get_transient( $cache_key );

	if ( ! $products ) {
		$response = wp_safe_remote_get( add_query_arg( $args, $store_url . '/edd-api/v2/products/' ) );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$raw_data = wp_remote_retrieve_body( $response );

		if ( ! json_decode( $raw_data ) ) {
			return new WP_Error( 'edd_api_error', __( 'Invalid API response' ) );
		}

		$products = $raw_data;
		set_transient( $cache_key, $raw_data, 48 * HOUR_IN_SECONDS );
	}

	return json_decode( $products );
}

/**
 * Get the saved license key.
 *
 * @param string $search_key The key to search for.
 *
 * @return string
 */
function wptravelengine_pro_get_saved_license_key( string $search_key ): string {
	$license_keys = wptravelengine_pro_get_license_option( 'wp_travel_engine_license', array() );

	$key_mappings = array(
		'wp-travel-engine-trip-fixed-starting-dates'     => 'wte_fixed_starting_dates_license_key',
		'wp-travel-engine-trip-fixed-starting-dates-countdown' => 'wte_fixed_starting_dates_countdown_license_key',
		'wp-travel-engine-group-discount'                => 'wte_group_discount_license_key',
		'wp-travel-engine-extra-services'                => 'wte_extra_services_license_key',
		'wp-travel-engine-advanced-itinerary-builder'    => 'wte_advanced_itinerary_license_key',
		'wp-travel-engine-form-editor'                   => 'wte_form_editor_license_key',
		'wp-travel-engine-currency-converter'            => 'wte_currency_converter_license_key',
		'wptravelengine-custom-booking-link'             => 'wptravelengine_custom_booking_link_license_key',
		'wp-travel-engine-file-downloads'                => 'wte_file_downloads_license_key',
		'wp-travel-engine-hbl-payment-gateway'           => 'wte_hbl_payment_license_key',
		'himalayan-bank-payment-gateway'                 => 'wte_hbl_payment_license_key',
		'wp-travel-engine-itinerary-downloader'          => 'wte_itinerary_downloader_license_key',
		'wp-travel-engine-midtrans-payment-gateway'      => 'wte_midtrans_license_key',
		'wp-travel-engine-partial-payment'               => 'wte_partial_payment_license_key',
		'wp-travel-engine-payfast-payment-gateway'       => 'wte_payfast_license_key',
		'wp-travel-engine-payhere-payment-gateway'       => 'wte_payhere_payment_license_key',
		'wp-travel-engine-paypal-express-gateway'        => 'wte_paypal_express_license_key',
		'wp-travel-engine-payu-payment-gateway'          => 'wte_payu_license_key',
		'payu-biz-payment-gateway'                       => 'wte_payu_license_key',
		'wp-travel-engine-authorize-net-payment-gateway' => 'wte_authorize_net_license_key',
		'wp-travel-engine-payumoney-payment-gateway'     => 'wte_payu_money_bolt_checkout_license_key',
		'wptravelengine-per-trip-emails'                 => 'wptravelengine_per_trip_emails_license_key',
		'wp-travel-engine-social-proof'                  => 'wte_social_proof_license_key',
		'wp-travel-engine-stripe-payment-gateway'        => 'wte_stripe_gateway_license_key',
		'wp-travel-engine-trip-reviews'                  => 'wte_trip_review_license_key',
		'wp-travel-engine-trips-embedder'                => 'wte_trips_embedder_license_key',
		'wp-travel-engine-trip-weather-forecast'         => 'wte_trip_weather_forecast_license_key',
		'wp-travel-engine-user-history'                  => 'wte_user_history_license_key',
		'wp-travel-engine-zapier'                        => 'wte_zapier_license_key',
		'wp-travel-engine-affiliate-booking'             => 'wte_affiliate_booking_license_key',
		'wptravelengine-woocommerce-payments'            => 'wptravelengine_wc_payments_license_key',
	);

	return $license_keys[ $search_key ] ?? $license_keys[ "wp-travel-engine-{$search_key}" ] ?? $license_keys[ "wptravelengine-{$search_key}" ] ?? $license_keys[ $key_mappings[ $search_key ] ?? 'invalid' ] ?? '';
}

/**
 * Get the product categories.
 *
 * @param string $taxonomy The taxonomy to get.
 * @param array  $args The arguments to pass to the API.
 * @param string $store_url The store URL.
 * @param bool   $refresh Whether to refresh the cache.
 *
 * @return array
 */
function wptravelengine_pro_get_product_categories( string $taxonomy, array $args = array(), string $store_url = '', bool $refresh = false ): array {

	if ( empty( $store_url ) ) {
		$taxonomy  = trim( $taxonomy, '/' );
		$store_url = trailingslashit( WP_TRAVEL_ENGINE_STORE_URL ) . "wp-json/wp/v2/{$taxonomy}";
	}

	$cache_key = 'wptravelengine_store_' . md5( $taxonomy . serialize( $args ) . $store_url );

	$categories = $refresh ? false : get_transient( $cache_key );

	if ( ! $categories ) {
		$response = wp_safe_remote_get( add_query_arg( $args, $store_url ) );

		if ( is_wp_error( $response ) ) {
			return array();
		}

		$raw_data = wp_remote_retrieve_body( $response );

		if ( ! json_decode( $raw_data ) ) {
			return array();
		}
		$categories = $raw_data;
		set_transient( $cache_key, $raw_data, 48 * HOUR_IN_SECONDS );
	}

	return json_decode( $categories );
}

/**
 * @param int    $id
 * @param string $store_url
 * @param array  $args
 *
 * @return EDDPluginsResponse
 */
function wptravelengine_pro_plugin_api_response( int $id, string $store_url, array $args = array() ): EDDPluginsResponse {
	return new EDDPluginsResponse( $id, $store_url, $args );
}

/**
 * Create an instance of the EddAPI class.
 *
 * This function initializes and returns an EddAPI object for interacting
 * with the Easy Digital Downloads API at the specified store URL.
 *
 * @param string $store_url The URL of the store to connect to. Defaults to WP_TRAVEL_ENGINE_STORE_URL.
 *
 * @return EddAPI The initialized EddAPI object.
 * @since 1.0.6
 */
function wptravelengine_pro_edd_api( string $store_url = WP_TRAVEL_ENGINE_STORE_URL ): EddAPI {
	return new EddAPI( $store_url );
}

/**
 * @param string $extension_slug
 * @param bool   $refresh
 *
 * @return array|stdClass
 * @since 1.0.6
 */
function wptravelengine_pro_get_extensions_version( string $extension_slug = '', bool $refresh = false ) {
	$_transient_data = array();

	if ( ! $refresh ) {
		$_transient_data = get_option( '_wptravelengine_pro_extensions_version', array() );
		if ( ! empty( $_transient_data ) ) {
			if ( ! $_transient_data = json_decode( $_transient_data ) ) {
				$_transient_data = (array) $_transient_data;
			}
		}
	}

	if ( empty( $_transient_data ) ) {

		$plugins = new Store();

		$addons = $plugins->get_products(
			'utility,add-ons,pro',
			array(
				'number' => - 1,
			)
		)->products ?? array();

		$items = array();
		foreach ( $addons as $plugin => $data ) {

			$item_id = $data->info->id;

			$slug        = Slug::$map[ $data->info->slug ] ?? $data->info->slug;
			$license_key = wptravelengine_pro_get_saved_license_key( $slug );

			if ( empty( $license_key ) ) {
				continue;
			}

			$plugin_slug           = $data->info->slug;
			$items[ $plugin_slug ] = array(
				'item_id' => $item_id,
				'license' => $license_key,
				'slug'    => $plugin_slug,
				'version' => $data->licensing->version,
				'plugin'  => $plugin,
			);
		}

		$_transient_data = wptravelengine_pro_edd_api()->check_version(
			array(
				'products' => $items,
			)
		);

		update_option( '_wptravelengine_pro_extensions_version', wp_json_encode( $_transient_data ) );
	}

	if ( ! empty( $extension_slug ) ) {
		$slugs_mappings  = array_flip( \WPTravelEnginePro\Slug::$map );
		$_transient_data = array_column( (array) $_transient_data, null, 'slug' );
		$fallback_slug   = preg_replace( '/^(wptravelengine|wp-travel-engine|wte|wpte)-/', '', $extension_slug );
		return $_transient_data[ $extension_slug ] ?? $_transient_data[ $fallback_slug ] ?? $_transient_data[ $slugs_mappings[ $extension_slug ] ?? 'not_exist' ] ?? array();
	}

	return $_transient_data;
}

/**
 * Get license option based on multisite or single site.
 *
 * @since 1.0.9
 * @param string $option_name
 * @param array  $default
 * @return array
 */
function wptravelengine_pro_get_license_option( string $option_name, array $default = array() ) {
	if ( is_multisite() ) {
		$site_option = get_site_option( $option_name, false );
		if ( $site_option !== false && is_array( $site_option ) && count( $site_option ) > 0 ) {
			return $site_option;
		}

		$regular_option = get_option( $option_name, false );
		if ( $regular_option !== false && is_array( $regular_option ) && count( $regular_option ) > 0 ) {
			update_site_option( $option_name, $regular_option );
			return $regular_option;
		}

		return $default;
	}
	return get_option( $option_name, $default );
}

/**
 * Update license option based on multisite or single site.
 *
 * @since 1.0.9
 * @param string $option_name
 * @param array  $value
 * @return bool
 */
function wptravelengine_pro_update_license_option( $option_name, $value ) {
	if ( is_multisite() ) {
		update_site_option( $option_name, $value );
		update_option( $option_name, $value );
		return true;
	}
	return update_option( $option_name, $value );
}

/**
 * Get license status based on multisite or single site.
 *
 * @since 1.0.9
 * @param string $option_name
 * @param string $default
 * @return string
 */
function wptravelengine_pro_get_license_status( $option_name, $default = '' ) {
	if ( is_multisite() ) {
		$site_option = get_site_option( $option_name, false );
		if ( $site_option !== false && ! empty( $site_option ) ) {
			return $site_option;
		}

		$regular_option = get_option( $option_name, false );
		if ( $regular_option !== false && ! empty( $regular_option ) ) {
			update_site_option( $option_name, $regular_option );
			return $regular_option;
		}

		return $default;
	}

	return get_option( $option_name, $default );
}

/**
 * Update license status based on multisite or single site.
 *
 * @since 1.0.9
 * @param string $option_name
 * @param string $value
 * @return bool
 */
function wptravelengine_pro_update_license_status( $option_name, $value ) {
	if ( is_multisite() ) {
		update_site_option( $option_name, $value );
		update_option( $option_name, $value );
		return true;
	}

	return update_option( $option_name, $value );
}
