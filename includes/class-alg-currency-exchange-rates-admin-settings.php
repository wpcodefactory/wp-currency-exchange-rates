<?php
/**
 * Currency Exchange Rates - Admin Settings Class
 *
 * @version 1.2.0
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_Currency_Exchange_Rates_Admin_Settings' ) ) :

class Alg_Currency_Exchange_Rates_Admin_Settings {

	/**
	 * Constructor.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function __construct() {
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'handle_actions' ) );
	}

	/**
	 * add_admin_menu.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function add_admin_menu() {
		add_options_page(
			__( 'Currency Exchange Rates', 'wp-currency-exchange-rates' ),
			__( 'Currency Exchange Rates', 'wp-currency-exchange-rates' ),
			'manage_options',
			'wp-currency-exchange-rates',
			array( $this, 'output_admin_menu' )
		);
	}

	/**
	 * get_table_html.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function get_table_html( $data, $args = array() ) {
		$defaults = array(
			'table_class'        => '',
			'table_style'        => '',
			'row_styles'         => '',
			'table_heading_type' => 'horizontal',
			'columns_classes'    => array(),
			'columns_styles'     => array(),
		);
		$args = array_merge( $defaults, $args );
		extract( $args );
		$table_class = ( '' == $table_class ) ? '' : ' class="' . $table_class . '"';
		$table_style = ( '' == $table_style ) ? '' : ' style="' . $table_style . '"';
		$row_styles  = ( '' == $row_styles )  ? '' : ' style="' . $row_styles  . '"';
		$html = '';
		$html .= '<table' . $table_class . $table_style . '>';
		$html .= '<tbody>';
		foreach( $data as $row_number => $row ) {
			$html .= '<tr' . $row_styles . '>';
			foreach( $row as $column_number => $value ) {
				$th_or_td = ( ( 0 === $row_number && 'horizontal' === $table_heading_type ) || ( 0 === $column_number && 'vertical' === $table_heading_type ) ) ? 'th' : 'td';
				$column_class = ( ! empty( $columns_classes ) && isset( $columns_classes[ $column_number ] ) ) ? ' class="' . $columns_classes[ $column_number ] . '"' : '';
				$column_style = ( ! empty( $columns_styles ) && isset( $columns_styles[ $column_number ] ) ) ? ' style="' . $columns_styles[ $column_number ] . '"' : '';

				$html .= '<' . $th_or_td . $column_class . $column_style . '>';
				$html .= $value;
				$html .= '</' . $th_or_td . '>';
			}
			$html .= '</tr>';
		}
		$html .= '</tbody>';
		$html .= '</table>';
		return $html;
	}

	/**
	 * handle_actions.
	 *
	 * @version 1.2.0
	 * @since   1.0.0
	 */
	function handle_actions() {
		if ( isset( $_POST['alg_cer_save_options'] ) ) {
			foreach ( $this->get_options() as $option ) {
				if ( isset( $_POST[ $option['id'] ] ) ) {
					update_option( $option['id'], $_POST[ $option['id'] ] );
				}
			}
			add_action( 'admin_notices', array( $this, 'admin_notice_options_saved' ) );
		}
		if ( isset( $_POST['alg_cer_delete_rates'] ) ) {
			delete_option( 'alg_cer_rates' );
			$cron_data = get_option( 'alg_cer_cron_data', array() );
			unset( $cron_data['last_run'] );
			unset( $cron_data['server'] );
			update_option( 'alg_cer_cron_data', $cron_data );
			add_action( 'admin_notices', array( $this, 'admin_notice_rates_deleted' ) );
		}
		if ( isset( $_POST['alg_cer_update_rates'] ) ) {
			do_action( 'alg_cer_update_exchange_rates' );
			add_action( 'admin_notices', array( $this, 'admin_notice_rates_updated' ) );
		}
	}

	/**
	 * admin_notice_options_saved.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function admin_notice_options_saved() {
		echo '<div class="notice notice-success is-dismissible">' .
			'<p>' . '<strong>' . __( 'Your settings have been saved.', 'wp-currency-exchange-rates' ) . '</strong>' . '</p>' . '</div>';
	}

	/**
	 * admin_notice_rates_deleted.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function admin_notice_rates_deleted() {
		echo '<div class="notice notice-success is-dismissible">' .
			'<p>' . '<strong>' . __( 'Rates have been deleted.', 'wp-currency-exchange-rates' ) . '</strong>' . '</p>' . '</div>';
	}

	/**
	 * admin_notice_rates_updated
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function admin_notice_rates_updated() {
		echo '<div class="notice notice-success is-dismissible">' .
			'<p>' . '<strong>' . __( 'Rates have been updated.', 'wp-currency-exchange-rates' ) . '</strong>' . '</p>' . '</div>';
	}

	/**
	 * get_api_key_desc.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 */
	function get_api_key_desc( $server ) {
		switch ( $server ) {
			default: // 'fixer'
				return sprintf( __( 'Get your free API key from %s.', 'wp-currency-exchange-rates' ),
					'<a target="_blank" href="https://fixer.io/product">Fixer.io</a>' );
		}
	}

	/**
	 * get_options.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 */
	function get_options() {
		return array(
			array(
				'title'    => __( 'Exchange rates server', 'wp-currency-exchange-rates' ),
				'id'       => 'alg_cer_server',
				'default'  => 'fixer',
				'type'     => 'select',
				'options'  => alg_cer_get_currency_exchange_rate_servers(),
				'class'    => 'widefat',
			),
			array(
				'title'    => __( 'API key', 'wp-currency-exchange-rates' ) . ' [' . get_option( 'alg_cer_server', 'fixer' ) . ']',
				'desc'     => $this->get_api_key_desc( get_option( 'alg_cer_server', 'fixer' ) ),
				'id'       => 'alg_cer_server_api_key_' . get_option( 'alg_cer_server', 'fixer' ),
				'default'  => '',
				'type'     => 'text',
				'class'    => 'widefat',
			),
			array(
				'title'    => __( 'Update period', 'wp-currency-exchange-rates' ),
				'id'       => 'alg_cer_update_period',
				'default'  => 'daily',
				'type'     => 'select',
				'options'  => array(
					'hourly'     => __( 'Update hourly', 'wp-currency-exchange-rates' ),
					'twicedaily' => __( 'Update twice daily', 'wp-currency-exchange-rates' ),
					'daily'      => __( 'Update daily', 'wp-currency-exchange-rates' ),
				),
				'class'    => 'widefat',
			),
		);
	}

	/**
	 * get_options_table.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 */
	function get_options_table() {
		$table_data = array();
		foreach ( $this->get_options() as $option ) {
			$option_html = '';
			$value = get_option( $option['id'], $option['default'] );
			switch ( $option['type'] ) {
				case 'select':
					$option_html .= '<select name="' . $option['id'] . '" class="' . $option['class'] . '">';
					foreach ( $option['options'] as $option_id => $option_name ) {
						$option_html .= '<option value="' . $option_id . '"' . selected( $value, $option_id, false ) . '>';
						$option_html .= $option_name;
						$option_html .= '</option>';
					}
					$option_html .= '</select>';
					break;
				default:
					$option_html .= '<input type="' . $option['type'] . '" name="' . $option['id'] . '" id="' . $option['id'] . '" class="' . $option['class'] . '" value="' . $value . '">';
			}
			if ( isset( $option['desc'] ) ) {
				$option_html .= '<p><em>' . $option['desc'] . '</em></p>';
			}
			$table_data[] = array( $option['title'], $option_html );
		}
		$table_data[] = array(
			'<input type="submit" name="alg_cer_save_options" class="button-primary" value="' .
				__( 'Save changes', 'wp-currency-exchange-rates' ) . '">',
			''
		);
		return '<form method="post" action="">' .
			$this->get_table_html( $table_data, array( 'table_class' => 'widefat striped', 'table_heading_type' => 'vertical' ) ) . '</form>';
	}

	/**
	 * get_rates_actions_form.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function get_rates_actions_form( $add_delete_button ) {
		return '<form method="post" action="">' .
			'<p>' .
				( $add_delete_button ? '<input type="submit" name="alg_cer_delete_rates" class="button-primary" value="' .
					__( 'Delete saved rates', 'wp-currency-exchange-rates' ) . '">' . ' ' : '' ) .
				'<input type="submit" name="alg_cer_update_rates" class="button-primary" value="' .
					__( 'Update rates now', 'wp-currency-exchange-rates' ) . '">' .
			'</p>' .
		'</form>';
	}

	/**
	 * get_rates_table.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function get_rates_table() {
		$rates = get_option( 'alg_cer_rates', array() );
		if ( ! empty( $rates ) ) {
			$table_data   = array();
			$table_data[] = array( '', __( 'Pair', 'wp-currency-exchange-rates' ), __( 'Rate', 'wp-currency-exchange-rates' ) );
			$counter = 1;
			foreach ( $rates as $base_currency => $base_currency_rates ) {
				foreach ( $base_currency_rates as $currency_code => $rate ) {
					$table_data[] = array( $counter++, $base_currency . $currency_code, $rate );
				}
			}
			return $this->get_rates_actions_form( true ) .
				$this->get_table_html( $table_data, array( 'table_class' => 'widefat striped' ) );
				$html;
		} else {
			return $this->get_rates_actions_form( false ) .
				'<p style="font-style:italic;">' . __( 'No saved rates yet.', 'wp-currency-exchange-rates' ) . '</p>';
		}
	}

	/**
	 * get_cron_data.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function get_cron_data() {
		$html = '';
		$cron_data = get_option( 'alg_cer_cron_data', array() );
		if ( ! empty( $cron_data ) ) {
			if ( isset( $cron_data['next_scheduled'] ) && '' != $cron_data['next_scheduled'] ) {
				$html .= '<p style="font-style:italic;">' . sprintf(
					__( 'Next update scheduled at %s (in %s).', 'wp-currency-exchange-rates' ),
						'<code>' . date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $cron_data['next_scheduled'] ) . '</code>',
						'<code>' . human_time_diff( $cron_data['next_scheduled'] ) . '</code>'
				) . '</p>';
			}
			if ( isset( $cron_data['last_run'] ) && '' != $cron_data['last_run'] && isset( $cron_data['server'] ) && '' != $cron_data['server'] ) {
				$servers = alg_cer_get_currency_exchange_rate_servers();
				$server  = ( isset( $servers[ $cron_data['server'] ] ) ? $servers[ $cron_data['server'] ] : $cron_data['server'] );
				$html .= '<p style="font-style:italic;">' . sprintf(
					__( 'Last update at %s (%s ago) from %s server.', 'wp-currency-exchange-rates' ),
						'<code>' . date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $cron_data['last_run'] ) . '</code>',
						'<code>' . human_time_diff( $cron_data['last_run'] ) . '</code>',
						'<code>' . $server . '</code>'
				) . '</p>';
			}
		}
		return $html;
	}

	/**
	 * output_admin_menu.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function output_admin_menu() {
		$html = '';
		$html .= '<div class="wrap">';
		$html .= '<h1>' . __( 'Currency Exchange Rates', 'wp-currency-exchange-rates' ) . '</h1>';
		$html .= '<h2>' . __( 'Options', 'wp-currency-exchange-rates' ) . '</h2>';
		$html .= $this->get_options_table();
		$html .= '<h2>' . __( 'Saved Rates', 'wp-currency-exchange-rates' ) . '</h2>';
		$html .= $this->get_cron_data();
		$html .= $this->get_rates_table();
		$html .= '</div>';
		echo $html;
	}

}

endif;

return new Alg_Currency_Exchange_Rates_Admin_Settings();
