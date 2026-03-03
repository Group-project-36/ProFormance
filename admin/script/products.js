
const API_PRODUCTS = "/api/products";

const getCsrfToken = () => document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

async function loadProducts() {
    try {
        const res = await fetch(API_PRODUCTS);
        const products = await res.json();
        renderProducts(products);
    } catch (err) {
        console.error("Failed to fetch products:", err);
    }
}

// Delete
async function deleteProduct(id) {
    if (!confirm("Are you sure you want to delete this product?")) return;

    try {
        const res = await fetch(`${API_PRODUCTS}/${id}`, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": getCsrfToken(),
                "Content-Type": "application/json"
            }
        });
// Refresh
        if (res.ok) {
            loadProducts();
        } else {
            alert("Delete failed on server.");
        }
    } catch (err) {
        console.error("Error during delete:", err);
    }
}

// 3.Edit
function editProduct(id) {
    document.getElementById("product-id").value = id; 
    document.getElementById("modal-title").innerText = "Edit Product";
    openProductModal();
    //need to based on the ID to fill in other(e.g. product_name)
}

// 4. Submit(DB product & Variants)
document.getElementById("product-form").onsubmit = async (e) => {
    e.preventDefault();
    
    const id = document.getElementById("product-id").value;
    const isEdit = id !== "";
    
    const productPayload = {
        product_name: document.getElementById("product_name").value,
        short_description: document.getElementById("short_description").value,
        is_active: parseInt(document.getElementById("is_active").value),
        variants: [{
            sku: document.getElementById("sku").value,
            stock_quantity: parseInt(document.getElementById("stock_quantity").value)
        }]
    };

    const url = isEdit ? `${API_PRODUCTS}/${id}` : API_PRODUCTS;
    const method = isEdit ? "PUT" : "POST";

    try {
        const res = await fetch(url, {
            method: method,
            headers: {
                "X-CSRF-TOKEN": getCsrfToken(),
                "Content-Type": "application/json",
                "Accept": "application/json"
            },
            body: JSON.stringify(productPayload)
        });

        if (res.ok) {
            alert(isEdit ? "Product updated!" : "Product created!");
            closeProductModal();
            loadProducts();
        } else {
            const errorData = await res.json();
            console.error("Server error:", errorData);
            alert("Save failed. Check console for details.");
        }
    } catch (err) {
        console.error("Network error:", err);
    }
};

window.editProduct = editProduct;
window.deleteProduct = deleteProduct;
window.openProductModal = openProductModal;

document.addEventListener("DOMContentLoaded", loadProducts);
