/**
 * checkout.js
 *
 * Checkout page logic:
 *  - Address selection (saved vs manual)
 *  - Billing same-as-shipping toggle
 *  - Payment method toggle (card vs PayPal)
 *  - Card number formatting and expiry auto-slash
 *  - Live VAT/shipping/total recalculation via AJAX
 */

// ── Address / billing / payment toggles ──

function toggleManualAddress(radio) {
    const fields = document.getElementById('manual-address-fields');
    if (fields) fields.style.display = radio.value === '' ? 'block' : 'none';
}

function toggleBilling() {
    const checkbox = document.getElementById('billing-same-checkbox');
    const fields   = document.getElementById('billing-fields');
    if (fields) fields.style.display = checkbox?.checked ? 'none' : 'block';
}

function togglePayPal() {
    const selected     = document.querySelector('input[name="payment_method"]:checked')?.value;
    const cardFields   = document.getElementById('card-fields');
    const paypalNotice = document.getElementById('paypal-notice');

    if (cardFields)   cardFields.style.display   = selected === 'paypal' ? 'none' : 'block';
    if (paypalNotice) paypalNotice.style.display  = selected === 'paypal' ? 'flex' : 'none';
}

// ── Card formatting (simple auto-space and auto-slash) ──

document.addEventListener('DOMContentLoaded', () => {

    // Card number: add spaces every 4 digits
    document.querySelector('input[name="card_number"]')?.addEventListener('input', function () {
        let val = this.value.replace(/\s+/g, '').replace(/[^0-9]/g, '');
        this.value = val.match(/.{1,4}/g)?.join(' ') || val;
    });

    // Expiry: auto-insert slash after MM
    document.querySelector('input[name="card_expiry"]')?.addEventListener('input', function () {
        let val = this.value.replace(/\//g, '').replace(/[^0-9]/g, '');
        if (val.length >= 2) val = val.substring(0, 2) + '/' + val.substring(2);
        this.value = val;
    });


    // ── Live VAT / shipping / total recalculation ──

    const checkoutData = document.getElementById('checkoutData');
    if (!checkoutData) return;

    const totalsUrl  = checkoutData.dataset.totalsUrl;
    const symbol     = checkoutData.dataset.symbol || '£';
    const countryInput = document.querySelector('[name="ship_country_code"]');

    function selectedCountryFromRadio() {
        const checked = document.querySelector('input[name="address_id"]:checked');
        return (checked?.dataset?.country || '').trim().toUpperCase();
    }

    function selectedCountry() {
        const radioCC = selectedCountryFromRadio();
        return radioCC.length === 2 ? radioCC : (countryInput?.value || '').trim().toUpperCase();
    }

    function selectedShippingMethod() {
        return document.querySelector('input[name="shipping_method"]:checked')?.value || 'standard';
    }

    function updateShippingOptions(countryCode) {
        const nextDayContainer = document.getElementById('nextDayOption');
        if (!nextDayContainer) return;

        if (countryCode === 'GB') {
            nextDayContainer.style.display = '';
        } else {
            nextDayContainer.style.display = 'none';
            const nextDayRadio = nextDayContainer.querySelector('input');
            if (nextDayRadio?.checked) {
                const standard = document.querySelector('input[name="shipping_method"][value="standard"]');
                if (standard) standard.checked = true;
            }
        }
    }

    async function refreshTotals() {
        const cc = selectedCountry();
        if (cc.length !== 2) return;

        updateShippingOptions(cc);

        const res = await fetch(totalsUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ ship_country_code: cc, shipping_method: selectedShippingMethod() }),
        });

        if (!res.ok) return;
        const data = await res.json();
        if (!data.ok) return;

        const fmt = p => `${symbol}${(p / 100).toFixed(2)}`;

        const subtotalEl = document.getElementById('subtotalAmount');
        const vatLabelEl = document.getElementById('vatLabel');
        const vatEl      = document.getElementById('vatAmount');
        const shipEl     = document.getElementById('shippingAmount');
        const totalEl    = document.getElementById('totalAmount');

        if (subtotalEl) subtotalEl.textContent = fmt(data.subtotalPenny);
        if (vatLabelEl) vatLabelEl.textContent = `VAT (${Math.round(data.vatRate * 100)}%)`;
        if (vatEl)      vatEl.textContent      = fmt(data.taxPenny);
        if (shipEl)     shipEl.textContent      = fmt(data.shippingPenny);
        if (totalEl)    totalEl.textContent     = fmt(data.totalPenny);
    }

    // Bind events
    document.querySelectorAll('input[name="address_id"]').forEach(r => r.addEventListener('change', refreshTotals));
    countryInput?.addEventListener('input', refreshTotals);
    countryInput?.addEventListener('change', refreshTotals);
    document.querySelectorAll('input[name="shipping_method"]').forEach(r => r.addEventListener('change', refreshTotals));

    refreshTotals();
});
