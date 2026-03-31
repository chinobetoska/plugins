<?php
/**
 * Handles addons record and license details for addons.
 *
 * @since 1.0.5
 */

namespace WPTravelEnginePro;

use FilesystemIterator;
use WP_Error;

class Store {

	public string $store_url;

	protected string $cache_dir;

	public function __construct( string $store_url = WP_TRAVEL_ENGINE_STORE_URL ) {

		$this->store_url = trim( $store_url, '/' );
		$this->cache_dir = WP_CONTENT_DIR . '/wptravelengine/cache/';

	}

	/**
	 * @param string $filename
	 * @param mixed $content
	 *
	 * @return bool
	 */
	protected function write( string $filename, $content ): bool {
		$file_path = $this->cache_dir . $filename;

		if ( ! is_dir( $this->cache_dir ) ) {
			mkdir( $this->cache_dir, 0755, true );
		}

		$content = is_string( $content ) ? $content : json_encode( $content, JSON_PRETTY_PRINT );

		return ! ! file_put_contents( $file_path, $content );
	}

	protected function read( string $filename ) {
		$file_path = $this->cache_dir . $filename;

		if ( file_exists( $file_path ) ) {
			return json_decode( file_get_contents( $file_path ) );
		}

		return false;
	}

	protected function delete( string $filename ): bool {
		$file_path = $this->cache_dir . $filename;

		if ( file_exists( $file_path ) ) {
			return unlink( $file_path );
		}

		return false;
	}

	protected function request( $url, $args = array() ) {
		if ( 'POST' === ( $args[ 'method' ] ?? 'GET' ) ) {
			return wp_safe_remote_post( $url, $args );
		}

		return wp_safe_remote_get( $url, $args );
	}

	/**
	 * @param $url
	 * @param array $query_args
	 *
	 * @return array|WP_Error
	 */
	public function get( $url, array $query_args = array() ) {

		$cached = $query_args[ 'cached' ] ?? true;
		unset( $query_args[ 'cached' ] );
		$cache_key = 'wptravelengine_store_' . md5( serialize( $query_args ) . $this->store_url );

		if ( $cached ) {
			if ( $data = $this->read( $cache_key ) ) {
				return $data;
			}
		}

		$response = $this->request( add_query_arg( $query_args, $url ) );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$raw_data = wp_remote_retrieve_body( $response );

		if ( ! is_wp_error( $raw_data ) ) {
			$this->write( $cache_key, $raw_data );
		}

		if ( ! $raw_data = json_decode( $raw_data ) ) {
			return new WP_Error( 'edd_api_error', __( 'Invalid API response' ) );
		}

		return $raw_data;

	}

	public function get_product_categories( string $category, array $query_args = array() ) {
		return $this->get( $this->store_url . "/wp-json/wp/v2/{$category}", $query_args );
	}

	public function get_products( string $type, array $query_args = array() ) {

		$args = wp_parse_args(
			$query_args,
			array(
				'category' => $type,
				'number'   => '10',
				'orderby'  => 'menu_order',
				'order'    => 'asc',
				'cached'   => true,
			)
		);

		return $this->get( $this->store_url . '/edd-api/v2/products/', $args ) ?? array();

	}

	/**
	 * @param array $query_args
	 *
	 * @return array|WP_Error
	 */
	public function get_addons( array $query_args = array() ) {
		return $this->get_products( 'add-ons', $query_args );
	}

	public function get_themes( array $query_args = array() ) {
		return $this->get_products( 'travel-wordpress-themes', $query_args );
	}

	public function get_services( array $query_args = array() ) {
		return $this->get_products( 'services', $query_args );
	}

	public function is_up(): bool {
		return wp_remote_retrieve_response_code( $this->request( $this->store_url, array( 'timeout' => 5 ) ) ) === 200;
	}

	public function refresh() {
		$cached_files = new FilesystemIterator( $this->cache_dir, FilesystemIterator::SKIP_DOTS );

		foreach ( $cached_files as $file ) {
			$_file = $file->getBaseName();
			if ( preg_match( '#^wptravelengine_store_*#', $_file ) ) {
				$this->delete( $_file );
			}
		}

		$this->get_products( 'utility,add-ons,pro', array( 'number' => -1 ) );
		$this->get_product_categories( 'edd-categories', [ 'parent' => 5 ] );
	}

}
