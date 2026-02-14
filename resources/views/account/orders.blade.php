{{-- resources/views/account/orders.blade.php --}}
@extends('account.layout')

@section('account-content')
<div class="kb-account-section">
    <h1 class="kb-account-title">My Orders</h1>
    <p class="kb-account-subtitle">View and track your order history.</p>
</div>

{{-- STATUS FILTER --}}
<div class="kb-account-filters">
    <a href="{{ route('account.orders') }}"
       class="kb-filter-tag {{ !request('status') ? 'active' : '' }}">All</a>
    @foreach(['Pending', 'Paid', 'Fulfilled', 'Cancelled', 'Refunded'] as $status)
        <a href="{{ route('account.orders', ['status' => $status]) }}"
           class="kb-filter-tag {{ request('status') === $status ? 'active' : '' }}">
            {{ $status }}
        </a>
    @endforeach
</div>

@if($orders->isEmpty())
    <div class="kb-account-empty">
        <i class="bi bi-bag-x"></i>
        <p>No orders found.</p>
        <a href="{{ route('products.index') }}" class="kb-account-btn kb-account-btn-primary">Start Shopping</a>
    </div>
@else
    <div class="kb-account-table-wrapper">
        <table class="kb-account-table">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    <tr>
                        <td>{{ $order->order_number }}</td>
                        <td>{{ $order->created_at->format('d M Y') }}</td>
                        <td>
                            <span class="kb-order-status kb-order-status-{{ strtolower($order->order_status) }}">
                                {{ $order->order_status }}
                            </span>
                        </td>
                        <td>{{ $order->items->count() }} item(s)</td>
                        <td>£{{ number_format($order->total_penny / 100, 2) }}</td>
                        <td>
                            <a href="{{ route('account.orders.show', $order->order_id) }}" class="kb-account-btn kb-account-btn-small">
                                View Details
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="kb-account-pagination">
        {{ $orders->withQueryString()->links() }}
    </div>
@endif
@endsection
