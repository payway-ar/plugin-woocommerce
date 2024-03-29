<?php
/**
 * @author IURCO - Prisma SA
 * @copyright Copyright © 2022 IURCO and PRISMA. All rights reserved.
 */

defined( 'ABSPATH' ) || exit;

if ( class_exists( 'WC_Payway_Admin_Navigation_Page_Promotions', false ) ) {
	return;
}

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 *
 */
class WC_Payway_Admin_Navigation_Page_Promotions extends WP_List_Table {

	/**
	 * The single instance of the class
	 *
	 * @var WC_Payway_Admin_Page_Promotions
	 */
	protected static $_instance = null;

	/**
	 *
	 */
	public function __construct() {
		parent::__construct( [
			'singular' => __( 'Promotion', 'wc-gateway-payway' ),
			'plural' => __( 'Promotions', 'wc-gateway-payway' ),
			'ajax' => false,
			'screen' => 'payway_admin_page_promotions'
		] );

		// wp_register_script(
		// 	'payway_admin_promotions_plans',
		// 	plugins_url('assets/js/admin-promotions-plans.js', WC_PAYWAY_PLUGIN_FILE),
		// 	array('jquery')
		// );
		// wp_enqueue_script('payway_admin_promotions_plans');
	}

	/**
	 * Main Payway Instance.
	 *
	 * @static
	 * @return WC_Payway_Admin_Page_Promotions
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
		return WC_Payway_Promotion_Factory::get_promotions( $per_page, $page_number );
	}

	/**
	 *
	 * @param int $promotion_id
	 * @return stdClass|null
	 */
	public function get_promotion( $promotion_id ) {
		return WC_Payway_Promotion_Factory::get_promotion( $promotion_id );
	}

	/**
	 * Validates form fields
	 *
	 * @param array $post Form post data to be validated
	 * @return WP_Error|null
	 */
	public function validate_promotion_data( $post ) {
		$required_field_names = WC_Payway_Promotion_Factory::get_required_fields();

		$error_messages = array();
		$errors = null;

		foreach($required_field_names as $field) {
			if ( ! isset($post[$field['name']]) || $post[$field['name']] == '' ) {
				$error_messages[] = array(
					'error_key' => $field['name'],
					'message' => __( '<strong>' . $field['label'] . '</strong>' . ' es un campo requerido', 'wc-gateway-payway' )
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

		return WC_Payway_Promotion_Factory::update_promotion( $data );
	}

	/**
	 * @param int|string $id
	 * @return
	 */
	public static function delete_promotion( $id ) {
		return WC_Payway_Promotion_Factory::delete_promotion( $id );
	}

	/**
	 * @param string $name
	 * @return
	 */
	protected function create_promotion( $data ) {
		/** @see WP_Payway_Promotion_Factory::__construct() */
		return apply_filters('wc_payway_gateway_admin_promotion_create', $data );
	}

	public function no_items() {
		return __( 'No Promotions available', 'wc-gateway-payway' );
	}

	/**
	 * Returns list of Cards for filling out Card dropdown
	 *
	 * @return array[]
	 */
	public static function get_cards() {
		$cards = WC_Payway_Card_Factory::get_cards();

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
		$banks = WC_Payway_Bank_Factory::get_banks();

		$result = array();
		foreach ($banks as $bank) {
			$result[] = array('label' => $bank->name, 'value' => $bank->id);
		}

		return $result;
	}

	/**
	 * Returns an array with all the week days for the dropdown to be filled in
	 *
	 * @see WC_Payway_Promotion_Factory::get_weekdays_list()
	 * @return array[]
	 */
	public function get_weekdays_for_element() {
		return WC_Payway_Promotion_Factory::get_weekdays_list();
	}

	/**
	 * Whether the given day number exists in the given Promotion
	 *
	 * @param string $day_number
	 * @param stdClass $promotion instance of the current Promotion item
	 * @return bool
	 */
	public function is_day_in_promotion( $day_number, $promotion ) {
		$exists = false;
		$promo_days = WC_Payway_Promotion_Factory::get_promotion_days( $promotion );

		if ( is_array( $promo_days)) {
			$exists = in_array( $day_number, $promo_days );
		} else {
			$exists = $promo_days === $day_number;
		}

		return $exists;
	}

	/**
	* Associative array of columns
	*
	* @return array
	*/
	public function get_columns() {
		return array(
			'rule_name' => __( 'Name', 'wc-gateway-payway' ),
			'bank_id' => __( 'Bank', 'wc-gateway-payway' ),
			'card_id' => __( 'Card', 'wc-gateway-payway' ),
			'from_date' => __( 'From Date', 'wc-gateway-payway' ),
			'to_date' => __( 'To Date', 'wc-gateway-payway' ),
			'priority' => __( 'Priority', 'wc-gateway-payway' ),
			'is_active' => __( 'Enabled?', 'wc-gateway-payway' ),
			'applicable_days' => __( 'Applicable Days', 'wc-gateway-payway' )
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
		$bank = WC_Payway_Bank_Factory::get_bank( $item->bank_id );
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
		$card = WC_Payway_Card_Factory::get_card( $item->card_id );
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
			? __( 'Enabled', 'wc-gateway-payway' )
			: __( 'Disabled', 'wc-gateway-payway' );
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
		$delete_nonce = wp_create_nonce( '_wpnonce_payway-delete-promotion-' . $item->id );

		$title = sprintf(
			'<a href="?page=%s&action=%s&promotion_id=%s"><strong>%s</strong></a>',
			esc_attr( $_REQUEST['page'] ),
			'editpromotion',
			$item->id,
			$item->rule_name
		);

		$actions = [
			'edit' => sprintf(
				'<a href="?page=%s&action=%s&promotion_id=%s">' . __('Edit') . '</a>',
				esc_attr( $_REQUEST['page'] ),
				'editpromotion',
				absint( $item->id )
			),
			'delete' => sprintf(
				'<a href="?page=%s&action=%s&promotion_id=%s&_wpnonce=%s">' . __('Delete', 'wc-gateway-payway')  . '</a>',
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
		$total_items = WC_Payway_Promotion_Factory::record_count();

		$this->set_pagination_args( [
			'total_items' => $total_items, //WE have to calculate the total number of items
			'per_page' => $per_page //WE have to determine how many items to show on a page
		] );

		// TODO: implement pagination
		$this->items = self::get_promotions( $per_page, $current_page );
		return $this;
	}

	public function render() {
		require_once WC_PAYWAY_ABSPATH . 'includes/admin/html-payway-admin-page-promotions.php';

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
}
