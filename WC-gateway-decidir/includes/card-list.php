<?php



function prisma_card_list() {
    ?>
<style>
img {
    width: 80px;
}

td {
    width: 220px;
    text-align: left;
}


</style>
    <link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/WC-gategay-decidir/assets/css/style-admin.css" rel="stylesheet" />
    <div class="wrap">
        <h2>Cards</h2>
        <div class="tablenav top">
            <div class="alignleft actions">
                <a href="<?php echo admin_url('admin.php?page=prisma_card_create'); ?>">Add New</a>
            </div>
            <br class="clear">
        </div>
        <?php
        global $wpdb;
        $table_name = $wpdb->prefix . "cards";

        $rows = $wpdb->get_results("SELECT id,name from $table_name");
        ?>
       <table class="data-grid data-grid-draggable" data-role="grid">
       <thead>
<tr data-bind="foreach: {data: getVisible(), as: '$col'}"> 


 
<th class="data-grid-th _sortable _draggable _descend" style="text-align:right;">
    <span class="data-grid-cell-content" data-bind="i18n: label">ID</span>
</th>
 
<th class="data-grid-th _sortable _draggable" style="text-align:right;">
    <span class="data-grid-cell-content" data-bind="i18n: label">Card Name</span>
</th>
<!-- /ko --><!-- ko template: getHeader() -->
<th class="data-grid-th _sortable _draggable" style="text-align:right;">
    <span class="data-grid-cell-content" data-bind="i18n: label">ID SPS</span>
</th>
<!-- /ko --><!-- ko template: getHeader() -->
<th class="data-grid-th _sortable _draggable" style="text-align:right;">
    <span class="data-grid-cell-content" data-bind="i18n: label">ID NPS</span>
</th>
<!-- /ko --><!-- ko template: getHeader() -->
<th class="data-grid-th _draggable" style="text-align:right;">
    <span class="data-grid-cell-content" data-bind="i18n: label">Logo</span>
</th>
<!-- /ko --><!-- ko template: getHeader() -->
<th class="data-grid-th" style="text-align:right;">
    <span class="data-grid-cell-content" data-bind="i18n: label">Action</span>
</th>
<!-- /ko --></tr>
        </thead>
        
    </table>
    </div>
    <?php
}