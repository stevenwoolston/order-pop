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
		'stop_notifications' => true,
		'pop_interval_between_pop_refresh_seconds' => 10,
		'pop_interval_between_pops_after_dismissed_minutes' => 1440,
		'pop_background_colour' => '#15bbd1',
		'pop_font_colour' => '#ffffff',
		'pop_last_order_count' => 25,
		'anonomise_customer' => false,
		'debug_active' => false,
		'custom_css' => get_default_css(),
		'utm_code' => '',
		'excluded_categories' => Array()
	);
	
	update_option('op-plugin', $default_options);
	flush_rewrite_rules();
}

function get_default_css() {
	return (get_option('op-plugin')['custom_css']) ?
		get_option('op-plugin')['custom_css'] : ``;
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