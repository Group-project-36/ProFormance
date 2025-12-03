const API_PRODUCTS = "/api/products";
const LOW_STOCK_THRESHOLD = 10;

//Product load view

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

//Product Layout (View Product HTML

content.innerHTML = `
    <div class="card">
      <div style="display:flex; justify-content:space-between; align-items:center;">
        <h1>Product Management</h1>
        <button class="action-btn" id="open-add-modal">+ Add Product</button>
      </div>
      <div class="search-box" style="margin-top: 15px;">
         <input type="text" id="product-search" placeholder="Search products..." style="width: 100%; padding: 10px;">
         <button id="product-search-btn" style="display:none;">Search</button>
      </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <h3>Total Inventory</h3>
            <p class="stat-number" id="total-stock-count">-</p>
        </div>
        <div class="stat-card">
            <h3>Low Stock Items</h3>
            <p class="stat-number" id="low-stock-count">-</p>
        </div>
    </div>

    <div class="card">
      <table class="orders-table">
        <thead>
            <tr>
                <th>Image</th>
                <th>Product Name / SKU</th>
                <th>Price</th>
                <th>Stock Level</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="product-table-body">
            <tr><td colspan="6" style="text-align:center;">Loading...</td></tr>
        </tbody>
      </table>
    </div>

    <div id="product-modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5);">
      <div style="background:white; margin:5% auto; padding:20px; width:50%; border-radius:8px;">
        <h2 id="modal-title">Add Product</h2>
        <form id="product-form" style="display:flex; flex-direction:column; gap:10px;">
            <input type="hidden" id="p_id">
            <input type="text" id="p_name" placeholder="Product Name" required style="padding:8px;">
            <input type="text" id="p_sku" placeholder="SKU" required style="padding:8px;">
            <input type="number" id="p_price" placeholder="Price ($)" step="0.01" required style="padding:8px;">
            <input type="number" id="p_stock" placeholder="Stock Quantity" required style="padding:8px;">
            <input type="text" id="p_image" placeholder="Image URL" style="padding:8px;">
            <textarea id="p_desc" placeholder="Description" rows="3" style="padding:8px;"></textarea>
            
            <div style="margin-top:10px; text-align:right;">
                <button type="button" onclick="closeModal()" style="padding:8px 15px;">Cancel</button>
                <button type="submit" class="action-btn" style="padding:8px 15px;">Save</button>
            </div>
        </form>
      </div>
    </div>
  `;

// ELement
  document.getElementById("open-add-modal").addEventListener("click", () => openModal());
  document.getElementById("product-form").addEventListener("submit", handleProductSubmit);
  document.getElementById("product-search").addEventListener("keyup", handleSearch);

  loadProducts();
}

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
  tbody.innerHTML = "";

  if (!products || products.length === 0) {
    tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 20px;">No products found</td></tr>';
    return;
  }
