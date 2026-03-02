const API_ANALYTICS = "/api/analytics";

// Mock Data 
const mockData = {
    revenue: {
        labels: ['24 Feb', '25 Feb', '26 Feb', '27 Feb', '28 Feb', '01 Mar', '02 Mar'],
        values: [1200, 1900, 800, 3500, 2400, 4200, 3100]
    },
    status: {
        labels: ['Paid', 'Pending', 'Fulfilled', 'Cancelled'],
        values: [45, 20, 30, 5] 
    },
    topProducts: {
        labels: ['Pro Dumbbell', 'Weight Bench', 'Yoga Mat', 'Protein Shake', 'Kettlebell'],
        values: [120, 95, 70, 55, 40]
    }
};

// Initialise Graph
const initCharts = async () => {
    let data = mockData;

    try {
        const res = await fetch(API_ANALYTICS);
        if (res.ok) data = await res.json();
    } catch (e) {
        console.warn("Using mock data for analytics.");
    }

    renderRevenueChart(data.revenue);
    renderStatusChart(data.status);
    renderProductsChart(data.topProducts);
};

// Graph Logic

function renderRevenueChart(revenue) {
    const ctx = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: revenue.labels,
            datasets: [{
                label: 'Revenue',
                data: revenue.values,
                borderColor: '#4f46e5',
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });
}

function renderStatusChart(status) {
    const ctx = document.getElementById('statusChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: status.labels,
            datasets: [{
                data: status.values,
                backgroundColor: ['#3b82f6', '#f59e0b', '#10b981', '#ef4444'] 
            }]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
    });
}

function renderProductsChart(products) {
    const ctx = document.getElementById('productsChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: products.labels,
            datasets: [{
                label: 'Units Sold',
                data: products.values,
                backgroundColor: '#6366f1'
            }]
        },
        options: { 
            indexAxis: 'y', 
            responsive: true, 
            maintainAspectRatio: false 
        }
    });
}

document.addEventListener("DOMContentLoaded", initCharts);
