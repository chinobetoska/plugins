<?php
/** Handle ajax requests.
 *
 * @package    Extra_Services_Wp_Travel_Engine
 * @subpackage Extra_Services_Wp_Travel_Engine/admin
 * @author     WP Travel Engine <info@wptravelengine.com>
 */
class Extra_Services_Wp_Travel_Engine_Ajax {

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
     * Ajax actions and callbacks.
     * 
     * @since     1.0.0
     * @access    private
     * @var       array     $actions The ajax actions and callbacks. 
     */
    private $actions = array(
        'wte_extra_service_get_extra_service_template' => array(
            'priv' => 'get_extra_service_template',
            'nopriv' => 'get_extra_service_template'
        )
    );
    

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

        // Initializes hooks.
        $this->init_hooks();
    }

    /**
     * Intializes hooks.
     * 
     * @access private
     * @since 1.0.0
     */
    private function init_hooks() {
        foreach( $this->actions as $action => $callbacks ) {
            if ( isset( $callbacks['priv'] ) ) {
                add_action( "wp_ajax_{$action}", array( $this, $callbacks['priv'] ) );
            }

            if ( isset( $callbacks['nopriv'] ) ) {
                add_action( "wp_ajax_nopriv_{$action}", array( $this, $callbacks['nopriv'] ) );
            }
        }
    }

    public function get_extra_service_template() {
        // Get WP Travel Engine settings.
        $wte_option_settings = get_option( 'wp_travel_engine_settings', false );
        
        // Set index and name.
        $index = isset( $_POST['index'] ) ? $_POST['index'] : 0 ;
        $name = isset( $_POST['name'] ) ? $_POST['name'] : 'wp_travel_engine_settings';

        // If extra service is present, fill the extra service template.
        $defaults = array(
            'extra_service'      => '',
            'extra_service_cost' => '',
            'extra_service_desc' => '',
            'extra_service_unit' => 'unit',
        );

        // If extra service is not found, extract defaults.
        if ( ! isset( $_POST['extra_service'] ) ) {
            extract( $defaults );
        }
            
        $extra_service_index = array_search( $_POST['extra_service'], $wte_option_settings['extra_service'] );            
        if ( false !== $extra_service_index ) {
            $extra_service = array(
                'extra_service'      => $wte_option_settings['extra_service'][ $extra_service_index ],
                'extra_service_cost' => $wte_option_settings['extra_service_cost'][ $extra_service_index ],
                'extra_service_desc' => $wte_option_settings['extra_service_desc'][ $extra_service_index ],
                'extra_service_unit' => $wte_option_settings['extra_service_unit'][ $extra_service_index ],
            );
            $extra_service = wp_parse_args( $extra_service, $defaults );
            extract ( $extra_service );
        } else {
            extract( $defaults );
        }
        
        require_once WTE_EXTRA_SERVICE_PATH . '/admin/partials/extra-services-wp-travel-engine-admin-extra-service.php';
        wp_die();
    }
}
