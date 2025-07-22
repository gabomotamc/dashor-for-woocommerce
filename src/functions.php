<?php
# TOTAL VALUES (NO CHARTS)

# 1. Total Customers
function get_total_customers() {
    $customers = get_users(['role' => 'customer']);
    return count($customers);
}

# 2. Total Orders
function get_total_orders() {
    $orders = wc_get_orders(['limit' => -1]);
    return count($orders);
}

# 3. Total Products
function get_total_products() {
    $products = wc_get_products(['limit' => -1]);
    return count($products);
}

# TOTAL VALUES (CHARTS)

# 📅 4. Revenue by Status (Last 7 Days)
function get_revenue_by_status_last_7_days() {
    $statuses = wc_get_order_statuses();
    $revenues = [];
    $start_date = (new DateTime('-7 days'))->format('Y-m-d');

    foreach ($statuses as $status => $label) {
        $orders = wc_get_orders([
            'status' => $status,
            'limit' => -1,
            'date_created' => $start_date
        ]);

        $total = array_sum(array_map(fn($o) => $o->get_total(), $orders));
        $revenues[$label] = $total;
    }

    return $revenues;
}

# 📅 5. Revenue by Status (Last 30 Days)
function get_revenue_by_status_last_30_days() {
    $statuses = wc_get_order_statuses();
    $revenues = [];
    $start_date = (new DateTime('-30 days'))->format('Y-m-d');

    foreach ($statuses as $status => $label) {
        $orders = wc_get_orders([
            'status' => $status,
            'limit' => -1,
            'date_created' => $start_date
        ]);

        $total = array_sum(array_map(fn($o) => $o->get_total(), $orders));
        $revenues[$label] = $total;
    }

    return $revenues;
}


# 🔢 6. Total Orders by Each Status
function get_total_orders_by_status() {
    $statuses = wc_get_order_statuses();
    $counts = [];

    foreach ($statuses as $status => $label) {
        $orders = wc_get_orders(['status' => $status, 'limit' => -1]);
        $counts[$label] = count($orders);
    }

    return $counts;
}

# 💰 7. Total Revenue by Each Status
function get_total_revenue_by_status() {
    $statuses = wc_get_order_statuses();
    $revenues = [];

    foreach ($statuses as $status => $label) {
        $orders = wc_get_orders(['status' => $status, 'limit' => -1]);
        $total = array_sum(array_map(fn($o) => $o->get_total(), $orders));
        $revenues[$label] = $total;
    }

    return $revenues;
}

# 🔝 8. Most Ordered Products
function get_top_most_ordered_products($limit = 10) {
    global $wpdb;
    $results = $wpdb->get_results("
        SELECT order_item_name AS name, SUM(order_item_quantity) AS qty
        FROM {$wpdb->prefix}woocommerce_order_items oi
        JOIN {$wpdb->prefix}woocommerce_order_itemmeta om 
            ON oi.order_item_id = om.order_item_id
        WHERE om.meta_key = '_product_id'
        GROUP BY order_item_name
        ORDER BY qty DESC
        LIMIT {$limit}
    ");

    return $results;
}

# 🔝 6. Minus Ordered Products
function get_top_least_ordered_products($limit = 10) {
    global $wpdb;

    $results = $wpdb->get_results("
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

    return $results;
}

function get_low_stock_tracked_products($threshold = 5, $limit = 20) {
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
    return $low_stock;
}

#🗂️ 7. Total Products by Category
function get_total_products_by_category() {
    $categories = get_terms(['taxonomy' => 'product_cat', 'hide_empty' => false]);
    $data = [];

    foreach ($categories as $cat) {
        $products = wc_get_products(['category' => [$cat->slug], 'limit' => -1]);
        $data[$cat->name] = count($products);
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


