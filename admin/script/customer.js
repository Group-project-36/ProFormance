const API_BASE_URL = 'http://cs2team36.cs2410-web01pvm.aston.ac.uk/api';

// Get Customer from DB
async function fetchAllCustomers() {
  try {
    const response = await fetch(`${API_BASE_URL}/customers`);
    
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    
    const customers = await response.json();
    return customers;
  } catch (error) {
    console.error('Error Getting Customers:', error);
    throw error;
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

document.addEventListener('DOMContentLoaded', function() {
  
// Add Customer Button Funnction ( For Admin Only) (via Backend )
const addCustomerBtn = document.getElementById('add-customer-btn');

if (addCustomerBtn) {
  addCustomerBtn.addEventListener('click', async () => {
    const name = prompt('Enter customer name:');
    if (!name) return;

    const email = prompt('Enter customer email:');
    if (!email) return;

    const joinDate =
      prompt('Enter join date (YYYY-MM-DD):') ||
      new Date().toISOString().split('T')[0];

    const orders = prompt('Enter number of orders:') || 0;
    const status = prompt('Enter status (Active/Inactive):') || 'Active';

    const customerData = {
      name,
      email,
      joinDate,
      orders: Number(orders),
      status
    };

    try {
      const newCustomer = await addCustomerToDB(customerData);

      const tbody = document.querySelector('.table-container tbody');
      const newRow = document.createElement('tr');

      const statusClass =
        newCustomer.status.toLowerCase() === 'active'
          ? 'delivered'
          : 'pending';

      newRow.innerHTML = `
        <td>${newCustomer.name}</td>
        <td>${newCustomer.email}</td>
        <td>${newCustomer.joinDate}</td>
        <td>${newCustomer.orders}</td>
        <td><span class="status ${statusClass}">${newCustomer.status}</span></td>
        <td>
          <button class="delete-btn" onclick="deleteCustomer(this)">
            <i class="fas fa-trash"></i>
          </button>
        </td>
      `;

      tbody.appendChild(newRow);
    } catch (err) {
      alert('Failed to add customer. Please try again.');
    }
  });
}
// Add Customer Btn
  const addCustomerBtn = document.getElementById('add-customer-btn');
  if (addCustomerBtn) {
    addCustomerBtn.addEventListener('click', function() {
      openAddCustomerModal();
    });
  }
  

// Delete Function btn

function deleteCustomer(button) {
  if (confirm('Confirm if you want to delete this customer account and there details.')) {
    const row = button.closest('tr');
    row.remove();
  }
}

// Search 

const searchInput = document.querySelector('.search-input input');
const searchBtn = document.querySelector('.search-btn');
  
  if (searchInput && searchBtn) {
    searchBtn.addEventListener('click', function() {
      const query = searchInput.value.trim();
      if (query) {
        searchCustomers(query);
      } else {
        loadCustomers();
      }
});

// Submit form

  const customerForm = document.getElementById('customer-form');
  if (customerForm) {
    customerForm.addEventListener('submit', handleAddCustomer);
  }
});

// Load Customer whem first time go web

async function loadCustomers() {
  try {
    showLoadingState();
    const customers = await fetchAllCustomers();
    renderCustomers(customers);
  } catch (error) {
    console.error('Error', error);
    showError('Canot load, please check for connection ');
  }
}

//Search Query
async function searchCustomers(query) {
  try {
    showLoadingState();
    const customers = await searchCustomersFromDB(query);
    renderCustomers(customers);
  } catch (error) {
    console.error('Error searching customers:', error);
    showError('Failed to search customers.');
    showEmptyState('Search failed. Please try again.');
  }
}

//render Customer

function renderCustomers(customers) {
  const tbody = document.querySelector('.table-container tbody');
  if (!tbody) return;
  
  if (customers.length === 0) {
    tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 20px; color: #64748b;">No customers found</td></tr>';
    return;
  }
  
  tbody.innerHTML = customers.map(customer => {
    const statusClass = customer.status.toLowerCase() === 'active' ? 'delivered' : 'pending';
    return `
      <tr data-customer-id="${customer.id}">
        <td>${escapeHtml(customer.name)}</td>
        <td>${escapeHtml(customer.email)}</td>
        <td>${customer.joinDate || 'N/A'}</td>
        <td>${customer.orders || 0}</td>
        <td><span class="status ${statusClass}">${customer.status}</span></td>
        <td>
          <button class="delete-btn" onclick="deleteCustomer(${customer.id})" title="Delete customer">
            <i class="fas fa-trash"></i>
          </button>
        </td>
      </tr>
    `;
  }).join('');
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
    showError('Name and email are required fields.');
    return;
  }

    closeAddCustomerModal();
    loadCustomers();
    showSuccess('Customer added successfully!');
  } catch (error) {
    console.error('Error adding customer:', error);
    showError(error.message || 'Failed to add customer. Please try again.');
  } finally {
    submitBtn.disabled = false;
    submitBtn.textContent = originalText;
  }
}
