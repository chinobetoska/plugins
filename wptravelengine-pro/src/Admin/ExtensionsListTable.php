<?php
/**
 * Extension List Table.
 *
 * @since 1.0.0
 */

namespace WPTravelEnginePro\Admin;

use WP_Plugin_Install_List_Table;
use WPTravelEnginePro\License;
use WPTravelEnginePro\Slug;
use WPTravelEnginePro\Store;

/**
 * Extensions List Table.
 *
 * @since 1.0.0
 */
class ExtensionsListTable extends WP_Plugin_Install_List_Table {

	public function display_rows() {
		$search_category = isset( $_REQUEST['category'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['category'] ) ) : '';
		$search_category = $search_category === 'all' ? '' : $search_category;

		foreach ( $this->items as $item ) {
			if ( ! empty( $search_category ) ) {
				$item_category = array_column( $item->category ?? array(), 'slug', 'slug' );
				if ( ! isset( $item_category[ $search_category ] ) ) {
					continue;
				}
			}

			$this->single_row( $item );
		}
	}

	public function single_row( $item ) {

		$action_links = '';

		$plugins = get_plugins();

		$item->installed_version = false;
		foreach ( array_keys( $plugins ) as $_plugin ) {
			if ( strpos( $_plugin, $item->slug ) !== false ) {
				$plugin_data             = get_plugin_data( WP_PLUGIN_DIR . '/' . $_plugin );
				$item->installed_version = $plugin_data['Version'];
				break;
			}
		}

		if ( ! empty( $item->license_key ) && ! empty( $item->download_link ) ) {
			if ( ! isset( $item->package ) ) {
				$item->package = $item->download_link;
			}
			$action_links = wp_get_plugin_action_button( $item->name, $item, true, true );
		}

		if ( 'wptravelengine-pro' === $item->slug ) {
			$item->homepage = '';
		}

		$license        = new License( $item->license_key, $item->id, $item->slug );
		$status_message = '';
		if ( ! empty( $license->license() ) ) {
			$status_message = $license->status_message();
		}

		wptravelengine_pro_view(
			'admin/list-table/single-row',
			array(
				'item'           => $item,
				'action_links'   => $action_links,
				'license_status' => $license->get_status(),
				'message'        => $status_message,
				'error'          => $license->get_status() !== 'valid' ? $status_message : '',
			)
		);
	}

	public function display() {
		$search     = isset( $_REQUEST['search'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['search'] ) ) : '';
		$store      = new Store();
		$categories = $store->get_product_categories( 'edd-categories', array( 'parent' => 5 ) );
		$category   = isset( $_REQUEST['category'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['category'] ) ) : 'all';
		wptravelengine_pro_view( 'admin/list-table/header', compact( 'search', 'categories', 'category' ) );
		?>
		<div class="wp-list-table <?php echo implode( ' ', $this->get_table_classes() ); ?>">
			<?php
			$this->screen->render_screen_reader_content( 'heading_list' );
			?>
			<div id="the-list" class="wpte-addon__list">
				<?php $this->display_rows_or_placeholder(); ?>
			</div>
		</div>
		<?php
		$this->display_tablenav( 'bottom' );
	}
}
