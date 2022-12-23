<?php
/**
 * Currency Exchange Rates - Functions
 *
 * @version 1.1.0
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'alg_cer_get_saved_exchange_rate' ) ) {
	/**
	 * alg_cer_get_saved_exchange_rate.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 */
	function alg_cer_get_saved_exchange_rate( $currency_from, $currency_to, $max_precision = 6 ) {
		$eur_currency_from = 1;
		if ( 'EUR' != $currency_from ) {
			if ( ! ( $eur_currency_from = alg_cer_get_saved_exchange_rate( 'EUR', $currency_from, $max_precision ) ) ) {
				return false;
			}
			$currency_from = 'EUR';
		}
		$rates = get_option( 'alg_cer_rates', array() );
		return ( isset( $rates[ $currency_from ][ $currency_to ] ) ? round( $rates[ $currency_from ][ $currency_to ] / $eur_currency_from, $max_precision ) : false );
	}
}

if ( ! function_exists( 'alg_cer_get_currency_exchange_rate_servers' ) ) {
	/**
	 * alg_cer_get_currency_exchange_rate_servers.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 */
	function alg_cer_get_currency_exchange_rate_servers() {
		return apply_filters( 'alg_cer_currency_exchange_rate_servers', array(
			'fixer' => 'Fixer.io',
		) );
	}
}

if ( ! function_exists( 'alg_cer_get_exchange_rate' ) ) {
	/*
	 * alg_cer_get_exchange_rate.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 *
	 * @todo    [maybe] (dev) calculate by invert
	 * @todo    [maybe] (dev) offsets
	 * @todo    [maybe] (dev) rounding (up/down)
	 */
	function alg_cer_get_exchange_rate( $currency_from, $currency_to, $max_precision = 6 ) {
		$exchange_rates_server = get_option( 'alg_cer_server', 'fixer' );
		if ( null === ( $return = apply_filters( 'alg_get_exchange_rate', null, $exchange_rates_server, $currency_from, $currency_to ) ) ) {
			switch ( $exchange_rates_server ) {
				default: // 'fixer'
					$return = alg_cer_fixer_get_exchange_rate( $currency_from, $currency_to );
			}
		}
		return ( false !== $return ? round( $return, $max_precision ) : false );
	}
}

if ( ! function_exists( 'alg_cer_download_url' ) ) {
	/*
	 * alg_cer_download_url.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 *
	 * @todo    [maybe] (dev) use `download_url()` function
	 */
	function alg_cer_download_url( $url ) {
		$response = false;
		if ( function_exists( 'curl_version' ) ) {
			$curl = curl_init( $url );
			curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
			$response = curl_exec( $curl );
			curl_close( $curl );
		} elseif ( ini_get( 'allow_url_fopen' ) ) {
			$response = file_get_contents( $url );
		}
		return $response;
	}
}

if ( ! function_exists( 'alg_cer_fixer_get_exchange_rate' ) ) {
	/*
	 * alg_cer_fixer_get_exchange_rate.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 *
	 * @todo    [next] (dev) get all rates at once (for "saved rates")
	 */
	function alg_cer_fixer_get_exchange_rate( $currency_from, $currency_to ) {
		$eur_currency_from = 1;
		if ( 'EUR' != $currency_from ) {
			if ( ! ( $eur_currency_from = alg_cer_fixer_get_exchange_rate( 'EUR', $currency_from ) ) ) {
				return false;
			}
			$currency_from = 'EUR';
		}
		$api_key = get_option( 'alg_cer_server_api_key_fixer', '' );
		if ( false !== ( $response = alg_cer_download_url( 'http://data.fixer.io/api/latest?access_key=' . $api_key . '&base=' . $currency_from . '&symbols=' . $currency_to ) ) ) {
			$exchange_rate = json_decode( $response );
		}
		return ( isset( $exchange_rate->rates->{$currency_to} ) ) ? floatval( $exchange_rate->rates->{$currency_to} / $eur_currency_from ) : false;
	}
}
