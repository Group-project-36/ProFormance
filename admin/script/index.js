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
const formatDate = (timestamp) => {
    if (!timestamp) return "-";
    return new Date(timestamp).toLocaleDateString('en-GB');
};

// Dashboard Control
const loadDashboardStats = async () => {
    // Mock data matching the DB screenshot field names
    const mockStats = {
        total_sales_penny: 1243000, 
        total_orders: 380,
        total_customers: 142,
        low_stock: 5 // Based on variants.available_stock

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
    } catch (err) {
        console.warn("[Dashboard] Network error, using mock stats.");
        const statCards = document.querySelectorAll(".stat-card .stat-number");
        statCards[0].innerText = formatCurrency(mockStats.total_sales_penny);
        statCards[1].innerText = mockStats.total_orders;
        statCards[2].innerText = mockStats.total_customers;
        statCards[3].innerText = mockStats.low_stock;
    }
};

//Load Recent Orders
const loadDashboardOrders = async () => {
    const tbody = document.querySelector(".recent-orders .orders-table tbody");
    if (!tbody) return;
    
// Mock Data using exact DB field names
    const mockOrders = [
        { order_number: "ORD-1024", ship_recipient_name: "Bruno Mars", created_at: "2026-03-01 10:00:00", total_penny: 12000, order_status: "Fulfilled" },
        { order_number: "ORD-1025", ship_recipient_name: "Chris Wong", created_at: "2026-03-01 11:30:00", total_penny: 8900, order_status: "Pending" },
        { order_number: "ORD-1026", ship_recipient_name: "Michael Jackson", created_at: "2026-03-01 12:45:00", total_penny: 24000, order_status: "Cancelled" }
    ];

    try {
        const res = await fetch(API_RECENT_ORDERS);
        const orders = res.ok ? await res.json() : mockOrders;

        tbody.innerHTML = ""; 
        orders.forEach(order => {
            const tr = document.createElement("tr");
            // Mapping CSS classes to DB Enum
            const statusClass = order.order_status.toLowerCase(); 
            
            tr.innerHTML = `
                <td>#${order.order_number}</td>
                <td>${order.ship_recipient_name}</td>
                <td>${formatDate(order.created_at)}</td>
                <td>${formatCurrency(order.total_penny)}</td>
                <td><span class="status ${statusClass}">${order.order_status}</span></td>
            `;
            tbody.appendChild(tr);
        });
    } catch (err) {
        console.error("[Dashboard] Failed to load orders:", err);
    }
};

//Product Management (SPA)
function initProductPage() {
  const content = document.querySelector('.content');
  if (!content) return;
  //Product Layout(View Product HTML)
content.innerHTML = `
        <div class="card">
            <div style="display:flex; justify-content:space-between; align-items:center;">
                <h1>Product Management</h1>
                <button class="action-btn" id="open-add-modal">+ Add Product</button>
            </div>
            <div class="search-box" style="margin-top: 15px;">
                 <input type="text" id="product-search" placeholder="Search products..." style="width: 100%; padding: 10px;">
            </div>
        </div>
        <div class="stats-grid">
            <div class="stat-card"><h3>Total Inventory</h3><p class="stat-number" id="total-stock-count">-</p></div>
            <div class="stat-card"><h3>Low Stock Items</h3><p class="stat-number" id="low-stock-count">-</p></div>
        </div>
        <div class="card">
            <table class="orders-table">
                <thead>
                    <tr><th>Image</th><th>Product / SKU</th><th>Price</th><th>Stock</th><th>Status</th><th>Actions</th></tr>
                </thead>
                <tbody id="product-table-body"><tr><td colspan="6" style="text-align:center;">Loading...</td></tr></tbody>
            </table>
        </div>
        <div id="product-modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000;">
            <div style="background:white; margin:5% auto; padding:20px; width:50%; border-radius:8px;">
                <h2 id="modal-title">Add Product</h2>
                <form id="product-form" style="display:flex; flex-direction:column; gap:10px;">
                    <input type="hidden" id="p_id">
                    <input type="text" id="p_name" placeholder="Product Name" required>
                    <input type="text" id="p_sku" placeholder="SKU" required>
                    <input type="number" id="p_price" placeholder="Price" step="0.01" required>
                    <input type="number" id="p_stock" placeholder="Stock Quantity" required>
                    <input type="text" id="p_image" placeholder="Image URL">
                    <textarea id="p_desc" placeholder="Description"></textarea>
                    <div style="text-align:right;">
                        <button type="button" onclick="closeModal()">Cancel</button>
                        <button type="submit" class="action-btn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    `;
  document.getElementById("open-add-modal").addEventListener("click", () => openModal());
  document.getElementById("product-form").addEventListener("submit", handleProductSubmit);
  document.getElementById("product-search").addEventListener("keyup", handleSearch);

  loadProducts();
}

// Independent Function
// Load Product

async function loadProducts() {
  try {
    const res = await fetch(API_PRODUCTS);
    const data = await res.json(); 
// Low alert
    renderProducts(data);
    updateStockAlerts(data); 
  } catch (err) {
    console.error("Error loading products:", err);
  }
}

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
                <button onclick='editProduct(${JSON.stringify(p)})'>Edit</button>
                <button onclick="deleteProduct(${p.id})" style="color:red; margin-left:10px;">Delete</button>
            </td>
        `;
        tbody.appendChild(tr);
    });
}  

function updateStockAlerts(products) {
    const lowStock = products.filter(p => p.stock_quantity <= LOW_STOCK_THRESHOLD).length;
    const total = products.reduce((sum, p) => sum + parseInt(p.stock_quantity || 0), 0);
    
    if (document.getElementById("low-stock-count")) {
        document.getElementById("low-stock-count").innerText = lowStock;
        document.getElementById("total-stock-count").innerText = total;
    }
}

 /* Status of stock
  let statusBadge = '<span class="status completed">In Stock</span>';
  if (p.stock_quantity == 0) statusBadge = '<span class="status cancelled">Out of Stock</span>';
  else if (p.stock_quantity <= LOW_STOCK_THRESHOLD) statusBadge = '<span class="status pending">Low Stock</span>';

  const displayPrice = parseFloat(p.price).toFixed(2); 

  return `
    <tr>
      <td><img src="${p.image_url || 'assets/placeholder.png'}" style="width:40px; height:40px; object-fit:cover; border-radius:4px;"></td>
       <td>
           <strong>${p.product_name}</strong><br>
           <small style="color:#666;">SKU: ${p.sku || '-'}</small>
       </td>
       <td>$${displayPrice}</td>
       <td>${p.stock_quantity}</td>
       <td>${statusBadge}</td>
       <td>
        <button style="cursor:pointer; color:blue; background:none; border:none;" onclick='editProduct(${JSON.stringify(p)})'>Edit</button>
        <button class="delete-btn" style="cursor:pointer; color:red; background:none; border:none; margin-left:10px;" onclick="deleteProduct(${p.id})">Delete</button>
      </td>
     </tr>
    `;
  });

  tbody.innerHTML = htmlRows.join('');
}
*/

/* Low Stock Alert
function updateStockAlerts(products) {
    // Calculate for low stocks
    const lowStockItems = products.filter(p => p.stock_quantity <= LOW_STOCK_THRESHOLD).length;
    const totalItems = products.reduce((sum, p) => sum + parseInt(p.stock_quantity || 0), 0);

    // Update
    const lowStockEl = document.getElementById("low-stock-count");
    if(lowStockEl) {
        lowStockEl.innerText = lowStockItems;
        lowStockEl.style.color = lowStockItems > 0 ? "red" : "black";
    }

    const totalStockEl = document.getElementById("total-stock-count");
    if(totalStockEl) totalStockEl.innerText = totalItems;
}
*/

// UI Component
// Search Function
const initActionSearch = () => {
    const searchInput = document.getElementById("main-search");
    const resultsContainer = document.getElementById("search-results");
    if (!searchInput || !resultsContainer) return;

    searchInput.addEventListener("input", (e) => {
        const keyword = e.target.value.toLowerCase().trim();
        if (!keyword) return resultsContainer.classList.remove("show");

        const filtered = systemActions.filter(a => a.title.toLowerCase().includes(keyword));
        resultsContainer.innerHTML = filtered.length 
            ? filtered.map(a => `<a href="${a.url}" class="search-item"><i class="fas ${a.icon}"></i> <span>${a.title}</span></a>`).join('')
            : '<div class="search-empty">No results found</div>';
        resultsContainer.classList.add("show");
    });

    window.addEventListener("click", (e) => { if (!searchInput.contains(e.target)) resultsContainer.classList.remove("show"); });
};

const initProfileDropdown = () => {
    const btn = document.getElementById("profile-avatar");
    const menu = document.getElementById("profile-dropdown");
    if (!btn || !menu) return;

    btn.addEventListener("click", (e) => {
        e.stopPropagation();
        const shown = menu.classList.toggle("show");
        btn.setAttribute("aria-expanded", shown);
    });

    window.addEventListener("click", () => {
        menu.classList.remove("show");
        btn.setAttribute("aria-expanded", "false");
    });
};

// App Initailisation
const systemActions = [
    { title: "Dashboard Overview", url: "index.html", icon: "fa-border-all", type: "Page" },
    { title: "Manage Customers", url: "customer.html", icon: "fa-users", type: "Page" },
    { title: "Manage Products", url: "products.html", icon: "fa-dumbbell", type: "Page" }
];

document.addEventListener("DOMContentLoaded", () => {
    loadDashboardStats();
    loadDashboardOrders();
    initActionSearch();
    initProfileDropdown();

    document.querySelectorAll('.nav a').forEach(link => {
        if (link.textContent.includes("Products")) {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                document.querySelectorAll('.nav a').forEach(l => l.classList.remove('active'));
                link.classList.add('active');
                initProductPage();
            });
        }
    });
});


// Product helpers (Global scope for inline onclicks)
function openModal() { document.getElementById("product-modal").style.display = "block"; }
function closeModal() { document.getElementById("product-modal").style.display = "none"; }

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
        if (res.ok) { closeModal(); loadProducts(); }
    } catch (err) { console.error("Save failed:", err); }
}

async function handleSearch() {
    const keyword = document.getElementById("product-search").value.toLowerCase();
    const res = await fetch(API_PRODUCTS);
    const list = await res.json();
    renderProducts(list.filter(p => p.product_name.toLowerCase().includes(keyword)));
}

function editProduct(p) {
    document.getElementById("p_id").value = p.id;
    document.getElementById("p_name").value = p.product_name;
    document.getElementById("p_sku").value = p.sku;
    document.getElementById("p_price").value = p.price;
    document.getElementById("p_stock").value = p.stock_quantity;
    document.getElementById("modal-title").innerText = "Edit Product";
    openModal();
}

async function deleteProduct(id) {
    if (confirm('Delete this product?')) {
        await fetch(`${API_PRODUCTS}/${id}`, { method: "DELETE" });
        loadProducts();
    }
}
