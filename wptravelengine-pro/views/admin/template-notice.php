<?php
/**
 * Admin Notice Template.
 *
 * @since 1.0.0
 */

/**
 * @var array $notices
 */
?>
<style>
	.wpte-pro__admin-alerts {
		--wpte-alert-bg: transparent;
		--wpte-alert-color: inherit;
		--wpte-alert-link-color: inherit;
		--wpte-alert-border-color: #DCDCDC;
		--wpte-alert-border-radius: .75rem;
		--wpte-alert-padding-x: 1rem;
		--wpte-alert-padding-y: 1rem;
		--wpte-alert-gap: .5rem;
		display: flex;
		flex-direction: column;
		gap: .5rem;
	}

	.wpte-pro__admin-alert {
		display: flex;
		padding: 16px;
	}

	.wpte-pro__alert-info {
		--wpte-alert-bg: #cff4fc;
		--wpte-alert-color: #055160;
		--wpte-alert-border-color: #9eeaf9;
		--wpte-alert-link-color: #055160;
	}

	.wpte-pro__alert-danger {
		--wpte-alert-bg: linear-gradient(90deg, #FFF8F8 0%, #FFFFFF 100%);
		--wpte-alert-color: #F04438;
		--wpte-alert-link-color: #F04438;
	}

	.wpte-pro__alert-warning {
		--wpte-alert-bg: linear-gradient(90deg, #FFFAF2 0%, #FFFFFF 100%);
		--wpte-alert-color: #F79009;
		--wpte-alert-link-color: #F79009;
	}

	.wpte-pro__admin-alert {
		display: flex;
		gap: var(--wpte-alert-gap);
		background: var(--wpte-alert-bg);
		color: #0F1D23;
		border: 1px solid var(--wpte-alert-border-color);
		border-radius: var(--wpte-alert-border-radius);
		padding: var(--wpte-alert-padding-y) var(--wpte-alert-padding-x)
	}

	.wpte-pro__admin-alert .wpte-icon{
		color: var(--wpte-alert-color);
	}

	.wpte-pro__admin-alert .wpte-icon svg{
		width: 35px;
		height: 35px;
		transform: translate(-7px, -7px);
	}

	.wpte-pro__admin-alert p{
		color: #566267;
	}

	.wpte-pro__admin-alert p:not(:last-child){
		margin-bottom: var(--wpte-alert-gap);
	}

	.wpte-pro__alert-body h3,
	.wpte-pro__alert-body p {
		margin: 0;
		font-size: 14px;
		line-height: 1.7;
	}

	.wpte-pro__alert-body code {
		background-color: #EBEBEB;
		padding: 2px 4px;
		border-radius: 4px;
	}

	.wpte-pro__alert-body h3 {
		line-height: 1.3;
		margin: 0 0 var(--wpte-alert-gap);
	}

	.wpte-pro__alert-body ul{
		list-style-type: disc;
		margin: 0 0 0 1.5rem;
	}
</style>
<div class="wrap wpte-pro__admin-alerts">
	<?php
	foreach ( $notices as $type => $messages ) {
		if ( isset( $messages[ 0 ] ) ) {
			wptravelengine_pro_view( 'admin/content-notice', array(
				'messages' => $messages,
				'type'     => $type,
			) );
		}
	}
	?>
</div>
