<?php
/**
 * Functions of the plugin.
*/

class WTE_Fixed_Starting_Dates_Countdown_Functions {

	function wte_countdown_minify_js( $input ) {
	    if(trim($input) === "") return $input;
	    return preg_replace(
	        array(
	            // Remove comment(s)
	            '#\s*("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')\s*|\s*\/\*(?!\!|@cc_on)(?>[\s\S]*?\*\/)\s*|\s*(?<![\:\=])\/\/.*(?=[\n\r]|$)|^\s*|\s*$#',
	            // Remove white-space(s) outside the string and regex
	            '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/)|\/(?!\/)[^\n\r]*?\/(?=[\s.,;]|[gimuy]|$))|\s*([!%&*\(\)\-=+\[\]\{\}|;:,.<>?\/])\s*#s',
	            // Remove the last semicolon
	            '#;+\}#',
	            // Minify object attribute(s) except JSON attribute(s). From `{'foo':'bar'}` to `{foo:'bar'}`
	            '#([\{,])([\'])(\d+|[a-z_]\w*)\2(?=\:)#i',
	            // --ibid. From `foo['bar']` to `foo.bar`
	            '#([\w\)\]])\[([\'"])([a-z_]\w*)\2\]#i',
	            // Replace `true` with `!0`
	            '#(?<=return |[=:,\(\[])true\b#',
	            // Replace `false` with `!1`
	            '#(?<=return |[=:,\(\[])false\b#',
	            // Clean up ...
	            '#\s*(\/\*|\*\/)\s*#'
	        ),
	        array(
	            '$1',
	            '$1$2',
	            '}',
	            '$1$3',
	            '$1.$3',
	            '!0',
	            '!1',
	            '$1'
	        ),
	    $input);
	}
}
new WTE_Fixed_Starting_Dates_Countdown_Functions;