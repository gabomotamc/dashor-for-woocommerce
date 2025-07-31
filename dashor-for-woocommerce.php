<?php
/*
Plugin Name: Dashor for Woocommerce
Plugin URI: https://gaboworks.com/portfolio/dashor
Description: Generates charts for WooCommerce orders, products, and customers.
Version: 1.0.0
Author: Gabriel Mota Chong
Author URI: https://gaboworks.com/portfolio/dashor
License: GPL2
*/

if ( !defined('ABSPATH') ) exit;

define('DFW_PLUGIN_PATH',plugin_dir_path( __FILE__ ));
define('DFW_PLUGIN_URL',plugin_dir_url(__FILE__));
define('DFW_PLUGIN_VERSION',"1.0.0");
define('DFW_PLUGIN_PREFIX',"dfw");
define('DFW_PLUGIN_CACHE_LIFETIME',1800);
define('DFW_PLUGIN_CACHE_GROUP',"dfw_cache_queries");

require_once(DFW_PLUGIN_PATH."src/helpers.php");
require_once(DFW_PLUGIN_PATH."src/metrics.php");
require_once(DFW_PLUGIN_PATH."src/admin-menu.php");
require_once(DFW_PLUGIN_PATH."src/admin-pages.php");
require_once(DFW_PLUGIN_PATH."src/scripts.php");