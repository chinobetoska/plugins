<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://wptravelengine.com/
 * @since      1.0.0
 *
 * @package    Wte_Fixed_Starting_Dates_Countdown
 * @subpackage Wte_Fixed_Starting_Dates_Countdown/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wte_Fixed_Starting_Dates_Countdown
 * @subpackage Wte_Fixed_Starting_Dates_Countdown/includes
 * @author     WP Travel Engine <test@test.com>
 */
class Wte_Fixed_Starting_Dates_Countdown_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wte-fixed-starting-dates-countdown',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
