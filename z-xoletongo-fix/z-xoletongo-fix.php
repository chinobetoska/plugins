<?php
/*
Plugin Name: Xoletongo - Fix de Fechas
Description: Habilita las funciones de fechas que le faltan al Countdown.
Version: 1.0
*/

// Forzar que el sistema crea que la extensión de fechas existe
add_filter( 'get_option_wp_travel_engine_settings', function( $settings ) {
    if ( ! is_array( $settings ) ) $settings = array();
    $settings['extensions']['trip_fixed_starting_dates'] = 'yes';
    return $settings;
});

// Activar la pestaña de "Dates" en el editor de viajes
add_filter( 'wte_trip_metabox_settings_tabs', function( $tabs ) {
    $tabs['dates'] = array(
        'label' => 'Fechas (Xoletongo Fix)',
        'target' => 'wte-panel-dates',
        'class' => array(),
    );
    return $tabs;
}, 20 );

// Forzar licencias a "valid"
add_filter( 'pre_option_wp-travel-engine-trip-fixed-starting-dates_license_status', function() { return 'valid'; });