<?php
/*
@package Woolston Web Design Developer Plugin
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

add_action("wp_ajax_op_get_order", "op_get_order");
add_action("wp_ajax_nopriv_op_get_order", "op_get_order");

function op_get_order() {
   echo json_encode(op_get_orders());
   die();
}

function op_get_orders() {
    $selected_order = 0;

    $args = array(
        'limit' => 1,
        'return' => 'ids',
        'status' => 'completed',
        'orderby' => 'rand'
    );

    $query = new WC_Order_Query($args);
    $orders = $query->get_orders();
    $order = wc_get_order($orders[0]);
    $order_items = $order->get_items();
    $product = [];
    foreach($order->get_items() as $item_id => $item) {
        $product = $item->get_product();        
    }

    // Get the Order meta data in an unprotected array
    $data  = $order->get_data(); // The Order data
    // $product_data = $order_item->get_product();

    $order_id        = $data['id'];
    $order_parent_id = $data['parent_id'];
    $order_date_created = $data['date_created']->date('Y-m-d H:i:s');

    //  get the product
    $product_name = $item->get_name();
    // Get the Customer ID (User ID)
    $customer_id     = $data['customer_id'];

    ## BILLING INFORMATION:

    $billing_email      = $data['billing']['email'];
    $billing_phone      = $data['billing']['phone'];
    $billing_first_name = $data['billing']['first_name'];
    $billing_last_name  = $data['billing']['last_name'];
    $billing_company    = $data['billing']['company'];
    $billing_address_1  = $data['billing']['address_1'];
    $billing_address_2  = $data['billing']['address_2'];
    $billing_city       = $data['billing']['city'];
    $billing_state      = $data['billing']['state'];
    $billing_postcode   = $data['billing']['postcode'];
    $billing_country    = $data['billing']['country'];    

    return 
        array (
            'product_name' => $product_name,
            'order_date' => $order_date_created,
            'first_name' => $billing_first_name,
            'last_name' => $billing_last_name
        );        
}