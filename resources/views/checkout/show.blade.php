{{-- resources/views/checkout/show.blade.php --}}
@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/checkout.css') }}">
@endsection

@section('content')
@if($errors->any())
    <div style="background:red;color:white;padding:20px;">
        @foreach($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif
<div class="kb-checkout-page">
    <div class="kb-checkout-container">

        {{-- LEFT COLUMN: Forms --}}
        <div class="kb-checkout-forms">
            <h1 class="kb-checkout-heading">Checkout</h1>

            <form method="POST" action="{{ route('checkout.store') }}" id="checkout-form">
                @csrf

                {{-- ============ SECTION 1: SHIPPING ============ --}}
                <div class="kb-checkout-section">
                    <div class="kb-checkout-section-header">
                        <span class="kb-checkout-step-num">1</span>
                        <h2 class="kb-checkout-section-title">Shipping Address</h2>
                    </div>

                    @if($addresses->isNotEmpty())
                        <div class="kb-checkout-saved-addresses">
                            <p class="kb-checkout-hint">Select a saved address or enter a new one below.</p>

                            <div class="kb-checkout-address-options">
                                @foreach($addresses as $address)
                                    <label class="kb-checkout-address-card">
                                        <input type="radio" name="address_id" value="{{ $address->address_id }}"
                                               {{ $address->is_default_shipping_address ? 'checked' : '' }}
                                               onchange="toggleManualAddress(this)">
                                        <div class="kb-checkout-address-card-body">
                                            @if($address->is_default_shipping_address)
                                                <span class="kb-badge kb-badge-shipping">Default</span>
                                            @endif
                                            <strong>{{ $address->recipient_name }}</strong>
                                            <span>{{ $address->house_number }} {{ $address->address_line_one }}</span>
                                            <span>{{ $address->city }}, {{ $address->postcode }}</span>
                                        </div>
                                    </label>
                                @endforeach

                                <label class="kb-checkout-address-card kb-checkout-address-new">
                                    <input type="radio" name="address_id" value=""
                                           {{ $addresses->where('is_default_shipping_address', true)->isEmpty() ? 'checked' : '' }}
                                           onchange="toggleManualAddress(this)">
                                    <div class="kb-checkout-address-card-body">
                                        <i class="bi bi-plus-circle"></i>
                                        <strong>New Address</strong>
                                    </div>
                                </label>
                            </div>
                        </div>
                    @endif

                    {{-- Manual address fields --}}
                    <div id="manual-address-fields" class="kb-checkout-manual-fields"
                         style="{{ $addresses->isNotEmpty() && $addresses->where('is_default_shipping_address', true)->isNotEmpty() ? 'display:none;' : '' }}">

                        <div class="kb-form-group">
                            <label class="kb-form-label">Full Name</label>
                            <input type="text" name="ship_recipient_name" class="kb-form-input"
                                   value="{{ old('ship_recipient_name', $user->first_name . ' ' . $user->last_name) }}">
                            @error('ship_recipient_name') <div class="kb-form-error">{{ $message }}</div> @enderror
                        </div>

                        <div class="kb-form-row">
                            <div class="kb-form-group kb-form-group-small">
                                <label class="kb-form-label">House No.</label>
                                <input type="text" name="ship_house_number" class="kb-form-input"
                                       value="{{ old('ship_house_number') }}">
                            </div>
                            <div class="kb-form-group">
                                <label class="kb-form-label">Address Line 1</label>
                                <input type="text" name="ship_address_line_one" class="kb-form-input"
                                       value="{{ old('ship_address_line_one') }}">
                                @error('ship_address_line_one') <div class="kb-form-error">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="kb-form-group">
                            <label class="kb-form-label">Address Line 2 <span class="kb-form-optional">(optional)</span></label>
                            <input type="text" name="ship_address_line_two" class="kb-form-input"
                                   value="{{ old('ship_address_line_two') }}">
                        </div>

                        <div class="kb-form-row">
                            <div class="kb-form-group">
                                <label class="kb-form-label">City</label>
                                <input type="text" name="ship_city" class="kb-form-input"
                                       value="{{ old('ship_city') }}">
                                @error('ship_city') <div class="kb-form-error">{{ $message }}</div> @enderror
                            </div>
                            <div class="kb-form-group">
                                <label class="kb-form-label">County <span class="kb-form-optional">(optional)</span></label>
                                <input type="text" name="ship_county" class="kb-form-input"
                                       value="{{ old('ship_county') }}">
                            </div>
                        </div>

                        <div class="kb-form-row">
                            <div class="kb-form-group">
                                <label class="kb-form-label">Postcode</label>
                                <input type="text" name="ship_postcode" class="kb-form-input"
                                       value="{{ old('ship_postcode') }}">
                                @error('ship_postcode') <div class="kb-form-error">{{ $message }}</div> @enderror
                            </div>
                            <div class="kb-form-group">
                                <label class="kb-form-label">Country Code</label>
                                <input type="text" name="ship_country_code" class="kb-form-input"
                                       value="{{ old('ship_country_code', 'GB') }}" maxlength="2">
                            </div>
                        </div>

                        <div class="kb-form-group">
                            <label class="kb-form-label">Phone Number <span class="kb-form-optional">(optional)</span></label>
                            <input type="text" name="ship_phone_number" class="kb-form-input"
                                   value="{{ old('ship_phone_number') }}">
                        </div>
                    </div>
                </div>

                {{-- ============ SECTION 2: BILLING ============ --}}
                <div class="kb-checkout-section">
                    <div class="kb-checkout-section-header">
                        <span class="kb-checkout-step-num">2</span>
                        <h2 class="kb-checkout-section-title">Billing Address</h2>
                    </div>

                    <label class="kb-checkbox-label kb-checkout-same-billing">
                        <input type="checkbox" name="billing_same" value="1" id="billing-same-checkbox"
                               checked onchange="toggleBilling()">
                        <span>Same as shipping address</span>
                    </label>

                    <div id="billing-fields" class="kb-checkout-manual-fields" style="display:none;">
                        <div class="kb-form-group">
                            <label class="kb-form-label">Full Name</label>
                            <input type="text" name="bill_recipient_name" class="kb-form-input"
                                   value="{{ old('bill_recipient_name') }}">
                        </div>

                        <div class="kb-form-row">
                            <div class="kb-form-group kb-form-group-small">
                                <label class="kb-form-label">House No.</label>
                                <input type="text" name="bill_house_number" class="kb-form-input"
                                       value="{{ old('bill_house_number') }}">
                            </div>
                            <div class="kb-form-group">
                                <label class="kb-form-label">Address Line 1</label>
                                <input type="text" name="bill_address_line_one" class="kb-form-input"
                                       value="{{ old('bill_address_line_one') }}">
                            </div>
                        </div>

                        <div class="kb-form-group">
                            <label class="kb-form-label">Address Line 2 <span class="kb-form-optional">(optional)</span></label>
                            <input type="text" name="bill_address_line_two" class="kb-form-input"
                                   value="{{ old('bill_address_line_two') }}">
                        </div>

                        <div class="kb-form-row">
                            <div class="kb-form-group">
                                <label class="kb-form-label">City</label>
                                <input type="text" name="bill_city" class="kb-form-input"
                                       value="{{ old('bill_city') }}">
                            </div>
                            <div class="kb-form-group">
                                <label class="kb-form-label">County <span class="kb-form-optional">(optional)</span></label>
                                <input type="text" name="bill_county" class="kb-form-input"
                                       value="{{ old('bill_county') }}">
                            </div>
                        </div>

                        <div class="kb-form-row">
                            <div class="kb-form-group">
                                <label class="kb-form-label">Postcode</label>
                                <input type="text" name="bill_postcode" class="kb-form-input"
                                       value="{{ old('bill_postcode') }}">
                            </div>
                            <div class="kb-form-group">
                                <label class="kb-form-label">Country Code</label>
                                <input type="text" name="bill_country_code" class="kb-form-input"
                                       value="{{ old('bill_country_code', 'GB') }}" maxlength="2">
                            </div>
                        </div>

                        <div class="kb-form-group">
                            <label class="kb-form-label">Phone Number <span class="kb-form-optional">(optional)</span></label>
                            <input type="text" name="bill_phone_number" class="kb-form-input"
                                   value="{{ old('bill_phone_number') }}">
                        </div>
                    </div>
                </div>

                {{-- ============ SECTION 3: PAYMENT ============ --}}
                <div class="kb-checkout-section">
                    <div class="kb-checkout-section-header">
                        <span class="kb-checkout-step-num">3</span>
                        <h2 class="kb-checkout-section-title">Payment Method</h2>
                    </div>

                    <div class="kb-checkout-payment-methods">
                        <label class="kb-checkout-payment-option">
                            <input type="radio" name="payment_method" value="visa" checked
                                   onchange="togglePayPal()">
                            <div class="kb-checkout-payment-label">
                                <img src="{{ asset('images/payments/visa.svg') }}" alt="Visa" class="kb-payment-icon">
                                <span>Visa</span>
                            </div>
                        </label>
                        <label class="kb-checkout-payment-option">
                            <input type="radio" name="payment_method" value="mastercard"
                                   onchange="togglePayPal()">
                            <div class="kb-checkout-payment-label">
                                <img src="{{ asset('images/payments/mastercard.svg') }}" alt="Mastercard" class="kb-payment-icon">
                                <span>Mastercard</span>
                            </div>
                        </label>
                        <label class="kb-checkout-payment-option">
                            <input type="radio" name="payment_method" value="amex"
                                   onchange="togglePayPal()">
                            <div class="kb-checkout-payment-label">
                                <img src="{{ asset('images/payments/amex.svg') }}" alt="Amex" class="kb-payment-icon">
                                <span>Amex</span>
                            </div>
                        </label>
                        <label class="kb-checkout-payment-option">
                            <input type="radio" name="payment_method" value="paypal"
                                   onchange="togglePayPal()">
                            <div class="kb-checkout-payment-label">
                                <img src="{{ asset('images/payments/paypal.svg') }}" alt="PayPal" class="kb-payment-icon">
                                <span hidden>PayPal</span>
                            </div>
                        </label>
                    </div>

                    @error('payment_method') <div class="kb-form-error">{{ $message }}</div> @enderror

                    {{-- Card fields (hidden for PayPal) --}}
                    <div id="card-fields" class="kb-checkout-card-fields">
                        <div class="kb-form-group">
                            <label class="kb-form-label">Name on Card</label>
                            <input type="text" name="card_name" class="kb-form-input"
                                   value="{{ old('card_name', $user->first_name . ' ' . $user->last_name) }}"
                                   placeholder="John Smith">
                            @error('card_name') <div class="kb-form-error">{{ $message }}</div> @enderror
                        </div>

                        <div class="kb-form-group">
                            <label class="kb-form-label">Card Number</label>
                            <input type="text" name="card_number" class="kb-form-input"
                                   value="{{ old('card_number') }}"
                                   placeholder="1234 5678 9012 3456" maxlength="19">
                            @error('card_number') <div class="kb-form-error">{{ $message }}</div> @enderror
                        </div>

                        <div class="kb-form-row">
                            <div class="kb-form-group">
                                <label class="kb-form-label">Expiry Date</label>
                                <input type="text" name="card_expiry" class="kb-form-input"
                                       value="{{ old('card_expiry') }}"
                                       placeholder="MM/YY" maxlength="5">
                                @error('card_expiry') <div class="kb-form-error">{{ $message }}</div> @enderror
                            </div>
                            <div class="kb-form-group">
                                <label class="kb-form-label">CVV</label>
                                <input type="text" name="card_cvv" class="kb-form-input"
                                       value="{{ old('card_cvv') }}"
                                       placeholder="123" maxlength="4">
                                @error('card_cvv') <div class="kb-form-error">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="kb-checkout-simulated-notice">
                            <i class="bi bi-shield-check"></i>
                            <span>This is a simulated payment — no real charges will be made.</span>
                        </div>
                    </div>

                    {{-- PayPal notice --}}
                    <div id="paypal-notice" style="display:none;" class="kb-checkout-simulated-notice">
                        <i class="bi bi-shield-check"></i>
                        <span>You would be redirected to PayPal to complete your payment. (Simulated — no real transaction.)</span>
                    </div>
                </div>

                {{-- ============ SECTION 4: ORDER NOTES ============ --}}
                <div class="kb-checkout-section">
                    <div class="kb-checkout-section-header">
                        <span class="kb-checkout-step-num">4</span>
                        <h2 class="kb-checkout-section-title">Order Notes</h2>
                    </div>

                    <div class="kb-form-group">
                        <label class="kb-form-label">Delivery instructions or special requests <span class="kb-form-optional">(optional)</span></label>
                        <textarea name="order_notes" class="kb-form-input kb-form-textarea" rows="3"
                                  placeholder="e.g. Leave at the front door, ring doorbell twice...">{{ old('order_notes') }}</textarea>
                    </div>
                </div>

                {{-- SUBMIT (mobile only — desktop uses sidebar button) --}}
                <div class="kb-checkout-submit-mobile">
                    <button type="submit" class="kb-account-btn kb-account-btn-primary kb-checkout-place-order-btn">
                        <i class="bi bi-lock-fill"></i> Place Order — {{ $symbol }}{{ number_format($grandTotal, 2) }}
                    </button>
                </div>
            </form>
        </div>

        {{-- RIGHT COLUMN: Order Summary --}}
        <aside class="kb-checkout-sidebar">
            <div class="kb-checkout-sidebar-sticky">
                <div class="kb-checkout-summary-card">
                    <h2 class="kb-checkout-summary-title">Order Summary</h2>

                    <div class="kb-checkout-items">
                        @foreach($lines as $line)
                            <div class="kb-checkout-item">
                                @if(!empty($line['image']))
                                    <img src="{{ asset($line['image']) }}" alt="{{ $line['name'] }}"
                                         class="kb-checkout-item-img">
                                @else
                                    <div class="kb-checkout-item-img kb-checkout-item-img-placeholder">
                                        <i class="bi bi-image"></i>
                                    </div>
                                @endif
                                <div class="kb-checkout-item-info">
                                    <span class="kb-checkout-item-name">{{ $line['name'] }}</span>
                                    <span class="kb-checkout-item-qty">Qty: {{ $line['quantity'] }}</span>
                                </div>
                                <span class="kb-checkout-item-price">
                                    {{ $symbol }}{{ number_format($line['price'] * $line['quantity'], 2) }}
                                </span>
                            </div>
                        @endforeach
                    </div>

                    {{-- DISCOUNT CODE --}}
                    <div class="kb-checkout-discount-section">
                        @if($appliedDiscount)
                            <div class="kb-checkout-discount-applied">
                                <div class="kb-checkout-discount-info">
                                    <i class="bi bi-tag-fill"></i>
                                    <span><strong>{{ $appliedDiscount['discount_code'] }}</strong></span>
                                    <span class="kb-checkout-discount-amount">
                                        -{{ $symbol }}{{ number_format($discountAmount, 2) }}
                                    </span>
                                </div>
                                <form method="POST" action="{{ route('checkout.discount.remove') }}">
                                    @csrf
                                    <button type="submit" class="kb-checkout-discount-remove" title="Remove">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </form>
                            </div>
                        @else
                            <form method="POST" action="{{ route('checkout.discount.apply') }}" class="kb-checkout-discount-form">
                                @csrf
                                <input type="text" name="discount_code" class="kb-form-input"
                                       placeholder="Discount code" value="{{ old('discount_code') }}">
                                <button type="submit" class="kb-account-btn kb-account-btn-outline">Apply</button>
                            </form>
                            @error('discount_code') <div class="kb-form-error">{{ $message }}</div> @enderror
                        @endif
                    </div>

                    {{-- TOTALS --}}
                    <div class="kb-checkout-totals">
                        <div class="kb-checkout-total-row">
                            <span>Subtotal</span>
                            <span>{{ $symbol }}{{ number_format($total, 2) }}</span>
                        </div>

                        @if($discountAmount > 0)
                            <div class="kb-checkout-total-row kb-checkout-discount-row">
                                <span>Discount</span>
                                <span>-{{ $symbol }}{{ number_format($discountAmount, 2) }}</span>
                            </div>
                        @endif

                        <div class="kb-checkout-total-row">
                            <span>Shipping</span>
                            <span>Free</span>
                        </div>

                        <div class="kb-checkout-total-row">
                            <span>Tax</span>
                            <span>{{ $symbol }}0.00</span>
                        </div>

                        <div class="kb-checkout-total-row kb-checkout-grand-total">
                            <strong>Total</strong>
                            <strong>{{ $symbol }}{{ number_format($grandTotal, 2) }}</strong>
                        </div>
                    </div>

                    <button type="submit" form="checkout-form"
                            class="kb-account-btn kb-account-btn-primary kb-checkout-place-order-btn">
                        <i class="bi bi-lock-fill"></i> Place Order
                    </button>

                    <div class="kb-checkout-trust-badges">
                        <div class="kb-checkout-trust-item">
                            <i class="bi bi-shield-lock-fill"></i>
                            <span>Secure Checkout</span>
                        </div>
                        <div class="kb-checkout-trust-item">
                            <i class="bi bi-arrow-return-left"></i>
                            <span>Free Returns</span>
                        </div>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function toggleManualAddress(radio) {
        const fields = document.getElementById('manual-address-fields');
        if (radio.value === '') {
            fields.style.display = 'block';
        } else {
            fields.style.display = 'none';
        }
    }

    function toggleBilling() {
        const checkbox = document.getElementById('billing-same-checkbox');
        const fields = document.getElementById('billing-fields');
        fields.style.display = checkbox.checked ? 'none' : 'block';
    }

    function togglePayPal() {
        const selected = document.querySelector('input[name="payment_method"]:checked').value;
        const cardFields = document.getElementById('card-fields');
        const paypalNotice = document.getElementById('paypal-notice');

        if (selected === 'paypal') {
            cardFields.style.display = 'none';
            paypalNotice.style.display = 'flex';
        } else {
            cardFields.style.display = 'block';
            paypalNotice.style.display = 'none';
        }
    }

    // Card number auto-spacing
    document.querySelector('input[name="card_number"]')?.addEventListener('input', function(e) {
        let val = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
        let formatted = val.match(/.{1,4}/g)?.join(' ') || val;
        e.target.value = formatted;
    });

    // Expiry date auto-slash
    document.querySelector('input[name="card_expiry"]')?.addEventListener('input', function(e) {
        let val = e.target.value.replace(/\//g, '').replace(/[^0-9]/g, '');
        if (val.length >= 2) {
            val = val.substring(0, 2) + '/' + val.substring(2);
        }
        e.target.value = val;
    });
</script>
@endsection
