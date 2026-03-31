<?php
/**
 * Admin Notice template.
 */

/**
 * @var array $messages
 * @var string $type
 */
?>
<div class="wpte-pro__admin-alert wpte-pro__alert-<?php echo esc_attr( $type ) ?>">
	<span class="wpte-icon">
		<svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
			<g opacity="0.3">
				<rect x="5.50002" y="5.50001" width="25" height="25" rx="12.5" stroke="currentColor" stroke-width="1.66667"/>
			</g>
			<g opacity="0.1">
				<rect x="1.33333" y="1.33333" width="33.3333" height="33.3333" rx="16.6667" stroke="currentColor" stroke-width="1.66667"/>
			</g>
			<g clip-path="url(#clip0_2185_8445)">
				<path d="M18 14.6667V18M18 21.3333H18.0084M26.3334 18C26.3334 22.6024 22.6024 26.3333 18 26.3333C13.3976 26.3333 9.66669 22.6024 9.66669 18C9.66669 13.3976 13.3976 9.66667 18 9.66667C22.6024 9.66667 26.3334 13.3976 26.3334 18Z" stroke="currentColor" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
			</g>
			<defs>
				<clipPath id="clip0_2185_8445">
					<rect width="20" height="20" fill="white" transform="translate(8 8)"/>
				</clipPath>
			</defs>
		</svg>
	</span>
	<div class="wpte-pro__alert-body">
		<h3>WP Travel Engine PRO</h3>
		<ul>
			<?php
			foreach ( $messages as $message ) {
				echo wp_kses( sprintf( "<li>%s</li>", $message[ 'message' ] ), 'admin-notice' );
			}
			?>
		</ul>
	</div>
</div>
