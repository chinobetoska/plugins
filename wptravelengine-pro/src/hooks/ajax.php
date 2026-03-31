<?php
/**
 * Hooks: Ajax hooks.
 *
 * @since 1.0.0
 */

use WPTravelEnginePro\AJAX;

add_action( 'wp_ajax_wptravelengine_pro_config', function () {
	$ajax = new AJAX();
	$ajax->process();
} );
