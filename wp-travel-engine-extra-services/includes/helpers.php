<?php
/**
 * Helper Functions.
 */

function wtees_get( &$var, $index, $default ) {
	if ( is_array( $var ) && ! empty( $var[ $index ] ) ) {
		return $var[ $index ];
	}
	return $default;
}
