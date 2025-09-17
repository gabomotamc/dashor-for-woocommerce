<?php
// AJAX endpoint
add_action('wp_ajax_get_dfw_charts', function() {

    wp_send_json([
        'dfw_get_total_customers' => dfw_get_total_customers(),
        'dfw_get_total_orders' => dfw_get_total_orders(),
        'dfw_get_total_products' => dfw_get_total_products(),
        
        // Orders revenues
        'dfw_weekly_revenue' => dfw_get_revenues('-7 days'),
        'dfw_monthly_revenue' => dfw_get_revenues('-30 days'),

        // Orders
        'dfw_get_total_orders_by_statuses' => dfw_get_total_orders_by_statuses(),
        'dfw_get_total_revenues_by_statuses' => dfw_get_total_revenues_by_statuses(),

        // Products
        'dfw_get_low_stock_tracked_products' => dfw_get_low_stock_tracked_products(),
        'dfw_get_total_products_by_category' => dfw_get_total_products_by_category(),

        // Products (ordereds)
        'dfw_get_top_most_ordered_products' => dfw_get_top_most_ordered_products(),
        'dfw_get_top_least_ordered_products' => dfw_get_top_least_ordered_products(),  

        // Customers
        'dfw_get_top_customers_orders' => dfw_get_top_customers_orders()

    ]);
});