<?php
function dwv_create_dashor_table() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'dwv_dashor';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        status VARCHAR(100) DEFAULT 'active' NOT NULL,
        name VARCHAR(255) NOT NULL,  
        web_domain VARCHAR(255) NOT NULL,
        api_domain VARCHAR(255) NOT NULL,
        api_version VARCHAR(50) NOT NULL,
        store_uid VARCHAR(150) NOT NULL,
        store_token VARCHAR(255) NOT NULL,
        description TEXT DEFAULT NULL,
        image TEXT DEFAULT NULL,
        created_at TIMESTAMP DEFAULT NULL,
        updated_at TIMESTAMP DEFAULT NULL,
        deleted_at TIMESTAMP DEFAULT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
}

