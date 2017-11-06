<?php
/*
* Plugin Name: WooCommerce Product Bundles - Top Add to Cart Button
* Plugin URI: http://woocommerce.com/products/product-bundles/
* Description: Adds an add-to-cart button section at the top of Product Bundle pages.
* Version: 1.0.1
* Author: SomewhereWarm
* Author URI: http://somewherewarm.gr/
*
* Text Domain: woocommerce-product-bundles-top-add-to-cart-button
* Domain Path: /languages/
*
* Requires at least: 4.1
* Tested up to: 4.8
*
* WC requires at least: 3.0
* WC tested up to: 3.2
*
* Copyright: © 2017 SomewhereWarm SMPC.
* License: GNU General Public License v3.0
* License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_PB_Top_Add_To_Cart {

	/**
	 * Version.
	 * @var string
	 */
	public static $version = '1.0.1';

	/**
	 * Required PB version.
	 * @var string
	 */
	public static $req_pb_version = '5.5';

	/**
	 * Plugin URL.
	 */
	public static function plugin_url() {
		return plugins_url( basename( plugin_dir_path(__FILE__) ), basename( __FILE__ ) );
	}

	/**
	 * Plugin path.
	 */
	public static function plugin_path() {
		return untrailingslashit( plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Entry point.
	 */
	public static function init() {
		add_action( 'plugins_loaded', array( __CLASS__, 'load_plugin' ) );
	}

	/**
	 * Lights on.
	 */
	public static function load_plugin() {

		if ( ! function_exists( 'WC_PB' ) || version_compare( WC_PB()->version, self::$req_pb_version ) < 0 ) {
			add_action( 'admin_notices', array( __CLASS__, 'version_notice' ) );
			return false;
		}

		// Localize plugin.
		add_action( 'init', array( __CLASS__, 'localize_plugin' ) );

		// Hook extra add-to-cart section.
		add_action( 'woocommerce_single_product_summary', array( __CLASS__, 'top_add_to_cart' ), 25 );

		// Enqueue scripts and styles.
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'script' ) );
	}

	/**
	 * Load textdomain.
	 *
	 * @return void
	 */
	public static function localize_plugin() {
		load_plugin_textdomain( 'woocommerce-product-bundles-top-add-to-cart-button', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * PB version check notice.
	 */
	public static function version_notice() {
	    echo '<div class="error"><p>' . sprintf( __( '<strong>WooCommerce Product Bundles &ndash; Top Add To Cart</strong> requires Product Bundles <strong>v%s</strong> or higher.', 'woocommerce-product-bundles-top-add-to-cart-button' ), self::$req_pb_version ) . '</p></div>';
	}

	/**
	 * Show extra add-to-cart button section.
	 */
	public static function top_add_to_cart() {

		global $product;

		if ( is_a( $product, 'WC_Product_Bundle' ) ) {

			$elements      = apply_filters( 'woocommerce_bundles_top_add_to_cart_elements', array( 'error', 'availability', 'button' ), $product );
			$elements_html = '';

			foreach ( $elements as $el ) {
				$elements_html .= '<div class="bundle_' . $el . '"' . ( 'error' === $el ? ' style="display:none"' : '' ) . '></div>';
			}

			if ( ! empty( $elements_html ) ) {
				echo '<form method="post" class="cart bundle_form bundle_form_top"><div class="bundle_wrap">' . $elements_html . '</div></form>';
			}
		}
	}

	/**
	 * Script.
	 */
	public static function script() {

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_register_script( 'wc-pb-top-add-to-cart-button', self::plugin_url() . '/assets/js/wc-pb-top-add-to-cart-button' . $suffix . '.js', array( 'wc-add-to-cart-bundle' ), self::$version, true );
		wp_enqueue_script( 'wc-pb-top-add-to-cart-button' );
	}
}

WC_PB_Top_Add_To_Cart::init();
