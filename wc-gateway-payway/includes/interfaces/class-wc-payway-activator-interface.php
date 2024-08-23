<?php
/**
 * @author IURCO - Prisma SA
 * @copyright Copyright © 2022 IURCO and PRISMA. All rights reserved.
 */

/**
 * Holds Payway database configuration
 */
interface WC_Payway_Activator_Interface {

	/**
	 * @var string
	 */
	const WC_PAYWAY_VERSION = 'payway_gateway_version';

	/**
	 * Plugin version
	 *
	 * When updating, this must be also updated in the `index.php` file metadata
	 * otherwise, WordPress won't detect the version update
	 *
	 * @see wc-gateway-payway/index.php
	 * @var string
	 */
	const WC_PAYWAY_VERSION_VALUE = '0.3.3';

	/**
	 * @var string
	 */
	const WC_PAYWAY_SDK_VERSION = 'payway_gateway_sdk_version';

	/**
	 * @var string
	 */
	const WC_PAYWAY_SDK_VERSION_VALUE = '1.5.0';

	/**
	 * @var string
	 */
	const TABLE_NAME_BANKS = 'prisma_payway_banks';

	/**
	 * @var string
	 */
	const TABLE_NAME_CARDS = 'prisma_payway_cards';

	/**
	 * @var string
	 */
	const TABLE_NAME_PROMOTIONS = 'prisma_payway_promotions';

	/**
	 * Config option name that holds the plugin version
	 *
	 * @var string
	 */
	const CONFIG_OPTION_GATEWAY_VERSION_NAME = 'payway_gateway_version';

	/**
	 * Config option name that holds the Payway SDK version
	 *
	 * @var string
	 */
	const CONFIG_OPTION_SDK_VERSION_NAME = 'payway_gateway_sdk_version';
}
