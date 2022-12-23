<?php
/**
 * Currency Exchange Rates - Main Class
 *
 * @version 1.2.0
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_Currency_Exchange_Rates' ) ) :

final class Alg_Currency_Exchange_Rates {

	/**
	 * Plugin version.
	 *
	 * @var   string
	 * @since 1.0.0
	 */
	public $version = ALG_WP_CURRENCY_EXCHANGE_RATES_VERSION;

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
	 *
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
	 * @version 1.2.0
	 * @since   1.0.0
	 *
	 * @access  public
	 */
	function __construct() {

		// Set up localisation
		add_action( 'init', array( $this, 'localize' ) );

		// Include required files
		$this->includes();

		// Admin
		if ( is_admin() ) {
			$this->admin();
		}

	}

	/**
	 * localize.
	 *
	 * @version 1.2.0
	 * @since   1.2.0
	 */
	function localize() {
		load_plugin_textdomain( 'wp-currency-exchange-rates', false, dirname( plugin_basename( ALG_WP_CURRENCY_EXCHANGE_RATES_FILE ) ) . '/langs/' );
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 *
	 * @version 1.2.0
	 * @since   1.0.0
	 */
	function includes() {
		// Core
		$this->core = require_once( 'class-alg-currency-exchange-rates-core.php' );
	}

	/**
	 * admin.
	 *
	 * @version 1.2.0
	 * @since   1.1.0
	 */
	function admin() {
		// Action links
		add_filter( 'plugin_action_links_' . plugin_basename( ALG_WP_CURRENCY_EXCHANGE_RATES_FILE ), array( $this, 'action_links' ) );
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
	 *
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
	 * @version 1.2.0
	 * @since   1.0.0
	 *
	 * @return  string
	 */
	function plugin_url() {
		return untrailingslashit( plugin_dir_url( ALG_WP_CURRENCY_EXCHANGE_RATES_FILE ) );
	}

	/**
	 * Get the plugin path.
	 *
	 * @version 1.2.0
	 * @since   1.0.0
	 *
	 * @return  string
	 */
	function plugin_path() {
		return untrailingslashit( plugin_dir_path( ALG_WP_CURRENCY_EXCHANGE_RATES_FILE ) );
	}

	/**
	 * Get the plugin file.
	 *
	 * @version 1.2.0
	 * @since   1.0.0
	 */
	function plugin_file() {
		return ALG_WP_CURRENCY_EXCHANGE_RATES_FILE;
	}

}

endif;
