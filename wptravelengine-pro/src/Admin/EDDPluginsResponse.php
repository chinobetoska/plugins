<?php
/**
 * Plugins API Response.
 *
 * @package WPTravelEnginePro
 * @since 1.0.0
 */

namespace WPTravelEnginePro\Admin;

use WP_Error;
use WPTravelEnginePro\Abstracts\PluginsAPIResponse;

/**
 * This class acts as a response from the WordPress.org plugins API for PRO extensions.
 */
class EDDPluginsResponse extends PluginsAPIResponse {
	protected array $api_data = array();

	/**
	 * @var int
	 */
	protected int $product_id = 0;

	/**
	 * @var mixed
	 */
	protected string $license_key = '';

	/**
	 * Constructor.
	 */
	public function __construct( $product_id, $store_url, array $api_data ) {

		$this->product_id = $product_id;

		$this->set_store_url( $store_url );
		$this->set_api_data( $api_data );
	}

	public function get_cache_key(): string {
		if ( $this->cache_key ) {
			return $this->cache_key;
		}

		$this->cache_key = $this->generate_cache_key( 'edd_sl_', $this->slug, $this->license_key, $this->beta );

		return $this->cache_key;
	}

	public function fetch_product( int $product_id = null ) {
		$product_id = $product_id ?? $this->product_id;

		$response = wp_remote_get( $this->store_url . 'edd-api/v2/products/?product=' . $product_id );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$response = json_decode( wp_remote_retrieve_body( $response ) );

		if ( ! $response || ! isset( $response->products ) || ! is_array( $response->products ) ) {
			return new WP_Error( 'edd_api_error', __( 'Invalid API response' ) );
		}

		return (object) $response->products[0];
	}

	public function set_api_data( $data ) {
		$this->name        = $data['name'] ?? $this->name;
		$this->slug        = $data['slug'] ?? $this->slug;
		$this->version     = $data['version'] ?? $this->version;
		$this->license_key = $data['license'] ?? $this->license_key;
		$this->beta        = $data['beta'] ?? $this->beta;
		$this->author      = $data['author'] ?? $this->author;
		$this->cache_key   = $this->generate_cache_key( 'edd_sl_', $this->slug, $this->license_key, $this->beta );

		$this->api_data['item_id'] = $data['item_id'] ?? $this->product_id;
		$this->api_data            = $data;
	}

	public function set_version( $version ) {
		$this->version = $version;
	}

	public function set_license_key( $license_key ) {
		$this->license_key = $license_key;
		$this->cache_key   = $this->generate_cache_key( 'edd_sl_', $this->slug, $this->license_key, $this->beta );
	}

	public function beta( $beta = true ) {
		$this->beta      = $beta;
		$this->cache_key = $this->generate_cache_key( 'edd_sl_', $this->slug, $this->license_key, $this->beta );
	}

	public function set_version_info_cache( object $value, string $cache_key = '' ) {
		parent::set_version_info_cache( $value, $this->cache_key );
		delete_option( $this->generate_cache_key( 'edd_api_request_', $this->slug, $this->license_key, $this->beta ) );
	}

	public function get_plugin_information( $action = 'get_version', $cache = true, $expiration = 7 * DAY_IN_SECONDS ) {
		$cache_key = $this->generate_cache_key( 'wptravelengine_pro_cache_', $action, $this->product_id, $this->slug, $this->license_key, $this->beta );

		$response = $cache ? get_transient( $cache_key ) : false;

		if ( ! $response ) {

			if ( get_site_transient( 'wptravelengine_site_forbidden' ) ) {
				return new WP_Error(
					'rate_limited',
					__( 'The store is temporarily unavailable. Please wait a moment and try again.', 'wptravelengine-pro' )
				);
			}

			$response = $this->api_request(
				$action,
				array(
					'slug'   => $this->slug,
					'is_ssl' => is_ssl(),
					'fields' => array(
						'banners' => array(),
						'reviews' => false,
						'icons'   => array(),
					),
				)
			);

			if ( ! is_wp_error( $response ) && $response ) {
				set_transient( $cache_key, wp_json_encode( $response ), $expiration );
			}
		} else {
			$response = json_decode( $response );
		}

		return $response;
	}

	protected function api_request( string $_action, array $_data = array() ) {

		global $edd_plugin_url_available;

		$verify_ssl = $this->verify_ssl();

		$store_hash = md5( $this->store_url );
		if ( ! is_array( $edd_plugin_url_available ) || ! isset( $edd_plugin_url_available[ $store_hash ] ) ) {
			$test_url_parts = parse_url( $this->store_url );

			$scheme = ! empty( $test_url_parts['scheme'] ) ? $test_url_parts['scheme'] : 'http';
			$host   = ! empty( $test_url_parts['host'] ) ? $test_url_parts['host'] : '';
			$port   = ! empty( $test_url_parts['port'] ) ? ':' . $test_url_parts['port'] : '';

			if ( empty( $host ) ) {
				$edd_plugin_url_available[ $store_hash ] = false;
			} else {
				$test_url                                = $scheme . '://' . $host . $port;
				$response                                = wp_remote_get(
					$test_url,
					array(
						'timeout'   => $this->health_check_timeout,
						'sslverify' => $verify_ssl,
					)
				);
				$edd_plugin_url_available[ $store_hash ] = ! is_wp_error( $response );
			}
		}

		if ( false === $edd_plugin_url_available[ $store_hash ] ) {
			return false;
		}

		$data = array_merge( $this->api_data, $_data );

		if ( $data['slug'] != $this->slug ) {
			return false;
		}

		if ( $this->store_url == trailingslashit( home_url() ) ) {
			return false;
		}

		if ( is_multisite() ) {
			$url = network_site_url();
		} else {
			$url = home_url();
		}

		$api_params = array(
			'edd_action' => $_action,
			'license'    => $this->license_key,
			'item_id'    => $this->product_id,
			'version'    => $this->version,
			'slug'       => $this->slug,
			'author'     => $this->author,
			'url'        => $url,
			'beta'       => $this->beta,
		);

		$response = wp_remote_post(
			$this->store_url,
			array(
				'timeout'   => 30,
				'sslverify' => $verify_ssl,
				'body'      => $api_params,
			)
		);

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$response_code = wp_remote_retrieve_response_code( $response );

		$error_msg = '';
		if ( 403 === $response_code ) {
			$error_msg = __( 'Access forbidden. Please contact support for more information.', 'wptravelengine-pro' );
		} elseif ( 429 === $response_code ) {
			$error_msg = __( 'Too Many Requests.', 'wptravelengine-pro' );
		}

		if ( $error_msg ) {
			set_site_transient( 'wptravelengine_site_forbidden', time(), 5 * MINUTE_IN_SECONDS );
			return new WP_Error( 'forbidden', $error_msg );
		}

		$response = json_decode( wp_remote_retrieve_body( $response ) );

		if ( $response ) {
			$this->prepare_response( $response );
		}

		return $response;
	}

	protected function verify_ssl(): bool {
		return (bool) apply_filters( 'edd_sl_api_request_verify_ssl', true, $this );
	}

	protected function prepare_response( $response ) {

		if ( $response && isset( $response->sections ) ) {
			$response->sections = maybe_unserialize( $response->sections );
		} else {
			$response = false;
		}

		if ( $response && isset( $response->banners ) ) {
			$response->banners = maybe_unserialize( $response->banners );
		}

		if ( $response && isset( $response->icons ) ) {
			$response->icons = maybe_unserialize( $response->icons );
		}

		if ( ! empty( $response->sections ) ) {
			foreach ( $response->sections as $key => $section ) {
				$response->$key = (array) $section;
			}
		}

		return $response;
	}
}
