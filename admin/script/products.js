
const API_PRODUCTS = "/api/products";
//Mock Data
const mockProducts = [
    { 
        product_id: 1, 
        product_name: "Pro Dumbbell Set", 
        brand_id: 101, 
        is_active: 1, 
        stock_sum: 45, 
        short_description: "High-quality rubber dumbbells"
    },
    { 
        product_id: 2, 
        product_name: "Weight Bench Pro", 
        brand_id: 102, 
        is_active: 1, 
        stock_sum: 12,
        short_description: "Adjustable commercial bench"
    }
];

const renderProducts = (products) => {
    const tbody = document.getElementById("product-table-body");
    if (!tbody) return;
    
    tbody.innerHTML = products.map(p => `
        <tr>
            <td>
                <div style="font-weight:600;">${p.product_name}</div>
                <div style="font-size:12px; color:#64748b;">${p.short_description || ''}</div>
            </td>
            <td>ID: ${p.brand_id}</td>
            <td>${p.stock_sum} in stock</td>
            <td>
                <span class="status ${p.is_active ? 'completed' : 'cancelled'}">
                    ${p.is_active ? 'Active' : 'Inactive'}
                </span>
            </td>
            <td>
                <button class="action-btn" onclick="editProduct(${p.product_id})"><i class="fas fa-edit"></i></button>
                <button class="delete-btn" onclick="deleteProduct(${p.product_id})"><i class="fas fa-trash"></i></button>
            </td>
        </tr>
    `).join('');
};

function openProductModal() { document.getElementById("product-modal").style.display = "flex"; }
function closeProductModal() { 
    document.getElementById("product-modal").style.display = "none";
    document.getElementById("product-form").reset();
}

// Send form
document.getElementById("product-form").onsubmit = async (e) => {
    e.preventDefault();
    
    const productPayload = {
        product_name: document.getElementById("product_name").value,
        short_description: document.getElementById("short_description").value,
        is_active: parseInt(document.getElementById("is_active").value),
        variants: [{
            sku: document.getElementById("sku").value,
            stock_quantity: parseInt(document.getElementById("stock_quantity").value)
        }]
    };

    console.log("Sending to Laravel API:", productPayload);
    
    alert("Product saved successfully (Simulated)");
    closeProductModal();
};

document.addEventListener("DOMContentLoaded", () => {
    renderProducts(mockProducts);
});
