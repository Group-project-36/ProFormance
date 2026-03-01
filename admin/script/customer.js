const API_CUSTOMERS = "/api/customers";


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

// Delete Customer Function (need provide DELETE /customers/{id})
async function deleteCustomer(id) {
  if (!confirm('Are you sure you want to delete this customer?')) return;

  try {
    const res = await fetch(`${API_CUSTOMERS}/${id}`, { 
      method: "DELETE" 
    });

    if (res.ok) {
       await loadCustomers();
    } else {
      alert('Failed to delete customer.');
    }
  } catch (err) {
    console.error("Error deleting customer:", err);
  }
}

// Search 
  
document.getElementById("search-btn").addEventListener("click", async () => {
  const keyword = document.getElementById("customer-search").value.toLowerCase();

  const res = await fetch(API_CUSTOMERS);
  const customersList = await res.json();

  const filtered = customersList.filter(c =>
    (c.FullName || "").toLowerCase().includes(keyword) ||
    (c.Email || "").toLowerCase().includes(keyword)
  );

  renderCustomers(filtered);
});

//render Customer

function renderCustomers(customers) {
  const tbody = document.getElementById("customer-table-body");
  tbody.innerHTML = "";
  
  if (!customers || customers.length === 0) {
    tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 20px; color: #64748b;">No customers found</td></tr>';
    return;
  }
  
  const htmlRows = customers.map(customer => {
    return `
      <tr>
        <td>${customer.FullName || customer.name}</td>
        <td>${customer.Email || customer.email}</td>
        <td>${customer.JoinDate || "-"}</td>
        <td>${customer.OrdersCount ?? "-"}</td>
        <td>${customer.Status || "Active"}</td>
        <td>
          <button class="edit-btn" style="color: blue; cursor: pointer; border: none; background: none;" onclick='editCustomer(${JSON.stringify(customer)})'>Edit</button>
          <button class="delete-btn" onclick="deleteCustomer(${customer.CustomerID})">Delete</button>
        </td>
      </tr>
    `;
  });
  tbody.innerHTML = htmlRows.join('');
}

// Add Customer Submission 
document.getElementById("customer-form").addEventListener("submit", async function(e) {
  e.preventDefault();
  
  const newData = {
    FullName: document.getElementById("name").value,
    Email: document.getElementById("email").value,
    JoinDate: document.getElementById("join-date").value,
    OrdersCount: document.getElementById("orders").value,
    Status: document.getElementById("status").value
  };
  
  try {
    const res = await fetch(API_CUSTOMERS, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(newData)
    });

    if (res.ok) {
      closeModal();
      loadCustomers();
    } else {
      alert("Failed to add customer.");
    }
  } catch (err) {
    console.error("Error adding customer:", err);
  }
});
// Edit Customer
function editCustomer(customer) {
  // Customer ID input
  document.getElementById("customer-id").value = customer.CustomerID;
  
  // Other Details
  document.getElementById("name").value = customer.FullName || customer.name;
  document.getElementById("email").value = customer.Email || customer.email;
  document.getElementById("join-date").value = customer.JoinDate || "";
  document.getElementById("orders").value = customer.OrdersCount || 0;
  document.getElementById("status").value = customer.Status || "Active";

  // Title
  document.querySelector(".modal-header h2").innerText = "Edit Customer";
  document.getElementById("add-customer-modal").style.display = "block";
}
// Load Customer 
loadCustomers();

// Modal Screen
document.getElementById("add-customer-btn").addEventListener("click", () => {
  document.getElementById("customer-form").reset(); // 清空表單
  document.getElementById("add-customer-modal").style.display = "block";
});

//Close Btn
function closeAddCustomerModal() {
  document.getElementById("add-customer-modal").style.display = "none";
}
function closeModal() {
  closeAddCustomerModal();
}
document.querySelector(".modal-close").addEventListener("click", closeAddCustomerModal);
