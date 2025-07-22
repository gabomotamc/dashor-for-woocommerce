document.addEventListener('DOMContentLoaded', () => {
    fetch(WooData.ajax_url + '?action=get_woo_data')
        .then(res => res.json())
        .then(data => {

            document.getElementById('totalCustomersLabel').innerHTML = data.get_total_customers;
            document.getElementById('totalOrdersLabel').innerHTML = data.get_total_orders;
            document.getElementById('totalProductsLabel').innerHTML = data.get_total_products;
            
            new Chart(document.getElementById('weeklyRevenueChart'), {
                type: 'bar',
                data: {
                    labels: Object.keys(data.weekly_revenue),
                    datasets: [{
                        label: 'Ganhos nos últimos 7 días',
                        data: Object.values(data.weekly_revenue),
                        backgroundColor: '#3F51B8'
                    }]
                }
            });
            new Chart(document.getElementById('monthlyRevenueChart'), {
                type: 'bar',
                data: {
                    labels: Object.keys(data.monthly_revenue),
                    datasets: [{
                        label: 'Ganhos nos últimos 30 días',
                        data: Object.values(data.monthly_revenue),
                        backgroundColor: '#3F51B8'
                    }]
                }
            });

            new Chart(document.getElementById('totalOrdersByStatusChart'), {
                type: 'bar',
                data: {
                    labels: Object.keys(data.total_orders_by_status),
                    datasets: [{
                        label: 'Total de pedidos (por status)',
                        data: Object.values(data.total_orders_by_status),
                        backgroundColor: '#3F51B5'
                    }]
                }
            });
            new Chart(document.getElementById('totalRevenueByStatusChart'), {
                type: 'bar',
                data: {
                    labels: Object.keys(data.total_revenue_by_status),
                    datasets: [{
                        label: 'Total ganhos pedidos (por status)',
                        data: Object.values(data.total_revenue_by_status),
                        backgroundColor: '#3F51B6'
                    }]
                }
            }); 
            
            new Chart(document.getElementById('topMostOrderedProductsChart'), {
                type: 'bar',
                data: {
                    labels: Object.keys(data.top_most_ordered_products),
                    datasets: [{
                        label: 'Top produtos mais vendidos',
                        data: Object.values(data.top_most_ordered_products),
                        backgroundColor: '#3F51B8'
                    }]
                }
            });
            new Chart(document.getElementById('topLeastOrderedProductsChart'), {
                type: 'bar',
                data: {
                    labels: Object.keys(data.top_least_ordered_products),
                    datasets: [{
                        label: 'Top produtos menos vendidos',
                        data: Object.values(data.top_least_ordered_products),
                        backgroundColor: '#3F51B8'
                    }]
                }
            });

            new Chart(document.getElementById('productsLowStockChart'), {
                type: 'bar',
                data: {
                    labels: Object.keys(data.products_low_stock),
                    datasets: [{
                        label: 'Produtos com baixo stock',
                        data: Object.values(data.products_low_stock),
                        backgroundColor: '#3F51B8'
                    }]
                }
            });            
            new Chart(document.getElementById('productsByCategoryChart'), {
                type: 'bar',
                data: {
                    labels: Object.keys(data.products_by_category),
                    datasets: [{
                        label: 'Total produtos por categoria',
                        data: Object.values(data.products_by_category),
                        backgroundColor: '#3F51B8'
                    }]
                }
            });            
            
            new Chart(document.getElementById('topCustomersChart'), {
                type: 'bar',
                data: {
                    labels: Object.keys(data.top_customers),
                    datasets: [{
                        label: 'Top clientes com mais vendas',
                        data: Object.values(data.top_customers),
                        backgroundColor: '#3F51B8'
                    }]
                }
            });
            
            new Chart(document.getElementById('customersChart'), {
                type: 'doughnut',
                data: {
                    labels: data.customers.map(c => c.name),
                    datasets: [{data: data.customers.map(() => 1), backgroundColor: '#9C27B0' }]
                }
            });

        });
});
