<?php
/**
 * Plugin Install Page.
 *
 * @since 1.0.0
 */

namespace WPTravelEnginePro\Admin\Controllers;

use WPTravelEnginePro\Slug;
use WPTravelEnginePro\Admin\ExtensionsListTable;
use stdClass;
use WPTravelEnginePro\Store;
use WPTravelEnginePro\License;

class PluginInstall {

	protected static ?PluginInstall $instance = null;

	public static function instance(): PluginInstall {
		if ( is_null( static::$instance ) ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	public function hooks() {
		add_filter( 'install_plugins_tabs', [ $this, 'install_plugins_tabs' ] );
		add_action( 'install_plugins_wptravelengine', [ $this, 'install_plugins_wptravelengine' ] );
		add_filter( 'install_plugins_table_api_args_wptravelengine', function ( $args ) {
			if ( ! $args ) {
				$args = array();
			}

			$args[ 'browse' ]   = 'wptravelengine';
			$args[ 'paged' ]     = (int) ( $_GET[ '_page' ] ?? 1 );
			$args[ 'per_page' ] = (int) ( $_GET[ 'per_page' ] ?? 100 );
			$args[ 'locale' ]   = get_user_locale();

			return $args;
		} );
		add_filter( 'wp_list_table_class_name', function ( string $classname ) {
			global $tab;

			if ( 'wptravelengine' === ( $_GET[ 'tab' ] ?? '' ) ) {
				$classname = ExtensionsListTable::class;
			}

			return $classname;
		}, 10, 2 );

	}

	/**
	 * Check for Updates at the defined API endpoint and modify the update array.
	 *
	 * This function dives into the update API just when WordPress creates its update array,
	 * then adds a custom API call and injects the custom plugin data retrieved from the API.
	 * It is reassembled from parts of the native WordPress plugin update code.
	 * See wp-includes/update.php line 121 for the original wp_update_plugins() function.
	 *
	 * @param mixed $_transient_data Update array build by WordPress.
	 *
	 * @return object Modified update array with custom plugin data.
	 * @uses api_request()
	 */
	public static function check_update( $_transient_data ) {

		global $pagenow;

		if ( ! is_object( $_transient_data ) ) {
			$_transient_data = new stdClass();
		}

		if ( 'plugins.php' == $pagenow && is_multisite() ) {
			return $_transient_data;
		}

		$installed_plugins = get_plugins();

		foreach ( $installed_plugins as $plugin => $data ) {

			if ( ! empty( $_transient_data->response[ $plugin ] ) ) {
				continue;
			}

			$plugin_file = WP_PLUGIN_DIR . '/' . $plugin;
			if ( ! file_exists( $plugin_file ) ) {
				continue;
			}

			$wte_header = get_file_data( $plugin_file, [ 'WTE' => 'WTE' ] );
			if ( empty( $wte_header['WTE'] ) ) {
				continue;
			}

			$data['WTE'] = $wte_header['WTE'];

			$license_key = wptravelengine_pro_get_saved_license_key( dirname( $plugin ) );

			if ( empty( $license_key ) ) {
				continue;
			}

			$plugin_slug =  explode( '/', $plugin )[ 0 ];

			$product_slug = array_search( $plugin_slug, Slug::$map, true );
			if ( false === $product_slug ) {
				$product_slug = $plugin_slug;
			}

			$current = (object) wptravelengine_pro_get_extensions_version( $product_slug );

			$active_version = $data[ 'Version' ];

			$current->plugin = $plugin;
			if ( $current->new_version ?? false ) {
				$current->slug = $plugin_slug;
				if ( version_compare( $active_version, $current->new_version, '<' ) ) {
					$_transient_data->response[ $plugin ] = $current;
				} else {
					$_transient_data->no_update[ $plugin ] = $current;
				}
			}

			$_transient_data->last_checked       = time();
			$_transient_data->checked[ $plugin ] = $active_version;
		}

		return $_transient_data;
	}

	public function install_plugins_tabs( $tabs ) {
		$tabs[ 'wptravelengine' ] = 'WP Travel Engine';

		return $tabs;
	}

	public function install_plugins_wptravelengine() {
		wp_enqueue_style( 'wptravelengine-pro_plugin-install' );
		wp_enqueue_script( 'wptravelengine-pro_plugin-install' );
		wptravelengine_pro_view( 'admin/plugin-install-wptravelengine' );
	}

	public static function plugins_api( ...$args ) {

		if ( 'wptravelengine' === ( $args[ 2 ]->browse ?? '' ) ) {
			return static::query_plugins( ...$args );
		}

		if ( 'plugin_information' === $args[ 1 ] ) {

			$slug = $args[ 2 ]->slug;

			$plugin = static::is_store_plugin( $slug );

			if ( ! $plugin ) {
				return $args[ 0 ];
			}

			if ( ! defined( 'WP_TRAVEL_ENGINE_STORE_URL' ) ) {
				return new \WP_Error( 'missing_constant', __( 'Store URL constant is not defined.', 'wptravelengine-pro' ) );
			}

			// Check transient cache first.
			$transient_key = "wptravelengine_pro_plugin_info_{$slug}";
			$cached_response = get_transient( $transient_key );

			if ( $cached_response !== false ) {
				return $cached_response;
			}

			try {
				$plugin_api = wptravelengine_pro_plugin_api_response( 
					$plugin->id, 
					WP_TRAVEL_ENGINE_STORE_URL,
					array(
						'slug' => $slug,
						'license' => $plugin->license_key,
						'item_id' => $plugin->id
					)
				);

				if ( ! $plugin_api || ! method_exists( $plugin_api, 'get_plugin_information' ) ) {
					return new \WP_Error( 'api_error', __( 'Failed to initialize plugin API.', 'wptravelengine-pro' ) );
				}

				$edd_data = $plugin_api->get_plugin_information();

				// Check if API response is valid
				if ( is_wp_error( $edd_data ) || ! $edd_data ) {
					return new \WP_Error( 'api_error', __( 'Failed to fetch plugin information from API.', 'wptravelengine-pro' ) );
				}

				// Safely access sections and changelog
				$sections  = $edd_data->sections ?? new stdClass();
				$changelog = '';

				if ( is_object( $sections ) && isset( $sections->changelog ) ) {
					$changelog = $sections->changelog;
				} elseif ( is_array( $sections ) && isset( $sections['changelog'] ) ) {
					$changelog = $sections['changelog'];
				}

				// Format the response with dynamic data and proper null checks
				$response                 = new stdClass();
				$response->name           = $edd_data->name ?? $plugin->name ?? $slug;
				$response->version        = $edd_data->new_version ?? $edd_data->version ?? '1.0.0';
				$response->item_id        = $plugin->id;
				$response->slug           = $slug;
				$response->license        = $plugin->license_key ?? '';
				$response->author         = 'WP Travel Engine';
				$response->author_profile = 'https://wptravelengine.com/';

				$last_updated = static::get_last_updated_from_changelog( $changelog );
				if ( empty( $last_updated ) ) {
					$last_updated = $edd_data->last_updated ?? gmdate( 'Y-m-d' );
				}
				$response->last_updated = $last_updated;

				// Fix string concatenation issue
				$homepage = $edd_data->homepage ?? '';
				if ( ! empty( $homepage ) ) {
					// Ensure homepage starts with /
					$homepage = '/' . ltrim( $homepage, '/' );
					$response->homepage = "https://wptravelengine.com{$homepage}";
				} else {
					$response->homepage = $plugin->homepage ?? 'https://wptravelengine.com';
				}

				$response->download_link = $edd_data->download_link ?? '';

				$response->sections = [
					'changelog' => $changelog,
				];

				// Store in transient for 48 hours.
				set_transient( $transient_key, $response, 48 * HOUR_IN_SECONDS );

				return $response;
			} catch ( \Exception $e ) {
				return new \WP_Error( 'exception', __( 'An error occurred while fetching plugin information.', 'wptravelengine-pro' ) );
			}
		}

		return $args[0];
	}

	/**
	 * Get last updated date from changelog content
	 * @param string $changelog
	 * @return string
	 */
	private static function get_last_updated_from_changelog( $changelog ): string {
		if ( empty( $changelog ) ) {
			return gmdate( 'Y-m-d' ); // Return current date as fallback
		}

		// Look for the first version with date in format "Version X.X.X – XXth Month YYYY"
		if ( preg_match( '/Version\s+[0-9.]+\s*[–-]\s*([^\n\r]+)/i', $changelog, $matches ) ) {
			$date_string = trim( $matches[1] );

			// Remove ordinal suffixes (st, nd, rd, th) from the date string
			$date_string = preg_replace( '/(\d+)(st|nd|rd|th)/', '$1', $date_string );

			// Clean the string of any invisible characters and extra whitespace
			$date_string = preg_replace( '/[\x00-\x1F\x7F-\x9F]/', '', $date_string );
			$date_string = trim( $date_string );

			// Try multiple date parsing approaches
			$parsed_date = false;

			$parsed_date = strtotime( $date_string );

			if ( false === $parsed_date ) {
				if ( ! preg_match( '/\d{4}/', $date_string ) ) {
					$date_with_year = $date_string . ' ' . gmdate( 'Y' );
					$parsed_date    = strtotime( $date_with_year );
				}
			}

			if ( false === $parsed_date ) {
				if ( preg_match( '/(\d+)\s+(\w+)\s+(\d{4})/', $date_string, $date_parts ) ) {
					$day   = absint( $date_parts[1] );
					$month = $date_parts[2];
					$year  = absint( $date_parts[3] );

					// Convert month name to number
					$month_timestamp = strtotime( $month );
					if ( false !== $month_timestamp ) {
						$month_num = (int) gmdate( 'n', $month_timestamp );
						if ( $month_num ) {
							$parsed_date = mktime( 0, 0, 0, $month_num, $day, $year );
						}
					}
				}
			}

			if ( false !== $parsed_date ) {
				return gmdate( 'Y-m-d', $parsed_date );
			}
		}

		return gmdate( 'Y-m-d' ); // Return current date as fallback
	}

	/**
	 * Format changelog for display.
	 * @param string $changelog
	 * @return string
	 * @since 1.0.10
	 */
	private static function format_changelog( $changelog ): string {
		if ( empty( $changelog ) ) {
			return '<p>No changelog available.</p>';
		}

		// If it's already HTML, return as is
		if ( false !== strpos( $changelog, '<' ) ) {
			return $changelog;
		}

		// Convert plain text to HTML
		$changelog = nl2br( esc_html( $changelog ) );

		// Convert version headers to proper HTML
		$changelog = preg_replace( '/Version\s+([0-9.]+)\s*–\s*([^<]+)/', '<h4>Version $1 – $2</h4>', $changelog );

		return $changelog;
	}

	/**
	 * @param mixed ...$args
	 *
	 * @return stdClass
	 */
	public static function query_plugins( ...$args ): stdClass {

		$response = new stdClass();

		$query_args = $args[ 2 ] ?? array();

		$response->plugins = static::get_store_plugins( 'utility,add-ons,pro', 'api-response', (array) $query_args );

		$response->info[ 'results' ] = count( $response->plugins );

		return $response;
	}

	public static function is_store_plugin( $slug ) {
		$plugins = static::get_store_plugins( 'pro,utility,add-ons', 'api-response' );

		foreach ( $plugins as $plugin ) {
			if ( $plugin->slug === $slug ) {
				return $plugin;
			}
		}

		return false;
	}

	public static function get_store_plugins( $type = 'add-ons', $format = '', array $query_args = array() ) {
		$store = new Store();

		$plugins = $store->get_products( $type, array(
			'number' => $query_args[ 'per_page' ] ?? -1,
			'page'   => $query_args[ 'paged' ] ?? 1,
		) )->products ?? [];

		if ( $format == 'api-response' ) {
			$plugins = array_map( [ static::class, 'format_item' ], $plugins );
		}

		return $plugins;
	}

	public static function format_item( $item ): stdClass {
		$_item = new stdClass();

		$slug = Slug::$map[ $item->info->slug ] ?? false;
		if ( ! $slug ) {
			$slug = str_replace( 'wptravelengine-', '', $item->info->slug );
			$slug = "wptravelengine-$slug";
		}

		$_item->id                = $item->info->id;
		$_item->name              = $item->info->title;
		$_item->slug              = $slug;
		$_item->version           = $item->licensing->version ?? '';
		$_item->author            = $item->info->author ?? 'WP Travel Engine';
		$_item->requires_php      = $item->info->requires_php ?? '7.4';
		$_item->requires_plugins  = $item->info->requires_plugins ?? [];
		$_item->tested            = $item->info->tested ?? '6.7';
		$_item->rating            = $item->info->rating ?? 0;
		$_item->ratings           = $item->info->ratings ?? [];
		$_item->short_description = $item->info->excerpt ?? '';
		$_item->description       = $item->info->content ?? '';
		$_item->licensing         = $item->info->licensing ?? [];
		$_item->pricing           = $item->info->pricing ?? [];
		$_item->thumbnail         = $item->info->thumbnail ?? '';
		$_item->tags              = $item->info->tags ?? [];
		$_item->price             = $item->info->price ?? '';
		$_item->category          = $item->info->category ?? '';
		$_item->image             = $item->info->image ?? '';
		$_item->license_key       = wptravelengine_pro_get_saved_license_key( $slug );

		if ( defined( 'WP_TRAVEL_ENGINE_STORE_URL' ) ) {
			$permalink        = trim( $item->info->permalink ?? '', '/' );
			$_item->homepage  = trailingslashit( WP_TRAVEL_ENGINE_STORE_URL ) . $permalink;
			$_item->homepage  = add_query_arg(
				[
					'utm_source'   => 'free_plugin',
					'utm_medium'   => 'pro_addon',
					'utm_campaign' => 'upgrade_to_pro',
				],
				$_item->homepage
			);
		} else {
			$_item->homepage = 'https://wptravelengine.com';
		}

		$_item->download_link = wptravelengine_pro_get_extensions_version( $item->info->slug )->download_link ?? '';
		$_item->package       = $_item->download_link; // WordPress expects 'package' property for install URLs

		return $_item;
	}

}
