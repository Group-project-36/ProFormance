<script>
(function() {

    
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
})();
    
    (function () {
  const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

  function formatMoney(n) { return Number(n).toFixed(2); }

  function debounce(fn, delay = 350) {
    let t;
    return (...args) => { clearTimeout(t); t = setTimeout(() => fn(...args), delay); };
  }

  async function postQtyUpdate(form, qty) {
    const res = await fetch(form.action, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': token
      },
      body: JSON.stringify({ quantity: qty })
    });
    if (!res.ok) throw new Error('Update failed');
    return await res.json();
  }

  function setRowStatus(row, msg) {
    const el = row.querySelector('.js-row-status');
    if (el) el.textContent = msg || '';
  }

  function updateRowSubtotal(row, qty) {
    const price = Number(row.getAttribute('data-price') || 0);
    const subtotalEl = row.querySelector('.js-line-subtotal');
    if (subtotalEl) subtotalEl.textContent = formatMoney(price * qty);
  }

  function updateSummary(totalItems, total) {
    const el1 = document.getElementById('js-total-items');
    const el2 = document.getElementById('js-total-items-2');
    const totalEl = document.getElementById('js-total');
    if (el1) el1.textContent = totalItems;
    if (el2) el2.textContent = totalItems;
    if (totalEl) totalEl.textContent = formatMoney(total);
  }

  const sendUpdate = debounce(async (input) => {
    const row = input.closest('.kb-cart-row');
    const form = input.closest('form');
    if (!row || !form) return;

    let qty = parseInt(input.value || '0', 10);
    if (Number.isNaN(qty)) qty = 0;
    qty = Math.max(0, qty);
    input.value = qty;

    // optimistic UI
    updateRowSubtotal(row, qty);
    setRowStatus(row, 'Updating…');

    try {
      const data = await postQtyUpdate(form, qty);

      if (qty <= 0) {
        row.remove();
        updateSummary(data.totalItems, data.total);
        if (data.totalItems <= 0) location.reload();
        return;
      }

      updateSummary(data.totalItems, data.total);
      setRowStatus(row, 'Updated');
      setTimeout(() => setRowStatus(row, ''), 700);
    } catch (e) {
      setRowStatus(row, 'Refreshing…');
      form.submit(); // fallback
    }
  }, 300);

  // typing (works already)
  document.querySelectorAll('.js-qty-input').forEach(input => {
    input.addEventListener('input', () => sendUpdate(input));
    input.addEventListener('change', () => sendUpdate(input));
  });

  document.addEventListener('click', (e) => {
    const inc = e.target.closest('.js-qty-inc');
    const dec = e.target.closest('.js-qty-dec');
    if (!inc && !dec) return;

    const row = (inc || dec).closest('.kb-cart-row');
    if (!row) return;

    const input = row.querySelector('.js-qty-input');
    if (!input) return;

    const cur = parseInt(input.value || '0', 10) || 0;
    input.value = inc ? (cur + 1) : Math.max(0, cur - 1);

    sendUpdate(input);
  });
  

})();
</script>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const previewItems = document.getElementById('preview-items');
  const previewTotalItems = document.getElementById('preview-total-items');
  const previewTotalPrice = document.getElementById('preview-total-price');
  const cartCount = document.getElementById('cart-count');
  const container = document.querySelector('.kb-cart-container-button');

  if (!previewItems || !container) return;

  const CACHE_MS = 15000; // 15s cache (tweak)
  let cache = null;       // { data, ts }
  let inflight = null;    // Promise

  function money(symbol, value) {
    return `${symbol}${Number(value || 0).toFixed(2)}`;
  }

  function escapeHtml(str) {
    return String(str)
      .replaceAll('&', '&amp;')
      .replaceAll('<', '&lt;')
      .replaceAll('>', '&gt;')
      .replaceAll('"', '&quot;')
      .replaceAll("'", '&#039;');
  }

  function render(data) {
    const lines = data.lines || [];
    const symbol = data.symbol || '£';

    if (previewTotalItems) {
      const n = data.totalItems || 0;
      previewTotalItems.textContent = `${n} item${n === 1 ? '' : 's'}`;
    }
    if (previewTotalPrice) {
      previewTotalPrice.textContent = money(symbol, data.total || 0);
    }
    if (cartCount) cartCount.textContent = data.totalItems || 0;

    if (!lines.length) {
      previewItems.innerHTML = `<div style="padding:10px 0;color:#666;font-size:14px;">Your cart is empty.</div>`;
      return;
    }

    previewItems.innerHTML = lines.map(line => {
      const lineTotal = (line.price || 0) * (line.quantity || 0);
      const img = line.image
        ? `<img class="cart-preview-thumb" src="/${line.image}" alt="">`
        : `<div class="cart-preview-thumb"></div>`;

      return `
        <div class="cart-preview-item">
          ${img}
          <div class="cart-preview-meta">
            <p class="cart-preview-title">${escapeHtml(line.name || '')}</p>
            <div class="cart-preview-sub">
              Qty: ${line.quantity || 0} · ${money(symbol, lineTotal)}
            </div>
          </div>
        </div>
      `;
    }).join('');
  }

  async function fetchPreview() {
    // Serve from cache if fresh
    if (cache && (Date.now() - cache.ts) < CACHE_MS) return cache.data;

    // Deduplicate concurrent requests
    if (inflight) return inflight;

    inflight = fetch('/cart/preview', { headers: { 'Accept': 'application/json' } })
      .then(r => r.json())
      .then(data => {
        cache = { data, ts: Date.now() };
        return data;
      })
      .finally(() => {
        inflight = null;
      });

    return inflight;
  }

  async function ensurePreviewFast() {
    // If we already have cached data, render immediately (instant hover)
    if (cache && cache.data) render(cache.data);

    // Then refresh in background (if cache is stale, user still sees something instantly)
    try {
      const data = await fetchPreview();
      if (data && data.ok) render(data);
    } catch (e) {
      // keep whatever was rendered
    }
  }

  // 1) Prefetch ASAP after page is interactive (feels instant on hover)
  // Use requestIdleCallback where available so it doesn't block rendering
  if ('requestIdleCallback' in window) {
    requestIdleCallback(() => fetchPreview().catch(() => {}));
  } else {
    setTimeout(() => fetchPreview().catch(() => {}), 300);
  }

  // 2) On hover, render instantly from cache then refresh if needed
  container.addEventListener('mouseenter', ensurePreviewFast);

  // 3) Optional: if user changes tabs, force refresh next time
  document.addEventListener('visibilitychange', () => {
    if (!document.hidden) cache = null;
  });
});
</script>
<script>
    // ============================================
    // Product Search on contact us page
    // ============================================
document.addEventListener('DOMContentLoaded', () => {
  const AUTOCOMPLETE_URL = '/product-autocomplete';

  const input = document.getElementById('product_search');
  if (!input) {
    return;
  }

  const suggestions = document.getElementById('product_suggestions');
  const productIdEl = document.getElementById('product_id');
  const variantIdEl = document.getElementById('variant_id');

  if (!suggestions || !productIdEl || !variantIdEl) {
    console.warn('[autocomplete] Missing suggestion/hidden input elements', {
      suggestions,
      productIdEl,
      variantIdEl,
    });
    return;
  }

  const MIN_CHARS = 2;
  const DEBOUNCE_MS = 250;
  const MAX_ITEMS = 10;

  let timer = null;
  let lastQuery = '';
  let activeFetchController = null;

  function clearIds() {
    productIdEl.value = '';
    variantIdEl.value = '';
  }

  function hideSuggestions() {
    suggestions.style.display = 'none';
    suggestions.innerHTML = '';
  }

  function buildItemButton(item) {
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'list-group-item list-group-item-action';
    btn.textContent = item.label;

    btn.addEventListener('click', () => {
      input.value = item.label;
      productIdEl.value = item.product_id ?? '';
      variantIdEl.value = item.variant_id ?? '';
      hideSuggestions();
    });

    return btn;
  }

  function showSuggestions(items) {
    suggestions.innerHTML = '';

    if (!Array.isArray(items) || items.length === 0) {
      hideSuggestions();
      return;
    }

    items.slice(0, MAX_ITEMS).forEach((item) => {
      suggestions.appendChild(buildItemButton(item));
    });

    suggestions.style.display = 'block';
  }

  async function fetchSuggestions(q) {
    if (activeFetchController) {
      activeFetchController.abort();
    }
    activeFetchController = new AbortController();

    const url = `${AUTOCOMPLETE_URL}?q=${encodeURIComponent(q)}`;

    const res = await fetch(url, {
      method: 'GET',
      headers: { 'X-Requested-With': 'XMLHttpRequest' },
      signal: activeFetchController.signal,
    });

    if (!res.ok) {
      hideSuggestions();
      return;
    }

    const data = await res.json();
    showSuggestions(data);
  }

  function scheduleFetch() {
    const q = input.value.trim();

    clearIds();

    if (q.length < MIN_CHARS) {
      hideSuggestions();
      lastQuery = '';
      return;
    }

    if (q === lastQuery) return;
    lastQuery = q;

    if (timer) clearTimeout(timer);
    timer = setTimeout(() => {
      fetchSuggestions(q).catch((err) => {
        if (err && err.name === 'AbortError') return;
        console.error('[autocomplete] fetch error', err);
        hideSuggestions();
      });
    }, DEBOUNCE_MS);
  }

  input.addEventListener('input', scheduleFetch);

  input.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') hideSuggestions();
  });

  
  document.addEventListener('click', (e) => {
    if (e.target === input) return;
    if (suggestions.contains(e.target)) return;
    hideSuggestions();
  });

  
  input.addEventListener('focus', () => {
    const q = input.value.trim();
    if (q.length >= MIN_CHARS) scheduleFetch();
  });
});
</script>