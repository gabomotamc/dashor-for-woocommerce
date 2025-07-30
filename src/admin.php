<?php
function dwv_admin_menus() {

    add_menu_page(
        'Dashor', 
        'Dashor', 
        'manage_options', 
        'dashor-woo-visualizer', 
        'dashor_woo_visualizer_page',
        'dashicons-chart-bar',
        26
    );

    add_submenu_page(
        'dashor-woo-visualizer',        
        'Dashor', 
        'Charts', 
        'manage_options', 
        'dashor-woo-visualizer', 
        'dashor_woo_visualizer_page',
        'dashicons-chart-bar',
    );    

    add_submenu_page(
        'dashor-woo-visualizer',
        'Dashor Store Tokens Manager',   // Page title
        'Store Tokens',           // Menu title
        'manage_options',         // Capability
        'dashor-woo-visualizer-store-tokens',           // Menu slug
        'dwv_store_tokens_admin_page', // Function to display main page
        'dashicons-admin-shop',        // Icon
    );

    /*add_submenu_page(
        'dashor-woo-visualizer',           // Parent slug
        'Dashor Add New Store Token',          // Page title
        'Dashor Add New Store',                // Menu title
        'manage_options',         // Capability
        'store_tokens_add',       // Menu slug
        'store_tokens_admin_form' // Function to display submenu page
    );*/
}
add_action('admin_menu', 'dwv_admin_menus');
