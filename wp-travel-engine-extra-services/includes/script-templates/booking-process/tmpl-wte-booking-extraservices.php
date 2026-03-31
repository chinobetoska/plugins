<div id="wte-booking-extraservices-content"></div>
<script type="text/html" id="tmpl-wte-booking-extraservices">
	<div class="wte-process-tab-content-wrapper">
		<h5 class="wte-process-tab-title"><?php esc_html_e( 'Select Extra Services', 'wte-extra-services' ); ?></h5>
		<div id="wte-booking-extraservices__services" class="wte-trip-options"></div>
	</div>
</script>
<?php
require_once plugin_dir_path( WTE_EXTRA_SERVICES_FILE_PATH ) . 'includes/script-templates/booking-process/tmpl-wte-booking-es-default.php';
require_once plugin_dir_path( WTE_EXTRA_SERVICES_FILE_PATH ) . 'includes/script-templates/booking-process/tmpl-wte-booking-es-custom.php';
require_once plugin_dir_path( WTE_EXTRA_SERVICES_FILE_PATH ) . 'includes/script-templates/booking-process/tmpl-wte-booking-es-summary.php';
?>
<script>
;(function () {
	document.addEventListener('wteLoadBookingTemplates', function () {
		window.WTEBooking && window.WTEBooking.extraServicesAPI && window.WTEBooking.extraServicesAPI()
	})
})();
</script>
