<?php
/**
 *
 *
 */

class WC_Decidir_Request_General_Processor implements WC_Decidir_Request_Processor_Interface {

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
