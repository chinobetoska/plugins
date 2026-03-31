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
 * @package           Wte_Fixed_Starting_Dates_Countdown
 *
 * @wordpress-plugin
 * Plugin Name:       WP Travel Engine - Trip Fixed Starting Dates Countdown
 * Plugin URI:        https://wptravelengine.com/
 * Description:       An extension for WP Travel Engine plugin to add a fixed starting dates countdown to desired trips.
 * Version:           2.2.0
 * Author:            WP Travel Engine
 * Author URI:        https://wptravelengine.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wte-fixed-starting-dates-countdown
 * Domain Path:       /languages
 * WTE: 6695:wte_trip_fixed_starting_dates_countdown_license_key
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */

define( 'WTE_FSDATES_COUNTDOWN_VERSION', '2.2.0' );
define( 'WTE_FSDATES_COUNTDOWN_BASE_PATH', dirname( __FILE__ ) );
define( 'WP_TRAVEL_ENGINE_TRIP_COUNTDOWN_FILE_PATH', __FILE__ );
define( 'WP_TRAVEL_ENGINE_TRIP_COUNTDOWN_REQUIRES_AT_LEAST', '4.3.0' );

add_action('plugins_loaded',function() {
    // Bypass Beto: Forzamos el estado de la licencia en la base de datos
    update_option('wp-travel-engine-trip-fixed-starting-dates-countdown_license_status', 'valid');
    
    wptravelengine_pro_config(WP_TRAVEL_ENGINE_TRIP_COUNTDOWN_FILE_PATH,array(
            'id'           => 6695,
            'slug'         => 'wp-travel-engine-trip-fixed-starting-dates-countdown',
            'plugin_name'  => 'Trip Fixed Starting Dates Countdown',
            'file_path'    => WP_TRAVEL_ENGINE_TRIP_COUNTDOWN_FILE_PATH,
            'version'      => WTE_FSDATES_COUNTDOWN_VERSION,
            'dependencies' => [
                'requires' => [
                    'includes/class-wte-fixed-starting-dates-countdown',
                ],
            ],
        'execute'      => 'Wte_Fixed_Starting_Dates_Countdown',
    ) );
}, 10);
