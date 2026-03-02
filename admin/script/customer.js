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
    
    customers.forEach(c => {
        const name = c.first_name ? `${c.first_name} ${c.last_name}` : (c.FullName || "Unknown");
        const email = c.email_address || c.Email || "-";
        const status = c.contact_status || c.Status || "Pending";

        const tr = document.createElement("tr");
        tr.innerHTML = `
            <td><strong>${name}</strong></td>
            <td>${email}</td>
            <td>${c.created_at ? new Date(c.created_at).toLocaleDateString() : "-"}</td>
            <td>${c.order_id || "N/A"}</td>
            <td><span class="status ${status.toLowerCase()}">${status}</span></td>
            <td>
                <button class="delete-btn" onclick="deleteCustomer(${c.query_id || c.CustomerID})">Delete</button>
            </td>
        `;
        tbody.appendChild(tr);
    });
}

// Edit Customer
async function handleCustomerSubmit(e) {
    e.preventDefault();
    const nameParts = document.getElementById("name").value.split(" ");

    const newData = {
        first_name: nameParts[0],
        last_name: nameParts.slice(1).join(" ") || "",
        email_address: document.getElementById("email").value,
        contact_status: document.getElementById("status").value,
        message_description: "Added from Admin Panel"
    };
}

//Submit Form
document.getElementById("customer-form").addEventListener("submit", async function(e) {
    e.preventDefault();
    
    const fullName = document.getElementById("name").value;
    const nameParts = fullName.split(" ");
    
    const formData = {
        first_name: nameParts[0] || "Unknown",
        last_name: nameParts.slice(1).join(" ") || "",
        email_address: document.getElementById("email").value,
        contact_status: document.getElementById("status").value,
        subject_select: "Support", 
        message_description: "Added from Admin Panel" 
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

        if (res.ok) {
            closeAddCustomerModal();
            loadCustomers();
        } else {
            alert(isEdit ? "Update failed." : "Add failed.");
        }
    } catch (err) {
        console.warn("Local demo: Simulating add success.");
        mockCustomers.unshift({
            FullName: fullName,
            Email: formData.email_address,
            Status: formData.contact_status,
            JoinDate: new Date().toISOString().split('T')[0]
        });
        renderCustomers(mockCustomers);
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
