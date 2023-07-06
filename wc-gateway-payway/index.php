<?php
/**
 * Plugin Name: Payway Payment Gateway for WooCommerce
 * Plugin URI: https://www.prismamediosdepago.com.ar
 * Description: Payway payment gateway integration for WooCommerce
 * Version: 0.2.9
 * Author: IURCO - Prisma SA
 * Author URI: https://iurco.com/
 * Text Domain: wc-gateway-payway
 * Domain Path: /i18n/languages
 * License: GPLv2 or later
 * Requires at least: 5.8.3
 * Requires PHP: 7.4
 *
 * @package wc-gateway-payway
 * @author IURCO - Prisma SA
 * @copyright Copyright © 2022 IURCO and PRISMA. All rights reserved.
 */

if ( ! defined( 'WC_PAYWAY_PLUGIN_FILE' ) ) {
	define( 'WC_PAYWAY_PLUGIN_FILE', __FILE__ );
}

// Include the main Payway class.
if ( ! class_exists( 'PaywayWC', false ) ) {
	include_once dirname( WC_PAYWAY_PLUGIN_FILE ) . '/includes/class-payway-wc.php';
}

/**
 * Introduce class to handle activate/deactivate/uninstall hooks
 * This should be moved into the PaywayWC class and refactor the entire plugin initialization
 * Currently this (along with the next few global functions), is requiered to retrieve the plugin version
 * from the database and the codebase (WC_Payway_Activator::class)
 *
 */
require_once 'includes/interfaces/class-wc-payway-activator-interface.php';
require_once 'includes/class-payway-activator.php';


/**
 * Returns Payway Gateway configuration options
 * if not set, `get_option` returns `false`
 *
 * @return array|bool
 */
function wc_payway_get_config_options() {
	return get_option( 'woocommerce_payway_gateway_settings' );
}

/**
 * Returns specific option value
 * If given option isn't found, `false` is returned
 *
 * @param string $name config option to retrieve
 * @return mixed
 */
function wc_payway_get_config_option( $name ) {
	$options = wc_payway_get_config_options();

	return ($options && isset($options[$name]))
		? $options[$name]
		: false;
}

/**
 * Returns the version value that the database holds
 *
 * @return string
 */
function wc_payway_get_version() {
	return get_option(
		WC_Payway_Activator_Interface::WC_PAYWAY_VERSION
	);
}

/**
 * Returns the version value that the database holds
 *
 * @return string
 */
function wc_payway_get_sdk_version() {
	return get_option(
		WC_Payway_Activator_Interface::WC_PAYWAY_SDK_VERSION
	);
}

/**
 * Returns the version value that the database holds
 * It's just a reference to display in a Status Report page
 * Please rely and use `wc_payway_get_version` along the entire codebase
 *
 * @return string
 */
function wc_payway_get_version_in_codebase() {
	return WC_Payway_Activator_Interface::WC_PAYWAY_VERSION_VALUE;
}

/**
 * Returns the version value that the database holds
 * It's just a reference to display in a Status Report page
 * Please rely and use `wc_payway_get_sdk_version` along the entire codebase
 *
 * @return string
 */
function wc_payway_get_sdk_version_in_codebase() {
	return WC_Payway_Activator_Interface::WC_PAYWAY_SDK_VERSION_VALUE;
}

// Registers activation process
register_activation_hook( __FILE__, array( 'WC_Payway_Activator', 'activate' ));
// register_deactivation_hook( __FILE__, array( 'WC_Payway_Activator', 'deactivate' ) );

// Registers uninstall process
register_uninstall_hook( __FILE__, array( 'WC_Payway_Activator', 'uninstall' ) );

// Initialize Payway class
add_action( 'plugins_loaded', array( 'PaywayWC', 'instance'));

/**
 * Returns the main instance of PaywayWC::class.
 */
// function Payway() {
// 	return PaywayWC::instance();
// }
