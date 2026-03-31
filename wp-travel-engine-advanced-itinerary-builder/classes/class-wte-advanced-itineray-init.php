<?php

/**
 * WP Travel Engine Advanced Itinerary init class
 */

use WPTravelEngine\Core\Models\Post\Trip;
use WTE_Advanced_Itinerary\Settings\Globals;
use WTE_Advanced_Itinerary\Settings\TripEdit;

class WTE_Advanced_Itinerary_Init {


	/**
	 * Class Constructor.
	 */
	public function __construct() {

		if ( ! defined( 'WP_TRAVEL_ENGINE_VERSION' ) || version_compare( WP_TRAVEL_ENGINE_VERSION, '6.4.1', '<' ) ) {
			add_action( 'admin_notices', function() {
				echo '<div class="notice notice-error is-dismissible"><p>'. __( 'WP Travel Engine Advanced Itinerary Builder requires WP Travel Engine version 6.4.1 or higher.', 'wte-advanced-itinerary' ) .'</p></div>';
			} );
			return;
		}

		add_action( 'init', array( $this, 'init_hooks' ) );
		$this->admin_hooks();
		add_action( 'admin_enqueue_scripts', array( $this, 'wte_register_admin_assets' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'wte_register_front_assets' ) );
		add_action( 'admin_footer', array( $this, 'wte_ai_add_itinerary_template' ), 20 );
		add_action( 'admin_footer', array( $this, 'wte_ai_load_itinerary_extension_sample_fields' ), 20 );
		add_action( 'add_settings_for_advance_itinerary', array( $this, 'add_additional_settings_for_advance_itinerary' ) );
		add_action( 'save_post', array( $this, 'wpteai_save_meta_box' ) );
		add_action( 'wpte_save_and_continue_additional_meta_data', array( $this, 'wpteai_save_meta_box' ) );
		add_filter( 'wte_trip_itinerary_setting_path', array( $this, 'wte_advanced_itinerary_setting_call' ) );
		add_filter( 'wte_locate_template', array( $this, 'wte_advanced_itinerary_template_call' ), 10, 3 );
		add_filter( 'wpte_get_global_extensions_tab', array( $this, 'wte_advanced_itinerary_extensions_tab_call' ), 10, 3 );

		/**
		 * Adds alititude chart.
		 *
		 * @since 2.0.5
		 */
		add_action( 'wte_after_itinerary_header', array( $this, 'altitude_chart' ) );

	}

	/**
	 * Register admin hooks.
	 *
	 * @return void
	 * @since v2.2.4
	 */
	public function admin_hooks() {
		if ( version_compare( WP_TRAVEL_ENGINE_VERSION, '6.6.9', '>=' ) ) {
			
			//Register hooks for global settings.
			require_once WTEAD_CLASSES_DIR . '/settings/global/global.php';
			Globals::register_hooks();

			//Register hooks for trip edit settings.
			require_once WTEAD_CLASSES_DIR . '/settings/trip-edit/trip-edit.php';
			TripEdit::register_hooks();
		}
	}

	/**
	 * Display Altitude chart.
	 *
	 * @return void
	 */
	public function altitude_chart() {
		$settings = get_option( 'wp_travel_engine_settings', array() );
		$show_chart_on_trip_page = isset( $settings['wte_advance_itinerary']['chart']['show'] ) ? $settings['wte_advance_itinerary']['chart']['show'] : 'yes';
		if ( $show_chart_on_trip_page !== 'yes' ) {
			return;
		}
		wp_enqueue_script( 'wte-chart' );
		wp_enqueue_script( 'wte-chart-datalabels' );
		global $post;
		if ( empty( json_decode( get_post_meta( $post->ID, 'trip_itinerary_chart_data', true ) ) ) ) {
			
			return;
		}
		
		

		$html_atts = array(
			'id'     => 'wteAltChart',
			'class'  => 'ate-alt-chart',
			'height' => 450,
			'width'  => '100%',
		);

		$html_atts = implode(
			' ',
			array_map(
				function( $att_value, $att_key ) {
					return "$att_key=\"$att_value\"";
				},
				array_values( $html_atts ),
				array_keys( $html_atts )
			)
		);

		$elevation_unit_label = __( 'Altitude Unit:', 'wte-advanced-itinerary' );
		$labels               = array(
			'm'  => __( 'M.', 'wte-advanced-itinerary' ),
			'ft' => __( 'FT.', 'wte-advanced-itinerary' ),
		);

		$attachment_id  = isset( $settings['wte_advance_itinerary']['chart']['bg'] ) ? $settings['wte_advance_itinerary']['chart']['bg'] : '';
		$attachment_src = ! empty( $attachment_id ) ? wp_get_attachment_image_src( $attachment_id, 'full' )[0] : '';
		$background     = ! empty( $attachment_src ) ? 'background-image:url(' . esc_url( $attachment_src ) . ');' : '';
		?>
		<div class="altitude-chart-container">
			<div class='altitude-unit-switcher'>
				<label class="elevation-unit-label"><?php echo esc_html( $elevation_unit_label ); ?></label>
				<div class="altitude-unit-switches">
					<span><input type='radio' checked value='m' name='elevation-unit' id="elevation-unit-m"/><label for="elevation-unit-m"><?php  echo esc_html( $labels['m'] ); ?></label></span>
					<span><input type='radio' value='ft' name='elevation-unit' id="elevation-unit-ft"/><label for="elevation-unit-ft"><?php echo esc_html( $labels['ft'] ); ?></label></span>
				</div>
			</div>
			<div class="screen-canvas-wrap" style="<?php echo esc_attr( $background ); ?>">
				<div id="altitude-chart-screen">
					<canvas <?php echo $html_atts; ?>></canvas>
				</div>
			</div>
		</div>
		<?php
	}

	function wte_advanced_itinerary_extensions_tab_call( $sub_tabs ) {
		$sub_tabs['wte_advance_itinerary'] = array(
			'label'        => __( 'Advanced Itinerary Builder', 'wte-advanced-itinerary' ),
			'content_path' => WTEAD_ADMIN_DIR . 'itinerary-extension-subsetting-tab.php',
			'current'      => false,
		);
		return $sub_tabs;
	}

	function wte_advanced_itinerary_setting_call() {
		return WTEAD_ADMIN_DIR . 'itinerary-meta-setting-tab.php';
	}
	/**
	 * Main wrap of the single trip.
	 * aa
	 *
	 * @since    1.0.0
	 */
	function wte_advanced_itinerary_template_call( $template, $template_name, $template_path ) {
		if ( 'single-trip/trip-tabs/itinerary-tab.php' !== $template_name ) {
			return $template;
		}

		if ( false !== strpos( $template, '/themes/' ) ) {
			return $template;
		} else {
			return WTEAD_FRONT_TEMPLATE_DIR . 'trip-itinerary-template.php';
		}

		return $template;
	}

	/*
	 * i18n
	 */

	function load_wte_ai_textdomain() {
		 load_plugin_textdomain( 'wte-advanced-itinerary', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/** Action hook to add setting in advanced setting tab
	 */
	function add_additional_settings_for_advance_itinerary() {
		include WTEAD_ADMIN_DIR . 'itinerary-extension-subsetting-tab.php';
	}

	/**
	 * Init hooks For WTE_Advanced_Itinerary_Init
	 *
	 * @return void
	 */
	public function init_hooks() {
		load_plugin_textdomain( 'wte-advanced-itinerary', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		add_action( 'after_setup_theme', array( $this, 'wteai_setup_theme' ) );
	}


		/**
		 * Save meta box content.
		 *
		 * @param int $post_id Post ID
		 */
	function wpteai_save_meta_box() {
		global $post;
		// Get post ID.
		if ( ! is_object( $post ) && defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			$post_id = isset( $_POST['post_id'] ) && ! empty( $_POST['post_id'] ) ? $_POST['post_id'] : '';
		} else {
			$post_id = isset( $post ) && is_object( $post ) ? $post->ID : '';
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		if ( isset( $_POST['wte_advanced_itinerary'] ) && ! empty( $post_id ) ) {
			$obj      = new Wp_Travel_Engine_Functions();
			$settings = $obj->wte_sanitize_array( $_POST['wte_advanced_itinerary'] );
			update_post_meta( $post_id, 'wte_advanced_itinerary', $settings );
		}
	}
	 /**
	  * Define custom image size for gallery thumbnail
	  */
	function wteai_setup_theme() {
		add_theme_support( 'wteai-gallery-thumbnail' );
		add_image_size( 'wteai-gallery-thumbnail', 300, 200, true );
	}

		/**
		 * Back end Scripts
		 */
	function wte_register_admin_assets() {

		$screen = get_current_screen();
		if ( wp_script_is( 'wp-color-picker', 'enqueued' ) ) { // To fix issue library conflicts: Color and chart.js
			wp_dequeue_script( 'wp-color-picker' );
		}
		wp_register_script( 'wte-ai-common', WTEAD_JS_DIR . 'common.js', array( 'wte-chart', 'wte-chart-datalabels', 'wp-color-picker' ), WTEAI_VERSION, true );
		if ( $screen->post_type == 'trip' || $screen->post_type == 'booking' ) {
			wp_enqueue_script( 'wte-chart', plugin_dir_url( WTEAD_FILE_PATH ) . 'lib/Chart.min.js', array(), '2.9.4', true );
			wp_enqueue_script( 'wte-chart-datalabels', plugin_dir_url( WTEAD_FILE_PATH ) . 'lib/chartjs-plugin-datalabels.js', array(), '0.7.0', true );
			wp_register_script( 'wte-ai-backend-jquery', WTEAD_JS_DIR . 'wte-ai-admin.js', array( 'jquery' ) );
			$settings       = get_option( 'wp_travel_engine_settings', array() );
			$chart_settings = isset( $settings['wte_advance_itinerary']['chart'] ) && is_array( $settings['wte_advance_itinerary']['chart'] ) ? $settings['wte_advance_itinerary']['chart'] : array();
			$options        = wp_parse_args( $chart_settings, $this->default_chart_settings() );
			wp_localize_script(
				'wte-ai-backend-jquery',
				'wteAIL10n',
				apply_filters(
					'wteAIAdminL10n',
					$options
				)
			);
			wp_enqueue_script( 'wte-ai-common' );
			wp_enqueue_script( 'wte-ai-backend-jquery' );
			wp_enqueue_style( 'wte-ai-backend-design', WTEAD_CSS_DIR . 'wte-ai-admin.css' );
			wp_enqueue_editor();
		}

		if ( version_compare( WP_TRAVEL_ENGINE_VERSION, '6.6.9', '>=' ) ) {
			/**
			 * Enqueue advanced itinerary admin script and style.
			 * 
			 * @since v2.2.4
			 */
			$admin_script_path = plugin_dir_path( WTEAD_FILE_PATH ) . '/dist/admin/wpte-advanced-itinerary-admin.asset.php';
			$asset = require $admin_script_path;
			wp_enqueue_script( 'wpte-advanced-itinerary-admin', plugin_dir_url( WTEAD_FILE_PATH ) . 'dist/admin/wpte-advanced-itinerary-admin.js', array_merge( $asset['dependencies'], array( 'wp-hooks', 'wptravelengine-exports' ) ), WTEAI_VERSION, true );
			wp_enqueue_style( 'wpte-advanced-itinerary-admin', plugin_dir_url( WTEAD_FILE_PATH ) . 'dist/admin/wpte-advanced-itinerary-admin.css', array(), WTEAI_VERSION, 'all' );
		}
	}

	public function default_chart_settings() {
		return array(
			'chartData' => '[]',
			'unit'      => 'm',
			'data'      => array(
				'color'              => '#147dfe',
				'datasets.data.fill' => true,
			),
			'options'   => array(
				'scales.xAxes.display' => false,
				'scales.yAxes.display' => false,
			),
			'strings'   => array(
				'invalidJSON'         => __( 'Invalid JSON String', 'wte-advanced-itinerary' ),
				'data.datasets.label' => __( 'Altitude', 'wte-advanced-itinerary' ),
				'options.scales.yAxes.scaleLabel.labelString' => __( 'Altitude', 'wte-advanced-itinerary' ),
				'options.scales.xAxes.scaleLabel.labelString' => __( 'Days', 'wte-advanced-itinerary' ),
				'options.title.text'  => __( 'Location altitudes', 'wte-advanced-itinerary' ),
			),
		);
	}

	/**
	 * Front end Scripts
	 */
	function wte_register_front_assets() {
		$asset_script_path = 'min/';
		$version_prefix    = '-' . WTEAI_VERSION;

		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
			$asset_script_path = '';
			$version_prefix    = '';
		}

		if ( is_singular( 'trip' ) ) {
			wp_register_script( 'wte-chart', plugin_dir_url( WTEAD_FILE_PATH ) . 'lib/Chart.min.js', array(), '2.9.4', true );
			wp_register_script( 'wte-chart-datalabels', plugin_dir_url( WTEAD_FILE_PATH ) . 'lib/chartjs-plugin-datalabels.js', array(), '0.7.0', true );

			wp_register_script( 'wte-ai-common', WTEAD_JS_DIR . $asset_script_path . 'common' . $version_prefix . '.js', array( 'jquery' ), WTEAI_VERSION, true );
			wp_register_script( 'wte-ai-front', WTEAD_JS_DIR . $asset_script_path . 'wte-ai-front' . $version_prefix . '.js', array( 'jquery' ), filemtime( WTEAD_FILE_ROOT_DIR . 'assets/js/' . $asset_script_path . 'wte-ai-front' . $version_prefix . '.js' ), true );

			global $post;

			$trip = new Trip( $post->ID );

			$settings       = get_option( 'wp_travel_engine_settings', array() );
			$chart_settings = isset( $settings['wte_advance_itinerary']['chart'] ) && is_array( $settings['wte_advance_itinerary']['chart'] ) ? $settings['wte_advance_itinerary']['chart'] : array();
			$all_labels   	= $trip->get_itinerary_chart_labels();
			
			$chart_settings['chartData'] = json_decode( get_post_meta( $post->ID, 'trip_itinerary_chart_data', true ) );
			if ( is_object( $chart_settings['chartData'] ) ) {
				foreach ( $chart_settings['chartData'] as $key => $data ) {
					$chart_settings['chartData']->$key->label = $all_labels[ $key - 1 ] ?? '';
				}
			}

			$chart_settings['unit']      = ! empty( $settings['wte_advance_itinerary']['chart']['alt_unit'] ) ? $settings['wte_advance_itinerary']['chart']['alt_unit'] : 'm';
			$options                     = wp_parse_args( $chart_settings, $this->default_chart_settings() );
			wp_localize_script(
				'wte-ai-front',
				'wteAIL10n',
				apply_filters(
					'wteAIL10n',
					$options,
					$post->ID
				)
			);
			wp_enqueue_script( 'wte-ai-front' );
			wp_enqueue_script( 'wte-ai-common' );
			wp_enqueue_style( 'wte-ai-front', WTEAD_CSS_DIR . $asset_script_path . 'wte-ai-front' . $version_prefix . '.css', array(), WTEAI_VERSION, 'all' );
			wp_enqueue_style( 'wte-advanced-itinerary-public', plugin_dir_url( WTEAD_FILE_PATH ) . 'dist/public/wpte-advanced-itinerary-public.css', array(), WTEAI_VERSION, 'all' );

			wp_enqueue_script( 'jquery-fancy-box' );

			wp_enqueue_style( 'jquery-fancy-box' );

			wp_enqueue_script( 'wte-custom-scrollbar' );

			wp_enqueue_style( 'wte-custom-scrollbar' );
		}
	}

	/*
	 *  New Metabox Template Sample According to our plugin
	 */

	function wte_ai_add_itinerary_template() {
		$screen = get_current_screen();
		if ( $screen->post_type == 'trip' ) {
			include WTEAD_ADMIN_DIR . 'itinerary-meta-clone-settings.php';
		}
	}

	/*
	 * New Extra Setting Template Sample According to our plugin
	 */

	function wte_ai_load_itinerary_extension_sample_fields() {
		$screen = get_current_screen();
		if ( $screen->post_type == 'booking' ) {
			include WTEAD_ADMIN_DIR . 'itinerary-extension-clone-setting.php';
		}
	}

		/**
		 * Sanitizign the Multi depth Array
		 */
	function sanitize_array( $array = array(), $sanitize_rule = array() ) {
		if ( ! is_array( $array ) || count( $array ) == 0 ) {
			return array();
		}

		foreach ( $array as $k => $v ) {
			if ( ! is_array( $v ) ) {

				$default_sanitize_rule = ( is_numeric( $k ) ) ? 'html' : 'text';
				$sanitize_type         = isset( $sanitize_rule[ $k ] ) ? $sanitize_rule[ $k ] : $default_sanitize_rule;
				$array[ $k ]           = $this->sanitize_value( $v, $sanitize_type );
			}
			if ( is_array( $v ) ) {
				$array[ $k ] = $this->sanitize_array( $v, $sanitize_rule );
			}
		}

		return $array;
	}

		/**
		 * Sanitizes Value in the multi depth array
		 */
	function sanitize_value( $value = '', $sanitize_type = 'text' ) {
		switch ( $sanitize_type ) {
			case 'html':
				$allowed_html = wp_kses_allowed_html( 'post' );
				return wp_kses( $value, $allowed_html );
				break;
			default:
				return sanitize_text_field( $value );
				break;
		}
	}

}

// Initialize Class
new WTE_Advanced_Itinerary_Init();
