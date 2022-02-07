<?php
/**
 * @author IURCO - Prisma SA
 * @copyright Copyright © 2022 IURCO and PRISMA. All rights reserved.
 */

class WC_Decidir_Request_Order_Processor implements WC_Decidir_Request_Processor_Interface {

	/**
	 * @var string
	 */
	const AMOUNT = 'amount';

	/**
     * @var string
     */
    const CURRENCY = 'currency';

	/**
     * @var string
     */
    const ESTABLISHMENT_NAME = 'establishment_name';

	/**
     * @var string
     */
	const SITE_TRANSACTION_ID = 'site_transaction_id';

	/**
	 *
	 * @param WC_Order $order
	 * @param array $checkout_posted_data
	 * @return array
	 */
	public function process( $order, $checkout_posted_data ) {
		return $this->get_data( $order );
	}

	/**
	 *
	 * @param WC_Order
	 * @return array
	 */
	private function get_data( $order ) {
		return array(
			self::AMOUNT => $order->get_total( $order ),
			self::CURRENCY => $order->get_currency(),
			self::ESTABLISHMENT_NAME => $this->get_establishment_name(),
			self::SITE_TRANSACTION_ID => $order->get_order_number()
		);
	}

	/**
	 *
	 * @param WC_Order $order
	 * @return int
	 */
	private function get_amount( $order ) {
		return $order->get_total();

		// return (int) str_replace(",", "", str_replace(".", "", $amount));
	}

	/**
	 * Returns the WordPress blog site title
	 *
	 * @return string
	 */
	private function get_establishment_name()
	{
		return (string) get_bloginfo('name');
	}
}
