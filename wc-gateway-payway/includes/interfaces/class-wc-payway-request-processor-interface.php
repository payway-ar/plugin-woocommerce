<?php
/**
 * @author IURCO - Prisma SA
 * @copyright Copyright © 2022 IURCO and PRISMA. All rights reserved.
 */

/**
 *
 */
interface WC_Payway_Request_Processor_Interface {
	/**
	 *
	 * @param WC_Order $order
	 * @param array $checkout_posted_data Checkout posted data
	 * @return array
	 */
	public function process( $order, $checkout_posted_data );
}
