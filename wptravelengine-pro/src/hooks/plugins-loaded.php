<?php
/**
 * Hooks - plugins_loaded.
 */

use WPTravelEnginePro\Plugin;

add_action( 'plugins_loaded', function () {
	do_action( 'wptravelengine_pro_loaded', Plugin::instance() );
}, 9 );
