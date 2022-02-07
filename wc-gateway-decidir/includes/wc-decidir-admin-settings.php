<?php
/**
 *
 *
 */

defined( 'ABSPATH' ) || exit;

return array(
	WC_Decidir_Config_Interface::FIELD_ENABLED => array(
		'title'			=> __('Status', 'wp-gateway-decidir'),
		'label'			=> __('enable/disable payment gateway', 'wp-gateway-decidir'),
		'type'			=> 'checkbox',
		'description'	=> '',
		'default'		=> 'no'
	),
	WC_Decidir_Config_Interface::FIELD_GATEWAY_TITLE => array(
		'title'			=> __('Title', 'decidir_gateway'),
		'type'			=> 'text',
		'description'	=> 'Payment method title that will be displayed during Checkout.',
		'desc_tip'		=> true,
		'default'		=> 'Credit Card'
	),
	WC_Decidir_Config_Interface::FIELD_GATEWAY_DESCRIPTION => array(
		'title'	   		=> __('Description', 'decidir_gateway'),
		'type'			=> 'textarea',
		'description' 	=> 'This controls the description which the user sees during checkout.',
		'default'	 	=> 'Pay thought PRISMA Decidir with your credit card right from the store.'
	),
	WC_Decidir_Config_Interface::FIELD_SANDBOX_MODE => array(
		'title'			=> __('Sandbox Mode', 'decidir_gateway'),
		'label'			=> __('Enable test mode', 'decidir_gateway'),
		'type'			=> 'checkbox',
		'description'	=> 'Gateway will place all the transactions against the Sandbox environment using the test credentials.',
		'desc_tip'		=> true,
		'default'		=> 'yes'
	),
	WC_Decidir_Config_Interface::FIELD_CYBERSOURCE_ENABLED => array(
		'title'			=> __('Use Cybersource', 'decidir_gateway'),
		'label'			=> __('Enable Cybersource', 'decidir_gateway'),
		'type'			=> 'checkbox',
		'description'	=> __('Use Cybersource for all transactions', 'decidir_gateway'),
		'desc_tip'		=> true,
		'default'		=> 'yes'
	),
	WC_Decidir_Config_Interface::FIELD_DEBUG_ENABLED => array(
		'title'			=> __('Debug Mode', 'decidir_gateway'),
		'label'			=> __('Enable debug log', 'decidir_gateway'),
		'type'			=> 'checkbox',
		'description'	=>  sprintf(
			__('Records information for debug purposes <small><a href="%s">WooCommerce -> Status -> Logs</a></small>', 'decidir_gateway'),
			admin_url('admin.php?page=wc-status&tab=logs')
		),
		'default'		=> 'yes'
	),
	WC_Decidir_Config_Interface::FIELD_SANDBOX_SITE_ID => array(
		'title'			=> __('Sandbox Site Id', 'decidir_gateway'),
		'type'			=> 'text',
		'description'	=> __('Enter your Sandbox site id', 'decidir_gateway'),
		'desc_tip'		=> true,
		'default'		=> ''
	),
	WC_Decidir_Config_Interface::FIELD_SANDBOX_PUBLIC_KEY => array(
		'title' 		=> __('Sandbox Public Key', 'decidir_gateway'),
		'type'  		=> 'text',
		'default'		=> ''
	),
	WC_Decidir_Config_Interface::FIELD_SANDBOX_PRIVATE_KEY => array(
		'title' 		=> 'Sandbox Private Key',
		'type'  		=> 'password',
		'default'		=> ''
	),
	WC_Decidir_Config_Interface::FIELD_PRODUCTION_SITE_ID => array(
		'title'			=> __('Production Site Id', 'decidir_gateway'),
		'type'			=> 'text',
		'description'	=> __('Enter your Production Site Id', 'decidir_gateway'),
		'desc_tip'		=> true,
		'default'		=> ''
	),
	WC_Decidir_Config_Interface::FIELD_PRODUCTION_PUBLIC_KEY => array(
		'title' 		=> __('Production Public Key', 'decidir_gateway'),
		'type'  		=> 'text',
		'description'	=> __('Enter your Production Public Key', 'decidir_gateway'),
		'default'		=> ''
	),
	WC_Decidir_Config_Interface::FIELD_PRODUCTION_PRIVATE_KEY => array(
		'title'			=> __('Production Private Key', 'decidir_gateway'),
		'type'			=> 'password',
		'description'	=> __('Enter your Production Private Key', 'decidir_gateway'),
		'default'		=> ''
	)
);
