<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://wptravelengine.com/
 * @since      1.0.0
 *
 * @package    Extra_Services_Wp_Travel_Engine
 * @subpackage Extra_Services_Wp_Travel_Engine/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Extra_Services_Wp_Travel_Engine
 * @subpackage Extra_Services_Wp_Travel_Engine/public
 * @author     WP Travel Engine <info@wptravelengine.com>
 */
class Extra_Services_Wp_Travel_Engine_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Extra_Services_Wp_Travel_Engine_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Extra_Services_Wp_Travel_Engine_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		$asset_script_path = '/min/';
		$version_prefix    = '-' . WTE_EXTRA_SERVICES_VERSION;

		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
			$asset_script_path = '/';
			$version_prefix    = '';
		}

		if ( is_singular( 'trip' ) ) {

			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css' . $asset_script_path . 'extra-services-wp-travel-engine-public' . $version_prefix . '.css', array(), $this->version, 'all' );
		}

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Extra_Services_Wp_Travel_Engine_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Extra_Services_Wp_Travel_Engine_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		$asset_script_path = '/min/';
		$version_prefix    = '-' . WTE_EXTRA_SERVICES_VERSION;

		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
			$asset_script_path = '/';
			$version_prefix    = '';
		}

		wp_register_script( 'popper-js', 'https://unpkg.com/@popperjs/core@2', array(), '2.0', true );
		wp_register_script( 'tippy-js', 'https://unpkg.com/tippy.js@6', array(), '6.0', true );
		if ( is_singular( 'trip' ) ) {
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js' . $asset_script_path . 'extra-services-wp-travel-engine-public' . $version_prefix . '.js', array( 'jquery', 'popper-js', 'tippy-js' ), $this->version, true );
		}

	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function calculate_extra_services_cost() {
		global $post;
		$wp_travel_engine_setting  = get_post_meta( $post->ID, 'wp_travel_engine_setting', true );
		$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings', true );
		$person_format             = isset( $wp_travel_engine_settings['person_format'] ) ? $wp_travel_engine_settings['person_format'] : '/person';
		$user                      = get_user_meta( $post->post_author, 'wpte_vendor', true );
		if ( isset( $wp_travel_engine_setting['extra_service'] ) ) {
			echo '<div class="wpte-expand-extra-service">';?>
			<span class="scroll-down">
				<h5>
					<?php
					$extra_service_title = isset( $wp_travel_engine_settings['extra_service_title'] ) ? esc_attr( $wp_travel_engine_settings['extra_service_title'] ) : __( 'Extra Service(s)', 'wte-extra-services' );
					echo esc_attr( $extra_service_title );
					?>
				</h5>
			</span>
				<?php
				$size = sizeof( $wp_travel_engine_setting['extra_service'] );
				for ( $i = 0; $i < $size; $i++ ) {
					$extra_service = isset( $wp_travel_engine_setting['extra_service'][ $i ] ) ? esc_attr( $wp_travel_engine_setting['extra_service'][ $i ] ) : '';
					$final         = preg_replace( '#[ -]+#', '-', $extra_service );

					$extra_service_person_format = isset( $wp_travel_engine_setting['extra_service_unit'][ $i ] ) ? $wp_travel_engine_setting['extra_service_unit'][ $i ] : 'person';

					$exs_data_id = 'wte_exts_' . $i;

					?>
					<div class="extra-service-wrap">
						<div class="extra-service-info-holder">
							<div class="extra-service-header-holder">
								<span class="extra-service-title" data-id="<?php echo strtolower( $exs_data_id ); ?>">
								<?php
								echo isset( $wp_travel_engine_setting['extra_service'][ $i ] ) ? esc_attr( $wp_travel_engine_setting['extra_service'][ $i ] ) . ':' : '';
								?>
								</span>
								<?php if ( isset( $wp_travel_engine_setting['extra_service_desc'][ $i ] ) && ! empty( $wp_travel_engine_setting['extra_service_desc'][ $i ] ) ) : ?>
									<span class="tooltip"><span class="tooltip-content">
									<?php
									echo isset( $wp_travel_engine_setting['extra_service_desc'][ $i ] ) ? esc_attr( $wp_travel_engine_setting['extra_service_desc'][ $i ] ) : '';
									?>
									</span></span>
								<?php endif; ?>
							</div>
							<span class="extra-service-currency">
								<?php
								$code     = 'USD';
								$obj      = new Wp_Travel_Engine_Functions();
								$code     = $obj->trip_currency_code( $post );
								$currency = $obj->wp_travel_engine_currencies_symbol( $code );
								echo '<span class="currency-code">' . $code . '</span>&nbsp;';
								echo '<span class="currency">' . $currency . '</span>';

								$extra_service_cost = isset( $wp_travel_engine_setting['extra_service_cost'][ $i ] ) ? $wp_travel_engine_setting['extra_service_cost'][ $i ] : '';

								if ( $extra_service_cost != '' && class_exists( 'Wte_Trip_Currency_Converter_Init' ) ) {
									$extra_service_cost = $obj->convert_trip_price( $post, $wp_travel_engine_setting['extra_service_cost'][ $i ] );
								}
								?>
							</span>
							<span class="extra-service-price">
								<input type="hidden" class="extra-service-cost" name="extra_service_name[<?php echo $i; ?>]" data-cost="<?php echo $extra_service_cost; ?>" data-id="<?php echo strtolower( $exs_data_id ); ?>" value="<?php echo isset( $wp_travel_engine_setting['extra_service_cost'][ $i ] ) ? absint( $wp_travel_engine_setting['extra_service_cost'][ $i ] ) : ''; ?>">
								<input type="hidden" class="service-temporary" data-id="<?php echo strtolower( $exs_data_id ); ?>" value="0">
								<?php
								echo absint( $extra_service_cost );
								echo '<span class="per-person">' . ' /' . $extra_service_person_format . '</span>';
								?>
							</span>
						</div>
						<div class="input-holder">
							<div class="less-no"><span class="dashicons dashicons-minus"></span></div>
							<input type="text" name="extra_service[<?php echo $i; ?>]" class="extra-service" data-id="<?php echo strtolower( $exs_data_id ); ?>" value="0" readonly>
							<div class="more-no"><span class="dashicons dashicons-plus"></span></div>
						</div>
					</div>
					<?php
				}
				echo '</div>';
		}
	}

	/**
	 * Extra services frontend
	 *
	 * @return void
	 */
	function add_extra_services_fontend() {
		$tid = '';
		if ( isset( $_GET['vendor-trip'] ) ) {
			$tid = $_GET['vendor-trip'];
		} elseif ( isset( $_SESSION['draft'] ) ) {
			$tid = $_SESSION['draft'];
		} else {
			global $post;
			$tid = $post->ID;
		}
		$current_user              = wp_get_current_user();
		$userid                    = $current_user->ID;
		$user                      = get_user_meta( $userid, 'wpte_vendor', true );
		$wp_travel_engine_settings = get_post_meta( $tid, 'wp_travel_engine_setting', true );
		$size                      = sizeof( $wp_travel_engine_settings['extra_service'] );
		if ( isset( $wp_travel_engine_settings['extra_service'] ) && $size > 0 ) {
			for ( $i = 1; $i <= $size; $i++ ) {
				?>

				<div class="extra-service-repeater" data-id="<?php echo $i; ?>">
					<span class="dashicons dashicons-no"></span>
					<input type="text" name="wp_travel_engine_setting[extra_service][<?php echo $i; ?>]" value="<?php echo isset( $wp_travel_engine_settings['extra_service'][ $i ] ) ? esc_attr( $wp_travel_engine_settings['extra_service'][ $i ] ) : ''; ?>" placeholder="Service name">
					<div class="less-no"><span class="dashicons dashicons-minus"></span></div>
					<input type="text" name="wp_travel_engine_setting[extra_service_cost][<?php echo $i; ?>]" value="<?php echo isset( $wp_travel_engine_settings['extra_service_cost'][ $i ] ) ? esc_attr( $wp_travel_engine_settings['extra_service_cost'][ $i ] ) : ''; ?>" placeholder="Price per person">
						<?php
						$code = 'USD';
						if ( isset( $wp_travel_engine_settings['currency_code'] ) && $wp_travel_engine_settings['currency_code'] != '' ) {
							$code = $wp_travel_engine_settings['currency_code'];
						}
						$obj      = new Wp_Travel_Engine_Functions();
						$currency = $obj->wp_travel_engine_currencies_symbol( $code );
						echo $currency;
						?>
					<div class="more-no"><span class="dashicons dashicons-plus"></span></div>
				</div>
				<?php
			}
		} else {
			?>
			<div class="extra-service-repeater" data-id="1">
			<span class="dashicons dashicons-no"></span>
			<input type="text" name="wp_travel_engine_setting[extra_service][1]" value="Single Suppliment" placeholder="Service name">
			<input type="number" name="wp_travel_engine_setting[extra_service_cost][1]" min="0" step=".1" value="" placeholder="Price per person">
				<?php
				$code = 'USD';
				if ( isset( $wp_travel_engine_settings['currency_code'] ) && $wp_travel_engine_settings['currency_code'] != '' ) {
					$code = $wp_travel_engine_settings['currency_code'];
				}
				$obj      = new Wp_Travel_Engine_Functions();
				$currency = $obj->wp_travel_engine_currencies_symbol( $code );
				echo $currency;
				?>
			</div>
			<?php
		}
		?>
		<div class="extra-service-holder"></div>
		<div class="extra-service-submit">
			<input type="button" class="primary add-extra-service" value="Add Extra Service">
		</div>
		<script type="text/javascript">
			jQuery(document).ready(function($) {
				$(document).on('click', '.add-extra-service', function(event) {
					maximum = 0;
					$('.extra-service-repeater').each(function() {
						var value = $(this).attr('data-id');
						if ( !isNaN( value ) ) {
							value = parseInt( value );
							maximum = ( value > maximum ) ? value : maximum;
						}
					});
					maximum++;
					var template = '<div class="extra-service-repeater" data-id="'+maximum+'"><span class="dashicons dashicons-no"></span><input type="text" name="wp_travel_engine_setting[extra_service]['+maximum+']" value="" placeholder="Service name"><input type="number" min="0" step=".1" name="wp_travel_engine_setting[extra_service_cost]['+maximum+']" placeholder="Price per person">'+'
					<?php
					$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings', true );
					$code                      = 'USD';
					if ( isset( $wp_travel_engine_settings['currency_code'] ) && $wp_travel_engine_settings['currency_code'] != '' ) {
						$code = $wp_travel_engine_settings['currency_code'];
					}$obj     = new Wp_Travel_Engine_Functions();
					$currency = $obj->wp_travel_engine_currencies_symbol( $code );
					if ( isset( $user['currency_code'] ) ) {
						$currency = $obj->wp_travel_engine_currencies_symbol( $user['currency_code'] );
						echo $currency; }
					?>
					'+'</div>';
					$( template ).appendTo( '.extra-service-holder' );
					$('.extra-service-repeater .dashicons-no').on('click',function()
					{
						$(this).parent().fadeOut('slow', function() {
							$(this).remove();
						});
					})
				});
			});
		</script>
		<?php
	}
	/**
	 * Function to show extra charge info in checkout page
	 *
	 * @param [type] $pid
	 * @return void
	 */
	function show_extra_service_checkout( $pid ) {
		$wp_travel_engine_settings = get_post_meta( $pid, 'wp_travel_engine_setting', true );
		$wp_travel_engine_setting  = get_option( 'wp_travel_engine_settings', true );
		$trip_post                 = get_post( $pid );
		$total_extra_cost          = 0;
		$obj                       = new Wp_Travel_Engine_Functions();

		if ( isset( $wp_travel_engine_settings['extra_service'] ) ) {
			?>
			<div class="extra-service-template">
			<?php
			$size = sizeof( $wp_travel_engine_settings['extra_service'] );
			for ( $i = 1; $i <= $size; $i++ ) {
				if ( isset( $_POST['extra_service'][ $i ] ) && $_POST['extra_service'][ $i ] != '0' ) {
					if ( $i == 1 ) {
						?>
					<h5>
						<?php
						$extra_service_title = __( 'Extra Services:', 'wte-extra-services' );
						echo apply_filters( 'checkout_extra_service_title', $extra_service_title );
						?>
					</h5>
					<?php } ?>
					<div class="extra-service-repeater">
						<span class="extra-service-name">
							<?php echo isset( $wp_travel_engine_settings['extra_service'][ $i ] ) ? esc_attr( $wp_travel_engine_settings['extra_service'][ $i ] ) . ': ' : ''; ?>
						</span>
						<span class="extra-service-cost">
							<?php
							$code = 'USD';
							$user = get_userdata( $trip_post->post_author );
							if ( class_exists( 'Vendor_Wp_Travel_Engine' ) && $user && in_array( 'trip_vendor', $user->roles ) ) {
								$userid = $user->ID;
								$user   = get_user_meta( $userid, 'wpte_vendor', true );
								if ( isset( $user['currency_code'] ) && $user['currency_code'] != '' ) {
									$code = $user['currency_code'];
								}
							} elseif ( isset( $wp_travel_engine_setting['currency_code'] ) && $wp_travel_engine_setting['currency_code'] != '' ) {
								$code = $wp_travel_engine_setting['currency_code'];
							}
							echo $code . ' ';
							echo isset( $wp_travel_engine_settings['extra_service_cost'][ $i ] ) ? esc_attr( $wp_travel_engine_settings['extra_service_cost'][ $i ] ) . ' X ' . $_POST['extra_service'][ $i ] : '';
							$total_extra_cost += $_POST['extra_service'][ $i ] * $_POST['extra_service_name'][ $i ];
							?>
						</span>
					</div>
					<?php
				}
			}
			if ( isset( $_POST['extra_service'][1] ) && $_POST['extra_service'][1] != '0' ) {
				if ( class_exists( 'Wte_Trip_Currency_Converter_Init' ) ) {
					$tcc_code             = $obj->trip_currency_code( $trip_post );
					$tcc_total_extra_cost = $obj->convert_trip_price( $trip_post, $total_extra_cost );
				}
				echo '<div class="extra-service-total-cost">';
				echo '<span class="extra-service-name">' . apply_filters( 'total_extra_cost_title', 'Total Extra Service Cost' ) . ': </span>';
				echo '<span class="extra-service-cost">' . esc_attr( $code ) . esc_attr( $obj->wp_travel_engine_price_format( $total_extra_cost ) ) . '</span></div>';
				if ( class_exists( 'Wte_Trip_Currency_Converter_Init' ) && $tcc_code != $code ) {
					echo "<span class='trip-currency-convert'>(" . esc_attr( $tcc_code . ' ' . $tcc_total_extra_cost ) . ')</span>';
				}
			}
			echo '</div>';
		}
	}

	public function add_trip_booking_step( $booking_steps ) {
		global $post;

		// Bail early if the trip doesn't have extra services.
		if ( ! $this->is_trip_has_extra_services( $post->ID ) ) {
			return $booking_steps;
		}

		array_push( $booking_steps, __( 'Extra Services', 'wte-extra-services' ) );

		return $booking_steps;
	}

	public function add_booking_step_content() {
		global $post;

		// Bail early if the trip doesn't have extra services.
		if ( ! $this->is_trip_has_extra_services( $post->ID ) ) {
			return;
		}

		require_once apply_filters( 'trip_view__booking__services_tab_content__path', plugin_dir_path( __FILE__ ) . 'partials/extra-services-wp-travel-engine-public-trip.php' );
	}

	public function add_extra_service_price_holder() {
		global $post;

		// Bail early if the trip doesn't have extra services.
		if ( ! $this->is_trip_has_extra_services( $post->ID ) ) {
			return;
		}

		$wte_settings = get_option( 'wp_travel_engine_settings' );

		if ( ! isset( $wte_settings['extra_service_title'] ) ) {
			$wte_settings['extra_service_title'] = '';
		}
		if ( empty( $wte_settings['extra_service_title'] ) ) {
			$wte_settings['extra_service_title'] = __( 'Extra Services', 'wte-extra-services' );
		}

		$extra_service_title = $wte_settings['extra_service_title'];

		require_once plugin_dir_path( __FILE__ ) . 'partials/extra-services-wp-travel-engine-public-trip-price.php';
	}

	private function is_trip_has_extra_services( $trip_id ) {

		$trip_meta           = get_post_meta( $trip_id, 'wp_travel_engine_setting', true );
		$has_global_services = false;
		if ( ! apply_filters( 'use_legacy_trip_extras', false ) ) {
			$has_global_services = ! empty( $trip_meta['wte_services_ids'] );
		}

		return ( isset( $trip_meta['extra_service'] ) && is_array( $trip_meta['extra_service'] ) && ! empty( $trip_meta['extra_service'] ) ) || $has_global_services;
	}

	/**
	 * Add trip extras to paypal.
	 *
	 * @param [type] $args
	 * @param [type] $item
	 * @param [type] $cart_id
	 * @param [type] $agrs_index
	 * @return void
	 */
	public function standard_paypal_args( $args, $item, $cart_id, $agrs_index ) {

		$trip_extras = isset( $item['trip_extras'] ) ? $item['trip_extras'] : array();

		if ( is_array( $trip_extras ) && count( $trip_extras ) > 0 ) {

			foreach ( $trip_extras as $key => $extra ) :

				$agrs_index += 1;

				$args[ 'item_name_' . $agrs_index ] = $extra['extra_service'];
				$args[ 'quantity_' . $agrs_index ]  = $extra['qty'];
				$args[ 'amount_' . $agrs_index ]    = $extra['price'];

			endforeach;

		}
		return $args;
	}
}
