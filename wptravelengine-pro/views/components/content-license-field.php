<?php
/* @var WPTravelEnginePro\Extension $extension */
$license_data = array(
	'id'          => $extension->ID,
	'slug'        => $extension->slug,
	'license_key' => $extension->license_key(),
);
/* @var WPTravelEnginePro\License $license */
$valid_license = $license->valid();
?>
<div class="wpte-field wpte-floated"
	 data-item-id="<?php echo esc_attr( $extension->ID ); ?>"
	 data-license="<?php echo esc_attr( wp_json_encode( $license_data ) ) ?>">
	<?php /* @var WPTravelEnginePro\Extension $extension */ ?>
	<label
		for="<?php echo esc_attr( $extension->slug ); ?>"
		class="wpte-field-label"><?php echo esc_html( $extension->name ); ?></label>
	<div class="wp_travel_engine_addon_license_key_wrapper">
		<input id="<?php echo esc_attr( $extension->slug ); ?>"
			<?php echo esc_attr( $license->valid() ? 'readonly' : '' ) ?>
			   class="wp_travel_engine_addon_license_key"
			   name="wp_travel_engine_license[<?php echo esc_attr( $extension->slug ); ?>_license_key]"
			   type="text" class="regular-text"
			   value="<?php echo esc_attr( $extension->license_key() ); ?>" />
		<?php
		if ( $valid_license ) :
			?>
			<span class="wte-license-active">
				<?php wptravelengine_svg_by_fa_icon( 'fas fa-check' ); ?>
				<?php esc_html_e( 'Activated', 'wptravelengine-pro' ); ?>
			</span>
		<?php endif; ?>
	</div>
	<div class="wpte-btn-wrap">
		<input type="submit"
			   data-action="<?php echo $valid_license ? 'deactivate' : 'activate'; ?>"
			   class="wpte-btn <?php echo $valid_license ? 'wpte-btn-deactive deactivate-license' : 'wpte-btn-active activate-license'; ?>"
			   data-id=" <?php echo esc_attr( $extension->slug ); ?>"
			   value="<?php echo $valid_license ? 'Deactivate License' : 'Activate License'; ?>"
		/>
	</div>
	<span class="wpte-tooltip <?php echo esc_attr( 'license-' . $license->get_status() ); ?>">
		<?php echo wp_kses_post( $license->status_message() ); ?>
	</span>
</div>
