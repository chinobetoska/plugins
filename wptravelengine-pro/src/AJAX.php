<?php
/**
 * Ajax Controller.
 *
 * @since 1.0.0
 */

namespace WPTravelEnginePro;

use Exception;
use stdClass;
use WP_REST_Request;
use WPTravelEnginePro\Admin\Controllers\PluginInstall;

class AJAX {

	protected function create_request(): Request {
		return wptravelengine_pro_create_request();
	}

	/**
	 * Process the ajax request.
	 */
	public function process() {

		$request = $this->create_request();

		$response = (object) array( 'success' => true );
		try {
			if ( ! $this->validate_nonce( 'wptravelengine_pro_nonce', 'nonce', true ) ) {
				throw new Exception( __( 'Invalid or missing nonce', 'wptravelengine-pro' ), 401 );
			}

			$_action = $request->get_param( '_action' );
			switch ( $_action ) {
				case 'deactivate_license':
				case 'activate_license':
				case 'check_license':
				case 'batch_check_licenses':
					$response         = $this->handle_license_request( $_action, $request );
					$response->reload = true;
					break;
				case 'refresh-results':
					$response = $this->refresh_results();
					break;
				case 'query':
					$response = $this->query( $request );
					break;
				default:
					throw new Exception( __( 'Invalid action', 'wptravelengine-pro' ), 401 );
			}
		} catch ( Exception  $e ) {
			wp_send_json_error(
				array(
					'message' => $e->getMessage(),
					'error'   => $e->getMessage(),
				)
			);
		}

		wp_send_json( $response );
	}

	protected function validate_nonce( $action, string $nonce, $referer = true ): bool {
		if ( $referer ) {
			return check_ajax_referer( $action, $nonce, false );
		}

		return wp_verify_nonce( $nonce, $action );
	}

	/**
	 * Handle license check, activate, and deactivate actions.
	 *
	 * @param string          $action
	 * @param WP_REST_Request $request
	 *
	 * @return object
	 * @throws Exception
	 * @since 1.0.15 updated to adapt new api endpoint
	 */
	public function handle_license_request( string $action, WP_REST_Request $request ): object {
		if ( ! current_user_can( 'update_plugins' ) ) {
			throw new Exception( __( 'Insufficient permissions.', 'wptravelengine-pro' ), 403 );
		}

		if ( in_array( $action, array( 'check_license', 'batch_check_licenses' ), true ) ) {
			$result = License::batch_check( true );
			return (object) array(
				'success' => $result['success'],
				'data'    => (object) array(
					'results' => $result['results'],
					'message' => $result['message'],
				),
			);
		}

		$license_data = $request->get_param( 'license' );

		$license_key = $license_data['license_key'] ?? '';
		$item_id     = (int) ( $license_data['id'] ?? 0 );
		$slug        = $license_data['slug'] ?? '';

		$licenses = wptravelengine_pro_get_license_option( 'wp_travel_engine_license', array() );
		if ( ! isset( $licenses[ $slug ] ) || $licenses[ $slug ] !== $license_key ) {
			$licenses[ $slug ] = $license_key;
			wptravelengine_pro_update_license_option( 'wp_travel_engine_license', $licenses );
		}

		$license = new License( $license_key, $item_id, $slug );

		if ( in_array( $action, array( 'activate_license', 'deactivate_license' ), true ) ) {
			delete_site_transient( 'wptravelengine_site_forbidden' );
		}

		switch ( $action ) {
			case 'activate_license':
				$response = $license->activate();
				break;
			case 'deactivate_license':
				$response = $license->deactivate();
				break;
			default:
				throw new Exception( __( 'Invalid request action.', 'wptravelengine-pro' ), 401 );
		}

		if ( is_wp_error( $response ) ) {
			throw new Exception( $response->get_error_message() );
		}

		if ( false === $response || null === $response ) {
			throw new Exception( __( 'The license server could not be reached. Please check your connection and try again.', 'wptravelengine-pro' ) );
		}

		if ( $response->success ?? false ) {
			License::batch_check( true );

			$license_status = json_decode( wptravelengine_pro_get_license_status( '_wptravelengine_license_status', '{}' ) ) ?? new stdClass();

			if ( ! isset( $license_status->{$slug} ) ) {
				$license_status->{$slug} = $response;
				wptravelengine_pro_update_license_status( '_wptravelengine_license_status', wp_json_encode( $license_status ) );
			}
		}

		return (object) array(
			'success' => $response->success ?? false,
			'data'    => $response,
		);
	}

	/**
	 * @throws Exception
	 */
	public function refresh_results(): stdClass {
		$store = new Store();

		delete_site_transient( 'wptravelengine_site_forbidden' );

		$store->refresh();

		$response = new stdClass();

		$patterns = array(
			'_transient_wptravelengine_store_%',
			'_transient_timeout_wptravelengine_store_%',
			'_transient_wptravelengine_pro_cache_%',
			'_transient_timeout_wptravelengine_pro_cache_%',
			'_wptravelengine_pro_extensions_version',
		);

		global $wpdb;

		$conditions = implode( ' OR ', array_fill( 0, count( $patterns ), 'option_name LIKE %s' ) );
		$sql        = "DELETE FROM {$wpdb->options} WHERE $conditions";
		$result     = $wpdb->query( $wpdb->prepare( $sql, ...$patterns ) );

		if ( $result === false ) {
			throw new Exception( sprintf( __( 'Database Error: %s', 'wptravelengine-pro' ), $wpdb->last_error ) );
		}

		wptravelengine_pro_get_extensions_version( '', true );

		$response->success       = true;
		$response->rows_affected = $result;
		$response->reload        = true;

		$response->message = __( 'Results refreshed', 'wptravelengine-pro' );

		return $response;
	}

	/**
	 * @return object
	 * @since 1.0.5
	 */
	public function query( $request ) {
		$query_args = $request->get_param( 'query' );

		$store = new Store();

		$items = $store->get_products( $query_args['type'], $query_args )->products ?? array();

		$items = array_map( array( PluginInstall::class, 'format_item' ), $items );

		return (object) array(
			'success' => true,
			'items'   => $items,
		);
	}
}
