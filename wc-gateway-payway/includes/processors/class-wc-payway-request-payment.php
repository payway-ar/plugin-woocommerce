<?php
/**
 * @author IURCO - Prisma SA
 * @copyright Copyright © 2022 IURCO and PRISMA. All rights reserved.
 */

class WC_Payway_Request_Payment_Processor implements WC_Payway_Request_Processor_Interface {

	/**
     * @var string
     */
    const BIN = 'bin';

    /**
     * @var string
     */
    const PAYMENT_METHOD_ID = 'payment_method_id';

    /**
     * @var string
     */
    const PAYMENT_TYPE = 'payment_type';

    /**
     * @var string
     */
    const INSTALLMENTS = 'installments';

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
		$data = array(
			self::INSTALLMENTS => $this->get_installments( $checkout_posted_data ),
			self::PAYMENT_METHOD_ID => $this->get_payment_method_id( $checkout_posted_data ),
			self::PAYMENT_TYPE => $this->get_payment_type( $order )
		);

		$cc_bin = $this->get_bin( $checkout_posted_data );
		if ( $cc_bin ) {
			$data[self::BIN] = $cc_bin;
		}

		return $data;
	}

	/**
	 *
	 * @param array $data
	 * @return int
	 */
	private function get_installments( $data ) {
		return (int) $data['installments'];
	}

	/**
	 *
	 * @param array $data
	 * @return int
	 */
	private function get_payment_method_id( $data ) {
		return (int) $data['cc_type'];
	}

	/**
	 *
	 * @param WC_Order $order
	 * @return string
	 */
	private function get_payment_type( $order ) {
		return 'single';
	}

	/**
	 *
	 * @param array $data
	 * @return null|string
	 */
	private function get_bin( $data ) {
		return (string) $data['bin'];
	}
}
