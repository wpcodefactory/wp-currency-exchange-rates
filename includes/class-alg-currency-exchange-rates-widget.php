<?php
/**
 * Currency Exchange Rates - WP Widget
 *
 * @version 1.1.0
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Alg_Currency_Exchange_Rates_WP_Widget' ) ) :

class Alg_Currency_Exchange_Rates_WP_Widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function __construct() {
		$widget_ops = array(
			'classname'   => 'alg_currency_exchange_rates_wp_widget',
			'description' => __( 'Add currency exchange rates table to your site.', 'wp-currency-exchange-rates' ),
		);
		parent::__construct( $widget_ops['classname'], __( 'Currency Exchange Rates', 'wp-currency-exchange-rates' ), $widget_ops );
	}

	/**
	 * Outputs the content of the widget.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 *
	 * @param   array $args
	 * @param   array $instance
	 */
	function widget( $args, $instance ) {
		$html = '';
		$html .= $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			$html .= $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}
		if ( ! empty( $instance['pairs'] ) ) {
			$do_use_saved = ( isset( $instance['use_saved'] ) && 'yes' === $instance['use_saved'] );
			$pairs = str_replace( ' ', '', strtoupper( trim( $instance['pairs'] ) ) );
			$pairs = explode( ',', $pairs );
			if ( ! empty( $pairs ) ) {
				foreach ( $pairs as $pair ) {
					$func  = ( $do_use_saved ? 'alg_cer_get_saved_exchange_rate' : 'alg_cer_get_exchange_rate' );
					$html .= $pair . ' ' . $func( substr( $pair, 0, 3 ), substr( $pair, 3 ) ) . '<br>';
				}
			}
		}
		$html .= $args['after_widget'];
		echo $html;
	}

	/**
	 * get_widget_option_fields.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 *
	 * @todo    [maybe] (dev) more styling options (table/simple; class/style; labels)
	 * @todo    [maybe] (dev) option to get saved (i.e. not live) rates
	 */
	function get_widget_option_fields() {
		return array(
			'title' => array(
				'title'   => __( 'Title', 'wp-currency-exchange-rates' ),
				'default' => '',
			),
			'pairs' => array(
				'title'   => __( 'Currency pairs (comma separated list)', 'wp-currency-exchange-rates' ) . '<br>' .
					sprintf( __( 'E.g.: %s', 'wp-currency-exchange-rates' ), '<code>EURUSD,EURGBP,USDGBP</code>' ),
				'default' => '',
			),
			'use_saved' => array(
				'title'   => __( 'Use saved rates', 'wp-currency-exchange-rates' ),
				'default' => 'no',
				'type'    => 'select',
				'options' => array(
					'no'  => __( 'No', 'wp-currency-exchange-rates' ),
					'yes' => __( 'Yes', 'wp-currency-exchange-rates' ),
				),
			),
		);
	}

	/**
	 * Outputs the options form on admin.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 *
	 * @param   array $instance The widget options
	 */
	function form( $instance ) {
		$html = '';
		foreach ( $this->get_widget_option_fields() as $id => $widget_option_field ) {
			$value = ( ! empty( $instance[ $id ] ) ? $instance[ $id ] : $widget_option_field['default'] );
			$label = sprintf( '<label for="%s">%s</label>', $this->get_field_id( $id ), $widget_option_field['title'] );
			if ( ! isset( $widget_option_field['type'] ) ) {
				$widget_option_field['type'] = 'text';
			}
			switch ( $widget_option_field['type'] ) {
				case 'select':
					$options = '';
					foreach ( $widget_option_field['options'] as $option_id => $option_title ) {
						$options .= sprintf( '<option value="%s"%s>%s</option>', $option_id, selected( $option_id, $value, false ), $option_title );
					}
					$field = sprintf( '<select class="widefat" id="%s" name="%s">%s</select>',
						$this->get_field_id( $id ), $this->get_field_name( $id ), $options );
					break;
				default: // 'text'
					$field = sprintf( '<input class="widefat" id="%s" name="%s" type="text" value="%s">',
						$this->get_field_id( $id ), $this->get_field_name( $id ), esc_attr( $value ) );
			}
			$html .= '<p>' . $label . $field . '</p>';
		}
		echo $html;
	}

	/**
	 * Processing widget options on save.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 *
	 * @param   array $new_instance The new options
	 * @param   array $old_instance The previous options
	 */
	function update( $new_instance, $old_instance ) {
		foreach ( $this->get_widget_option_fields() as $id => $widget_option_field ) {
			if ( empty( $new_instance[ $id ] ) ) {
				$new_instance[ $id ] = $widget_option_field['default'];
			}
		}
		return $new_instance;
	}
}

endif;

if ( ! function_exists( 'register_alg_currency_exchange_rates_wp_widget' ) ) {
	/**
	 * register Alg_Currency_Exchange_Rates_WP_Widget widget.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function register_alg_currency_exchange_rates_wp_widget() {
		register_widget( 'Alg_Currency_Exchange_Rates_WP_Widget' );
	}
}

add_action( 'widgets_init', 'register_alg_currency_exchange_rates_wp_widget' );
