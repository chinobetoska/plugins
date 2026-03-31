<?php
/**
 * Scripts.
 */

add_action( 'admin_enqueue_scripts', function () {
	$assets = include WPTRAVELENGINE_PRO_DIR__ . '/build/wptravelengine-pro.asset.php';

	wp_register_script(
		'wptravelengine-pro',
		plugin_dir_url( WPTRAVELENGINE_PRO_FILE__ ) . 'build/wptravelengine-pro.js',
		$assets[ 'dependencies' ] ?? [],
		$assets[ 'version' ],
		true
	);

	$assets = include WPTRAVELENGINE_PRO_DIR__ . '/build/plugin-install.asset.php';
	wp_register_script(
		'wptravelengine-pro_plugin-install',
		plugin_dir_url( WPTRAVELENGINE_PRO_FILE__ ) . 'build/plugin-install.js',
		$assets[ 'dependencies' ] ?? [],
		$assets[ 'version' ],
		true
	);
	wp_register_style(
		'wptravelengine-pro_plugin-install',
		plugin_dir_url( WPTRAVELENGINE_PRO_FILE__ ) . 'build/plugin-install.css',
		[],
		$assets[ 'version' ]
	);
	wp_localize_script( 'wptravelengine-pro_plugin-install', 'wptravelengine_pro', array(
		'ajax_url' => admin_url( 'admin-ajax.php' ),
		'nonce'    => wp_create_nonce( 'wptravelengine_pro_nonce' ),
	) );

} );
