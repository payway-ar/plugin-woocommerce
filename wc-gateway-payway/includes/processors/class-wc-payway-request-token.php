<?php
/**
 * @author IURCO - Prisma SA
 * @copyright Copyright © 2022 IURCO and PRISMA. All rights reserved.
 */

class WC_Payway_Request_Token_Processor implements WC_Payway_Request_Processor_Interface {

	/**
	 * @var string
	 */
    const TOKEN = 'token';

	/**
	 *
	 * @param WC_Order $order
	 * @param array $checkout_posted_data
	 * @return array
	 */
	public function process( $order, $checkout_posted_data ) {
		return $this->get_data( $order, $checkout_posted_data );
	}

	/**
	 * @param WC_Order $order
	 * @param array $checkout_posted_data
	 * @return array
	 */
	private function get_data( $order, $checkout_posted_data )
	{
		return array(
			self::TOKEN => $this->get_token( $checkout_posted_data )
		);
	}

	private function get_token( $data ) {
		return $data['token'];
	}
}
