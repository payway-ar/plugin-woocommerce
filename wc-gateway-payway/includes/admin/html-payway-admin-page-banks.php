<?php
/**
 * @author IURCO - Prisma SA
 * @copyright Copyright © 2022 IURCO and PRISMA. All rights reserved.
 */

defined( 'ABSPATH' ) || exit;

$PAGE_KEY_NAME = 'payway_admin_banks';
$ACTION_NAME_NEW = 'newbank';
$ACTION_NAME_EDIT = 'editbank';
$ACTION_NAME_DELETE = 'deletebank';

// Alias in case in future we update the way we pass data
$params = $_GET;

$is_creation = isset( $params['action'] ) && $params['action'] === $ACTION_NAME_NEW;

// Page Title
$title = $is_creation
	? esc_html__('Add New', 'wc-gateway-payway')
	: esc_html__('Banks', 'wc-gateway-payway');
?>
<div class="wrap">
	<h1 class="wp-heading-inline">
		<?php echo $title; ?>
	</h1>
	<?php if ( ! isset($params['action']) ): ?>
		<?php //wp_nonce_field( 'payway-table-banks-massaction', '_wpnonce_payway-delete-banks-massaction' ); ?>
		<a href="<?php echo esc_url( admin_url( 'admin.php?page=' . $PAGE_KEY_NAME . '&action=' . $ACTION_NAME_NEW ) ); ?>" class="page-title-action">
			<?php echo esc_html__( 'Add New', 'wc-gateway-payway' ); ?>
		</a>
		<?php echo $this->get_table(); ?>
	<?php else: ?>
		<?php if (isset( $_POST['action'] )): ?>
			<?php if ( $_POST['action'] === $ACTION_NAME_NEW ): ?>
				<?php check_admin_referer( 'payway-create-edit-bank', '_wpnonce_payway-create-edit-bank' ); ?>
				<?php
					$errors = new WP_Error();

					//TODO: move to it's own validate method, as it currently is in promotions page
					$data = array();
					if ( isset( $_POST['name'] ) && $_POST['name'] != '' ) {
						$data['name'] = sanitize_text_field( $_POST['name'] );
					} else {
						$errors->add( 'name', __('Name is a required field', 'wc-gateway-payway') );
					}
				?>
				<?php if ( ! $errors->has_errors() ): ?>
					<?php $result = $this->create_bank( $data ); ?>
					<?php if ( $result ): ?>
						<?php $redirect = remove_query_arg( array( 'wp_http_referer', 'updated', 'action' ) ); ?>
						<?php wp_redirect( $redirect ); ?>
						<?php die(); ?>
					<?php else: ?>
						<p><?php echo __('Some things happened during bank creation', 'wc-gateway-payway'); ?></p>
						<a href="<?php echo esc_url( admin_url( 'admin.php?page=' . $PAGE_KEY_NAME ) ); ?>"><?php echo __('Go back', 'wc-gateway-payway'); ?></a>
					<?php endif; ?>
				<?php else: ?>
					<?php if ( isset( $errors ) && is_wp_error( $errors ) ): ?>
						<div class="error">
							<ul>
							<?php foreach ( $errors->get_error_messages() as $errorMessage ): ?>
								<li><?php echo $errorMessage; ?></li>
							<?php endforeach; ?>
							</ul>
						</div>
						<a href="<?php echo esc_url( admin_url( 'admin.php?page=' . $PAGE_KEY_NAME . '&action=' . $ACTION_NAME_NEW) ); ?>"><?php echo __('Go back', 'wc-gateway-payway'); ?></a>
					<?php endif; ?>
				<?php endif; ?>
			<?php elseif ( $_POST['action'] === $ACTION_NAME_EDIT ): ?>
				<?php check_admin_referer( 'payway-create-edit-bank', '_wpnonce_payway-create-edit-bank' ); ?>
				<?php $result = $this->update_bank( $_POST ); ?>
				<?php if ( $result ): ?>
					<?php $redirect = remove_query_arg( array( 'wp_http_referer', 'updated', 'action' ) ); ?>
					<?php wp_redirect( $redirect ); ?>
					<?php die(); ?>
				<?php else: ?>
					<p><?php echo __('Some things happened while updating the bank', 'wc-gateway-payway'); ?></p>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=' . $PAGE_KEY_NAME ) ); ?>"><?php echo __('Go back', 'wc-gateway-payway'); ?></a>
				<?php endif; ?>
			<?php else: ?>
			<p>unhandled $_POST action</p>
			<?php endif; ?>
		<?php elseif ( $ACTION_NAME_NEW === $params['action']
			|| $ACTION_NAME_EDIT === $params['action'] ): ?>
				<?php $is_edit = $ACTION_NAME_EDIT === $params['action']; ?>
				<?php $bank_id = isset( $params['bank_id'] ) ? $params['bank_id'] : false; ?>
				<?php $bank = $bank_id ? $this->get_bank( $bank_id ) : false ?>
				<?php if ( $is_edit && ! $bank ): ?>
					<?php $errors = new WP_Error(); ?>
					<?php $errors->add( 'bank_id', __('Bank does not exist') ); ?>
					<div class="error">
						<ul>
						<?php foreach ( $errors->get_error_messages() as $err ): ?>
							<?php echo "<li>$err</li>\n"; ?>
						<?php endforeach; ?>
						</ul>
					</div>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=' . $PAGE_KEY_NAME ) ); ?>"><?php echo __('Go back', 'wc-gateway-payway'); ?></a>
					<?php die(); ?>
				<?php endif; ?>
				<form method="post" name="<?php echo $params['action'] ?>" id="<?php echo $params['action'] ?>" class="validate" novalidate="novalidate">
					<input name="action" type="hidden" value="<?php echo $params['action'] ?>" />
					<?php if ( $is_edit ): ?>
						<input name="bank_id" type="hidden" value="<?php echo $bank_id ?>" />
					<?php endif; ?>
					<?php wp_nonce_field( 'payway-create-edit-bank', '_wpnonce_payway-create-edit-bank' ); ?>
					<?php
						// Load up the passed data, else set to a default.
						$bank_name = $is_edit ? $bank->name : '';
					?>
					<table class="form-table" role="presentation">
						<tr class="form-field form-required">
							<th scope="row"><label for="name"><?php _e( 'Name', 'wc-gateway-payway' ); ?> <span class="description"><?php _e( '(required)', 'wc-gateway-payway' ); ?></span></label></th>
							<td><input name="name" type="text" id="name" value="<?php echo esc_attr( $bank_name ); ?>" aria-required="true" autocapitalize="none" autocorrect="off" maxlength="100" /></td>
						</tr>
					</table>
					<div>
						<?php if ( $is_edit ): ?>
							<?php submit_button( __( 'Save', 'wc-gateway-payway' ), 'primary', 'editbank', true, array( 'id' => 'editbanksub' ) ); ?>
						<?php else: ?>
							<?php submit_button( __( 'Add', 'wc-gateway-payway' ), 'primary', 'createbank', true, array( 'id' => 'newbanksub' ) ); ?>
						<?php endif; ?>
					</div>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=' . $PAGE_KEY_NAME ) ); ?>"><?php echo __('Go back', 'wc-gateway-payway'); ?></a>
				</form>
		<?php elseif ( $ACTION_NAME_DELETE === $params['action'] ): ?>
			<?php //TODO: implement a backend notice whether success/error happened ?>
			<?php wp_verify_nonce( '_wpnonce_payway-delete-bank-' . $params['bank_id'] ); ?>
			<?php $result = $this->delete_bank( $params['bank_id'] ); ?>
			<?php $redirect = remove_query_arg( array( 'action' ) ); ?>
			<?php //echo 'REDIRECT: ' . $redirect; ?>
			<?php wp_redirect( $redirect ); ?>
			<?php die(); ?>
		<?php else: ?>
			<p>You shouldn't be here...</p>
		<?php endif; ?>
	<?php endif; ?>
</div>
