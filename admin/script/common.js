async function loadSidebar() {
    const placeholder = document.getElementById('sidebar-placeholder');
    if (!placeholder) {
        console.warn("Sidebar placeholder not found on this page.");
        return;
    }

    try {
        const response = await fetch('sidebar.html');
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
        
        const html = await response.text();
        placeholder.innerHTML = html;

        const currentPage = window.location.pathname.split("/").pop() || "index.html";
        const navLinks = placeholder.querySelectorAll('.nav a');
        navLinks.forEach(link => {
            if (link.getAttribute('href') === currentPage) {
                link.classList.add('active');
            }
        });
        console.log("Sidebar loaded successfully.");
    } catch (err) {
        console.error("Failed to load sidebar:", err);
        placeholder.innerHTML = "<p style='color:white; padding:10px;'>Error loading menu.</p>";
    }
}

const initGlobalSearch = () => {
    const searchInput = document.getElementById("main-search");
    const resultsContainer = document.getElementById("search-results");
    if (!searchInput || !resultsContainer) return;

    const siteMap = [
        { title: "Dashboard Overview", url: "index.html", icon: "fa-border-all" },
        { title: "Sales Analytics", url: "analytics.html", icon: "fa-chart-column" },
        { title: "Customer Management", url: "customer.html", icon: "fa-users" },
        { title: "Product Inventory", url: "index.html#products", icon: "fa-dumbbell" }
    ];

    searchInput.addEventListener("input", (e) => {
        const kw = e.target.value.toLowerCase().trim();
        if (!kw) return resultsContainer.classList.remove("show");

        const filtered = siteMap.filter(item => item.title.toLowerCase().includes(kw));
        
        if (filtered.length > 0) {
            resultsContainer.innerHTML = filtered.map(item => `
                <a href="${item.url}" class="search-item">
                    <i class="fas ${item.icon}"></i>
                    <span>${item.title}</span>
                </a>
            `).join('');
            resultsContainer.classList.add("show");
        } else {
            resultsContainer.innerHTML = `<div class="search-empty">No pages found.</div>`;
            resultsContainer.classList.add("show");
        }
    });

    document.addEventListener("click", (e) => {
        if (!searchInput.contains(e.target)) resultsContainer.classList.remove("show");
    });
};

const initUI = () => {
    const avatar = document.getElementById("profile-avatar");
    const dropdown = document.getElementById("profile-dropdown");
    if (avatar && dropdown) {
        avatar.onclick = (e) => { e.stopPropagation(); dropdown.classList.toggle("show"); };
        window.onclick = () => dropdown.classList.remove("show");
    }
};

// Initialize everything
document.addEventListener("DOMContentLoaded", () => {
    loadSidebar();
    initUI();
    initGlobalSearch();
});
