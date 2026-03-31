<?php
/**
 * WP Travel Engine Extensions.
 */

namespace WPTravelEnginePro;

class Extensions {
	use Traits\Singleton;

	protected bool $loaded_extensions = false;

	protected array $extensions = array();

	public function add( Extension $extension ) {
		$this->extensions[ $extension->ID ] = $extension;
	}

	public function get_extensions( $refresh = false ): array {
		if ( $refresh || ! $this->loaded_extensions ) {
			$this->extensions        = array();
			$this->loaded_extensions = false;
			$this->load_extensions();
		}

		return apply_filters( 'wptravelengine_pro_extensions', $this->extensions );
	}

	protected function load_extensions() {
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$extensions = get_plugins();

		foreach ( $extensions as $file => $extension ) {
			if ( empty( $extension[ 'WTE' ] ) ) {
				continue;
			}

			$this->add( Extension::from_plugin_meta( wp_normalize_path( WP_PLUGIN_DIR . '/' . $file ) ) );
		}

		$this->loaded_extensions = true;
	}

	public function get_extension( $id ): ?Extension {
		return $this->extensions[ $id ] ?? null;
	}
}
