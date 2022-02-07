<?php
/**
 *
 *
 */

class WC_Decidir_Request_Customer_Processor implements WC_Decidir_Request_Processor_Interface {

	/**
     * @var string
     */
    const CUSTOMER = 'customer';

    /**
     * @var string
     */
    const ID = 'id';

    /**
     * @var string
     */
    const EMAIL = 'email';

    /**
     * @var string
     */
    const IP_ADDRESS = 'ip_address';

	/**
	 *
	 * @param WC_Order $order
	 * @param array $checkout_posted_data
	 * @return array
	 */
	public function process( $order, $checkout_posted_data ) {
		return array(
			self::CUSTOMER => $this->get_data( $order )
		);
	}

	/**
	 * @param WC_Order $order
	 * @return array
	 */
	private function get_data( $order )
	{
		$customerId = $order->get_customer_id() > 0
			? $order->get_customer_id()
			: $order->get_billing_first_name() . '_' . $order->get_billing_last_name();

		return array(
			self::ID => (string) $customerId,
			self::EMAIL => $order->get_billing_email(),
			self::IP_ADDRESS => $order->get_customer_ip_address(),
		);
	}
}
