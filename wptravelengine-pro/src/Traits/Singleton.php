<?php
/**
 * Singleton trait.
 *
 * @package WPTravelEnginePro
 * @since 1.0.0
 */

namespace WPTravelEnginePro\Traits;

/**
 * Singleton trait.
 *
 * @package WPTravelEnginePro
 * @since 1.0.0
 */
trait Singleton {

	protected static ?object $instance = null;

	/**
	 * Get the instance of the class.
	 *
	 * @return $this
	 */
	public static function instance(): object {
		if ( is_null( static::$instance ) ) {
			static::$instance = new static();
		}

		return static::$instance;
	}
}
