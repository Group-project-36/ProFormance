<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address;
use App\Models\Discount;
use App\Models\DiscountRedemption;
use App\Models\TaxProfile;


use App\Rules\Luhn;
use App\Rules\CardNumberBrand;
use App\Rules\CardExpiry;
use App\Rules\CardCvv;

use App\Support\CardBrands;


use Illuminate\Validation\Rule;
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

    /**
     * Penny-safe subtotal from cart.
     * Prefers line['price_penny'] if present; falls back to line['price'] * 100.
     */
    private function cartSubtotalPenny(array $cart): int
    {
        $subtotal = 0;

        foreach ($cart as $line) {
            $qty = (int)($line['quantity'] ?? 0);

            $unitPenny = isset($line['price_penny'])
                ? (int)$line['price_penny']
                : (int) round(((float)($line['price'] ?? 0)) * 100);

            $subtotal += $unitPenny * $qty;
        }

        return $subtotal;
    }

    /**
     * Simple VAT:
     * - GB => 20%
     * - non-GB => 0% (export)
     * - tax_exempt override => 0%
     */
    private function vatRateFor(string $countryCode, ?TaxProfile $taxProfile): float
    {
        $countryCode = strtoupper(trim($countryCode));
        $rate = ($countryCode === 'GB') ? 0.20 : 0.00;

        if ($taxProfile && (int)$taxProfile->tax_exempt === 1) {
            $rate = 0.00;
        }

        return $rate;
    }

    /**
     * Show the checkout page.
     */
    public function show(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Please sign in to proceed to checkout.');
        }

        

        $cart = $this->getCart();

        $giftOrders = session('gift_orders', []);
        $isGift = false;

        foreach ($cart as $line) {
            $variantId = $line['variant_id'] ?? null;
            if ($variantId && isset($giftOrders[$variantId])) {
                $isGift = true;
                $giftShippingPreview = $giftOrders[$variantId];
                break;
            }
        }

        if (empty($cart)) {
            return redirect()->route('cart.index')
                ->with('error', 'Your basket is empty.');
        }

        $user = Auth::user();

        // Addresses (default first)
        $addresses = Address::where('user_id', $user->user_id)
            ->orderByDesc('is_default_shipping_address')
            ->get();

        // Preview shipping country for VAT (default address or GB)
        // Default shipping country (buyer default or GB)
        $defaultAddress = $addresses->first();
        $shipCountryCode = $defaultAddress?->country_code ?? 'GB';

        // If cart contains a gift item, override ship country from gift address stored in session
        $giftOrders = session('gift_orders', []);
        $isGift = false;
        $giftShippingPreview = null;

        foreach ($cart as $line) {
            $variantId = $line['variant_id'] ?? null;
            if ($variantId && isset($giftOrders[$variantId])) {
                $isGift = true;
                $giftShippingPreview = $giftOrders[$variantId];
                break;
            }
        }

        // Compute pennies
        $subtotalPenny = $this->cartSubtotalPenny($cart);
        $totalItems = array_sum(array_map(fn ($l) => (int)($l['quantity'] ?? 0), $cart));

        // Discount (pennies)
        $appliedDiscount = session('checkout_discount');
        $discountPenny = 0;

        if ($appliedDiscount) {
            $discount = Discount::find($appliedDiscount['discount_id']);
            if ($discount) {
                $discountPenny = $discount->calculatePenny($subtotalPenny);
            }
        }

        // VAT
        $taxProfile = TaxProfile::where('user_id', $user->user_id)->first();
        $vatRate = $this->vatRateFor($shipCountryCode, $taxProfile);

        // Taxable base after discount
        $taxablePenny = max(0, $subtotalPenny - $discountPenny);
        $taxPenny = (int) round($taxablePenny * $vatRate);
        $shippingMethod = $data['shipping_method'] ?? 'standard';
        $shippingPenny = $this->shippingPennyFor($shipCountryCode, $shippingMethod);
        $totalPenny = max(0, $taxablePenny + $taxPenny + $shippingPenny);

        $symbol = $cart[0]['symbol'] ?? '£';

        return view('checkout.show', [
            'lines'           => $cart,
            'addresses'       => $addresses,
            'user'            => $user,
            'appliedDiscount' => $appliedDiscount,

            'isGift' => $isGift,
            'giftShippingPreview' => $giftShippingPreview,

            // totals for display
            'subtotalPenny'   => $subtotalPenny,
            'discountPenny'   => $discountPenny,
            'taxPenny'        => $taxPenny,
            'shippingPenny'   => $shippingPenny,
            'totalPenny'      => $totalPenny,
            'vatRate'         => $vatRate,
            'shipCountryCode' => $shipCountryCode,

            'totalItems'      => $totalItems,
            'symbol'          => $symbol,
        ]);
    }

    /**
     * Apply a discount code.
     */
    public function applyDiscount(Request $request)
    {
        $request->validate([
            'discount_code' => 'required|string|max:50',
        ]);

        $cart = $this->getCart();
        $subtotalPenny = $this->cartSubtotalPenny($cart);

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

        session(['checkout_discount' => [
            'discount_id'    => $discount->discount_id,
            'discount_code'  => $discount->discount_code,
            'discoun_type'   => $discount->discoun_type,
            'discount_value' => $discount->discount_value,
            'amount_penny'   => $discountPenny,
        ]]);

        return back()->with('success', 'Discount code applied!');
    }

    public function totals(Request $request)
    {
        $request->validate([
            'ship_country_code' => 'required|string|size:2',
            'shipping_method'   => 'required|in:standard,next_day',
        ]);

        $cart = $this->getCart();
        $subtotalPenny = $this->cartSubtotalPenny($cart);

        $appliedDiscount = session('checkout_discount');
        $discountPenny = 0;

        if ($appliedDiscount) {
            $discount = Discount::find($appliedDiscount['discount_id']);
            if ($discount) {
                $discountPenny = $discount->calculatePenny($subtotalPenny);
            }
        }

        $user = Auth::user();
        $taxProfile = TaxProfile::where('user_id', $user->user_id)->first();

        $shipCountryCode = strtoupper(trim($request->ship_country_code));
        $vatRate = $this->vatRateFor($shipCountryCode, $taxProfile);

        $taxablePenny = max(0, $subtotalPenny - $discountPenny);
        $taxPenny = (int) round($taxablePenny * $vatRate);

        $shippingMethod = $request->shipping_method;
        $shippingPenny = $this->shippingPennyFor($shipCountryCode, $shippingMethod);
        $totalPenny = max(0, $taxablePenny + $taxPenny + $shippingPenny);
        $totalPenny = max(0, $taxablePenny + $taxPenny + $shippingPenny);

        return response()->json([
            'ok' => true,
            'subtotalPenny' => $subtotalPenny,
            'discountPenny' => $discountPenny,
            'taxPenny' => $taxPenny,
            'shippingPenny' => $shippingPenny,
            'totalPenny' => $totalPenny,
            'vatRate' => $vatRate,
            'shipCountryCode' => $shipCountryCode,
        ]);
    }


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

        $usingSavedAddress = $request->filled('address_id');
        $billingSame       = $request->has('billing_same');
         $method = $request->input('payment_method', 'card'); // default to card

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
            'billing_same'          => 'sometimes|in:1',
            'bill_recipient_name'   => ($billingSame ? 'nullable' : 'required') . '|string|max:200',
            'bill_house_number'     => 'nullable|string|max:10',
            'bill_address_line_one' => ($billingSame ? 'nullable' : 'required') . '|string|max:100',
            'bill_address_line_two' => 'nullable|string|max:100',
            'bill_city'             => ($billingSame ? 'nullable' : 'required') . '|string|max:100',
            'bill_county'           => 'nullable|string|max:100',
            'bill_postcode'         => ($billingSame ? 'nullable' : 'required') . '|string|max:100',
            'bill_country_code'     => ($billingSame ? 'nullable' : 'required') . '|string|max:2',
            'bill_phone_number'     => 'nullable|string|max:30',

            // Payment
            'payment_method' => ['required', 'in:card,paypal'],

            'card_name'   => $method === 'paypal'
                ? ['nullable']
                : ['required','string','max:200'],

            'card_number' => $method === 'paypal'
                ? ['nullable']
                : ['required','string','max:30', new Luhn(), new CardNumberBrand()],

            'card_expiry' => $method === 'paypal'
                ? ['nullable']
                : ['required','string','max:7', new CardExpiry()],

            'card_cvv'    => $method === 'paypal'
                ? ['nullable']
                : ['required','string','max:4', new CardCvv($request->input('card_number'))],

            // Notes
            'order_notes'      => 'nullable|string|max:1000',
            'shipping_method' => 'required|in:standard,next_day',
        ]);

        $user = Auth::user();
        $cart = $this->getCart();

        $totalItems = array_sum(array_map(fn ($l) => (int)($l['quantity'] ?? 0), $cart));
        if ($totalItems === 0) {
            return redirect()->route('cart.index')
                ->with('error', 'Your basket is empty.');
        }
        
        // Detect gift shipping (from session + cart)
        $giftOrders = session('gift_orders', []);
        $cart = $this->getCart();

        $giftShipping = null;
        foreach ($cart as $line) {
            $variantId = $line['variant_id'] ?? null;
            if ($variantId && isset($giftOrders[$variantId])) {
                $giftShipping = $giftOrders[$variantId];
                break;
            }
        }

        // ✅ Always define $shipping in one of these branches
        if ($giftShipping) {
            $shipping = [
                'address_id'       => $giftShipping['address_id'] ?? null,
                'recipient_name'   => $giftShipping['recipient_name'] ?? null,
                'house_number'     => $giftShipping['house_number'] ?? null,
                'address_line_one' => $giftShipping['address_line_one'] ?? null,
                'address_line_two' => $giftShipping['address_line_two'] ?? null,
                'city'             => $giftShipping['city'] ?? null,
                'county'           => $giftShipping['county'] ?? null,
                'postcode'         => $giftShipping['postcode'] ?? null,
                'country_code'     => $giftShipping['country_code'] ?? null,
                'phone_number'     => $giftShipping['phone_number'] ?? null,
            ];
        } else {
            $shipping = $this->resolveShippingAddress($data, $user);
        }


    $shipCountryCode = strtoupper($shipping['country_code'] ?? 'GB');
        $shippingMethod = $data['shipping_method'] ?? 'standard';

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

        $selectedMethod = $request->input('payment_method');

        $data = $request->validate([
            // ... your shipping/billing rules unchanged ...

            'payment_method' => ['required', 'string', Rule::in(['card','paypal'])],

            'card_name'   => ['required_unless:payment_method,paypal', 'nullable', 'string', 'max:200'],

            'card_number' => $selectedMethod === 'paypal'
                ? ['nullable']
                : ['required', 'string', 'max:30', new Luhn(), new CardNumberBrand($selectedMethod)],

            'card_expiry' => $selectedMethod === 'paypal'
                ? ['nullable']
                : ['required', 'string', 'max:7', new CardExpiry()],

            'card_cvv'    => $selectedMethod === 'paypal'
                ? ['nullable']
                : ['required', 'string', 'max:4', new CardCvv($selectedMethod)],

            'order_notes' => ['nullable','string','max:1000'],
            'shipping_method' => ['required','in:standard,next_day'],
        ]);


        // Subtotal + shipping
        $subtotalPenny = $this->cartSubtotalPenny($cart);

        // Discount
        $discountPenny = 0;
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

        // VAT
        $taxProfile = TaxProfile::where('user_id', $user->user_id)->first();
        $vatRate = $this->vatRateFor($shipCountryCode, $taxProfile);

        $taxablePenny = max(0, $subtotalPenny - $discountPenny);
        $taxPenny = (int) round($taxablePenny * $vatRate);
        $shippingMethod = $data['shipping_method'] ?? 'standard';
        $shippingPenny = $this->shippingPennyFor($shipCountryCode, $shippingMethod);
        $totalPenny = max(0, $taxablePenny + $taxPenny + $shippingPenny);
        DB::transaction(function () use (
            $cart, $user, $shipping, $billing, $data,
            $subtotalPenny, $shippingPenny, $taxPenny, $discountPenny, $totalPenny,
            $discount, $vatRate, $request
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

            foreach ($cart as $line) {
                $qty = (int)($line['quantity'] ?? 1);

                $unitPricePenny = isset($line['price_penny'])
                    ? (int)$line['price_penny']
                    : (int) round(((float)($line['price'] ?? 0)) * 100);

                // Look up SKU
                $sku = 'UNKNOWN';
                if (!empty($line['variant_id'])) {
                    $variant = \App\Models\ProductVariant::find($line['variant_id']);
                    if ($variant) {
                        $sku = $variant->sku;
                    }
                }

                OrderItem::create([
                    'order_id'         => $order->order_id,
                    'variant_id'       => $line['variant_id'] ?? null,
                    'sku'              => $sku,
                    'title'            => $line['name'] ?? 'Untitled',
                    'unit_price_penny' => $unitPricePenny,
                    'quantity'         => $qty,
                    'tax_rate'         => $vatRate, // 0.20 or 0.00
                ]);
            }

            // Discount redemption
            if ($discount && $discountPenny > 0) {
                DiscountRedemption::create([
                    'discount_id' => $discount->discount_id,
                    'user_id'     => $user->user_id,
                    'order_id'    => $order->order_id,
                    'redeemed_at' => now(),
                ]);

                DB::table('order_discounts')->insert([
                    'order_id'             => $order->order_id,
                    'discount_id'          => $discount->discount_id,
                    'amount_applied_penny' => $discountPenny,
                ]);
            }

            // Simulated payment record
            $providerMap = [
                'visa' => 3,
                'mastercard' => 4,
                'amex' => 2,
                'paypal' => 1,
            ];

            $providerKey = 'paypal';

            if (($data['payment_method'] ?? 'card') === 'card') {
                $brand = CardBrands::detect($data['card_number'] ?? '');
                $providerKey = $brand; // visa/mastercard/amex
            }

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
            $request->session()->forget('gift_orders');
        });

        return redirect()->route('account.orders')
            ->with('success', 'Your order has been placed successfully!');
    }

    private function shippingPennyFor(string $countryCode, string $shippingMethod): int
    {
        $countryCode = strtoupper(trim($countryCode));
        $shippingMethod = strtolower(trim($shippingMethod));

        // Simple pricing (edit numbers to taste)
        if ($countryCode === 'GB') {
            return $shippingMethod === 'next_day' ? 1299 : 799; // £7.99 or £4.99
        }

        // International flat rate
        return 1999; // £19.99
    }

    /**
     * Resolve shipping address from saved address or manual input.
     */
    private function resolveShippingAddress(array $data, $user): array
    {
        if (!empty($data['address_id'])) {
            $address = Address::where('user_id', $user->user_id)
                ->where('address_id', $data['address_id'])
                ->first();

            if ($address) {
                return [
                    'address_id'       => $address->address_id,
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
