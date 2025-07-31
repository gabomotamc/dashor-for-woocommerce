<?php
# TOTAL VALUES (NO CHARTS)

# 1. Total Customers
function dfw_get_total_customers( $replace = false ) {
    $cacheKey = DFW_PLUGIN_PREFIX.'-total-customers';
    $totalCustomers = get_transient( $cacheKey );

    // SET
    if ( false === $totalCustomers ) {
        $totalCustomers = count(get_users(['role' => 'customer']));
        set_transient( $cacheKey, $totalCustomers , DFW_PLUGIN_CACHE_LIFETIME );
    }    

    // REPLACE
    if ( false !== $totalCustomers && $replace ) {
        $totalCustomers = count(get_users(['role' => 'customer']));
        delete_transient( $cacheKey );
        set_transient( $cacheKey, $totalCustomers , DFW_PLUGIN_CACHE_LIFETIME );
    }

    return $totalCustomers;
}

# 2. Total Orders
function dfw_get_total_orders( $replace = false ) {
    $cacheKey = DFW_PLUGIN_PREFIX.'-total-orders';
    $totalOrders = get_transient( $cacheKey );

    // SET
    if ( false === $totalOrders ) {
        $totalOrders = count(wc_get_orders(['limit' => -1]));
        set_transient( $cacheKey, $totalOrders , DFW_PLUGIN_CACHE_LIFETIME );
    }    

    // REPLACE
    if ( false !== $totalOrders && $replace ) {
        $totalOrders = count(wc_get_orders(['limit' => -1]));
        delete_transient( $cacheKey );
        set_transient( $cacheKey, $totalOrders , DFW_PLUGIN_CACHE_LIFETIME );
    }

    return $totalOrders;
}

# 3. Total Products
function dfw_get_total_products( $replace = false ) {

    $cacheKey = DFW_PLUGIN_PREFIX.'-total-products';
    $totalProducts = get_transient( $cacheKey );

    // SET
    if ( false === $totalProducts ) {
        $totalProducts = count(wc_get_products(['limit' => -1]));
        set_transient( $cacheKey, $totalProducts , DFW_PLUGIN_CACHE_LIFETIME );
    }    

    // REPLACE
    if ( false !== $totalProducts && $replace ) {
        $totalProducts = count(wc_get_products(['limit' => -1]));
        delete_transient( $cacheKey );        
        set_transient( $cacheKey, $totalProducts , DFW_PLUGIN_CACHE_LIFETIME );
    }

    return $totalProducts;

}

# TOTAL VALUES (CHARTS)

# 📅 4. Revenue by Status (Last 7 Days) 
# & 
# 📅 5. Revenue by Status (Last 30 Days)
function dfw_get_revenues($days, $replace = false ) {

    $cacheKey = DFW_PLUGIN_PREFIX.'-get-revenues'.str_replace(' ','-',$days);
    $getReneveus = get_transient( $cacheKey );
    $revenues = [];
    $numberDays = (int)preg_replace('/\D/', '', $days);

    if ( $getReneveus !== false && $replace === false ) {
        return $getReneveus;
    }

    // Get orders from last 7 days
    $orders = wc_get_orders([
        'limit' => -1,
        'status' => ['completed', 'processing'],
        'date_created' => '>' . (new DateTime($days))->format('Y-m-d H:i:s'),
    ]);

    // Prepare daily revenue
    for ($i = $numberDays; $i >= 0; $i--) {
        $date = (new DateTime("-$i days"))->format('Y-m-d');
        $revenues[$date] = 0;
    }

    foreach ($orders as $order) {
        $date = $order->get_date_created()->date('Y-m-d');
        if (isset($revenues[$date])) {
            $revenues[$date] += floatval($order->get_total());
        }
    }

    // SET
    if ( false === $getReneveus ) {
        set_transient( $cacheKey, $revenues , DFW_PLUGIN_CACHE_LIFETIME );
    }    

    // REPLACE
    if ( false !== $getReneveus && $replace ) {
        delete_transient( $cacheKey );        
        set_transient( $cacheKey, $revenues , DFW_PLUGIN_CACHE_LIFETIME );
    }    

    return $revenues;
}


# 🔢 6. Total Orders by Each Status
function dfw_get_total_orders_by_statuses( $replace = false ) {

    $counts = [];
    $cacheKey = DFW_PLUGIN_PREFIX.'-total-orders-by-statuses';
    $data = get_transient( $cacheKey );

    if ( $data !== false && $replace === false ) {
        return $data;
    }

    $statuses = wc_get_order_statuses();
    foreach ($statuses as $status => $label) {
        $orders = wc_get_orders(['status' => $status, 'limit' => -1]);
        $counts[$label] = count($orders);
    }

    // SET
    if ( false === $data ) {
        set_transient( $cacheKey, $counts , DFW_PLUGIN_CACHE_LIFETIME );
    }    

    // REPLACE
    if ( false !== $data && $replace ) {
        delete_transient( $cacheKey );        
        set_transient( $cacheKey, $counts , DFW_PLUGIN_CACHE_LIFETIME );
    }

    return $counts;
}

# 💰 7. Total Revenue by Each Status
function dfw_get_total_revenues_by_statuses( $replace = false ) {

    $revenues = [];
    $cacheKey = DFW_PLUGIN_PREFIX.'-total-reveneus-by-statuses';
    $data = get_transient( $cacheKey );

    if ( $data !== false && $replace === false ) {
        return $data;
    }

    $statuses = wc_get_order_statuses();
    foreach ($statuses as $status => $label) {
        $orders = wc_get_orders(['status' => $status, 'limit' => -1]);
        $total = array_sum(array_map(fn($o) => $o->get_total(), $orders));
        $revenues[$label] = $total;
    }
    
    // SET
    if ( false === $data ) {
        set_transient( $cacheKey, $revenues , DFW_PLUGIN_CACHE_LIFETIME );
    }    

    // REPLACE
    if ( false !== $data && $replace ) {
        delete_transient( $cacheKey );        
        set_transient( $cacheKey, $revenues , DFW_PLUGIN_CACHE_LIFETIME );
    }

    return $revenues;
}

# 🔝 8. Most Ordered Products
function dfw_get_top_most_ordered_products($limit = 10, $replace = false) {

    global $wpdb;
    $products = [];
    $cacheKey = DFW_PLUGIN_PREFIX.'-top-most-ordered-products';
    $data = get_transient( $cacheKey );

    if ( $data !== false && $replace === false ) {
        return $data;
    }

    $orders = wc_get_orders([
        'limit' => -1,
        'status' => ['completed', 'processing'],
        'date_created' => '>' . (new DateTime('-30 days'))->format('Y-m-d H:i:s'),
    ]);

    $product_counts = [];

    foreach ($orders as $order) {
        foreach ($order->get_items() as $item) {
            $product_id = $item->get_product_id();
            $quantity = $item->get_quantity();

            if (!isset($product_counts[$product_id])) {
                $product_counts[$product_id] = 0;
            }

            $product_counts[$product_id] += $quantity;
        }
    }

    // Sort by quantity descending
    arsort($product_counts);

    // Get top products
    $top_products = array_slice($product_counts, 0, $limit, true);

    // Format result
    foreach ($top_products as $product_id => $count) {
        $product = wc_get_product($product_id);
        $products[] = [
            'name' => $product->get_name(),
            'id' => $product_id,
            'orders' => $count
        ];
    }

    // SET
    if ( false === $data ) {
        set_transient( $cacheKey, $products , DFW_PLUGIN_CACHE_LIFETIME );
    }    

    // REPLACE
    if ( false !== $data && $replace ) {
        delete_transient( $cacheKey );        
        set_transient( $cacheKey, $products , DFW_PLUGIN_CACHE_LIFETIME );
    }

    return $products;  
}

# 🔝 6. Minus Ordered Products
function dfw_get_top_least_ordered_products($limit = 10, $replace = false ) {
    global $wpdb;
    $products = [];
    $cacheKey = DFW_PLUGIN_PREFIX.'-top-least-ordered-products';
    $data = get_transient( $cacheKey );

    if ( $data !== false && $replace === false ) {
        return $data;
    }

    $products = $wpdb->get_results("
        SELECT om.meta_value AS product_id, 
               p.post_title AS name, 
               SUM(om_qty.meta_value) AS orders
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
        ORDER BY orders ASC
        LIMIT {$limit}
    ");

    // SET
    if ( false === $data ) {
        set_transient( $cacheKey, $products , DFW_PLUGIN_CACHE_LIFETIME );
    }    

    // REPLACE
    if ( false !== $data && $replace ) {
        delete_transient( $cacheKey );        
        set_transient( $cacheKey, $products , DFW_PLUGIN_CACHE_LIFETIME );
    }

    return $products;
}

function dfw_get_low_stock_tracked_products($threshold = 5, $limit = 20, $replace = false ) {

    $low_stock = [];
    $cacheKey = DFW_PLUGIN_PREFIX.'-low-stock-tracked-products';
    $data = get_transient( $cacheKey );

    if ( $data !== false && $replace === false ) {
        return $data;
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
    if ( false === $data ) {
        set_transient( $cacheKey, $low_stock , DFW_PLUGIN_CACHE_LIFETIME );
    }    

    // REPLACE
    if ( false !== $data && $replace ) {
        delete_transient( $cacheKey );        
        set_transient( $cacheKey, $low_stock , DFW_PLUGIN_CACHE_LIFETIME );
    }

    return $low_stock;
}

#🗂️ 7. Total Products by Category
function dfw_get_total_products_by_category( $replace = false ) {

    $data = [];
    $cacheKey = DFW_PLUGIN_PREFIX.'-low-stock-tracked-products';
    $data = get_transient( $cacheKey );

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
        set_transient( $cacheKey, $data , DFW_PLUGIN_CACHE_LIFETIME );
    }    

    // REPLACE
    if ( false !== $data && $replace ) {
        delete_transient( $cacheKey );        
        set_transient( $cacheKey, $data , DFW_PLUGIN_CACHE_LIFETIME );
    }

    return $data;
}

# 🌟 7. Customers with Most Orders
function dfw_get_top_customers_orders($limit = 10) {

    $list = [];
    $cacheKey = DFW_PLUGIN_PREFIX.'-top-customers-orders';
    $data = get_transient( $cacheKey );

    if ( $data !== false && $replace === false ) {
        return $data;
    }

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

    $list = array_map(function($id, $count) {
        $user = get_userdata($id);
        return ['name' => "{$user->display_name} ({$user->user_email})", 'orders' => $count];
    }, array_keys($top), $top);


    // SET
    if ( false === $data ) {
        set_transient( $cacheKey, $list , DFW_PLUGIN_CACHE_LIFETIME );
    }    

    // REPLACE
    if ( false !== $data && $replace ) {
        delete_transient( $cacheKey );        
        set_transient( $cacheKey, $list , DFW_PLUGIN_CACHE_LIFETIME );
    }

    return $list;    
}