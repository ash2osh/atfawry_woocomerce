<?php

function pageDifinitions() {
    return array(
        'fawrycallback' => array(
            'title' => __('fawrycallback', ASH2OSH_FAW_TEXT_DOM),
            'content' => ''
        ),
    );
}

function ash2osh_faw_activate() {

//////////////////////////////////////pages///////////////////////////////////
//no need anymore
//        $page_definitions = pageDifinitions();
//        foreach ($page_definitions as $slug => $page) {
//// Check that the page doesn't exist already
//            $query = new WP_Query('pagename=' . $slug);
//            if (!$query->have_posts()) {
//// Add the page using the data from the array above
//                wp_insert_post(
//                        array(
//                            'post_content' => $page['content'],
//                            'post_name' => $slug,
//                            'post_title' => $page['title'],
//                            'post_status' => 'publish',
//                            'post_type' => 'page',
//                            'ping_status' => 'closed',
//                            'comment_status' => 'closed',
//                        )
//                );
//            }
//        }
//        
//        
    ///////////////////////////////////////database////////////////////////////

    global $wpdb;

    $table_name = $wpdb->prefix . 'ash2osh_faw_callback_log';
    $wpdb_collate = $wpdb->collate;
    $createSQL = "CREATE TABLE {$table_name} (
         `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
	`date_called` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`data_rec`  TEXT NOT NULL ,
	PRIMARY KEY (`id`)
        )
         COLLATE {$wpdb_collate}";
    //echo $createSQL;die();
    require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
    dbDelta($createSQL);


    flush_rewrite_rules(); //automatic flushing of the WordPress rewrite rules for cpt
     //activate the schedule
    wp_schedule_event(time(), 'hourly', 'woocommerce_cancel_unpaid_submitted');
    //run immediate with http://localhost/wpshop/wp-cron.php?doing_wp_cron
}

function ash2osh_faw_deactivate() {
    //remove the schedule
    wp_clear_scheduled_hook( 'woocommerce_cancel_unpaid_submitted' );
}
