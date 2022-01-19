<?php 
/*
Plugin Name: Dynamics NAV Integration
Plugin URI: https://www.captivix.com
Description: Microsoft Dynamics NAV WooCommerce Integration.
Version: 1.0.0
Author: Captivix
Author URI: https://www.captivix.com
License: GPLv2 or later
Text Domain: uno
@package NavLink 

Dynamics NAV Integration is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
Dynamics NAV Integration is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with Dynamics NAV Integration. If not, see {License URI}.
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! defined( 'UNOURL' )){
    define( 'UNOURL', plugins_url('uno') );
}
if ( ! defined( 'UNOPATH' )){
    define( 'UNOPATH', plugin_dir_path( __FILE__ ) );
}

include_once UNOPATH. '/init.php';

define('USERPWD', get_option('_uno_username').':'.get_option('_uno_password')); 

include_once UNOPATH . '/functions.php';

if(is_admin()){
	add_action('admin_menu', 'add_uno_menu');
	add_action( 'admin_enqueue_scripts', 'load_nav_link_custom_css_js' );
}

//wordpress scheduler
add_action('nav_link_daily_update', 'uno_daily_update_scheduler');

//ajax queried
add_action('wp_ajax_import_nav_products','import_nav_products_function');
add_action('wp_ajax_setup_nav_import','setup_nav_import_data');
add_action( 'admin_init', 'register_uno_settings');

//on order complete
add_filter( 'woocommerce_order_number', 'change_woocommerce_order_number', 1, 2 );
add_action('woocommerce_thankyou','nav_order_create_complete',5);

//import orders status for update
add_action('wp_ajax_update_nav_orders','update_nav_orders_function');
add_action('wp_ajax_setup_nav_orders_import','setup_nav_orders_import_function');

//add an additional column to orders list
add_filter( 'manage_edit-shop_order_columns', 'nav_add_order_no_column', 20 );
add_action( 'manage_shop_order_posts_custom_column', 'nav_add_order_no_column_value' );

// change orderstatus
add_action( 'init', 'register_my_new_order_statuses' );
add_filter( 'wc_order_statuses', 'my_new_wc_order_statuses' );
add_action('admin_footer','custom_bulk_admin_footer');

register_activation_hook( __FILE__, 'uno_pre_setup' );
register_deactivation_hook(__FILE__, 'uno_post_uninstall');

//add_filter( 'wc_order_statuses', 'wc_renaming_order_status' );

add_action( 'show_user_profile', 'extra_user_profile_fields' );
add_action( 'edit_user_profile', 'extra_user_profile_fields' );

add_action( 'personal_options_update', 'save_extra_user_profile_fields' );
add_action( 'edit_user_profile_update', 'save_extra_user_profile_fields' );

add_action( 'woocommerce_view_order', 'nav_view_order_function', 20 );
add_action( 'add_meta_boxes', 'add_shipping_meta_box' );


add_filter( 'woocommerce_available_payment_gateways', 'uno_paypal_enable_manager' );