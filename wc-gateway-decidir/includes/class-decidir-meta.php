<?php
/**
 * @author IURCO - Prisma SA
 * @copyright Copyright © 2022 IURCO and PRISMA. All rights reserved.
 */

defined( 'ABSPATH' ) || exit;

/**
 * Meta data factory class
 *
 * @see https://developer.wordpress.org/reference/functions/get_post_meta/
 * @see https://developer.wordpress.org/reference/functions/update_post_meta/
 */
class WC_Decidir_Meta implements WC_Decidir_Meta_Interface {

	/**
	 * Instance
	 *
	 * @static
	 * @return WC_Decidir_Meta
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Returns the prefix which is used to unique identify our custom fields
	 *
	 * @return string
	 */
	private static function get_prefix_meta_name() {
		return static::PREFIX;
	}

	/**
	 * Includes the prefix used in all the meta fields
	 *
	 * @param string $meta name of the custom meta
	 * @return string
	 */
	public static function get_full_meta_name( $meta ) {
		return self::get_prefix_meta_name() . $meta;
	}

	/**
	 * Retrieves the `transaction_id` value from the meta field
	 *
	 * @param int $order_id
	 * @param boolean $single whether to return a single value or not
	 * @return mixed
	 */
	public static function get_order_transaction_response( $order_id, $single = false ) {
		return get_post_meta(
			$order_id,
			self::get_full_meta_name( self::FULL_RESPONSE ),
			$single
		);
	}

	/**
	 * Saves the SDK response from the transaction into the WC_Order custom meta field
	 *
	 * @param int $order_id WC_Order id
	 * @param array $response SDK Transaction response
	 */
	public static function set_order_transaction_response( $order_id, $response ) {
		update_post_meta(
			$order_id,
			self::get_full_meta_name( self::FULL_RESPONSE ),
			$response
		);
	}

	/**
	 * Retrieves the `transaction_id` value from the meta field
	 *
	 * @param int $order_id
	 * @param boolean $single whether to return a single value or not
	 * @return mixed
	 */
	public static function get_order_transaction_id ( $order_id, $single = false ) {
		return get_post_meta(
			$order_id,
			self::get_full_meta_name( self::TRANSACTION_ID ),
			$single
		);
	}

	/**
	 * Saves the `transaction_id` value into the WC_Order custom meta field
	 *
	 * @param int $order_id WC_Order id
	 * @param string $transaction_id
	 */
	public static function set_order_transaction_id ( $order_id, $transaction_id ) {
		update_post_meta(
			$order_id,
			self::get_full_meta_name( self::TRANSACTION_ID ),
			$transaction_id
		);
	}

	/**
	 * Returns the `site_transaction_id` saved in the meta custom field
	 *
	 * @param int $order_id
	 * @param boolean $single whether to return a single value or not
	 * @return mixed
	 */
	public static function get_order_site_transaction_id( $order_id, $single = false ) {
		return get_post_meta(
			$order_id,
			self::get_full_meta_name( static::SITE_TRANSACTION_ID ),
			$single
		);
	}

	/**
	 * Saves the `site_transaction_id` value into the WC_Order
	 *
	 * @param int $order_id
	 * @param string $stid
	 */
	public static function set_order_site_transaction_id( $order_id, $stid ) {
		update_post_meta(
			$order_id,
			self::get_full_meta_name( static::SITE_TRANSACTION_ID ),
			$stid
		);
	}

	/**
	 * Returns saved Promotion meta data from the Order
	 *
	 * @param int $order_id WC_Order id
	 * @return array
	 */
	public static function get_order_promotion( $order_id, $single = false ) {
		return get_post_meta(
			$order_id,
			self::get_full_meta_name( static::PROMOTION_APPLIED ),
			$single
		);
	}

	/**
	 * Saves the Promotion Fee Plan selected during the Checkout process
	 *
	 * @param int $order_id WC_Order
	 * @param array $data
	 */
	public static function set_order_promotion( $order_id, $data ) {
		update_post_meta(
			$order_id,
			self::get_full_meta_name( static::PROMOTION_APPLIED ),
			$data
		);
	}

	/**
	 * Returns saved Promotion meta data from the Order
	 *
	 * @param int $order_id WC_Order id
	 * @return array|string
	 */
	public static function get_order_payment_data( $order_id, $single = false ) {
		return get_post_meta(
			$order_id,
			self::get_full_meta_name( static::PAYMENT_DATA ),
			$single
		);
	}

	/**
	 * Sets custom Payment Data related to the transaction
	 *
	 * @param int $order_id WC_Order id
	 * @param array $data
	 */
	public static function set_order_payment_data( $order_id, $data ) {
		update_post_meta(
			$order_id,
			self::get_full_meta_name( static::PAYMENT_DATA ),
			$data
		);
	}
}
