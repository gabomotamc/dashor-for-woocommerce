document.addEventListener('DOMContentLoaded', () => {
    fetch(DfwCharts.ajax_url + '?action=get_dfw_charts')
        .then(res => res.json())
        .then(data => {
            
            const barColors = [
            '#4E79A7', '#F28E2B', '#59A14F', '#E15759',
            '#B07AA1', '#76B7B2', '#EDC948', '#9C9C9C', '#3F51B8'
            ];

            document.getElementById('totalCustomersLabel').innerHTML = data.dfw_get_total_customers;
            document.getElementById('totalOrdersLabel').innerHTML = data.dfw_get_total_orders;
            document.getElementById('totalProductsLabel').innerHTML = data.dfw_get_total_products;
            
            new Chart(document.getElementById('weeklyRevenueChart'), {
                type: 'line',
                data: {
                    labels: Object.keys(data.dfw_weekly_revenue),
                    datasets: [{
                        label: 'Ganhos nos últimos 7 días',
                        data: Object.values(data.dfw_weekly_revenue),
                        backgroundColor: barColors
                    }]
                }
            });
            new Chart(document.getElementById('monthlyRevenueChart'), {
                type: 'line',
                data: {
                    labels: Object.keys(data.dfw_monthly_revenue),
                    datasets: [{
                        label: 'Ganhos nos últimos 30 días',
                        data: Object.values(data.dfw_monthly_revenue),
                        backgroundColor: barColors
                    }]
                }
            });

            new Chart(document.getElementById('totalOrdersByStatusChart'), {
                type: 'bar',
                data: {
                    labels: Object.keys(data.dfw_get_total_orders_by_statuses),
                    datasets: [{
                        label: 'Total de pedidos (por status)',
                        data: Object.values(data.dfw_get_total_orders_by_statuses),
                        backgroundColor: barColors
                    }]
                }
            });
            new Chart(document.getElementById('totalRevenueByStatusChart'), {
                type: 'bar',
                data: {
                    labels: Object.keys(data.dfw_get_total_revenues_by_statuses),
                    datasets: [{
                        label: 'Total ganhos pedidos (por status)',
                        data: Object.values(data.dfw_get_total_revenues_by_statuses),
                        backgroundColor: barColors
                    }]
                }
            }); 

            new Chart(document.getElementById('productsLowStockChart'), {
                type: 'bar',
                data: {
                    labels: Object.keys(data.dfw_get_low_stock_tracked_products),
                    datasets: [{
                        label: 'Produtos com baixo stock',
                        data: Object.values(data.dfw_get_low_stock_tracked_products),
                        backgroundColor: barColors
                    }]
                }
            });            
            new Chart(document.getElementById('productsByCategoryChart'), {
                type: 'bar',
                data: {
                    labels: Object.keys(data.dfw_get_total_products_by_category),
                    datasets: [{
                        label: 'Total produtos por categoria',
                        data: Object.values(data.dfw_get_total_products_by_category),
                        backgroundColor: barColors
                    }]
                }
            });       
            
            let topMostOrderedProductsList = document.getElementById('topMostOrderedProductsList');
            data.dfw_get_top_most_ordered_products.forEach(item => {
                let mostLi = document.createElement('li');
                mostLi.textContent = item.name +" ("+ item.orders +")";
                topMostOrderedProductsList.appendChild(mostLi);
            });

            let topLeastOrderedProductsList = document.getElementById('topLeastOrderedProductsList');
            data.dfw_get_top_least_ordered_products.forEach(item => {
                let leastLi = document.createElement('li');
                leastLi.textContent = item.name +" ("+ item.orders +")";
                topLeastOrderedProductsList.appendChild(leastLi);
            });            

            let topCustomersOrdersList = document.getElementById('topCustomersOrdersList');
            data.dfw_get_top_customers_orders.forEach(item => {
                let li = document.createElement('li');
                li.textContent = item.name +" ("+ item.orders +" pedidos)";
                topCustomersOrdersList.appendChild(li);
            });

        });
});
