<?php
/**
 * @author IURCO - Prisma SA
 * @copyright Copyright © 2022 IURCO and PRISMA. All rights reserved.
 */

defined( 'ABSPATH' ) || exit;

$PAGE_KEY_NAME = 'decidir_admin_promotions';
$ACTION_NAME_NEW = 'newpromotion';
$ACTION_NAME_EDIT = 'editpromotion';
$ACTION_NAME_DELETE = 'deletepromotion';

// Alias in case in future we update the way we pass data
$params = $_GET;

$is_creation = isset( $params['action'] ) && $params['action'] === $ACTION_NAME_NEW;

// Page Title
$title = $is_creation
	? esc_html__('Add New', 'wc-gateway-decidir')
	: esc_html__('Promotions', 'wc-gateway-decidir');
?>
<div class="wrap">
	<h1 class="wp-heading-inline">
		<?php echo $title; ?>
	</h1>
	<?php if ( ! isset($params['action']) ): ?>
		<?php //wp_nonce_field( 'decidir-table-cards-massaction', '_wpnonce_decidir-delete-cards-massaction' ); ?>
		<a href="<?php echo esc_url( admin_url( 'admin.php?page=' . $PAGE_KEY_NAME . '&action=' . $ACTION_NAME_NEW ) ); ?>" class="page-title-action">
			<?php echo esc_html__( 'Add New', 'wc-gateway-decidir' ); ?>
		</a>
		<?php echo $this->get_table(); ?>
	<?php else: ?>
		<?php if (isset( $_POST['action'] )): ?>
			<?php if ( $_POST['action'] === $ACTION_NAME_NEW ): ?>
				<?php check_admin_referer( 'decidir-create-edit-promotion', '_wpnonce_decidir-create-edit-promotion' ); ?>
				<?php
					/** @var WP_Error|null */
					$validation_result = $this->validate_promotion_data( $_POST );
				?>
				<?php if ( ! $validation_result || ( ! is_wp_error( $validation_result ) &&  ! $validation_result->has_errors() ) ): ?>
					<?php $result = $this->create_promotion( $_POST ); ?>
					<?php if ( $result ): ?>
						<?php $redirect = remove_query_arg( array( 'wp_http_referer', 'updated', 'action' ) ); ?>
						<?php wp_redirect( $redirect ); ?>
						<?php die(); ?>
					<?php else: ?>
						<p><?php echo __('Some things happened during Promotion creation', 'wc-gateway-decidir'); ?></p>
						<a href="<?php echo esc_url( admin_url( 'admin.php?page=' . $PAGE_KEY_NAME ) ); ?>"><?php echo __('Go back', 'wc-gateway-decidir'); ?></a>
						<?php die(); ?>
					<?php endif; ?>
				<?php else: ?>
					<div class="error">
						<ul>
						<?php foreach ( $validation_result->get_error_messages() as $error ): ?>
							<li><?php echo $error['message']; ?></li>
						<?php endforeach; ?>
						</ul>
					</div>
					<?php die(); ?>
				<?php endif; ?>
			<?php elseif ( $_POST['action'] === $ACTION_NAME_EDIT ): ?>
				<?php check_admin_referer( 'decidir-create-edit-promotion', '_wpnonce_decidir-create-edit-promotion' ); ?>
				<?php
					/** @var WP_Error|null */
					$validation_result = $this->validate_promotion_data( $_POST );
				?>
				<?php if ( ! $validation_result || ( ! is_wp_error( $validation_result ) &&  ! $validation_result->has_errors() ) ): ?>
					<?php $result = $this->update_promotion( $_POST ); ?>
					<?php if ( $result ): ?>
						<?php $redirect = remove_query_arg( array( 'wp_http_referer', 'updated', 'action' ) ); ?>
						<?php wp_redirect( $redirect ); ?>
						<?php die(); ?>
					<?php else: ?>
						<p><?php echo __('Ensure all required fields are being filled with valid information', 'wc-gateway-decidir'); ?></p>
						<a href="<?php echo esc_url( admin_url( 'admin.php?page=' . $PAGE_KEY_NAME ) ); ?>"><?php echo __('Go back', 'wc-gateway-decidir'); ?></a>
					<?php endif; ?>
					<?php die(); ?>
				<?php else: ?>
					<div class="error">
						<ul>
						<?php foreach ( $validation_result->get_error_messages() as $error ): ?>
							<li><?php echo $error['message']; ?></li>
						<?php endforeach; ?>
						</ul>
					</div>
					<?php die(); ?>
				<?php endif; ?>
			<?php else: ?>
			<!-- If you ended up here, means someone is playing around with params... -->
			<?php endif; ?>
		<?php elseif ( $ACTION_NAME_NEW === $params['action']
			|| $ACTION_NAME_EDIT === $params['action'] ): ?>
				<?php $is_edit = $ACTION_NAME_EDIT === $params['action']; ?>
				<?php $promotion_id = isset( $params['promotion_id'] ) ? $params['promotion_id'] : false; ?>
				<?php $promotion = $promotion_id ? $this->get_promotion( $promotion_id ) : false; ?>
				<?php if ( $is_edit && ! $promotion ): ?>
					<?php $errors = new WP_Error(); ?>
					<?php $errors->add( 'promotion_id', __('Promotion does not exist', 'wc-gateway-decidir') ); ?>
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
				<form method="post" name="<?php echo $params['action']; ?>" id="<?php echo $params['action']; ?>" class="validate" novalidate="novalidate">
					<input name="action" type="hidden" value="<?php echo $params['action'] ?>" />
					<?php if ( $is_edit ): ?>
						<input name="promotion_id" type="hidden" value="<?php echo $promotion_id ?>" />
					<?php endif; ?>
					<?php wp_nonce_field( 'decidir-create-edit-promotion', '_wpnonce_decidir-create-edit-promotion' ); ?>
					<?php
						// Load up the passed data, else set to a default.
						$rule_name = $is_edit ? $promotion->rule_name : '';
						$bank_id = $is_edit ? $promotion->bank_id : '';
						$card_id = $is_edit ? $promotion->card_id : '';
						$is_active = $is_edit ? (int) $promotion->is_active : '';
						$from_date = $is_edit ? $promotion->from_date : '';
						$to_date = $is_edit ? $promotion->to_date : '';
						$priority = $is_edit ? $promotion->priority : '';
						$applicable_days = $is_edit
							? (strpos($promotion->applicable_days, ',') !== false)
								? explode( ',', $promotion->applicable_days )
								: $promotion->applicable_days
							: '';
					?>
					<table class="form-table" role="presentation">
						<tr class="form-field form-required">
							<th scope="row"><label for="rule_name">
								<?php _e( 'Promotion Name', 'wc-gateway-decidir' ); ?> <span class="description"><?php echo __( '(required)', 'wc-gateway-decidir' ); ?></span></label>
							</th>
							<td>
								<input name="rule_name" type="text" id="rule_name" value="<?php echo esc_attr( $rule_name ); ?>" aria-required="true" autocapitalize="none" autocorrect="off" maxlength="100" />
							</td>
						</tr>
						<tr class="form-field form-required">
							<th scope="row"><label for="is_active">
								<?php _e( 'Is Active', 'wc-gateway-decidir' ); ?></label>
							</th>
							<td>
								<select name="is_active" type="text" id="is_active">
									<option value="1" <?php echo $is_active === 1 ? 'selected' : '' ?>><?php echo __( 'Enabled', 'wc-gateway-decidir' ) ?></option>
									<option value="0" <?php echo $is_active === 0 ? 'selected' : '' ?>><?php echo __( 'Disabled', 'wc-gateway-decidir' ) ?></option>
								</select>
							</td>
						</tr>
						<tr class="form-field form-required">
							<th scope="row"><label for="bank_id">
								<?php _e( 'Bank', 'wc-gateway-decidir' ); ?> <span class="description"><?php echo __( '(required)', 'wc-gateway-decidir' ); ?></span></label>
							</th>
							<td>
								<select name="bank_id" type="text" id="bank_id" aria-required="true">
									<?php foreach ($this->get_banks() as $bank): ?>
										<option value="<?php echo $bank['value']; ?>" <?php echo $bank_id === $bank['value'] ? 'selected' : '' ?>><?php echo $bank['label']; ?></option>
									<?php endforeach; ?>
								</select>
							</td>
						</tr>
						<tr class="form-field form-required">
							<th scope="row"><label for="card_id">
								<?php _e( 'Card', 'wc-gateway-decidir' ); ?> <span class="description"><?php echo __( '(required)', 'wc-gateway-decidir' ); ?></span></label>
							</th>
							<td>
								<select name="card_id" type="text" id="card_id" aria-required="true">
									<?php foreach ($this->get_cards() as $card): ?>
										<option value="<?php echo $card['value']; ?>" <?php echo $card_id === $card['value'] ? 'selected' : '' ?>><?php echo $card['label']; ?></option>
									<?php endforeach; ?>
								</select>
							</td>
						</tr>
						<tr class="form-field form-required">
							<th scope="row"><label for="from_date">
								<?php _e( 'From Date', 'wc-gateway-decidir' ); ?> <span class="description"><?php echo __( '(required)', 'wc-gateway-decidir' ); ?></span></label>
							</th>
							<td>
								<input type="datetime-local" name="from_date" id="from_date" value="<?php echo $from_date ?>" />
							</td>
						</tr>
						<tr class="form-field form-required">
							<th scope="row"><label for="to_date">
								<?php _e( 'To Date', 'wc-gateway-decidir' ); ?> <span class="description"><?php echo __( '(required)', 'wc-gateway-decidir' ); ?></span></label>
							</th>
							<td>
								<input type="datetime-local" name="to_date" id="to_date" value="<?php echo $to_date ?>"/>
							</td>
						</tr>
						<tr class="form-field form-required">
							<th scope="row"><label for="priority">
								<?php _e( 'Priority', 'wc-gateway-decidir' ); ?></label>
							</th>
							<td>
								<input name="priority" type="text" id="priority" value="<?php echo esc_attr( $priority ); ?>" maxlength="2" />
							</td>
						</tr>
						<tr class="form-field form-required">
							<th scope="row"><label for="applicable_days">
								<?php _e( 'Applicable days', 'wc-gateway-decidir' ); ?> <span class="description"><?php echo __( '(required)', 'wc-gateway-decidir' ); ?></span></label>
							</th>
							<td>
								<select multiple="true" name="applicable_days[]" id="applicable_days" aria-required="true">
									<?php foreach ($this->get_weekdays_for_element() as $day): ?>
										<option value="<?php echo $day['value']; ?>" <?php echo $this->is_day_in_promotion( $day['value'], $promotion ) ? 'selected' : '' ?>><?php echo $day['label']; ?></option>
									<?php endforeach; ?>
								</select>
							</td>
						</tr>
					</table>

					<?php require_once( WC_DECIDIR_ABSPATH . 'includes/admin/html-decidir-admin-page-promo-plans.php' ); ?>

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
			<?php wp_verify_nonce( '_wpnonce_decidir-delete-card-' . $params['promotion_id'] ); ?>
			<?php $result = $this->delete_promotion( $params['promotion_id'] ); ?>
			<?php $redirect = remove_query_arg( array( 'action' ) ); ?>
			<?php wp_redirect( $redirect ); ?>
			<?php die(); ?>
		<?php else: ?>
			<!-- If you ended up here, means someone is playing around with params... -->
		<?php endif; ?>
	<?php endif; ?>
</div>
