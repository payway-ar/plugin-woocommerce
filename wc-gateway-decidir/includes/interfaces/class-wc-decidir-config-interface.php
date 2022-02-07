<?php
/**
 * @author IURCO - Prisma SA
 * @copyright Copyright © 2022 IURCO and PRISMA. All rights reserved.
 */

/**
 * Holds all Decidir config field names within WooCommerce Payment Settings
 */
interface WC_Decidir_Config_Interface {

	/**
	 * @var string
	 */
	const PAYMENT_CODE = 'decidir_gateway';

	/**
	 * @var string
	 */
	const FIELD_ENABLED = 'enabled';

	/**
	 * @var string
	 */
	const FIELD_GATEWAY_TITLE = 'title';

	/**
	 * @var string
	 */
	const FIELD_GATEWAY_DESCRIPTION = 'description';

	/**
	 * @var string
	 */
	const FIELD_SANDBOX_MODE = 'sandbox_mode';

	/**
	 * @var string
	 */
	const FIELD_CYBERSOURCE_ENABLED = 'cybersource_enabled';

	/**
	 * @var string
	 */
	const FIELD_DEBUG_ENABLED = 'debug';

	/**
	 * @var string
	 */
	const FIELD_SANDBOX_SITE_ID = 'sandbox_site_id';

	/**
	 * @var string
	 */
	const FIELD_SANDBOX_PUBLIC_KEY = 'sandbox_public_key';

	/**
	 * @var string
	 */
	const FIELD_SANDBOX_PRIVATE_KEY = 'sandbox_private_key';

	/**
	 * @var string
	 */
	const FIELD_PRODUCTION_SITE_ID = 'production_site_id';

	/**
	 * @var string
	 */
	const FIELD_PRODUCTION_PUBLIC_KEY = 'production_public_key';

	/**
	 * @var string
	 */
	const FIELD_PRODUCTION_PRIVATE_KEY = 'production_private_key';
}
