<?php
/**
 * @author IURCO - Prisma SA
 * @copyright Copyright © 2022 IURCO and PRISMA. All rights reserved.
 */

use Automattic\WooCommerce\Utilities\NumberUtil;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Generates requests to send
 */
class WC_Decidir_Request_Builder {

	/**
	 * WooCommerce Order
	 *
	 * @var WC_Order
	 */
	protected $order;

	/**
	 * Holds all processors responsible for building the request
	 *
	 * @see WC_Decidir_Request_Processor_Interface
	 * @var array
	 */
	protected $processors;

	/**
	 * Holds relevant checkout form data values
	 * @var array
	 */
	protected $checkout_posted_data;

	/**
	 * Constructor
	 *
	 */
	public function __construct( ) {
		// initialize request processors
		$this->init_processors();
	}

	/**
	 * Includes required classes for request building
	 */
	private function init_processors() {
		$processors = array(
			'token-processor' 		=> new WC_Decidir_Request_Token_Processor,
			'general-processor' 	=> new WC_Decidir_Request_General_Processor,
			'customer-processor' 	=> new WC_Decidir_Request_Customer_Processor,
			'payment-processor' 	=> new WC_Decidir_Request_Payment_Processor,
			'order-processor' 		=> new WC_Decidir_Request_Order_Processor,
			'subpayments-processor' => new WC_Decidir_Request_SubPayments_Processor,
		);

		if ( wc_decidir_config_is_cs_enabled() ) {
			$processors['cybersource-processor'] = new WC_Decidir_Request_CyberSource_Processor;
		}

		$this->processors = $processors;
	}

	/**
	 *
	 * TODO: WC_Checkout::get_checkout_fields
	 * to add custom form fields into the list through:
	 * filter `woocommerce_checkout_fields`
	 *
	 * TODO: WC_Checkout::get_posted_data filter `woocommerce_checkout_posted_data` to capture posted data
	 * TODO: WC_Checkout::get_posted_data filter `woocommerce_process_checkout_field_{$key}` to capture one field at a time
	 * for manging custom fields
	 *
	 * TODO: WC_Checkout::process_checkout filter `woocommerce_checkout_order_processed`
	 *
	 * @param array $post_data
	 * @return $this
	 */
	public function set_checkout_form_data( $post_data ) {
		$this->checkout_posted_data = array(
			'installments' => isset($post_data['decidir_gateway_cc_installments'])
				? explode('-', $post_data['decidir_gateway_cc_installments'])[2]
				: false,
			'token' => isset($post_data['decidir_gateway_cc_token'])
				? $post_data['decidir_gateway_cc_token']
				: false,
			'bin' => isset($post_data['decidir_gateway_cc_bin'])
				? $post_data['decidir_gateway_cc_bin']
				: false,
			'last_digits' => isset($post_data['decidir_gateway_cc_last_digits'])
				? $post_data['decidir_gateway_cc_last_digits']
				: false,
			'cc_type' => isset($post_data['decidir_gateway_cc_type'])
				? $post_data['decidir_gateway_cc_type']
				: false
		);

		return $this;
	}

	/**
	 *
	 * @param WC_Order $order
	 *
	 * @return void
	 */
	public function set_order( $order ) {
		$this->order = $order;

		return $this;
	}

	/**
	 *
	 * @return array
	 */
	public function process()
	{
		$request = $this->request;
		$result = array();

		foreach ( $this->processors as $name => $processor ) {
			$result = $this->merge(
				$result,
				$processor->process( $this->order, $this->checkout_posted_data )
			);
		}

		return $result;
	}

	/**
     * Merge function for builders
     *
     * @param array $result
     * @param array $builder
     * @return array
     */
    protected function merge(array $result, array $builder)
    {
        return array_replace_recursive($result, $builder);
    }
}
