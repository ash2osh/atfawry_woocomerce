<?php

add_filter('woocommerce_thankyou_order_received_text', 'ash2osh_faw_woo_change_order_received_text', 10, 2);

/**
 * change the thank you text 
 *
 * @param  string $str 
 * @param  WC_Order $order
 */
function ash2osh_faw_woo_change_order_received_text($str, $order) {
    //  $new_str = sprintf( esc_html__( 'Please Pay for the order using the below Button', ASH2OSH_FAW_TEXT_DOM ), $count );
    if ($order->get_payment_method() == 'ash2osh_faw' && $order->get_status() == 'pending') {
        
    }
    $new_str = __('Please Pay for the order using the below Button', ASH2OSH_FAW_TEXT_DOM);
    $new_str .= '<br>' . getProductsJson($order->get_items());
    $new_str .= '<script> '
            . 'var merchantRefNum= ' . $order->get_id() . ';'
            . 'var productsJSON=JSON.stringify('.getProductsJson($order->get_items()).')'
            . '</script>';
    $new_str .= '<br>' . '<button id="faw_checkout" style="background-color: #ffd205;border: 1px solid #e7bf08;">
          <img  src="' . ASH2OSH_FAW_URL . 'images/logo_small.png"></button>';
    return $new_str;
}

/**
 * return the products as json array
 * 
 * @param WC_Order_Item[] $items
 */
function getProductsJson($items) {
    $arr = [];
    foreach ($items as $item) {
        $data = $item->get_data();
        $arr[] = [
            'productSKU' => $data['product_id'],
            'description' => $data['name'],
            'quantity' => $data['quantity'],
            'price' => $data['total'],
//"width":"1",
//      "height":"2",
//      "length":"3",
//      "weight":"600"
        ];
    }
    return json_encode($arr);
}

//add fawry js
//TODO check plugin settings for staging checkbox
function ash2osh_faw_scripts() {
    $php_vars = array(
        'siteurl' => get_option('siteurl'),
        'ajaxurl' => admin_url('admin-ajax.php'),
    );
    wp_localize_script('ash2osh_cob', 'CB_PHPVAR', $php_vars); //SP_PHPVAR must be unqiue
    if (is_page('checkout')) {
        wp_enqueue_script('fawry_js', 'https://atfawry.fawrystaging.com/ECommercePlugin/scripts/fawryPlugin.js');
        wp_enqueue_script('faw_checkout', plugin_dir_url(__DIR__) . 'scripts/faw_checkout.js', array('fawry_js'));
    }
}

add_action('wp_enqueue_scripts', 'ash2osh_faw_scripts');
