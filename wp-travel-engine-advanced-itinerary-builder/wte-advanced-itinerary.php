<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
 * Plugin Name: WP Travel Engine - Advanced Itinerary Builder
 * Plugin URI: https://wptravelengine.com
 * Description: WP Travel Engine - Advanced Itinerary Builder is a custom addon for WP Travel Engine plugin that helps you build advanced itinerary.
 * Author: WP Travel Engine
 * Author URI: https://wptravelengine.com
 * Version: 2.2.5
 * License: GPL2+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: wte-advanced-itinerary
 * Domain Path: /languages
 *
 * WTE Tested up to: 5.6
 * WTE requires at least: 4.3.0
 * WTE: 31567:wte_advanced_itinerary_license_key
 *
 * @package WTE Advanced Itinerary.
 */

require plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';
const WPTRAVELENGINE_ADVANCED_ITINERARY_VERSION = '2.2.5';
const WPTRAVELENGINE_ADVANCED_ITINERARY_FILE_PATH = __FILE__;
const WPTRAVELENGINE_ADVANCED_ITINERARY_REQUIRES_AT_LEAST = '4.3.0';
define( 'WTEAD_IMAGE_DIR', plugin_dir_url( __FILE__ ) . 'assets/images/' );
define( 'WTEAD_JS_DIR', plugin_dir_url( __FILE__ ) . 'assets/js/' );
define( 'WTEAD_CSS_DIR', plugin_dir_url( __FILE__ ) . 'assets/css/' );
define( 'WTEAD_ADMIN_DIR', dirname( __FILE__ ) . '/admin/' );
define( 'WTEAD_FRONT_TEMPLATE_DIR', dirname( __FILE__ ) . '/templates/' );
define( 'WTEAD_CLASSES_DIR', dirname( __FILE__ ) . '/classes' );
define( 'WTEAD_FILE_ROOT_DIR', plugin_dir_path( __FILE__ ) );


add_action('plugins_loaded',function() {
		wptravelengine_pro_config(__FILE__,array(
				'id'           => 31567,
				'slug'         => 'wp-travel-engine-advanced-itinerary-builder',
				'plugin_name'  => 'Advanced Itinerary Builder',
				'file_path'    => __FILE__,
				'version'      => WPTRAVELENGINE_ADVANCED_ITINERARY_VERSION,
				'dependencies' => [
					'requires' => [
						'classes/class-wte-advanced-itinerary',
					],
				],
			'execute'      => 'WTE_Advanced_Itinerary',
		) );
}, 12);
