<?php

function prisma_promo_list() {
    ?>
    <link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/WC-gategay-decidir/assets/css/style-admin.css" rel="stylesheet" />
    <div class="wrap">
        <h2>Promotions</h2>
        <div class="tablenav top">
            <div class="alignleft actions">
                <a href="<?php echo admin_url('admin.php?page=prisma_promo_create'); ?>">Add New</a>
            </div>
            <br class="clear">
        </div>
        <?php
        global $wpdb;
        $table_name = $wpdb->prefix . "promotion";

        $rows = $wpdb->get_results("SELECT id,name from $table_name");
        ?>
       <table class="data-grid data-grid-draggable" data-role="grid">
       <thead>
            <tr> 
<th >
    
</th>

<th>
    <span >ID</span>
</th>

<th>
    <span >Rule Name</span>
</th>

<th>
    <span >Card</span>
</th>

<th>
    <span >Bank</span>
</th>

<th>
    <span >Coeficiente</span>
</th>

<th>
    <span >Value</span>
</th>

<th>Priority</span>
</th>

 

<th >
    <span >Action</span>
</th>
 </tr>
        </thead>
    
    </table>
    </div>
    <?php
}