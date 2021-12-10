<?php

function tables_options_install()
{

    global $wpdb;

    $table_name_banks = $wpdb->prefix . "banks";
    $charset_collate  = $wpdb->get_charset_collate();
    $sql              = "CREATE TABLE $table_name (
            `id` varchar(3) CHARACTER SET utf8 NOT NULL,
            `logo` varchar(150) CHARACTER SET utf8 NOT NULL,
            `name` varchar(150) CHARACTER SET utf8 NOT NULL,
            `enable` int(1) CHARACTER SET utf8 NOT NULL,
            PRIMARY KEY (`id`)
          ) $charset_collate; ";

    $table_name_cards = $wpdb->prefix . "cards";
    $charset_collate  = $wpdb->get_charset_collate();
    $sql1             = "CREATE TABLE $table_name (
            `id` varchar(3) CHARACTER SET utf8 NOT NULL,
            `logo` varchar(150) CHARACTER SET utf8 NOT NULL,
            `name` varchar(150) CHARACTER SET utf8 NOT NULL,
            `enable` int(1) CHARACTER SET utf8 NOT NULL,
            PRIMARY KEY (`id`)
          ) $charset_collate; ";

    $table_name_promotions = $wpdb->prefix . "promotions";
    $charset_collate       = $wpdb->get_charset_collate();
    $sql2                  = "CREATE TABLE $table_name (
            `id` varchar(3) CHARACTER SET utf8 NOT NULL,
            `name` varchar(150) CHARACTER SET utf8 NOT NULL,
            `enable` int(1) CHARACTER SET utf8 NOT NULL,
            `id` varchar(3) CHARACTER SET utf8 NOT NULL,
            PRIMARY KEY (`id`)
          ) $charset_collate; ";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
    dbDelta($sql1);
    dbDelta($sql2);
}

register_activation_hook(__FILE__, 'tables_options_install');

add_action('admin_menu', 'prisma_bank_modifymenu');

function prisma_bank_modifymenu()
{

    add_menu_page('Decidir Promotions',
        'Decidir Promotions',
        'manage_options',
        'prisma_bank_list',
        'prisma_bank_list'
    );

    add_submenu_page('prisma_bank_list',
        'Banks',
        'Banks',
        'manage_options',
        'prisma_bank_list',
        'prisma_bank_list');

    add_submenu_page(null,
        'Update Bank',
        'Update',
        'manage_options',
        'prisma_bank_create',
        'prisma_bank_create');

    add_submenu_page('prisma_bank_list',
        'Cards',
        'Cards',
        'manage_options',
        'prisma_card_list',
        'prisma_card_list');

    add_submenu_page(null,
        'List Card',
        'List',
        'manage_options',
        'prisma_card_create',
        'prisma_card_create');

    add_submenu_page('prisma_bank_list',
        'Promotions',
        'Promotions',
        'manage_options',
        'prisma_promo_list',
        'prisma_promo_list');

    add_submenu_page(null,
        'Update Promo',
        'Update',
        'manage_options',
        'prisma_promo_create',
        'prisma_promo_create');

    add_submenu_page('prisma_bank_list',
        'View Log',
        'View Log',
        'manage_options',
        'prisma_view_log',
        'prisma_view_log');

    add_submenu_page(null,
        'View Log',
        'View Log',
        'manage_options',
        'prisma_view_log',
        'prisma_view_log');

}

define('ROOTDIR', plugin_dir_path(__FILE__));
require_once ROOTDIR . '/includes/bank-list.php';
require_once ROOTDIR . '/includes/bank-create.php';
require_once ROOTDIR . '/includes/bank-update.php';
require_once ROOTDIR . '/includes/card-list.php';
require_once ROOTDIR . '/includes/card-create.php';
require_once ROOTDIR . '/includes/card-update.php';
require_once ROOTDIR . '/includes/promo-list.php';
require_once ROOTDIR . '/includes/promo-create.php';
require_once ROOTDIR . '/includes/promo-update.php';
require_once ROOTDIR . '/includes/view-log.php';
