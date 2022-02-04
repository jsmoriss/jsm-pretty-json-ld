<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2018-2022 Jean-Sebastien Morisset (https://surniaulula.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'JsmPrettyJsonLdFilters' ) ) {

	class JsmPrettyJsonLdFilters {

		private static $instance = null;	// JsmPrettyJsonLdFilters class object.

		public function __construct() {

			if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

				return;	// Nothing to do.
			}

			/**
			 * Note that the FIRST PHP output buffer created will be the LAST to execute, so hook the
			 * WordPress 'template_redirect' action before any other plugin and start the PHP output buffer.
			 */
			add_action( 'template_redirect', array( __CLASS__, 'output_buffer_start' ), PHP_INT_MIN );
		}

		public static function &get_instance() {

			if ( null === self::$instance ) {

				self::$instance = new self;
			}

			return self::$instance;
		}

		public static function output_buffer_start() {

			ob_start( array( __CLASS__, 'extract_json_ld' ) );
		}

		public static function extract_json_ld( $buffer ) {

			if ( empty( $buffer ) || is_feed() ) {
				return $buffer;
			}

			/**
			 * PCRE modifier notes:
			 *
			 * U = This modifier inverts the "greediness" of the quantifiers so that they are not greedy by default, but become greedy if followed by ?.
			 * i = If this modifier is set, letters in the pattern match both upper and lower case letters.
			 * s = If this modifier is set, a dot metacharacter in the pattern matches all characters, including newlines.
			 *
			 * DO NOT USE THE 's' MODIFIER, SO THAT ONLY SINGLE-LINE LD+JSON SCRIPTS ARE DETECTED AND FORMATTED.
			 */
			$buffer = preg_replace_callback(
				array(
					'/(<script\b[^>]*type=["\']application\/ld\+json["\'][^>]*>)({.*})(<\/script>)/Ui',
				),
				array( __CLASS__, 'format_json_ld' ),
				$buffer
			);

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

		private static function get_min_int() {

			return defined( 'PHP_INT_MIN' ) ? PHP_INT_MIN : -2147483648;	// Since PHP v7.0.0.
		}
	}

	JsmPrettyJsonLdFilters::get_instance();
}
