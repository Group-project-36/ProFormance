const API_CUSTOMERS = "/api/customers";


// Load Customer (Done)
async function loadCustomers() {
  try {
    const res = await fetch(API_CUSTOMERS);
    const data = await res.json();

    renderCustomers(data);
  } catch (err) {
    console.error("Error loading customers:", err);
  }
}


//Search customer from backend
async function searchCustomersFromDB(query) {
  const res = await fetch(
    `${API_BASE_URL}/customers/search?q=${encodeURIComponent(query)}`
  );
  if (!res.ok) return [];
  return res.json();
}

// Add Customer to DB Funnction ( For Admin Only) (via Backend )
async function addCustomerToDB(customerData) {
  const response = await fetch(`${API_BASE_URL}/customers`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(customerData)
  });

  if (!response.ok) throw new Error(`Add failed: ${response.status}`);

  return response.json();
}

// Delete Function
async function deleteCustomerFromDB(customerId) {
  const response = await fetch(`${API_BASE_URL}/customers/${customerId}`, { method: 'DELETE' });
  
  if (!response.ok) throw new Error(`Delete failed: ${response.status}`);
}

// Add Customer Modal
function openAddCustomerModal() {
  const modal = document.getElementById('add-customer-modal');
  const form = document.getElementById('customer-form');
  modal.classList.add('show');
  form.reset();

  const joinDateInput = document.getElementById('join-date');
  if (joinDateInput) {
    joinDateInput.valueAsDate = new Date();
  }
}

function closeAddCustomerModal() {
  document.getElementById('add-customer-modal').classList.remove('show');
}

// Delete Customer Function
async function deleteCustomer(customerId) {
  if (!confirm('Are you sure you want to delete this customer?')) return;

  try {
    await deleteCustomerFromDB(customerId);
    await loadCustomers();
  } catch (error) {
    alert('Failed to delete customer.');
  }
}



// Search 

document.addEventListener('DOMContentLoaded', () => {
  loadCustomers();
  
  const searchInput = document.querySelector('.search-input input');
  const searchBtn = document.querySelector('.search-btn');
  const customerForm = document.getElementById('customer-form');
  
  if (searchInput && searchBtn) {
    searchBtn.addEventListener('click', function() {
      const query = searchInput.value.trim();
      if (query) {
        searchCustomers(query);
      } else {
        loadCustomers();
      }
  });
}

  if (customerForm) {
    customerForm.addEventListener('submit', handleAddCustomer);
  }
});
// Load Customer whem first time go web

async function loadCustomers() {
  try {
    const customers = await fetchAllCustomers();
    renderCustomers(customers);
  } catch (error) {
    console.error('Error', error);
    alert('Canot load, please check for connection ');
  }
}

//Search Query
async function searchCustomers(query) {
  try {
    const customers = await searchCustomersFromDB(query);
    renderCustomers(customers);
  } catch (error) {
    console.error('Error searching customers:', error);
    alert('Search Failed.');
  }
}

//render Customer (Done)

function renderCustomers(customers) {
  const tbody = document.getElementById("customer-table-body");
  tbody.innerHTML = "";
  
  if (!list || list.length === 0) {
    tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 20px; color: #64748b;">No customers found</td></tr>';
    return;
  }
  
  list.forEach(customer => {
    const row = `
      <tr>
        <td>${customer.FullName || customer.name}</td>
        <td>${customer.Email || customer.email}</td>
        <td>${customer.JoinDate || "-"}</td>
        <td>${customer.OrdersCount ?? "-"}</td>
        <td>${customer.Status || "Active"}</td>
        <td>
          <button class="delete-btn" onclick="deleteCustomer(${customer.CustomerID})">Delete</button>
        </td>
      </tr>
    `;
    tbody.innerHTML += row;
  });
}

// Add Customer Submission
async function handleAddCustomer(e) {
  e.preventDefault();
  
  const formData = new FormData(e.target);
  const customerData = {
    name: formData.get('name').trim(),
    email: formData.get('email').trim(),
    joinDate: formData.get('join-date') || new Date().toISOString().split('T')[0],
    orders: parseInt(formData.get('orders')) || 0,
    status: formData.get('status') || 'Active'
  };
  
  if (!customerData.name || !customerData.email) {
    alert('Name and email are required fields.');
    return;
  }

    try {
    await addCustomerToDB(customerData);
    closeAddCustomerModal();
    loadCustomers();
  } catch (error) {
    console.error(error);
    alert('Failed to add customer.');
  }
}
