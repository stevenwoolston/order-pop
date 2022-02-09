<?php
/*
@package Woolston Web Design Developer Plugin
*/

if (!defined('ABSPATH')) exit;  // if direct access 

add_action("wp_ajax_op_get_order", "op_get_order");
add_action("wp_ajax_nopriv_op_get_order", "op_get_order");    

function op_get_order() {
    echo json_encode(op_get_orders());
    die();
}

function op_get_orders() {

    $op_options = get_option('op-plugin');
    if ($op_options['stop_notifications']) {
        die();
    }
    
	$pop_last_order_count = $op_options['pop_last_order_count'];
	// $initial_date = $op_options['order_query_start_date'] != '' ? $op_options['order_query_start_date'] : '0000-01-01';
	// $final_date = $op_options['order_query_end_date'] != '' ? $op_options['order_query_end_date'] : date('Y-m-d');
	$args = array(
		'type' => 'shop_order',
		'limit' => $pop_last_order_count,
		'return' => 'ids',
		'status' => 'completed',
		'orderby' => 'date',
		'order' => 'DESC',
		'meta_query' => array(
				'relation' => 'AND',
				array(
						'meta_key' => 'billing_first_name',
						'meta_value' => '',
						'meta_compare' => '!=',
				),
				array(
						'meta_key' => 'billing_last_name',
						'meta_value' => '',
						'meta_compare' => '!=',
				)            
		)
	);

	$query = new WC_Order_Query($args);
	$orders = $query->get_orders();
	shuffle($orders);
	$product = [];
	foreach($orders as $order_id) {
		if ($product) {
			break;
		}

		$order = wc_get_order($order_id);
		$order_products = $order->get_items();
		
		foreach($order_products as $order_product) {
			$product_id = $order_product->get_product()->get_id();
			if (!has_term(getExcludedCategories($op_options['excluded_categories']), 'product_cat', $product_id)) {
				$product = getProductFromOrderItem($order_product);
				break;
			}
		}
	}

	if (!$product) {
		die();
	}

	$category = get_term_by('id', $product->get_category_ids()[0], 'product_cat')->name;

	return
			array (
					'options' => array(
							'interval' => $op_options['pop_interval_minutes'],
							'sale_message' => $op_options['sale_message'],
							'pop_background_colour' => $op_options['pop_background_colour'],
							'pop_font_colour' => $op_options['pop_font_colour'],
							'debugging_enabled' => $op_options['debug_active'],
							'custom_css' => $op_options['custom_css'],
							// 'test' => $excluded_categories
					),
					'order_date' => $order->get_date_completed()->date('Y-m-d H:i:s'),
					'customer' => array(
							'first_name' => $order->get_billing_first_name(),
							'last_name'  => $order->get_billing_last_name(),	
							'city'  => ucwords(strtolower($order->get_billing_city())),
							'state'  => $order->get_billing_state(),
					),
					'product' => array(
							'name' => $product->get_name(),
							'url' => $product->get_permalink(),
							'image' => $product->get_image(),
							'category' => $category,
					),
			);
}

function getExcludedCategories($categories) {
    $excluded_categories = [];
    foreach($categories as $cat) {
        // $term = get_term_by('id', $cat, 'product_cat', 'ARRAY_A');
        array_push($excluded_categories, get_term_by('slug', $cat, 'product_cat', 'ARRAY_A')['slug']);
    }
    return $excluded_categories;		
}

function getQualifyingProduct($order, $excluded_categories) {
    $matchFound = false;
    foreach($order->get_items() as $item_id => $item) {
        $product = getProductFromOrderItem($item);
        if (!$excluded_categories || !has_term($excluded_categories, 'product_cat', $product->get_id())) {
            $matchFound = true;
            break;
        }
    }
    return $matchFound ? $product : null;
}

function getProductFromOrderItem($item) {
    if ($item->get_product()->get_parent_id() == 0) {
        return $item->get_product();
    }
    return wc_get_product($item->get_product()->get_parent_id());
}
