<?php
/**
 * @author IURCO - Prisma SA
 * @copyright Copyright © 2022 IURCO and PRISMA. All rights reserved.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles response
 */
class WC_Gateway_Decidir_Response {

	const FRAUD_DETECTION_FIELD = 'fraud_detection';
	const FIELD_STATUS = 'status';
	const FIELD_DECISION = 'decision';
	const FIELD_REASON_CODE = 'reason_code';
	const FIELD_DESCRIPTION = 'description';
	const FIELD_REVIEW = 'review';

	protected $response;
	protected $status;
	protected $reason_code;

	/**
	 * The single instance of the class
	 *
	 * @var WC_Gateway_Decidir_Response
	 */
	protected static $_instance = null;

	/**
     * @var WC_Decidir_Response_Processor_Interface[]
     */
    private $handlers;

	public function __construct() {
		// $this->init_handlers();
	}

	/**
	 * Instance
	 *
	 * @static
	 * @return WC_Gateway_Decidir_Response
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	// private function init_handlers() {
	// 	$processors = array(
	// 		'payment-processor' => new WC_Decidir_Response_Payment_Processor,
	// 	);
	//
	// 	// if ( wc_decidir_config_is_cs_enabled() ) {
	// 	// 	$processors['cybersource-processor'] = new WC_Decidir_Response_CyberSource_Processor;
	// 	// }
	//
	// 	$this->handlers = $processors;
	// }

	/**
	 * Handles response
	 *
	 * @param array $data
	 * @return void
	 */
	// public function handle(array $data)
	// {
	// 	$response = array();
	//
	// 	foreach ($this->handlers as $handler) {
	// 		$handler->handle( $data, $response );
	// 	}
	//
	// 	$this->set_response( $response );
	// }

	/**
	 * @return string
	 */
	public function get_status() {
		return $this->status;
	}

	/**
	 * @return string
	 */
	public function get_decision() {
		return $this->decision;
	}

	/**
	 * @return int
	 */
	public function get_reason_code() {
		return $this->reason_code;
	}


}
