<script>
(function() {
    'use strict';
    
    // ============================================
    // SHARED UTILITIES
    // ============================================
    const Toast = {
        show: function(message, type = 'info') {
            const existing = document.querySelector('.kb-toast');
            if (existing) existing.remove();
            
            const toast = document.createElement('div');
            toast.className = `kb-toast kb-toast-${type}`;
            toast.textContent = message;
            
            toast.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 12px 20px;
                background: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#3b82f6'};
                color: white;
                border-radius: 6px;
                z-index: 9999;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                animation: kbToastSlideIn 0.3s ease;
            `;
            
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.style.animation = 'kbToastSlideOut 0.3s ease';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
            
            if (!document.querySelector('#kb-toast-animations')) {
                const style = document.createElement('style');
                style.id = 'kb-toast-animations';
                style.textContent = `
                    @keyframes kbToastSlideIn {
                        from { transform: translateX(100%); opacity: 0; }
                        to { transform: translateX(0); opacity: 1; }
                    }
                    @keyframes kbToastSlideOut {
                        from { transform: translateX(0); opacity: 1; }
                        to { transform: translateX(100%); opacity: 0; }
                    }
                `;
                document.head.appendChild(style);
            }
        }
    };
    
    const Notification = {
        show: function(message, type = 'success') {
            const existing = document.querySelector('.kb-cart-notification');
            if (existing) existing.remove();
            
            const notification = document.createElement('div');
            notification.className = `kb-cart-notification ${type}`;
            notification.innerHTML = `
                <div class="notification-content">
                    <span class="notification-icon">${type === 'success' ? '✓' : '!'}</span>
                    <span>${message}</span>
                </div>
            `;
            
            notification.style.cssText = `
                position: fixed;
                bottom: 20px;
                right: 20px;
                background: ${type === 'success' ? '#10b981' : '#ef4444'};
                color: white;
                padding: 12px 20px;
                border-radius: 8px;
                z-index: 9999;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                animation: kbNotifSlideIn 0.3s ease;
                font-family: system-ui, -apple-system, sans-serif;
            `;
            
            if (!document.querySelector('#kb-notif-animations')) {
                const style = document.createElement('style');
                style.id = 'kb-notif-animations';
                style.textContent = `
                    @keyframes kbNotifSlideIn {
                        from { transform: translateX(100%); opacity: 0; }
                        to { transform: translateX(0); opacity: 1; }
                    }
                    @keyframes kbNotifSlideOut {
                        from { transform: translateX(0); opacity: 1; }
                        to { transform: translateX(100%); opacity: 0; }
                    }
                `;
                document.head.appendChild(style);
            }
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.animation = 'kbNotifSlideOut 0.3s ease';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }
    };
    
    // ============================================
    // INITIALIZATION
    // ============================================
    document.addEventListener('DOMContentLoaded', function() {
        console.log('KB E-commerce initializing...');
        
        // Initialize all modules
        initDropdowns();
        initAccountDropdown();
        initHeroSlider();
        initProductsCarousel();
        initSidebars();
        initCategorySubmenus();
        initWishlist();
        
        console.log('KB E-commerce initialized!');
    });
    
    // ============================================
    // 1. DROPDOWN FUNCTIONALITY
    // ============================================
    function initDropdowns() {
        document.querySelectorAll('.kb-dropdown').forEach(drop => {
            const toggle = drop.querySelector('.kb-dropdown-toggle');
            const hiddenInput = drop.querySelector('input[type="hidden"]');
            const selected = drop.querySelector('.kb-selected-option');
            
            toggle?.addEventListener('click', (e) => {
                e.stopPropagation();
                drop.classList.toggle('open');
            });
            
            drop.querySelectorAll('.kb-dropdown-item').forEach(item => {
                item.addEventListener('click', () => {
                    const value = item.getAttribute('data-value');
                    if (hiddenInput) hiddenInput.value = value;
                    if (selected) selected.innerText = item.innerText;
                    drop.classList.remove('open');
                    
                    const form = drop.closest('form');
                    if (form) form.submit();
                });
            });
            
            document.addEventListener('click', (e) => {
                if (!drop.contains(e.target)) {
                    drop.classList.remove('open');
                }
            });
        });
    }
    
    // ============================================
    // 2. ACCOUNT DROPDOWN
    // ============================================
    function initAccountDropdown() {
        const accountWrapper = document.querySelector('.kb-account-wrapper');
        const accountBtn = document.querySelector('.kb-account-btn');
        
        if (accountWrapper && accountBtn) {
            accountBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                accountWrapper.classList.toggle('open');
            });
            
            document.addEventListener('click', (e) => {
                if (!accountWrapper.contains(e.target)) {
                    accountWrapper.classList.remove('open');
                }
            });
        }
    }
    
    // ============================================
    // 3. HERO SLIDER
    // ============================================
    function initHeroSlider() {
        const heroSlider = document.querySelector('.kb-hero-slider');
        if (!heroSlider) return;
        
        const slides = Array.from(heroSlider.querySelectorAll('.kb-hero-slide'));
        const dots = Array.from(document.querySelectorAll('.kb-hero-dot'));
        const prevBtn = document.querySelector('.kb-hero-nav--prev');
        const nextBtn = document.querySelector('.kb-hero-nav--next');
        
        let current = 0;
        let timer = null;
        const INTERVAL = 7000;
        
        function setActive(index) {
            current = index;
            
            slides.forEach((slide, i) => {
                slide.classList.toggle('is-active', i === current);
            });
            
            dots.forEach((dot, i) => {
                dot.classList.toggle('is-active', i === current);
            });
        }
        
        function nextSlide() {
            setActive((current + 1) % slides.length);
        }
        
        function prevSlide() {
            setActive((current - 1 + slides.length) % slides.length);
        }
        
        function startAuto() {
            stopAuto();
            timer = setInterval(nextSlide, INTERVAL);
        }
        
        function stopAuto() {
            if (timer) clearInterval(timer);
        }
        
        if (prevBtn) prevBtn.addEventListener('click', () => { prevSlide(); startAuto(); });
        if (nextBtn) nextBtn.addEventListener('click', () => { nextSlide(); startAuto(); });
        
        dots.forEach((dot, i) => {
            dot.addEventListener('click', () => {
                setActive(i);
                startAuto();
            });
        });
        
        heroSlider.addEventListener('mouseenter', stopAuto);
        heroSlider.addEventListener('mouseleave', startAuto);
        
        setActive(0);
        startAuto();
    }
    
    // ============================================
    // 4. NEW PRODUCTS CAROUSEL
    // ============================================
    function initProductsCarousel() {
        const carouselContainer = document.querySelector('.kb-new-carousel-container');
        if (!carouselContainer) return;
        
        const track = carouselContainer.querySelector('.kb-new-carousel-track');
        const cards = track.querySelectorAll('.kb-new-card');
        const parent = carouselContainer.closest('.kb-new-products-inner');
        const prevBtn = parent?.querySelector('.kb-carousel-btn--prev');
        const nextBtn = parent?.querySelector('.kb-carousel-btn--next');
        const dotsContainer = carouselContainer.querySelector('.kb-carousel-dots');
        
        if (cards.length === 0) return;
        
        const cardWidth = cards[0].offsetWidth + 24;
        const containerWidth = carouselContainer.offsetWidth;
        const cardsPerView = Math.max(1, Math.floor(containerWidth / cardWidth));
        const totalSlides = Math.ceil(cards.length / cardsPerView);
        let currentSlide = 0;
        
        // Create dots
        if (dotsContainer && totalSlides > 1) {
            for (let i = 0; i < totalSlides; i++) {
                const dot = document.createElement('button');
                dot.className = 'kb-carousel-dot';
                dot.setAttribute('aria-label', `Go to slide ${i + 1}`);
                if (i === 0) dot.classList.add('active');
                
                dot.addEventListener('click', () => {
                    goToSlide(i);
                });
                
                dotsContainer.appendChild(dot);
            }
        }
        
        const dots = dotsContainer ? dotsContainer.querySelectorAll('.kb-carousel-dot') : [];
        
        function updateButtons() {
            if (prevBtn) prevBtn.disabled = currentSlide === 0;
            if (nextBtn) nextBtn.disabled = currentSlide === totalSlides - 1;
        }
        
        function updateDots() {
            dots.forEach((dot, index) => {
                dot.classList.toggle('active', index === currentSlide);
            });
        }
        
        function goToSlide(slideIndex) {
            currentSlide = Math.max(0, Math.min(slideIndex, totalSlides - 1));
            const translateX = -currentSlide * cardsPerView * cardWidth;
            track.style.transform = `translateX(${translateX}px)`;
            updateButtons();
            updateDots();
        }
        
        function nextCarouselSlide() {
            if (currentSlide < totalSlides - 1) goToSlide(currentSlide + 1);
        }
        
        function prevCarouselSlide() {
            if (currentSlide > 0) goToSlide(currentSlide - 1);
        }
        
        if (prevBtn) prevBtn.addEventListener('click', prevCarouselSlide);
        if (nextBtn) nextBtn.addEventListener('click', nextCarouselSlide);
        
        let touchStartX = 0;
        let touchEndX = 0;
        
        track.addEventListener('touchstart', function(e) {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });
        
        track.addEventListener('touchend', function(e) {
            touchEndX = e.changedTouches[0].screenX;
            const diff = touchStartX - touchEndX;
            const swipeThreshold = 50;
            
            if (Math.abs(diff) > swipeThreshold) {
                if (diff > 0) nextCarouselSlide();
                else prevCarouselSlide();
            }
        }, { passive: true });
        
        updateButtons();
    }
    
    // ============================================
    // 5. SIDEBARS (CATEGORIES & CART)
    // ============================================
    function initSidebars() {
        const closeAll = document.querySelector('.kb-sidebar-close-all');
        const allButtons = document.querySelectorAll('.kb-all-btn, .kb-menu-btn');
        const sidebarAll = document.querySelector('.kb-sidebar-all');
        const overlayAll = document.querySelector('.kb-sidebar-overlay-all');
        
        const cartBtn = document.querySelector('.kb-cart-btn');
        const sidebarCart = document.querySelector('.kb-sidebar-cart');
        const overlayCart = document.querySelector('.kb-sidebar-overlay-cart');
        const closeCart = document.querySelector('.kb-sidebar-close-cart');
        
        function openSidebar(sidebar, overlay) {
            if (!sidebar || !overlay) return;
            sidebar.classList.add('active');
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        
        function closeSidebar(sidebar, overlay) {
            if (!sidebar || !overlay) return;
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        }
        
        allButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                openSidebar(sidebarAll, overlayAll);
            });
        });
        
        closeAll?.addEventListener('click', () => {
            closeSidebar(sidebarAll, overlayAll);
        });
        
        overlayAll?.addEventListener('click', () => {
            closeSidebar(sidebarAll, overlayAll);
        });
        
        cartBtn?.addEventListener('click', () => {
            openSidebar(sidebarCart, overlayCart);
        });
        
        closeCart?.addEventListener('click', () => {
            closeSidebar(sidebarCart, overlayCart);
        });
        
        overlayCart?.addEventListener('click', () => {
            closeSidebar(sidebarCart, overlayCart);
        });
        
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                if (sidebarAll?.classList.contains('active')) {
                    closeSidebar(sidebarAll, overlayAll);
                }
                if (sidebarCart?.classList.contains('active')) {
                    closeSidebar(sidebarCart, overlayCart);
                }
            }
        });
    }
    
    // ============================================
    // 6. CATEGORY SUBMENUS
    // ============================================
    function initCategorySubmenus() {
        const categoryMainLinks = document.querySelectorAll('.kb-category-main.has-subcategories');
        
        categoryMainLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const group = this.closest('.kb-category-group');
                const isActive = group.classList.contains('active');
                
                document.querySelectorAll('.kb-category-group').forEach(otherGroup => {
                    if (otherGroup !== group) otherGroup.classList.remove('active');
                });
                
                group.classList.toggle('active', !isActive);
            });
        });
        
        const allCategoryLinks = document.querySelectorAll('.kb-category-main, .kb-subcategory, .kb-category-deals, .kb-category-new');
        allCategoryLinks.forEach(link => {
            link.addEventListener('click', function() {
                const sidebarAll = document.querySelector('.kb-sidebar-all');
                const overlayAll = document.querySelector('.kb-sidebar-overlay-all');
                if (window.innerWidth < 768 && sidebarAll?.classList.contains('active')) {
                    setTimeout(() => {
                        if (sidebarAll && overlayAll) {
                            sidebarAll.classList.remove('active');
                            overlayAll.classList.remove('active');
                            document.body.style.overflow = '';
                        }
                    }, 300);
                }
            });
        });
    }
    
    // ============================================
    // 7. WISHLIST FUNCTIONALITY
    // ============================================
    function initWishlist() {
        document.querySelectorAll('.kb-wishlist-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const productId = this.getAttribute('data-product-id') ||
                                 this.closest('[data-product-id]')?.getAttribute('data-product-id');
                
                if (!productId) return;
                
                toggleWishlist(productId, this);
            });
        });
        
        function toggleWishlist(productId, button) {
            const icon = button.querySelector('i');
            const isActive = icon.classList.contains('bi-heart-fill');
            
            if (isActive) {
                icon.classList.remove('bi-heart-fill');
                icon.classList.add('bi-heart');
                button.style.color = '';
                Toast.show('Removed from wishlist', 'info');
                removeFromWishlist(productId);
            } else {
                icon.classList.remove('bi-heart');
                icon.classList.add('bi-heart-fill');
                button.style.color = '#dc3545';
                Toast.show('Added to wishlist', 'success');
                addToWishlist(productId);
            }
        }
        
        function addToWishlist(productId) {
            let wishlist = JSON.parse(localStorage.getItem('kb_wishlist') || '[]');
            if (!wishlist.includes(productId)) {
                wishlist.push(productId);
                localStorage.setItem('kb_wishlist', JSON.stringify(wishlist));
                updateWishlistBadge();
            }
        }
        
        function removeFromWishlist(productId) {
            let wishlist = JSON.parse(localStorage.getItem('kb_wishlist') || '[]');
            wishlist = wishlist.filter(id => id !== productId);
            localStorage.setItem('kb_wishlist', JSON.stringify(wishlist));
            updateWishlistBadge();
        }
        
        function updateWishlistBadge() {
            const wishlist = JSON.parse(localStorage.getItem('kb_wishlist') || '[]');
            const badge = document.querySelector('.kb-wishlist-badge');
            if (badge) {
                if (wishlist.length > 0) {
                    badge.textContent = wishlist.length > 99 ? '99+' : wishlist.length;
                    badge.style.display = 'flex';
                } else {
                    badge.style.display = 'none';
                }
            }
        }
        
        updateWishlistBadge();
    }
    
    // ============================================
    // 7. cart and the mini cart in the header which is disabled
    // ============================================

class CartManager {
    constructor() {
        this.config = {
            endpoints: {
                update: '/cart/update/',
                remove: '/cart/remove/',
                clear: '/cart/clear',
                count: '/cart/count'
            },
            selectors: {
                cartContainer: '.pf-cart-container',
                cartItem: '.pf-cart-item-card',
                quantityBtn: '.pf-quantity-btn',
                quantityInput: '.pf-quantity-input',
                removeBtn: '.pf-cart-remove-btn',
                clearBtn: '#pfClearCartBtn',
                subtotalEl: '.pf-cart-item-subtotal',
                unitPrice: '.pf-cart-item-unit-price',
                summaryTotal: '[data-cart-summary="total"]',
                summaryItems: '[data-cart-summary="total-items"]',
                summaryUnique: '[data-cart-summary="unique"]'
            },
            currency: {
                GBP: '£',
                USD: '$',
                EUR: '€'
            }
        };
        
        this.state = {
            isUpdating: false,
            items: []
        };
        
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    }
    
    init() {
        if (!this.isCartPage()) {
            return;
        }
        
        console.log('CartManager: Initializing...');
        
        this.cacheElements();
        this.bindEvents();
        this.updateCartSummary();
        
        console.log('CartManager: Ready');
    }
    
    isCartPage() {
        return !!document.querySelector(this.config.selectors.cartContainer);
    }
    
    cacheElements() {
        this.elements = {
            cartContainer: document.querySelector(this.config.selectors.cartContainer),
            cartItems: document.querySelectorAll(this.config.selectors.cartItem),
            quantityButtons: document.querySelectorAll(this.config.selectors.quantityBtn),
            quantityInputs: document.querySelectorAll(this.config.selectors.quantityInput),
            removeButtons: document.querySelectorAll(this.config.selectors.removeBtn),
            clearButton: document.querySelector(this.config.selectors.clearBtn)
        };
    }
    

    bindEvents() {
        this.elements.quantityButtons.forEach(button => {
            button.addEventListener('click', (e) => this.handleQuantityButton(e));
        });
        
        this.elements.quantityInputs.forEach(input => {
            input.addEventListener('change', (e) => this.handleQuantityChange(e));
            input.addEventListener('blur', (e) => this.validateQuantityInput(e));
        });
        
        this.elements.removeButtons.forEach(button => {
            button.addEventListener('click', (e) => this.handleRemoveItem(e));
        });
        
        if (this.elements.clearButton) {
            this.elements.clearButton.addEventListener('click', (e) => this.handleClearCart(e));
        }
        
        document.addEventListener('input', (e) => {
            if (e.target.matches(this.config.selectors.quantityInput) && e.target.value < 1) {
                e.target.value = 1;
            }
        });
    }

    handleQuantityButton(event) {
        if (this.state.isUpdating) return;
        
        const button = event.currentTarget;
        const itemId = button.dataset.cartItemId;
        const input = document.querySelector(`${this.config.selectors.quantityInput}[data-cart-item-id="${itemId}"]`);
        
        if (!input || !itemId) {
            this.showToast('Unable to update quantity', 'error');
            return;
        }
        
        let quantity = parseInt(input.value) || 1;
        
        if (button.classList.contains('pf-quantity-plus')) {
            quantity += 1;
        } else if (button.classList.contains('pf-quantity-minus')) {
            quantity -= 1;
        }
        
        if (quantity < 1) {
            this.confirmRemoveItem(itemId);
            return;
        }
        
        input.value = quantity;
        this.updateQuantity(itemId, quantity);
    }
    
    handleQuantityChange(event) {
        const input = event.currentTarget;
        const itemId = input.dataset.cartItemId;
        let quantity = parseInt(input.value) || 1;
        
        if (quantity < 1) {
            this.confirmRemoveItem(itemId);
            return;
        }
        
        this.updateQuantity(itemId, quantity);
    }
    
    validateQuantityInput(event) {
        const input = event.currentTarget;
        if (input.value < 1) {
            input.value = 1;
        }
    }
    
    handleRemoveItem(event) {
        const button = event.currentTarget;
        const itemId = button.dataset.cartItemId;
        
        if (itemId) {
            this.confirmRemoveItem(itemId);
        }
    }
    
    async handleClearCart(event) {
        if (!confirm('Are you sure you want to clear your entire cart?')) {
            return;
        }
        
        try {
            this.state.isUpdating = true;
            
            const response = await fetch(this.config.endpoints.clear, {
                method: 'POST',
                headers: this.getRequestHeaders()
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.animateClearCart();
                this.showToast('Cart cleared successfully', 'success');
                
                setTimeout(() => {
                    window.location.reload();
                }, 500);
            } else {
                this.showToast(result.message || 'Failed to clear cart', 'error');
            }
        } catch (error) {
            console.error('CartManager: Clear cart error:', error);
            this.showToast('Error clearing cart', 'error');
        } finally {
            this.state.isUpdating = false;
        }
    }
    
    async updateQuantity(itemId, quantity) {
        if (!itemId || this.state.isUpdating) return;
        
        try {
            this.state.isUpdating = true;
            
            const response = await fetch(`${this.config.endpoints.update}${itemId}`, {
                method: 'PUT',
                headers: this.getRequestHeaders('json'),
                body: JSON.stringify({ quantity })
            });
            
            const result = await response.json();
            
            if (result.success) {
                if (result.item_removed) {
                    this.removeItemFromDOM(itemId);
                } else {
                    this.updateItemSubtotal(itemId);
                    this.updateCartSummary();
                    this.updateCartBadge();
                    this.showToast('Quantity updated', 'success');
                }
            } else {
                this.showToast(result.message || 'Failed to update quantity', 'error');
                this.revertQuantityInput(itemId);
            }
        } catch (error) {
            console.error('CartManager: Update quantity error:', error);
            this.showToast('Error updating quantity', 'error');
            this.revertQuantityInput(itemId);
        } finally {
            this.state.isUpdating = false;
        }
    }

    async confirmRemoveItem(itemId) {
        if (!confirm('Remove this item from cart?')) {
            return;
        }
        
        try {
            this.state.isUpdating = true;
            
            const response = await fetch(`${this.config.endpoints.remove}${itemId}`, {
                method: 'DELETE',
                headers: this.getRequestHeaders()
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.removeItemFromDOM(itemId);
                this.updateCartSummary();
                this.updateCartBadge();
                this.showToast('Item removed from cart', 'success');
            } else {
                this.showToast(result.message || 'Failed to remove item', 'error');
            }
        } catch (error) {
            console.error('CartManager: Remove item error:', error);
            this.showToast('Error removing item', 'error');
        } finally {
            this.state.isUpdating = false;
        }
    }
    
    removeItemFromDOM(itemId) {
        const itemElement = document.querySelector(`[data-cart-item-id="${itemId}"]`);
        
        if (!itemElement) return;
        
        itemElement.style.transition = 'all 0.3s ease';
        itemElement.style.opacity = '0';
        itemElement.style.transform = 'translateX(-100px)';
        itemElement.style.height = `${itemElement.offsetHeight}px`;
        
        setTimeout(() => {
            itemElement.style.height = '0';
            itemElement.style.margin = '0';
            itemElement.style.padding = '0';
            itemElement.style.border = 'none';
            itemElement.style.overflow = 'hidden';
        }, 50);
        
        setTimeout(() => {
            itemElement.remove();
            
            const remainingItems = document.querySelectorAll(this.config.selectors.cartItem);
            if (remainingItems.length === 0) {
                setTimeout(() => {
                    window.location.reload();
                }, 500);
            }
        }, 350);
    }
    
    animateClearCart() {
        const items = document.querySelectorAll(this.config.selectors.cartItem);
        
        items.forEach((item, index) => {
            setTimeout(() => {
                item.style.transition = 'all 0.3s ease';
                item.style.opacity = '0';
                item.style.transform = 'translateX(100px) scale(0.8)';
                item.style.height = `${item.offsetHeight}px`;
                
                setTimeout(() => {
                    item.style.height = '0';
                    item.style.margin = '0';
                    item.style.padding = '0';
                }, 50);
            }, index * 100);
        });
    }
    

    updateItemSubtotal(itemId) {
        const itemElement = document.querySelector(`[data-cart-item-id="${itemId}"]`);
        if (!itemElement) return;
        
        const priceElement = itemElement.querySelector(this.config.selectors.unitPrice);
        const subtotalElement = itemElement.querySelector(this.config.selectors.subtotalEl);
        const quantityInput = itemElement.querySelector(this.config.selectors.quantityInput);
        
        if (!priceElement || !subtotalElement || !quantityInput) return;
        
        const priceText = priceElement.textContent.trim();
        const priceMatch = priceText.match(/[\d.]+/);
        
        if (!priceMatch) return;
        
        const price = parseFloat(priceMatch[0]);
        const quantity = parseInt(quantityInput.value) || 1;
        const subtotal = price * quantity;
        
        const currencySymbol = this.extractCurrencySymbol(priceText);
        const subtotalAmount = subtotalElement.querySelector('.pf-subtotal-amount');
        
        if (subtotalAmount) {
            subtotalAmount.textContent = subtotal.toFixed(2);
        } else {
            subtotalElement.innerHTML = `
                <span class="pf-currency-symbol">${currencySymbol}</span>
                <span class="pf-subtotal-amount">${subtotal.toFixed(2)}</span>
            `;
        }
    }
    extractCurrencySymbol(priceText) {
        const symbolMatch = priceText.match(/[£$€¥]/);
        return symbolMatch ? symbolMatch[0] : '£';
    }
    
    updateCartSummary() {
        let subtotal = 0;
        let totalItems = 0;
        const uniqueProducts = new Set();
        
        document.querySelectorAll(this.config.selectors.cartItem).forEach(item => {
            const quantityInput = item.querySelector(this.config.selectors.quantityInput);
            const productId = item.dataset.cartProductId;
            
            if (quantityInput && productId) {
                const quantity = parseInt(quantityInput.value) || 1;
                totalItems += quantity;
                uniqueProducts.add(productId);
                
                const priceElement = item.querySelector(this.config.selectors.unitPrice);
                if (priceElement) {
                    const priceText = priceElement.textContent.trim();
                    const priceMatch = priceText.match(/[\d.]+/);
                    
                    if (priceMatch) {
                        const price = parseFloat(priceMatch[0]);
                        subtotal += price * quantity;
                    }
                }
            }
        });
        
        this.updateSummaryDisplay('unique', uniqueProducts.size);
        this.updateSummaryDisplay('total-items', totalItems);
        this.updateSummaryDisplay('total', subtotal.toFixed(2));
    }

    updateSummaryDisplay(type, value) {
        const element = document.querySelector(`[data-cart-summary="${type}"]`);
        if (!element) return;
        
        if (type === 'total') {
            const currentText = element.textContent.trim();
            const symbolMatch = currentText.match(/[£$€¥]/);
            const symbol = symbolMatch ? symbolMatch[0] : '£';
            element.textContent = `${symbol}${value}`;
        } else {
            element.textContent = value;
        }
    }
    
    updateCartBadge() {
        const event = new CustomEvent('cart-updated', {
            detail: { timestamp: Date.now() }
        });
        document.dispatchEvent(event);
        
        const badge = document.querySelector('.cart-badge, .pf-cart-badge, [data-cart-count]');
        if (badge) {
            this.fetchCartCount().then(count => {
                badge.textContent = count;
                badge.style.display = count > 0 ? 'flex' : 'none';
            });
        }
    }
    
    async fetchCartCount() {
        try {
            const response = await fetch(this.config.endpoints.count);
            const data = await response.json();
            return data.total_items || 0;
        } catch (error) {
            console.error('CartManager: Fetch count error:', error);
            return 0;
        }
    }
    
    revertQuantityInput(itemId) {
        const input = document.querySelector(`${this.config.selectors.quantityInput}[data-cart-item-id="${itemId}"]`);
        const itemElement = document.querySelector(`[data-cart-item-id="${itemId}"]`);
        
        if (input && itemElement) {
            const previousQuantity = input.dataset.previousQuantity || 1;
            input.value = previousQuantity;
            this.updateItemSubtotal(itemId);
        }
    }
    
    showToast(message, type = 'info') {
        document.querySelectorAll('.pf-toast').forEach(toast => {
            toast.remove();
        });
        
        const toast = document.createElement('div');
        toast.className = `pf-toast pf-toast-${type}`;
        toast.innerHTML = `
            <div class="pf-toast-content">
                <span class="pf-toast-message">${message}</span>
                <button class="pf-toast-close">&times;</button>
            </div>
        `;
        
        document.body.appendChild(toast);
        toast.querySelector('.pf-toast-close').addEventListener('click', () => {
            toast.remove();
        });
        
        setTimeout(() => {
            if (toast.parentNode) {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(20px)';
                setTimeout(() => toast.remove(), 300);
            }
        }, 3000);
        
        setTimeout(() => {
            toast.style.opacity = '1';
            toast.style.transform = 'translateY(0)';
        }, 10);
    }
    
    getRequestHeaders(contentType = 'json') {
        const headers = {
            'X-CSRF-TOKEN': this.csrfToken,
            'Accept': 'application/json'
        };
        
        if (contentType === 'json') {
            headers['Content-Type'] = 'application/json';
        }
        
        return headers;
    }

    debug() {
        console.log('=== CartManager Debug ===');
        console.log('State:', this.state);
        console.log('Config:', this.config);
        console.log('CSRF Token:', this.csrfToken ? 'Present' : 'Missing');
        console.log('Cart Items:', this.elements.cartItems.length);
        console.log('======================');
    }
}

document.addEventListener('DOMContentLoaded', function () {
    if (window.PFCart && typeof window.PFCart.init === 'function') {
        window.PFCart.init();
    }
});

const cartStyles = `
.pf-toast {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    min-width: 300px;
    max-width: 400px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    padding: 16px;
    opacity: 0;
    transform: translateY(-20px);
    transition: all 0.3s ease;
    border-left: 4px solid #3498db;
}

.pf-toast-success {
    border-left-color: #2ecc71;
}

.pf-toast-error {
    border-left-color: #e74c3c;
}

.pf-toast-warning {
    border-left-color: #f39c12;
}

.pf-toast-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.pf-toast-message {
    flex: 1;
    margin-right: 12px;
    font-size: 14px;
    color: #333;
}

.pf-toast-close {
    background: none;
    border: none;
    font-size: 20px;
    cursor: pointer;
    color: #999;
    padding: 0;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
}

.pf-toast-close:hover {
    background: #f5f5f5;
    color: #666;
}
`;

if (!document.querySelector('#cart-manager-styles')) {
    const styleElement = document.createElement('style');
    styleElement.id = 'cart-manager-styles';
    styleElement.textContent = cartStyles;
    document.head.appendChild(styleElement);
}
})();
</script>