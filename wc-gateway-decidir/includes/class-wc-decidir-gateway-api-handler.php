<?php
/**
 * @author IURCO - Prisma SA
 * @copyright Copyright © 2022 IURCO and PRISMA. All rights reserved.
 */

defined( 'ABSPATH' ) || exit;

/**
 *
 */
class WC_Decidir_Api_Handler {
	/**
	 * @var string
	 */
	protected $api_url;
	/**
	 * @var array
	 */
	protected $credentials;

	/**
	 * Environment type based if it's test mode or not
	 *
	 * @var string
	 */
	protected $type;

	/**
	 * @var \Decidir\Connector
	 */
	protected $connector;

	public function __construct() {
		$this->init_settings();
		$this->setup_connector();
	}

	protected function init_settings() {
		$config = wc_decidir_get_config_options();

		if ( ! $config ) {
			// no configuration saved yet
			wc_add_notice('no configuration saved yet');
			return;
		}

		if ( wc_decidir_is_sandbox_enabled() ) {
			$type = WC_Decidir_Rest_Interface::CONNECTOR_ENVIRONMENT_SANDBOX;
			$url = WC_Decidir_Rest_Interface::SANDBOX_URL;
			$public = $config[WC_Decidir_Config_Interface::FIELD_SANDBOX_PUBLIC_KEY];
			$private = $config[WC_Decidir_Config_Interface::FIELD_SANDBOX_PRIVATE_KEY];
		} else {
			$type = WC_Decidir_Rest_Interface::CONNECTOR_ENVIRONMENT_PRODUCTION;
			$url = WC_Decidir_Rest_Interface::PRODUCTION_URL;
			$public = $config[WC_Decidir_Config_Interface::FIELD_SANDBOX_PUBLIC_KEY];
			$private = $config[WC_Decidir_Config_Interface::FIELD_SANDBOX_PRIVATE_KEY];
		}

		$this->type = $type;
		$this->api_url = $url;
		$this->credentials = array(
			WC_Decidir_Rest_Interface::CONNECTOR_PUBLIC_KEY => $public,
			WC_Decidir_Rest_Interface::CONNECTOR_PRIVATE_KEY => $private,
		);
	}

	protected function setup_connector() {
		include_once WC_DECIDIR_ABSPATH . '/decidir/vendor/autoload.php';

		$this->connector = new \Decidir\Connector(
			$this->credentials,
			$this->type,
			WC_Decidir_Rest_Interface::CONNECTOR_DEVELOPER,
			WC_Decidir_Rest_Interface::CONNECTOR_GROUPER,
			WC_Decidir_Rest_Interface::CONNECTOR_SERVICE
		);
	}

	/**
	 * @param array $payment_data
	 * @return \Decidir\Payment\PaymentResponse
	 */
	public function post_payment( array $payment_data ) {
		return $this->connector
			->payment()
			->ExecutePayment( $payment_data );
	}
}
