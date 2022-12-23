<?php
/*
Plugin Name: WP Currency Exchange Rates
Plugin URI: https://wpfactory.com/
Description: Currency exchange rates for WordPress.
Version: 1.2.0
Author: WPFactory
Author URI: https://wpfactory.com
Text Domain: wp-currency-exchange-rates
Domain Path: /langs
*/

defined( 'ABSPATH' ) || exit;

defined( 'ALG_WP_CURRENCY_EXCHANGE_RATES_VERSION' ) || define( 'ALG_WP_CURRENCY_EXCHANGE_RATES_VERSION', '1.2.0' );

defined( 'ALG_WP_CURRENCY_EXCHANGE_RATES_FILE' ) || define( 'ALG_WP_CURRENCY_EXCHANGE_RATES_FILE', __FILE__ );

require_once( 'includes/class-alg-currency-exchange-rates.php' );

if ( ! function_exists( 'alg_currency_exchange_rates' ) ) {
	/**
	 * Returns the main instance of Alg_Currency_Exchange_Rates to prevent the need to use globals.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function alg_currency_exchange_rates() {
		return Alg_Currency_Exchange_Rates::instance();
	}
}

add_action( 'plugins_loaded', 'alg_currency_exchange_rates' );
