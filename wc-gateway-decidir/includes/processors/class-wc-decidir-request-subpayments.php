<?php
/**
 *
 *
 */

class WC_Decidir_Request_SubPayments_Processor implements WC_Decidir_Request_Processor_Interface {

	/**
	 * @var string
	 */
     const SUB_PAYMENTS = 'sub_payments';

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
			self::SUB_PAYMENTS => array()
		);
	}
}
