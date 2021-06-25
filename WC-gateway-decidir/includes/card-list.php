<?php

function prisma_card_list() {
    ?>
    <link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/WC-gategay-decidir/assets/css/style-admin.css" rel="stylesheet" />
    <div class="wrap">
        <h2>Schools</h2>
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
        <table class='wp-list-table widefat fixed striped posts'>
            <tr>
                <th class="manage-column ss-list-width">ID</th>
                <th class="manage-column ss-list-width">Name</th>
                <th>&nbsp;</th>
            </tr>
            <?php foreach ($rows as $row) { ?>
                <tr>
                    <td class="manage-column ss-list-width"><?php echo $row->id; ?></td>
                    <td class="manage-column ss-list-width"><?php echo $row->name; ?></td>
                    <td><a href="<?php echo admin_url('admin.php?page=prisma_card_update&id=' . $row->id); ?>">Update</a></td>
                </tr>
            <?php } ?>
        </table>
    </div>
    <?php
}