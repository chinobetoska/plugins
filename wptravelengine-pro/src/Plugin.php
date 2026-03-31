<?php
/**
 * Main Plugin Class.
 *
 * @package WPTravelEngine_Pro
 *
 * @since 1.0.0
 */

namespace WPTravelEnginePro;

/**
 * Main Plugin Class.
 *
 * @package WPTravelEnginePro
 *
 * @since 1.0.0
 */
class Plugin {
	use Traits\Singleton;

	protected bool $ready_to_bootstrap = false;

	public function __construct() {
		if ( $this->pre_bootstrap() ) {
			$this->bootstrap();
		}
	}

	protected function pre_bootstrap(): bool {
		require_once __DIR__ . '/hooks/pre-bootstrap.php';

		return wptravelengine_pro_is_core_active();
	}

	protected function bootstrap() {
		$this->collect_hooks();
	}

	protected function collect_hooks() {
		$hooks_dir = __DIR__ . '/hooks';

		$dir_iterator = new \DirectoryIterator( $hooks_dir );

		foreach ( $dir_iterator as $file_info ) {
			if ( $file_info->isFile() ) {
				$hook_file = $hooks_dir . '/' . $file_info->getFilename();

				if ( file_exists( $hook_file ) ) {
					require_once $hook_file;
				}
			}
		}
	}

}
