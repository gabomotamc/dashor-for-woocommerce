<?php
add_action('woocommerce_created_customer', 'dwv_trigger_additional_customer_event', 10, 3);
function dwv_trigger_additional_customer_event($customer_id, $new_customer_data, $password_generated) {
    // Dispatch your custom event
    do_action('dwv_send_total_customer_action', $customer_id, $new_customer_data, $password_generated);

    // Example: Log or notify
    error_log("New customer created: " . print_r($new_customer_data, true));
}

add_action('dwv_send_total_customer_action', 'dwv_send_total_customer_event', 10, 3);
function dwv_send_total_customer_event($customer_id, $new_customer_data, $password_generated) {

    $storeId = 123;

    $getMetric = apiGet($resource = "metrics/stores/{$storeId}/total/customers/total-customers");
    if ( isset( $getMetric['data']['id'] ) ) {
        apiUpdate(
            $resource = "metrics",
            $body = [ "value" => [ "total" => get_total_customers($replace = true) ] ]
            ,$id = $getMetric['data']['id']
        );
    }

}