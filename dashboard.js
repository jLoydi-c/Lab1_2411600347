const currentUser = localStorage.getItem('loggedInUser');

if (!currentUser) {
    window.location.href = 'index.html';
}

function buildProductRow(product) {
    let statusBadge = '';
    if (product.status === 'in-stock') {
        statusBadge = '<span class="badge badge-success-custom">In Stock</span>';
    } else if (product.status === 'low-stock') {
        statusBadge = '<span class="badge badge-warning-custom">Low Stock</span>';
    } else {
        statusBadge = '<span class="badge badge-danger-custom">Out of Stock</span>';
    }

    const rowClass = product.status !== 'in-stock' ? 'table-row-alert' : '';

    return `
        <tr class="${rowClass}">
            <td>${product.sku}</td>
            <td>${product.name}</td>
            <td>${product.category}</td>
            <td>${product.quantity}</td>
            <td>$${product.unit_price.toFixed(2)}</td>
            <td>$${(product.quantity * product.unit_price).toFixed(2)}</td>
            <td>${statusBadge}</td>
        </tr>
    `;
}

function renderProductTable() {
    const tableBody = document.getElementById('inventoryTableBody');
    const products = getProducts();

    if (products.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="7" class="text-center text-muted py-3">No products match your filters.</td></tr>';
        return;
    }

    tableBody.innerHTML = products.map(buildProductRow).join('');
}

function renderStatCards() {
    const stats = getStockStatistics();
    document.getElementById('stat1-value').textContent = stats.totalProducts;
    document.getElementById('stat2-value').textContent = '$' + stats.totalValue.toFixed(2);
    document.getElementById('stat3-value').textContent = stats.lowStockCount;
    document.getElementById('stat4-value').textContent = stats.outOfStockCount;
}

function renderLowStockAlert() {
    const lowStockItems = getLowStockProducts();
    const alertBox = document.getElementById('lowStockAlert');

    if (lowStockItems.length === 0) {
        alertBox.classList.add('d-none');
        return;
    }

    const itemNames = lowStockItems.map(function (p) { return p.name; }).join(', ');
    alertBox.textContent = 'Attention: ' + lowStockItems.length + ' item(s) need restocking - ' + itemNames;
    alertBox.classList.remove('d-none');
}

function populateCategoryDropdown() {
    const categorySummary = getCategorySummary();
    const dropdown = document.getElementById('categoryFilter');

    Object.keys(categorySummary).forEach(function (category) {
        const option = document.createElement('option');
        option.value = category;
        option.textContent = category;
        dropdown.appendChild(option);
    });
}

function refreshDashboard() {
    renderProductTable();
    renderStatCards();
    renderLowStockAlert();
    renderAllCharts();
}

function showNotification(message) {
    const toast = document.createElement('div');
    toast.className = 'inventory-toast';
    toast.textContent = message;
    document.body.appendChild(toast);

    setTimeout(function () { toast.remove(); }, 3000);
}

document.getElementById('categoryFilter').addEventListener('change', function (e) {
    filterByCategory(e.target.value);
    refreshDashboard();
});

document.getElementById('stockStatusFilter').addEventListener('change', function (e) {
    filterByStockStatus(e.target.value);
    refreshDashboard();
});

document.getElementById('applyPriceFilter').addEventListener('click', function () {
    const min = document.getElementById('minPrice').value;
    const max = document.getElementById('maxPrice').value;
    filterByPriceRange(min === '' ? null : parseFloat(min), max === '' ? null : parseFloat(max));
    refreshDashboard();
});

document.getElementById('resetFilters').addEventListener('click', function () {
    resetFilters();
    document.getElementById('categoryFilter').value = 'all';
    document.getElementById('stockStatusFilter').value = 'all';
    document.getElementById('minPrice').value = '';
    document.getElementById('maxPrice').value = '';
    document.getElementById('searchInput').value = '';
    refreshDashboard();
});

document.getElementById('searchInput').addEventListener('input', function (e) {
    searchProducts(e.target.value);
    renderProductTable();
});

document.getElementById('exportBtn').addEventListener('click', function () {
    const csv = exportToCSV(getProducts());
    downloadCSV(csv, 'inventory_export.csv');
});

document.getElementById('logoutBtn').addEventListener('click', function () {
    localStorage.removeItem('loggedInUser');
    window.location.href = 'index.html';
});

async function startDashboard() {
    document.getElementById('usernameDisplay').textContent = currentUser;

    await initializeData();
    populateCategoryDropdown();
    refreshDashboard();

    setInterval(function () {
        const updatedProduct = simulateInventoryUpdate();
        refreshDashboard();
        showNotification(updatedProduct.name + ' stock updated to ' + updatedProduct.quantity);
    }, 15000);
}

if (currentUser) {
    startDashboard();
}