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
  const htmlRows = products.map(p => {




// Status of stock
    let statusBadge = '<span class="status completed">In Stock</span>';
    if (p.stock_quantity == 0) statusBadge = '<span class="status cancelled">Out of Stock</span>';
    else if (p.stock_quantity <= LOW_STOCK_THRESHOLD) statusBadge = '<span class="status pending">Low Stock</span>';

    // Format Price
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

// Low Stock Alert
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

// Search Function
async function handleSearch() {
  const keyword = document.getElementById("product-search").value.toLowerCase();
  const res = await fetch(API_PRODUCTS);
  const list = await res.json();

  const filtered = list.filter(p => 
    (p.product_name || "").toLowerCase().includes(keyword) || 
    (p.sku || "").toLowerCase().includes(keyword)
  );

  renderProducts(filtered);
}

// Add/Edit/Delete

//Delete
async function deleteProduct(id) {
  if (!confirm('Are you sure you want to delete this product?')) return;

  try {
    const res = await fetch(`${API_PRODUCTS}/${id}`, { 
      method: "DELETE" 
    });

    if (res.ok) {
       await loadProducts();
    } else {
      alert('Failed to delete product.');
    }
  } catch (err) {
    console.error("Error deleting product:", err);
  }
}

//Submit (Add,Edit)
async function handleProductSubmit(e) {
  e.preventDefault();

  const id = document.getElementById("p_id").value;
  const formData = ["p_name", "p_sku", "p_price", "p_stock", "p_image", "p_desc"]
    .reduce((data, field) => {
      data[field.replace("p_", "")] = document.getElementById(field).value;
      return data;
    }, { is_active: 1 });

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
      alert("Failed to save product.");
    }
  } catch (err) {
    console.error("Error saving product:", err);
  }
}

//Reset
function openModal() {
    document.getElementById("product-form").reset();
    document.getElementById("p_id").value = ""; 
    document.getElementById("modal-title").innerText = "Add Product";
    document.getElementById("product-modal").style.display = "block";
}

// Edit button in table

function editProduct(product) {
    document.getElementById("p_id").value = product.id;
    document.getElementById("p_name").value = product.product_name;
    document.getElementById("p_sku").value = product.sku;
    document.getElementById("p_price").value = product.price;
    document.getElementById("p_stock").value = product.stock_quantity;
    document.getElementById("p_image").value = product.image_url;
    document.getElementById("p_desc").value = product.description || "";

    document.getElementById("modal-title").innerText = "Edit Product";
    document.getElementById("product-modal").style.display = "block";
}

function closeModal() {
    document.getElementById("product-modal").style.display = "none";
}
