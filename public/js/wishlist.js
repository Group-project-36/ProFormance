/**
 * wishlist.js
 *
 * Wishlist heart-toggle for product cards and the product detail page.
 * Calls the server-side toggle endpoint via AJAX.
 *
 * Usage: <button onclick="toggleWishlistCard(this)" data-variant-id="123">
 *
 * Note: The product detail page has its own toggleWishlist() in show.blade.php
 * which handles the extra "In Wishlist" text label. This file covers the
 * product card hearts on the listing/home pages.
 */

function toggleWishlistCard(btn) {
    const variantId = btn.dataset.variantId;
    const icon      = btn.querySelector('i');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

    fetch('/wishlist/toggle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify({ variant_id: variantId }),
    })
    .then(res => {
        if (res.status === 401) { window.location.href = '/login'; return; }
        return res.json();
    })
    .then(data => {
        if (!data) return;
        if (data.in_wishlist) {
            btn.classList.add('kb-wishlisted');
            icon.className = 'bi bi-heart-fill';
        } else {
            btn.classList.remove('kb-wishlisted');
            icon.className = 'bi bi-heart';
        }
    })
    .catch(err => console.error('Wishlist error:', err));
}
