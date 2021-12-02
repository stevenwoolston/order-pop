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

    if ($op_options['stop']) {
        die();
    }

    $excluded_categories = $op_options['excluded_categories'];

    $args = array(
        'limit' => 1,
        'return' => 'ids',
        'status' => 'completed',
        'orderby' => 'rand'
    );

    $query = new WC_Order_Query($args);
    $orders = $query->get_orders();
    $order = wc_get_order($orders[0]);
    // $order_items = $order->get_items();
    $product = [];
    foreach($order->get_items() as $item_id => $item) {
        $product = $item->get_product();
    }

    $data  = $order->get_data(); // The Order data
    // $product_data = $item->get_product();

    // $order_id        = $data['id'];
    // $order_parent_id = $data['parent_id'];
    $order_date_created = $data['date_created']->date('Y-m-d H:i:s');

    //  get the product
    $product_name = $product->get_name();
    $product_url = $product->get_permalink();
    // $product_name = $product['title'];
    // Get the Customer ID (User ID)
    // $customer_id     = $data['customer_id'];

    ## BILLING INFORMATION:
    $billing_first_name = $data['billing']['first_name'];
    $billing_last_name  = $data['billing']['last_name'];
    // $billing_email      = $data['billing']['email'];
    // $billing_phone      = $data['billing']['phone'];
    // $billing_company    = $data['billing']['company'];
    // $billing_address_1  = $data['billing']['address_1'];
    // $billing_address_2  = $data['billing']['address_2'];
    // $billing_city       = $data['billing']['city'];
    // $billing_state      = $data['billing']['state'];
    // $billing_postcode   = $data['billing']['postcode'];
    // $billing_country    = $data['billing']['country'];    

    return
        array (
            'options' => array(
                'interval' => $op_options['pop_interval_minutes'],
                'sale_message' => $op_options['sale_message']    
            ),
            'order_date' => $order_date_created,
            'customer' => array(
                'first_name' => $billing_first_name,
                'last_name' => $billing_last_name    
            ),
            'product' => array(
                'name' => $product_name,
                'url' => $product_url
            )
        );
}