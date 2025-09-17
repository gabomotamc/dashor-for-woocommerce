<?php
function dfw_dashor_woo_visualizer_page() {

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
            padding: 40px;
            font-size: 50px;
            font-weight: bolder;
            color: #4E79A7;     
        }

        .styled-list {
            list-style-type: none;
            padding: 15px;
        }

        .styled-list-success li {
            background-color: #d9ead3;  
            color: darkgreen;
            font-size: 18px;
            margin-top: 4px;            
            margin-bottom: 4px;
            text-align: center;
            padding: 10px;            
        }
        
        .styled-list-danger li {
            background-color: #f4cccc;
            color: darkred;
            font-size: 18px;      
            margin-top: 4px;            
            margin-bottom: 4px;
            text-align: center;
            padding: 10px;            
        }
        
        .styled-list-info li {
            background-color: #d0e0e3;            
            color: darkblue;
            font-size: 18px;            
            margin-top: 4px;            
            margin-bottom: 4px;
            text-align: center;
            padding: 10px;            
        }        
    </style>
    <?php
    echo '<div class="wrap"><h1>Dashor for Woocommerce - Charts</h1>';

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

            echo '<div class="chart-card"><h3>Produtos com baixo stock</h3><canvas id="productsLowStockChart"></canvas></div>';
            echo '<div class="chart-card"><h3>Total produtos por categoria</h3><canvas id="productsByCategoryChart"></canvas></div>';
            
            echo '<div class="chart-card"><h3>Produtos mais vendidos</h3><ul id="topMostOrderedProductsList" class="styled-list-success"></ul></div>';
            echo '<div class="chart-card"><h3>Produtos menos vendidos</h3><ul id="topLeastOrderedProductsList" class="styled-list-danger"></ul></div>';

            echo '<div class="chart-card"><h3>Clientes com mais pedidos</h3><ul id="topCustomersOrdersList" class="styled-list-info"></ul></div>'; 
    
        echo '</div>';

    echo '</div>';
   
}