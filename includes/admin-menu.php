<?php
function dwv_admin_menus() {

    add_menu_page(
        'Dashor', 
        'Dashor', 
        'manage_options', 
        'dashor-for-woocommerce', 
        'dfw_dashor_woo_visualizer_page',
        'dashicons-chart-bar',
        26
    );

}
add_action('admin_menu', 'dwv_admin_menus');