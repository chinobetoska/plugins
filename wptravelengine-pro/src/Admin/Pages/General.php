<?php
/**
 * General settings page.
 */

namespace WPTravelEnginePro\Admin\Pages;

use WPTravelEnginePro\Interfaces\AdminPage;

class General implements AdminPage {

	public string $parent_slug = 'edit.php?post_type=booking';

	public string $page_title = 'WP Travel Engine Pro';

	public string $menu_title = 'Pro';

	public string $capability = 'manage_options';

	public string $menu_slug = 'wptravelengine-pro';

	/**
	 * @inheritDoc
	 */
	public function get_parent_slug(): string {
		return $this->parent_slug;
	}

	/**
	 * @inheritDoc
	 */
	public function get_page_title(): string {
		return $this->page_title;
	}

	/**
	 * @inheritDoc
	 */
	public function get_menu_title(): string {
		return $this->menu_title;
	}

	/**
	 * @inheritDoc
	 */
	public function get_capability(): string {
		return $this->capability;
	}

	/**
	 * @inheritDoc
	 */
	public function get_menu_slug(): string {
		return $this->menu_slug;
	}

	/**
	 * @inheritDoc
	 */
	public function callback(): void {
		$extensions = wptravelengine_pro_get_extensions();
		wptravelengine_pro_view( 'pages/wptravelengine-pro', compact( 'extensions' ) );
	}

}
