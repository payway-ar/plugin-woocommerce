<?php
/**
 *
 */

defined( 'ABSPATH' ) || exit;

$title = __('Status Page', 'decidir_gateway');
?>
<div class="wrap">
	<h1 class="wp-heading-inline">
		<?php echo esc_html( $title ); ?>
	</h1>
	<div class="notice notice-info">
		<p><?php echo __('Keep in mind Decidir Payment Gateway logs are integrated with WooCommerce. You\'ll find this under <strong>WooCommerce / Status / Logs</strong> tab. By switching the right dropdown menu, you\'ll see log files named `decidir-gateway-xxx.log`', 'decidir_gateway'); ?></p>
		<p>
			<a class="button-primary" href="<?php echo esc_url( admin_url( 'admin.php?page=wc-status&tab=logs' ) ); ?>"><?php echo __('Go to WooCommerce Logs', 'decidir_gateway'); ?></a>
		</p>
	</div>
	<table class="wc_status_table widefat" cellspacing="0" id="status">
		<thead>
			<tr>
				<th colspan="2"><h2><?php esc_html_e( 'Plugin Information', 'decidir_gateway' ); ?></h2></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><?php esc_html_e( 'Plugin Version', 'decidir_gateway' ); ?>:</td>
				<td><?php echo $plugin['version']
					? $plugin['version']
					: '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . __('No version found', 'decidir_gateway') . '</mark>'; ?></td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'Decidir SDK Version', 'decidir_gateway' ); ?>:</td>
				<td><?php echo $plugin['sdk_version']
					? $plugin['sdk_version']
					: '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . __('No version found', 'decidir_gateway') . '</mark>'; ?></td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'Codebase Plugin Version', 'decidir_gateway' ); ?>:</td>
				<td><?php echo $plugin['cb_version']
					? $plugin['cb_version']
					: '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . __('No version found', 'decidir_gateway') . '</mark>'; ?></td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'Codebase SDK Version', 'decidir_gateway' ); ?>:</td>
				<td><?php echo $plugin['cb_sdk_version']
					? $plugin['cb_sdk_version']
					: '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . __('No version found', 'decidir_gateway') . '</mark>'; ?></td>
			</tr>
		</tbody>
	</table>
</div>
