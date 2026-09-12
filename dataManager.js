const state = {
    allProducts: [],
    filteredProducts: [],
    activeFilters: { category: 'all', stockStatus: 'all', minPrice: null, maxPrice: null },
    searchQuery: ''
};

const sampleProducts = [
    { id: 1, sku: 'SKU-1001', name: 'Adjustable Dumbbell Set', category: 'Equipment', quantity: 12, unit_price: 89.99, reorder_level: 5, supplier: 'IronForge Supply' },
    { id: 2, sku: 'SKU-1002', name: 'Olympic Barbell', category: 'Equipment', quantity: 4, unit_price: 149.99, reorder_level: 5, supplier: 'IronForge Supply' },
    { id: 3, sku: 'SKU-1003', name: 'Resistance Bands Set', category: 'Equipment', quantity: 0, unit_price: 19.99, reorder_level: 15, supplier: 'IronForge Supply' },
    { id: 4, sku: 'SKU-2001', name: 'Whey Protein Powder (Chocolate)', category: 'Supplements', quantity: 60, unit_price: 34.99, reorder_level: 20, supplier: 'PeakNutrition' },
    { id: 5, sku: 'SKU-2002', name: 'Pre-Workout Formula', category: 'Supplements', quantity: 10, unit_price: 29.99, reorder_level: 15, supplier: 'PeakNutrition' },
    { id: 6, sku: 'SKU-2003', name: 'BCAA Capsules', category: 'Supplements', quantity: 0, unit_price: 18.50, reorder_level: 20, supplier: 'PeakNutrition' },
    { id: 7, sku: 'SKU-3001', name: 'Gym Tank Top', category: 'Apparel', quantity: 85, unit_price: 15.00, reorder_level: 25, supplier: 'ActiveWear Co.' },
    { id: 8, sku: 'SKU-3002', name: 'Compression Leggings', category: 'Apparel', quantity: 18, unit_price: 32.00, reorder_level: 20, supplier: 'ActiveWear Co.' },
    { id: 9, sku: 'SKU-3003', name: 'Gym Shorts', category: 'Apparel', quantity: 6, unit_price: 20.00, reorder_level: 15, supplier: 'ActiveWear Co.' },
    { id: 10, sku: 'SKU-4001', name: 'Yoga Mat', category: 'Accessories', quantity: 40, unit_price: 22.00, reorder_level: 10, supplier: 'FlexFit Gear' },
    { id: 11, sku: 'SKU-4002', name: 'Shaker Bottle', category: 'Accessories', quantity: 5, unit_price: 8.99, reorder_level: 10, supplier: 'FlexFit Gear' },
    { id: 12, sku: 'SKU-4003', name: 'Weightlifting Gloves', category: 'Accessories', quantity: 30, unit_price: 14.50, reorder_level: 10, supplier: 'FlexFit Gear' },
    { id: 13, sku: 'SKU-5001', name: 'Foam Roller', category: 'Recovery', quantity: 20, unit_price: 25.00, reorder_level: 8, supplier: 'RecoverPlus' },
    { id: 14, sku: 'SKU-5002', name: 'Massage Gun', category: 'Recovery', quantity: 2, unit_price: 99.99, reorder_level: 5, supplier: 'RecoverPlus' },
    { id: 15, sku: 'SKU-5003', name: 'Ice Pack Wrap', category: 'Recovery', quantity: 15, unit_price: 12.99, reorder_level: 8, supplier: 'RecoverPlus' }
];

function getStockStatus(product) {
    if (product.quantity === 0) return 'out-of-stock';
    if (product.quantity <= product.reorder_level) return 'low-stock';
    return 'in-stock';
}

async function initializeData() {
    try {
        const response = await fetch('api/products.php');
        if (!response.ok) throw new Error('API response was not ok');
        state.allProducts = await response.json();
    } catch (error) {
        console.log('Could not fetch from API, using sample data instead:', error.message);
        state.allProducts = sampleProducts;
    }

    state.allProducts = state.allProducts.map(function (product) {
        return { ...product, status: getStockStatus(product) };
    });

    state.filteredProducts = [...state.allProducts];
    return state.allProducts;
}

function getProducts() {
    return state.filteredProducts;
}

function getProductById(id) {
    return state.allProducts.find(function (product) { return product.id === id; });
}

function getProductsByCategory(category) {
    return state.allProducts.filter(function (product) { return product.category === category; });
}

function getLowStockProducts() {
    return state.allProducts.filter(function (product) {
        return product.status === 'low-stock' || product.status === 'out-of-stock';
    });
}

function getStockStatistics() {
    const totalProducts = state.allProducts.length;
    const totalValue = state.allProducts.reduce(function (sum, product) {
        return sum + (product.quantity * product.unit_price);
    }, 0);
    const lowStockCount = state.allProducts.filter(function (p) { return p.status === 'low-stock'; }).length;
    const outOfStockCount = state.allProducts.filter(function (p) { return p.status === 'out-of-stock'; }).length;

    return { totalProducts, totalValue, lowStockCount, outOfStockCount };
}

function getCategorySummary() {
    const categories = {};
    state.allProducts.forEach(function (product) {
        if (!categories[product.category]) {
            categories[product.category] = { totalValue: 0, totalQuantity: 0 };
        }
        categories[product.category].totalValue += product.quantity * product.unit_price;
        categories[product.category].totalQuantity += product.quantity;
    });
    return categories;
}

function getTopProductsByValue() {
    return [...state.allProducts]
        .sort(function (a, b) { return (b.quantity * b.unit_price) - (a.quantity * a.unit_price); })
        .slice(0, 5);
}

function filterByCategory(category) {
    state.activeFilters.category = category;
    applyFilters();
}

function filterByStockStatus(status) {
    state.activeFilters.stockStatus = status;
    applyFilters();
}

function filterByPriceRange(min, max) {
    state.activeFilters.minPrice = min;
    state.activeFilters.maxPrice = max;
    applyFilters();
}

function applyFilters() {
    let result = [...state.allProducts];

    if (state.activeFilters.category !== 'all') {
        result = result.filter(function (p) { return p.category === state.activeFilters.category; });
    }
    if (state.activeFilters.stockStatus !== 'all') {
        result = result.filter(function (p) { return p.status === state.activeFilters.stockStatus; });
    }
    if (state.activeFilters.minPrice !== null) {
        result = result.filter(function (p) { return p.unit_price >= state.activeFilters.minPrice; });
    }
    if (state.activeFilters.maxPrice !== null) {
        result = result.filter(function (p) { return p.unit_price <= state.activeFilters.maxPrice; });
    }
    if (state.searchQuery.trim() !== '') {
        const query = state.searchQuery.toLowerCase();
        result = result.filter(function (p) {
            return p.name.toLowerCase().includes(query) || p.sku.toLowerCase().includes(query);
        });
    }

    state.filteredProducts = result;
    return result;
}

function resetFilters() {
    state.activeFilters = { category: 'all', stockStatus: 'all', minPrice: null, maxPrice: null };
    state.searchQuery = '';
    state.filteredProducts = [...state.allProducts];
}

function searchProducts(query) {
    state.searchQuery = query;
    return applyFilters();
}

function exportToCSV(data) {
    const headers = ['SKU', 'Name', 'Category', 'Quantity', 'Unit Price', 'Total Value', 'Status', 'Supplier'];
    const rows = data.map(function (p) {
        return [p.sku, p.name, p.category, p.quantity, p.unit_price.toFixed(2),
            (p.quantity * p.unit_price).toFixed(2), p.status, p.supplier].join(',');
    });
    return headers.join(',') + '\n' + rows.join('\n');
}

function downloadCSV(csvContent, filename) {
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.setAttribute('href', url);
    link.setAttribute('download', filename);
    link.style.display = 'none';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function simulateInventoryUpdate() {
    const randomIndex = Math.floor(Math.random() * state.allProducts.length);
    const product = state.allProducts[randomIndex];
    const change = Math.floor(Math.random() * 11) - 5;
    product.quantity = Math.max(0, product.quantity + change);
    product.status = getStockStatus(product);
    applyFilters();
    return product;
}