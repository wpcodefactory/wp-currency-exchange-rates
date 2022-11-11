<?php
/*
Plugin Name: WP Currency Exchange Rates
Plugin URI: https://wpfactory.com/
Description: Currency exchange rates for WordPress.
Version: 1.2.0-dev
Author: WPFactory
Author URI: https://wpfactory.com
Text Domain: wp-currency-exchange-rates
Domain Path: /langs
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_Currency_Exchange_Rates' ) ) :

/**
 * Main Alg_Currency_Exchange_Rates Class
 *
 * @class   Alg_Currency_Exchange_Rates
 * @version 1.1.0
 * @since   1.0.0
 */
final class Alg_Currency_Exchange_Rates {

	/**
	 * Plugin version.
	 *
	 * @var   string
	 * @since 1.0.0
	 */
	public $version = '1.2.0-dev-20221111-2227';

	/**
	 * @var   Alg_Currency_Exchange_Rates The single instance of the class
	 * @since 1.0.0
	 */
	protected static $_instance = null;

	/**
	 * Main Alg_Currency_Exchange_Rates Instance
	 *
	 * Ensures only one instance of Alg_Currency_Exchange_Rates is loaded or can be loaded.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @static
	 * @return  Alg_Currency_Exchange_Rates - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Alg_Currency_Exchange_Rates Constructor.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 * @access  public
	 */
	function __construct() {

		// Set up localisation
		load_plugin_textdomain( 'wp-currency-exchange-rates', false, dirname( plugin_basename( __FILE__ ) ) . '/langs/' );

		// Include required files
		$this->includes();

		// Admin
		if ( is_admin() ) {
			$this->admin();
		}
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 */
	function includes() {
		require_once( 'includes/class-alg-currency-exchange-rates-core.php' );
	}

	/**
	 * admin.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 */
	function admin() {
		// Action links
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'action_links' ) );
		// Version update
		if ( get_option( 'alg_currency_exchange_rates_version', '' ) !== $this->version ) {
			add_action( 'admin_init', array( $this, 'version_updated' ) );
		}
	}

	/**
	 * Show action links on the plugin screen.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @param   mixed $links
	 * @return  array
	 */
	function action_links( $links ) {
		$custom_links = array( '<a href="' . admin_url( 'options-general.php?page=wp-currency-exchange-rates' ) . '">' . __( 'Settings', 'wp-currency-exchange-rates' ) . '</a>' );
		return array_merge( $custom_links, $links );
	}

	/**
	 * version_updated.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 */
	function version_updated() {
		update_option( 'alg_currency_exchange_rates_version', $this->version );
	}

	/**
	 * Get the plugin url.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @return  string
	 */
	function plugin_url() {
		return untrailingslashit( plugin_dir_url( __FILE__ ) );
	}

	/**
	 * Get the plugin path.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @return  string
	 */
	function plugin_path() {
		return untrailingslashit( plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Get the plugin file.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function plugin_file() {
		return __FILE__;
	}

}

endif;

if ( ! function_exists( 'alg_currency_exchange_rates' ) ) {
	/**
	 * Returns the main instance of Alg_Currency_Exchange_Rates to prevent the need to use globals.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @return  Alg_Currency_Exchange_Rates
	 */
	function alg_currency_exchange_rates() {
		return Alg_Currency_Exchange_Rates::instance();
	}
}

alg_currency_exchange_rates();
