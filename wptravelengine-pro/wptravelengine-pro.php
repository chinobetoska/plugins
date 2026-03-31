<?php
/**
 * Plugin Name:     WP Travel Engine Pro
 * Plugin URI:      https://wptravelengine.com/plugins/wptravelengine-pro/
 * Description:     WP Travel Engine Pro is a premium add-on for WP Travel Engine plugin that adds more powerful features to the plugin.
 * Author:          WP Travel Engine
 * Author URI:      https://wptravelengine.com/
 * Text Domain:     wptravelengine-pro
 * Domain Path:     /languages
 * Version:         1.0.15
 *
 * WTE tested up to: 6.0
 * WTE requires at least: 5.7
 * WTE: 138060:wptravelengine-pro
 *
 * @package         WPTravelEngine_Pro
 */

use WPTravelEnginePro\Plugin;

defined( 'ABSPATH' ) || exit;

const WPTRAVELENGINE_PRO_VERSION = '1.0.15';
const WPTRAVELENGINE_PRO_FILE__  = __FILE__;
const WPTRAVELENGINE_PRO_DIR__   = __DIR__;

require WPTRAVELENGINE_PRO_DIR__ . '/vendor/autoload.php';

Plugin::instance();
