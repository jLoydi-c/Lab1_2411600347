// dashboard-live.js
// refreshes the dashboard every 30 seconds using AJAX polling (fetch)

const POLL_INTERVAL = 30000;
const urls = window.dashboardUrls;

// stops names from breaking the html when we build it with strings
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// small count up/down animation so changes are easy to notice
function animateNumber(element, endValue, prefix, decimals) {
    const startValue = parseFloat(element.dataset.value) || 0;
    if (startValue === endValue) {
        return;
    }

    const duration = 600;
    const startTime = performance.now();

    function step(now) {
        const progress = Math.min((now - startTime) / duration, 1);
        const current = startValue + (endValue - startValue) * progress;

        element.textContent = prefix + current.toLocaleString('en-US', {
            minimumFractionDigits: decimals,
            maximumFractionDigits: decimals
        });

        if (progress < 1) {
            requestAnimationFrame(step);
        } else {
            element.dataset.value = endValue;
        }
    }

    requestAnimationFrame(step);
}

async function fetchJson(url) {
    const response = await fetch(url, {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        credentials: 'same-origin'
    });

    if (!response.ok) {
        throw new Error('Request failed with status ' + response.status);
    }

    return response.json();
}

function updateStats(data) {
    animateNumber(document.getElementById('stat-total-products'), data.total_products, '', 0);
    animateNumber(document.getElementById('stat-total-value'), data.total_value, '$', 2);
    animateNumber(document.getElementById('stat-low-stock'), data.low_stock_count, '', 0);
    animateNumber(document.getElementById('stat-out-of-stock'), data.out_of_stock_count, '', 0);
    document.getElementById('lastUpdated').textContent = data.last_updated;
}

function renderLowStock(items) {
    const list = document.getElementById('lowStockList');

    if (items.length === 0) {
        list.innerHTML = '<li class="list-group-item text-muted">No low stock items.</li>';
        return;
    }

    list.innerHTML = items.map(function (p) {
        const textClass = p.status_color === 'warning' ? 'text-dark' : '';
        return '<li class="list-group-item d-flex justify-content-between align-items-center">' +
            '<a href="' + p.url + '">' + escapeHtml(p.name) + '</a>' +
            '<span class="badge bg-' + p.status_color + ' ' + textClass + '">' +
            p.quantity + ' left (reorder at ' + p.reorder_level + ')</span></li>';
    }).join('');
}

function renderRecent(items) {
    const body = document.getElementById('recentTransactionsBody');

    if (items.length === 0) {
        body.innerHTML = '<tr><td colspan="5" class="text-center text-muted py-3">No transactions yet.</td></tr>';
        return;
    }

    body.innerHTML = items.map(function (t) {
        const qtyClass = t.quantity > 0 ? 'text-success' : 'text-danger';
        const sign = t.quantity > 0 ? '+' : '';
        return '<tr>' +
            '<td>' + t.created_at + '</td>' +
            '<td><a href="' + t.product_url + '">' + escapeHtml(t.product) + '</a></td>' +
            '<td><span class="badge bg-' + t.type_color + '">' + t.type + '</span></td>' +
            '<td class="fw-bold ' + qtyClass + '">' + sign + t.quantity + '</td>' +
            '<td>' + escapeHtml(t.user) + '</td>' +
            '</tr>';
    }).join('');
}

async function refreshDashboard() {
    const status = document.getElementById('updateStatus');

    try {
        const results = await Promise.all([
            fetchJson(urls.stats),
            fetchJson(urls.lowStock),
            fetchJson(urls.recent)
        ]);

        updateStats(results[0]);
        renderLowStock(results[1].data);
        renderRecent(results[2].data);
        status.textContent = '';
    } catch (error) {
        console.log('Dashboard refresh failed:', error.message);
        status.textContent = '(could not refresh, will try again)';
    }
}

setInterval(refreshDashboard, POLL_INTERVAL);