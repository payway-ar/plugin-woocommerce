<?php
/**
 * @author IURCO - Prisma SA
 * @copyright Copyright © 2022 IURCO and PRISMA. All rights reserved.
 */

/**
 * Interface that holds custom meta field names
 * with Transaction and Promotion data applied to the WC Order
 */
interface WC_Payway_Meta_Interface
{
	/**
	 * Prefix for all Plugin's Meta fields
	 *
	 * @var string
	 */
	const PREFIX = '_payway_';

	/**
	 * @var string
	 */
	const TRANSACTION_ID = 'transaction_id';

	/**
	 * Meta field that holds the transaction id value from the Merchant
	 *
	 * @var string
	 */
	const SITE_TRANSACTION_ID = 'site_transaction_id';

	/**
	 * @var string
	 */
	const PAYMENT_DATA = 'payment_data';

	/**
	 * Holds the entire SDK response data of a transaction
	 *
	 * @var string
	 */
	 const FULL_RESPONSE = 'full_response';

	/**
	 * Holds relevant Promotion data selected during Checkout process
	 *
	 * @var string
	 */
	const PROMOTION_APPLIED = 'promotion_applied';
}
