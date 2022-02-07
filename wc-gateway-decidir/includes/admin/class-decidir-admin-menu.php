<?php
/**
 *
 *
 */

if ( class_exists( 'WC_Decidir_Admin_Menu' )) {
	return;
}

class WC_Decidir_Admin_Menu {

	/**
	 * Holds an instance of `WC_Decidir_Admin_Navigation_Page_Banks`
	 * @var WC_Decidir_Admin_Navigation_Page_Banks|null
	 */
	private $banks = null;

	/**
	 * Holds an instance of `WC_Decidir_Admin_Navigation_Page_Cards`
	 * @var WC_Decidir_Admin_Navigation_Page_Cards|null
	 */
	private $cards = null;

	/**
	 * Holds an instance of `WC_Decidir_Admin_Navigation_Page_Promotions`
	 * @var WC_Decidir_Admin_Navigation_Page_Promotions|null
	 */
	private $promotions = null;

	/**
	 * Holds an instance of `WC_Decidir_Admin_Navigation_Page_Status`
	 * @var WC_Decidir_Admin_Navigation_Page_Status|null
	 */
	private $status = null;

	/**
	 *
	 */
	public function __construct() {
		$this->process_navigation_includes();

		add_action( 'admin_menu', array( $this, 'register_pages' ) );
	}

	/**
	 * Includes admin pages
	 */
	public static function process_navigation_includes() {
		require_once WC_DECIDIR_ABSPATH . 'includes/admin/class-decidir-admin-page-banks.php';
		require_once WC_DECIDIR_ABSPATH . 'includes/admin/class-decidir-admin-page-cards.php';
		require_once WC_DECIDIR_ABSPATH . 'includes/admin/class-decidir-admin-page-promotions.php';
		require_once WC_DECIDIR_ABSPATH . 'includes/admin/class-decidir-admin-page-status.php';
	}

	/**
	 * Renders the Bank Page
	 */
	public function render_banks_page()
	{
		return $this->banks->render();
	}

	/**
	 * Renders the Cards Page
	 */
	public function render_cards_page()
	{
		return $this->cards->render();
	}

	/**
	 * Renders the Cards Page
	 */
	public function render_promotions_page()
	{
		return $this->promotions->render();
	}

	/**
	 * Renders the Cards Page
	 */
	public function render_status_page()
	{
		return $this->status->render();
	}

	/**
	 * Initializes menu items and page renderers
	 */
	public function register_pages() {
		$this->banks = new WC_Decidir_Admin_Navigation_Page_Banks();
		$this->cards = new WC_Decidir_Admin_Navigation_Page_Cards();
		$this->promotions = new WC_Decidir_Admin_Navigation_Page_Promotions();
		$this->status = new WC_Decidir_Admin_Navigation_Page_Status();

		add_menu_page(
			'Decidir',
			'Decidir',
			'manage_options',
			'decidir_admin_dashboard',
			function () {},
			'dashicons-lock',
			58
		);

		add_submenu_page(
			'decidir_admin_dashboard',
			'Manage Promotions',
			'Manage Promotions',
			'manage_options',
			'decidir_admin_promotions',
			array( $this, 'render_promotions_page' )
		);

		add_submenu_page(
			'decidir_admin_dashboard',
			'Manage Banks',
			'Manage Banks',
			'manage_options',
			'decidir_admin_banks',
			array( $this, 'render_banks_page' )
		);

		add_submenu_page(
			'decidir_admin_dashboard',
			'Manage Cards',
			'Manage Cards',
			'manage_options',
			'decidir_admin_cards',
			array( $this, 'render_cards_page' )
		);

		add_submenu_page(
			'decidir_admin_dashboard',
			'Status',
			'Status',
			'manage_options',
			'decidir_admin_status',
			array( $this, 'render_status_page' )
		);

		// removes the duplicated item that `add_menu_page` generates
		remove_submenu_page('decidir_admin_dashboard', 'decidir_admin_dashboard');

		return apply_filters( 'decidir_admin_navigation_sections', array() );
	}
}
