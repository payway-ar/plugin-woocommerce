<?php
/**
 *
 *
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
	const WC_DECIDIR_VERSION_VALUE = '0.1.0';

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
}
