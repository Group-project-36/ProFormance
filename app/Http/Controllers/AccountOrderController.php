<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Address;

class AccountOrderController extends Controller
{
    /**
     * List all orders for the authenticated user.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Order::where('user_id', $user->user_id)
            ->orderBy('created_at', 'desc');

        // Optional status filter
        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        $orders = $query->paginate(10);

        return view('account.orders', compact('orders'));
    }

    /**
     * Show a single order's details with its items.
     */
    public function show($orderId)
    {
        $user = Auth::user();

        $order = Order::where('user_id', $user->user_id)
            ->where('order_id', $orderId)
            ->with('items')
            ->firstOrFail();

        $isGift = false;

        if (!empty($order->shipping_address_id)) {
            $isGift = Address::where('address_id', $order->shipping_address_id)
                ->where('user_id', '!=', $order->user_id)
                ->exists();
        }

        return view('account.order-detail', compact('order', 'isGift'));
    }
}
