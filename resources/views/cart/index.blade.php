@extends('layouts.app')

@section('content')
<div class="kb-cart-page">
    <div class="container kb-cart-container">
        <div class="kb-cart-header">
            <h1 class="kb-cart-title">Your Basket</h1>
            @if (!empty($lines))
                <div class="kb-cart-header-meta">
                    <span><strong id="js-total-items">{{ $totalItems }}</strong> items</span>
                </div>
            @endif
        </div>

        @if (session('error'))
            <div class="kb-cart-alert kb-cart-alert-error">
                {{ session('error') }}
            </div>
        @endif

        @if (empty($lines))
            <div class="kb-cart-empty-wrap">
                <p class="kb-cart-empty">Your basket is empty.</p>
                <button onclick="location.href='/'" class="kb-btn kb-btn-primary">Continue Shopping</button>
            </div>
        @else
            <div class="kb-cart-grid">
                {{-- LEFT: LINES --}}
                <div class="kb-cart-lines">
                    @foreach ($lines as $line)
                        @php
                            $price = $line['price'] ?? 0;
                            $qty = $line['quantity'] ?? 0;
                            $subtotal = $price * $qty;

                            $imgSrc = !empty($line['image'])
                                ? asset($line['image'])
                                : asset('images/placeholders/product-placeholder.jpg');
                        @endphp

                        <div class="kb-cart-card kb-cart-row"
                             data-line-id="{{ $line['id'] }}"
                             data-price="{{ $price }}">
                            <a href="{{ route('products.show', $line['product_id']) }}" class="kb-cart-card-media kb-cart-link">
                                <img
                                    src="{{ $imgSrc }}"
                                    alt="{{ $line['name'] ?? 'Product image' }}"
                                    loading="lazy"
                                    onerror="this.src='{{ asset('images/placeholders/product-placeholder.jpg') }}'">
                            </a>


                            <div class="kb-cart-card-body">
                                <div class="kb-cart-card-top">
                                    <div class="kb-cart-card-title">
                                        <a href="{{ route('products.show', $line['product_id']) }}" class="kb-cart-product-name kb-cart-link">
                                            {{ $line['name'] ?? 'Unnamed product' }}
                                        </a>
                                        <div class="kb-cart-product-meta">
                                            <span class="kb-cart-unit-price">
                                                @php $symbol = $line['symbol'] ?? '£'; @endphp
                                                {{ $symbol }}{{ number_format($price, 2) }}
                                                <span class="kb-muted">{{ $vatLabel ?? ($line['vat_label'] ?? '') }}</span>
                                            </span>
                                        </div>
                                    </div>

                                    <form method="POST" action="{{ route('cart.remove', $line['id']) }}">
                                        @csrf
                                        <button type="submit" class="kb-link-danger" aria-label="Remove item">
                                            Remove
                                        </button>
                                    </form>
                                </div>

                                <div class="kb-cart-card-bottom">
                                    {{-- Quantity controls --}}
                                    <div class="kb-cart-qty-block">
                                        <span class="kb-cart-qty-label">Qty</span>

                                        <div class="kb-cart-qty-controls" role="group" aria-label="Quantity controls">
                                            <button type="button"
                                                    class="kb-qty-btn js-qty-dec"
                                                    data-line-id="{{ $line['id'] }}"
                                                    aria-label="Decrease quantity">−</button>

                                            <form method="POST"
                                                  action="{{ route('cart.update', $line['id']) }}"
                                                  class="kb-cart-qty-form"
                                                  data-line-id="{{ $line['id'] }}">
                                                @csrf
                                                <input type="number"
                                                       name="quantity"
                                                       value="{{ $qty }}"
                                                       min="0"
                                                       class="kb-cart-qty-input js-qty-input"
                                                       inputmode="numeric"
                                                       aria-label="Quantity">
                                                <noscript>
                                                    <button type="submit" class="kb-btn kb-btn-secondary">Update</button>
                                                </noscript>
                                            </form>

                                            <button type="button"
                                                    class="kb-qty-btn js-qty-inc"
                                                    data-line-id="{{ $line['id'] }}"
                                                    aria-label="Increase quantity">+</button>
                                        </div>
                                    </div>

                                    {{-- Subtotal --}}
                                    <div class="kb-cart-line-total">
                                        <div class="kb-cart-line-total-label">Subtotal</div>
                                        <div class="kb-cart-line-total-value">
                                            @php $symbol = $line['symbol'] ?? '£'; @endphp
                                            <span class="js-line-subtotal">{{ $symbol }}{{ number_format($subtotal, 2) }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="kb-cart-row-status js-row-status" aria-live="polite"></div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- RIGHT: SUMMARY --}}
                <aside class="kb-cart-summary-card">
                    <h2 class="kb-cart-summary-title">Summary</h2>

                    <div class="kb-cart-summary-line">
                        <span>Total items</span>
                        <strong id="js-total-items-2">{{ $totalItems }}</strong>
                    </div>

                    <div class="kb-cart-summary-line kb-cart-summary-total">
                        <span>Total</span>
                        @php $symbol = ($lines[0]['symbol'] ?? '£'); @endphp
                       <strong>{{ $symbol }}<span id="js-total">{{ number_format($total, 2) }}</span></strong>
                        <span class="kb-muted">{{ $vatLabel ?? '' }}</span>

                    </div>

                    <div class="kb-cart-summary-actions">
                        <button onclick="location.href='/checkout'"
                                class="kb-btn kb-btn-primary kb-btn-block">
                            Proceed to Checkout
                        </button>

                        <form method="POST" action="{{ route('cart.clear') }}" class="kb-cart-clear-form">
                            @csrf
                            <button type="submit" class="kb-btn kb-btn-outline kb-btn-block">
                                Clear Basket
                            </button>
                        </form>

                        <button onclick="location.href='/'" class="kb-btn kb-btn-ghost kb-btn-block">
                            Continue Shopping
                        </button>
                    </div>
                </aside>
            </div>
        @endif
    </div>
</div>
@endsection
