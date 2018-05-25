<?php


function ash2osh_faw_get_unpaid_submitted() {        
        global $wpdb;
        
        $unpaid_submitted = $wpdb->get_col( $wpdb->prepare( "
                SELECT posts.ID
                FROM {$wpdb->posts} AS posts
                WHERE posts.post_status = 'wc-on-hold'
                AND posts.post_date < %s
        ", date( 'Y-m-d H:i:s', strtotime('-1 hours') ) ) ); //TODO change to value setting by plugin
        
        return $unpaid_submitted;
}

function ash2osh_faw_wc_cancel_unpaid_submitted() {        
        $unpaid_submit = get_unpaid_submitted();
        
        if ( $unpaid_submit ) {                
                foreach ( $unpaid_submit as $unpaid_order ) {                        
                        $order = wc_get_order( $unpaid_order );
                        $cancel_order = True;

                        foreach  ( $order->get_items() as $item_key => $item_values) {                                
                                $manage_stock = get_post_meta( $item_values['variation_id'], '_manage_stock', true );
                                if ( $manage_stock == "no" ) {                                        
                                        $payment_method = $order->get_payment_method();                                        
                                        if ( $payment_method == "cheque" ) {
                                                $cancel_order = False;
                                        }
                                }                                
                        }
                        if ( $cancel_order == True ) {
                                $order -> update_status( 'cancelled', __( 'Unpaid submission expired after two days.', 'woocommerce') );
                        }
                }
        }        
}
add_action( 'woocommerce_cancel_unpaid_submitted', 'ash2osh_faw_wc_cancel_unpaid_submitted' );//for customization purposes


wp_schedule_event(time(), 'hourly', 'ash2osh_faw_wc_cancel_unpaid_submitted');
