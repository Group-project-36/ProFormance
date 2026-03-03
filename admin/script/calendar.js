const API_CALENDAR = "/api/schedules";

// Helper to get CSRF token from meta tag
const getCsrf = () => document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

// Fetch Schedules from Laravel
async function loadSchedules() {
    try {
        const res = await fetch(API_CALENDAR);
        if (!res.ok) throw new Error("Backend not connected");
        const data = await res.json();
        renderSchedules(data);
    } catch (err) {
        console.warn("Backend offline. Using Mock Data for preview.");
        const mockData = [
            { id: 1, name: "PT Session", type: "Training", time: "2026-03-04T10:00", staff: "Coach Alex", status: "Active" }
        ];
        renderSchedules(mockData);
    }
}

// Render Table
function renderSchedules(events) {
    const tbody = document.getElementById("calendar-table-body");
    if (!tbody) return;

    tbody.innerHTML = events.map(e => `
        <tr>
            <td><strong>${e.name}</strong></td>
            <td>${e.type}</td>
            <td>${new Date(e.time).toLocaleString('en-GB')}</td>
            <td>${e.staff || 'Unassigned'}</td>
            <td><span class="status ${e.status.toLowerCase()}">${e.status}</span></td>
            <td>
                <button class="delete-btn" onclick="window.deleteSchedule(${e.id})">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    `).join('');
}

// Data to Laravel
document.getElementById("event-form").onsubmit = async (e) => {
    e.preventDefault();
    
    const payload = {
        name: document.getElementById("event_name").value,
        type: document.getElementById("event_type").value,
        start_time: document.getElementById("start_time").value,
        staff: document.getElementById("staff_name").value
    };

    try {
        const res = await fetch(API_CALENDAR, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": getCsrf(),
                "Accept": "application/json"
            },
            body: JSON.stringify(payload)
        });

        if (res.ok) {
            window.closeEventModal();
            loadSchedules();
        }
    } catch (err) {
        alert("Local Preview: Simulated Success!");
        window.closeEventModal();
    }
};

// Global exports for HTML 
window.openEventModal = () => document.getElementById("event-modal").style.display = "flex";
window.closeEventModal = () => document.getElementById("event-modal").style.display = "none";
window.deleteSchedule = async (id) => {
    if (confirm("Delete this entry?")) {
        await fetch(`${API_CALENDAR}/${id}`, { 
            method: "DELETE", 
            headers: { "X-CSRF-TOKEN": getCsrf() } 
        });
        loadSchedules();
    }
};

document.addEventListener("DOMContentLoaded", loadSchedules);
