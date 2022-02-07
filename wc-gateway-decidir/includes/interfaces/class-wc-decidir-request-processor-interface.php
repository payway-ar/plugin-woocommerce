<?php
/**
 *
 *
 */

/**
 *
 */
interface WC_Decidir_Request_Processor_Interface {
	/**
	 *
	 * @param WC_Order $order
	 * @param array $checkout_posted_data Checkout posted data
	 * @return array
	 */
	public function process( $order, $checkout_posted_data );
}
