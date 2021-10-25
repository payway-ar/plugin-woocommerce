<?php

function prisma_bank_list() {
    ?>

    <style>
img {
    width: 80px;
}

td {
    width: 330px;
    text-align: left;
}


</style>
    <link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/WC-gategay-decidir/assets/css/style-admin.css" rel="stylesheet" />
    <div class="wrap">
        <h2>Bank</h2>
        <div class="tablenav top">
            <div class="alignleft actions">
                <a href="<?php echo admin_url('admin.php?page=prisma_bank_create'); ?>">Add New</a>
            </div>
            <br class="clear">
        </div>
        <?php
        global $wpdb;
        $table_name = $wpdb->prefix . "school";

        $rows = $wpdb->get_results("SELECT id,name from $table_name");
        ?>
        <table class="data-grid data-grid-draggable" data-role="grid">
       <thead>
            <tr data-bind="foreach: {data: getVisible(), as: '$col'}"><!-- ko template: getHeader() -->

 
<!-- /ko --><!-- ko template: getHeader() -->
<th class="data-grid-th _sortable _draggable _descend" style="text-align:right;">
    <span class="data-grid-cell-content" data-bind="i18n: label" style="text-align:right;">ID</span>
</th>
<!-- /ko --><!-- ko template: getHeader() -->
<th class="data-grid-th _draggable" style="text-align:right;">
    <span class="data-grid-cell-content" data-bind="i18n: label" style="text-align:right;">Logo</span>
</th>
<!-- /ko --><!-- ko template: getHeader() -->
<th class="data-grid-th _sortable _draggable" style="text-align:right;">
    <span class="data-grid-cell-content" data-bind="i18n: label" style="text-align:right;">Bank Name</span>
</th>
<!-- /ko --><!-- ko template: getHeader() -->
<th class="data-grid-th" style="text-align:right;">
    <span class="data-grid-cell-content" data-bind="i18n: label" style="text-align:right;">Action</span>
</th>
<!-- /ko --></tr>
        </thead>
        
    </table>
    </div>
    <?php
}