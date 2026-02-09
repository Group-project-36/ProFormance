<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Checkout – ProFormance</title>

  <link href="https://fonts.googleapis.com/css2?family=Anton&family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">

  <style>
    :root{
      --bg:#000;
      --panel:#111;
      --panel2:#0d0d0d;
      --border:#2a2a2a;
      --border2:#3a3a3a;
      --text:#fff;
      --muted:#cfcfcf;
      --gold:#FFD700;
      --gold2:#c9a400;
      --danger:#ff5a5a;
      --ok:#57d163;
      --shadow: 0 0 18px rgba(255,215,0,0.12);
      --radius: 12px;
    }

    *{ box-sizing:border-box; }
    body{
      background: var(--bg);
      color: var(--text);
      font-family: 'Roboto', sans-serif;
      margin:0;
      padding:0;
      line-height: 1.4;
    }

    .container{
      width: min(980px, 92%);
      margin: 0 auto;
      padding: 28px 0 60px;
    }

    h1{
      font-family: 'Anton', sans-serif;
      font-size: clamp(34px, 5vw, 56px);
      text-transform: uppercase;
      color: var(--gold);
      margin: 10px 0 22px;
      letter-spacing: 0.5px;
    }

    h2{
      font-family: 'Anton', sans-serif;
      color: var(--gold);
      margin: 0 0 14px;
      letter-spacing: 0.3px;
      font-size: 28px;
    }

    .checkout-section{
      background: var(--panel);
      padding: 22px;
      margin-bottom: 18px;
      border-radius: var(--radius);
      border: 1px solid var(--border);
      box-shadow: var(--shadow);
    }

    .grid{
      display:grid;
      grid-template-columns: 1fr 1fr;
      gap: 14px 16px;
    }
    @media (max-width: 760px){
      .grid{ grid-template-columns: 1fr; }
    }

    label{
      font-weight: 700;
      display:block;
      color: var(--gold);
      font-size: 14px;
    }

    .field{
      display:flex;
      flex-direction:column;
      gap:6px;
    }

    input, select{
      width: 100%;
      padding: 12px 12px;
      background: #1c1c1c;
      border: 1px solid var(--border2);
      color: var(--text);
      border-radius: 10px;
      outline:none;
      transition: border-color .15s ease, box-shadow .15s ease;
    }
    input::placeholder{ color:#8a8a8a; }

    input:focus, select:focus{
      border-color: var(--gold);
      box-shadow: 0 0 0 3px rgba(255,215,0,0.18);
    }

    .help{
      margin: 6px 0 0;
      color: var(--muted);
      font-size: 13px;
    }

    .divider{
      height: 1px;
      background: var(--border);
      margin: 16px 0;
    }

    /* Payment */
    .pay-methods{
      display:grid;
      grid-template-columns: 1fr 1fr;
      gap: 10px;
      margin-top: 8px;
    }
    @media (max-width: 760px){
      .pay-methods{ grid-template-columns: 1fr; }
    }

    .pay-pill{
      display:flex;
      align-items:center;
      gap: 10px;
      padding: 12px;
      border: 1px solid var(--border2);
      border-radius: 12px;
      background: #141414;
      cursor:pointer;
      user-select:none;
      transition: border-color .15s ease, transform .05s ease;
    }
    .pay-pill:hover{ border-color: var(--gold); }
    .pay-pill:active{ transform: scale(0.995); }
    .pay-pill input{ width:auto; }

    .tag{
      font-size: 12px;
      color:#000;
      background: var(--gold);
      padding: 2px 8px;
      border-radius: 999px;
      font-weight: 800;
      margin-left:auto;
      white-space: nowrap;
    }

    .pay-box{
      margin-top: 14px;
      background: var(--panel2);
      border: 1px solid var(--border);
      border-radius: 12px;
      padding: 14px;
    }

    .hidden{ display:none; }

    /* Summary */
    .order-summary{
      background: var(--panel2);
      padding: 16px;
      border-radius: 12px;
      border: 1px solid var(--border);
    }

    .summary-item{
      display:flex;
      justify-content: space-between;
      padding: 10px 0;
      border-bottom: 1px solid var(--border);
      gap: 12px;
    }
    .summary-item:last-child{ border-bottom:none; }

    .total{
      font-size: 20px;
      font-weight: 800;
      color: var(--gold);
      margin-top: 12px;
      text-align:right;
    }

    .actions{
      display:flex;
      gap: 12px;
      margin-top: 14px;
      flex-wrap: wrap;
    }

    button{
      flex: 1 1 220px;
      padding: 14px 16px;
      background: var(--gold);
      color: #000;
      border-radius: 12px;
      border: none;
      font-weight: 900;
      font-size: 16px;
      text-transform: uppercase;
      cursor: pointer;
      transition: background .15s ease, transform .05s ease;
    }
    button:hover{ background: var(--gold2); }
    button:active{ transform: scale(0.995); }

    .secondary{
      background: transparent;
      border: 1px solid var(--border2);
      color: var(--text);
      text-transform:none;
      font-weight: 700;
    }
    .secondary:hover{
      border-color: var(--gold);
      background: rgba(255,215,0,0.06);
    }

    .error{
      color: var(--danger);
      font-size: 13px;
      margin-top: 8px;
      display:none;
    }
    .success{
      color: var(--ok);
      font-size: 14px;
      margin-top: 10px;
      display:none;
      font-weight: 700;
    }

    .required::after{
      content:" *";
      color: var(--danger);
      font-weight: 900;
    }
  </style>
</head>

<body>
  <div class="container">
    <h1>Checkout</h1>

    <form id="checkoutForm" novalidate>
      <!-- CUSTOMER INFORMATION -->
      <div class="checkout-section">
        <h2>Customer Details</h2>

        <div class="grid">
          <div class="field">
            <label class="required" for="fullName">Full Name</label>
            <input id="fullName" name="fullName" type="text" placeholder="Your full name" autocomplete="name" required>
          </div>

          <div class="field">
            <label class="required" for="email">Email</label>
            <input id="email" name="email" type="email" placeholder="name@email.com" autocomplete="email" required>
          </div>

          <div class="field">
            <label class="required" for="phone">Phone</label>
            <input id="phone" name="phone" type="tel" placeholder="07..." autocomplete="tel" required>
          </div>
        </div>

        <div class="divider"></div>

        <div class="grid">
          <div class="field">
            <label class="required" for="address1">Address</label>
            <input id="address1" name="address1" type="text" placeholder="Street address" autocomplete="address-line1" required>
          </div>

          <div class="field">
            <label class="required" for="city">City</label>
            <input id="city" name="city" type="text" placeholder="City" autocomplete="address-level2" required>
          </div>

          <div class="field">
            <label class="required" for="postcode">Postcode</label>
            <input id="postcode" name="postcode" type="text" placeholder="e.g. E20 2ST" autocomplete="postal-code" required>
          </div>
        </div>
      </div>

      <!-- DELIVERY METHOD -->
      <div class="checkout-section">
        <h2>Delivery</h2>

        <div class="field">
          <label class="required" for="delivery">Delivery option</label>
          <select id="delivery" name="delivery" required>
            <option value="">Choose…</option>
            <option value="standard">Standard (3–5 days) – £3.99</option>
            <option value="express">Express (1–2 days) – £6.99</option>
            <option value="collection">Collect in store – £0.00</option>
          </select>
          <p class="help">No real orders/payments are taken on this demo page.</p>
        </div>
      </div>

      <!-- PAYMENT SECTION -->
      <div class="checkout-section">
        <h2>Payment</h2>
        <p class="help" style="margin-top:0;">Choose how you want to pay (demo UI only).</p>

        <div class="pay-methods" role="radiogroup" aria-label="Payment methods">
          <label class="pay-pill">
            <input type="radio" name="paymentMethod" value="card" checked>
            <span>Card</span>
            <span class="tag">Popular</span>
          </label>

          <label class="pay-pill">
            <input type="radio" name="paymentMethod" value="paypal">
            <span>PayPal</span>
          </label>

          <label class="pay-pill">
            <input type="radio" name="paymentMethod" value="applepay">
            <span>Apple Pay</span>
          </label>

          <label class="pay-pill">
            <input type="radio" name="paymentMethod" value="googlepay">
            <span>Google Pay</span>
          </label>

          <label class="pay-pill">
            <input type="radio" name="paymentMethod" value="paylater">
            <span>Pay later</span>
          </label>
        </div>

        <div class="pay-box">
          <div id="payCard">
            <div class="grid">
              <div class="field" style="grid-column: 1 / -1;">
                <label class="required" for="cardName">Name on card</label>
                <input id="cardName" name="cardName" type="text" placeholder="Name on the card" autocomplete="cc-name" required>
              </div>

              <div class="field" style="grid-column: 1 / -1;">
                <label class="required" for="cardNumber">Card number</label>
                <input id="cardNumber" name="cardNumber" inputmode="numeric" maxlength="19"
                       placeholder="1234 5678 9012 3456" autocomplete="cc-number" required>
              </div>

              <div class="field">
                <label class="required" for="exp">Expiry</label>
                <input id="exp" name="exp" inputmode="numeric" maxlength="5" placeholder="MM/YY" autocomplete="cc-exp" required>
              </div>

              <div class="field">
                <label class="required" for="cvv">CVV</label>
                <input id="cvv" name="cvv" inputmode="numeric" maxlength="4" placeholder="123" autocomplete="cc-csc" required>
              </div>
            </div>
          </div>

          <div id="payPaypal" class="hidden">
            <p class="help" style="margin:0;">In a real checkout you’d be sent to PayPal to approve the payment.</p>
          </div>

          <div id="payApple" class="hidden">
            <p class="help" style="margin:0;">Apple Pay button would show here when available.</p>
          </div>

          <div id="payGoogle" class="hidden">
            <p class="help" style="margin:0;">Google Pay button would show here when available.</p>
          </div>

          <div id="payLater" class="hidden">
            <p class="help" style="margin:0;">Pay-later providers usually run a quick eligibility check.</p>
          </div>
        </div>

        <div id="paymentError" class="error">Fill in the card details to continue.</div>
      </div>

      <!-- ORDER SUMMARY -->
      <div class="checkout-section">
        <h2>Order Summary</h2>

        <div class="order-summary" aria-live="polite">
          <div class="summary-item">
            <span>Gym Gloves</span>
            <span>£14.99</span>
          </div>
          <div class="summary-item">
            <span>Protein Shaker</span>
            <span>£9.99</span>
          </div>
          <div class="summary-item">
            <span>Creatine Monohydrate</span>
            <span>£19.99</span>
          </div>

          <div class="summary-item">
            <span>Delivery</span>
            <span id="deliveryCost">£0.00</span>
          </div>

          <div class="total" id="grandTotal">Total: £44.97</div>
        </div>

        <div class="actions">
          <button type="submit">Place Order</button>
          <button type="button" class="secondary" id="resetBtn">Reset</button>
        </div>

        <div id="successMsg" class="success">Done — demo order submitted. (Check console)</div>
      </div>
    </form>
  </div>

  <script>
    function formatGBP(n) {
      return "£" + n.toFixed(2);
    }

    const basketTotal = 44.97;

    const deliverySelect = document.getElementById('delivery');
    const deliveryCostEl = document.getElementById('deliveryCost');
    const totalEl = document.getElementById('grandTotal');

    const paymentError = document.getElementById('paymentError');
    const successMsg = document.getElementById('successMsg');

    const paymentViews = {
      card: document.getElementById('payCard'),
      paypal: document.getElementById('payPaypal'),
      applepay: document.getElementById('payApple'),
      googlepay: document.getElementById('payGoogle'),
      paylater: document.getElementById('payLater')
    };

    function deliveryPrice() {
      if (deliverySelect.value === 'standard') return 3.99;
      if (deliverySelect.value === 'express') return 6.99;
      return 0.00;
    }

    function refreshTotal() {
      const d = deliveryPrice();
      deliveryCostEl.textContent = formatGBP(d);
      totalEl.textContent = "Total: " + formatGBP(basketTotal + d);
    }

    function switchPaymentUI(method) {
      Object.keys(paymentViews).forEach(k => paymentViews[k].classList.add('hidden'));
      paymentViews[method].classList.remove('hidden');

      const needsCard = (method === 'card');
      ['cardName','cardNumber','exp','cvv'].forEach(id => {
        const el = document.getElementById(id);
        if (needsCard) el.setAttribute('required', 'required');
        else el.removeAttribute('required');
      });
    }

    const cardNumber = document.getElementById('cardNumber');
    cardNumber.addEventListener('input', () => {
      cardNumber.value = cardNumber.value
        .replace(/[^\d]/g,'')
        .slice(0,16)
        .replace(/(\d{4})(?=\d)/g,'$1 ');
    });

    const exp = document.getElementById('exp');
    exp.addEventListener('input', () => {
      let v = exp.value.replace(/[^\d]/g,'').slice(0,4);
      if (v.length > 2) v = v.slice(0,2) + '/' + v.slice(2);
      exp.value = v;
    });

    const cvv = document.getElementById('cvv');
    cvv.addEventListener('input', () => {
      cvv.value = cvv.value.replace(/[^\d]/g,'').slice(0,4);
    });

    document.querySelectorAll('input[name="paymentMethod"]').forEach(r => {
      r.addEventListener('change', (e) => {
        paymentError.style.display = "none";
        switchPaymentUI(e.target.value);
      });
    });

    deliverySelect.addEventListener('change', refreshTotal);
    refreshTotal();

    document.getElementById('checkoutForm').addEventListener('submit', (e) => {
      e.preventDefault();

      successMsg.style.display = "none";
      paymentError.style.display = "none";

      const form = e.currentTarget;
      const method = form.paymentMethod.value;

      if (!form.checkValidity()) {
        form.reportValidity();
        if (method === 'card') paymentError.style.display = "block";
        return;
      }

      if (method === 'card') {
        const digits = cardNumber.value.replace(/\s/g,'');
        if (digits.length !== 16 || exp.value.length !== 5 || cvv.value.length < 3) {
          paymentError.style.display = "block";
          return;
        }
      }

      const data = Object.fromEntries(new FormData(form).entries());
      data.delivery = deliverySelect.value;
      data.deliveryCost = deliveryPrice();
      data.total = (basketTotal + data.deliveryCost).toFixed(2);

      console.log("Checkout (demo):", data);

      successMsg.style.display = "block";
      form.reset();

      document.querySelector('input[name="paymentMethod"][value="card"]').checked = true;
      switchPaymentUI('card');
      refreshTotal();
    });

    document.getElementById('resetBtn').addEventListener('click', () => {
      const form = document.getElementById('checkoutForm');
      form.reset();
      paymentError.style.display = "none";
      successMsg.style.display = "none";
      document.querySelector('input[name="paymentMethod"][value="card"]').checked = true;
      switchPaymentUI('card');
      refreshTotal();
    });

    switchPaymentUI('card');
  </script>
</body>
</html>
