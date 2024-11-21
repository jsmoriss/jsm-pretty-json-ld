<?php
/*
 * Plugin Name: JSM Pretty Schema JSON-LD
 * Plugin Slug: jsm-pretty-json-ld
 * Text Domain: jsm-pretty-json-ld
 * Domain Path: /languages
 * Plugin URI: https://surniaulula.com/extend/plugins/jsm-pretty-json-ld/
 * Assets URI: https://jsmoriss.github.io/jsm-pretty-json-ld/assets/
 * Author: JS Morisset
 * Author URI: https://surniaulula.com/
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Description: Re-format Schema LD+JSON / JSON-LD from Yoast SEO, WooCommerce, and others to create human readable (aka "pretty") code.
 * Requires PHP: 7.4.33
 * Requires At Least: 5.9
 * Tested Up To: 6.7.1
 * WC Tested Up To: 9.4.1
 * Version: 1.2.0
 *
 * Version Numbering: {major}.{minor}.{bugfix}[-{stage}.{level}]
 *
 *      {major}         Major structural code changes and/or incompatible API changes (ie. breaking changes).
 *      {minor}         New functionality was added or improved in a backwards-compatible manner.
 *      {bugfix}        Backwards-compatible bug fixes or small improvements.
 *      {stage}.{level} Pre-production release: dev < a (alpha) < b (beta) < rc (release candidate).
 *
 * Copyright 2016-2024 Jean-Sebastien Morisset (https://surniaulula.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'JsmPrettyJsonLd' ) ) {

	class JsmPrettyJsonLd {

		private static $instance = null;	// JsmPrettyJsonLd class object.

		public function __construct() {

			add_action( 'plugins_loaded', array( $this, 'init_textdomain' ) );

			$plugin_dir = trailingslashit( realpath( dirname( __FILE__ ) ) );

			require_once $plugin_dir . 'lib/filters.php';	// Self instantiates.
		}

		public static function &get_instance() {

			if ( null === self::$instance ) {

				self::$instance = new self;
			}

			return self::$instance;
		}

		public function init_textdomain() {

			load_plugin_textdomain( 'jsm-pretty-json-ld', false, 'jsm-pretty-json-ld/languages/' );
		}
	}

	JsmPrettyJsonLd::get_instance();
}
