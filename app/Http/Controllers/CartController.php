<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\VariantCurrencyPrice;

class CartController extends Controller
{
    protected string $currency = 'GBP';

    protected function getCart(): array
    {
        return Session::get('cart', []);
    }

    protected function saveCart(array $cart): void
    {
        Session::put('cart', $cart);
    }

    protected function computeTotals(array $cart): array
    {
        $total = 0;
        $totalItems = 0;

        foreach ($cart as $line) {
            $lineTotal = ($line['price'] ?? 0) * ($line['quantity'] ?? 0);
            $total += $lineTotal;
            $totalItems += ($line['quantity'] ?? 0);
        }

        return [$total, $totalItems];
    }

    public function index()
    {
        $cart = $this->getCart();
        [$total, $totalItems] = $this->computeTotals($cart);

        return view('cart.index', [
            'lines'      => $cart,
            'total'      => $total,
            'totalItems' => $totalItems,
        ]);
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'variant_id' => 'required|integer',
            'quantity'   => 'nullable|integer|min:1',
        ]);

        $variantId = $validated['variant_id'];
        $quantity  = $validated['quantity'] ?? 1;

        $variantRow = DB::table('product_variants as pv')
            ->join('products as p', 'pv.product_id', '=', 'p.product_id')
            ->leftJoin('product_images as pi', 'pv.variant_id', '=', 'pi.variant_id')
            ->select('pv.variant_id', 'p.product_id', 'p.product_name', 'pi.image_url')
            ->where('pv.variant_id', $variantId)
            ->orderBy('pi.sort_order')
            ->first();

        if (! $variantRow) {
            return back()->with('error', 'Product not found.');
        }
        $priceRow = VariantCurrencyPrice::where('variant_id', $variantId)
            ->where('currency_code', $this->currency)
            ->first();

        $price = $priceRow?->price ?? 0;

        $cart = $this->getCart();

        $merged = false;
        foreach ($cart as &$line) {
            if ($line['variant_id'] == $variantId) {
                $line['quantity'] += $quantity;
                $merged = true;
                break;
            }
        }
        unset($line);

        if (! $merged) {
            $existingIds = array_column($cart, 'id');
            $newId = empty($existingIds) ? 1 : (max($existingIds) + 1);

            $cart[] = [
                'id'         => $newId,
                'variant_id' => $variantRow->variant_id,
                'product_id' => $variantRow->product_id,
                'name'       => $variantRow->product_name,
                'image'      => $variantRow->image_url,
                'price'      => $price,
                'quantity'   => $quantity,
                'currency'   => $this->currency,
            ];
        }

        $this->saveCart($cart);

        return redirect()->route('cart.index');
    }

    public function update(Request $request, int $lineId)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        $cart = $this->getCart();

        foreach ($cart as $idx => &$line) {
            if ($line['id'] == $lineId) {
                $qty = $validated['quantity'];

                if ($qty <= 0) {
                    unset($cart[$idx]);
                } else {
                    $line['quantity'] = $qty;
                }

                break;
            }
        }
        unset($line);

        $cart = array_values($cart);
        $this->saveCart($cart);

        return redirect()->route('cart.index');
    }

    public function remove(int $lineId)
    {
        $cart = $this->getCart();

        foreach ($cart as $idx => $line) {
            if ($line['id'] == $lineId) {
                unset($cart[$idx]);
                break;
            }
        }

        $cart = array_values($cart);
        $this->saveCart($cart);

        return redirect()->route('cart.index');
    }

    public function clear()
    {
        $this->saveCart([]);

        return redirect()->route('cart.index');
    }
}
