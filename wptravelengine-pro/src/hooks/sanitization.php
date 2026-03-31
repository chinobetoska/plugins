<?php
/**
 * Sanitization hooks.
 */

add_filter( 'wp_kses_allowed_html', function ( $allowed_tags, $context ) {

	switch ( $context ) {
		case 'admin_notice':
		case 'admin-notice':
			$allowed_tags = array(
				'div'    => array(
					'class' => true,
				),
				'span'   => array(
					'class' => true,
				),
				'p'      => array( 'class' => true ),
				'ul'     => array( 'class' => true ),
				'li'     => array(),
				'strong' => array(),
				'em'     => array(),
				'br'     => array(),
				"a"      => array(
					'href'     => true,
					'title'    => true,
					'target'   => true,
					'nofollow' => true,
					'class'    => true,
					'rel'      => true,
				),
			);
			break;
	}

	return $allowed_tags;
}, 10, 2 );
