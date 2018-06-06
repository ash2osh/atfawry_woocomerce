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
    if ($order->get_payment_method() == ASH2OSH_FAW_PAYMENT_METHOD && $order->get_status() == 'on-hold') {
        //continue 
    } else {//other payment methods or already paid
        return $str;
    }
    $new_str = __('<h2>Please Pay for the order using the below Button</h2>', ASH2OSH_FAW_TEXT_DOM);
    //  $new_str .= '<br>' . getProductsJson($order->get_items());
//get the options //returns array 
    $options = get_option('woocommerce_' . ASH2OSH_FAW_PAYMENT_METHOD . '_settings');
    $expire_hours = $options['unpaid_expire'];
    if (!trim($expire_hours)) {
        $expire_hours = '48';
    }
    $new_str .= '<script> '
            . 'var merchant= "' . $options['merchant_identifier'] . '";'
            . 'var merchantRefNum= "' . $order->get_id() . '";'
            . 'var productsJSON=JSON.stringify(' . getProductsJson($order->get_items()) . ');'
            . 'var customerName= "' . $order->get_billing_first_name() . ' ' . $order->get_billing_last_name() . '";'
            . 'var  mobile = "' . $order->get_billing_phone() . '";'
            . 'var  email = "' . $order->get_billing_email() . '";'
            . 'var  customerId = "' . $order->get_customer_id() . '";'
            . 'var  orderExpiry = "' . $expire_hours . '";'
            . 'var  locale = "' . ( (strpos(get_locale(), 'ar') !== false) ? 'ar-eg' : 'en-gb') . '";'
            . '</script>';

    if (wp_get_theme() == 'Avada') {
        $new_str .= do_shortcode('[fusion_button link="#" text_transform="" title="" target="_self" link_attributes="" alignment="center" modal="" hide_on_mobile="small-visibility,medium-visibility,large-visibility" class="" id="faw_checkout" color="custom" button_gradient_top_color="#ffd205" button_gradient_bottom_color="#eac804" button_gradient_top_color_hover="#ffd205" button_gradient_bottom_color_hover="#ffd205" accent_color="" accent_hover_color="" type="3d" bevel_color="#049bce" border_width="" size="large" stretch="default" shape="pill" icon="" icon_position="left" icon_divider="no" animation_type="shake" animation_direction="left" animation_speed="0.3" animation_offset=""]'
                . '  <img  src="' . ASH2OSH_FAW_URL . 'images/logo_small.png">'
                . '[/fusion_button]');
    } else {
        $new_str .= '<br>' . '<button id="faw_checkout" style="background-color: #ffd205;border: 1px solid #e7bf08;">
          <img  src="' . ASH2OSH_FAW_URL . 'images/logo_small.png"></button>';
    }



    return $new_str;
    //TODO send mail with payment url (just in case ??)
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
            'price' => $data['total'] / $data['quantity'],
//"width":"1",
//      "height":"2",
//      "length":"3",
//      "weight":"600"
        ];
    }
    return json_encode($arr);
}

//add fawry js
function ash2osh_faw_scripts() {
    $options = get_option('woocommerce_' . ASH2OSH_FAW_PAYMENT_METHOD . '_settings');
    $isStaging = $options['is_staging'] == 'no' ? FALSE : TRUE;
    $php_vars = array(
        'siteurl' => get_option('siteurl'),
        'ajaxurl' => admin_url('admin-ajax.php'),
    );
    wp_localize_script('ash2osh_cob', 'CB_PHPVAR', $php_vars); //SP_PHPVAR must be unqiue
    if (is_page('checkout')) {
        if ($isStaging) {
            wp_enqueue_script('fawry_js', 'https://atfawry.fawrystaging.com/ECommercePlugin/scripts/fawryPlugin.js');
        } else {
            wp_enqueue_script('fawry_js', 'https://www.atfawry.com/ECommercePlugin/scripts/fawryPlugin.js');
        }


        wp_enqueue_script('faw_checkout', plugin_dir_url(__DIR__) . 'scripts/faw_checkout.js', array('jquery','fawry_js'));
    }
}

add_action('wp_enqueue_scripts', 'ash2osh_faw_scripts');
