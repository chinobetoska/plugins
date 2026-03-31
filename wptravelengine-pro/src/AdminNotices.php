<?php
/**
 * Admin Notices.
 *
 * @package WPTravelEnginePro
 * @since 1.0.0
 */

namespace WPTravelEnginePro;

/**
 * Admin Notices.
 *
 * @package WPTravelEnginePro
 * @since 1.0.0
 */
class AdminNotices {

	public static array $notices = array();

	public function allowed_html(): array {
		return array(
			'a'      => array(
				'href'     => true,
				'title'    => true,
				'target'   => true,
				'nofollow' => true,
				'class'    => true,
				'rel'      => true,
			),
			'br'     => true,
			'em'     => true,
			'strong' => true,
			'p'      => array( 'class' => array() ),
		);
	}

	public static function add( string $code, string $message, string $type = 'info' ) {
		static::$notices[ $type ][] = array(
			'code'    => $code,
			'message' => $message,
			'type'    => $type,
		);
	}

	public function print( array $notices, string $type ) {
		wptravelengine_pro_view( 'admin/notice', array(
			'messages' => $notices,
			'type'     => $type,
		) );
	}

	public function render() {
		if ( ! empty( static::$notices ) ) {
			wptravelengine_pro_view( 'admin/template-notice', [ 'notices' => static::$notices ] );
		}
	}

}
