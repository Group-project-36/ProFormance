document.addEventListener('DOMContentLoaded', () => {
  const checkoutData = document.getElementById('checkoutData');
  if (!checkoutData) return;

  const totalsUrl = checkoutData.dataset.totalsUrl;
  const symbol = checkoutData.dataset.symbol || '£';

  const countryInput = document.querySelector('[name="ship_country_code"]');

  function selectedCountryFromRadio() {
    const checked = document.querySelector('input[name="address_id"]:checked');
    return (checked?.dataset?.country || '').trim().toUpperCase();
  }

  function selectedCountry() {
    const radioCC = selectedCountryFromRadio();
    if (radioCC.length === 2) return radioCC;
    return (countryInput?.value || '').trim().toUpperCase();
  }

  // ✅ NEW: shipping method reader
  function selectedShippingMethod() {
    return document.querySelector('input[name="shipping_method"]:checked')?.value || 'standard';
  }

  async function refreshTotals() {
    const cc = selectedCountry();
    if (cc.length !== 2) return;

      updateShippingOptions(cc);

    // ✅ NEW: grab shipping method
    const method = selectedShippingMethod();

    const res = await fetch(totalsUrl, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
        'Accept': 'application/json'
      },
      // ✅ NEW: send shipping_method too
      body: JSON.stringify({ ship_country_code: cc, shipping_method: method })
    });

    if (!res.ok) return;
    const data = await res.json();
    if (!data.ok) return;

    const fmt = (p) => `${symbol}${(p / 100).toFixed(2)}`;

    const subtotalEl = document.getElementById('subtotalAmount');
    const vatLabelEl = document.getElementById('vatLabel');
    const vatEl      = document.getElementById('vatAmount');
    const totalEl    = document.getElementById('totalAmount');
    const shipEl     = document.getElementById('shippingAmount'); // ✅ NEW

    if (subtotalEl) subtotalEl.textContent = fmt(data.subtotalPenny);
    if (vatLabelEl) vatLabelEl.textContent = `VAT (${Math.round(data.vatRate * 100)}%)`;
    if (vatEl)      vatEl.textContent = fmt(data.taxPenny);
    if (shipEl)     shipEl.textContent = fmt(data.shippingPenny); // ✅ NEW
    if (totalEl)    totalEl.textContent = fmt(data.totalPenny);
  }

  // address radios
  document.querySelectorAll('input[name="address_id"]').forEach(radio => {
    radio.addEventListener('change', refreshTotals);
  });

  // manual country field
  countryInput?.addEventListener('input', refreshTotals);
  countryInput?.addEventListener('change', refreshTotals);

  // ✅ NEW: shipping method radios
  document.querySelectorAll('input[name="shipping_method"]').forEach(radio => {
    radio.addEventListener('change', refreshTotals);
  });

  function updateShippingOptions(countryCode) {
    const nextDayContainer = document.getElementById('nextDayOption');
    if (!nextDayContainer) return;

    const isGB = countryCode === 'GB';

    if (isGB) {
        nextDayContainer.style.display = '';
    } else {
        nextDayContainer.style.display = 'none';

        // If next day was selected, switch back to standard
        const nextDayRadio = nextDayContainer.querySelector('input');
        if (nextDayRadio?.checked) {
        const standardRadio = document.querySelector('input[name="shipping_method"][value="standard"]');
        if (standardRadio) standardRadio.checked = true;
        }
    }
    }


  refreshTotals();
});