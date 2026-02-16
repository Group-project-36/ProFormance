/**
 * LOGIC ENGINE (Toast): Manages the lifecycle of top-screen alerts. 
 * This script dynamically creates toast elements, injects content, 
 * and assigns state classes (success/error/info). It handles the timing 
 * sequence, triggering exit animations and removing elements from the 
 * DOM after a set duration.
 */
const Toast = {
    show: function(message, type = 'info') {
        const existing = document.querySelector('.kb-toast');
        if (existing) existing.remove();
        
        const toast = document.createElement('div');
        toast.className = `kb-toast kb-toast-${type}`;
        toast.textContent = message;
        
        document.body.appendChild(toast);
        setTimeout(() => {
            toast.classList.add('kb-exit');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
};

/**
 * LOGIC ENGINE (Notification): Manages the lifecycle of bottom-screen alerts. 
 * Specifically designed for action feedback, it creates elements with icons
 * and handles the timing sequence to ensure the UI remains uncluttered 
 * by automatically removing notifications after use.
 */
const Notification = {
    show: function(message, type = 'success') {
        const existing = document.querySelector('.kb-cart-notification');
        if (existing) existing.remove();
        
        const notification = document.createElement('div');
        notification.className = `kb-cart-notification ${type}`;
        
        const icon = type === 'success' ? '✓' : '!';
        notification.innerHTML = `
            <div class="notification-content">
                <span class="notification-icon">${icon}</span>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.classList.add('kb-exit');
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
};