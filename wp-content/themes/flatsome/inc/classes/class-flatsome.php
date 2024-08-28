<?php
/**
 * Flatsome class.
 *
 * @author  UX Themes
 * @package Flatsome
 * @since   3.18.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class Flatsome
 *
 * @package Flatsome
 */
final class Flatsome {

	/**
	 * Parent theme version.
	 *
	 * @var string
	 */
	private $version;

	/**
	 * The Flatsome_Theme_JSON instance.
	 *
	 * @var Flatsome_Theme_JSON
	 */
	private $theme_json;

	/**
	 * The single instance of the class.
	 *
	 * @var Flatsome
	 */
	protected static $instance = null;

	/**
	 * Flatsome constructor.
	 */
	private function __construct() {
		$this->version = wp_get_theme( get_template() )->get( 'Version' );

		$this->theme_json = new Flatsome_Theme_JSON();
	}

	/**
	 * Initialize Flatsome.
	 */
	public function init() {
		$this->theme_json->init();

		if ( is_woocommerce_activated() ) {
			Flatsome\WooCommerce\MiniCart::instance();
			Flatsome\WooCommerce\Shipping::instance();
		}
	}

	/**
	 * Get parent theme version.
	 *
	 * @return string
	 */
	public function version() {
		return $this->version;
	}

	/**
	 * Main instance.
	 *
	 * @return Flatsome
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}
