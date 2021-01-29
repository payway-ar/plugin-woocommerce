<?php
namespace Decidir\Exception;

class RequiredValue extends \Decidir\Exception\SdkException {
	
	public function __construct($field) {
		$message = "Campo: " . $field . " es obligatorio.";
		$code = 99977;
		parent::__construct($message, $code, $field);
	}
}



