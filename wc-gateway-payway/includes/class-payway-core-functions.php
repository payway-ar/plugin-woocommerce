<?php
/**
 * @author IURCO - Prisma SA
 * @copyright Copyright © 2022 IURCO and PRISMA. All rights reserved.
 */

/**
 * TODO: implement cache for getting Settings to avoid hitting db too many times
 * - configuration settings could be cached (and cleaned/warmed up)
 *   when saving WC Settings admin section
 * - this interface loading, shouldn't be required to be done, `Payway()` is in charge
 */

// review an alternative load process
include_once WC_PAYWAY_ABSPATH . 'includes/interfaces/class-wc-payway-config-interface.php';
include_once WC_PAYWAY_ABSPATH . 'includes/interfaces/class-wc-payway-rest-interface.php';

/**
 * Returns the main instance of PaywayWC::class.
 */
function Payway() {
	return PaywayWC::instance();
}

/**
 * Retrieves the Payment code
 * which is used by WooCommerce to identify the Payment Gateway
 *
 * @return string
 */
function wc_payway_get_payment_code() {
	return WC_Payway_Config_Interface::PAYMENT_CODE;
}

/**
 * @return WC_Payway_Logger
 */
function wc_payway_get_logger( ) {
	return WC_Payway_Logger::instance();
}

/**
 * Whether plugin is configured to work in test mode
 *
 * @return boolean
 */
function wc_payway_is_sandbox_enabled() {
	return 'yes' === wc_payway_get_config_option(
		WC_Payway_Config_Interface::FIELD_SANDBOX_MODE
	);
}

/**
 * Whether Cybersource is enabled or not in the configuration
 *
 * @return boolean
 */
function wc_payway_config_is_cs_enabled() {
	return 'yes' === wc_payway_get_config_option(
		WC_Payway_Config_Interface::FIELD_CYBERSOURCE_ENABLED
	);
}

/**
 * Returns an array with credentials for the specified environment
 * returned array contains: `site_id`, `public_key` and `private_key`
 *
 * Avoid using this method directly. Use `wc_payway_get_<environment>_credentials()` instead
 *
 * @param boolean $sandbox Whether to return creds from sandbox or production
 * @param boolean $include_private_key Whether to include private key or not
 * @return array
 */
function wc_payway_get_credentials( $sandbox = true, $include_private_key = false ) {
	$data = [];
	$options = wc_payway_get_config_options();

	if ( $sandbox ) {
		$data['creds']['site_id'] = $options[WC_Payway_Config_Interface::FIELD_SANDBOX_SITE_ID] ?? '';
		$data['creds']['public_key'] = $options[WC_Payway_Config_Interface::FIELD_SANDBOX_PUBLIC_KEY] ?? '';

		if ( $include_private_key ) {
			$data['creds']['private_key'] = $options[WC_Payway_Config_Interface::FIELD_SANDBOX_PRIVATE_KEY] ?? '';
		}
	} else {
		$data['creds']['site_id'] = $options[WC_Payway_Config_Interface::FIELD_PRODUCTION_SITE_ID] ?? '';
		$data['creds']['public_key'] = $options[WC_Payway_Config_Interface::FIELD_PRODUCTION_PUBLIC_KEY] ?? '';

		if ( $include_private_key ) {
			$data['creds']['private_key'] = $options[WC_Payway_Config_Interface::FIELD_PRODUCTION_PRIVATE_KEY] ?? '';
		}
	}

	return $data;
}

/**
 * Retrieves Sandbox credentials, including the site id
 *
 * @param boolean $include_private_key whether to include private key or not
 * @return array
 */
function wc_payway_get_sandbox_credentials( $include_private_key = false ) {
	return wc_payway_get_credentials( true, $include_private_key );
}

/**
 * Retrieves Production credentials, including the site id
 *
 * @param boolean $include_private_key whether to include private key or not
 * @return array
 */
function wc_payway_get_production_credentials( $include_private_key = true ) {
	return wc_payway_get_credentials( false, $include_private_key );
}

/**
 * @return array
 */
function wc_payway_checkout_promotions() {
	$promotions = WC_Payway_Promotion_Factory::get_applicable_promotions();
	$banks = $cards = $plans = array();

	// If there're no Promotions configured, exit
	if ( empty( $promotions ) ) {
		return array();
	}

	foreach ( $promotions as $promo ) {
		$banks[$promo->bank_id] = array('name' => $promo->bank_name, 'value' => $promo->bank_id);
		$cards[$promo->bank_id][$promo->card_code] = array('name' => $promo->card_name, 'value' => $promo->card_code);

		if ($promo->fee_plans) {
			$list = json_decode($promo->fee_plans);

			foreach ($list as $plan) {
				$plans[$promo->bank_id][$promo->card_code][] = [
					'fee_period' => $plan->fee_period,
					'fee_to_send' => $plan->fee_to_send,
					'coefficient' => $plan->coefficient,
					'tea' => $plan->tea,
					'cft' => $plan->cft,
					'rule_id' => $promo->id,
					'rule_name' => $promo->rule_name
				];
			}
		}
	}

	return array(
		'banks' => $banks,
		'cards' => $cards,
		'plans' => $plans
	);
}

/**
 * Builds storefront required configuration for JS initialization
 * includes: public/private keys, endpoint url and if CS is enabled or not
 *
 * @param boolean $encode_as_json
 * @return array|JSON
 */
function wc_payway_storefront_options($encode_as_json = false) {
	$config_options = wc_payway_get_config_options();

	if ( wc_payway_is_sandbox_enabled() ) {
		$options = wc_payway_get_sandbox_credentials();
		$url = WC_Payway_Rest_Interface::SANDBOX_URL;
		$sandbox = true;
	} else {
		$options = wc_payway_get_production_credentials();
		$url = WC_Payway_Rest_Interface::PRODUCTION_URL;
		$sandbox = false;
	}

	$options[WC_Payway_Config_Interface::FIELD_CYBERSOURCE_ENABLED] = (
		'yes' === $config_options[WC_Payway_Config_Interface::FIELD_CYBERSOURCE_ENABLED])
			? true
			: false;

	$options['endpoint_url'] = $url;
	$options['sandbox_enabled'] = $sandbox;
	$options['promotions'] = wc_payway_checkout_promotions();

	return !$encode_as_json
		? $options
		: json_encode( $options );
}

/**
 * Returns the current day in numeric format
 *
 * Usually used to compare `applicable_days` of a Promotion
 * 0 (for Sunday) through 6 (for Saturday)
 *
 * @see https://www.php.net/manual/en/function.date.php
 * @see https://www.php.net/manual/en/datetime.format.php
 *
 * @return string
 */
function wc_payway_get_current_day_number() {
	return date('w');
}
