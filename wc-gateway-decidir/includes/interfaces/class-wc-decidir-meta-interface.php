<?php
/**
 * @author IURCO - Prisma SA
 * @copyright Copyright © 2022 IURCO and PRISMA. All rights reserved.
 */

/**
 *
 */
interface WC_Decidir_Meta_Interface
{
	/**
	 * @var string
	 */
	const PREFIX = '_decidir_';

	/**
	 * @var string
	 */
	const TRANSACTION_ID = 'transaction_id';

	/**
	 * @var string
	 */
	const SITE_TRANSACTION_ID = 'site_transaction_id';

	/**
	 * @var string
	 */
	const PAYMENT_DATA = 'payment_data';
}
