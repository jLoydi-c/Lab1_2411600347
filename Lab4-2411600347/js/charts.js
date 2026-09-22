let categoryChart = null;
let stockStatusChart = null;
let topProductsChart = null;

const chartColors = {
    navy: '#0F172A',
    blue: '#2563EB',
    amber: '#F59E0B',
    green: '#10B981',
    red: '#EF4444'
};

function renderCategoryValueChart(categorySummary) {
    const ctx = document.getElementById('categoryValueChart');
    const labels = Object.keys(categorySummary);
    const values = labels.map(function (cat) { return categorySummary[cat].totalValue; });

    if (categoryChart) categoryChart.destroy();

    categoryChart = new Chart(ctx, {
        type: 'bar',
        data: { labels: labels, datasets: [{ label: 'Inventory Value ($)', data: values, backgroundColor: chartColors.blue, borderRadius: 6 }] },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
    });
}

function renderStockStatusChart(stats) {
    const ctx = document.getElementById('stockStatusChart');
    const inStockCount = stats.totalProducts - stats.lowStockCount - stats.outOfStockCount;

    if (stockStatusChart) stockStatusChart.destroy();

    stockStatusChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['In Stock', 'Low Stock', 'Out of Stock'],
            datasets: [{ data: [inStockCount, stats.lowStockCount, stats.outOfStockCount], backgroundColor: [chartColors.green, chartColors.amber, chartColors.red] }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });
}

function renderTopProductsChart(topProducts) {
    const ctx = document.getElementById('topProductsChart');
    const labels = topProducts.map(function (p) { return p.name; });
    const values = topProducts.map(function (p) { return p.quantity * p.unit_price; });

    if (topProductsChart) topProductsChart.destroy();

    topProductsChart = new Chart(ctx, {
        type: 'bar',
        data: { labels: labels, datasets: [{ label: 'Total Value ($)', data: values, backgroundColor: chartColors.amber, borderRadius: 6 }] },
        options: { indexAxis: 'y', responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
    });
}

function renderAllCharts() {
    renderCategoryValueChart(getCategorySummary());
    renderStockStatusChart(getStockStatistics());
    renderTopProductsChart(getTopProductsByValue());
}