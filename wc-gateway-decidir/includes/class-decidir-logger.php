<?php
/**
 * @author IURCO - Prisma SA
 * @copyright Copyright © 2022 IURCO and PRISMA. All rights reserved.
 */

defined( 'ABSPATH' ) || exit;

/**
 * Cards factory class
 */
class WC_Decidir_Logger {

	/** @var string name to be used by WC for file creation */
	const LOG_SOURCE = 'decidir-gateway';

	/** @var WC_Logger */
	static $wc_logger;

	protected static $instance = null;

	/**
	 * Constructor function
	 */
	public function __construct() {
		self::$wc_logger = wc_get_logger();
	}

	/**
	 * Instance
	 *
	 * @static
	 * @return WC_Decidir_Logger_Transaction
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * @return WC_Logger
	 */
	public static function get_logger() {
		return self::$wc_logger;
	}

	/**
	 * @param string $message
	 * @return void
	 */
	public static function log( $message, $level = 'info' ) {
		self::get_logger()->log( $level, $message, array( 'source' => self::LOG_SOURCE ));
	}

	/**
	 *
	 * @param string $message
	 * @return void
	 */
	public static function info( $message ) {
		self::log( $message );
	}

	/**
	 *
	 * @param string $message
	 * @return void
	 */
	public static function debug( $message ) {
		self::log( $message, WC_Log_Levels::DEBUG );
	}

	/**
	 *
	 * @param string $message
	 * @return void
	 */
	public static function error( $message ) {
		self::log( $message, WC_Log_Levels::ERROR );
	}
}
