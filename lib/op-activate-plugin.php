<?php
/*
@package Woolston Web Design Developer Plugin
*/

if (! defined('ABSPATH')) exit;  // if direct access 

function op_plugin_activate() {
  
    if (!is_admin()) {
        return;
    }

    if (!class_exists('WooCommerce')) {
        die('Plugin could not be activated because Woocommerce was not detected.');
    }

    $default_options = array(
        'stop_notifications' => false,
        'pop_interval_minutes' => 5,
        'pop_background_colour' => '#15bbd1',
        'pop_font_colour' => '#ffffff',
        'pop_last_order_count' => 25,
        'sale_message' => '',
        'debug_active' => false,
        'custom_css' => get_default_css(),
        'excluded_categories' => Array()
   );
    
    // $op_options = get_option('op_plugin');
    // $new_options = $default_options + (is_array($op_options) ? $op_options : array());
    update_option('op-plugin', $default_options);
    flush_rewrite_rules();
    
    // if (!get_option('op-plugin')) {
    //     update_option('op-plugin', $defaultOptions);
    // }
}

function get_default_css() {
    return (get_option('op-plugin')['custom_css']) ?
        get_option('op-plugin')['custom_css'] :
        `.op-popper { border: 1px solid #aba9a9; border-radius: 10px; }
        .op-popper button.close { top: 0; right: 20px; color: #5D1FF0; }
        .op-popper .op-content { padding: 1vw 0 1vw; }
        .op-popper .op-content .meta { font-size: 12px; }
        .op-popper .op-content p { padding: 0 0 10px 0; }
        .op-popper p.customer-details { font-size: 15px; }
        .op-popper .product-name { color: #5D1FF0 !important; font-weight: 600; font-size: 18px; }
        .op-popper .op-content .meta a { font-size: 13px; text-decoration: underline; }
        .op-popper .op-content-container .op-image { padding: 0 5px 0 0; }`;
}
function op_plugin_deactivate() {
  
    if (!is_admin() || !get_option('op-plugin')) {
        return;
    }

    flush_rewrite_rules();

}

function op_plugin_uninstall() {
    if (!is_admin() || !get_option('op-plugin')) {
        return;
    }

    delete_option('op-plugin');
    flush_rewrite_rules();
}