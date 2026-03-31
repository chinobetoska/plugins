<script type="text/html" id="tmpl-wte-booking-es-summary">
	<#
	var extraServices = data.extraServices || {}
	if( Object.values(extraServices).length > 0 ) {
	#>
	<h6 class="wte-booking-details-title"><?php esc_html_e( 'Extra Services', 'wte-extra-services' ); ?></h6>
	<ul>
		<#
		for( var _i in extraServices ) { 
			var _counts = extraServices[_i]
			for(let _oid in _counts) {
				var _count = _counts[_oid]
				if(_count.count <= 0) continue
				var unitPrice = WTEBooking.priceFormat(_count.unitPrice) // WTEPrice Object
				var subtotal =  WTEBooking.priceFormat(+_count.count * +_count.unitPrice) // WTEPrice Object
				#>
				<li>
					<label><strong>{{_count.count}} {{_count.label}}</strong> <span class="qty">({{{unitPrice.format()}}}/{{_count.per}})</span></label>
					<div class="amount-figure">{{{subtotal.format()}}}</div>
				</li>
				<# 
			}
		}
	}
	#>
	</ul>
</script>
