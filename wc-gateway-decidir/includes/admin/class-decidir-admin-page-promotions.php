<?php
/**
 *
 */

defined( 'ABSPATH' ) || exit;

if ( class_exists( 'WC_Decidir_Admin_Navigation_Page_Promotions', false ) ) {
	return;
}

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 *
 */
class WC_Decidir_Admin_Navigation_Page_Promotions extends WP_List_Table {

	/**
	 * The single instance of the class
	 *
	 * @var WC_Decidir_Admin_Page_Promotions
	 */
	protected static $_instance = null;

	/**
	 *
	 */
	public function __construct() {
		parent::__construct( [
			'singular' => __( 'Promotion', 'decidir_gateway' ),
			'plural' => __( 'Promotions', 'decidir_gateway' ),
			'ajax' => false,
			'screen' => 'decidir_admin_page_promotions'
		] );

		// wp_register_script(
		// 	'decidir_admin_promotions_plans',
		// 	plugins_url('assets/js/admin-promotions-plans.js', WC_DECIDIR_PLUGIN_FILE),
		// 	array('jquery')
		// );
		// wp_enqueue_script('decidir_admin_promotions_plans');
	}

	/**
	 * Main Decidir Instance.
	 *
	 * @static
	 * @return WC_Decidir_Admin_Page_Promotions
	 */
	public function instance() {
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
	public function get_promotions( $per_page = 10, $page_number = 1 ) {
		return WC_Decidir_Promotion_Factory::get_promotions( $per_page, $page_number );
	}

	/**
	 *
	 * @param int $promotion_id
	 * @return stdClass|null
	 */
	public function get_promotion( $promotion_id ) {
		return WC_Decidir_Promotion_Factory::get_promotion( $promotion_id );
	}

	/**
	 * Validates form fields
	 *
	 * @param array $post Form post data to be validated
	 * @return WP_Error|null
	 */
	public function validate_promotion_data( $post ) {
		$required_field_names = WC_Decidir_Promotion_Factory::get_required_fields();

		$error_messages = array();
		$errors = null;

		foreach($required_field_names as $field_name) {
			if ( ! isset($post[$field_name]) || $post[$field_name] == '' ) {
				$error_messages[] = array(
					'error_key' => $field_name,
					'message' => __( $field_name . ' is a required field', 'decidir-gateway' )
				);
			}
		}

		if ( ! empty($error_messages) ) {
			$errors = new WP_Error;

			foreach($error_messages as $key => $message) {
				$errors->add( $key, $message );
			}
		}

		return $errors;
	}

	/**
	 *
	 * @param array $data
	 * @return false|int
	 */
	public function update_promotion( $data ) {

		if ( ! isset( $data['promotion_id'] )
			|| ! isset( $data['rule_name'] )
			|| ! isset( $data['card_id'] )
			|| ! isset( $data['bank_id'] )
			|| ! isset( $data['from_date'] )
			|| ! isset( $data['to_date'] )
			// || ! isset( $data['priority'] )
			|| ! isset( $data['is_active'] )
			|| ! isset( $data['applicable_days'] )
			// || ! isset( $data['fee_plans'] )
		) {
			return false;
		}

		// just so we avoid publishing in the html page the real column name
		// now replacing so update() receives the right data
		$data['id'] = $data['promotion_id'];
		unset($data['promotion_id']);

		return WC_Decidir_Promotion_Factory::update_promotion( $data );
	}

	/**
	 * @param int|string $id
	 * @return
	 */
	public static function delete_promotion( $id ) {
		return WC_Decidir_Promotion_Factory::delete_promotion( $id );
	}

	/**
	 * @param string $name
	 * @return
	 */
	protected function create_promotion( $data ) {
		/** @see WP_Decidir_Promotion_Factory::__construct() */
		return apply_filters('wc_decidir_gateway_admin_promotion_create', $data );
	}

	public function no_items() {
		return __( 'No Promotions available', 'decidir_gateway' );
	}

	/**
	 * Returns list of Cards for filling out Card dropdown
	 *
	 * @return array[]
	 */
	public static function get_cards() {
		$cards = WC_Decidir_Card_Factory::get_cards();

		$result = array();
		foreach ($cards as $card) {
			$result[] = array('label' => $card->card_name, 'value' => $card->id);
		}

		return $result;
	}

	/**
	 * Returns list of Banks for filling out Bank dropdown
	 *
	 * @return array[]
	 */
	public function get_banks() {
		$banks = WC_Decidir_Bank_Factory::get_banks();

		$result = array();
		foreach ($banks as $bank) {
			$result[] = array('label' => $bank->name, 'value' => $bank->id);
		}

		return $result;
	}

	/**
	 * Returns an array with all the week days for the dropdown to be filled in
	 *
	 * @see WC_Decidir_Promotion_Factory::get_weekdays_list()
	 * @return array[]
	 */
	public function get_weekdays_for_element() {
		return WC_Decidir_Promotion_Factory::get_weekdays_list();
	}


	/**
	 * Whether the given day number exists in the given Promotion
	 *
	 * @param string $day_number
	 * @param stdClass $promotion instance of the current Promotion item
	 * @return bool
	 */
	public function is_day_in_promotion( $day_number, $promotion ) {
		return in_array(
			$day_number,
		 	WC_Decidir_Promotion_Factory::get_promotion_days_array( $promotion )
	 	);
	}

	/**
	* Associative array of columns
	*
	* @return array
	*/
	public function get_columns() {
		return array(
			'rule_name' => __( 'Name', 'decidir_gateway' ),
			'bank_id' => __( 'Bank', 'decidir_gateway' ),
			'card_id' => __( 'Card', 'decidir_gateway' ),
			'from_date' => __( 'From Date', 'decidir_gateway' ),
			'to_date' => __( 'To Date', 'decidir_gateway' ),
			'priority' => __( 'Priority', 'decidir_gateway' ),
			'is_active' => __( 'Enabled?', 'decidir_gateway' ),
			'applicable_days' => __( 'Applicable Days', 'decidir_gateway' )
		);
	}

	/**
	 * Method for Bank column so it displays the name
	 * instead of the id
	 *
	 * @param object $item stdClass representation of the record
	 * @return string
	 */
	public function column_bank_id( $item ) {
		$bank = WC_Decidir_Bank_Factory::get_bank( $item->bank_id );
		return $bank->name;
	}

	/**
	 * Method for Bank column so it displays the name
	 * instead of the id
	 *
	 * @param object $item stdClass representation of the record
	 * @return string
	 */
	public function column_card_id( $item ) {
		$card = WC_Decidir_Card_Factory::get_card( $item->card_id );
		return $card->card_name;
	}

	/**
	 * Method for Bank column so it displays the name
	 * instead of the id
	 *
	 * @param object $item stdClass representation of the record
	 * @return string
	 */
	public function column_is_active( $item ) {
		return $item->is_active
			? __( 'Enabled', 'decidir_gateway' )
			: __( 'Disabled', 'decidir_gateway' );
	}

	/**
	 * Method for Bank column so it displays the name
	 * instead of the id
	 *
	 * @param object $item stdClass representation of the record
	 * @return string
	 */
	public function column_applicable_days( $item ) {
		global $wp_locale;

		if ( ! $item->applicable_days ) {
			return '';
		}

		$currents = explode( ',', $item->applicable_days );
		$labels = array();

		foreach ( $currents as $day_id ) {
			array_push( $labels, $wp_locale->get_weekday( $day_id ) );
		}

		return implode( ', ', $labels );
	}

	/**
	 * Method for Rule Name column
	 *
	 * @param array $item an array of DB data
	 * @return string
	 */
	public function column_rule_name( $item ) {
		// create a nonce
		$delete_nonce = wp_create_nonce( '_wpnonce_decidir-delete-promotion-' . $item->id );

		$title = sprintf(
			'<a href="?page=%s&action=%s&promotion_id=%s"><strong>%s</strong></a>',
			esc_attr( $_REQUEST['page'] ),
			'editpromotion',
			$item->id,
			$item->rule_name
		);

		$actions = [
			'edit' => sprintf(
				'<a href="?page=%s&action=%s&promotion_id=%s">Edit</a>',
				esc_attr( $_REQUEST['page'] ),
				'editpromotion',
				absint( $item->id )
			),
			'delete' => sprintf(
				'<a href="?page=%s&action=%s&promotion_id=%s&_wpnonce=%s">Delete</a>',
				esc_attr( $_REQUEST['page'] ),
				'deletepromotion',
				absint( $item->id ),
				$delete_nonce
			)
		];

		return $title . $this->row_actions( $actions );
	}

	/**
	 * Render a column when no column specific method exists.
	 *
	 * @param array $item
	 * @param string $column_name
	 *
	 * @return mixed
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			default:
				return $item->$column_name;
		}
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
	 * Handles data query and filter, sorting, and pagination.
	 */
	public function prepare_items() {
		$this->_column_headers = $this->get_column_info();

		/** Process bulk action */
		// $this->process_bulk_action();

		// $per_page = $this->get_items_per_page( 'customers_per_page', 5 );
		$per_page = 20;
		$current_page = $this->get_pagenum();
		$total_items = WC_Decidir_Promotion_Factory::record_count();

		$this->set_pagination_args( [
			'total_items' => $total_items, //WE have to calculate the total number of items
			'per_page' => $per_page //WE have to determine how many items to show on a page
		] );

		// TODO: implement pagination
		$this->items = self::get_promotions( $per_page, $current_page );
		return $this;
	}

	public function render() {
		require_once WC_DECIDIR_ABSPATH . 'includes/admin/html-decidir-admin-page-promotions.php';

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
	 * @param array $item
	 *
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
