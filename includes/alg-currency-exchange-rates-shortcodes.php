<?php
/**
 * Currency Exchange Rates - Shortcodes
 *
 * @version 1.1.0
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

add_shortcode( 'alg_cer_get_exchange_rate',       'alg_cer_get_exchange_rate_shortcode' );
add_shortcode( 'alg_cer_get_saved_exchange_rate', 'alg_cer_get_saved_exchange_rate_shortcode' );

if ( ! function_exists( 'alg_cer_get_exchange_rate_shortcode' ) ) {
	/**
	 * alg_cer_get_exchange_rate_shortcode.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function alg_cer_get_exchange_rate_shortcode( $atts ) {
		$atts = shortcode_atts( array(
			'from'          => get_woocommerce_currency(),
			'to'            => get_woocommerce_currency(),
			'max_precision' => 6,
		), $atts, 'alg_cer_get_exchange_rate' );
		return alg_cer_get_exchange_rate( $atts['from'], $atts['to'], $atts['max_precision'] );
	}
}

if ( ! function_exists( 'alg_cer_get_saved_exchange_rate_shortcode' ) ) {
	/**
	 * alg_cer_get_saved_exchange_rate_shortcode.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 */
	function alg_cer_get_saved_exchange_rate_shortcode( $atts ) {
		$atts = shortcode_atts( array(
			'from'          => get_woocommerce_currency(),
			'to'            => get_woocommerce_currency(),
			'max_precision' => 6,
		), $atts, 'alg_cer_get_saved_exchange_rate' );
		return alg_cer_get_saved_exchange_rate( $atts['from'], $atts['to'], $atts['max_precision'] );
	}
}
