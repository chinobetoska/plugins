<?php
/**
 * Extension.
 */

namespace WPTravelEnginePro;

/**
 * Extension.
 */
class Extension {

	/**
	 * Extension ID.
	 *
	 * @var int
	 */
	public int $ID = 0;

	/**
	 * Extension name.
	 *
	 * @var string
	 */
	public string $name = '';

	/**
	 * @var string
	 */
	public string $version = '0.0.0';

	/**
	 * @var mixed
	 */
	public string $tested_up_to;

	/**
	 * @var mixed
	 */
	public string $requires_at_least;

	/**
	 * @var string
	 */
	public string $slug = '';

	public string $file = '';

	/**
	 * @var array
	 */
	protected array $meta = array();

	/**
	 * @var License
	 */
	protected License $license;

	public function __construct( int $id, $args = array(), array $meta = array() ) {

		$args = wp_parse_args(
			$args,
			array(
				'name'              => '',
				'version'           => '',
				'tested_up_to'      => '',
				'requires_at_least' => '',
				'slug'              => '',
				'file'              => '',
			)
		);

		$this->ID                = (int) $id;
		$this->name              = $args['name'];
		$this->version           = $args['version'];
		$this->tested_up_to      = $args['tested_up_to'];
		$this->requires_at_least = $args['requires_at_least'];
		$this->slug              = $args['slug'];
		$this->file              = $args['file'];
		$this->meta              = $meta;

		$this->license = new License( $this->license_key(), $this->ID, $this->slug );
	}

	/**
	 * Create an extension from plugin meta.
	 *
	 * @param string $file_path File path to plugin.
	 *
	 * @return Extension
	 */
	public static function from_plugin_meta( string $file_path ): Extension {

		$plugin_meta = get_plugin_data( $file_path, false, false );

		list( $id ) = explode( ':', $plugin_meta['WTE'] );

		$plugin_basename = plugin_basename( $file_path );

		return new Extension(
			$id,
			array(
				'name'              => $plugin_meta['Name'],
				'version'           => $plugin_meta['Version'],
				'tested_up_to'      => $plugin_meta['WTE tested up to'],
				'requires_at_least' => $plugin_meta['WTE requires at least'],
				'slug'              => dirname( $plugin_basename ),
				'file'              => $plugin_basename,
			),
			$plugin_meta
		);
	}

	/**
	 * Get plugin meta data.
	 *
	 * @param string|null $key
	 *
	 * @return array|mixed|null
	 */
	public function get_meta( ?string $key = null ) {
		return $key ? $this->meta[ $key ] ?? null : $this->meta;
	}

	/**
	 * Get the file path.
	 *
	 * @return string
	 */
	public function directory_path(): string {
		return dirname( $this->file_path() );
	}

	public function file_path(): string {
		return dirname( WPTRAVELENGINE_PRO_DIR__ ) . "/{$this->file}";
	}

	public function license(): License {
		return $this->license;
	}

	public function update_license( string $license_key ) {
		$licenses = wptravelengine_pro_get_license_option( 'wp_travel_engine_license', array() );

		$licenses[ $this->slug ] = $license_key;

		wptravelengine_pro_update_license_option( 'wp_travel_engine_license', $licenses );
	}

	public function license_key() {
		return apply_filters( "wptravelengine_pro_{$this->slug}_license_key", wptravelengine_pro_get_saved_license_key( $this->slug ) );
	}

	public function is_activated(): bool {
		return is_plugin_active( $this->file );
	}
}
