<script type="text/html" id="tmpl-wte-booking-es-default">
<#
var service = data.service
var serviceData = service.meta.wte_services
var service_required = serviceData?.service_required ?? false
var cost = WTEBooking.priceFormat(serviceData.service_cost) // WTEPrice Instance.

var extraServices = data.extraServices || {}

var _infoData = {
	serviceID: service.id,
	option: 0
}
var count = extraServices[service.id] && extraServices[service.id][0].count || 0;

var serviceUnitLabels = <?php echo json_encode( WTE_Extra_Services_metaboxes::get_service_unit_labels() );  ?>;
#>
	<div class="wte-trip-guest-wrapper">
		<div class="check-in-wrapper">
			<label>
				<# if ( service_required ) { #>
					{{{service.title.rendered}}}
					<span class="wte-required" style="color: red;">*</span>
				<# } else { #>
					{{{service.title.rendered}}}
				<# } #>
			</label>
			<# if(service.content.rendered.trim().length > 0) { #>
			<span class="wte-meta-help">
				<svg xmlns="http://www.w3.org/2000/svg" width="6" height="8.226" viewBox="0 0 6 8.226">
					<path id="Path_23222" data-name="Path 23222" d="M361.369,288.944a1.569,1.569,0,0,0-1.026,1.453v.365h-1.014a4.251,4.251,0,0,1,.119-1.217,2.424,2.424,0,0,1,1.026-1.126c.9-.536,1.311-1.088,1.252-1.656-.089-.81-.611-1.238-1.56-1.292a1.956,1.956,0,0,0-2.1,1.656l-1.079-.466.178-.418c.492-1.147,1.554-1.7,3.179-1.656,1.607.08,2.485.783,2.633,2.1q.134,1.254-1.607,2.262Zm-1.014,3.865h-1.026v-1.034h1.026Z" transform="translate(-356.985 -284.584)" fill="#170d44"/>
				</svg>
				<span class="wte-help-content">{{{service.content.rendered}}}</span>
			</span>
			<# } #>
		</div>
		<div class="select-wrapper">
			<div class="amount-per-person">
				<!-- <span class="regular-price"><del>$1200</del></span> -->
				{{{cost.format()}}}
				<span class="per-text">/ {{serviceUnitLabels[serviceData.service_unit]['label']}}</span>
			</div>
			<div class="wte-qty-number wte-booking-es-counter" data-info="{{JSON.stringify(_infoData)}}">
				<button class="prev wte-down">
					<svg xmlns="http://www.w3.org/2000/svg" width="14" height="2" viewBox="0 0 14 2">
						<path id="Path_23951" data-name="Path 23951" d="M0,0H12" transform="translate(1 1)" fill="none" stroke="#170d44" stroke-linecap="round" stroke-width="2" opacity="0.5"/>
					</svg>
				</button>
				<input type="text" value="{{count}}" readonly>
				<button class="next wte-up">
					<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14">
						<g id="Group_2263" data-name="Group 2263" transform="translate(-78 -14)" opacity="0.5">
							<line id="Line_2" data-name="Line 2" x2="12" transform="translate(79 21)" fill="none" stroke="#170d44" stroke-linecap="round" stroke-width="2"/>
							<line id="Line_3" data-name="Line 3" x2="12" transform="translate(84.999 15) rotate(90)" fill="none" stroke="#170d44" stroke-linecap="round" stroke-width="2"/>
						</g>
					</svg>
				</button>
			</div>
		</div>
	</div>
</script>
