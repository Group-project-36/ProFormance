document.addEventListener('DOMContentLoaded', function() {
  
// Add Customer Button Funnction ( For Admin Only) ( Need change based on DB)
  const addCustomerBtn = document.getElementById('add-customer-btn');
  if (addCustomerBtn) {
    addCustomerBtn.addEventListener('click', function() {
      const name = prompt('Enter customer name:');
      if (!name) return;
      
      const email = prompt('Enter customer email:');
      if (!email) return;
      
      const joinDate = prompt('Enter join date (YYYY-MM-DD):') || new Date().toISOString().split('T')[0];
      const orders = prompt('Enter number of orders:') || '0';
      const status = prompt('Enter status (Active/Inactive):') || 'Active';
      
      const tbody = document.querySelector('.table-container tbody');
      const newRow = document.createElement('tr');
      
      const statusClass = status.toLowerCase() === 'active' ? 'delivered' : 'pending';
      
      newRow.innerHTML = `
        <td>${name}</td>
        <td>${email}</td>
        <td>${joinDate}</td>
        <td>${orders}</td>
        <td><span class="status ${statusClass}">${status}</span></td>
        <td>
          <button class="delete-btn" onclick="deleteCustomer(this)" title="Delete customer">
            <i class="fas fa-trash"></i>
          </button>
        </td>
      `;
      
      tbody.appendChild(newRow);
    });
  }
});
