<?php
/**
 * @author IURCO - Prisma SA
 * @copyright Copyright © 2022 IURCO and PRISMA. All rights reserved.
 */

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
	 *
	 * @var array
	 */
	protected $checkout_posted_data = array();

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
	 * Returns the Checkout posted data associated during the Payment process
	 *
	 * @see WC_Payment_Gateway_Decidir::process_payment()
	 *
	 * @return array
	 */
	public function get_checkout_form_data() {
		return $this->checkout_posted_data;
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
		$rule_id = false;
		$card_id = false;
		$installments = false;

		/**
		 * Installment dropdown option value
		 *
		 * built with the following structure: <rule_id>-<card_id>-<fee_to_send>
		 * @var array
		 */
		if ( isset( $post_data['decidir_gateway_cc_installments'])) {
			$cc_installments = explode('-', $post_data['decidir_gateway_cc_installments']);
			$rule_id = isset( $cc_installments[0] )
				? (int) $cc_installments[0]
				: false;
			$card_id = isset( $cc_installments[1] )
				? (int) $cc_installments[1]
				: false;
			$installments = isset( $cc_installments[2] )
				? (int) $cc_installments[2]
				: false;
		}

		$this->checkout_posted_data = array(
			'rule_id' => $rule_id,
			'card_id' => $card_id,
			'installments' => $installments,
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
	 * Returns the associated Order
	 *
	 * @return WC_Order|null
	 */
	public function get_order() {
		return $this->order;
	}

	/**
	 * Assigns the Order
	 *
	 * @param WC_Order $order
	 * @return void
	 */
	public function set_order( $order ) {
		$this->order = $order;

		return $this;
	}

	/**
	 * Executes all data processors
	 *
	 * @return array data to be sent to the Gateway
	 */
	public function process()
	{
		$request = $this->request;
		$result = array();

		do_action('wc_decidir_request_builder_process_before', $this);

		foreach ( $this->processors as $name => $processor ) {
			$result = $this->merge(
				$result,
				$processor->process( $this->order, $this->checkout_posted_data )
			);
		}

		do_action('wc_decidir_request_builder_process_after', $this);

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
