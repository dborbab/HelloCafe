// scripts.js

document.addEventListener('DOMContentLoaded', () => {
    // Mock Data
    const data = {
        totalCafeterias: 10,
        totalProducts: 150,
        newClients: 25,
        productsByCategory: {
            "Cafés Especiais": 50,
            "Chás": 30,
            "Salgados": 40,
            "Doces": 20,
            "Outros": 10
        },
        recentSales: [5, 15, 25, 35, 45, 55, 65]
    };

    // Update statistics
    document.getElementById('totalCafeterias').textContent = data.totalCafeterias;
    document.getElementById('totalProducts').textContent = data.totalProducts;
    document.getElementById('newClients').textContent = data.newClients;

    // Create Charts
    const ctxProductsByCategory = document.getElementById('productsByCategoryChart').getContext('2d');
    const productsByCategoryChart = new Chart(ctxProductsByCategory, {
        type: 'pie',
        data: {
            labels: Object.keys(data.productsByCategory),
            datasets: [{
                label: 'Produtos por Categoria',
                data: Object.values(data.productsByCategory),
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'],
                borderColor: '#fff',
                borderWidth: 1
            }]
        }
    });

    const ctxRecentSales = document.getElementById('recentSalesChart').getContext('2d');
    const recentSalesChart = new Chart(ctxRecentSales, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
            datasets: [{
                label: 'Vendas Recentes',
                data: data.recentSales,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        }
    });

    // Example Alerts
    const alerts = [
        "Novo produto adicionado: 'Café Mocha'",
        "Estoque baixo: 'Pão de Queijo'",
        "Novo cliente cadastrado: 'Maria Souza'"
    ];
    const alertsList = document.getElementById('alertsList');
    alerts.forEach(alert => {
        const li = document.createElement('li');
        li.textContent = alert;
        alertsList.appendChild(li);
    });
});
