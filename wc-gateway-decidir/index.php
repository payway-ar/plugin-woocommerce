<?php
/**
 * Plugin Name: Decidir Payment Gateway for WooCommerce
 * Plugin URI: https://www.prismamediosdepago.com.ar
 * Description: Decidir payment gateway integration for WooCommerce
 * Version: 0.1.0
 * Author: IURCO - Prisma SA
 * Author URI: https://iurco.com/
 * License: GPLv2 or later
 * Requires at least: 5.8
 * Requires PHP: 7.4
 *
 * @package wc-gateway-decidir
 * @author IURCO - Prisma SA
 * @copyright Copyright (c) 2021 IURCO SAS - PRISMA
 */

if ( ! defined( 'WC_DECIDIR_PLUGIN_FILE' ) ) {
	define( 'WC_DECIDIR_PLUGIN_FILE', __FILE__ );
}

// Include the main Decidir class.
if ( ! class_exists( 'DecidirWC', false ) ) {
	include_once dirname( WC_DECIDIR_PLUGIN_FILE ) . '/includes/class-decidir-wc.php';
}

/**
 * Introduce class to handle activate/deactivate/uninstall hooks
 * This should be moved into the DecidirWC class and refactor the entire plugin initialization
 * Currently this (along with the next few global functions), is requiered to retrieve the plugin version
 * from the database and the codebase (WC_Decidir_Activator::class)
 *
 */
require_once 'includes/interfaces/class-wc-decidir-activator-interface.php';
require_once 'includes/class-decidir-activator.php';


/**
 * Returns Decidir Gateway configuration options
 * if not set, `get_option` returns `false`
 *
 * @return array|bool
 */
function wc_decidir_get_config_options() {
	return get_option( 'woocommerce_decidir_gateway_settings' );
}

/**
 * Returns specific option value
 * If given option isn't found, `false` is returned
 *
 * @param string $name config option to retrieve
 * @return mixed
 */
function wc_decidir_get_config_option( $name ) {
	$options = wc_decidir_get_config_options();

	return ($options && isset($options[$name]))
		? $options[$name]
		: false;
}

/**
 * Returns the version value that the database holds
 *
 * @return string
 */
function wc_decidir_get_version() {
	return get_option(
		WC_Decidir_Activator_Interface::WC_DECIDIR_VERSION
	);
}

/**
 * Returns the version value that the database holds
 *
 * @return string
 */
function wc_decidir_get_sdk_version() {
	return get_option(
		WC_Decidir_Activator_Interface::WC_DECIDIR_SDK_VERSION
	);
}

/**
 * Returns the version value that the database holds
 * It's just a reference to display in a Status Report page
 * Please rely and use `wc_decidir_get_version` along the entire codebase
 *
 * @return string
 */
function wc_decidir_get_version_in_codebase() {
	return WC_Decidir_Activator_Interface::WC_DECIDIR_VERSION_VALUE;
}

/**
 * Returns the version value that the database holds
 * It's just a reference to display in a Status Report page
 * Please rely and use `wc_decidir_get_sdk_version` along the entire codebase
 *
 * @return string
 */
function wc_decidir_get_sdk_version_in_codebase() {
	return WC_Decidir_Activator_Interface::WC_DECIDIR_SDK_VERSION_VALUE;
}

// Registers activation process
register_activation_hook( __FILE__, array( 'WC_Decidir_Activator', 'activate' ));
// register_deactivation_hook( __FILE__, array( 'WC_Decidir_Activator', 'deactivate' ) );
// register_uninstall_hook( __FILE__, array( 'WC_Decidir_Activator', 'uninstall' ) );

// Initialize Decidir class
add_action( 'plugins_loaded', array( 'DecidirWC', 'instance'));

/**
 * Returns the main instance of DecidirWC::class.
 */
// function Decidir() {
// 	return DecidirWC::instance();
// }
