<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Review;
use App\Models\Wishlist;
use App\Models\WishlistItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        $applyCountFilters = function ($countQuery) use ($request, $currency, $toPennies) {

            $minPennies = $toPennies($request->input('min_price'));
            $maxPennies = $toPennies($request->input('max_price'));

            $countQuery->whereHas('activeVariants.prices', function ($q) use ($currency, $minPennies, $maxPennies) {
                $q->where('currency_code', $currency);

                if ($minPennies !== null) $q->where('price_penny', '>=', $minPennies);
                if ($maxPennies !== null) $q->where('price_penny', '<=', $maxPennies);
            });

            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $countQuery->where(function ($q) use ($searchTerm) {
                    $q->where('product_name', 'like', "%{$searchTerm}%")
                    ->orWhere('product_description', 'like', "%{$searchTerm}%")
                    ->orWhere('short_description', 'like', "%{$searchTerm}%");
                });
            }

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
            $parent->children->each(function ($child) use ($applyCountFilters) {
                $countQuery = Product::query()
                    ->where('products.is_active', true)
                    ->whereHas('categories', fn ($q) => $q->where('categories.category_id', $child->category_id));

                $child->products_count = $applyCountFilters($countQuery)
                    ->distinct('products.product_id')
                    ->count('products.product_id');
            });

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

        // Wishlist variant IDs for current user
        $wishlistVariantIds = [];
        if (Auth::check()) {
            $wl = Wishlist::where('user_id', Auth::user()->user_id)->first();
            if ($wl) {
                $wishlistVariantIds = WishlistItem::where('wishlists_id', $wl->wishlist_id)
                    ->pluck('variant_id')
                    ->all();
            }
        }

        return view('products.index', compact(
            'products', 
            'mainCategories', 
            'brands',
            'priceRange',
            'pageTitle',
            'vatRate',
            'vatLabel',
            'wishlistVariantIds'
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

        $product->load([
            'activeVariants.options.attribute',
            'activeVariants.images',
            'activeVariants.prices',
            'activeVariants.inventory',
            'brand',
            'categories',
        ]);

        $variants = $product->activeVariants;
        $defaultVariant = $variants->first();
        $bestPrice = $product->bestPriceForCurrency($currency);

        $attributeMap = [];
        foreach ($variants as $variant) {
            foreach ($variant->options as $option) {
                $attrName = $option->attribute->display_name;
                $attrId   = $option->attribute->attribute_id;
                $optValue = $option->display_value;
                $optId    = $option->option_id;

                if (!isset($attributeMap[$attrId])) {
                    $attributeMap[$attrId] = [
                        'name'    => $attrName,
                        'order'   => $option->attribute->selection_order,
                        'options' => [],
                    ];
                }

                if (!isset($attributeMap[$attrId]['options'][$optId])) {
                    $attributeMap[$attrId]['options'][$optId] = [
                        'value'      => $optValue,
                        'sort_order' => $option->sort_order,
                    ];
                }
            }
        }

        uasort($attributeMap, fn($a, $b) => $a['order'] <=> $b['order']);
        foreach ($attributeMap as &$attr) {
            uasort($attr['options'], fn($a, $b) => $a['sort_order'] <=> $b['sort_order']);
        }
        unset($attr);

        $variantsJson = $variants->map(function ($v) use ($currency, $vatRate) {
            $price = $v->priceForCurrency($currency);
            $inv   = $v->inventory;
            $stock = $inv ? max(0, $inv->available_stock - $inv->stock_allocated) : 0;

            return [
                'id'         => $v->variant_id,
                'sku'        => $v->sku,
                'title'      => $v->title,
                'stock'      => $stock,
                'options'    => $v->options->pluck('option_id')->toArray(),
                'price'      => $price ? round(($price->price_penny / 100) * (1 + $vatRate), 2) : null,
                'was_price'  => ($price && $price->was_price_penny) ? round(($price->was_price_penny / 100) * (1 + $vatRate), 2) : null,
                'images'     => $v->images->sortBy('sort_order')->map(fn($img) => [
                    'url' => $img->image_url,
                    'alt' => $img->alt_text ?? $v->title,
                ])->values()->toArray(),
            ];
        })->values()->toArray();

        $allImages = $variants->flatMap(function ($v) {
            return $v->images->map(fn($img) => [
                'url'        => $img->image_url,
                'alt'        => $img->alt_text ?? 'Product image',
                'variant_id' => $v->variant_id,
                'sort_order' => $img->sort_order,
            ]);
        })->sortBy('sort_order')->values()->toArray();

        if (empty($allImages)) {
            $allImages = [['url' => 'placeholders/product-placeholder.jpg', 'alt' => 'No image available', 'variant_id' => null, 'sort_order' => 0]];
        }

        $specifications = DB::table('product_specifications')
            ->where('product_id', $product->product_id)
            ->orderBy('sort_order')
            ->get()
            ->groupBy('spec_group');
        $defaultStock = 0;
        if ($defaultVariant && $defaultVariant->inventory) {
            $defaultStock = max(0, $defaultVariant->inventory->available_stock - $defaultVariant->inventory->stock_allocated);
        }

        $reviews = Review::where('product_id', $product->product_id)
            ->where('is_approved', true)
            ->with('user')
            ->orderByDesc('created_at')
            ->get();

        $reviewStats = [
            'count' => $reviews->count(),
            'avg'   => $reviews->count() > 0 ? round($reviews->avg('rating'), 1) : 0,
        ];

        $userReview = null;
        if (Auth::check()) {
            $userReview = Review::where('user_id', Auth::user()->user_id)
                ->where('product_id', $product->product_id)
                ->first();
        }
        $inWishlist = false;
        if (Auth::check() && $defaultVariant) {
            $wishlist = Wishlist::where('user_id', Auth::user()->user_id)->first();
            if ($wishlist) {
                $inWishlist = WishlistItem::where('wishlists_id', $wishlist->wishlist_id)
                    ->where('variant_id', $defaultVariant->variant_id)
                    ->exists();
            }
        }

        return view('products.show', compact(
            'product',
            'bestPrice',
            'vatRate',
            'vatLabel',
            'variants',
            'defaultVariant',
            'attributeMap',
            'variantsJson',
            'allImages',
            'specifications',
            'defaultStock',
            'currency',
            'reviews',
            'reviewStats',
            'userReview',
            'inWishlist'
        ));
    }

    public function getVariant(Request $request)
    {
        $request->validate([
            'variant_id' => 'required|integer|exists:product_variants,variant_id',
        ]);

        $currency = strtoupper(session('currency', 'GBP'));
        $vatRate  = $currency === 'GBP' ? 0.20 : 0.00;

        $variant = ProductVariant::with(['images', 'prices', 'inventory', 'options'])->find($request->variant_id);
        $price   = $variant->priceForCurrency($currency);
        $inv     = $variant->inventory;
        $stock   = $inv ? max(0, $inv->available_stock - $inv->stock_allocated) : 0;

        return response()->json([
            'id'        => $variant->variant_id,
            'sku'       => $variant->sku,
            'title'     => $variant->title,
            'stock'     => $stock,
            'price'     => $price ? round(($price->price_penny / 100) * (1 + $vatRate), 2) : null,
            'was_price' => ($price && $price->was_price_penny) ? round(($price->was_price_penny / 100) * (1 + $vatRate), 2) : null,
            'images'    => $variant->images->sortBy('sort_order')->map(fn($img) => [
                'url' => $img->image_url,
                'alt' => $img->alt_text ?? $variant->title,
            ])->values()->toArray(),
        ]);
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
