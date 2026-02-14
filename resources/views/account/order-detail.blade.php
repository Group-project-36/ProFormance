{{-- resources/views/account/order-detail.blade.php --}}
@extends('account.layout')

@section('account-content')
<div class="kb-account-section">
    <a href="{{ route('account.orders') }}" class="kb-account-back-link">
        <i class="bi bi-arrow-left"></i> Back to Orders
    </a>
    <h1 class="kb-account-title">Order {{ $order->order_number }}</h1>
    <p class="kb-account-subtitle">
        Placed on {{ $order->created_at->format('d M Y \a\t H:i') }}
        &nbsp;·&nbsp;
        <span class="kb-order-status kb-order-status-{{ strtolower($order->order_status) }}">
            {{ $order->order_status }}
        </span>
    </p>
</div>

{{-- ORDER ITEMS --}}
<div class="kb-account-card">
    <h2 class="kb-account-card-title">Items Ordered</h2>
    <div class="kb-account-table-wrapper">
        <table class="kb-account-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>SKU</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->title }}</td>
                        <td><code>{{ $item->sku }}</code></td>
                        <td>£{{ number_format($item->unit_price_penny / 100, 2) }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>£{{ number_format(($item->unit_price_penny * $item->quantity) / 100, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- ORDER SUMMARY + SHIPPING --}}
<div class="kb-account-grid-2">
    <div class="kb-account-card">
        <h2 class="kb-account-card-title">Order Summary</h2>
        <div class="kb-order-summary">
            <div class="kb-summary-row">
                <span>Subtotal</span>
                <span>£{{ number_format($order->subtotal_penny / 100, 2) }}</span>
            </div>
            <div class="kb-summary-row">
                <span>Shipping</span>
                <span>£{{ number_format($order->shipping_penny / 100, 2) }}</span>
            </div>
            <div class="kb-summary-row">
                <span>Tax</span>
                <span>£{{ number_format($order->tax_penny / 100, 2) }}</span>
            </div>
            @if($order->discount_penny > 0)
                <div class="kb-summary-row kb-summary-discount">
                    <span>Discount</span>
                    <span>-£{{ number_format($order->discount_penny / 100, 2) }}</span>
                </div>
            @endif
            <div class="kb-summary-row kb-summary-total">
                <strong>Total</strong>
                <strong>£{{ number_format($order->total_penny / 100, 2) }}</strong>
            </div>
        </div>
    </div>

    <div class="kb-account-card">
        <h2 class="kb-account-card-title">Shipping Address</h2>
        <div class="kb-address-display">
            <p>{{ $order->ship_recipient_name }}</p>
            <p>{{ $order->ship_house_number }} {{ $order->ship_address_line_one }}</p>
            @if($order->ship_address_line_two)
                <p>{{ $order->ship_address_line_two }}</p>
            @endif
            <p>{{ $order->ship_city }}{{ $order->ship_county ? ', ' . $order->ship_county : '' }}</p>
            <p>{{ $order->ship_postcode }}</p>
            @if($order->ship_phone_number)
                <p><i class="bi bi-telephone"></i> {{ $order->ship_phone_number }}</p>
            @endif
        </div>
    </div>
</div>
@endsection
