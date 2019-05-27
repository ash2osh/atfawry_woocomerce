<?php

class wc_gateway_at_fawry_payment extends WC_Payment_Gateway {

    public function __construct() {
        global $woocommerce;
        $this->id = ASH2OSH_FAW_PAYMENT_METHOD;
        //  $this->method_title =__( '@Fawry','ash2osh_faw');
        $this->title = __('@Fawry', 'ash2osh_faw');
        $this->description = $this->get_option('description','ash2osh_faw');
		
        // $this->load_plugin_textdomain();
        $this->icon = ASH2OSH_FAW_URL . '/images/logo_small.png';
        $this->has_fields = FALSE;
        if (is_admin()) {
            $this->has_fields = true;
            $this->init_form_fields();
        }


        $this->init_form_fields();
        $this->init_settings();

        //you need to add a save hook for your settings:
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));

        //callback handle
        add_action('woocommerce_api_' . strtolower(get_class($this)), array($this, 'callback_handler'));
    }

    /**
     * Initialise Gateway Settings Form Fields
     *
     * @access public
     * @return void
     */
    function init_form_fields() {
        $this->form_fields = array(
            'enabled' => array(
                'title' => __('Enable/Disable', 'ash2osh_faw'),
                'type' => 'checkbox',
                'label' => __('Enable the @Fawry gateway', 'ash2osh_faw'),
                'default' => 'yes'
            ),
            'description' => array(
                'title' => __('Description', 'ash2osh_faw'),
                'type' => 'text',
                'description' => __('This is the description the user sees during checkout.', 'ash2osh_faw'),
                'default' => __('Pay for your Order with any Credit or Debit Card or through Fawry Machines', 'ash2osh_faw')
            ),
            'merchant_identifier' => array(
                'title' => __('Merchant Identifier', 'ash2osh_faw'),
                'type' => 'text',
                'description' => __('Your Merchant Identifier', 'ash2osh_faw'),
                'default' => '',
                'desc_tip' => true,
                'placeholder' => ''
            ),
            'hash_code' => array(
                'title' => __('Hash Code', 'ash2osh_faw'),
                'type' => 'password',
                'description' => __('Your Hash Code ', 'ash2osh_faw') . '<br>' . __(' The Callback URL is  : ', 'ash2osh_faw')
                . '<strong>' . home_url() . '/wc-api/wc_gateway_at_fawry_payment</strong>'
                ,
                'default' => '',
                'desc_tip' => FALSE,
                'placeholder' => ''
            ),
            'is_staging' => array(
                'title' => __('Is Staging Environment', 'ash2osh_faw'),
                'type' => 'checkbox',
                'label' => __('Enable staging (Testing) Environment'),
                'default' => 'no'
            ),
            'unpaid_expire' => array(
                'title' => __('Unpaid Order Expiry(Hours)', 'ash2osh_faw'),
                'type' => 'number',
                'label' => __('Unpaid Order Expiration in hours(defualt is 48 hours)'),
                'default' => 'no'
            ),
            'order_complete_after_payment' => array(
                'label' => __('set order status to complete instead of processing after payment', 'ash2osh_faw'),
                'type' => 'checkbox',
                'title' => __('Complete Order after payment', 'ash2osh_faw'),
                'default' => 'no'
            ),
                'stupid_mode' => array(
                'label' => __('enable order calculations based only on total price (that includes taxes and shipping)', 'ash2osh_faw'),
                'type' => 'checkbox',
                'title' => __('Enable Stupid Mode', 'ash2osh_faw'),
                'default' => 'no'
            ),
        );
    }

    function process_payment($order_id) {
        global $woocommerce;
        $order = new WC_Order($order_id);

        //  $order->update_meta_data('fawref', 'xxxx');
        // Mark as on-hold (we're awaiting the callback)
        $order->update_status('on-hold', __('Awaiting fawry payment Confirmation', 'ash2osh_faw'));

        // Reduce stock levels
        //this will enable stock timeout after the timeout the order is cancelled 
        //you can disable stock or change timeout in settings ->products->inventory
        $order->reduce_order_stock();

        // Remove cart
        $woocommerce->cart->empty_cart();

        // Return thankyou redirect
        return array(
            'result' => 'success',
            'redirect' => $this->get_return_url($order)
        );
    }

    public function callback_handler() {
        //log the callback in the database
        global $wpdb;
        $res = $wpdb->replace(
                $wpdb->prefix . 'ash2osh_faw_callback_log', array(
            'data_rec' => json_encode($_REQUEST)
                ), array(
            '%s',
                )
        );
  

        // handle callback
        $options = get_option('woocommerce_' . ASH2OSH_FAW_PAYMENT_METHOD . '_settings');

        $FawryRefNo = $_REQUEST['FawryRefNo']; //internal to fawry
        $MerchantRefNo = $_REQUEST['MerchantRefNo'];
        $OrderStatus = $_REQUEST['OrderStatus']; //New, PAID, CANCELED, DELIVERED, REFUNDED, EXPIRED
        $Amount = $_REQUEST['Amount'];
        $MessageSignature = $_REQUEST['MessageSignature'];

//echo $Amount;echo '-';echo $FawryRefNo ;echo '-';echo $MerchantRefNo;echo '-';echo $OrderStatus;echo '-';
        
        $expected_signature = $this->generateSignature($FawryRefNo, $Amount, $MerchantRefNo, $OrderStatus);
        //echo $expected_signature;exit;
        //check signature
        if (strtoupper($expected_signature) === strtoupper($MessageSignature)) {
            //get order
            $order = wc_get_order($MerchantRefNo);
            //check amount and  order status PAID
            if ($Amount == $order->get_total() && $OrderStatus == 'PAID') {
                $order->payment_complete();
                if (trim($options['order_complete_after_payment']) === 'yes') {
                    $order->update_status('completed');
                }

                echo 'SUCCESS';
            } else {
			if ($Amount == $order->get_total() && $OrderStatus == 'EXPIRED') {
                $order->update_status('cancelled');
                echo 'FAILD';
            }
			}
        } else {
            echo 'INVALID_SIGNATURE';
        }
        // echo ‘SUCCESS’, ‘FAILD’,‘INVALID_SIGNATURE’
        exit;
    }

    private function generateSignature($fawryRefNo, $amount, $merchantRefNum, $orderStatus) {
        $options = get_option('woocommerce_' . ASH2OSH_FAW_PAYMENT_METHOD . '_settings');

        $hashKey = trim($options['hash_code']);

        $buffer = $hashKey . $amount . $fawryRefNo . $merchantRefNum . $orderStatus;
        return md5($buffer);
    }

}
