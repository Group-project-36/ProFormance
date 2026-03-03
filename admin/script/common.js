async function loadSidebar() {
    const placeholder = document.getElementById('sidebar-placeholder');
    if (!placeholder) return;

    try {
        const response = await fetch('sidebar.html'); 
        const html = await response.text();
        
        placeholder.innerHTML = html;
      
        let currentPage = window.location.pathname.split("/").pop() || "index.html";
        
        const navLinks = placeholder.querySelectorAll('.nav a');
        navLinks.forEach(link => {
            link.classList.remove('active');
            const href = link.getAttribute('href');
            
            if (href === currentPage) {
                link.classList.add('active');
            }
        });
    } catch (err) {
        console.error("Sidebar 載入失敗:", err);
    }
}
document.addEventListener("DOMContentLoaded", loadSidebar);
