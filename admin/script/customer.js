const API_CUSTOMERS = "/api/customers";
// Mock Data(for local)
const mockCustomers = [
    { id: 1, name: "Bruno Mars", email: "bruno@example.com", join_date: "2025-11-10", orders: 12, status: "Active" },
    { id: 2, name: "Chris Wong", email: "chris@example.com", join_date: "2025-11-12", orders: 5, status: "Inactive" }
];

// Load Customer 
async function loadCustomers() {
  try {
    const res = await fetch(API_CUSTOMERS);
    const data = await res.json();

    renderCustomers(data);
  } catch (err) {
    console.error("Error loading customers:", err);
  }
}

//Render Table
function renderCustomers(customers) {
    const tbody = document.getElementById("customer-table-body");
    if (!tbody) return;
    tbody.innerHTML = "";
    
    if (!customers || customers.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 20px;">No customers found</td></tr>';
        return;
    }
    
    tbody.innerHTML = customers.map(c => `
        <tr>
            <td>${c.name || c.FullName}</td>
            <td>${c.email || c.Email}</td>
            <td>${c.join_date || c.JoinDate || "-"}</td>
            <td>${c.orders ?? c.OrdersCount ?? 0}</td>
            <td><span class="status ${String(c.status || c.Status).toLowerCase()}">${c.status || c.Status}</span></td>
            <td>
                <button class="edit-link" onclick='editCustomer(${JSON.stringify(c)})'>Edit</button>
                <button class="delete-btn" onclick="deleteCustomer(${c.id || c.CustomerID})" style="color:red; margin-left:10px;">Delete</button>
            </td>
        </tr>
    `).join('');
}

// Edit Customer
function editCustomer(customer) {
    document.getElementById("name").value = customer.name || customer.FullName;
    document.getElementById("email").value = customer.email || customer.Email;
    document.getElementById("join-date").value = customer.join_date || customer.JoinDate;
    document.getElementById("orders").value = customer.orders ?? customer.OrdersCount;
    document.getElementById("status").value = customer.status || customer.Status;
    
    // Change modal title
    document.querySelector(".modal-header h2").innerText = "Edit Customer";
    document.getElementById("add-customer-modal").style.display = "block";
    window.editingCustomerId = customer.id || customer.CustomerID;
}

//Submit Form
document.getElementById("customer-form").addEventListener("submit", async function(e) {
    e.preventDefault();
    
    const formData = {
        name: document.getElementById("name").value,
        email: document.getElementById("email").value,
        join_date: document.getElementById("join-date").value,
        orders: document.getElementById("orders").value,
        status: document.getElementById("status").value
    };
    
    const isEdit = !!window.editingCustomerId;
    const url = isEdit ? `${API_CUSTOMERS}/${window.editingCustomerId}` : API_CUSTOMERS;
    const method = isEdit ? "PUT" : "POST";

    try {
        const res = await fetch(url, {
            method: method,
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(formData)
        });
//Reset
        if (res.ok) {
            closeAddCustomerModal();
            loadCustomers();
            window.editingCustomerId = null; 
        } else {
            alert(isEdit ? "Update failed." : "Add failed.");
        }
    } catch (err) {
        console.error("Network error, simulating success for local demo.");
        closeAddCustomerModal();
    }
});

document.addEventListener("DOMContentLoaded", loadCustomers);

//UI Control
function closeAddCustomerModal() {
    document.getElementById("add-customer-modal").style.display = "none";
    document.getElementById("customer-form").reset();
    document.querySelector(".modal-header h2").innerText = "Add New Customer";
    window.editingCustomerId = null;
}
