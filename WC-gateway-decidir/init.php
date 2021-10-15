<?php
				
function bank_options_install() {

    global $wpdb;

    $table_name = $wpdb->prefix . "bank";
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $table_name (
            `id` varchar(3) CHARACTER SET utf8 NOT NULL,
            `logo` varchar(150) CHARACTER SET utf8 NOT NULL,
            `name` varchar(150) CHARACTER SET utf8 NOT NULL,
            `enable` int(1) CHARACTER SET utf8 NOT NULL,
            PRIMARY KEY (`id`)
          ) $charset_collate; ";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);
}

// run the install scripts upon plugin activation
register_activation_hook(__FILE__, 'bank_options_install');

//menu items
add_action('admin_menu','prisma_bank_modifymenu');

function prisma_bank_modifymenu() {
	
	//this is the main item for the menu
	add_menu_page('Decidir Promotions', //page title
	'Decidir Promotions', //menu title
	'manage_options', //capabilities
	'prisma_bank_list', //menu slug
	'prisma_bank_list' //function
	);
	
	//this is a submenu
	add_submenu_page('prisma_bank_list', //parent slug
	'Add New Bank', //page title
	'Add New Bank', //menu title
	'manage_options', //capability
	'prisma_bank_create', //menu slug
	'prisma_bank_create'); //function
	
	//this submenu is HIDDEN, however, we need to add it anyways
	add_submenu_page(null, //parent slug
	'Update Bank', //page title
	'Update', //menu title
	'manage_options', //capability
	'prisma_bank_update', //menu slug
	'prisma_bank_update'); //function

	//this is a submenu
	add_submenu_page('prisma_bank_list', //parent slug
	'Add New Card', //page title
	'Add New Card', //menu title
	'manage_options', //capability
	'prisma_card_create', //menu slug
	'prisma_card_create'); //function
	
	//this submenu is HIDDEN, however, we need to add it anyways
	add_submenu_page(null, //parent slug
	'Update Card', //page title
	'Update', //menu title
	'manage_options', //capability
	'prisma_card_update', //menu slug
	'prisma_card_update'); //function

	//this is a submenu
	add_submenu_page('prisma_bank_list', //parent slug
	'Add New Promo', //page title
	'Add New Promo', //menu title
	'manage_options', //capability
	'prisma_promo_create', //menu slug
	'prisma_promo_create'); //function
	
	//this submenu is HIDDEN, however, we need to add it anyways
	add_submenu_page(null, //parent slug
	'Update Promo', //page title
	'Update', //menu title
	'manage_options', //capability
	'prisma_promo_update', //menu slug
	'prisma_promo_update'); //function


}
define('ROOTDIR', plugin_dir_path(__FILE__));
require_once(ROOTDIR . '/includes/bank-list.php');
require_once(ROOTDIR . '/includes/bank-create.php');
require_once(ROOTDIR . '/includes/bank-update.php');
require_once(ROOTDIR . '/includes/card-list.php');
require_once(ROOTDIR . '/includes/card-create.php');
require_once(ROOTDIR . '/includes/card-update.php');
require_once(ROOTDIR . '/includes/promo-list.php');
require_once(ROOTDIR . '/includes/promo-create.php');
require_once(ROOTDIR . '/includes/promo-update.php');