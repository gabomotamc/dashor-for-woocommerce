<?php
/*
Plugin Name: Dashor Woo Visualizer
Plugin URI: https://gaboworks.com/portfolio/dashor
Description: Generates charts for WooCommerce orders, products, and customers.
Version: 1.0.0
Author: Gabriel Mota Chong
Author URI: https://gaboworks.com/portfolio/dashor
License: GPL2
*/

if ( !defined('ABSPATH') ) exit;

define('PLUGIN_PATCH',plugin_dir_path( __FILE__ ));
define('PLUGIN_URL',plugin_dir_url(__FILE__));
define('API_DASHOR_DOMAIN','');
define('API_DASHOR_TOKEN','');

require_once(PLUGIN_PATCH."src/database.php");
require_once(PLUGIN_PATCH."src/api.php");
require_once(PLUGIN_PATCH."src/functions.php");

require_once(PLUGIN_PATCH."src/admin.php");
require_once(PLUGIN_PATCH."src/admin-pages.php");

require_once(PLUGIN_PATCH."src/metrics.php");
require_once(PLUGIN_PATCH."src/events.php");
require_once(PLUGIN_PATCH."src/scripts.php");

register_activation_hook(__FILE__, 'dwv_create_dashor_table');

add_action('save_post_product', 'on_product_saved', 10, 3);
function on_product_saved($post_id, $post, $update) {
    if (!$update) {
        // This is a new product
        do_action('my_custom_new_product_event', $post_id);
    }
}
