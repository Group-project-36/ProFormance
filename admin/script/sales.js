const API_SALES = "/api/orders";

//  Mock Data
let mockSales = [
    { order_id: 1024, customer: "Bruno Mars", date: "2026-03-01", total_penny: 12000, status: "Completed" },
    { order_id: 1025, customer: "Chris Wong", date: "2026-03-01", total_penny: 8900, status: "Pending" },
    { order_id: 1026, customer: "Michael Jackson", date: "2026-03-02", total_penny: 24000, status: "Cancelled" },
    { order_id: 1027, customer: "Dua Lipa", date: "2026-03-03", total_penny: 1550, status: "Completed" }
];

const formatGBP = (p) => new Intl.NumberFormat('en-GB', { style: 'currency', currency: 'GBP' }).format(p / 100);

// Render Table
const renderSales = (data) => {
    const tbody = document.getElementById("sales-table-body");
    if (!tbody) return;

    tbody.innerHTML = data.map(s => `
        <tr>
            <td>#${s.order_id}</td>
            <td><strong>${s.customer}</strong></td>
            <td>${s.date}</td>
            <td>${formatGBP(s.total_penny)}</td>
            <td><span class="status ${s.status.toLowerCase()}">${s.status}</span></td>
            <td>
                <button class="action-btn" onclick="viewOrder(${s.order_id})">View</button>
            </td>
        </tr>
    `).join('');
};

// Stats Update
const updateSalesStats = () => {
    const totalToday = mockSales
        .filter(s => s.status === "Completed")
        .reduce((sum, s) => sum + s.total_penny, 0);
    
    const pendingCount = mockSales.filter(s => s.status === "Pending").length;

    document.getElementById("today-revenue").innerText = formatGBP(totalToday);
    document.getElementById("pending-count").innerText = pendingCount;
};

// Filter
document.getElementById("status-filter").onchange = (e) => {
    const filter = e.target.value;
    if (filter === "all") {
        renderSales(mockSales);
    } else {
        const filtered = mockSales.filter(s => s.status.toLowerCase() === filter);
        renderSales(filtered);
    }
};

// Modal
window.viewOrder = (id) => {
    const order = mockSales.find(s => s.order_id === id);
    const content = document.getElementById("order-details-content");
    content.innerHTML = `
        <div style="line-height: 1.8;">
            <p><strong>Customer:</strong> ${order.customer}</p>
            <p><strong>Transaction Date:</strong> ${order.date}</p>
            <p><strong>Total Amount:</strong> ${formatGBP(order.total_penny)}</p>
            <p><strong>Payment Status:</strong> <span class="status ${order.status.toLowerCase()}">${order.status}</span></p>
            <hr style="margin: 15px 0; border: 0; border-top: 1px solid #eee;">
            <p style="font-size: 12px; color: #64748b;">Order handled by ProFormance Billing System.</p>
        </div>
    `;
    document.getElementById("order-modal").style.display = "flex";
};

window.closeOrderModal = () => {
    document.getElementById("order-modal").style.display = "none";
};

document.addEventListener("DOMContentLoaded", () => {
    renderSales(mockSales);
    updateSalesStats();
});
