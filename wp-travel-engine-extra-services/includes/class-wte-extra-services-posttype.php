<?php
/**
 *
 * Registers post type for trip extra services.
 *
 * @since 2.0.4
 */

/**
 * Class WTE_Extra_Services_Posttype.
 */
class WTE_Extra_Services_Posttype {

	/**
	 * Hold current instance.
	 *
	 * @var null|WTE_Extra_Services_Posttype
	 */
	protected static $instance = null;

	/**
	 * @var posttype
	 */
	private $posttype = 'wte-services';

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->register(
			apply_filters(
				'wte_extra_services_posttype_args',
				array(
					'label'               => __( 'Extra Services', 'wte-extra-services' ),
					'public'              => false,
					'show_in_rest'        => true,
					'show_in_menu'        => 'edit.php?post_type=booking',
					'map_meta_cap'        => true,
					'supports'            => array( 'custom-fields', 'title', 'editor' ),
					'exclude_from_search' => true,
					'show_ui'             => true,
				)
			)
		);

	}

	/**
	 * Returns singe instance.
	 *
	 * @return WTE_Extra_Services_Posttype
	 */
	public static function instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Registers Post Type wte-extra-services.
	 *
	 * @return void
	 */
	private function register( $args ) {
		register_post_type(
			$this->posttype,
			$args
		);
		register_post_meta(
			$this->posttype,
			'wte_services',
			array(
				'show_in_rest' => array(
					'schema' => array(
						'type'       => 'object',
						'properties' => array(
							'service_cost' => array(
								'type' => 'string',
							),
							'single_price' => array(
								'type' => 'string',
							),
							'service_required' => array(
								'type' => 'boolean',
							),
							'pricing_type' => array(
								'type' => 'string',
							),
							'service_unit' => array(
								'type' => 'string',
							),
							'service_type' => array(
								'type' => 'string',
							),
							'field_type'   => array(
								'type' => 'string',
							),
							'options'      => array(
								'type'  => 'array',
								'items' => array(
									'type' => 'string',
								),
							),
							'prices'       => array(
								'type'  => 'array',
								'items' => array(
									'type' => 'string',
								),
							),
							'unit' => array(
								'type' => 'array',
								'items' => array(
									'type' => 'string',
								),
							),
							'descriptions' => array(
								'type'  => 'array',
								'items' => array(
									'type' => 'string',
								),
							),
							'attributes'   => array(
								'type'  => 'array',
								'items' => array(
									'type'  => 'array',
									'items' => array(
										'type'       => 'object',
										'properties' => array(
											'label'   => array(
												'type' => 'string',
											),
											'options' => array(
												'type'  => 'array',
												'items' => array(
													'type' => 'string',
												),
											),
										),
									),
								),
							),
						),
					),
				),
				'type'         => 'object',
				'single'       => true,
			)
		);
	}
}

WTE_Extra_Services_Posttype::instance();
