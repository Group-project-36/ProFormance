@extends('layouts.app')

@section('content')
<div class="kb-cart-page">
    <div class="container kb-cart-container">
        <h1 class="kb-cart-title">Your Basket</h1>

        @if (session('error'))
            <div class="kb-cart-alert kb-cart-alert-error">
                {{ session('error') }}
            </div>
        @endif

        @if (empty($lines))
            <p class="kb-cart-empty">Your basket is empty.</p>
        @else
            <div class="kb-cart-table-wrapper">
                <table class="kb-cart-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lines as $line)
                            @php
                                $subtotal = $line['price'] * $line['quantity'];
                            @endphp
                            <tr>
                                <td class="kb-cart-product-cell">
                                    @if (!empty($line['image']))
                                        <img src="{{ $line['image'] }}" alt=""
                                             class="kb-cart-product-image">
                                    @endif
                                    <span class="kb-cart-product-name">
                                        {{ $line['name'] ?? 'Unnamed product' }}
                                    </span>
                                </td>
                                <td class="kb-cart-price">
                                    £{{ number_format($line['price'], 2) }}
                                </td>
                                <td class="kb-cart-qty">
                                    <form method="POST"
                                          action="{{ route('cart.update', $line['id']) }}"
                                          class="kb-cart-qty-form">
                                        @csrf
                                        <input type="number"
                                               name="quantity"
                                               value="{{ $line['quantity'] }}"
                                               min="0"
                                               class="kb-cart-qty-input">
                                        <button type="submit" class="kb-btn kb-btn-secondary kb-add-to-cart-btn">
                                            Update
                                        </button>
                                    </form>
                                </td>
                                <td class="kb-cart-subtotal">
                                    £{{ number_format($subtotal, 2) }}
                                </td>
                                <td class="kb-cart-actions">
                                    <form method="POST"
                                          action="{{ route('cart.remove', $line['id']) }}">
                                        @csrf
                                        <button type="submit" class="kb-add-to-cart-btn">
                                            Remove
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="kb-cart-summary">
                <div class="kb-cart-summary-line">
                    <span>Total items:</span>
                    <strong>{{ $totalItems }}</strong>
                </div>
                <div class="kb-cart-summary-line kb-cart-summary-total">
                    <span>Total:</span>
                    <strong>£{{ number_format($total, 2) }}</strong>
                </div>

                <form method="POST" action="{{ route('cart.clear') }}" class="kb-cart-clear-form">
                    @csrf
                    <button type="submit" class="kb-btn kb-btn-outline kb-add-to-cart-btn ">
                        Clear Basket
                    </button>
                </form>
                <span><p></p></span>
               <button onclick="location.href='/checkout'" type="submit" class="kb-add-to-cart-btn ">
                        Proceed to Checkout
                </button>

            </div>
        @endif
    </div>
</div>
@endsection
