<?php
// Enqueue scripts
add_action('admin_enqueue_scripts', function() {
    wp_enqueue_script('dashor-for-woocommerce-chartjs', DFW_PLUGIN_URL . 'assets/js/chartjs-lib.js', [], null, true);   
    wp_enqueue_script('dashor-for-woocommerce', DFW_PLUGIN_URL . 'assets/admin/js/render-charts.js', ['dashor-for-woocommerce-chartjs'], null, true);
    wp_localize_script('dashor-for-woocommerce', 'DfwCharts', ['ajax_url' => admin_url('admin-ajax.php')]);
});
