<?php
/**
 * @author IURCO - Prisma SA
 * @copyright Copyright © 2022 IURCO and PRISMA. All rights reserved.
 */

defined( 'ABSPATH' ) || exit;

/**
 * WC_Payment_Gateway_Decidir Class.
 */
class WC_Payment_Gateway_Decidir extends WC_Payment_Gateway {

	const PAYMENT_CODE = WC_Decidir_Config_Interface::PAYMENT_CODE;

	/**
	 * Whether or not logging is enabled
	 *
	 * @var bool
	 */
	public static $log_enabled = false;

	/**
	 * Logger instance
	 *
	 * @var WC_Logger
	 */
	public static $log = false;

	/**
	 * Constructor for the gateway.
	 */
	public function __construct() {
		$this->id					= self::PAYMENT_CODE;
		$this->has_fields			= true;
		$this->icon					= apply_filters(
			'woocommerce_decidir_icon',
			plugins_url('assets/images/card-logos.png', WC_DECIDIR_PLUGIN_FILE)
		);
		$this->order_button_text	= __( 'Pay with Decidir', 'wc-gateway-decidir' );
		$this->method_title			= __( 'Decidir Payment Gateway', 'wc-gateway-decidir' );
		$this->method_description	= __( 'PRISMA Decidir allows customers to pay directly through your site.', 'wc-gateway-decidir' );
		$this->supports				= array('products');

		// Load the settings.
		$this->init_form_fields();
		$this->init_settings();

		// Define Checkout Form variables.
		$this->title          = $this->get_option( 'title' );
		$this->description    = $this->get_option( 'description' );

		if ( ! $this->is_valid_for_use() ) {
			// TODO: add an admin notice
			$this->enabled = 'no';
		}

		// listens admin options gets saved for this Payment Gateway
		add_action(
			'woocommerce_update_options_payment_gateways_' . $this->id,
			array( $this, 'process_admin_options' )
		);

		// implement custom success content
		if ( 'yes' === $this->enabled ) {
			add_filter(
				'woocommerce_thankyou_order_received_text',
				array( $this, 'order_received_text' ),
				10,
				2
			);
		}

		// After processing Checkout Payment form
		// check if we need to add a fee into the Order before sending it to the gateway
		add_action(
			'wc_decidir_request_builder_process_before',
			array( $this, 'order_process_before_gateway' )
		);
	}

	/**
	 * Captures the Order and creates fees into the Order
	 *
	 * @param WC_Decidir_Request_Builder $builder
	 */
	public function order_process_before_gateway( $builder ) {
		$data = $builder->get_checkout_form_data();
		$plan = false;

		if (
			isset($data['rule_id'])
			&& isset($data['installments'])
			&& $data['rule_id']
			&& $data['installments']
		) {
			$rule_id = $data['rule_id'];
			$installments = $data['installments'];

			$plan = WC_Decidir_Promotion_Factory::get_applied_fee_plan( $rule_id,  $installments );
		}

		if ( $plan && $builder->get_order()) {
			$order = $builder->get_order();
			$total = $order->get_total();

			$coefficient = $plan->coefficient;
			$new_total = $total * $coefficient;
			$charge = $new_total - $total;

			// Ensure there's a charge that needs to be billed
			if ( $charge > 0) {
				$fee = new WC_Order_Item_Fee();
				$fee->set_name( __('Transaction Fee', 'wc-gateway-decidir') );
				$fee->set_amount( $charge );
				$fee->set_total( $charge );

				$order->add_item( $fee );
				$order->calculate_totals();
				$order->save();
			}
		}
	}

	/**
	 * Return whether or not this gateway still requires setup to function.
	 *
	 * When this gateway is toggled on via AJAX, if this returns true a
	 * redirect will occur to the settings page instead.
	 *
	 * @return bool
	 */
	public function needs_setup() {
		// TODO: implement config check so admin user gets redirected if there're missing fields
		// for now, we'll force the redirection
		return true;
	}

	/**
	 * Initialise Admin settings form fields
	 */
	public function init_form_fields()
	{
		$this->form_fields = include WC_DECIDIR_ABSPATH . 'includes/wc-decidir-admin-settings.php';
	}

	/**
	 * Check if this gateway is available based on WordPress and WooCommerce configuration
	 * - checks if site's currency
	 *
	 * @return bool
	 */
	public function is_valid_for_use() {
		$currency_supported = in_array(
			get_woocommerce_currency(),
			apply_filters(
				'woocommerce_decidir_supported_currencies',
				array( 'ARS' )
			),
			true
		);

		return (bool) $currency_supported;
	}

	public function is_available() {
		$is_available = parent::is_available();

		return (bool) $is_available && $this->is_config_ready_for_checkout();
	}

	/**
	 * Ensure all conditions are meet to render the form within the Checkout

	 * @return bool
	 */
	public function is_config_ready_for_checkout() {
		// Ensure we have Configured Promotions
		// otherwise, there's nothing to render in the Checkout
		$data = wc_decidir_storefront_options();
		$promotions = !empty( $data['promotions'] )
			? true
			: false;

		return (bool) $promotions;
	}

	/**
	 * Admin Panel Options.
	 */
	public function admin_options() {
		if ( $this->is_valid_for_use() ) {
			parent::admin_options();
		} else {
			?>
			<div class="inline error">
				<p>
					<strong><?php esc_html_e( 'Gateway disabled', 'woocommerce' ); ?></strong>: <?php esc_html_e( 'Decidir Gateway does not support your store currency.', 'wp-gateway-decidir' ); ?>
				</p>
			</div>
			<?php
		}
	}

	/**
	 * Builds Checkout Payment Form
	 *
	 * @return void
	 */
	public function payment_fields() {
		if ( ! is_checkout() || ! $this->is_config_ready_for_checkout() ) {
			return;
		}

		$this->enqueueStorefrontScripts();

		wp_localize_script(
			'woocommerce_gateway_decidir_sdk',
			'wc_gateway_decidir_params',
			wc_decidir_storefront_options()
		);

		require_once WC_DECIDIR_ABSPATH . 'template/html-decidir-checkout-form.php';
	}

	/**
	 *
	 * @return void
	 */
	public function validate_fields() {
// wc_add_notice( __METHOD__, 'error' );
// wc_add_notice( 'second message in validate_fields()', 'error' );
	}

	/**
	 * Process the payment and return the result.
	 *
	 * @param int $order_id Order ID.
	 * @return array
	 */
	public function process_payment( $order_id ) {
		// Loads all files required to execute the payment request
		Decidir()->init_decidir_request_includes();

		try {
			$order = wc_get_order( $order_id );
			$builder = new WC_Decidir_Request_Builder();

			// Retrieve custom payment fields
			// till we find a better way to hook up into WC process
			$request_data = $builder->set_checkout_form_data( $_POST )
				->set_order( $order )
				->process();

			// Store the Fee Plan selected by the Customer into the Order
			$this->update_order_meta_promotion(
				$order,
				$builder->get_checkout_form_data()
			);

			// Execute the Gateway call
			$request = new WC_Decidir_Request();
			$request->pay( $request_data );

			if ( $request->get_success() ) {
				$order->payment_complete( $request->get_transaction_id() );

				// Update WC_Order custom fields for custom metabox display
				$this->update_order_meta( $request->get_result(), $order );

				// Leave an Order message, without notifying
				$this->add_success_note_to_order( $order );

				$result = array(
					'result'   => 'success',
					'redirect' => $this->get_return_url( $order )
				);
			} else {
				wc_add_notice(
					__('Payment error: ', 'wc-gateway-decidir') . $request->get_error_messages(),
					'error'
				);

				// Leave an Order message, without notifying
				$this->add_failure_note_to_order(
					$order,
					$request->get_error_messages()
				);

				// Cancel the Order
				$message = 'Order cancelled due to an error while processing in the gateway: ' . $e->getMessage();
				$order->update_status(
					'cancelled',
					__( $message, 'wc-gateway-decidir' )
				);

				$result = array(
					'result'   => 'failure',
					'refresh' => true,
					'messages' => $request->get_error_messages()
				);
			}

			return $result;

		} catch (\Exception $e) {
			// TODO: log the error through custom wc_get_logger() implementation
			wc_add_notice(
				__('Payment error:', 'wc-gateway-decidir') . $e->getMessage(),
				'error'
			);

			// Leave an Order message, without notifying
			$this->add_failure_note_to_order(
				$order,
				$e->getMessage()
			);

			// Cancel the Order
			$message = 'Order cancelled due to an error while processing in the gateway: ' . $e->getMessage();
			$order->update_status(
				'cancelled',
				__( $message, 'wc-gateway-decidir' )
			);

			return array(
				'result' => 'failure',
				'refresh' => true,
				'messages' => $e->getMessage()
			);
		}
	}

	/**
	 * Adds a success message whether using Cybersource or not
	 *
	 * @param WC_Order $order
	 * @param string $error_message
	 */
	private function add_failure_note_to_order( $order, $error_message = '' ) {
		$note = __('Order couldn\'t be processed through Decidir Payment Gateway', 'wc-gateway-decidir');

		if ( wc_decidir_config_is_cs_enabled() ) {
			$note .= __(' using Cybersource', 'wc-gateway-decidir');
		}

		if ( $error_message ) {
			$note .= __(': ' . $error_message, 'wc-gateway-decidir');
		}

		$order->add_order_note( $note );
	}

	/**
	 * Adds a success message whether using Cybersource or not
	 *
	 * @param WC_Order $order
	 */
	private function add_success_note_to_order( $order ) {
		$note = __('Order successfully processed through Decidir Payment Gateway', 'wc-gateway-decidir');

		if ( wc_decidir_config_is_cs_enabled() ) {
			$note .= __(' using Cybersource', 'wc-gateway-decidir');
		}

		$order->add_order_note( $note );
	}

	/**
	 * Update the Promotion applied meta field with the selected Fee Plan
	 *
	 * @param WC_Order $order
	 * @param array $posted_data
	 * @return bool Whether process finished OK
	 */
	public function update_order_meta_promotion( $order, $posted_data ) {
		$order_id = $order->get_id();

		/**
		 * Retrieves the `fee_to_send` value
		 * from the Installment dropdown's selected option
		 * within the Checkout Payment form
		 *
		 * @see WC_Decidir_Request_Builder::set_checkout_form_data()
		 * @var int
		 */
		$fee_to_send = isset( $posted_data['installments'] )
			? $posted_data['installments']
			: false;
		$promotion_id = isset( $posted_data['rule_id'] )
			? $posted_data['rule_id']
			: false;

		// we can't proceed if we don't have both
		if ( !$promotion_id || !$fee_to_send ) {
			return false;
		}

		// Now retrieve the Fee Plan data
		$fee_details = WC_Decidir_Promotion_Factory::get_applied_fee_plan(
			$promotion_id,
			$fee_to_send
		);

		// Update the Order meta data with the selected Plan
		WC_Decidir_Meta::set_order_promotion( $order_id, $fee_details );
	}

	/**
	 * Saves order metadata for further display through Order View
	 *
	 * @param array $result
	 * @param WC_Order $order
	 */
	public function update_order_meta( $result, $order ) {
		$order_id = $order->get_id();

		// Saves full response from Gateway
		WC_Decidir_Meta::set_order_transaction_response( $order_id, $result );
		// Saves transaction id data
		WC_Decidir_Meta::set_order_transaction_id( $order_id, $result['id'] );
		// Saves Site Transaction Id data
		WC_Decidir_Meta::set_order_site_transaction_id( $order_id, $result['site_transaction_id'] );

		$payment_data = array();
		$payment_data['payment_method_id'] = $result['payment_method_id'] ?? 'no data in response';
		$payment_data['credit_card_type'] = $result['card_brand'] ?? 'no data in response';
		$payment_data['installments'] = $result['installments'] ?? 'no data in response';
		$payment_data['status'] = $result['status'] ?? 'no data in response';

		// Builds general status data details
		if ( isset($result['status_details']) ) {
			$details = $result['status_details'];
			$payment_data['status_details'] = array(
				'ticket' => $details['ticket'],
				'card_authorization_code' => $details['card_authorization_code'],
				'address_validation_code' => $details['address_validation_code']
			);
		}

		// Builds Cybersource data
		if ( isset($result['fraud_detection']) && isset($result['fraud_detection']['status']) ) {
			$fraud = $result['fraud_detection']['status'];
			$payment_data['cybersource'] = array(
				'decision' => $fraud['decision'],
				'description' => $fraud['description']
			);
		}

		// Save data into the Order meta field
		WC_Decidir_Meta::set_order_payment_data( $order_id, $payment_data );
	}

	/**
	 * Register scripts for frontend
	 *
	 * @return void
	 */
	private function enqueueStorefrontScripts() {
		//@TODO: most likely this shouldn't be hardcoded and depends on other factors, like SDK version
		$scriptUrl = 'https://live.decidir.com/static/v2.5/decidir.js';
		wp_enqueue_script(
			'woocommerce_gateway_decidir_sdk',
			$scriptUrl,
			array(),
			'2.5',
		);
		// wp_enqueue_script('decidir_js', 'https://live.decidir.com/static/v2/decidir.js');

		/**
		 * Introduce WC JS lib to control price format
		 * @see WC_Widget_Price_Filter
		 */
		wp_enqueue_script(
			'accounting',
			// we're forcing to always use `min` version
			WC()->plugin_url() . '/assets/js/accounting/accounting.min.js',
			array( 'jquery' ),
			'0.4.2',
			true
		);

		// Implements accounting.js configuration
		wp_localize_script(
			'accounting',
			'wc_gateway_decidir_accounting_format',
			array(
				'precision' => wc_get_price_decimals(),
				'symbol'  => get_woocommerce_currency_symbol(),
				'decimal'  => esc_attr( wc_get_price_decimal_separator() ),
				'thousand' => esc_attr( wc_get_price_thousand_separator() ),
				'format' => esc_attr( str_replace( array( '%1$s', '%2$s' ), array( '%s', '%v' ), get_woocommerce_price_format() ) )
			)
		);

		// Storefront styling
		wp_register_style(
			'woocommerce_gateway_decidir_styles',
			plugins_url('assets/css/style.css', WC_DECIDIR_PLUGIN_FILE)
		);
		wp_enqueue_style( 'woocommerce_gateway_decidir_styles' );

		// JS handling card changes
		// wp_register_script(
		// 	'woocommerce_gateway_decidir_form_card',
		// 	plugins_url('assets/js/card.js', WC_DECIDIR_PLUGIN_FILE),
		// 	array('jquery', 'woocommerce_gateway_decidir_sdk')
		// );
		// wp_enqueue_script('woocommerce_gateway_decidir_form_card');

		// JS that takes care of all Form interation
		// wp_register_script(
		// 	'woocommerce_gateway_decidir_form',
		// 	plugins_url('assets/js/form.js', WC_DECIDIR_PLUGIN_FILE),
		// 	array('jquery', 'woocommerce_gateway_decidir_sdk')
		// );
		// wp_enqueue_script('woocommerce_gateway_decidir_form');
	}

	/**
	 * Load admin scripts.
	 */
	public function admin_scripts() {
		$screen = get_current_screen();
		$screen_id = $screen ? $screen->id : '';

		if ( 'woocommerce_page_wc-settings' !== $screen_id ) {
			return;
		}

		// wp_enqueue_script(
		// 	'woocommerce_gateway_decidir_admin_scripts',
		// 	__DIR__ . '/includes/admin/assets/js/decidir-admin.js',
		// 	array(),
		// 	$version,
		// 	true
		// );
	}

	/**
	 * Custom order received text.
	 *
	 * @param string   $text Default text.
	 * @param WC_Order $order Order data.
	 * @return string
	 */
	public function order_received_text( $text, $order ) {
		if ( $order && $this->id === $order->get_payment_method() ) {
			return esc_html__( 'Thank you for your payment. Your transaction has been completed.', 'wc-gateway-decidir' );
		}

		return $text;
	}
}
