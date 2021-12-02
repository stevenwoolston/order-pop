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
        //	all categories are excluded
        die();
    }
    
	$excluded_categories = $op_options['excluded_categories'] ? getExcludedCategories($op_options['excluded_categories']) : [];
    $pop_last_order_count = $op_options['pop_last_order_count'];
    // $initial_date = $op_options['order_query_start_date'] != '' ? $op_options['order_query_start_date'] : '0000-01-01';
    // $final_date = $op_options['order_query_end_date'] != '' ? $op_options['order_query_end_date'] : date('Y-m-d');
    $args = array(
        'limit' => $pop_last_order_count,
        'return' => 'ids',
        'status' => 'completed',
        'orderby' => 'date',
        'order' => 'DESC'
   );

    $orders = wc_get_orders($args);
	$order_id = $orders[array_rand($orders, 1)];
	$order = wc_get_order($order_id);
	if (!$order) {
		die();
	}

	$product = getQualifyingProduct($order, $excluded_categories);
	if (empty($product)) {
		die();
	}

    // $product = [];
    // foreach($order->get_items() as $item_id => $item) {
    //     $product = $item->get_product();
    // }

    $data  = $order->get_data(); // The Order data
    // $product_data = $item->get_product();

    // $order_id        = $data['id'];
    // $order_parent_id = $data['parent_id'];
    $order_date_created = $data['date_created']->date('Y-m-d H:i:s');

    //  get the product
    $product_name = $product->get_name();
    $product_url = $product->get_permalink();
    $product_image = $product->get_image();
    // $product_name = $product['title'];
    // Get the Customer ID (User ID)
    // $customer_id     = $data['customer_id'];
	$category = get_term_by('id', $product->get_category_ids()[0], 'product_cat')->name;

    ## BILLING INFORMATION:
    $billing_first_name = $data['billing']['first_name'];
    $billing_last_name  = $data['billing']['last_name'];
    // $billing_email      = $data['billing']['email'];
    // $billing_phone      = $data['billing']['phone'];
    // $billing_company    = $data['billing']['company'];
    // $billing_address_1  = $data['billing']['address_1'];
    // $billing_address_2  = $data['billing']['address_2'];
    $billing_city       = $data['billing']['city'];
    $billing_state      = $data['billing']['state'];
    // $billing_postcode   = $data['billing']['postcode'];
    // $billing_country    = $data['billing']['country'];  

    return
        array (
            'options' => array(
                'interval' => $op_options['pop_interval_minutes'],
                'sale_message' => $op_options['sale_message'],
                'pop_background_colour' => $op_options['pop_background_colour'],
                'pop_font_colour' => $op_options['pop_font_colour'],
                // 'test' => $excluded_categories
           ),
            'order_date' => $order_date_created,
            'customer' => array(
                'first_name' => $billing_first_name,
                'last_name' => $billing_last_name,
                'city' => ucwords(strtolower($billing_city)),
                'state' => $billing_state
           ),
            'product' => array(
                'name' => $product_name,
                'url' => $product_url,
                'image' => $product_image,
				'category' => $category,
           )
       );
}

function getExcludedCategories($categories) {
    $excluded_categories = [];
    foreach($categories as $cat) {
        // $term = get_term_by('id', $cat, 'product_cat', 'ARRAY_A');
        array_push($excluded_categories, get_term_by('id', $cat, 'product_cat', 'ARRAY_A')['name']);
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
