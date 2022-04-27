<?php
/**
 * @author IURCO - Prisma SA
 * @copyright Copyright © 2022 IURCO and PRISMA. All rights reserved.
 */

class WC_Payway_Request_General_Processor implements WC_Payway_Request_Processor_Interface {

	/**
     * @var string
     */
    const DESCRIPTION = 'description';

	/**
	 *
	 * @param WC_Order $order
	 * @param array $checkout_posted_data
	 * @return array
	 */
	public function process( $order, $checkout_posted_data ) {
		return array(
			self::DESCRIPTION => $this->get_data( $order )
		);
	}

	/**
	 * @param WC_Order $order
	 * @return array
	 */
	private function get_data( $order )
	{
		return '';
	}
}
