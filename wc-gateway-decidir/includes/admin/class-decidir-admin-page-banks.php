<?php
/**
 *
 */

defined( 'ABSPATH' ) || exit;

if ( class_exists( 'WC_Decidir_Admin_Navigation_Page_Banks', false ) ) {
	return;
}

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 *
 */
class WC_Decidir_Admin_Navigation_Page_Banks extends WP_List_Table {

	/**
	 * The single instance of the class
	 *
	 * @var WC_Decidir_Admin_Page_Banks
	 */
	protected static $_instance = null;

	/**
	 *
	 */
	public function __construct() {
		parent::__construct( [
			'singular' => __( 'Bank', 'decidir_gateway' ),
			'plural' => __( 'Banks', 'decidir_gateway' ),
			'ajax' => false,
			'screen' => 'decidir_admin_page_banks'
		] );
	}

	/**
	 * Main Decidir Instance.
	 *
	 * @static
	 * @return WC_Decidir_Admin_Page_Banks
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 *
	 * @param int $per_page
	 * @param int $page_number
	 * @return
	 */
	public static function get_banks( $per_page = 10, $page_number = 1 ) {
		return WC_Decidir_Bank_Factory::get_banks( $per_page, $page_number );
	}

	/**
	 *
	 * @param int $bank_id
	 * @return stdClass|null
	 */
	public static function get_bank( $bank_id ) {
		return WC_Decidir_Bank_Factory::get_bank( $bank_id );
	}

	/**
	 *
	 * @param array $data
	 * @return false|int
	 */
	public static function update_bank( $data ) {
		if ( ! isset( $data['bank_id'] ) || ! isset( $data['name'] ) ) {
			return false;
		}

		// just so we avoid publishing in the html page the real column name
		// now replacing so update() receives the right data
		$data['id'] = $data['bank_id'];
		unset($data['bank_id']);

		return WC_Decidir_Bank_Factory::update_bank( $data );
	}

	/**
	 *
	 * @param int|string $id
	 * @return [type]     [description]
	 */
	public static function delete_bank( $id ) {
		return WC_Decidir_Bank_Factory::delete_bank( $id );
	}

	/**
	 * @param string $name
	 * @return
	 */
	protected function create_bank( $data ) {
		return apply_filters('wc_decidir_gateway_admin_bank_create', $data );
	}

	public function no_items() {
		return __( 'No Banks available', 'decidir_gateway' );
	}

	/**
	* Associative array of columns
	*
	* @return array
	*/
	public function get_columns() {
		$columns = [
			// 'cb' => '<input type="checkbox" />',
			'name' => __( 'Name', 'decidir_gateway' ),
			// 'logo' => __( 'Logo', 'decidir_gateway' ),
		];

		return $columns;
	}

	/**
	 * Custom render for the Bank's name value
	 *
	 * @param object $item stdClass representation of the record
	 * @return string
	 */
	public function column_name( $item ) {
		// create a nonce
		$delete_nonce = wp_create_nonce( '_wpnonce_decidir-delete-bank-' . $item->id );

		$title = sprintf(
			'<a href="?page=%s&action=%s&bank_id=%s"><strong>%s</strong></a>',
			esc_attr( $_REQUEST['page'] ),
			'editbank',
			$item->id,
			$item->name
		);

		$actions = [
			'edit' => sprintf(
				'<a href="?page=%s&action=%s&bank_id=%s">Edit</a>',
				esc_attr( $_REQUEST['page'] ),
				'editbank',
				absint( $item->id )
			),
			'delete' => sprintf(
				'<a href="?page=%s&action=%s&bank_id=%s&_wpnonce=%s">Delete</a>',
				esc_attr( $_REQUEST['page'] ),
				'deletebank',
				absint( $item->id ),
				$delete_nonce
			)
		];

		return $title . $this->row_actions( $actions );
	}

	/**
	* Columns to make sortable.
	*
	* @return array
	*/
	// public function get_sortable_columns() {
	// 	$sortable_columns = array(
	// 		'name' => array( 'name', true )
	// 	);
	//
	// 	return $sortable_columns;
	// }

	/**
	 * Render a column when no column specific method exists.
	 *
	 * @param object $item stdClass representation of the record
	 * @param string $column_name
	 * @return mixed
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			default:
				return $item->$column_name;
		}
	}

	/**
	 * Handles data query and filter, sorting, and pagination.
	 */
	public function prepare_items() {
		$this->_column_headers = $this->get_column_info();

		/** Process bulk action */
		// $this->process_bulk_action();

		// $per_page = $this->get_items_per_page( 'customers_per_page', 5 );
		$per_page = 20;
		$current_page = $this->get_pagenum();
		$total_items = WC_Decidir_Bank_Factory::record_count();

		$this->set_pagination_args( [
			'total_items' => $total_items, //WE have to calculate the total number of items
			'per_page' => $per_page //WE have to determine how many items to show on a page
		] );

		// TODO: implement pagination
		$this->items = self::get_banks( $per_page, $current_page );
		return $this;
	}

	public function render() {
		require_once WC_DECIDIR_ABSPATH . 'includes/admin/html-decidir-admin-page-banks.php';

		return $this;
	}

	/**
	 * Returns the record list as a `WP_List_Table` format
	 *
	 * @return string
	 */
	public function get_table() {
		return $this->prepare_items()->display();
	}

	/**
	 * Render the bulk edit checkbox
	 *
	 * @param object $item stdClass representation of the record
	 * @return string
	 */
	// public function column_cb( $item ) {
	// 	return sprintf(
	// 		'<input type="checkbox" name="bulk-delete[]" value="%s" />',
	// 		$item->id
	// 	);
	// }

	/**
	 * Returns an associative array containing the bulk action
	 *
	 * @return array
	 */
	// public function get_bulk_actions() {
	// 	$actions = [
	// 		'bulk-delete' => __( 'Delete', 'decidir_gateway' )
	// 	];
	//
	// 	return $actions;
	// }

	/**
	 *
	 * @return
	 */
	// public function process_bulk_action() {
	// 	// Detect when a bulk action is being triggered...
	// 	if ( 'delete' === $this->current_action() ) {
	// 		// In our file that handles the request, verify the nonce.
	// 		$nonce = esc_attr( $_REQUEST['_wpnonce'] );
	//
	// 		if ( ! wp_verify_nonce( $nonce, '_wpnonce_decidir-delete-banks-massaction' ) ) {
	// 			die( 'Go get a life script kiddies' );
	// 		} else {
	// 			self::delete_bank( absint( $_GET['bank_id'] ) );
	// 			wp_redirect( esc_url( add_query_arg() ) );
	// 			exit;
	// 		}
	// 	}
	//
	// 	// If the delete bulk action is triggered
	// 	if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' )
	// 	|| ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' )
	// 	) {
	// 		$delete_ids = esc_sql( $_POST['bulk-delete'] );
	//
	// 		// loop over the array of record ids and delete them
	// 		foreach ( $delete_ids as $id ) {
	// 			self::delete_bank( $id );
	// 		}
	//
	// 		wp_redirect( esc_url( add_query_arg() ) );
	// 		exit;
	// 	}
	// }
}
