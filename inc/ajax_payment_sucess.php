<?php

function ash2osh_faw_payment_recieved()
{
    $output = ['status' => 10];
    $merchantRefNum = (isset($_POST['merchantRefNum']) && $_POST['merchantRefNum']) ? $_POST['merchantRefNum'] : false;
    if ($merchantRefNum) {
        $order = wc_get_order($merchantRefNum);
        if ($order->get_user_id() === get_current_user_id()) {
            $order->update_meta_data('_rec_faw_pay', 1);
            $order->save();//dont forget
            $output = ['status' => 20];
        }
    }
    wp_send_json($output);
    wp_die();
}

add_action('wp_ajax_ash2osh_faw_payment_recieved', 'ash2osh_faw_payment_recieved');
add_action('wp_ajax_nopriv_ash2osh_faw_payment_recieved', 'ash2osh_faw_payment_recieved');//for guest checkout
