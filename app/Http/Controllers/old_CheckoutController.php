<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    protected function getCart(): array
    {
        return session('cart', []);
    }

    protected function computeTotals(array $cart): array
    {
        $total = 0;
        $totalItems = 0;

        foreach ($cart as $line) {
            $lineTotal   = ($line['price'] ?? 0) * ($line['quantity'] ?? 0);
            $total      += $lineTotal;
            $totalItems += ($line['quantity'] ?? 0);
        }

        return [$total, $totalItems];
    }

    public function show(Request $request)
    {
        $cart = $this->getCart();
        [$total, $totalItems] = $this->computeTotals($cart);

        return view('checkout.show', [
            'lines'      => $cart,
            'total'      => $total,
            'totalItems' => $totalItems,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ship_recipient_name'   => 'required|string|max:255',
            'ship_house_number'     => 'nullable|string|max:50',
            'ship_address_line_one' => 'required|string|max:255',
            'ship_address_line_two' => 'nullable|string|max:255',
            'ship_city'             => 'required|string|max:255',
            'ship_county'           => 'nullable|string|max:255',
            'ship_postcode'         => 'required|string|max:20',
            'ship_country_code'     => 'required|string|max:2',
            'ship_phone_number'     => 'nullable|string|max:50',
        ]);

        $cart = $this->getCart();
        [$total, $totalItems] = $this->computeTotals($cart);

        if ($totalItems === 0) {
            return redirect()->route('cart.index')
                ->with('error', 'Your basket is empty.');
        }

        $subtotalPenny = (int) round($total * 100);
        $shippingPenny = 0;
        $taxPenny      = 0;
        $discountPenny = 0;
        $totalPenny    = $subtotalPenny + $shippingPenny + $taxPenny - $discountPenny;

        DB::transaction(function () use (
            $data,
            $cart,
            $subtotalPenny,
            $shippingPenny,
            $taxPenny,
            $discountPenny,
            $totalPenny,
            $request
        ) {
            $order = Order::create([
                'order_number'        => 'ORD-' . strtoupper(Str::random(10)),
                'user_id'             => auth()->id(),
                'currency_code'       => 'GBP',
                'order_status'        => 'pending',
                'subtotal_penny'      => $subtotalPenny,
                'shipping_penny'      => $shippingPenny,
                'tax_penny'           => $taxPenny,
                'discount_penny'      => $discountPenny,
                'total_penny'         => $totalPenny,

                'ship_recipient_name'   => $data['ship_recipient_name'],
                'ship_house_number'     => $data['ship_house_number'] ?? null,
                'ship_address_line_one' => $data['ship_address_line_one'],
                'ship_address_line_two' => $data['ship_address_line_two'] ?? null,
                'ship_city'             => $data['ship_city'],
                'ship_county'           => $data['ship_county'] ?? null,
                'ship_postcode'         => $data['ship_postcode'],
                'ship_country_code'     => $data['ship_country_code'],
                'ship_phone_number'     => $data['ship_phone_number'] ?? null,

                'bill_recipient_name'   => $data['ship_recipient_name'],
                'bill_house_number'     => $data['ship_house_number'] ?? null,
                'bill_address_line_one' => $data['ship_address_line_one'],
                'bill_address_line_two' => $data['ship_address_line_two'] ?? null,
                'bill_city'             => $data['ship_city'],
                'bill_county'           => $data['ship_county'] ?? null,
                'bill_postcode'         => $data['ship_postcode'],
                'bill_country_code'     => $data['ship_country_code'],
                'bill_phone_number'     => $data['ship_phone_number'] ?? null,
            ]);

            foreach ($cart as $line) {
                $unitPricePenny = (int) round(($line['price'] ?? 0) * 100);

                OrderItem::create([
                    'order_id'         => $order->order_id,
                    'variant_id'       => $line['id'] ?? null,
                    'sku' => $line['sku'] ?? 'UNKNOWN',
                    'title'            => $line['name'] ?? 'Untitled',
                    'unit_price_penny' => $unitPricePenny,
                    'quantity'         => $line['quantity'] ?? 1,
                    'tax_rate'         => 0,
                ]);
            }

            $request->session()->forget('cart');
        });

        return redirect()->route('home')
            ->with('success', 'Your order has been placed.');
    }
}
