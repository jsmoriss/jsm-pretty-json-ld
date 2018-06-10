<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2018 Jean-Sebastien Morisset (https://surniaulula.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'These aren\'t the droids you\'re looking for...' );
}

if ( ! class_exists( 'JSM_Pretty_JSON_LD_Filters' ) ) {

	class JSM_Pretty_JSON_LD_Filters {

		private static $instance;

		public function __construct() {

			if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
				return;	// Nothing to do.
			}

			add_action( 'template_redirect', array( __CLASS__, 'output_buffer_start' ), self::get_min_int() );
		}

		public static function &get_instance() {

			if ( ! isset( self::$instance ) ) {
				self::$instance = new self;
			}

			return self::$instance;
		}

		public static function get_min_int() {
			return defined( 'PHP_INT_MIN' ) ? PHP_INT_MIN : -2147483648;    // Since PHP 7.0.0.
		}
		
		public static function get_max_int() {
			return defined( 'PHP_INT_MAX' ) ? PHP_INT_MAX : 2147483647;     // Since PHP 5.0.2.
		}

		public static function output_buffer_start() {

			ob_start( array( __CLASS__, 'extract_json_ld' ) );
		}

		public static function extract_json_ld( $buffer ) {

			if ( empty( $buffer ) || is_feed() ) {
				return $buffer;
			}

			$buffer = preg_replace_callback( '/(<script type=["\']application\/ld\+json["\']>)({.*})(<\/script>)/Ui',
				array( __CLASS__, 'format_json_ld' ), $buffer );

			return $buffer;
		}

		public static function format_json_ld( $matches ) {

			$lib_dir = trailingslashit( realpath( dirname( __FILE__ ) ) );

			require_once $lib_dir . 'ext/json-format.php';

			if ( ! empty( $matches[2] ) ) {
				$matches[2] = SuextJsonFormat::get( $matches[2] );
			}

			return $matches[1] . $matches[2] . $matches[3];
		}
	}

	JSM_Pretty_JSON_LD_Filters::get_instance();
}
