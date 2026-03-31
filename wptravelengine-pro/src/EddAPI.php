<?php
/**
 * EDD API Helper Class.
 *
 * @since 1.0.6
 */

namespace WPTravelEnginePro;

/**
 * EDD API Helper class.
 *
 * @since 1.0.6
 */
class EddAPI {

	public string $store_url;

	protected int $health_check_timeout;

	public function __construct( string $api_url ) {
		$this->store_url = $api_url;
		$this->health_check_timeout = 5;
	}

	protected function verify_ssl(): bool {
		return (bool) apply_filters( 'edd_sl_api_request_verify_ssl', true, $this );
	}

	public function test(): bool {
		global $edd_plugin_url_available;

		$store_hash = md5( $this->store_url );
		if ( ! is_array( $edd_plugin_url_available ) || ! isset( $edd_plugin_url_available[ $store_hash ] ) ) {
			$test_url_parts = parse_url( $this->store_url );

			$scheme = ! empty( $test_url_parts[ 'scheme' ] ) ? $test_url_parts[ 'scheme' ] : 'http';
			$host   = ! empty( $test_url_parts[ 'host' ] ) ? $test_url_parts[ 'host' ] : '';
			$port   = ! empty( $test_url_parts[ 'port' ] ) ? ':' . $test_url_parts[ 'port' ] : '';

			if ( empty( $host ) ) {
				$edd_plugin_url_available[ $store_hash ] = false;
			} else {
				$test_url                                = $scheme . '://' . $host . $port;
				$response                                = wp_remote_get(
					$test_url,
					array(
						'timeout'   => $this->health_check_timeout,
						'sslverify' => $this->verify_ssl(),
					)
				);
				$edd_plugin_url_available[ $store_hash ] = ! is_wp_error( $response );
			}
		}

		return false !== $edd_plugin_url_available[ $store_hash ];

	}

	public function check_version( array $args ) : array {

		$response = wp_remote_post(
			$this->store_url,
			array(
				'timeout'   => $this->health_check_timeout,
				'sslverify' => $this->verify_ssl(),
				'body'      => array_merge(
					array( 'edd_action' => 'get_version' ),
					$args
				),
			)
		);

		if ( ! is_wp_error( $response ) && 200 === wp_remote_retrieve_response_code( $response ) ) {
			return (array) json_decode( wp_remote_retrieve_body( $response ) );
		}

		return array();
	}

	public function set_timeout( int $timeout ) {
		$this->health_check_timeout = $timeout;
	}

}
