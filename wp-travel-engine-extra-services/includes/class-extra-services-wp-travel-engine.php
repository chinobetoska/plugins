<?php

use WPTravelEngine\Core\Controllers\RestAPI\V2;
use WPTravelEngine\Filters\TripAPISchema;
use WPTravelEngine\Core\Controllers\RestAPI\V2\Trip as TripController;

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://wptravelengine.com/
 * @since      1.0.0
 *
 * @package    Extra_Services_Wp_Travel_Engine
 * @subpackage Extra_Services_Wp_Travel_Engine/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Extra_Services_Wp_Travel_Engine
 * @subpackage Extra_Services_Wp_Travel_Engine/includes
 * @author     WP Travel Engine <info@wptravelengine.com>
 */
class Extra_Services_Wp_Travel_Engine {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Extra_Services_Wp_Travel_Engine_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		define( 'WTE_EXTRA_SERVICES_VERSION', WPTRAVELENGINE_EXTRA_SERVICES_VERSION );
		define( 'WTE_EXTRA_SERVICES_FILE_PATH', WPTRAVELENGINE_EXTRA_SERVICES_FILE_PATH );
		define( 'WTE_EXTRA_SERVICES_REQUIRES_AT_LEAST', WPTRAVELENGINE_EXTRA_SERVICES_REQUIRES_AT_LEAST );
		
		if ( defined( 'WTE_EXTRA_SERVICES_VERSION' ) ) {
			$this->version = WTE_EXTRA_SERVICES_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'wte-extra-services';

		/**
		 * New files since 2.0.4 are includes here.
		 */
		$this->includes();

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

		/**
		 * New hooks added from here.
		 *
		 * @since 2.0.3
		 */
		$this->init_hooks();

	}

	/**
	 * Files includes here.
	 *
	 * @return void
	 */
	private function includes() {
		require_once sprintf( '%s/includes/helpers.php', WTE_EXTRA_SERVICE_PATH );
		require_once sprintf( '%s/includes/class-wte-extra-services-ajax.php', WTE_EXTRA_SERVICE_PATH );
		require_once sprintf( '%s/includes/class-wte-extra-services-metaboxes.php', WTE_EXTRA_SERVICE_PATH );
		if ( version_compare( WTE_EXTRA_SERVICES_VERSION, '2.0.3', '>=' ) ) {
			require_once sprintf( '%s/upgrades/204.php', WTE_EXTRA_SERVICE_PATH );
		}
	}

	/**
	 * {init} hooks.
	 *
	 * @return void
	 */
	public function init() {
		require_once sprintf( '%s/includes/class-wte-extra-services-posttype.php', WTE_EXTRA_SERVICE_PATH );
	}

	/**
	 * Hooks and filters.
	 *
	 * @return void
	 */
	private function init_hooks() {
		add_action( 'init', array( $this, 'init' ) );
		add_filter(
			'wp_travel_engine_admin_trip_meta_tabs',
			function( $trip_meta_tabs ) {
				wp_enqueue_script( 'wte-select2' );
				wp_enqueue_style( 'wte-select2' );
				if ( ! apply_filters( 'use_legacy_extra_services', false ) ) {
					$trip_meta_tabs['wpte-extra-services']['content_path'] = WTE_EXTRA_SERVICE_PATH . '/admin/partials/trip/edit/tab__extra-services.php';
				}
				return $trip_meta_tabs;
			},
			11
		);
		add_filter(
			'trip_view__booking__services_tab_content__path',
			function( $file_path ) {
				if ( apply_filters( 'use_legacy_extra_services', false ) ) {
					return $file_path;
				}
				return WTE_EXTRA_SERVICE_PATH . '/public/partials/trip/view/booking__services-tab-content.php';
			}
		);


		add_filter( 'wptravelengine_tripedit:extensions:fields', array( $this, 'add_extra_services_trip_meta_tab' ), 20, 2 );

		if( has_action( 'wptravelengine_prepare_extra_services' ) && has_action( 'wptravelengine_update_extra_services' ) ) {
			// Remove actions from TripAPISchema.
			$trip_api_schema = new TripAPISchema();
			remove_action( 'wptravelengine_prepare_extra_services', array( $trip_api_schema, 'prepare_extra_services' ), 10 );
			remove_action( 'wptravelengine_update_extra_services', array( $trip_api_schema, 'update_extra_services' ), 10 );

			// Add actions to prepare and update extra services.
			add_filter('wptravelengine_rest_prepare_trip', array( $this, 'prepare_extra_services' ), 20, 3 );
			add_filter( 'wptravelengine_api_update_trip', array( $this, 'update_extra_services' ), 20, 2 );
			add_filter( 'wptravelengine_trip_api_schema', array( $this, 'update_trip_api_schema' ), 20, 2 );
		}


		/**
		 * Adds REST API Meta for trip.
		 *
		 * @return void
		 */
		add_filter(
			'wte_rest_field__' . WP_TRAVEL_ENGINE_POST_TYPE,
			function( $fields ) {
				$fields['trip_extras'] = array(
					'schema'       => array(
						'type'   => 'array',
						'schema' => array( 'items' => 'number' ),
					),
					'get_callback' => function( $object, $field_name, $default ) {
						if ( ! defined( 'WTE_EXTRA_SERVICE_PATH' ) ) {
							return array();
						}
						$object_meta = get_post_meta( $object['id'], 'wp_travel_engine_setting', ! 0 );
						$value = wte_array_get( $object_meta, 'wte_services_ids', array() );
						return is_string( $value ) ? array_map(
							function( $v ) {
								return (int) trim( $v );
							},
							explode( ',', $value )
						) : (array) $value;
					},
				);

				$fields['trip_extra_services'] = array(
					'schema'       => array(
						'type'   => 'array',
						'schema' => array( 'items' => 'number' ),
					),
					'get_callback' => function( $object, $field_name, $default ) {
						if ( ! defined( 'WTE_EXTRA_SERVICE_PATH' ) ) {
							return array();
						}
						$object_meta = get_post_meta( $object['id'], 'wp_travel_engine_setting', ! 0 );
						$value              = wte_array_get( $object_meta, 'wte_services_ids', array() );
						$extra_services_ids = is_string( $value ) ? array_map(
							function( $v ) {
								return (int) trim( $v );
							},
							explode( ',', $value )
						) : (array) $value;

						$extra_services = array();
						foreach ( $extra_services_ids as $esid ) {
							$es_object = get_post( $esid );
							if ( is_null( $es_object ) ) {
								continue;
							}
							$meta_value = $es_object->{'wte_services'};
							if ( empty( $meta_value ) ) {
								continue;
							}

							$options = array();
							if ( isset( $meta_value['service_type'] ) && 'custom' === $meta_value['service_type'] && isset( $meta_value['options'] ) ) {
								foreach ( $meta_value['options'] as $index => $value ) {
									$options[ $index ]['label'] = $value;
									$options[ $index ]['description'] = ! empty( $meta_value['descriptions'][ $index ] ) ? $meta_value['descriptions'][ $index ] : '';
									$options[ $index ]['price'] = ! empty( $meta_value['prices'][ $index ] ) ? +$meta_value['prices'][ $index ] : 0;
									$options[ $index ]['attributes']  = ! empty( $meta_value['attributes'][ $index ] ) ? +$meta_value['attributes'][ $index ] : array();
								}
							}

							$meta_value['options'] = $options;
							unset( $meta_value['descriptions'] );
							unset( $meta_value['prices'] );
							unset( $meta_value['attributes'] );

							$meta_value['title'] = $es_object->post_title;
							$extra_services[]    = $meta_value;
						}
						return empty( $extra_services ) ? new StdClass() : $extra_services;
					},
				);
				return $fields;
			}
		);
		

		/**
		 * Booking process ES templates.
		 *
		 * @return void
		 */
		// Extra Service Tab On trip booking popup.
		add_filter(
			'wte-booking-process-tabs',
			function( $tabs ) {
				global $post;

				$trip_meta = get_post_meta( $post->ID, 'wp_travel_engine_setting', true );

				if ( empty( $trip_meta['wte_services_ids'] ) ) {
					return $tabs;
				}

				$tabs['extraservices'] = array(
					'id'               => 'wte-booking-extraservices',
					'tab_title'        => __( 'Extra Services', 'wte-extra-services' ),
					'tab_icon'         => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="4" viewBox="0 0 16 4"><g id="Group_2257" data-name="Group 2257" transform="translate(-349.914 -82)"><g id="Group_2418" data-name="Group 2418"><circle id="Ellipse_100" data-name="Ellipse 100" cx="2" cy="2" r="2" transform="translate(349.914 82)"/><circle id="Ellipse_101" data-name="Ellipse 101" cx="2" cy="2" r="2" transform="translate(355.914 82)"/><circle id="Ellipse_102" data-name="Ellipse 102" cx="2" cy="2" r="2" transform="translate(361.914 82)"/></g></g></svg>',
					'content_callback' => function() {
						require_once plugin_dir_path( WTE_EXTRA_SERVICES_FILE_PATH ) . 'includes/script-templates/booking-process/tmpl-wte-booking-extraservices.php';
					},
				);
				return $tabs;
			}
		);

		// Extra Services Summary
		add_action(
			'wte_after_booking_details',
			function() {
				echo '<div id="wte-booking-es-summary-content" class="wte-booking-details"></div>';
			}
		);

		add_filter( 'wte_booking_mail_tags', array( $this, 'mail_tags' ), 11, 2 );
		
	}

	/**
	 * Prepare the extra services.
	 *
	 * @param array $data
	 * @param WP_REST_Request $request
	 * @param TripController $controller
	 * @return void
	 */
	public function prepare_extra_services( array $data, WP_REST_Request $request, TripController $controller ): array {

        // Check if the addon is active.
        if ( !wptravelengine_is_addon_active('extra-services') ) {
            return $data;
        }
    
        // Get the extra services ids.
        $extra_services_ids = $controller->trip->get_setting('wte_services_ids');
        if ( empty( $extra_services_ids ) ) {
            return $data;
        }
    
        $services = get_posts([
            'post_type'         => 'wte-services',
            'post_status'       => 'publish',
            'post__in'          => explode(',', $extra_services_ids),
            'posts_per_page'    => -1,
            'orderby'           => 'post__in',
        ]);
    
        // Get the trip extra services.
        $trip_extra_services = $controller->trip->get_setting( 'trip_extra_services' ) ?? [];
        
        // Loop through the services.
        foreach ( $services as $index => $service ) {
            $service_data = get_post_meta($service->ID, 'wte_services', true);

            // Skip if service data is empty.
            if ( empty( $service_data ) ) {
                continue;
            }
            
            // Get extra service data saved in meta for current index.
            $extra_service_data = $trip_extra_services[$index] ?? [];

            // Handle Options.
            $service_data['options'] = $extra_service_data['options'] ?? $service_data['options'] ?? [];
            
            // Handle Descriptions.
            $service_data['descriptions'] = $extra_service_data['descriptions'] ?? $service_data['descriptions'] ?? [];
            
            // Handle Prices.
            $service_data['prices'] = $extra_service_data['prices'] ?? $service_data['prices'] ?? [];

            // Handle Compatibility with old data for Default Service Type.
            if( $service_data['service_type'] != 'custom' ) {
               // Set default price if not set or empty string.
                if ( !isset( $service_data['prices'][0] ) || $service_data['prices'][0] === '' ) {
                    $service_data['prices'] = [
                        0 => $service_data['service_cost'] ?? 0
                    ];
                }
                
                // Set default description if not set or empty string.
                if ( !isset( $service_data['descriptions'][0] ) || $service_data['descriptions'][0] === '' ) {
                    $service_data['descriptions'] = [
                        0 => get_the_content( '', false, $service->ID ) ?? ''
                    ];
                }
            }
            
            // Handle Service Type.
            $service_data['service_type'] = isset( $service_data['service_type'] ) 
                ? ( $service_data['service_type'] == 'custom' ? 'Advanced' : 'Default' )
                : 'Default';

            $service_post_data = get_post_meta($service->ID, 'wte_services', true);
            //Map options name to service_post_data options name.
            foreach( $service_post_data['options'] as $key => $option ) {
                if( isset( $service_data['options'][$key] ) ) {
                    $service_data['options'][$key] = $option;
                }
            }

            // Add service data to trip extra services.
            $data['trip_extra_services'][] = [
                'id'            => (int)$service->ID,
                'label'         => (string)$service->post_title,
                'type'          => (string)$service_data['service_type'],
                'options'       => (array)$service_data['options'],
                'descriptions'  => (array)$service_data['descriptions'],
                'prices'        => (array) $service_data['prices'],
            ];
        }

		return $data;
    }

	/**
	 * Update the extra services.
	 *
	 * @param WP_REST_Request $request
	 * @param TripController $controller
	 * @return void
	 */
	public function update_extra_services( WP_REST_Request $request, TripController $controller ): void {
		$trip_settings    = $controller->trip_settings;
		if ( isset( $request[ 'trip_extra_services' ] ) ) {
            $trip_settings->set( 'trip_extra_services', $request[ 'trip_extra_services' ] );
			$trip_settings->set( 'wte_services_ids', implode( ',', array_column( $request[ 'trip_extra_services' ], 'id' ) ) );
		}
	}

	/**
	 * Update the trip api schema.
	 *
	 * @param array $schema
	 * @param TripController $controller
	 * @return array
	 */
	public function update_trip_api_schema( array $schema, TripController $controller ): array {
		$schema[ 'trip_extra_services' ] = array(
            'description' => __( 'Trip extra services.', 'wte-extra-services' ),
            'type'        => 'array',
            'items'       => array(
                'type'       => 'object',
                'properties' => array(
                    'id'      => array(
                        'description' => __( 'Extra service ID.', 'wte-extra-services' ),
                        'type'        => 'integer',
                    ),
                    'label'   => array(
                        'description' => __( 'Extra service label.', 'wte-extra-services' ),
                        'type'        => 'string',
                    ),
                    'type'    => array(
                        'description' => __( 'Extra service type.', 'wte-extra-services' ),
                        'type'        => 'string',
                    ),
                    'options' => array(
                        'description' => __( 'Extra service options.', 'wte-extra-services' ),
                        'type'        => 'array',
                        'items'       => array(
                            'type' => 'string',
                        ),
                    ),
                    'descriptions' => array(
                        'description' => __( 'Extra service descriptions.', 'wte-extra-services' ),
                        'type'        => 'array',
                        'items'       => array(
                            'type' => 'string',
                        ),
                    ),
                    'prices' => array(
                        'description' => __( 'Extra service prices.', 'wte-extra-services' ),
                        'type'        => 'array',
                        'items'       => array(
                            'type' => 'number',
                        ),
                    ),
                ),
            ),
        );

		return $schema;
	}

	/**
	 * Add extra services trip meta tab.
	 *
	 * @param array $fields
	 * @param string $extension
	 * @since 2.2.1
	 * @return array
	 */
	public function add_extra_services_trip_meta_tab( array $fields, string $extension ): array {
		if ( 'extra-services' === $extension ) {
			$services = get_posts(
				array(
					'post_type'      => 'wte-services',
					'post_status'    => 'publish',
					'posts_per_page' => - 1,
					'orderby'        => 'post__in',
				)
			);
            
			$extra_services = [];
			foreach ( $services ?? [] as $service ) {
				if ( $service_data = get_post_meta( $service->ID, 'wte_services', true ) ) {
                    $service_data[ 'service_type' ] = $service_data[ 'service_type' ] == 'custom' ? __( 'Advanced', 'wte-extra-services' ) : __( 'Default', 'wte-extra-services' );
					$extra_services[] = [
                        'id'            => (int) $service->ID ?? 0,
                        'label'         => (string) $service->post_title ?? '',
                        'type'          => (string) $service_data[ 'service_type' ] ?? '',
                        'options'       => (array) ( $service_data[ 'options' ] ?? [] ),
                        'prices'        => isset( $service_data[ 'service_cost' ] ) && $service_data[ 'service_cost' ] > 0 && $service_data[ 'service_type' ] === 'Default' ? (array) floatval( $service_data[ 'service_cost' ] ) : (array) ( $service_data[ 'prices' ] ?? [] ),
                        'descriptions'  => (array) (
                            isset( $service_data['service_type'] ) && $service_data['service_type'] === 'Advanced'
                                ? ( $service_data['descriptions'] ?? [] )
                                : (
                                    !empty( $service_data['default_descriptions'] )
                                        ? $service_data['default_descriptions']
                                        : apply_filters('the_content', get_the_content('', false, $service->ID))
                                )
                        ),
					];
				}
			}
            
			$fields = array(
                array(
                    'field'       	=> [
                        'type'     	=> 'ALERT',
                        'content'	=> __('<p><strong>NOTE:</strong> Do you want to provide additional services such as supplementary room, hotel upgrade, airport pick and drop, etc? Extra Services extension allows you to create add-on services and sell more to your customer. <a href="https://wptravelengine.com/plugins/extra-services/?utm_source=free_plugin&utm_medium=pro_addon&utm_campaign=upgrade_to_pro" target="_blank">Get Extra Services extension now</a></p>', 'wte-extra-services'),
                    ],
                    'visibility'  	=> ! wptravelengine_is_addon_active( 'extra-services' ),
                ),
				array(
					'field'       	=> [
						'type'     	=> 'ALERT',
						'content'	=> __("<p><strong>NOTE:</strong> You can add, edit and delete the global extra services via <strong>WP Travel Engine > Extra Services</strong>.</p>", 'wte-extra-services'),
					],
					'visibility'  	=> wptravelengine_is_addon_active( 'extra-services' ),
				),
				array(
					'label'       	=> __('Section Extra Service', 'wte-extra-services'),
					'description' 	=> __('Choose and select the global Extra Service.', 'wte-extra-services'),
					'divider'    	=> true,
					'field'   		=> array(
						'type'         => 'EXTRA_SERVICES',
						'name'         => 'trip_extra_services',
                        'options'      => $extra_services,
                    ),
					'visibility'  	=> wptravelengine_is_addon_active( 'extra-services' ),
				)
			);
		}

		return $fields;
	}
	
	/**
	 *
	 * @since 2.1.1
	 */
	public function mail_tags( $mail_tags, $payment_id ) {

		$booking_id  = get_post_meta( $payment_id, 'booking_id', true );
		$order_trips = get_post_meta( $booking_id, 'order_trips', true );
		$cart_info   = get_post_meta( $booking_id, 'cart_info', true );

		$email_output = '';

		$total_extra_service_cost = 0;
		if ( is_array( $order_trips ) ) {
			foreach ( $order_trips as $cart_trip ) {
				if ( ! isset( $cart_trip['trip_extras'] ) || ! is_array( $cart_trip['trip_extras'] ) ) {
					continue;
				}
				foreach ( $cart_trip['trip_extras'] as $trip_extra ) {
					$extra_service_cost        = floatval( $trip_extra['qty'] * $trip_extra['price'] );
					$total_extra_service_cost += $extra_service_cost;
					$formated_cost             = wte_get_formated_price( $extra_service_cost, $cart_info['currency'] );
					$extra_services_trip_cost  = wte_get_formated_price( $trip_extra['price'], $cart_info['currency'] );

					$email_output .= '<div>';
					$email_output .= "<span>{$trip_extra['extra_service']}</span>, ";
					$email_output .= "<span>{$trip_extra['qty']}</span> X ";
					$email_output .= "<span>{$extra_services_trip_cost}</span> = ";
					$email_output .= "<span>{$formated_cost}</span>";
				}
			}
			if ( $total_extra_service_cost > 0 ) {
				$email_output .= "<div>Extra Services Cost = {$total_extra_service_cost}</div>";
				$email_output .= '</div>';
			}
		}

		$mail_tags['{extra_services}'] = $email_output;
		return $mail_tags;
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Extra_Services_Wp_Travel_Engine_Loader. Orchestrates the hooks of the plugin.
	 * - Extra_Services_Wp_Travel_Engine_i18n. Defines internationalization functionality.
	 * - Extra_Services_Wp_Travel_Engine_Admin. Defines all hooks for the admin area.
	 * - Extra_Services_Wp_Travel_Engine_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-extra-services-wp-travel-engine-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-extra-services-wp-travel-engine-i18n.php';

		/**
		 * The class responsible for ajax requests of the plugn.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-extra-services-wp-travel-engine-ajax.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-extra-services-wp-travel-engine-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-extra-services-wp-travel-engine-public.php';

		/**
		 * The class responsible for adding metaboxes to the booking post type.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-extra-services-wp-travel-engine-booking-metabox.php';

		/**
		 * The class responsible for updating the add-on from EDD.
		 */
		require plugin_dir_path( dirname( __FILE__ ) ) . '/updater/wte-extra-services-updater.php';

		$this->loader = new Extra_Services_Wp_Travel_Engine_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Extra_Services_Wp_Travel_Engine_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Extra_Services_Wp_Travel_Engine_i18n();

		$this->loader->add_action( 'init', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin   = new Extra_Services_Wp_Travel_Engine_Admin( $this->get_plugin_name(), $this->get_version() );
		$plugin_ajax    = new Extra_Services_Wp_Travel_Engine_Ajax( $this->get_plugin_name(), $this->get_version() );
		$plugin_metabox = new Extra_Services_Wp_Travel_Engine_Booking_Metabox( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'add_extra_services', $plugin_admin, 'add_extra_services' );
		$this->loader->add_action( 'add_extra_services_trips', $plugin_admin, 'add_extra_services_trips' );
		// $this->loader->add_action( 'add_extra_services_tab', $plugin_admin, 'add_extra_services_tab' );
		$this->loader->add_filter( 'wp_travel_engine_admin_trip_meta_tabs', $plugin_admin, 'add_extra_services_tab' );

		$this->loader->add_filter( 'wpte_trip_meta_array_key_bases', $plugin_admin, 'add_extra_service_array_key' );

		// $this->loader->add_action( 'wp_ajax_wpte_add_extra_service', $plugin_admin, 'wpte_add_extra_service' );
		// $this->loader->add_action( 'wp_ajax_nopriv_wpte_add_extra_service', $plugin_admin, 'wpte_add_extra_service' );

		// Fix array indices of extra services in options table
		// when deleted in settings page of WP Travel Engine.
		$this->loader->add_action( 'pre_update_option_wp_travel_engine_settings', $plugin_admin, 'fix_array_indices_in_options', 10, 2 );

		// Fix array indices of extra services in post meta table
		// when deleted in post trip page.
		$this->loader->add_action( 'updated_postmeta', $plugin_admin, 'fix_array_indices_in_trip_meta', 10, 4 );

		// Save extra services in the booking post type.
		$this->loader->add_action( 'save_post_booking', $plugin_admin, 'save_extra_services', 11, 3 );
		// $this->loader->add_action('wpte_save_and_continue_additional_meta_data', array($this, 'save_extra_services'));

		// Add extra services in email tags.
		$this->loader->add_action( 'wte_additional_payment_email_tags', $plugin_admin, 'add_payment_email_tag' );
		$this->loader->add_action( 'wte_additional_booking_email_tags', $plugin_admin, 'add_payment_email_tag' );

		// Extra services email tags output render for booking receipt.
		$this->loader->add_action( 'wte_booking_reciept_email_content', $plugin_admin, 'replace_extra_services_tag', 10, 2 );

		// Extra services email tags output render for purchase receipt.
		$this->loader->add_action( 'wte_purchase_reciept_email_content', $plugin_admin, 'replace_extra_services_tag', 10, 2 );

		$this->loader->add_filter( 'wpte_get_global_extensions_tab', $plugin_admin, 'wte_extra_services_extensions_tab_call', 10, 2 );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Extra_Services_Wp_Travel_Engine_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'calculate_extra_services_cost', $plugin_public, 'calculate_extra_services_cost', 1000, 1 );
		$this->loader->add_action( 'add_extra_services_fontend', $plugin_public, 'add_extra_services_fontend' );
		$this->loader->add_action( 'show_extra_service_checkout', $plugin_public, 'show_extra_service_checkout' );

		$this->loader->add_filter( 'wte_trip_booking_steps', $plugin_public, 'add_trip_booking_step' );
		$this->loader->add_action( 'wte_after_travellers_booking_step', $plugin_public, 'add_booking_step_content' );
		$this->loader->add_action( 'wte_before_trip_price_total', $plugin_public, 'add_extra_service_price_holder' );

		$this->loader->add_action( 'wp_travel_engine_tour_extras_paypal_request_args', $plugin_public, 'standard_paypal_args', 10, 4 );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Extra_Services_Wp_Travel_Engine_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Execute Plugin.
	 *
	 * @return void
	 */
	public static function execute() {
		$instance = new static();
		$instance->run();
	}
}
