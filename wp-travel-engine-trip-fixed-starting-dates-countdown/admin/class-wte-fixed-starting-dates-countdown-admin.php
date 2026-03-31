<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wptravelengine.com/
 * @since      1.0.0
 *
 * @package    Wte_Fixed_Starting_Dates_Countdown
 * @subpackage Wte_Fixed_Starting_Dates_Countdown/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wte_Fixed_Starting_Dates_Countdown
 * @subpackage Wte_Fixed_Starting_Dates_Countdown/admin
 * @author     WP Travel Engine <test@test.com>
 */
class Wte_Fixed_Starting_Dates_Countdown_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = WTE_FSDATES_COUNTDOWN_VERSION;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wte_Fixed_Starting_Dates_Countdown_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wte_Fixed_Starting_Dates_Countdown_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wte-fixed-starting-dates-countdown-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		// Bypass Beto: Forzamos el estado de la licencia antes de cargar el JS
		update_option( 'wp-travel-engine-trip-fixed-starting-dates-countdown_license_status', 'valid' );

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wte-fixed-starting-dates-countdown-admin.js', array( 'jquery' ), $this->version, false );

		// Localizamos el script con el estado válido para que el JS no bloquee la interfaz
		wp_localize_script( $this->plugin_name, 'wte_fs_countdown_license', array(
			'status' => 'valid'
		) );
	}

	/**
	 * This will uninstall this plugin if parent WP Travel plugin not found.
	 *
	 * @since 1.0.0
	 */
	public function check_dependency() {
	    
	    update_option( 'wp-travel-engine-trip-fixed-starting-dates-countdown_license_status', 'valid' );

		if ( ! class_exists( 'Wp_Travel_Engine' ) || ! $this->meets_requirements() ) {
			echo '<div class="error">';
			echo wp_kses_post(
				'
				<p>
					<strong>
						WP Travel Engine - Trip Fixed Starting Dates Countdown
					</strong> 
					requires the <a href="https://wptravelengine.com" target="__blank">WP Travel Engine</a>.
						Please install and activate the latest WP Travel Engine plugin first. 
						<b>WP Travel Engine - Trip Fixed Starting Dates Countdown will be deactivated now.</b>
				</p>'
			);
			echo '</div>';

			// Deactivate Plugins.
			deactivate_plugins( plugin_basename( WP_TRAVEL_ENGINE_TRIP_COUNTDOWN_FILE_PATH ) );
		}
	}

	/**
	 * Check if all plugin requirements are met.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if requirements are met, otherwise false.
	 */
	private function meets_requirements() {
		return ( class_exists( 'WP_Travel_Engine' ) && defined( 'WP_TRAVEL_ENGINE_VERSION' ) && version_compare( WP_TRAVEL_ENGINE_VERSION, '4.1.1', '>=' ) );
	}

}
