<?php
# TOTAL VALUES (NO CHARTS)

# 1. Total Customers
function dfw_get_total_customers( $replace = false ) {
    $cacheKey = DFW_PLUGIN_PREFIX.'-total-customers';
    $totalCustomers = wp_cache_get( $cacheKey, DFW_PLUGIN_CACHE_GROUP );

    // SET
    if ( false === $totalCustomers ) {
        $totalCustomers = count(get_users(['role' => 'customer']));
        wp_cache_set( $cacheKey, $totalCustomers, DFW_PLUGIN_CACHE_GROUP , DFW_PLUGIN_CACHE_LIFETIME );
    }    

    // REPLACE
    if ( false !== $totalCustomers && $replace ) {
        $totalCustomers = count(get_users(['role' => 'customer']));
        wp_cache_replace( $cacheKey, $totalCustomers, DFW_PLUGIN_CACHE_GROUP , DFW_PLUGIN_CACHE_LIFETIME );
    }

    return $totalCustomers;
}

# 2. Total Orders
function dfw_get_total_orders( $replace = false ) {
    $cacheKey = DFW_PLUGIN_PREFIX.'-total-orders';
    $totalOrders = wp_cache_get( $cacheKey, DFW_PLUGIN_CACHE_GROUP );

    // SET
    if ( false === $totalOrders ) {
        $totalOrders = count(wc_get_orders(['limit' => -1]));
        wp_cache_set( $cacheKey, $totalOrders, DFW_PLUGIN_CACHE_GROUP , DFW_PLUGIN_CACHE_LIFETIME );
    }    

    // REPLACE
    if ( false !== $totalOrders && $replace ) {
        $totalOrders = count(wc_get_orders(['limit' => -1]));
        wp_cache_replace( $cacheKey, $totalOrders, DFW_PLUGIN_CACHE_GROUP , DFW_PLUGIN_CACHE_LIFETIME );
    }

    return $totalOrders;
}

# 3. Total Products
function dfw_get_total_products( $replace = false ) {

    $cacheKey = DFW_PLUGIN_PREFIX.'-total-products';
    $totalProducts = wp_cache_get( $cacheKey, DFW_PLUGIN_CACHE_GROUP );

    // SET
    if ( false === $totalProducts ) {
        $totalProducts = count(wc_get_products(['limit' => -1]));
        wp_cache_set( $cacheKey, $totalProducts, DFW_PLUGIN_CACHE_GROUP , DFW_PLUGIN_CACHE_LIFETIME );
    }    

    // REPLACE
    if ( false !== $totalProducts && $replace ) {
        $totalProducts = count(wc_get_products(['limit' => -1]));
        wp_cache_replace( $cacheKey, $totalProducts, DFW_PLUGIN_CACHE_GROUP , DFW_PLUGIN_CACHE_LIFETIME );
    }

    return $totalProducts;

}

# TOTAL VALUES (CHARTS)

# 📅 4. Revenue by Status (Last 7 Days) 
# & 
# 📅 5. Revenue by Status (Last 30 Days)
function dfw_get_revenue_by_statuses($days, $replace = false ) {

    $cacheKey = DFW_PLUGIN_PREFIX.'-get_revenue_by_statuses'.str_replace(' ','-',$days);
    $getReneveus = wp_cache_get( $cacheKey, DFW_PLUGIN_CACHE_GROUP );

    if ( $getReneveus !== false && $replace === false ) {
        return $getReneveus;
    }

    $statuses = wc_get_order_statuses();
    $revenues = [];
    $start_date = (new DateTime($days))->format('Y-m-d');

    foreach ($statuses as $status => $label) {
        $orders = wc_get_orders([
            'status' => $status,
            'limit' => -1,
            'date_created' => $start_date
        ]);

        $total = array_sum(array_map(fn($o) => $o->get_total(), $orders));
        $revenues[$label] = $total;
    }

    // SET
    if ( false === $getReneveus ) {
        wp_cache_set( $cacheKey, $revenues, DFW_PLUGIN_CACHE_GROUP , DFW_PLUGIN_CACHE_LIFETIME );
    }    

    // REPLACE
    if ( false !== $getReneveus && $replace ) {
        wp_cache_replace( $cacheKey, $revenues, DFW_PLUGIN_CACHE_GROUP , DFW_PLUGIN_CACHE_LIFETIME );
    }    

    return $revenues;
}


# 🔢 6. Total Orders by Each Status
function dfw_get_total_orders_by_statuses( $replace = false ) {

    $counts = [];
    $cacheKey = DFW_PLUGIN_PREFIX.'-total-orders-by-statuses';
    $counts = wp_cache_get( $cacheKey, DFW_PLUGIN_CACHE_GROUP );

    if ( $counts !== false && $replace === false ) {
        return $counts;
    }

    $statuses = wc_get_order_statuses();
    foreach ($statuses as $status => $label) {
        $orders = wc_get_orders(['status' => $status, 'limit' => -1]);
        $counts[$label] = count($orders);
    }

    // SET
    if ( false === $counts ) {
        wp_cache_set( $cacheKey, $counts, DFW_PLUGIN_CACHE_GROUP , DFW_PLUGIN_CACHE_LIFETIME );
    }    

    // REPLACE
    if ( false !== $counts && $replace ) {
        wp_cache_replace( $cacheKey, $counts, DFW_PLUGIN_CACHE_GROUP , DFW_PLUGIN_CACHE_LIFETIME );
    }

    return $counts;
}

# 💰 7. Total Revenue by Each Status
function dfw_get_total_revenues_by_statuses( $replace = false ) {

    $revenues = [];
    $cacheKey = DFW_PLUGIN_PREFIX.'-total-reveneus-by-statuses';
    $revenues = wp_cache_get( $cacheKey, DFW_PLUGIN_CACHE_GROUP );

    if ( $revenues !== false && $replace === false ) {
        return $revenues;
    }

    $statuses = wc_get_order_statuses();
    foreach ($statuses as $status => $label) {
        $orders = wc_get_orders(['status' => $status, 'limit' => -1]);
        $total = array_sum(array_map(fn($o) => $o->get_total(), $orders));
        $revenues[$label] = $total;
    }
    
    // SET
    if ( false === $revenues ) {
        wp_cache_set( $cacheKey, $revenues, DFW_PLUGIN_CACHE_GROUP , DFW_PLUGIN_CACHE_LIFETIME );
    }    

    // REPLACE
    if ( false !== $revenues && $replace ) {
        wp_cache_replace( $cacheKey, $revenues, DFW_PLUGIN_CACHE_GROUP , DFW_PLUGIN_CACHE_LIFETIME );
    }

    return $revenues;
}

# 🔝 8. Most Ordered Products
function dfw_get_top_most_ordered_products($limit = 10, $replace = false) {

    global $wpdb;
    $products = [];
    $cacheKey = DFW_PLUGIN_PREFIX.'-top-most-ordered-products';
    $products = wp_cache_get( $cacheKey, DFW_PLUGIN_CACHE_GROUP );

    if ( $products !== false && $replace === false ) {
        return $products;
    }    

    $products = $wpdb->get_results("
        SELECT order_item_name AS name, SUM(order_item_quantity) AS qty
        FROM {$wpdb->prefix}woocommerce_order_items oi
        JOIN {$wpdb->prefix}woocommerce_order_itemmeta om 
            ON oi.order_item_id = om.order_item_id
        WHERE om.meta_key = '_product_id'
        GROUP BY order_item_name
        ORDER BY qty DESC
        LIMIT {$limit}
    ");

    // SET
    if ( false === $products ) {
        wp_cache_set( $cacheKey, $products, DFW_PLUGIN_CACHE_GROUP , DFW_PLUGIN_CACHE_LIFETIME );
    }    

    // REPLACE
    if ( false !== $products && $replace ) {
        wp_cache_replace( $cacheKey, $products, DFW_PLUGIN_CACHE_GROUP , DFW_PLUGIN_CACHE_LIFETIME );
    }

    return $products;  
}

# 🔝 6. Minus Ordered Products
function dfw_get_top_least_ordered_products($limit = 10, $replace = false ) {
    global $wpdb;
    $products = [];
    $cacheKey = DFW_PLUGIN_PREFIX.'-top-least-ordered-products';
    $products = wp_cache_get( $cacheKey, DFW_PLUGIN_CACHE_GROUP );

    if ( $products !== false && $replace === false ) {
        return $products;
    } 

    $products = $wpdb->get_results("
        SELECT om.meta_value AS product_id, 
               p.post_title AS name, 
               SUM(om_qty.meta_value) AS qty
        FROM {$wpdb->prefix}woocommerce_order_itemmeta om
        JOIN {$wpdb->prefix}woocommerce_order_items oi 
            ON om.order_item_id = oi.order_item_id
        JOIN {$wpdb->prefix}woocommerce_order_itemmeta om_qty 
            ON oi.order_item_id = om_qty.order_item_id
        JOIN {$wpdb->prefix}posts p
            ON p.ID = om.meta_value
        WHERE om.meta_key = '_product_id'
          AND om_qty.meta_key = '_qty'
        GROUP BY om.meta_value
        ORDER BY qty ASC
        LIMIT {$limit}
    ");

    // SET
    if ( false === $products ) {
        wp_cache_set( $cacheKey, $products, DFW_PLUGIN_CACHE_GROUP , DFW_PLUGIN_CACHE_LIFETIME );
    }    

    // REPLACE
    if ( false !== $products && $replace ) {
        wp_cache_replace( $cacheKey, $products, DFW_PLUGIN_CACHE_GROUP , DFW_PLUGIN_CACHE_LIFETIME );
    }

    return $products;
}

function dfw_get_low_stock_tracked_products($threshold = 5, $limit = 20, $replace = false ) {

    $low_stock = [];
    $cacheKey = DFW_PLUGIN_PREFIX.'-low-stock-tracked-products';
    $low_stock = wp_cache_get( $cacheKey, DFW_PLUGIN_CACHE_GROUP );

    if ( $low_stock !== false && $replace === false ) {
        return $low_stock;
    }

    $products = wc_get_products([
        'limit' => -1,
        'stock_status' => 'instock',
    ]);

    $low_stock = [];

    foreach ($products as $product) {
        if ( ! $product->managing_stock() ) {
            continue; // Skip products that don't track stock
        }

        $stock_qty = $product->get_stock_quantity();

        if ($stock_qty !== null && $stock_qty <= $threshold) {
            $low_stock[$product->get_name()] = $stock_qty;
        }
    }

    // SET
    if ( false === $low_stock ) {
        wp_cache_set( $cacheKey, $low_stock, DFW_PLUGIN_CACHE_GROUP , DFW_PLUGIN_CACHE_LIFETIME );
    }    

    // REPLACE
    if ( false !== $low_stock && $replace ) {
        wp_cache_replace( $cacheKey, $low_stock, DFW_PLUGIN_CACHE_GROUP , DFW_PLUGIN_CACHE_LIFETIME );
    }

    return $low_stock;
}

#🗂️ 7. Total Products by Category
function dfw_get_total_products_by_category( $replace = false ) {

    $data = [];
    $cacheKey = DFW_PLUGIN_PREFIX.'-low-stock-tracked-products';
    $data = wp_cache_get( $cacheKey, DFW_PLUGIN_CACHE_GROUP );

    if ( $data !== false && $replace === false ) {
        return $data;
    }

    $categories = get_terms(['taxonomy' => 'product_cat', 'hide_empty' => false]);

    foreach ($categories as $cat) {
        $products = wc_get_products(['category' => [$cat->slug], 'limit' => -1]);
        $data[$cat->name] = count($products);
    }

    // SET
    if ( false === $data ) {
        wp_cache_set( $cacheKey, $data, DFW_PLUGIN_CACHE_GROUP , DFW_PLUGIN_CACHE_LIFETIME );
    }    

    // REPLACE
    if ( false !== $data && $replace ) {
        wp_cache_replace( $cacheKey, $data, DFW_PLUGIN_CACHE_GROUP , DFW_PLUGIN_CACHE_LIFETIME );
    }

    return $data;
}

# 🌟 7. Customers with Most Orders
function get_top_customers($limit = 10) {
    $orders = wc_get_orders(['limit' => -1]);
    $customer_counts = [];

    foreach ($orders as $order) {
        $customer_id = $order->get_customer_id();
        if ($customer_id) {
            $customer_counts[$customer_id] = ($customer_counts[$customer_id] ?? 0) + 1;
        }
    }

    arsort($customer_counts);
    $top = array_slice($customer_counts, 0, $limit, true);

    return array_map(function($id, $count) {
        $user = get_userdata($id);
        return ['name' => $user->display_name, 'orders' => $count];
    }, array_keys($top), $top);
}