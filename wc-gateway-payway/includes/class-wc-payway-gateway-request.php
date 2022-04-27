<?php
/**
 * @author IURCO - Prisma SA
 * @copyright Copyright © 2022 IURCO and PRISMA. All rights reserved.
 */

use Automattic\WooCommerce\Utilities\NumberUtil;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Generates requests to send
 */
class WC_Payway_Request {

	/**
     * @var string
     */
    const CS_DECISION = 'decision';

    /**
     * @var string
     */
    const STATUS = 'status';

    /**
     * @var string
     */
    const CS_FRAUD_DETECTION = 'fraud_detection';
	/**
     * @var array[]
     */
    const CS_DECISION_SUCCESS_VALUES = [
        WC_Payway_Cybersource_Validator_Interface::DECISION_GREEN,
        WC_Payway_Cybersource_Validator_Interface::DECISION_YELLOW
    ];

	/**
     * <code>
     * 400  malformed_request_error Error en el armado del json
     * 401  authentication_error    ApiKey Inválido
     * 402  invalid_request_error   Error por datos inválidos
     * 404  not_found_error         Error con datos no encontrados
     * 409  api_error               Error inesperado en la API REST
     * </code>
     *
     * @var array
     */
    const ERROR_CODES = [
        'unknown_error' => 0,
        'malformed_request_error' => 400,
        'authentication_error' => 401,
        'invalid_request_error' => 402,
        'not_found_error' => 404,
        'api_error' => 409
    ];

	/**
	 * Pointer to gateway making the request.
	 *
	 * @var WC_Payway_Api_Handler
	 */
	protected $api;

	/**
	 * @var int[]
	 */
	protected $error_codes = array();

	/**
	 * @var string[]
	 */
	protected $error_messages = array();

	/**
	 * @var bool
	 */
	protected $success = false;

	/**
	 * if payment gets approved, then this will filled
	 * with the Payway Transaction ID
	 *
	 * @var int
	 */
	protected $transaction_id = 0;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->api = new WC_Payway_Api_Handler();
	}

	public function pay( $payment_data ) {
		try {
			/** @var WC_Payway_Logger */
			$logger = wc_payway_get_logger();
			$logger->debug( print_r($payment_data, true) );

			/** @var \Decidir\Payment\PaymentResponse $result */
			$result = $this->api->post_payment( $payment_data );
			$logger->debug( print_r($result->getDataField(), true) );

			$this->set_result( $result->getDataField() );

			return $this->process_response( $result );

		} catch (\Decidir\Exception\SdkException $exception) {
			$this->set_success( false );
			$this->set_error_codes( $exception->getCode() );
			$this->set_error_messages( $this->extract_exception_message( $exception ) );

			$logger->error( '\Decidir\Exception\SdkException catch' );
			$logger->error( print_r($exception->getMessage(), true) );
			$logger->error( print_r($exception->getData(), true) );

			// We'll throw again so plugin can cancel the order
			throw $exception;

		} catch (\Exception $exception) {
			$this->set_success( false );
			$this->set_error_codes( $exception->getCode() );
			$this->set_error_messages( array( $exception->getMessage() ) );

			$logger->error( '\Exception catch' );
			$logger->error( print_r($exception->getMessage(), true) );

			// We'll throw again so plugin can cancel the order
			throw $exception;
		}

		return $this;
	}

	// private function process_exception_response( $exception ) {
	// 	return $this->process_response(
	// 		new Decidir\Data\AbstractData( $exception->getData() )
	// 	);
	// }

	/**
	 * Retrieves the exact error from the exception
	 *
	 * @param \Decidir\Exception\SdkException $exception
	 * @return string
	 */
	private function extract_exception_message( $exception ) {
		$message = '';
		$error_type = '';
		$data = $exception->getData();

		if ( isset( $data['error_type'] )) {
			$error_type = $data['error_type'];
		}

		if ( isset($data['validation_errors']) && is_array( $data['validation_errors'] ) ) {
			$item = $data['validation_errors'][0];
			$code = isset( $item['code'] )
				? $item['code']
				: '';
			$param = isset( $item['param'] )
				? $item['param']
				: '';

			return $error_type . ': ' . $item['code'] . ' ' . $item['param'];
		}

		return json_encode( $exception->getData() );
	}

	/**
	 *
	 * @param \Decidir\Payment\PaymentResponse $response
	 * @return $this
	 */
	private function process_response( $response ) {
		$data = $response->getDataField();

		$this->validate( $data );

		if ( $this->get_success()) {
			if ( isset($data['id']) ) {
				$this->set_transaction_id( $data['id'] );
			}
		}

		return $this;
	}

	/**
	 * @param array $response_data
	 * @return $this
	 */
	private function validate( $response_data ) {

		$validator_methods = array(
			'response_validator',
			'payment_validator'
		);

		// validate cybersource if enabled
		if ( wc_payway_config_is_cs_enabled() ) {
			$validator_methods[] = 'cs_retail_validator';
		}

		foreach ($validator_methods as $method) {
			$result = $this->$method( $response_data );

			if ( ! $result->is_valid ) {
				$this->set_success( false );
				$this->set_error_codes( $result->error_codes );
				$this->set_error_messages( $result->error_messages );

				return $this;
			}
		}

		// $result = $this->response_validator( $response_data );
		// if ( ! $result->is_valid ) {
		// 	$this->set_success( false );
		// 	$this->set_error_codes( $result->error_codes );
		// 	$this->set_error_messages( $result->error_messages );
		//
		// 	return $this;
		// }
		//
		// $result = $this->payment_validator( $response_data );
		// if ( ! $result->is_valid ) {
		// 	$this->set_success( false );
		// 	$this->set_error_codes( $result->error_codes );
		// 	$this->set_error_messages( $result->error_messages );
		//
		// 	return $this;
		// }

		$this->set_success( true );
		$this->set_error_codes( array() );
		$this->set_error_messages( array() );
		return $this;
	}

	/**
	 * Checks if general `validation_errors` has occured
	 *
	 * @param array $data
	 * @return stdClass
	 */
	private function response_validator( $data ) {

		$is_valid = true;
		$error_messages = array();
		$error_codes = array();

		// @TODO add validation when response does not contain anything else than a message

		// append the rest of the incoming error data
		if (isset($data['validation_errors'])) {
			// SDK does not returns an object when request fails
			// if `error_type` is present request has failed
			$is_valid = false;
			$error_messages[] = 'Gateway Error';
			$error_messages[] .= ' ' . json_encode($data['validation_errors']);

			// validate first position due SDK response format
			$error_codes[] = isset($data['validation_errors'][0]['code'])
				? $data['validation_errors'][0]['code']
				: self::ERROR_CODES['unknown_error'];
		}

		return $this->validator_create_result(
			$is_valid,
			$error_messages,
			$error_codes
		);
	}

	/**
	 * Checks for a status detail error in the given array
	 *
	 * @param array $data
	 * @return stdClass
	 */
	private function payment_validator( $data ) {

		$is_valid = true;
		$error_messages = array();
		$error_codes = array();

		if (isset($data['status_details']['error']['type'])) {
			$is_valid = false;
			$error_messages[] = $data['status_details']['error']['reason']['description'];
			$error_codes[] = $data['status_details']['error']['reason']['id'];
		}

		return $this->validator_create_result(
			$is_valid,
			$error_messages,
			$error_codes
		);
	}

	/**
	 * @param array $data
	 * @return stdClass
	 */
	private function cs_retail_validator( $data ) {

		$is_valid = true;
		$error_messages = array();
		$error_codes = array();

		$decision_result = $this->cs_validate_decision( $data );

		if ($decision_result['errors']) {
			$is_valid = false;
			$error_messages = $error_codes = array();
			$error_messages[] = $decision_result['description'];
			$error_codes[] = $decision_result['reason_code'];
		}

		return $this->validator_create_result(
			$is_valid,
			$error_messages,
			$error_codes
		);
	}

	/**
	 * Checks for Cybersource status color
	 *
	 * @see self::CS_DECISION_SUCCESS_VALUES
	 * @param array $data
	 * @return array
	 */
	private function cs_validate_decision( array $data ) {

		$decision = array();
		$decision['errors'] = false;
		$fraud_status_result = isset($data['fraud_detection']['status'])
			? $data['fraud_detection']['status']
			: array();

		// validate if transaction status and decision color are not valid in any combination
		if (
			isset($fraud_status_result['decision'])
			&& !in_array($fraud_status_result['decision'], self::CS_DECISION_SUCCESS_VALUES)
		) {
			$decision['errors'] = true;
			$decision['decision'] = $fraud_status_result['decision'];
			$decision['description'] = $fraud_status_result['description'];
			$decision['reason_code'] = $fraud_status_result['reason_code'];
		}

		return $decision;
	}

	/**
     * Factory method
     *
     * @param bool $is_valid
     * @param array $messages
     * @param array $error_codes
     * @return stdClass
     */
	private function validator_create_result(
		$is_valid,
		array $messages = [],
		array $error_codes = []
	) {
		$result = new stdClass();
		$result->is_valid = (bool) $is_valid;
		$result->error_messages = $messages;
		$result->error_codes = $error_codes;
		return $result;
	}

	/**
	 * @return int[]
	 */
	public function get_error_codes() {
		return $this->error_codes;
	}

	/**
	 * @param int[] $codes
	 * @return $this
	 */
	public function set_error_codes( $codes ) {
		$this->error_codes = $codes;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function get_success() {
		return $this->success;
	}

	/**
	 *
	 * @param bool $status
	 * @return $this
	 */
	public function set_success( $status ) {
		$this->success = $status;
		return $this;
	}

	/**
	 * @return array
	 */
	public function get_error_messages() {
		return $this->error_messages;
	}

	/**
	 * @param array $message
	 * @return $this
	 */
	public function set_error_messages( $messages ) {
		$this->error_messages = $messages;
		return $this;
	}

	public function get_transaction_id() {
		return $this->transaction_id;
	}

	public function set_transaction_id( $transaction_id ) {
		$this->transaction_id = $transaction_id;
		return $this;
	}

	/**
	 * Only gets filled when request ends without exceptions
	 *
	 * @return $this
	 */
	public function get_result() {
		return $this->result;
	}

	/**
	 * Only gets called when request ends without exceptions
	 *
	 * @param array $result
	 * @return $this
	 */
	public function set_result( $result ) {
		$this->result = $result;
		return $this;
	}

}
