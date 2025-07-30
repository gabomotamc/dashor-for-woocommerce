<?php
function dwv_store_tokens_admin_page() {
    echo '<div class="wrap"><h1>Dashor Woo Visualizer - Store Tokens Manager</h1>';
    echo '<a href="?page=dashor-woo-visualizer-dashor-woo-visualizer-store-tokens&action=create" class="button button-primary">Add New Token</a>';
    
    // Display list or form based on action
    $action = $_GET['action'] ?? 'list';
    
    switch ($action) {
        case 'create':
            store_tokens_form();
            break;
        case 'edit':
            $id = intval($_GET['id']);
            store_tokens_form($id);
            break;
        default:
            store_tokens_list();
            break;
    }

    echo '</div>';
}

function store_tokens_list() {
    global $wpdb;
    $table = $wpdb->prefix . 'dwv_store_tokens';
    $results = $wpdb->get_results("SELECT * FROM $table");

    echo '<table class="wp-list-table widefat fixed striped">';
    echo '<thead><tr><th>ID</th><th>Name</th><th>Status</th><th>Actions</th></tr></thead><tbody>';
    foreach ($results as $row) {
        echo "<tr>
            <td>{$row->id}</td>
            <td>{$row->name}</td>
            <td>{$row->status}</td>
            <td>
                <a href='?page=dashor-woo-visualizer-store-tokens&action=edit&id={$row->id}'>Edit</a> | 
                <a href='?page=dashor-woo-visualizer-store-tokens&action=delete&id={$row->id}'>Delete</a>
            </td>
        </tr>";
    }
    echo '</tbody></table>';
}

function store_tokens_form($id = null) {
    global $wpdb;
    $table = $wpdb->prefix . 'dwv_store_tokens';
    $data = [
        'name' => '',
        'status' => '',
        'store_id' => '',
        'store_token' => '',
        'description' => '',
        'image' => ''
    ];

    if ($id) {
        $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE id = %d", $id));
        foreach ($data as $key => &$value) {
            $value = $row->$key ?? '';
        }
    }

    echo '<form method="post">';
    foreach ($data as $key => $value) {
        echo "<p><label>" . ucfirst(str_replace('_', ' ', $key)) . "</label><br>";
        echo "<input type='text' name='$key' value='" . esc_attr($value) . "' class='regular-text'></p>";
    }
    echo '<p><input type="submit" name="save_token" class="button-primary" value="Save Token"></p>';
    echo '</form>';

    if ($_POST['save_token']) {
        $fields = [];
        foreach ($data as $key => $_) {
            $fields[$key] = sanitize_text_field($_POST[$key]);
        }

        if ($id) {
            $wpdb->update($table, $fields, ['id' => $id]);
        } else {
            $wpdb->insert($table, $fields);
        }
        echo '<div class="updated"><p>Token saved!</p></div>';
    }
}

function store_tokens_handle_delete() {
    global $wpdb;
    if ($_GET['action'] === 'delete' && isset($_GET['id'])) {
        $table = $wpdb->prefix . 'store_tokens';
        $id = intval($_GET['id']);
        $wpdb->delete($table, ['id' => $id]);
        wp_redirect(admin_url('admin.php?page=dashor-woo-visualizer-store-tokens'));
        exit;
    }
}
add_action('admin_init', 'store_tokens_handle_delete');

function dashor_woo_visualizer_page() {

    ?>
    <style>
        .woo-dashboard-label-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .woo-dashboard-chart-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(600px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }        

        .chart-card {
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 3px 6px rgba(0,0,0,0.1);
            border-radius: 8px;
            transition: box-shadow 0.3s ease;
        }

        .chart-card:hover {
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }

        .chart-card canvas {
            max-width: 100%;
            height: auto;
        }

        .label-card {
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 3px 6px rgba(0,0,0,0.1);
            border-radius: 8px;
            transition: box-shadow 0.3s ease;
        }

        .label-card:hover {
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }

        .label-card div {
            max-width: 100%;
            height: auto;
            text-align: center;
            padding: 50px;
            font-size: 60px;
            font-weight: bolder;        
        }        
    </style>
    <?php

    echo '<div class="wrap"><h1>Dashor Woo Visualizer - Charts</h1>';

        echo '<div class="woo-dashboard-label-grid">';
            echo '<div class="label-card"><h3>Total clientes</h3><div id="totalCustomersLabel"></div></div>';
            echo '<div class="label-card"><h3>Total pedidos</h3><div id="totalOrdersLabel"></div></div>';
            echo '<div class="label-card"><h3>Total produtos</h3><div id="totalProductsLabel"></div></div>';
        echo '</div>';    

        echo '<div class="woo-dashboard-chart-grid">';        
            echo '<div class="chart-card"><h3>Ganhos nos últimos 7 días</h3><canvas id="weeklyRevenueChart"></canvas></div>';
            echo '<div class="chart-card"><h3>Ganhos nos últimos 30 días</h3><canvas id="monthlyRevenueChart"></canvas></div>';

            echo '<div class="chart-card"><h3>Total de pedidos por status</h3><canvas id="totalOrdersByStatusChart"></canvas></div>';
            echo '<div class="chart-card"><h3>Total de ganhos por status de pedidos</h3><canvas id="totalRevenueByStatusChart"></canvas></div>';

            echo '<div class="chart-card"><h3>Produtos mais vendidos</h3><canvas id="topMostOrderedProductsChart"></canvas></div>';
            echo '<div class="chart-card"><h3>Produtos menos vendidos</h3><canvas id="topLeastOrderedProductsChart"></canvas></div>';
            

            echo '<div class="chart-card"><h3>Produtos com baixo stock</h3><canvas id="productsLowStockChart"></canvas></div>';
            echo '<div class="chart-card"><h3>Total produtos por categoria</h3><canvas id="productsByCategoryChart"></canvas></div>';
            
            /*
            echo '<div class="chart-card"><h3>Clientes con mais pedidos</h3><canvas id="topCustomersChart"></canvas></div>';
            echo '<div class="chart-card"><h3>Clientes</h3><canvas id="customersChart"></canvas></div>';*/
    
        echo '</div>';

    echo '</div>';
   
}
