<?php
/**
 * Currency Exchange Rates - Core
 *
 * @version 1.0.0
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_Currency_Exchange_Rates_Core' ) ) :

class Alg_Currency_Exchange_Rates_Core {

	/**
	 * Constructor.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function __construct() {
		require_once( 'alg-currency-exchange-rates-functions.php' );
		require_once( 'alg-currency-exchange-rates-shortcodes.php' );
		require_once( 'class-alg-currency-exchange-rates-admin-settings.php' );
		require_once( 'class-alg-currency-exchange-rates-crons.php' );
		require_once( 'class-alg-currency-exchange-rates-widget.php' );
	}

}

endif;

return new Alg_Currency_Exchange_Rates_Core();
