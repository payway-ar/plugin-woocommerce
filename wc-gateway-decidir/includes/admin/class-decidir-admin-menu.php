<?php
/**
 * @author IURCO - Prisma SA
 * @copyright Copyright © 2022 IURCO and PRISMA. All rights reserved.
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

		$main_icon = base64_encode('<svg width="45" height="45" xmlns="http://www.w3.org/2000/svg" xmlns:svg="http://www.w3.org/2000/svg"><g class="layer"><title>payway</title><g id="svg_57"><path d="m13.02,11.7l-8.29,-8.29c-0.21,-0.21 -0.5,-0.34 -0.8,-0.35c-0.15,0 -0.31,0.02 -0.46,0.08c-0.15,0.06 -0.28,0.14 -0.39,0.25c-0.12,0.12 -0.21,0.25 -0.27,0.39c-0.06,0.15 -0.09,0.31 -0.09,0.46l0,16.59c0,0.16 0.03,0.32 0.09,0.46c0.06,0.15 0.15,0.28 0.27,0.39c0.11,0.11 0.24,0.2 0.39,0.25c0.15,0.06 0.31,0.09 0.46,0.08c0.3,-0.01 0.59,-0.13 0.8,-0.34l8.29,-8.3c0.11,-0.11 0.2,-0.24 0.26,-0.38c0.06,-0.14 0.09,-0.3 0.09,-0.45c0,-0.16 -0.03,-0.31 -0.09,-0.45c-0.06,-0.15 -0.15,-0.28 -0.26,-0.39z" fill="#0DA9A8" id="svg_1"/><path d="m40.96,29.05l-9.67,-9.67l-6.27,6.27l13.95,13.95c0.16,0.17 0.37,0.28 0.6,0.32c0.22,0.05 0.46,0.03 0.67,-0.06c0.22,-0.09 0.4,-0.24 0.53,-0.43c0.13,-0.19 0.19,-0.42 0.19,-0.65l0,-9.73z" fill="#FFD400" id="svg_2"/><path d="m22.45,10.55c-0.11,-0.11 -0.24,-0.2 -0.38,-0.26c-0.14,-0.06 -0.29,-0.09 -0.45,-0.09c-0.15,0 -0.31,0.03 -0.45,0.09c-0.14,0.06 -0.27,0.15 -0.38,0.26l-17.73,17.71c-0.11,0.11 -0.19,0.24 -0.25,0.38c-0.06,0.15 -0.09,0.3 -0.09,0.45c0,0.16 0.03,0.31 0.09,0.45c0.06,0.15 0.14,0.28 0.25,0.39l4.61,4.61c0.22,0.22 0.52,0.34 0.84,0.34c0.31,0 0.61,-0.12 0.83,-0.34l11.45,-11.45c0.22,-0.22 0.52,-0.35 0.83,-0.35c0.31,0 0.61,0.13 0.83,0.35l2.57,2.56l6.27,-6.27l-8.84,-8.83z" fill="#E5124C" id="svg_3"/><path d="m38.95,11.72l-7.66,7.66l9.67,9.67l0,-16.5c0,-0.23 -0.07,-0.46 -0.19,-0.65c-0.13,-0.2 -0.32,-0.35 -0.53,-0.44c-0.22,-0.09 -0.46,-0.11 -0.69,-0.07c-0.23,0.05 -0.44,0.16 -0.6,0.33z" fill="#00387B" id="svg_4"/></g></g></svg>');

		add_menu_page(
			'payway',
			'payway',
			'manage_options',
			'decidir_admin_dashboard',
			function () {},
			'data:image/svg+xml;base64,' . $main_icon,
			58
		);

		add_submenu_page(
			'decidir_admin_dashboard',
			__('Manage Promotions', 'wc-gateway-decidir' ),
			__('Manage Promotions', 'wc-gateway-decidir' ),
			'manage_options',
			'decidir_admin_promotions',
			array( $this, 'render_promotions_page' )
		);

		add_submenu_page(
			'decidir_admin_dashboard',
			__( 'Manage Banks', 'wc-gateway-decidir' ),
			__( 'Manage Banks', 'wc-gateway-decidir' ),
			'manage_options',
			'decidir_admin_banks',
			array( $this, 'render_banks_page' )
		);

		add_submenu_page(
			'decidir_admin_dashboard',
			__( 'Manage Cards', 'wc-gateway-decidir' ),
			__( 'Manage Cards', 'wc-gateway-decidir' ),
			'manage_options',
			'decidir_admin_cards',
			array( $this, 'render_cards_page' )
		);

		add_submenu_page(
			'decidir_admin_dashboard',
			__( 'Status', 'wc-gateway-decidir' ),
			__( 'Status', 'wc-gateway-decidir' ),
			'manage_options',
			'decidir_admin_status',
			array( $this, 'render_status_page' )
		);

		// removes the duplicated item that `add_menu_page` generates
		remove_submenu_page('decidir_admin_dashboard', 'decidir_admin_dashboard');

		return apply_filters( 'decidir_admin_navigation_sections', array() );
	}
}
