<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with([
                'activeVariants',
                'activeVariants.prices',
                'activeVariants.images',
                'categories',
                'brand'
            ])
            ->where('is_active', true);
        
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('product_name', 'like', "%{$searchTerm}%")
                  ->orWhere('product_description', 'like', "%{$searchTerm}%")
                  ->orWhere('short_description', 'like', "%{$searchTerm}%");
            });
        }
        
        if ($request->filled('category')) {
            $input = (array) $request->category;
            $idInputs   = array_filter($input, fn ($c) => is_numeric($c));
            $slugInputs = array_filter($input, fn ($c) => !is_numeric($c));
            $baseCategoryIds = Category::query()
                ->when($idInputs, fn ($q) => $q->whereIn('category_id', $idInputs))
                ->when($slugInputs, fn ($q) => $q->orWhereIn('slug', $slugInputs))
                ->pluck('category_id')
                ->all();

            if (!empty($baseCategoryIds)) {
                $allCategoryIds = Category::query()
                    ->whereIn('category_id', $baseCategoryIds)
                    ->orWhereIn('parent_id', $baseCategoryIds)
                    ->pluck('category_id')
                    ->all();

                $query->whereHas('categories', function ($q) use ($allCategoryIds) {
                    $q->whereIn('categories.category_id', $allCategoryIds);
                });
            }
        }

        
        if ($request->has('brand') && !empty($request->brand)) {
            $query->whereHas('brand', function($q) use ($request) {
                $q->where('brand_id', $request->brand)
                  ->orWhere('slug', $request->brand);
            });
        }
        
        if ($request->has('min_price') || $request->has('max_price')) {
            $query->whereHas('activeVariants.prices', function($q) use ($request) {
                if ($request->min_price) {
                    $q->where('price_penny', '>=', $request->min_price * 100);
                }
                if ($request->max_price) {
                    $q->where('price_penny', '<=', $request->max_price * 100);
                }
            });
        }
        
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'price_low':
                $query->join('product_variants as pv', 'products.product_id', '=', 'pv.product_id')
                      ->join('variant_currency_prices as vcp', 'pv.variant_id', '=', 'vcp.variant_id')
                      ->where('pv.is_active', true)
                      ->orderBy('vcp.price_penny', 'asc')
                      ->select('products.*')
                      ->distinct();
                break;
            case 'price_high':
                $query->join('product_variants as pv', 'products.product_id', '=', 'pv.product_id')
                      ->join('variant_currency_prices as vcp', 'pv.variant_id', '=', 'vcp.variant_id')
                      ->where('pv.is_active', true)
                      ->orderBy('vcp.price_penny', 'desc')
                      ->select('products.*')
                      ->distinct();
                break;
            case 'name_asc':
                $query->orderBy('product_name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('product_name', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }
        
        $perPage = $request->get('per_page', 12);
        $products = $query->paginate($perPage)->appends($request->query());
        $categories = Category::where('is_active', true)->get();
        $brands = Brand::where('is_active', true)->get();
        
        $priceRange = DB::table('variant_currency_prices')
            ->join('product_variants', 'variant_currency_prices.variant_id', '=', 'product_variants.variant_id')
            ->join('products', 'product_variants.product_id', '=', 'products.product_id')
            ->where('products.is_active', true)
            ->where('product_variants.is_active', true)
            ->select(
                DB::raw('MIN(variant_currency_prices.price_penny) / 100 as min_price'),
                DB::raw('MAX(variant_currency_prices.price_penny) / 100 as max_price')
            )
            ->first();
        
        return view('products.index', compact(
            'products', 
            'categories', 
            'brands',
            'priceRange'
        ));
    }
    
    public function show(Product $product): View
    {

        $bestPrice = $product->bestPriceForCurrency();

        $primaryImage = $product->primaryImage()->first();
        $imagePath = $primaryImage
            ? 'images/' . $primaryImage->image_url
            : 'images/placeholders/product-placeholder.jpg';

        $variant = $product->variants()->first();

        return view('products.show', compact(
            'product',
            'bestPrice',
            'imagePath',
            'variant'
        ));
    }
}