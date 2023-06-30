<?php
/**
 * @author IURCO - Prisma SA
 * @copyright Copyright © 2022 IURCO and PRISMA. All rights reserved.
 */

class WC_Payway_Request_CyberSource_Processor implements WC_Payway_Request_Processor_Interface {
	/**
	 * @var string
	 */
	const FRAUD_DETECTION = 'fraud_detection';

	/**
	 * @var boolean
	 */
	const SEND_TO_CS = true;

	/**
	 * @var string
	 */
	const CHANNEL = 'Web';

	/**
	 *
	 * @param WC_Order $order
	 * @param array $checkout_posted_data
	 * @return array
	 */
	public function process( $order, $checkout_posted_data ) {
		return array(
			self::FRAUD_DETECTION => $this->get_data( $order )
		);
	}

	/**
	 *
	 * @param WC_Order $order
	 * @return array
	 */
	private function get_data( $order )
	{
		$data = array(
			'send_to_cs' => (bool) self::SEND_TO_CS,
			'channel' => self::CHANNEL,
			'dispatch_method' => '',
			'bill_to' => $this->get_bill_to( $order ),
			'purchase_totals' => $this->get_purchase_total( $order ),
			'customer_in_site' => $this->get_customer_in_site( $order ),
			'retail_transaction_data' => array(
				'ship_to' => $this->get_ship_to( $order ),
				'items' => $this->get_order_items( $order ),
				'days_to_delivery' => '',
				'dispatch_method' => '',
				'tax_voucher_required' => true,
				'customer_loyalty_number' => '',
				'coupon_code' => ''
			)
		);

		return $data;
	}

	/**
	 *
	 * @param WC_Order $order
	 * @return int
	 */
	private function get_purchase_total( $order ) {
		$amount = $this->parse_amount( $order->get_total() );
		return array(
			'currency' => $order->get_currency(),
			'amount' => $amount * 100
		);
	}

	/**
	 *
	 * @param WC_Order $order
	 * @return array
	 */
	private function get_order_items( $order ) {
		$items = array();

		foreach ( $order->get_items() as $item ) {
			$product = $item->get_product();
			$product_sku = $product->get_sku();
			$product_price = $this->parse_amount( $product->get_price() );

			$item_id = $item->get_id();
			$item_name = $item->get_name();
			$description = $product->get_short_description() ?? $product->get_description();
			$line_total = $this->parse_amount( $item->get_total() );
			$quantity = $item->get_quantity();

			if ($product_sku == "") {
				$product_sku = $item_id . "-" . strtolower(str_replace(" ", "-", $item_name));
			}

			$items[] = [
				'code' => $product_sku,
				'name' => $item_name,
				'description' => $description,
				'sku' => $product_sku,
				'total_amount' => $line_total * 100,
				'quantity' => $quantity,
				'unit_price' => $product_price * 100
			];
		}

		return $items;
	}

	private function get_bill_to( $order ) {
		return array(
			'customer_id' => (string) $order->get_customer_id() ?? $order->get_billing_first_name() . '_' . $order->get_billing_last_name(),
			'first_name' => $order->get_billing_first_name(),
			'last_name' => $order->get_billing_last_name(),
			'email' => $order->get_billing_email(),
			'phone_number' => $order->get_billing_phone(),
			'country' => $order->get_billing_country(),
			'state' => $order->get_billing_state(),
			'city' => $order->get_billing_city(),
			'postal_code' => $order->get_billing_postcode(),
			'street1' => $order->get_billing_address_1()
		);
	}

	/**
	 *
	 * @param WC_Order $order
	 * @return array
	 */
	private function get_ship_to( $order ) {
		if ( ! $order->has_shipping_address() ) {
			$bill_to = $this->get_bill_to( $order );
			unset($bill_to['customer_id']);
			return $bill_to;
		}

		$shipping_phone = $order->get_shipping_phone() != ''
			? $order->get_shipping_phone()
			: $order->get_billing_phone();

		return array(
			'customer_id' => $order->get_customer_id() ?? $order->get_shipping_first_name() . '_' . $order->get_shipping_last_name(),
			'first_name' => $order->get_shipping_first_name(),
			'last_name' => $order->get_shipping_last_name(),
			'email' => $order->get_billing_email(),
			'phone_number' => $shipping_phone,
			'country' => $order->get_shipping_country(),
			'state' => $order->get_shipping_state(),
			'city' => $order->get_shipping_city(),
			'postal_code' => $order->get_shipping_postcode(),
			'street1' => $order->get_shipping_address_1()
		);
	}

	/**
	 *
	 * @param WC_Order $order
	 * @return array
	 */
	private function get_customer_in_site( $order ) {
		$cid = $order->get_customer_id();

		if ( $cid ) {
			$customer = new WC_Customer( $cid );
		}

		return array(
			// 'days_in_site' => (string) $cid ? $this->get_days_in_site( $customer ) : 0,
			'is_guest' => $cid ? false : true,
			'password' => '',
			'num_of_transactions' => (int) $cid ? $customer->get_order_count() : 0,
			'cellphone_number' => $order->get_billing_phone(),
			'date_of_birth' => '',
			'street1' => (int) $cid ? $customer->get_billing_address() : ''
		);
	}

	/**
	 * @param WC_Customer $customer
	 * @return int
	 */
	private function get_days_in_site( $customer ) {
		try {
			$datetime1 = date_create( $customer->get_date_created() );
			$datetime2 = date_create( 'now' );

			$interval = date_diff($datetime1, $datetime2);
			$days = $interval->format( '%a' );
		} catch (\Exception $exception) {
			// we're not interested logging this exception
			// given this param is optional
			$days = 0;
		}

		return $days;
	}

	/**
	 * Returns an integer from a float
	 *
     * Gateway accepts only integers for prices in some cases
     * will infer last two digits as decimals
     *
     * @param float $total
     * @return int
     */
	private function parse_amount( $total ) {
		return (int) str_replace(",", "", str_replace(".", "", $total));
	}
}
