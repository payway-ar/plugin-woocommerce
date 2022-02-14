<?php
/**
 * @author IURCO - Prisma SA
 * @copyright Copyright © 2022 IURCO and PRISMA. All rights reserved.
 */

/**
 * Holds Decidir database configuration
 */
interface WC_Decidir_Activator_Interface {

	/**
	 * @var string
	 */
	const WC_DECIDIR_VERSION = 'decidir_gateway_version';

	/**
	 * Plugin version
	 *
	 * When updating, this must be also updated in the `index.php` file metadata
	 * otherwise, WordPress won't detect the version update
	 *
	 * @see wc-gateway-decidir/index.php
	 * @var string
	 */
	const WC_DECIDIR_VERSION_VALUE = '0.2.0';

	/**
	 * @var string
	 */
	const WC_DECIDIR_SDK_VERSION = 'decidir_gateway_sdk_version';

	/**
	 * @var string
	 */
	const WC_DECIDIR_SDK_VERSION_VALUE = '1.5.0';

	/**
	 * @var string
	 */
	const TABLE_NAME_BANKS = 'prisma_decidir_banks';

	/**
	 * @var string
	 */
	const TABLE_NAME_CARDS = 'prisma_decidir_cards';

	/**
	 * @var string
	 */
	const TABLE_NAME_PROMOTIONS = 'prisma_decidir_promotions';

	/**
	 * Config option name that holds the plugin version
	 *
	 * @var string
	 */
	const CONFIG_OPTION_GATEWAY_VERSION_NAME = 'decidir_gateway_version';

	/**
	 * Config option name that holds the Decidir SDK version
	 *
	 * @var string
	 */
	const CONFIG_OPTION_SDK_VERSION_NAME = 'decidir_gateway_sdk_version';
}
