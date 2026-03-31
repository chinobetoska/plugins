<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://wptravelengine.com/
 * @since             1.0.0
 * @package           Extra_Services_Wp_Travel_Engine
 *
 * @wordpress-plugin
 * Plugin Name:       WP Travel Engine - Extra Services
 * Plugin URI:        https://wptravelengine.com/
 * Description:       Extra Services is an extension for WP Travel Engine to add additional services for trips.
 * Version:           2.2.1
 * Author:            WP Travel Engine
 * Author URI:        https://wptravelengine.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wte-extra-services
 * Domain Path:       i18n/languages
 * WTE tested up to:  5.8
 * WTE requires at least: 4.3
 * WTE: 20573:wte_extra_services_license_key
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
const WPTRAVELENGINE_EXTRA_SERVICES_VERSION = '2.2.1';
const WPTRAVELENGINE_EXTRA_SERVICES_FILE_PATH = __FILE__;
const WPTRAVELENGINE_EXTRA_SERVICES_REQUIRES_AT_LEAST = '4.3.0';

if ( ! defined( 'WTE_EXTRA_SERVICE_PATH' ) ) {
	define( 'WTE_EXTRA_SERVICE_PATH', __DIR__ );
}
register_activation_hook( __FILE__, 'activate_extra_services_wp_travel_engine' );
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-extra-services-wp-travel-engine-activator.php
 */
function activate_extra_services_wp_travel_engine() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-extra-services-wp-travel-engine-activator.php';
	Extra_Services_Wp_Travel_Engine_Activator::activate();
}

register_deactivation_hook( __FILE__, 'deactivate_extra_services_wp_travel_engine' );
/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-extra-services-wp-travel-engine-deactivator.php
 */
function deactivate_extra_services_wp_travel_engine() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-extra-services-wp-travel-engine-deactivator.php';
	Extra_Services_Wp_Travel_Engine_Deactivator::deactivate();
}

require plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_extra_services_wp_travel_engine() {
}

add_action( 'plugins_loaded', function () {
	wptravelengine_pro_config( __FILE__, array(
		'id'           => 20573,
		'slug'         => 'wp-travel-engine-extra-services',
		'plugin_name'  => 'Extra Services',
		'file_path'    => __FILE__,
		'version'      => WPTRAVELENGINE_EXTRA_SERVICES_VERSION,
		'dependencies' => [
			'requires' => [
				'includes/class-extra-services-wp-travel-engine',
			],
		],
		'execute'      => 'Extra_Services_Wp_Travel_Engine',
	) );
}, 11 );
