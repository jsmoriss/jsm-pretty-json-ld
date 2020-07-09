<?php
/**
 * Plugin Name: JSM's Pretty Schema JSON-LD
 * Plugin Slug: jsm-pretty-json-ld
 * Text Domain: jsm-pretty-json-ld
 * Domain Path: /languages
 * Plugin URI: https://wpsso.com/extend/plugins/jsm-pretty-json-ld/
 * Assets URI: https://jsmoriss.github.io/jsm-pretty-json-ld/assets/
 * Author: JS Morisset
 * Author URI: https://surniaulula.com/
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Description: Re-format Schema LD+JSON / JSON-LD from Yoast SEO, WooCommerce, and others to create human readable (aka "pretty") code.
 * Requires PHP: 5.6
 * Requires At Least: 4.2
 * Tested Up To: 5.4.2
 * WC Tested Up To: 4.3.0
 * Version: 1.1.0
 * 
 * Version Numbering: {major}.{minor}.{bugfix}[-{stage}.{level}]
 *
 *      {major}         Major structural code changes / re-writes or incompatible API changes.
 *      {minor}         New functionality was added or improved in a backwards-compatible manner.
 *      {bugfix}        Backwards-compatible bug fixes or small improvements.
 *      {stage}.{level} Pre-production release: dev < a (alpha) < b (beta) < rc (release candidate).
 * 
 * Copyright 2016-2020 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'JSM_Pretty_JSON_LD' ) ) {

	class JSM_Pretty_JSON_LD {

		private static $instance = null;

		public function __construct() {

			add_action( 'plugins_loaded', array( __CLASS__, 'init_textdomain' ) );

			$plugin_dir = trailingslashit( realpath( dirname( __FILE__ ) ) );

			require_once $plugin_dir . 'lib/filters.php';	// Self instantiates.
		}

		public static function &get_instance() {

			if ( null === self::$instance ) {

				self::$instance = new self;
			}

			return self::$instance;
		}

		public static function init_textdomain() {

			static $loaded = null;

			if ( null !== $loaded ) {
				return;
			}

			$loaded = true;

			load_plugin_textdomain( 'jsm-pretty-json-ld', false, 'jsm-pretty-json-ld/languages/' );
		}
	}

	JSM_Pretty_JSON_LD::get_instance();
}
