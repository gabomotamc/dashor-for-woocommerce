<?php
// Enqueue scripts
add_action('admin_enqueue_scripts', function() {
   //wp_enqueue_script('dashor-woo-visualizer-chartjs', 'https://cdn.jsdelivr.net/npm/chart.js', [], null, true);
    wp_enqueue_script('dashor-woo-visualizer-chartjs', PLUGIN_URL . 'assets/chart.js', [], null, true);   
    wp_enqueue_script('dashor-woo-visualizer', PLUGIN_URL . 'js/visualizer.js', ['dashor-woo-visualizer-chartjs'], null, true);
    wp_localize_script('dashor-woo-visualizer', 'WooData', ['ajax_url' => admin_url('admin-ajax.php')]);
});

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