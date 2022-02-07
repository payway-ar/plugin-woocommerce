<?php
/**
 * Decidir Cards factory.
 */

defined( 'ABSPATH' ) || exit;

/**
 * Cards factory class
 */
class WC_Decidir_Card_Factory {

	/**
	 * The single instance of the class
	 *
	 * @var WC_Decidir_Card_Factory
	 */
	protected static $_instance = null;

	/**
	 * Constructor function
	 */
	public function __construct() {
		add_filter( 'wc_decidir_gateway_admin_card_create', array( $this, 'create_card' ) );
	}

	/**
	 * Instance
	 *
	 * @static
	 * @return WC_Decidir_Card_Factory
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Returns Cards database table name
	 *
	 * @return string
	 */
	private static function get_table_name() {
		global $wpdb;

		return $wpdb->prefix . WC_Decidir_Activator_Interface::TABLE_NAME_CARDS;
	}

	/**
	 * Retrieves all the records from the database
	 *
	 * @return stdClass[]
	 */
	public static function get_cards() {
		global $wpdb;

		$query = "SELECT * FROM " . self::get_table_name();

		return $wpdb->get_results(
			// $wpdb->prepare()
			$query
		);
	}

	/**
	 *
	 * @param int|string $id
	 * @return stdClass|null
	 */
	public static function get_card( $card_id ) {
		global $wpdb;

		$query = $wpdb->prepare(
			"SELECT * FROM " . self::get_table_name() . " WHERE id = %d",
			$card_id
		);

		return $wpdb->get_row( $query );
	}

	/**
	 *
	 * @param array $data
	 * @return int|false id of the inserted record or false if fails
	 */
	public static function create_card( $data ) {
		global $wpdb;

		$wpdb->insert(
			self::get_table_name(),
			array(
				'card_name' => $data['card_name'],
				'id_sps' => $data['id_sps'],
				'id_nps' => $data['id_nps']
			),
			array( '%s', '%d', '%d' )
		);

		return $wpdb->insert_id;
	}

	/**
	 * Updates the given record id
	 *
	 * @param array $data
	 * @return bool if successful, false otherwise
	 */
	public static function update_card( $data ) {
		global $wpdb;

		$update = $wpdb->update(
			self::get_table_name(),
			array(
				'card_name' => $data['card_name'],
				'id_sps' => $data['id_sps'],
				'id_nps' => $data['id_nps']
			),
			array( 'id' => $data['id'] ),
			array( '%s', '%d', '%d' ),
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
	 * Deletes the given record from the database
	 *
	 * @param int $record_id
	 * @return bool
	 */
	public static function delete_card( $record_id ) {
		global $wpdb;

		return (bool) $wpdb->delete(
			self::get_table_name(),
			[ 'id' => $record_id ],
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
