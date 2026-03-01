const API_BASE = "/api";
const API_DASHBOARD_STATS = `${API_BASE}/dashboard/stats`;
const API_RECENT_ORDERS = `${API_BASE}/dashboard/recent-orders`;
const API_PRODUCTS = `${API_BASE}/products`;
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

//Dropdown & Search
const initUI = () => {
    // Profile Dropdown
    const avatar = document.getElementById("profile-avatar");
    const dropdown = document.getElementById("profile-dropdown");
    if (avatar && dropdown) {
        avatar.onclick = (e) => { e.stopPropagation(); dropdown.classList.toggle("show"); };
        window.onclick = () => dropdown.classList.remove("show");
    }

    // Action Search
    const search = document.getElementById("main-search");
    const results = document.getElementById("search-results");
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



//Product Management (CRUD)

// render Product
function renderProducts(products) {
    const tbody = document.getElementById("product-table-body");
    if (!tbody) return;
    tbody.innerHTML = products.length ? "" : '<tr><td colspan="6" style="text-align:center;">No products found</td></tr>';

    products.forEach(p => {
        let statusBadge = p.stock_quantity > LOW_STOCK_THRESHOLD 
            ? '<span class="status completed">In Stock</span>' 
            : (p.stock_quantity > 0 ? '<span class="status pending">Low Stock</span>' : '<span class="status cancelled">Out of Stock</span>');

        const tr = document.createElement("tr");
        tr.innerHTML = `
            <td><img src="${p.image_url || 'assets/placeholder.png'}" style="width:40px; height:40px; object-fit:cover; border-radius:4px;"></td>
            <td><strong>${p.product_name}</strong><br><small>SKU: ${p.sku || '-'}</small></td>
            <td>$${parseFloat(p.price).toFixed(2)}</td>
            <td>${p.stock_quantity}</td>
            <td>${statusBadge}</td>
            <td>
                <button class="edit-btn" onclick='editProduct(${JSON.stringify(p)})'>Edit</button>
                <button class="delete-btn" onclick="deleteProduct(${p.id})">Delete</button>
            </td>
        `;
        tbody.appendChild(tr);
    });
}

// Add or Edit
async function handleProductSubmit(e) {
    e.preventDefault();
    const id = document.getElementById("p_id").value;
    const formData = {
        product_name: document.getElementById("p_name").value,
        sku: document.getElementById("p_sku").value,
        price: document.getElementById("p_price").value,
        stock_quantity: document.getElementById("p_stock").value,
        image_url: document.getElementById("p_image").value,
        description: document.getElementById("p_desc").value
    };

    try {
        const method = id ? "PUT" : "POST";
        const url = id ? `${API_PRODUCTS}/${id}` : API_PRODUCTS;
        const res = await fetch(url, {
            method,
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(formData)
        });
        if (res.ok) { 
            closeModal(); 
            loadProducts(); 
        } else {
            alert("Failed to save product. Check backend API.");
        }
    } catch (err) { console.error("Save failed:", err); }
}

// Edit or Delete
function editProduct(p) {
    document.getElementById("p_id").value = p.id;
    document.getElementById("p_name").value = p.product_name;
    document.getElementById("p_sku").value = p.sku;
    document.getElementById("p_price").value = p.price;
    document.getElementById("p_stock").value = p.stock_quantity;
    document.getElementById("p_image").value = p.image_url || "";
    document.getElementById("p_desc").value = p.description || "";
    document.getElementById("modal-title").innerText = "Edit Product";
    openModal();
}

async function deleteProduct(id) {
    if (confirm('Are you sure you want to delete this product?')) {
        try {
            const res = await fetch(`${API_PRODUCTS}/${id}`, { method: "DELETE" });
            if (res.ok) loadProducts();
        } catch (e) { console.error("Delete failed."); }
    }
}

// Initalisation
const systemActions = [
    { title: "Dashboard Overview", url: "index.html", icon: "fa-border-all" },
    { title: "Manage Customers", url: "customer.html", icon: "fa-users" },
    { title: "Manage Products", url: "#", icon: "fa-dumbbell" }
];

document.addEventListener("DOMContentLoaded", () => {
    loadDashboardData();
    initUI();
    
    //Sidebar
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

//  Global scope
function openModal() { document.getElementById("product-modal").style.display = "block"; }
function closeModal() { document.getElementById("product-modal").style.display = "none"; }
async function loadProducts() { /* Call Fetch & Render */ }
async function handleSearch() { /* Search Filter Logic */ }
const initUI = () => {
    const avatar = document.getElementById("profile-avatar");
    const dropdown = document.getElementById("profile-dropdown");
    if (avatar && dropdown) {
        avatar.onclick = (e) => { e.stopPropagation(); dropdown.classList.toggle("show"); };
        window.onclick = () => dropdown.classList.remove("show");
    }

    const search = document.getElementById("main-search");
    const results = document.getElementById("search-results");
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

//Sales Chart
const initSalesChart = async () => {
    const ctx = document.getElementById('salesChart');
    if (!ctx) return;

    // 1. Define Mock Data (Before Back-end)
    const mockData = {
        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
        values: [1200, 1900, 3000, 5000, 2400, 3500, 4200]
    };

    try {
        const res = await fetch("/api/analytics/sales");
        const data = res.ok ? await res.json() : mockData;

        renderChart(ctx, data.labels, data.values);
    } catch (err) {
        console.warn("[Chart] Using mock data for local preview.");
        renderChart(ctx, mockData.labels, mockData.values);
    }
};


const renderChart = (ctx, labels, data) => {
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Revenue (£)',
                data: data,
                borderColor: '#4f46e5',
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f1f5f9' },
                    ticks: { callback: (val) => '£' + val }
                },
                x: { grid: { display: false } }
            }
        }
    });
};
document.addEventListener("DOMContentLoaded", () => {
    loadDashboardStats();
    loadDashboardOrders();
    initSalesChart(); 
});
