<?php
/*
@package Woolston Web Design Developer Plugin
*/

if (! defined('ABSPATH')) exit;  // if direct access 

function op_add_admin_page() {
    add_menu_page('Order Pop Theme Options', 'Order Pop', 'manage_options', 'op_plugin', 'op_theme_create_settings_page', 'dashicons-sas', 90);
    add_submenu_page('op_plugin',  'WWD Theme Options',  'Settings',  'manage_options',  'op_plugin',  'op_theme_create_settings_page');

    register_setting('op-plugin-options', 'op-plugin');
}
add_action('admin_menu', 'op_add_admin_page');

function op_theme_create_settings_page() {
    $options = get_option('op-plugin');
    // var_dump($options);
    require_once OP_PLUGIN_PATH . "/templates/admin.php";
}