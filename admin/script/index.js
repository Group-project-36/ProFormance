const API_BASE = "/api";
const API_DASHBOARD_STATS = `${API_BASE}/dashboard/stats`;
const API_RECENT_ORDERS = `${API_BASE}/dashboard/recent-orders`;
const API_PRODUCTS = `${API_BASE}/products`;
const API_SALES_ANALYTICS = `${API_BASE}/analytics/sales`;
const LOW_STOCK_THRESHOLD = 10;

// Currency
const formatCurrency = (pennies, currency = 'GBP') => {
    const config = { 'GBP': 'en-GB', 'USD': 'en-US', 'EUR': 'de-DE' };
    const locale = config[currency] || 'en-GB';

    return new Intl.NumberFormat(locale, {
        style: 'currency',
        currency: currency,
    }).format(pennies / 100); 
};

// Data Formatter
const formatDate = (ts) => ts ? new Date(ts).toLocaleDateString('en-GB') : "-";

// Dashboard Control
const loadDashboardData = async () => {
    const mockStats = { total_sales_penny: 1243000, total_orders: 380, total_customers: 142, low_stock: 5 };
    const mockOrders = [
        { order_number: "ORD-1024", ship_recipient_name: "Bruno Mars", created_at: "2026-03-01", total_penny: 12000, order_status: "Fulfilled" },
        { order_number: "ORD-1025", ship_recipient_name: "Chris Wong", created_at: "2026-03-01", total_penny: 8900, order_status: "Pending" }
    ];

  // Dashboard Stats Cards
    try {
        const res = await fetch(API_DASHBOARD_STATS);
        const stats = res.ok ? await res.json() : mockStats;
        const statCards = document.querySelectorAll(".stat-card .stat-number");
        
        if (statCards.length >= 4) {
            statCards[0].innerText = formatCurrency(stats.total_sales_penny);
            statCards[1].innerText = stats.total_orders;
            statCards[2].innerText = stats.total_customers;
            statCards[3].innerText = stats.low_stock;
        }
    } catch (e) { console.warn("Using mock stats."); }
    
  // Update Recent Orders
    const tbody = document.querySelector(".recent-orders .orders-table tbody");
    if (!tbody) return;
    try {
        const res = await fetch(API_RECENT_ORDERS);
        const orders = res.ok ? await res.json() : mockOrders;
        tbody.innerHTML = orders.map(o => `
            <tr>
                <td>#${o.order_number}</td>
                <td>${o.ship_recipient_name}</td>
                <td>${formatDate(o.created_at)}</td>
                <td>${formatCurrency(o.total_penny)}</td>
                <td><span class="status ${o.order_status.toLowerCase()}">${o.order_status}</span></td>
            </tr>
        `).join('');
    } catch (e) { tbody.innerHTML = "<tr><td colspan='5'>Error loading orders.</td></tr>"; }
};    


// Sales Chart

const initSalesChart = async () => {
    const ctx = document.getElementById('salesChart');
    if (!ctx) return;
    const mockData = { labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'], values: [1200, 1900, 3000, 5000, 2400, 3500, 4200] };
    try {
        const res = await fetch("/api/analytics/sales");
        const data = res.ok ? await res.json() : mockData;
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [{ 
                    label: 'Revenue (£)', 
                    data: data.values, 
                    borderColor: '#4f46e5', 
                    tension: 0.4, 
                    fill: true, 
                    backgroundColor: 'rgba(79, 70, 229, 0.1)' 
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
        });
    } catch (err) { console.warn("Chart using mock data."); }
};

// Product Management (CRUD)

function initProductPage() {
    const content = document.querySelector('.content');
    if (!content) return;
    
    content.innerHTML = `
        <div class="card">
            <div style="display:flex; justify-content:space-between; align-items:center;">
                <h1>Product Management</h1>
                <button class="action-btn" onclick="openModal()">+ Add Product</button>
            </div>
        </div>
        <div class="card"><table class="orders-table">
            <thead><tr><th>Image</th><th>Product</th><th>Price</th><th>Stock</th><th>Actions</th></tr></thead>
            <tbody id="product-table-body"><tr><td colspan="5" style="text-align:center;">Loading...</td></tr></tbody>
        </table></div>
        <div id="product-modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000;">
            <div style="background:white; margin:5% auto; padding:20px; width:50%; border-radius:8px;">
                <h2 id="modal-title">Add Product</h2>
                <form id="product-form" onsubmit="handleProductSubmit(event)">
                    <input type="hidden" id="p_id">
                    <input type="text" id="p_name" placeholder="Name" required style="width:100%; margin-bottom:10px;">
                    <input type="number" id="p_price" placeholder="Price" step="0.01" required style="width:100%; margin-bottom:10px;">
                    <input type="number" id="p_stock" placeholder="Stock" required style="width:100%; margin-bottom:10px;">
                    <div style="text-align:right;"><button type="button" onclick="closeModal()">Cancel</button><button type="submit" class="action-btn">Save</button></div>
                </form>
            </div>
        </div>
    `;
    loadProducts();
}

// UI

const initUI = () => {
    // Dropdown 
    const avatar = document.getElementById("profile-avatar");
    const dropdown = document.getElementById("profile-dropdown");
    if (avatar && dropdown) {
        avatar.onclick = (e) => { e.stopPropagation(); dropdown.classList.toggle("show"); };
        window.onclick = () => dropdown.classList.remove("show");
    }

    // Search 
    const search = document.getElementById("main-search");
    const results = document.getElementById("search-results");
    const systemActions = [
        { title: "Dashboard Overview", url: "index.html", icon: "fa-border-all" },
        { title: "Manage Products", url: "#", icon: "fa-dumbbell" }
    ];

    if (search && results) {
        search.oninput = (e) => {
            const kw = e.target.value.toLowerCase().trim();
            if (!kw) return results.classList.remove("show");
            const filtered = systemActions.filter(a => a.title.toLowerCase().includes(kw));
            results.innerHTML = filtered.map(a => `<a href="${a.url}" class="search-item"><i class="fas ${a.icon}"></i> ${a.title}</a>`).join('');
            results.classList.add("show");
        };
    }
};

document.addEventListener("DOMContentLoaded", () => {
    loadDashboardData();
    initUI();
    initSalesChart();

// Sidebar
    document.querySelectorAll('.nav a').forEach(link => {
        if (link.textContent.includes("Products")) {
            link.onclick = (e) => { 
                e.preventDefault(); 
                document.querySelectorAll('.nav a').forEach(l => l.classList.remove('active'));
                link.classList.add('active');
                initProductPage(); 
            };
        }
    });
});

// Global Helpers
function openModal() { document.getElementById("product-modal").style.display = "block"; }
function closeModal() { document.getElementById("product-modal").style.display = "none"; }

async function handleProductSubmit(e) {
    e.preventDefault();
    const id = document.getElementById("p_id").value;
    const url = id ? `${API_PRODUCTS}/${id}` : API_PRODUCTS;
    // CRUD Submit
    console.log(`Submitting to ${url}`);
    closeModal();
}

function editProduct(p) {
    document.getElementById("p_id").value = p.id;
    document.getElementById("p_name").value = p.product_name;
    document.getElementById("p_price").value = p.price;
    document.getElementById("p_stock").value = p.stock_quantity;
    document.getElementById("modal-title").innerText = "Edit Product";
    openModal();
}

async function deleteProduct(id) {
    if (confirm("Delete product?")) {
        console.log(`Deleting product ${id}`);
        // DELETE fetch 
    }
}
