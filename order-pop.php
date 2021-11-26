<?php
/*
@package OP_Developer
@version 0.1.0
Plugin Name: Order Pop
Plugin URI: https://github.com/stevenwoolston/order-pop
Description: Sales Order Pop Notification
Version: 0.1
Author: Steven Woolston
Author URI: https://www.woolston.com.au
GitHub Plugin URI: https://github.com/stevenwoolston/order-pop
*/

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

define("OP_PLUGIN_PATH", plugin_dir_path(__FILE__));
define('OP_PLUGIN_URL', plugin_dir_url(__FILE__));
define("OP_PLUGIN_BASENAME", plugin_basename(__FILE__));

require_once(plugin_dir_path(__FILE__) . '/inc/op-config.php');

require_once(plugin_dir_path(__FILE__) . '/lib/op-activate-plugin.php');

require_once(plugin_dir_path(__FILE__) . '/lib/op-admin-options.php');

require_once(plugin_dir_path(__FILE__) . '/lib/popController.php');

add_action('init', 'op_enqueue_assets');

add_action('admin_enqueue_scripts', 'op_admin_enqueue_assets');
register_activation_hook(__FILE__, 'op_activation_hook');
register_deactivation_hook(__FILE__, 'op_deactivation_hook');


function op_enqueue_assets() {
    wp_enqueue_style('op-style', plugin_dir_url(__FILE__) . '/css/order-pop.css', array(), '1.0', 'all');
    wp_register_script('op_order_script', plugin_dir_url(__FILE__) . '/js/order-pop.js', array('jquery') );
    wp_localize_script('op_order_script', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
    wp_enqueue_script('jquery' );
    wp_enqueue_script('op_order_script' );
}

function op_admin_enqueue_assets() {
    wp_enqueue_media();
    wp_enqueue_style('op-developer-style', plugin_dir_url(__FILE__) . '/css/op-admin.css', array(), '1.0', 'all');
    wp_enqueue_script('op-developer-script', plugin_dir_url(__FILE__) . '/js/op-admin.js', array('jquery'), '1.0', true);

    //Enqueue CSS just for us
    if ( isset( $_GET['page'] ) && $_GET['page'] == 'op_plugin' ) {
        wp_enqueue_style('op-bootstrap', plugin_dir_url(__FILE__) . '/css/bootstrap.min.css');
    }    
}

function op_activation_hook() {
    op_plugin_activate();
}

function op_deactivation_hook() {
    op_plugin_deactivate();
}