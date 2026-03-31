<?php
/* @var WPTravelEnginePro\Extension[] $extensions */
if ( count( $extensions ) == 0 ) {
	echo '<h3 class="active-msg" style="color:#CA4A1F;">' . esc_html__( 'Premium Extensions not Found!', 'wptravelengine-pro' ) . '</h3>';
}

foreach ( $extensions as $extension ) {
	/* @var WPTravelEnginePro\Extension $extension */
	if ( $extension->is_activated() ) {
		$license = $extension->license();
		wptravelengine_pro_view( 'components/content-license-field', compact( 'extension', 'license' ) );
	}
}
