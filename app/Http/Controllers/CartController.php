<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\VariantCurrencyPrice;

class CartController extends Controller
{
    protected function currency(): string
    {
        return session('currency', 'gbp');
    }

    protected function repriceCart(array $cart): array
    {
        $currency = $this->currency();

        $variantIds = array_values(array_unique(array_column($cart, 'variant_id')));
        if (empty($variantIds)) return $cart;

        // Pull prices + currency symbol in one go
        $rows = DB::table('variant_currency_prices as vcp')
            ->join('currencies as c', 'c.currency_code', '=', 'vcp.currency_code')
            ->whereIn('vcp.variant_id', $variantIds)
            ->where('vcp.currency_code', $currency)
            ->select('vcp.variant_id', 'vcp.price_penny', 'vcp.was_price_penny', 'c.symbol')
            ->get()
            ->keyBy('variant_id');

        foreach ($cart as &$line) {
            $row = $rows[$line['variant_id']] ?? null;

            $line['currency'] = $currency;
            $line['symbol'] = $row->symbol ?? '£';

            // Convert pennies → decimal
            $line['price'] = $row ? ($row->price_penny / 100) : 0;
            $line['was']   = ($row && $row->was_price_penny) ? ($row->was_price_penny / 100) : null;
        }
        unset($line);

        $this->saveCart($cart);
        return $cart;
    }


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
        $cart = $this->repriceCart($cart);
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
            ->where('currency_code', $this->currency())
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
                'image'      => $variantRow->image_url ? ('images/' . $variantRow->image_url) : null,
                'price'      => $price,
                'quantity'   => $quantity,
                'currency'   => $this->currency(),
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
        $cart = $this->repriceCart($cart);

        [$total, $totalItems] = $this->computeTotals($cart);

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'total' => $total,
                'totalItems' => $totalItems,
            ]);
        }

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

    public function preview()
    {
        $cart = $this->getCart();
        $cart = $this->repriceCart($cart);
        [$total, $totalItems] = $this->computeTotals($cart);

        // Only send what the mini preview needs
        $lines = array_map(function ($line) {
            return [
                'id'       => $line['id'],
                'name'     => $line['name'] ?? '',
                'image'    => $line['image'] ?? null,
                'price'    => (float)($line['price'] ?? 0),
                'quantity' => (int)($line['quantity'] ?? 0),
                'symbol'   => $line['symbol'] ?? '£',
            ];
        }, $cart);

        return response()->json([
            'ok'         => true,
            'total'      => (float)$total,
            'totalItems' => (int)$totalItems,
            'currency'   => $cart[0]['currency'] ?? $this->currency(),
            'symbol'     => $cart[0]['symbol'] ?? '£',
            'lines'      => $lines,
        ]);
    }

}
