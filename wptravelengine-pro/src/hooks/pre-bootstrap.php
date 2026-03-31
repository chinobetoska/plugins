<?php
/**
 * Pre Bootstrap.
 */

use WPTravelEnginePro\AdminNotices;

add_action( 'admin_notices', function () {
	$admin_notices = new AdminNotices();
	$admin_notices->render();
} );

add_filter( 'wptravelengine_pro_extension_loader', function () {
	return 'wptravelengine_pro_load_extension';
} );

add_action( 'admin_init', function () {
	if ( ! wptravelengine_pro_is_core_active() ) {
		AdminNotices::add(
			'plugin_not_running',
			__( '<p>WP Travel Engine Pro requires <strong>WP Travel Engine</strong> to be installed and activated. The plugin is <strong>NOT RUNNING</strong></p>', 'wptravelengine-pro' ),
			'error'
		);
	}
} );
