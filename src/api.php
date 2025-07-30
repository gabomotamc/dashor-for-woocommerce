<?php
function apiGet($resource) {
    $response = wp_remote_post(API_DASHOR_DOMAIN."/".$resource, [
        'method'    => 'GET',
        'headers'   => [
            'Authorization' => 'Bearer ' . API_DASHOR_TOKEN,
            'Content-Type'  => 'application/json',
        ],
        'timeout'   => 15,
    ]);

    if (is_wp_error($response)) {
        error_log('API POST Error: ' . $response->get_error_message());
    } else {
        error_log('Customer GET to API: ' . wp_remote_retrieve_body($response));
    }

    return json_decode( wp_remote_retrieve_body( $response ) , true);
}

function apiUpdate($resource,$body,$id) {
    $response = wp_remote_post(API_DASHOR_DOMAIN."/{$id}", [
        'method'    => 'PUT',
        'headers'   => [
            'Authorization' => 'Bearer ' . API_DASHOR_TOKEN,
            'Content-Type'  => 'application/json',
        ],
        'body'      => wp_json_encode($body), 
        'timeout'   => 15,
    ]);

    if (is_wp_error($response)) {
        error_log('API POST Error: ' . $response->get_error_message());
    } else {
        error_log('Customer UPDATE to API: ' . wp_remote_retrieve_body($response));
    }

    return json_decode( wp_remote_retrieve_body( $response ) , true);
}