<?php
/**
 * @author IURCO - Prisma SA
 * @copyright Copyright © 2022 IURCO and PRISMA. All rights reserved.
 */

defined( 'ABSPATH' ) || exit;

return array(
	WC_Payway_Config_Interface::FIELD_ENABLED => array(
		'title'			=> __('Status', 'wc-gateway-payway'),
		'label'			=> __('Enabled?', 'wc-gateway-payway'),
		'type'			=> 'checkbox',
		'description'	=> '',
		'default'		=> 'no'
	),
	WC_Payway_Config_Interface::FIELD_GATEWAY_TITLE => array(
		'title'			=> __('Title', 'wc-gateway-payway'),
		'type'			=> 'text',
		'description'	=> __('Payment method title that will be displayed during Checkout.', 'wc-gateway-payway'),
		'desc_tip'		=> true,
		'default'		=> __('Credit Card', 'wc-gateway-payway')
	),
	WC_Payway_Config_Interface::FIELD_GATEWAY_DESCRIPTION => array(
		'title'	   		=> __('Description', 'wc-gateway-payway'),
		'type'			=> 'textarea',
		'description' 	=> __('This controls the description which the user sees during checkout.', 'wc-gateway-payway'),
		'default'	 	=> __('Pay thought Payway with your credit card right from the store.', 'wc-gateway-payway')
	),
	WC_Payway_Config_Interface::FIELD_SANDBOX_MODE => array(
		'title'			=> __('Sandbox Mode', 'wc-gateway-payway'),
		'label'			=> __('Enable test mode', 'wc-gateway-payway'),
		'type'			=> 'checkbox',
		'description'	=> __('Gateway will place all the transactions against the Sandbox environment using the test credentials.', 'wc-gateway-payway'),
		'desc_tip'		=> true,
		'default'		=> 'yes'
	),
	WC_Payway_Config_Interface::FIELD_CYBERSOURCE_ENABLED => array(
		'title'			=> __('Use Cybersource', 'wc-gateway-payway'),
		'label'			=> __('Enable Cybersource', 'wc-gateway-payway'),
		'type'			=> 'checkbox',
		'description'	=> __('Use Cybersource for all transactions', 'wc-gateway-payway'),
		'desc_tip'		=> true,
		'default'		=> 'yes'
	),
	WC_Payway_Config_Interface::FIELD_DEBUG_ENABLED => array(
		'title'			=> __('Debug Mode', 'wc-gateway-payway'),
		'label'			=> __('Enable debug log', 'wc-gateway-payway'),
		'type'			=> 'checkbox',
		'description'	=>  sprintf(
			/* translators: %s: URL for WooCommerce Logs tab */
			__('Records information for debug purposes <small><a href="%s">WooCommerce -> Status -> Logs</a></small>', 'wc-gateway-payway'),
			admin_url('admin.php?page=wc-status&tab=logs')
		),
		'default'		=> 'yes'
	),
	WC_Payway_Config_Interface::FIELD_SANDBOX_SITE_ID => array(
		'title'			=> __('Sandbox Site Id', 'wc-gateway-payway'),
		'type'			=> 'text',
		'description'	=> __('Enter your Sandbox site id', 'wc-gateway-payway'),
		'desc_tip'		=> true,
		'default'		=> ''
	),
	WC_Payway_Config_Interface::FIELD_SANDBOX_PUBLIC_KEY => array(
		'title' 		=> __('Sandbox Public Key', 'wc-gateway-payway'),
		'type'  		=> 'text',
		'default'		=> ''
	),
	WC_Payway_Config_Interface::FIELD_SANDBOX_PRIVATE_KEY => array(
		'title' 		=> __('Sandbox Private Key', 'wc-gateway-payway'),
		'type'  		=> 'password',
		'default'		=> ''
	),
	WC_Payway_Config_Interface::FIELD_PRODUCTION_SITE_ID => array(
		'title'			=> __('Production Site Id', 'wc-gateway-payway'),
		'type'			=> 'text',
		'description'	=> __('Enter your Production Site Id', 'wc-gateway-payway'),
		'desc_tip'		=> true,
		'default'		=> ''
	),
	WC_Payway_Config_Interface::FIELD_PRODUCTION_PUBLIC_KEY => array(
		'title' 		=> __('Production Public Key', 'wc-gateway-payway'),
		'type'  		=> 'text',
		'description'	=> __('Enter your Production Public Key', 'wc-gateway-payway'),
		'default'		=> ''
	),
	WC_Payway_Config_Interface::FIELD_PRODUCTION_PRIVATE_KEY => array(
		'title'			=> __('Production Private Key', 'wc-gateway-payway'),
		'type'			=> 'password',
		'description'	=> __('Enter your Production Private Key', 'wc-gateway-payway'),
		'default'		=> ''
	)
);
