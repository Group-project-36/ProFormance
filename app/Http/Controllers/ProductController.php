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
        $currency = strtoupper(session('currency', 'GBP'));
        $vatRate  = strtoupper($currency) === 'GBP' ? 0.20 : 0.00;
        $vatLabel = $vatRate > 0 ? 'incl. VAT' : 'excl. VAT';
        $toPennies = function ($value) {
            if ($value === null) return null;

            $value = trim((string) $value);
            if ($value === '') return null;

            $value = preg_replace('/[^\d.]/', '', $value);
            if ($value === '') return null;

            [$pounds, $pence] = array_pad(explode('.', $value, 2), 2, '0');
            $pence = substr(str_pad($pence, 2, '0'), 0, 2);

            return ((int) $pounds * 100) + (int) $pence;
        };

        $query = Product::with([
                'activeVariants',
                'activeVariants.prices',
                'activeVariants.images',
                'categories',
                'brand'
            ])
            ->where('is_active', true);


        if ($request->filled('search')) {
            $searchTerm = trim($request->input('search'));

            $query->where(function ($q) use ($searchTerm) {
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

        
        if ($request->filled('brand')) {
            $input = (array) $request->brand;

            $idInputs   = array_filter($input, fn($b) => is_numeric($b));
            $slugInputs = array_filter($input, fn($b) => !is_numeric($b));

            $brandIds = Brand::query()
                ->when($idInputs, fn($q) => $q->whereIn('brand_id', $idInputs))
                ->when($slugInputs, fn($q) => $q->orWhereIn('slug', $slugInputs))
                ->pluck('brand_id')
                ->all();

            if (!empty($brandIds)) {
                $query->whereIn('brand_id', $brandIds);
            }
        }

        if ($request->boolean('deals')) {
            $currency = strtoupper(session('currency', 'GBP'));

            $query->whereHas('activeVariants.prices', function ($q) use ($currency) {
                $q->where('currency_code', $currency)
                ->whereNotNull('was_price_penny')
                ->whereColumn('was_price_penny', '>', 'price_penny')
                ->whereRaw('price_penny = (
                    SELECT MIN(vcp2.price_penny)
                    FROM product_variants pv2
                    JOIN variant_currency_prices vcp2 ON vcp2.variant_id = pv2.variant_id
                    WHERE pv2.product_id = products.product_id
                        AND pv2.is_active = 1
                        AND vcp2.currency_code = ?
                )', [$currency]);
            });
        }



        
        $minPennies = $toPennies($request->input('min_price'));
        $maxPennies = $toPennies($request->input('max_price'));

        if ($minPennies !== null || $maxPennies !== null) {
            $query->whereHas('activeVariants.prices', function ($q) use ($currency, $minPennies, $maxPennies) {
                $q->where('currency_code', $currency);

                if ($minPennies !== null) {
                    $q->where('price_penny', '>=', $minPennies);
                }
                if ($maxPennies !== null) {
                    $q->where('price_penny', '<=', $maxPennies);
                }
            });
        }



        $pageTitle = 'All Products';

        if ($request->filled('search')) {
            $pageTitle = 'Search Results for "'.$request->search.'"';
        } elseif ($request->boolean('deals')) {
            $pageTitle = 'Hot Deals';
        } elseif ($request->filled('category')) {
            $selected = (array) $request->category;

            if (count($selected) === 1) {
                $one = Category::query()
                    ->where('category_id', $selected[0])
                    ->orWhere('slug', $selected[0])
                    ->first();

                $pageTitle = $one?->category_name ?? 'Category';
            } else {
                $pageTitle = 'Selected Categories';
            }
        }


        
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'price_low':
            case 'price_high':
                $direction = $sort === 'price_low' ? 'asc' : 'desc';

                $query->addSelect([
                    'min_price_penny' => DB::table('product_variants as pv')
                        ->join('variant_currency_prices as vcp', 'pv.variant_id', '=', 'vcp.variant_id')
                        ->whereColumn('pv.product_id', 'products.product_id')
                        ->where('pv.is_active', true)
                        ->where('vcp.currency_code', $currency)
                        ->selectRaw('MIN(vcp.price_penny)')
                ])->orderBy('min_price_penny', $direction);

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
        $currency = strtoupper(session('currency', 'GBP'));

        $mainCategories = Category::query()
            ->with(['children' => function ($q) {
                $q->where('is_active', true)->orderBy('sort_order');
            }])
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // helper to apply the same filters to a Product query
        $applyCountFilters = function ($countQuery) use ($request, $currency, $toPennies) {

            // currency + price filters
            $minPennies = $toPennies($request->input('min_price'));
            $maxPennies = $toPennies($request->input('max_price'));

            $countQuery->whereHas('activeVariants.prices', function ($q) use ($currency, $minPennies, $maxPennies) {
                $q->where('currency_code', $currency);

                if ($minPennies !== null) $q->where('price_penny', '>=', $minPennies);
                if ($maxPennies !== null) $q->where('price_penny', '<=', $maxPennies);
            });

            // search
            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $countQuery->where(function ($q) use ($searchTerm) {
                    $q->where('product_name', 'like', "%{$searchTerm}%")
                    ->orWhere('product_description', 'like', "%{$searchTerm}%")
                    ->orWhere('short_description', 'like', "%{$searchTerm}%");
                });
            }

            // brands (multi-select)
            if ($request->filled('brand')) {
                $input = (array) $request->brand;
                $idInputs   = array_filter($input, fn ($b) => is_numeric($b));
                $slugInputs = array_filter($input, fn ($b) => !is_numeric($b));

                $brandIds = Brand::query()
                    ->when($idInputs, fn ($q) => $q->whereIn('brand_id', $idInputs))
                    ->when($slugInputs, fn ($q) => $q->orWhereIn('slug', $slugInputs))
                    ->pluck('brand_id')
                    ->all();

                if (!empty($brandIds)) {
                    $countQuery->whereIn('brand_id', $brandIds);
                }
            }

            return $countQuery;
        };

        $mainCategories->each(function ($parent) use ($applyCountFilters) {

            // count for each child
            $parent->children->each(function ($child) use ($applyCountFilters) {
                $countQuery = Product::query()
                    ->where('products.is_active', true)
                    ->whereHas('categories', fn ($q) => $q->where('categories.category_id', $child->category_id));

                $child->products_count = $applyCountFilters($countQuery)
                    ->distinct('products.product_id')
                    ->count('products.product_id');
            });

            // parent total = sum of children counts
            $parent->products_total = (int) $parent->children->sum('products_count');
        });

        $brands = Brand::where('is_active', true)->get();
        
        $priceRange = DB::table('variant_currency_prices')
            ->join('product_variants', 'variant_currency_prices.variant_id', '=', 'product_variants.variant_id')
            ->join('products', 'product_variants.product_id', '=', 'products.product_id')
            ->where('products.is_active', true)
            ->where('product_variants.is_active', true)
            ->where('variant_currency_prices.currency_code', $currency)
            ->select(
                DB::raw('MIN(variant_currency_prices.price_penny) / 100 as min_price'),
                DB::raw('MAX(variant_currency_prices.price_penny) / 100 as max_price')
            )
            ->first();

        
        return view('products.index', compact(
            'products', 
            'mainCategories', 
            'brands',
            'priceRange',
            'pageTitle',
            'vatRate',
            'vatLabel'
        ));
    }

    private function displayVatRateForCurrency(string $currency): float
    {
        return strtoupper($currency) === 'GBP' ? 0.20 : 0.00;
    }

    private function priceForDisplayPenny(int $netPenny, float $vatRate): int
    {
        return (int) round($netPenny * (1 + $vatRate));
    }

    
    public function show(Product $product): View
    {

       $currency = strtoupper(session('currency', 'GBP'));
        $vatRate  = $currency === 'GBP' ? 0.20 : 0.00;
        $vatLabel = $vatRate > 0 ? 'incl. VAT' : 'excl. VAT';

        $bestPrice = $product->bestPriceForCurrency($currency); // if your method accepts currency
        $bestPriceDisplay = ($bestPrice && isset($bestPrice->price))
            ? $bestPrice->price * (1 + $vatRate)
            : null;


        $primaryImage = $product->primaryImage()->first();
        $imagePath = $primaryImage
            ? 'images/' . $primaryImage->image_url
            : 'images/placeholders/product-placeholder.jpg';

        $variant = $product->variants()->first();

        return view('products.show', compact(
            'product',
            'bestPrice',
            'bestPriceDisplay',
            'vatRate',
            'vatLabel',
            'imagePath',
            'variant'
        ));
    }   

    public function category(Request $request, string $slug)
    {
        $category = Category::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        
        $request->merge([
            'category' => [$category->category_id],
        ]);

        return $this->index($request);
    }


}