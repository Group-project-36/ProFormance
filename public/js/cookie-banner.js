/**
 * cookie-banner.js
 *
 * Dismisses the educational/cookie notice banner via AJAX.
 */

document.addEventListener('DOMContentLoaded', () => {
    const banner = document.getElementById('eduBanner');
    const btn    = document.getElementById('eduBannerBtn');

    if (!banner || !btn) return;

    btn.addEventListener('click', async () => {
        try {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            await fetch('/accept-edu-notice', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token || '',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ accepted: true }),
            });

            banner.style.display = 'none';
        } catch {
            banner.style.display = 'none';
        }
    });
});
