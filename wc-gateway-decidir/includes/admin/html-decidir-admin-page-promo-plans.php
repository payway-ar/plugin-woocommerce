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
?>
<?php if ( ! isset($params['action']) ): ?>
<?php else: ?>
	<?php if ( $ACTION_NAME_NEW === $params['action']
		|| $ACTION_NAME_EDIT === $params['action'] ): ?>
		<?php $is_edit = $ACTION_NAME_EDIT === $params['action']; ?>

		<div class="wrap-table-fee-plans" id="wrap-table-fee-plans">
			<h2><?php echo __( 'Fee Plans configuration', 'wc-gateway-decidir' ); ?></h2>
			<table id="decidir-admin-promotions-plans">
				<thead>
					<tr>
						<th><?php echo __( 'Period' , 'wc-gateway-decidir' ); ?></th>
						<th><?php echo __( 'Coeficient' , 'wc-gateway-decidir' ); ?></th>
						<th><?php echo __( 'TEA' , 'wc-gateway-decidir' ); ?></th>
						<th><?php echo __( 'CFT' , 'wc-gateway-decidir' ); ?></th>
						<th><?php echo __( 'Fee to send' , 'wc-gateway-decidir' ); ?></th>
						<th><?php echo __( 'Delete' , 'wc-gateway-decidir' ); ?></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td colspan="6">
							<a href="#" id="fee-plans-add-new" title="<?php echo __( 'Add new plan', 'wc-gateway-decidir' ); ?>">
								<?php echo __( 'Add new plan', 'wc-gateway-decidir' ); ?>
							</a>
						</td>
					</tr>
				</tfoot>
				<tbody><!-- gets filled out with the script at the bottom --></tbody>
			</table>
		</div>
	<?php else: ?>
		<p>You shouldn't be here...</p>
	<?php endif; ?>
<?php endif; ?>
<script type="text/javascript">
jQuery( function( $ ) {
  	var decidir_plan_wrapper_id = '#wrap-table-fee-plans',
		plan_add_new_row_identifier = '#fee-plans-add-new',
		plan_delete_row_identifier = '.plans-remove-row';

    // Denotes total number of rows
    var row_id = 0,
		plans = <?php echo $promotion && $promotion->fee_plans != '' ? $promotion->fee_plans : json_encode([]); ?>;

	function add_plan_row( data ) {
		// append a new row into the Plans table
		$(decidir_plan_wrapper_id + ' tbody').append(
			get_row_html( data )
		);
	}

	// builds the HTML for a new row (with/without pre-existing data)
	function get_row_html( data ) {
		var row_fee_period = data && data.fee_period || '',
			row_coefficient = data && data.coefficient || '',
			row_tea = data && data.tea || '',
			row_cft = data && data.cft || '',
			row_fee_to_send = data && data.fee_to_send || '';

		return `<tr id="plan-row-${row_id}">
			<td><input type="text" name="fee_plans[${row_id}][fee_period]"
				id="plan_${row_id}_fee_period" class="promotion-fee-plan-field field-fee-period" value="${row_fee_period}" /></td>
			<td><input type="text" name="fee_plans[${row_id}][coefficient]"
				id="plan_${row_id}_coefficient" class="promotion-fee-plan-field field-coefficient" value="${row_coefficient}" /></td>
			<td><input type="text" name="fee_plans[${row_id}][tea]"
				id="plan_${row_id}_tea" class="promotion-fee-plan-field field-tea" value="${row_tea}" /></td>
			<td><input type="text" name="fee_plans[${row_id}][cft]"
				id="plan_${row_id}_cft" class="promotion-fee-plan-field field-cft" value="${row_cft}"/></td>
			<td><input type="text" name="fee_plans[${row_id}][fee_to_send]"
				id="plan_${row_id}_fee_to_send" class="promotion-fee-plan-field field-fee-to-send" value="${row_fee_to_send}" /></td>
			<td>
				<a href="#" class="plans-remove-row" id="fee-plans-remove-row" title="<?php echo __( 'Delete', 'wc-gateway-decidir' ); ?>">
					<?php echo __( 'Delete', 'wc-gateway-decidir' ); ?>
				</a>
			</td>
		</tr>`;
	};

	// click event - add row
	$(decidir_plan_wrapper_id + ' ' + plan_add_new_row_identifier)
		.on('click', function (e) {
			e.preventDefault();
			++row_id;
			$(decidir_plan_wrapper_id + ' tbody').append(get_row_html());
		});

	// click event - remove row
	$(decidir_plan_wrapper_id + ' tbody')
		.on('click', plan_delete_row_identifier, function (e) {
			e.preventDefault();
			$(this).closest('tr').remove();
		});

// console.log('plans', plans);
	if (plans) {
		$.each(plans, function (index, data) {
// console.log(index, data);
			row_id++;
			add_plan_row(data);
		});
	} else {
		$('#wrap-table-fee-plans #fee-plans-add-new').trigger('click');
	}
});

</script>
