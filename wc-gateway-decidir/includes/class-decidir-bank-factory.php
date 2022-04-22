<?php
/**
 * @author IURCO - Prisma SA
 * @copyright Copyright © 2022 IURCO and PRISMA. All rights reserved.
 */

defined( 'ABSPATH' ) || exit;

/**
 * Banks factory class
 */
class WC_Decidir_Bank_Factory {

	/**
	 * The single instance of the class
	 *
	 * @var WC_Decidir_Bank_Factory
	 */
	protected static $_instance = null;

	public function __construct() {
		add_filter( 'wc_decidir_gateway_admin_bank_create', array( $this, 'create_bank' ) );
		add_filter( 'wc_decidir_gateway_admin_bank_update', array( $this, 'update_bank' ) );
	}

	/**
	 * Instance
	 *
	 * @static
	 * @return WC_Decidir_Bank_Factory
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	private static function get_table_name() {
		global $wpdb;

		return $wpdb->prefix . WC_Decidir_Activator_Interface::TABLE_NAME_BANKS;
	}

	/**
	 * Retrieves all records from the database
	 *
	 * @return (array[stdClass]|null)
	 */
	public static function get_banks() {
		global $wpdb;

		$query = "SELECT * FROM " . self::get_table_name();

		return $wpdb->get_results(
			// $wpdb->prepare()
			$query
		);
	}

	/**
	 *
	 * @param int|string $bank_id
	 * @return stdClass|null
	 */
	public static function get_bank( $bank_id ) {
		global $wpdb;

		$query = $wpdb->prepare(
			"SELECT * FROM " . self::get_table_name() . " WHERE id = %d",
			$bank_id
		);

		return $wpdb->get_row( $query );
	}

	/**
	 *
	 * @param array $data
	 * @return int|false id of the inserted record or false if fails
	 */
	public static function create_bank( $data ) {
		global $wpdb;

		$wpdb->insert(
			self::get_table_name(),
			array( 'name' => $data['name'] ),
			array( '%s' )
		);

		return $wpdb->insert_id;
	}

	/**
	 * Pass in the `name` and the `id` of the bank that needs to be updated
	 *
	 * @param array $data
	 * @return bool if successful, false otherwise
	 */
	public static function update_bank( $data ) {
		global $wpdb;

		$update = $wpdb->update(
			self::get_table_name(),
			array( 'name' => $data['name'] ),
			array( 'id' => $data['id'] ),
			array( '%s' ),
			array( '%d' )
		);

		if ( is_wp_error( $update ) ) {
			throw new Exception( $update->get_error_message() );
		}

		// When record exists but isn't updated (cause same data has been posted)
		// $wpdb->update() returns a zero integer, we need to normalize our response
		// to avoid showing an error when everything went OK but nothing gets saved
		return is_numeric($update)
			? true
			: false;
	}

	/**
	 *
	 * @param int $bank_id
	 * @return bool
	 */
	public static function delete_bank( $bank_id ) {
		global $wpdb;

		return (bool) $wpdb->delete(
			self::get_table_name(),
			[ 'id' => $bank_id ],
			[ '%d' ]
		);
	}

	/**
	 * Amount of banks currently in the database
	 * (usually to display then in the admin grids)
	 * returns `null` if no var is found by `get_var`
	 *
	 * @return mixed|null
	 */
	public static function record_count() {
		global $wpdb;

		$query = "SELECT COUNT(*) FROM " . self::get_table_name();

		return $wpdb->get_var( $query );
	}
}
