<?php
/**
 * Admin Page Interface.
 */

namespace WPTravelEnginePro\Interfaces;

/**
 * Admin Page Interface.
 */
interface AdminPage {

	/**
	 * Get the parent slug.
	 *
	 * @return string
	 */
	public function get_parent_slug(): string;

	/**
	 * Get the page title.
	 *
	 * @return string
	 */
	public function get_page_title(): string;

	/**
	 * Get the menu title.
	 *
	 * @return string
	 */
	public function get_menu_title(): string;

	/**
	 * Get the capability.
	 *
	 * @return string
	 */
	public function get_capability(): string;

	/**
	 * Get the menu slug.
	 *
	 * @return string
	 */
	public function get_menu_slug(): string;

	/**
	 * Callback function.
	 *
	 * @return void
	 */
	public function callback(): void;
}
