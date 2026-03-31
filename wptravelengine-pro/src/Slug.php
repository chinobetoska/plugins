<?php
/**
 * Key mappings for extension.
 *
 * @since 1.0.0
 */

namespace WPTravelEnginePro;

class Slug {

	public static array $map = array(
		"trip-fixed-starting-dates"               => "wp-travel-engine-trip-fixed-starting-dates",
		"wptravelengine-pro"                      => "wptravelengine-pro",
		"group-discount"                          => "wp-travel-engine-group-discount",
		"extra-services"                          => "wp-travel-engine-extra-services",
		"advanced-itinerary-builder"              => "wp-travel-engine-advanced-itinerary-builder",
		"woocommerce-payments"                    => "wptravelengine-woocommerce-payments",
		"custom-booking-link"                     => "wptravelengine-custom-booking-link",
		"per-trip-emails"                         => "wptravelengine-per-trip-emails",
		"partial-payment"                         => "wp-travel-engine-partial-payment",
		"legal-documents"                         => "wp-travel-engine-legal-documents",
		"social-proof"                            => "wp-travel-engine-social-proof",
		"trips-embedder"                          => "wp-travel-engine-trips-embedder",
		"form-editor"                             => "wp-travel-engine-form-editor",
		"itinerary-downloader"                    => "wp-travel-engine-itinerary-downloader",
		"email-customizer"                        => "wptravelengine-email-customizer",
		"trip-reviews"                            => "wp-travel-engine-trip-reviews",
		"user-history"                            => "wp-travel-engine-user-history",
		"file-downloads"                          => "wp-travel-engine-file-downloads",
		"currency-converter"                      => "wp-travel-engine-currency-converter",
		"trip-fixed-starting-dates-countdown"     => "wp-travel-engine-trip-fixed-starting-dates-countdown",
		"trip-weather-forecast"                   => "wp-travel-engine-trip-weather-forecast",
		"zapier"                                  => "wp-travel-engine-zapier",
		"stripe-payment-gateway"                  => "wp-travel-engine-stripe-payment-gateway",
		"paypal-express-checkout-payment-gateway" => "wp-travel-engine-paypal-express-gateway",
		"payu-money-payment-gateway"              => "wp-travel-engine-payumoney-payment-gateway",
		"midtrans-payment-gateway"                => "wp-travel-engine-midtrans-payment-gateway",
		"payhere-payment-gateway"                 => "wp-travel-engine-payhere-payment-gateway",
		"himalayan-bank-payment-gateway"          => "wp-travel-engine-hbl-payment-gateway",
		"payu-biz-payment-gateway"                => "wp-travel-engine-payu-payment-gateway",
		"payfast-payment-gateway"                 => "wp-travel-engine-payfast-payment-gateway",
		"authorize-net-payment-gateway"           => "wp-travel-engine-authorize-net-payment-gateway",
		"we-travel"                               => "wp-travel-engine-affiliate-booking",
	);

	public static function map( $key ) {
		return static::$map[ $key ] ?? "wptravelengine-$key";
	}

}
