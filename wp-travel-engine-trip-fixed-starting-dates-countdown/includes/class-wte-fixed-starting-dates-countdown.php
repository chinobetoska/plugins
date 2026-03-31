<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://wptravelengine.com/
 * @since      1.0.0
 *
 * @package    Wte_Fixed_Starting_Dates_Countdown
 * @subpackage Wte_Fixed_Starting_Dates_Countdown/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Wte_Fixed_Starting_Dates_Countdown
 * @subpackage Wte_Fixed_Starting_Dates_Countdown/includes
 * @author     WP Travel Engine <test@test.com>
 */
class Wte_Fixed_Starting_Dates_Countdown {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Wte_Fixed_Starting_Dates_Countdown_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'WTE_FSDATES_COUNTDOWN_VERSION' ) ) {
			$this->version = WTE_FSDATES_COUNTDOWN_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'wte-fixed-starting-dates-countdown';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wte_Fixed_Starting_Dates_Countdown_Loader. Orchestrates the hooks of the plugin.
	 * - Wte_Fixed_Starting_Dates_Countdown_i18n. Defines internationalization functionality.
	 * - Wte_Fixed_Starting_Dates_Countdown_Admin. Defines all hooks for the admin area.
	 * - Wte_Fixed_Starting_Dates_Countdown_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wte-fixed-starting-dates-countdown-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wte-fixed-starting-dates-countdown-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wte-fixed-starting-dates-countdown-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wte-fixed-starting-dates-countdown-public.php';

		/**
		 * The class responsible for creating fixed starting dates countdown widget for trips.
		 */
		require_once WTE_FSDATES_COUNTDOWN_BASE_PATH . '/includes/widget/wte-fixed-starting-dates-countdown-widget.php';

		/**
		 * The class responsible for creating fixed departure countdown meta box in the admin-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wte-fixed-starting-dates-countdown-meta.php';

		/**
		 * The class responsible for creating fixed departure countdown functions in the plugin.
		 */
		require_once WTE_FSDATES_COUNTDOWN_BASE_PATH . '/includes/class-wte-fixed-starting-dates-countdown-functions.php';

		/**
		 * The class responsible for updates.
		 */
		require_once WTE_FSDATES_COUNTDOWN_BASE_PATH . '/updater/wte-fixed-starting-dates-countdown-updater.php';

		$this->loader = new Wte_Fixed_Starting_Dates_Countdown_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wte_Fixed_Starting_Dates_Countdown_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Wte_Fixed_Starting_Dates_Countdown_i18n();

		$this->loader->add_action( 'init', $plugin_i18n, 'load_plugin_textdomain' );

	}

/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		// Bypass Beto: Forzamos el estado válido antes de cargar el panel
		update_option( 'wp-travel-engine-trip-fixed-starting-dates-countdown_license_status', 'valid' );

		$plugin_admin = new Wte_Fixed_Starting_Dates_Countdown_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'admin_notices', $plugin_admin, 'check_dependency' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Wte_Fixed_Starting_Dates_Countdown_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Wte_Fixed_Starting_Dates_Countdown_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
     * Execute Plugin.
     *
     * @return void
     */
    public static function execute() {
        $plugin = new Wte_Fixed_Starting_Dates_Countdown();
        $plugin->run();
    }



}
