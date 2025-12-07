<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $currency = 'GBP';

        $products = DB::table('products as p')
            ->join('product_variants as pv', 'pv.product_id', '=', 'p.product_id')
            ->join('variant_currency_prices as vcp', function ($join) use ($currency) {
                $join->on('vcp.variant_id', '=', 'pv.variant_id')
                     ->where('vcp.currency_code', '=', $currency);
            })
            ->leftJoin('product_images as pi', 'pi.variant_id', '=', 'pv.variant_id')
            ->select(
                'p.product_id',
                'p.product_name',
                'pv.variant_id',
                'vcp.price_penny',
                'pi.image_url'
            )
            ->where('p.is_active', 1)
            ->where('pv.is_active', 1)
            ->groupBy('p.product_id', 'p.product_name', 'pv.variant_id', 'vcp.price_penny', 'pi.image_url')
            ->orderBy('p.product_id')
            ->get();

        $heroProducts = Product::with([
                'activeVariants',
                'activeVariants.prices',
                'activeVariants.images',
            ])
            ->where('is_active', true)
            ->orderByDesc('created_at')
            ->take(4)
            ->get();

        $newProducts = Product::with([
                'activeVariants',
                'activeVariants.prices',
                'activeVariants.images',
            ])
            ->where('is_active', true)
            ->orderByDesc('created_at')
            ->take(8)
            ->get();

        $bestPrice = null;
        if ($newProducts->isNotEmpty()) {
            $firstProduct = $newProducts->first();

            if (method_exists($firstProduct, 'bestPriceForCurrency')) {
                $bestPrice = $firstProduct->bestPriceForCurrency($currency);
            }
        }

        return view('home', [
            'products'     => $products,
            'heroProducts' => $heroProducts,
            'newProducts'  => $newProducts,
            'bestPrice'    => $bestPrice,
        ]);
    }
}
