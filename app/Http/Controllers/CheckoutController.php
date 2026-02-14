<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderDiscount;
use App\Models\Address;
use App\Models\Discount;
use App\Models\DiscountRedemption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    protected function currency(): string
    {
        return session('currency', 'gbp');
    }

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

    /**
     * Show the checkout page.
     */
    public function show(Request $request)
    {
        // Require login
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Please sign in to proceed to checkout.');
        }

        $cart = $this->getCart();

        if (empty($cart)) {
            return redirect()->route('cart.index')
                ->with('error', 'Your basket is empty.');
        }

        [$total, $totalItems] = $this->computeTotals($cart);

        $user = Auth::user();

        // Fetch saved addresses
        $addresses = Address::where('user_id', $user->user_id)
            ->orderByDesc('is_default_shipping_address')
            ->get();

        // Check if a discount is already applied in session
        $appliedDiscount = session('checkout_discount');
        $discountAmount  = 0;

        if ($appliedDiscount) {
            $discount = Discount::find($appliedDiscount['discount_id']);
            if ($discount) {
                $subtotalPenny  = (int) round($total * 100);
                $discountAmount = $discount->calculatePenny($subtotalPenny) / 100;
            }
        }

        $symbol = $cart[0]['symbol'] ?? '£';

        return view('checkout.show', [
            'lines'           => $cart,
            'total'           => $total,
            'totalItems'      => $totalItems,
            'addresses'       => $addresses,
            'user'            => $user,
            'appliedDiscount' => $appliedDiscount,
            'discountAmount'  => $discountAmount,
            'grandTotal'      => max(0, $total - $discountAmount),
            'symbol'          => $symbol,
        ]);
    }

    /**
     * Apply a discount code (AJAX or normal request).
     */
    public function applyDiscount(Request $request)
    {
        $request->validate([
            'discount_code' => 'required|string|max:50',
        ]);

        $cart = $this->getCart();
        [$total] = $this->computeTotals($cart);
        $subtotalPenny = (int) round($total * 100);

        $discount = Discount::where('discount_code', strtoupper(trim($request->discount_code)))
            ->first();

        if (!$discount) {
            return back()->withErrors(['discount_code' => 'Invalid discount code.']);
        }

        $valid = $discount->isValid($subtotalPenny, Auth::user()->user_id);

        if ($valid !== true) {
            return back()->withErrors(['discount_code' => $valid]);
        }

        $discountPenny = $discount->calculatePenny($subtotalPenny);

        // Store in session
        session(['checkout_discount' => [
            'discount_id'   => $discount->discount_id,
            'discount_code' => $discount->discount_code,
            'discoun_type'  => $discount->discoun_type,
            'discount_value' => $discount->discount_value,
            'amount_penny'  => $discountPenny,
        ]]);

        return back()->with('success', 'Discount code applied!');
    }

    /**
     * Remove applied discount.
     */
    public function removeDiscount()
    {
        session()->forget('checkout_discount');
        return back()->with('success', 'Discount removed.');
    }

    /**
     * Place the order.
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // If a saved address is selected, shipping fields aren't needed
        $usingSavedAddress = $request->filled('address_id');
        $billingSame       = $request->has('billing_same');

        $data = $request->validate([
            // Shipping
            'address_id'            => 'nullable|integer',
            'ship_recipient_name'   => ($usingSavedAddress ? 'nullable' : 'required') . '|string|max:200',
            'ship_house_number'     => 'nullable|string|max:10',
            'ship_address_line_one' => ($usingSavedAddress ? 'nullable' : 'required') . '|string|max:100',
            'ship_address_line_two' => 'nullable|string|max:100',
            'ship_city'             => ($usingSavedAddress ? 'nullable' : 'required') . '|string|max:100',
            'ship_county'           => 'nullable|string|max:100',
            'ship_postcode'         => ($usingSavedAddress ? 'nullable' : 'required') . '|string|max:100',
            'ship_country_code'     => ($usingSavedAddress ? 'nullable' : 'required') . '|string|max:2',
            'ship_phone_number'     => 'nullable|string|max:30',

            // Billing
            'billing_same'              => 'sometimes|in:1',
            'bill_recipient_name'       => ($billingSame ? 'nullable' : 'required') . '|string|max:200',
            'bill_house_number'         => 'nullable|string|max:10',
            'bill_address_line_one'     => ($billingSame ? 'nullable' : 'required') . '|string|max:100',
            'bill_address_line_two'     => 'nullable|string|max:100',
            'bill_city'                 => ($billingSame ? 'nullable' : 'required') . '|string|max:100',
            'bill_county'               => 'nullable|string|max:100',
            'bill_postcode'             => ($billingSame ? 'nullable' : 'required') . '|string|max:100',
            'bill_country_code'         => ($billingSame ? 'nullable' : 'required') . '|string|max:2',
            'bill_phone_number'         => 'nullable|string|max:30',

            // Payment (simulated)
            'payment_method'   => 'required|string|in:visa,mastercard,amex,paypal',
            'card_name'        => 'required_unless:payment_method,paypal|nullable|string|max:200',
            'card_number'      => 'required_unless:payment_method,paypal|nullable|string|max:30',
            'card_expiry'      => 'required_unless:payment_method,paypal|nullable|string|max:7',
            'card_cvv'         => 'required_unless:payment_method,paypal|nullable|string|max:4',

            // Notes
            'order_notes'      => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();
        $cart = $this->getCart();
        [$total, $totalItems] = $this->computeTotals($cart);

        if ($totalItems === 0) {
            return redirect()->route('cart.index')
                ->with('error', 'Your basket is empty.');
        }

        // Resolve shipping address
        $shipping = $this->resolveShippingAddress($data, $user);

        // Resolve billing address
        if (!empty($data['billing_same'])) {
            $billing = $shipping;
        } else {
            $billing = [
                'recipient_name'   => $data['bill_recipient_name'],
                'house_number'     => $data['bill_house_number'] ?? null,
                'address_line_one' => $data['bill_address_line_one'],
                'address_line_two' => $data['bill_address_line_two'] ?? null,
                'city'             => $data['bill_city'],
                'county'           => $data['bill_county'] ?? null,
                'postcode'         => $data['bill_postcode'],
                'country_code'     => $data['bill_country_code'],
                'phone_number'     => $data['bill_phone_number'] ?? null,
            ];
        }

        $subtotalPenny = (int) round($total * 100);
        $shippingPenny = 0;
        $taxPenny      = 0;
        $discountPenny = 0;

        // Apply discount from session
        $appliedDiscount = session('checkout_discount');
        $discount = null;

        if ($appliedDiscount) {
            $discount = Discount::find($appliedDiscount['discount_id']);
            if ($discount) {
                $valid = $discount->isValid($subtotalPenny, $user->user_id);
                if ($valid === true) {
                    $discountPenny = $discount->calculatePenny($subtotalPenny);
                }
            }
        }

        $totalPenny = max(0, $subtotalPenny + $shippingPenny + $taxPenny - $discountPenny);

        DB::transaction(function () use (
            $cart, $user, $shipping, $billing, $data,
            $subtotalPenny, $shippingPenny, $taxPenny, $discountPenny, $totalPenny,
            $discount, $request
        ) {
            $order = Order::create([
                'order_number'   => 'ORD-' . strtoupper(Str::random(10)),
                'user_id'        => $user->user_id,
                'currency_code'  => strtoupper($this->currency()),
                'order_status'   => 'Paid',
                'subtotal_penny' => $subtotalPenny,
                'shipping_penny' => $shippingPenny,
                'tax_penny'      => $taxPenny,
                'discount_penny' => $discountPenny,
                'total_penny'    => $totalPenny,

                'ship_recipient_name'   => $shipping['recipient_name'],
                'ship_house_number'     => $shipping['house_number'],
                'ship_address_line_one' => $shipping['address_line_one'],
                'ship_address_line_two' => $shipping['address_line_two'],
                'ship_city'             => $shipping['city'],
                'ship_county'           => $shipping['county'],
                'ship_postcode'         => $shipping['postcode'],
                'ship_country_code'     => $shipping['country_code'],
                'ship_phone_number'     => $shipping['phone_number'],
                'shipping_address_id'   => $shipping['address_id'] ?? null,

                'bill_recipient_name'   => $billing['recipient_name'],
                'bill_house_number'     => $billing['house_number'],
                'bill_address_line_one' => $billing['address_line_one'],
                'bill_address_line_two' => $billing['address_line_two'],
                'bill_city'             => $billing['city'],
                'bill_county'           => $billing['county'],
                'bill_postcode'         => $billing['postcode'],
                'bill_country_code'     => $billing['country_code'],
                'bill_phone_number'     => $billing['phone_number'],
                'billing_address_id'    => $billing['address_id'] ?? null,
            ]);

            // Create order items with correct variant_id and SKU
            foreach ($cart as $line) {
                $unitPricePenny = (int) round(($line['price'] ?? 0) * 100);

                // Look up the actual SKU from the variant
                $sku = 'UNKNOWN';
                if (!empty($line['variant_id'])) {
                    $variant = \App\Models\ProductVariant::find($line['variant_id']);
                    if ($variant) {
                        $sku = $variant->sku;
                    }
                }

                OrderItem::create([
                    'order_id'         => $order->order_id,
                    'variant_id'       => $line['variant_id'] ?? $line['id'] ?? null,
                    'sku'              => $sku,
                    'title'            => $line['name'] ?? 'Untitled',
                    'unit_price_penny' => $unitPricePenny,
                    'quantity'         => $line['quantity'] ?? 1,
                    'tax_rate'         => 0,
                ]);
            }

            // Record discount redemption
            if ($discount && $discountPenny > 0) {
                DiscountRedemption::create([
                    'discount_id' => $discount->discount_id,
                    'user_id'     => $user->user_id,
                    'order_id'    => $order->order_id,
                    'redeemed_at' => now(),
                ]);

                // Also record in order_discounts pivot
                DB::table('order_discounts')->insert([
                    'order_id'             => $order->order_id,
                    'discount_id'          => $discount->discount_id,
                    'amount_applied_penny' => $discountPenny,
                ]);
            }

            // Simulated payment record
            $providerMap = [
                'visa'       => 3,
                'mastercard' => 4,
                'amex'       => 2,
                'paypal'     => 1,
            ];

            DB::table('payments')->insert([
                'order_id'      => $order->order_id,
                'provider_id'   => $providerMap[$data['payment_method']] ?? 3,
                'provider_ref'  => 'SIM-' . strtoupper(Str::random(12)),
                'amount_penny'  => $totalPenny,
                'currency_code' => strtoupper($this->currency()),
                'return_status' => 'Authorised',
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);

            // Clear cart and discount
            $request->session()->forget('cart');
            $request->session()->forget('checkout_discount');
        });

        return redirect()->route('account.orders')
            ->with('success', 'Your order has been placed successfully!');
    }

    /**
     * Resolve shipping address from saved address or manual input.
     */
    private function resolveShippingAddress(array $data, $user): array
    {
        // If user selected a saved address
        if (!empty($data['address_id'])) {
            $address = Address::where('user_id', $user->user_id)
                ->where('address_id', $data['address_id'])
                ->first();

            if ($address) {
                return [
                    'address_id'     => $address->address_id,
                    'recipient_name'   => $address->recipient_name,
                    'house_number'     => $address->house_number,
                    'address_line_one' => $address->address_line_one,
                    'address_line_two' => $address->address_line_two,
                    'city'             => $address->city,
                    'county'           => $address->county,
                    'postcode'         => $address->postcode,
                    'country_code'     => $address->country_code,
                    'phone_number'     => $address->phone_number,
                ];
            }
        }

        // Manual entry
        return [
            'address_id'       => null,
            'recipient_name'   => $data['ship_recipient_name'],
            'house_number'     => $data['ship_house_number'] ?? null,
            'address_line_one' => $data['ship_address_line_one'],
            'address_line_two' => $data['ship_address_line_two'] ?? null,
            'city'             => $data['ship_city'],
            'county'           => $data['ship_county'] ?? null,
            'postcode'         => $data['ship_postcode'],
            'country_code'     => $data['ship_country_code'],
            'phone_number'     => $data['ship_phone_number'] ?? null,
        ];
    }
}
