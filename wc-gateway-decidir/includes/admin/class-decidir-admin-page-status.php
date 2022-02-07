<?php
/**
 *
 */

defined( 'ABSPATH' ) || exit;

use Automattic\Jetpack\Constants;

/**
 *
 */
class WC_Decidir_Admin_Navigation_Page_Status {

	public function render() {
		$version   = Constants::get_constant( 'WC_VERSION' );
		wp_register_style(
			'woocommerce_admin_styles',
			WC()->plugin_url() . '/assets/css/admin.css',
			array(),
			$version
		);

		wp_enqueue_style( 'woocommerce_admin_styles' );

		return self::output();
	}

	/**
	* Handles output of the status page
	*/
	public static function output() {
		$plugin = array(
			'version' => wc_decidir_get_version(),
			'sdk_version' => wc_decidir_get_sdk_version(),
			'cb_version' => wc_decidir_get_version_in_codebase(),
			'cb_sdk_version' => wc_decidir_get_sdk_version_in_codebase()
		);

		include_once WC_DECIDIR_ABSPATH . 'includes/admin/html-decidir-admin-page-status.php';
	}
}
