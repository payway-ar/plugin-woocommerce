<?php
/**
 * @author IURCO - Prisma SA
 * @copyright Copyright © 2022 IURCO and PRISMA. All rights reserved.
 */

defined( 'ABSPATH' ) || exit;

$PAGE_KEY_NAME = 'decidir_admin_cards';
$ACTION_NAME_NEW = 'newcard';
$ACTION_NAME_EDIT = 'editcard';
$ACTION_NAME_DELETE = 'deletecard';

// Alias in case in future we update the way we pass data
$params = $_GET;

$is_creation = isset( $params['action'] ) && $params['action'] === $ACTION_NAME_NEW;

// Page Title
$title = $is_creation
	? esc_html__('Add New', 'wc-gateway-decidir')
	: esc_html__('Cards', 'wc-gateway-decidir');
?>
<div class="wrap">
	<h1 class="wp-heading-inline">
		<?php echo $title; ?>
	</h1>
	<?php if ( ! isset($params['action']) ): ?>
		<?php //wp_nonce_field( 'decidir-table-cards-massaction', '_wpnonce_decidir-delete-cards-massaction' ); ?>
		<a href="<?php echo esc_url( admin_url( 'admin.php?page=' . $PAGE_KEY_NAME . '&action=' . $ACTION_NAME_NEW ) ); ?>" class="page-title-action">
			<?php echo __( 'Add New', 'wc-gateway-decidir' ); ?>
		</a>
		<?php echo $this->get_table(); ?>
	<?php else: ?>
		<?php if (isset( $_POST['action'] )): ?>
			<?php if ( $_POST['action'] === $ACTION_NAME_NEW ): ?>
				<?php check_admin_referer( 'decidir-create-edit-card', '_wpnonce_decidir-create-edit-card' ); ?>
				<?php
					$errors = new WP_Error();

					//TODO: move to it's own validate method, as it currently is in promotions page
					$data = array();
					if ( isset( $_POST['card_name'] ) && $_POST['card_name'] != '' ) {
						$data['card_name'] = sanitize_text_field( $_POST['card_name'] );
					} else {
						$errors->add( 'card_name', __('Name is a required field', 'wc-gateway-decidir') );
					}

					if ( isset( $_POST['id_sps'] ) && $_POST['id_sps'] != '' ) {
						$data['id_sps'] = sanitize_text_field( $_POST['id_sps'] );
					} else {
						$errors->add( 'id_sps', __('ID SPS is a required field', 'wc-gateway-decidir') );
					}
					if ( isset( $_POST['id_nps'] ) && $_POST['id_nps'] != '' ) {
						$data['id_nps'] = sanitize_text_field( $_POST['id_nps'] );
					} else {
						$errors->add( 'id_nps', __('ID NPS is a required field', 'wc-gateway-decidir') );
					}
				?>
				<?php if ( ! $errors->has_errors() ): ?>
					<?php $result = $this->create_card( $data ); ?>
					<?php if ( $result ): ?>
						<?php $redirect = remove_query_arg( array( 'wp_http_referer', 'updated', 'action' ) ); ?>
						<?php wp_redirect( $redirect ); ?>
						<?php die(); ?>
					<?php else: ?>
						<p><?php echo __('Some things happened during Card creation', 'wc-gateway-decidir'); ?></p>
						<a href="<?php echo esc_url( admin_url( 'admin.php?page=' . $PAGE_KEY_NAME ) ); ?>"><?php echo __('Go back', 'wc-gateway-decidir'); ?></a>
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
						<a href="<?php echo esc_url( admin_url( 'admin.php?page=' . $PAGE_KEY_NAME . '&action=' . $ACTION_NAME_NEW ) ); ?>"><?php echo __('Go back', 'wc-gateway-decidir'); ?></a>
					<?php endif; ?>
				<?php endif; ?>
			<?php elseif ( $_POST['action'] === $ACTION_NAME_EDIT ): ?>
				<?php check_admin_referer( 'decidir-create-edit-card', '_wpnonce_decidir-create-edit-card' ); ?>
				<?php $result = $this->update_card( $_POST ); ?>
				<?php if ( $result ): ?>
					<?php $redirect = remove_query_arg( array( 'wp_http_referer', 'updated', 'action' ) ); ?>
					<?php wp_redirect( $redirect ); ?>
					<?php die(); ?>
				<?php else: ?>
					<p><?php echo __('Some things happened while updating the Card', 'wc-gateway-decidir'); ?></p>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=' . $PAGE_KEY_NAME ) ); ?>"><?php echo __('Go back', 'wc-gateway-decidir'); ?></a>
				<?php endif; ?>
			<?php else: ?>
			<p>unhandled $_POST action</p>
			<?php endif; ?>
		<?php elseif ( $ACTION_NAME_NEW === $params['action']
			|| $ACTION_NAME_EDIT === $params['action'] ): ?>
				<?php $is_edit = $ACTION_NAME_EDIT === $params['action']; ?>
				<?php $card_id = isset( $params['card_id'] ) ? $params['card_id'] : false; ?>
				<?php $card = $card_id ? $this->get_card( $card_id ) : false ?>
				<?php if ( $is_edit && ! $card ): ?>
					<?php $errors = new WP_Error(); ?>
					<?php $errors->add( 'card_id', __('Card does not exist') ); ?>
					<div class="error">
						<ul>
						<?php foreach ( $errors->get_error_messages() as $err ): ?>
							<?php echo "<li>$err</li>\n"; ?>
						<?php endforeach; ?>
						</ul>
					</div>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=' . $PAGE_KEY_NAME ) ); ?>"><?php echo __('Go back', 'wc-gateway-decidir'); ?></a>
					<?php die(); ?>
				<?php endif; ?>
				<form method="post" name="<?php echo $params['action'] ?>" id="<?php echo $params['action'] ?>" class="validate" novalidate="novalidate">
					<input name="action" type="hidden" value="<?php echo $params['action'] ?>" />
					<?php if ( $is_edit ): ?>
						<input name="card_id" type="hidden" value="<?php echo $card_id ?>" />
					<?php endif; ?>
					<?php wp_nonce_field( 'decidir-create-edit-card', '_wpnonce_decidir-create-edit-card' ); ?>
					<?php
						// Load up the passed data, else set to a default.
						$card_name = $is_edit ? $card->card_name : '';
						$id_sps = $is_edit ? $card->id_sps : '';
						$id_nps = $is_edit ? $card->id_nps : '';
					?>
					<table class="form-table" role="presentation">
						<tr class="form-field form-required">
							<th scope="row"><label for="card_name">
								<?php _e( 'Card Name', 'wc-gateway-decidir' ); ?> <span class="description"><?php _e( '(required)' ); ?></span></label>
							</th>
							<td>
								<input name="card_name" type="text" id="card_name" value="<?php echo esc_attr( $card_name ); ?>" aria-required="true" autocapitalize="none" autocorrect="off" maxlength="100" />
							</td>
						</tr>
						<tr class="form-field form-required">
							<th scope="row"><label for="id_sps">
								<?php _e( 'ID SPS', 'wc-gateway-decidir' ); ?> <span class="description"><?php _e( '(required)' ); ?></span></label>
							</th>
							<td>
								<input name="id_sps" type="text" id="id_sps" value="<?php echo esc_attr( $id_sps ); ?>" aria-required="true" autocapitalize="none" autocorrect="off" maxlength="100" />
							</td>
						</tr>
						<tr class="form-field form-required">
							<th scope="row"><label for="id_nps">
								<?php _e( 'ID NPS', 'wc-gateway-decidir' ); ?> <span class="description"><?php _e( '(required)' ); ?></span></label>
							</th>
							<td>
								<input name="id_nps" type="text" id="id_nps" value="<?php echo esc_attr( $id_nps ); ?>" aria-required="true" autocapitalize="none" autocorrect="off" maxlength="100" />
							</td>
						</tr>
					</table>
					<div>
						<?php if ( $is_edit ): ?>
							<?php submit_button( __( 'Save', 'wc-gateway-decidir' ), 'primary', 'editbank', true, array( 'id' => 'editcardsub' ) ); ?>
						<?php else: ?>
							<?php submit_button( __( 'Add', 'wc-gateway-decidir' ), 'primary', 'createbank', true, array( 'id' => 'newcardsub' ) ); ?>
						<?php endif; ?>
					</div>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=' . $PAGE_KEY_NAME ) ); ?>"><?php echo __('Go back', 'wc-gateway-decidir'); ?></a>
				</form>
		<?php elseif ( $ACTION_NAME_DELETE === $params['action'] ): ?>
			<?php //TODO: implement a backend notice whether success/error happened ?>
			<?php wp_verify_nonce( '_wpnonce_decidir-delete-card-' . $params['card_id'] ); ?>
			<?php $result = $this->delete_card( $params['card_id'] ); ?>
			<?php $redirect = remove_query_arg( array( 'action' ) ); ?>
			<?php wp_redirect( $redirect ); ?>
			<?php die(); ?>
		<?php else: ?>
			<p>You shouldn't be here...</p>
		<?php endif; ?>
	<?php endif; ?>
</div>
