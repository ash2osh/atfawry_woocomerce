<?php

/**
 * @link              http://ash2osh.com
 * @since             1.0.0
 * @package           ash2osh_faw
 *
 * @wordpress-plugin
 * Plugin Name:       @Fawry Payment
 * Plugin URI:        http://ash2osh.com
 * Description:       made by ash2osh for safkaonline site.
 * Version:           1.0.0
 * Author:            ash2osh
 * Author URI:        http://ash2osh.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ash2osh_faw
 * Domain Path:       /languages
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
/**
 * Check if WooCommerce is active
 * */
if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    exit;
}
////////////////CONSTANTS//////////////////////
define('ASH2OSH_FAW_TEXT_DOM', 'ash2osh_faw');
define('ASH2OSH_FAW_PAYMENT_METHOD','ash2osh_faw');
//////////////////////////////////////////////
// gets the absolute path to this plugin directory
function ash2osh_faw_plugin_path() {
    return untrailingslashit(plugin_dir_path(__FILE__));
}

if (!defined('ASH2OSH_FAW_URL')) {
    define('ASH2OSH_FAW_URL', plugin_dir_url(__FILE__));
}



//add class to woo commerce payment methods
function add_ash2osh_faw_gateway_class($methods) {
    $methods[] = 'wc_gateway_at_fawry_payment';
    return $methods;
}
add_filter('woocommerce_payment_gateways', 'add_ash2osh_faw_gateway_class');

//register class
function init_ash2osh_faw_gateway_class() {
    require_once 'inc/wc_gateway_at_fawry_payment.php';

}
add_action('plugins_loaded', 'init_ash2osh_faw_gateway_class');

/////////////////includes////////////////////////
require_once 'inc/thankyoupage_customizer.php';
require_once 'inc/cancel_unpaid_on_hold_schedule.php';
require_once 'inc/activation.php';

register_activation_hook( __FILE__, 'ash2osh_faw_activate' );
register_deactivation_hook(__FILE__, 'ash2osh_faw_deactivate');

