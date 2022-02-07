<?php
/**
 * Decidir Activation class
 *
 * Inspired on MailChimp_WooCommerce_Activator class
 * @see https://github.com/mailchimp/mc-woocommerce
 */

// do not allow this file to be called directly
defined( 'ABSPATH' ) || exit;

/**
 * Activator class
 */
class WC_Decidir_Activator implements WC_Decidir_Activator_Interface {

	/**
	 * Returns Decidir Gateway configuration options
	 * if not set, `get_option` returns `false`
	 *
	 * @return array|bool
	 */
	 private static function wc_decidir_get_config_options() {
		return get_option( 'woocommerce_decidir_gateway_settings' );
	}

	/**
	 * Returns specific option value
	 *
	 * @param string $name config option to retrieve
	 * @return mixed
	 */
	private static function wc_decidir_get_config_option( $name ) {
		$options = static::wc_decidir_get_config_options();

		return ($options && isset($options[$name]))
			? $options[$name]
			: false;
	}

	// If the plugin is active, we're good.
	public static function wc_decidir_is_woocommerce_plugin_installed() {
		$is_woocommerce_installed = in_array(
			'woocommerce/woocommerce.php',
			apply_filters( 'active_plugins', get_option('active_plugins'))
		);

		if ($is_woocommerce_installed) {
			return true;
		}

		return false;
	}

	/**
	 * Checks and disable plugin if WooCommerce isn't installed
	 */
	public static function check_woocommerce_install() {

		if ( ! static::wc_decidir_is_woocommerce_plugin_installed()) {
			// Deactivate the plugin
			if ( is_plugin_active( WC_DECIDIR_PLUGIN_FILE )) {
				deactivate_plugins( array( WC_DECIDIR_PLUGIN_FILE));
			}

			add_action( 'admin_notices', array( __FILE__, 'display_deactivation_notice'));
			return false;
		}

		return true;
	}

	public static function display_deactivation_notice() {
		$class = 'notice notice-error error';
		$message = __('<div class="error">Decidir Payment Gateway requires the <a href="http://wordpress.org/extend/plugins/woocommerce/">WooCommerce</a> plugin to run.</div>', 'decidir_gateway');
		printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );
	}

	/**
	 * Hook when plugin is activated
	 */
	public static function activate() {
		if ( ! static::check_woocommerce_install() ) {
			return false;
		}

		$current_version = get_option( 'decidir_gateway_version', false );
		$current_sdk_version = get_option( 'decidir_gateway_sdk_version', false );

		// fresh install
		if (empty($current_version)) {
			static::decidir_clean_config_options();

			// if we don't find the version in database,
			// then set the ones in the codebase
			update_option( 'decidir_gateway_version', static::WC_DECIDIR_VERSION_VALUE );
			update_option( 'decidir_gateway_sdk_version', static::WC_DECIDIR_SDK_VERSION_VALUE );

			// Create core tables
			static::create_core_tables();
		}

		// if any of the versions isn't the same, then just go and sync'em all
		if ( $current_version < static::WC_DECIDIR_VERSION_VALUE ) {
			self::update_plugin_db_versions();
		}
	}

	/**
	 * Hook for deactivation process
	 */
	public static function deactivate() {}

	/**
	* Hook for uninstall process
	*/
	public static function uninstall() {}

	/**
	 * Updates version config options in the database
	 * with the latest codebase
	 *
	 * @see DecidirWC::define_constants()
	 */
	public static function update_plugin_db_versions() {
		update_option( 'decidir_gateway_version', static::WC_DECIDIR_VERSION_VALUE );
		update_option( 'decidir_gateway_sdk_version', static::WC_DECIDIR_SDK_VERSION_VALUE );
	}

	/**
	 * Removes all plugin configuration from `wp_options`
	 */
	public static function decidir_clean_config_options() {
		global $wpdb;

		// Delete pre-existing Plugin options
		// this shouldn't do anything in a fresh install
		// (we've already validated if there's a current version option)
		$plugin_options = $wpdb->get_results( "SELECT option_name FROM $wpdb->options WHERE option_name LIKE '%decidir%gateway%'" );

		foreach( $plugin_options as $option ) {
			delete_option( $option->option_name );
		}
	}

	/**
	 * Removes all data from custom tables
	 */
	public static function decidir_flush_core_tables() {
		try {
			global $wpdb;

			$truncate_banks = "TRUNCATE {$wpdb->prefix}" . static::TABLE_NAME_BANKS;
			$truncate_cards = "TRUNCATE {$wpdb->prefix}" . static::TABLE_NAME_CARDS;
			$truncate_promotions = "TRUNCATE {$wpdb->prefix}" . static::TABLE_NAME_PROMOTIONS;

			$wpdb->query($truncate_banks);
			$wpdb->query($truncate_cards);
			$wpdb->query($truncate_promotions);
		} catch (\Exception $e) {

		}
	}

	/**
	 * Creates core tables
	 */
	private static function create_core_tables() {
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		global $wpdb;

		$wpdb->hide_errors();
		$charset_collate = $wpdb->get_charset_collate();

		$query = static::get_create_banks_query();
		dbDelta( $query );
		$query = static::get_create_cards_query();
		dbDelta( $query );
		$query = static::get_create_promotions_query();
		dbDelta( $query );
	}

	/**
	 * Creates banks table
	 */
	private static function get_create_banks_query() {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();
		$table_banks = $wpdb->prefix . static::TABLE_NAME_BANKS;

		$sql = "CREATE TABLE IF NOT EXISTS $table_banks (
			id int(11) unsigned NOT NULL AUTO_INCREMENT,
			name varchar(150) NOT NULL,
			logo varchar(150) DEFAULT NULL,
			created_at datetime NOT NULL,
			PRIMARY KEY  (id)
		) $charset_collate;";

		return $sql;
	}

	/**
	 * Creates Cards table
	 */
	private static function get_create_cards_query() {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();
		$table_cards = $wpdb->prefix . static::TABLE_NAME_CARDS;

		$sql = "CREATE TABLE IF NOT EXISTS $table_cards (
			id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
			card_name varchar(150) NOT NULL,
			id_sps int(11) UNSIGNED NOT NULL,
			id_nps int(11) UNSIGNED NOT NULL,
			logo text,
			created_at datetime NOT NULL,
			PRIMARY KEY  (id)
		) $charset_collate;";

		return $sql;
	}

	/**
	 * Creates Promotions table
	 */
	private static function get_create_promotions_query() {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();
		$table_promotions = $wpdb->prefix . static::TABLE_NAME_PROMOTIONS;
		$table_banks = $wpdb->prefix . static::TABLE_NAME_BANKS;
		$table_cards = $wpdb->prefix . static::TABLE_NAME_CARDS;

		$sql = "CREATE TABLE IF NOT EXISTS $table_promotions (
			id int(11) unsigned NOT NULL AUTO_INCREMENT,
			rule_name varchar(150) NOT NULL,
			card_id int(11) unsigned NOT NULL,
			bank_id int(11) unsigned NOT NULL,
			from_date timestamp NOT NULL DEFAULT current_timestamp(),
			to_date timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
			priority int(2) unsigned DEFAULT NULL,
			is_active tinyint(1) NOT NULL DEFAULT 1,
			applicable_days varchar(50) NOT NULL,
			fee_plans text DEFAULT NULL,
			PRIMARY KEY  (`id`),
			CONSTRAINT `wp_prisma_promotions_bank_FK` FOREIGN KEY (bank_id) REFERENCES {$table_banks} (id) ON DELETE CASCADE ON UPDATE CASCADE,
			CONSTRAINT `wp_prisma_promotions_card_FK` FOREIGN KEY (card_id) REFERENCES {$table_cards} (id) ON DELETE CASCADE ON UPDATE CASCADE
		) $charset_collate;";
		return $sql;
	}
}
