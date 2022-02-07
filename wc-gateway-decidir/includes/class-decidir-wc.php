<?php
/**
 * @author IURCO - Prisma SA
 * @copyright Copyright © 2022 IURCO and PRISMA. All rights reserved.
 */

defined( 'ABSPATH' ) || exit;

final class DecidirWC {
	/**
	 * The single instance of the class
	 *
	 * @var DecidirWC
	 */
	protected static $instance = null;

	/**
	 * @var Decidir_WC_Promotion_Factory
	 */
	public $promotion = null;

	/**
	 * @var Decidir_WC_Bank_Factory
	 */
	public $bank = null;

	/**
	 * @var Decidir_WC_Card_Factory
	 */
	public $card = null;

	/**
	 *
	 */
	public function __construct() {
		$this->define_constants();
		$this->includes();
		$this->init_hooks();

		if ( is_admin() ) {
			add_action('init', array($this, 'init_admin_menu'));
		}
	}

	/**
	 * Cloning is forbidden.
	 */
	public function __clone() {
		wc_doing_it_wrong( __FUNCTION__, __( 'Cloning is forbidden.', 'wc-gateway-decidir' ), '0.1.0' );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 */
	public function __wakeup() {
		wc_doing_it_wrong( __FUNCTION__, __( 'Unserializing instances of this class is forbidden.', 'wc-gateway-decidir' ), '0.1.0' );
	}

	/**
	 * Main Decidir Instance.
	 *
	 * @static
	 * @return DecidirWC - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	* Adds the Payment Gateway into WooCommerce gateways' pool
	* @see WC filter `woocommerce_payment_gateways`
	*/
	public function decidir_add_gateway_class( $gateways ) {
		if ( ! class_exists( 'WC_Payment_Gateway' ) ) {
			return;
		}

		if ( ! class_exists( 'WC_Payment_Gateway_Decidir' ) ) {
			require_once dirname( WC_DECIDIR_PLUGIN_FILE ) . '/wc-payment-gateway-decidir.php';
		}

		$gateways[] = 'WC_Payment_Gateway_Decidir';
		return $gateways;
	}

	/**
	 * Defines global constants used across the entire plugin
	 */
	private function define_constants() {
		$upload_dir = wp_upload_dir( null, false );

		$this->define( 'WC_DECIDIR_PLUGIN_VERSION', wc_decidir_get_version() );
		$this->define( 'WC_DECIDIR_PLUGIN_SDK_VERSION', wc_decidir_get_sdk_version() );
		$this->define( 'WC_DECIDIR_PLUGIN_VERSION_CODEBASE', wc_decidir_get_version_in_codebase() );
		$this->define( 'WC_DECIDIR_PLUGIN_SDK_VERSION_CODEBASE', wc_decidir_get_sdk_version_in_codebase() );
		$this->define( 'WC_DECIDIR_ABSPATH', dirname( WC_DECIDIR_PLUGIN_FILE ) . '/' );
	}

	public function init_admin_menu() {
		// Admin menu initialization
		new WC_Decidir_Admin_Menu;
	}

	/**
	 * Define constant if not already set.
	 *
	 * @param string $name  Constant name.
	 * @param string|bool $value Constant value.
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	/**
	 * Includes required classes
	 */
	private function includes() {
		// Core classes
		require_once WC_DECIDIR_ABSPATH . 'includes/class-decidir-logger.php';
		require_once WC_DECIDIR_ABSPATH . 'includes/class-decidir-activator.php';

		// Interfaces classes
		require_once WC_DECIDIR_ABSPATH . 'includes/interfaces/class-wc-decidir-meta-interface.php';

		// Factory classes
		require_once WC_DECIDIR_ABSPATH . 'includes/class-decidir-core-functions.php';
		require_once WC_DECIDIR_ABSPATH . 'includes/class-decidir-bank-factory.php';
		require_once WC_DECIDIR_ABSPATH . 'includes/class-decidir-card-factory.php';
		require_once WC_DECIDIR_ABSPATH . 'includes/class-decidir-promotion-factory.php';

		// Core functions
		require_once WC_DECIDIR_ABSPATH . 'includes/class-decidir-core-functions.php';

		// Admin menu creation
		if ( is_admin() ) {
			require_once WC_DECIDIR_ABSPATH . 'includes/admin/class-decidir-admin-menu.php';
		}
	}

	/**
	 * Process all required files to execute a Payment Request against Decidir SDK
	 *
	 * @return void
	 */
	public function init_decidir_request_includes() {
		// Interfaces
		require_once WC_DECIDIR_ABSPATH . 'includes/interfaces/class-wc-decidir-config-interface.php';
		require_once WC_DECIDIR_ABSPATH . 'includes/interfaces/class-wc-decidir-rest-interface.php';
		require_once WC_DECIDIR_ABSPATH . 'includes/interfaces/class-wc-decidir-request-processor-interface.php';
		require_once WC_DECIDIR_ABSPATH . 'includes/interfaces/class-wc-decidir-cybersource-validator-interface.php';

		// Request and builder classes
		require_once WC_DECIDIR_ABSPATH . 'includes/class-wc-decidir-gateway-request-builder.php';
		require_once WC_DECIDIR_ABSPATH . 'includes/class-wc-decidir-gateway-request.php';
		require_once WC_DECIDIR_ABSPATH . 'includes/class-wc-decidir-gateway-api-handler.php';

		// Request Processors
		require_once WC_DECIDIR_ABSPATH . 'includes/processors/class-wc-decidir-request-token.php';
		require_once WC_DECIDIR_ABSPATH . 'includes/processors/class-wc-decidir-request-general.php';
		require_once WC_DECIDIR_ABSPATH . 'includes/processors/class-wc-decidir-request-customer.php';
		require_once WC_DECIDIR_ABSPATH . 'includes/processors/class-wc-decidir-request-order.php';
		require_once WC_DECIDIR_ABSPATH . 'includes/processors/class-wc-decidir-request-payment.php';
		require_once WC_DECIDIR_ABSPATH . 'includes/processors/class-wc-decidir-request-cybersource.php';
		require_once WC_DECIDIR_ABSPATH . 'includes/processors/class-wc-decidir-request-subpayments.php';
	}

	/**
	 * Loads languages for this plugin
	 */
	public function load_localization_files() {
		load_plugin_textdomain(
			'wc-gateway-decidir',
			false,
			dirname( plugin_basename( WC_DECIDIR_PLUGIN_FILE)) . '/i18n/languages'
		);
	}

	/**
	 * Hook into actions and filters.
	 */
	private function init_hooks() {
		// registers an action to process all language files
		add_action( 'before_wc_decidir_gateway_init', array( $this, 'load_localization_files' ));

		// adds Decidir into the Payment Gateways list
		add_filter( 'woocommerce_payment_gateways', array( $this, 'decidir_add_gateway_class'));

		// initialize dependencies
		add_action( 'init', array( $this, 'init' ), 0 );

		// expose custom meta box within Order Details
		add_action( 'woocommerce_admin_order_data_after_shipping_address', array( $this, 'render_order_payment_details') );
	}

	/**
	 * TODO: this is currently required, cause they register the custom filters.
	 * refactor to a better architecture.
	 *
	 * @see self::init_hooks()
	 * @see wc_decidir_gateway_admin_xxx_create
	 * @see wc_decidir_gateway_admin_xxx_update
	 */
	public function init() {
		do_action( 'before_wc_decidir_gateway_init' );

		$this->bank = new WC_Decidir_Bank_Factory();
		$this->card = new WC_Decidir_Card_Factory();
		$this->promotion = new WC_Decidir_Promotion_Factory();
	}

	/**
	 * Renders custom information within the WooCommerce Order view screen
	 * TODO: convert into a meta box and move to it's own class
	 *
	 * @param WC_Order $order
	 * @return string
	 */
	public function render_order_payment_details( $order ) {
		// ensure the current Order was payed through Decidir
		if ( wc_decidir_get_payment_code() !== $order->get_payment_method() ) {
			return;
		}

		$prefix = WC_Decidir_Meta_Interface::PREFIX;
		$trans_id_meta = $prefix . WC_Decidir_Meta_Interface::TRANSACTION_ID;
		$site_trans_meta = $prefix . WC_Decidir_Meta_Interface::SITE_TRANSACTION_ID;
		$payment_data_meta = $prefix . WC_Decidir_Meta_Interface::PAYMENT_DATA;

		$decidir_transaction_id = get_post_meta( $order->get_id(), $trans_id_meta, true );
		$decidir_site_transaction_id = get_post_meta( $order->get_id(), $site_trans_meta, true );
		$payment_data = get_post_meta( $order->get_id(), $payment_data_meta, true );

		?>
			<h3><?php _e('Decidir Payment Information', 'wc-gateway-decidir'); ?></h3>
			<?php if ( $decidir_transaction_id ): ?>
			<div>
				<strong><?php _e('Trans. ID', 'wc-gateway-decidir'); ?>:</strong>
				<span><?php echo $decidir_transaction_id; ?></span>
			</div>
			<?php endif; ?>
			<?php if ( $decidir_site_transaction_id ): ?>
			<div>
				<strong><?php _e('Site Trans. ID', 'wc-gateway-decidir'); ?>:</strong>
				<span><?php echo $decidir_site_transaction_id; ?></span>
			</div>
			<?php endif; ?>
			<?php if ( !empty($payment_data) && isset($payment_data['status_details']) ): ?>
			<?php $status = $payment_data['status_details']; ?>
			<div>
				<strong><?php _e('Ticket', 'wc-gateway-decidir'); ?></strong>
				<span><?php echo $status['ticket']; ?></span>
			</div>
			<div>
				<strong><?php _e('CC Auth Code', 'wc-gateway-decidir'); ?></strong>
				<span><?php echo $status['card_authorization_code']; ?></span>
			</div>
			<div>
				<strong><?php _e('CC Address Code', 'wc-gateway-decidir'); ?></strong>
				<span><?php echo $status['address_validation_code']; ?></span>
			</div>
			<?php endif; ?>
			<?php if ( !empty($payment_data) && isset($payment_data['cybersource']) ): ?>
			<?php $cybersource = $payment_data['cybersource']; ?>
			<div>
				<strong><?php _e('CS Decision', 'wc-gateway-decidir'); ?></strong>
				<span><?php echo $cybersource['decision']; ?></span>
			</div>
			<?php endif; ?>
		<?php
	}
}
