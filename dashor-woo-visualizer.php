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

require_once(plugin_dir_path( __FILE__ )."src/functions.php");

// Enqueue scripts
add_action('admin_enqueue_scripts', function() {
   //wp_enqueue_script('dashor-woo-visualizer-chartjs', 'https://cdn.jsdelivr.net/npm/chart.js', [], null, true);
    wp_enqueue_script('dashor-woo-visualizer-chartjs', plugin_dir_url(__FILE__) . 'assets/chart.js', [], null, true);   
    wp_enqueue_script('dashor-woo-visualizer', plugin_dir_url(__FILE__) . 'js/visualizer.js', ['dashor-woo-visualizer-chartjs'], null, true);
    wp_localize_script('dashor-woo-visualizer', 'WooData', ['ajax_url' => admin_url('admin-ajax.php')]);
});

// Create admin page
add_action('admin_menu', function() {
    add_menu_page('Dashor', 'Dashor', 'manage_options', 'dashor-woo-visualizer', 'dashor_woo_visualizer_page','dashicons-chart-bar',55);
});

function dashor_woo_visualizer_page() {

    ?>
    <style>
        .woo-dashboard-label-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .woo-dashboard-chart-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(600px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }        

        .chart-card {
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 3px 6px rgba(0,0,0,0.1);
            border-radius: 8px;
            transition: box-shadow 0.3s ease;
        }

        .chart-card:hover {
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }

        .chart-card canvas {
            max-width: 100%;
            height: auto;
        }

        .label-card {
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 3px 6px rgba(0,0,0,0.1);
            border-radius: 8px;
            transition: box-shadow 0.3s ease;
        }

        .label-card:hover {
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }

        .label-card div {
            max-width: 100%;
            height: auto;
            text-align: center;
            padding: 50px;
            font-size: 60px;
            font-weight: bolder;        
        }        
    </style>
    <?php

    echo '<div class="wrap"><h1>Dashor Woo Visualizer</h1>';

        echo '<div class="woo-dashboard-label-grid">';
            echo '<div class="label-card"><h3>Total clientes</h3><div id="totalCustomersLabel"></div></div>';
            echo '<div class="label-card"><h3>Total pedidos</h3><div id="totalOrdersLabel"></div></div>';
            echo '<div class="label-card"><h3>Total produtos</h3><div id="totalProductsLabel"></div></div>';
        echo '</div>';    

        echo '<div class="woo-dashboard-chart-grid">';        
            echo '<div class="chart-card"><h3>Ganhos nos últimos 7 días</h3><canvas id="weeklyRevenueChart"></canvas></div>';
            echo '<div class="chart-card"><h3>Ganhos nos últimos 30 días</h3><canvas id="monthlyRevenueChart"></canvas></div>';

            echo '<div class="chart-card"><h3>Total de pedidos por status</h3><canvas id="totalOrdersByStatusChart"></canvas></div>';
            echo '<div class="chart-card"><h3>Total de ganhos por status de pedidos</h3><canvas id="totalRevenueByStatusChart"></canvas></div>';

            echo '<div class="chart-card"><h3>Produtos mais vendidos</h3><canvas id="topMostOrderedProductsChart"></canvas></div>';
            echo '<div class="chart-card"><h3>Produtos menos vendidos</h3><canvas id="topLeastOrderedProductsChart"></canvas></div>';
            

            echo '<div class="chart-card"><h3>Produtos com baixo stock</h3><canvas id="productsLowStockChart"></canvas></div>';
            echo '<div class="chart-card"><h3>Total produtos por categoria</h3><canvas id="productsByCategoryChart"></canvas></div>';
            
            /*
            echo '<div class="chart-card"><h3>Clientes con mais pedidos</h3><canvas id="topCustomersChart"></canvas></div>';
            echo '<div class="chart-card"><h3>Clientes</h3><canvas id="customersChart"></canvas></div>';*/
    
        echo '</div>';

    echo '</div>';
   
}

// AJAX endpoint
add_action('wp_ajax_get_woo_data', function() {

    wp_send_json([
        'get_total_customers' => get_total_customers(),
        'get_total_orders' => get_total_orders(),
        'get_total_products' => get_total_products(),
        
        // Orders revenues
        'weekly_revenue' => get_revenue_by_status_last_7_days(),
        'monthly_revenue' => get_revenue_by_status_last_30_days(),

        // Orders
        'total_orders_by_status' => get_total_orders_by_status(),
        'total_revenue_by_status' => get_total_revenue_by_status(),

        // Products (ordereds)
        'top_most_ordered_products' => get_top_most_ordered_products(),
        'top_least_ordered_products' => get_top_least_ordered_products(),

        // Products
        'products_low_stock' => get_low_stock_tracked_products(),
        'products_by_category' => get_total_products_by_category(),

        /*
        'top_customers' => get_top_customers(),
        'customers' => array_map(fn($c) => ['name' => $c->display_name], get_users(['role' => 'customer'])),*/
    ]);
});
