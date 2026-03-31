<?php
/**
 * Loads Pro Extensions.
 *
 * @since 1.0.0
 */

namespace WPTravelEnginePro;

/**
 * Class ExtensionLoader.
 *
 * Loads extensions.
 *
 * @package WPTravelEnginePro
 *
 * @since 1.0.0
 */
class ExtensionLoader {
	protected int $extension_id;

	protected array $args = array();

	protected Extension $extension;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct( Extension $extension, array $args ) {
		$this->extension    = $extension;
		$this->extension_id = $extension->ID;

		$this->args = wp_parse_args(
			$args,
			array(
				'callback'              => null,
				'pro_requires_at_least' => '1.0.0',
				'execute'               => true,
				'dependencies'          => array(),
			)
		);

		add_action( 'admin_init', array( $this, 'add_admin_notices' ) );

	}

	/**
	 * Validate extension license
	 *
	 * @since 1.0.0
	 */
	protected function validate_license( License $license ): bool {
		return $license->valid();
	}

	/**
	 * @param License $license
	 *
	 * @return void
	 */
	protected function license_admin_notice( License $license ): void {

		if ( $license->valid() ) {
			return;
		}

		$license_status = $license->get_status();
		$install_url = $this->get_install_url();

		$message        = '';
		switch ( $license_status ) {
			case License::STATUS_EXPIRED:
				$message = __( 'Your license for <strong>%1$s</strong> has expired and is no longer valid. Please %2$s your license to continue receiving regular updates and support. %3$s', 'wptravelengine-pro' );
				break;
			case License::STATUS_INACTIVE:
			case License::STATUS_DEACTIVATED:
				$message = __( 'Your license for <strong>%1$s</strong> is inactive. Please activate your license to use <strong>%1$s</strong> and continue receiving regular updates and support. %3$s', 'wptravelengine-pro' );
				break;
			case License::STATUS_INVALID:
			case License::STATUS_INVALID_ITEM_ID:
				$message = __( '<strong>%1$s is active but not running</strong> because of an invalid license key. Please enter a valid license key to use <strong>%1$s</strong> and continue receiving regular updates and support. %3$s', 'wptravelengine-pro' );
				break;
		}

		if ( $message ) {
			$message = sprintf(
				'<p>%s</p>',
				sprintf(
					$message,
					"<em>{$this->args['plugin_name']}</em>",
					'<a href="https://wptravelengine.com/checkout/?edd_license_key=' . esc_attr( $license->license() ) . '" target="_blank">renew</a>',
					$install_url ? sprintf(
						__('Click <strong>%1$s</strong> to manage your license.', 'wptravelengine-pro'),
						'<a href="'. esc_url( $install_url ) .'" target="_self">here</a>'
					) : ''
				)
			);

			AdminNotices::add('license', $message, 'danger');
		}
	}

	/**
	 * Get install url
	 *
	 * @since 1.0.1
	 */
	protected function get_install_url(): string {
		global $pagenow;
		if ( !is_admin() || $pagenow === 'plugin-install.php' ) {
			return '';
		}
		return esc_url( add_query_arg([ 'tab' => 'wptravelengine' ], admin_url('plugin-install.php') ) );
	}

	public function load(): ExtensionLoader {

		$license = $this->extension->license();

		if ( ! $this->validate_license( $license ) ) {
			return $this;
		}

		if (
			$this->is_core_compatible( $this->extension->requires_at_least )
			&& $this->is_pro_compatible( $this->args[ 'pro_requires_at_least' ] )
		) {
			$this->run_the_extension( $this->extension );
		}

		return $this;
	}

	public function add_admin_notices() {
		$license = $this->extension->license();

		if ( ! $license->valid() ) {
			$this->license_admin_notice( $license );
			return;
		}

		if ( $license->is_expiring() ) {
			AdminNotices::add(
				'license_expiring',
				sprintf(
					__( 'Your license for <strong>%1$s</strong> is expiring on %3$s. Please %2$s your license to continue using and receiving regular updates and support.', 'wptravelengine-pro' ),
					$this->args[ 'plugin_name' ],
					'<a href="https://wptravelengine.com/checkout/?edd_license_key=' . $license->license() . '" target="_blank">renew</a>',
					'<strong>' . $license->expiry_datetime()->format( 'F j, Y' ) . '</strong>'
				),
				'warning'
			);
		}

		if ( ! $this->is_core_compatible( $this->extension->requires_at_least ) ) {
			AdminNotices::add(
				'core_requires_update',
				sprintf(
					__( '<strong>%s</strong> requires <strong>WP Travel Engine</strong> version %s or higher.', 'wptravelengine-pro' ),
					$this->extension->name,
					$this->extension->requires_at_least
				),
				'warning'
			);
		}

		if ( ! $this->is_pro_compatible( $this->args[ 'pro_requires_at_least' ] ) ) {
			AdminNotices::add(
				'pro_requires_update',
				sprintf(
					__( '<strong>%s</strong> requires <strong>WP Travel Engine Pro</strong> version %s or higher.', 'wptravelengine-pro' ),
					$this->extension->name,
					$this->args[ 'pro_requires_at_least' ]
				),
				'warning'
			);
		}
	}

	protected function run_the_extension( $extension ) {
		$this->load_dependencies( $extension );
		$this->execute( $extension );
	}

	public function is_core_compatible( string $compatible_version ) {
		return version_compare( wptravelengine_pro_core_version(), $compatible_version, '>=' );
	}

	public function is_pro_compatible( string $compatible_version ) {
		return version_compare( WPTRAVELENGINE_PRO_VERSION, $compatible_version, '>=' );
	}

	public function get_license_page_url(): string {
		return admin_url( 'edit.php?post_type=booking&page=wptravelengine-pro' );
	}

	protected function execute( $extension ) {
		if ( class_exists( $this->args[ 'execute' ] ?? '' ) ) {
			$class_name = $this->args[ 'execute' ];
			$class_name::execute( $extension, $this );
		} else if ( is_callable( $this->args[ 'execute' ] ) ) {
			call_user_func( $this->args[ 'execute' ], $extension, $this );
		}
	}

	protected function load_dependencies( Extension $extension ) {
		$extension_directory = trailingslashit( $extension->directory_path() );
		if ( $this->args[ 'dependencies' ][ 'requires' ] ?? false ) {
			foreach ( $this->args[ 'dependencies' ][ 'requires' ] as $file ) {
				require_once $extension_directory . $file . '.php';
			}
		}
	}
}
