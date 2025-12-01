<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout – ProFormance</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">

    <style>
        body {
            background: #000;
            color: #fff;
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 85%;
            margin: auto;
            padding: 30px;
        }

        h1 {
            font-family: 'Anton';
            font-size: 50px;
            text-transform: uppercase;
            color: #FFD700;
            margin-bottom: 20px;
        }

        h2 {
            font-family: 'Anton';
            color: #FFD700;
            margin-bottom: 10px;
        }

        .checkout-section {
            background: #111;
            padding: 25px;
            margin-bottom: 30px;
            border-radius: 10px;
            border: 1px solid #333;
            box-shadow: 0 0 15px rgba(255,215,0,0.15);
        }

        label {
            font-weight: bold;
            margin-top: 10px;
            display: block;
            color: #FFD700;
        }

        input, select {
            width: 100%;
            padding: 12px;
            margin: 8px 0 15px;
            background: #222;
            border: 1px solid #444;
            color: white;
            border-radius: 6px;
        }

        .order-summary {
            background: #0d0d0d;
            padding: 20px;
            border-radius: 10px;
            border: 1px solid #333;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #333;
        }

        .summary-item:last-child {
            border-bottom: none;
        }

        .total {
            font-size: 22px;
            font-weight: bold;
            color: #FFD700;
            margin-top: 15px;
            text-align: right;
        }

        button {
            width: 100%;
            padding: 15px;
            background: #FFD700;
            color: #000;
            border-radius: 6px;
            border: none;
            font-weight: bold;
            font-size: 18px;
            text-transform: uppercase;
            cursor: pointer;
            margin-top: 20px;
        }

        button:hover {
            background: #c9a400;
        }
    </style>
</head>

<body>

<div class="container">

    <h1>Checkout</h1>

    <!-- CUSTOMER INFORMATION -->
    <div class="checkout-section">
        <h2>Customer Details</h2>

        <label>Full Name *</label>
        <input type="text" placeholder="Enter your full name" required>

        <label>Email Address *</label>
        <input type="email" placeholder="Enter your email" required>

        <label>Phone Number *</label>
        <input type="text" placeholder="Enter your phone number" required>

        <label>Shipping Address *</label>
        <input type="text" placeholder="Street Address" required>
        <input type="text" placeholder="City" required>
        <input type="text" placeholder="Postcode" required>
    </div>

    <!-- DELIVERY METHOD -->
    <div class="checkout-section">
        <h2>Delivery Method</h2>

        <label>Select Delivery Option *</label>
        <select required>
            <option value="">Choose a delivery option</option>
            <option>Standard Delivery (3–5 Days) – £3.99</option>
            <option>Express Delivery (1–2 Days) – £6.99</option>
            <option>Free Collection from Store</option>
        </select>
    </div>

    <!-- PAYMENT SECTION -->
    <div class="checkout-section">
        <h2>Payment Method</h2>

        <label>Select Payment Option *</label>
        <select required>
            <option value="">Choose a payment method</option>
            <option>Credit/Debit Card</option>
            <option>PayPal</option>
            <option>Apple Pay</option>
        </select>

        <p style="color:#ccc;">(This is a placeholder section for MVP — no real payments are processed.)</p>
    </div>

    <!-- ORDER SUMMARY -->
    <div class="checkout-section">
        <h2>Order Summary</h2>

        <div class="order-summary">
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

            <div class="total">Total: £44.97</div>
        </div>

        <button>Place Order</button>
    </div>

</div>

</body>
</html>
