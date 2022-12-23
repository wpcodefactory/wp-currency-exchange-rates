<?php
/**
 * Currency Exchange Rates - Crons Class
 *
 * @version 1.1.0
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_Currency_Exchange_Rates_Crons' ) ) :

class Alg_Currency_Exchange_Rates_Crons {

	/**
	 * Constructor.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 *
	 * @todo    [next] (dev) `wp_unschedule_event` on plugin deactivation
	 * @todo    [maybe] (dev) remove `schedule_the_events` on `admin_init`
	 */
	function __construct() {
		add_action( 'init',                          array( $this, 'schedule_the_events' ) );
		add_action( 'admin_init',                    array( $this, 'schedule_the_events' ) );
		add_action( 'alg_cer_update_exchange_rates', array( $this, 'update_exchange_rates' ) );
	}

	/**
	 * schedule_the_events.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function schedule_the_events() {
		$event_hook        = 'alg_cer_update_exchange_rates';
		$selected_interval = get_option( 'alg_cer_update_period', 'daily' );
		$update_intervals  = array( 'hourly', 'twicedaily', 'daily' );
		foreach ( $update_intervals as $interval ) {
			$event_timestamp = wp_next_scheduled( $event_hook, array( $interval ) );
			if ( $selected_interval === $interval ) {
				$cron_data = get_option( 'alg_cer_cron_data', array() );
				$cron_data['next_scheduled'] = $event_timestamp;
				update_option( 'alg_cer_cron_data', $cron_data );
			}
			if ( ! $event_timestamp && $selected_interval === $interval ) {
				wp_schedule_event( time(), $selected_interval, $event_hook, array( $selected_interval ) );
			} elseif ( $event_timestamp && $selected_interval !== $interval ) {
				wp_unschedule_event( $event_timestamp, $event_hook, array( $interval ) );
			}
		}
	}

	/**
	 * update_exchange_rates.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 */
	function update_exchange_rates( $interval ) {

		$cron_data = get_option( 'alg_cer_cron_data', array() );
		$cron_data['last_run'] = time();
		$cron_data['server']   = get_option( 'alg_cer_server', 'fixer' );
		update_option( 'alg_cer_cron_data', $cron_data );

		$base_currency = get_woocommerce_currency();
		$currencies    = get_woocommerce_currencies();
		$rates         = get_option( 'alg_cer_rates', array() );
		foreach ( $currencies as $currency_code => $currency_name ) {
			if ( false !== ( $rate = alg_cer_get_exchange_rate( $base_currency, $currency_code ) ) ) {
				$rates[ $base_currency ][ $currency_code ] = $rate;
			}
		}
		update_option( 'alg_cer_rates', $rates );

	}

}

endif;

return new Alg_Currency_Exchange_Rates_Crons();
