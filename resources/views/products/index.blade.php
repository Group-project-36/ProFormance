@extends('layouts.app')

@section('content')
<div class="kb-products-page">
    <div class="kb-products-container">
        <div class="kb-products-header">
            <h1 class="kb-products-title">
                @if(request()->has('search') && !empty(request()->search))
                    Search Results for "{{ request()->search }}"
                @elseif(request()->has('category') && !empty(request()->category))
                    {{ \App\Models\Category::find(request()->category)->category_name ?? 'Category' }}
                @else
                    All Products
                @endif
            </h1>
            
            <div class="kb-products-results">
                <p class="kb-products-count">
                    Showing {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} of {{ $products->total() }} products
                </p>
                
                <div class="kb-products-sort">
                    <form method="GET" action="{{ route('products.index') }}" class="kb-sort-form">
                        @if(request()->has('search'))
                            <input type="hidden" name="search" value="{{ request()->search }}">
                        @endif
                        @if(request()->has('category'))
                            <input type="hidden" name="category" value="{{ request()->category }}">
                        @endif
                        
                        <label for="sort">Sort by:</label>
                        <select name="sort" id="sort" onchange="this.form.submit()">
                            <option value="newest" {{ request()->get('sort', 'newest') == 'newest' ? 'selected' : '' }}>
                                Newest First
                            </option>
                            <option value="price_low" {{ request()->get('sort') == 'price_low' ? 'selected' : '' }}>
                                Price: Low to High
                            </option>
                            <option value="price_high" {{ request()->get('sort') == 'price_high' ? 'selected' : '' }}>
                                Price: High to Low
                            </option>
                            <option value="name_asc" {{ request()->get('sort') == 'name_asc' ? 'selected' : '' }}>
                                Name: A to Z
                            </option>
                            <option value="name_desc" {{ request()->get('sort') == 'name_desc' ? 'selected' : '' }}>
                                Name: Z to A
                            </option>
                        </select>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="kb-products-layout">
            <aside class="kb-products-sidebar">
                <form method="GET" action="{{ route('products.index') }}" class="kb-filter-form">
                    @if(request()->has('search'))
                        <input type="hidden" name="search" value="{{ request()->search }}">
                    @endif
                    
                    <div class="kb-filter-section">
                        <h3 class="kb-filter-title">Categories</h3>
                        <div class="kb-filter-categories">
                            @foreach($categories as $category)
                                <label class="kb-filter-checkbox">
                                    <input type="checkbox" name="category[]" value="{{ $category->category_id }}"
                                        {{ in_array($category->category_id, (array)request()->category) ? 'checked' : '' }}
                                        onchange="this.form.submit()">
                                    <span>{{ $category->category_name }}</span>
                                    <span class="kb-filter-count">({{ $category->products_count ?? 0 }})</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="kb-filter-section">
                        <h3 class="kb-filter-title">Brands</h3>
                        <div class="kb-filter-brands">
                            @foreach($brands as $brand)
                                <label class="kb-filter-checkbox">
                                    <input type="checkbox" name="brand[]" value="{{ $brand->brand_id }}"
                                        {{ in_array($brand->brand_id, (array)request()->brand) ? 'checked' : '' }}
                                        onchange="this.form.submit()">
                                    <span>{{ $brand->brand_name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="kb-filter-section">
                        <h3 class="kb-filter-title">Price Range</h3>
                        <div class="kb-filter-price">
                            <div class="kb-price-inputs">
                                <input type="number" name="min_price" placeholder="Min" 
                                    value="{{ request()->min_price }}" min="0" step="0.01">
                                <span>to</span>
                                <input type="number" name="max_price" placeholder="Max" 
                                    value="{{ request()->max_price }}" min="0" step="0.01">
                            </div>
                            @if($priceRange)
                                <div class="kb-price-range">
                                    <span>£{{ number_format($priceRange->min_price, 2) }}</span>
                                    <span>£{{ number_format($priceRange->max_price, 2) }}</span>
                                </div>
                            @endif
                            <button type="submit" class="kb-filter-btn">Apply Price Filter</button>
                        </div>
                    </div>
                    
                    <div class="kb-filter-actions">
                        <a href="{{ route('products.index') }}" class="kb-clear-filters">Clear All Filters</a>
                    </div>
                </form>
            </aside>
            <main class="kb-products-main">

                @if ($products->count() > 0)
                    <div class="kb-products-grid">
                        @foreach($products as $product)
                            @php
                                // Get best price for this product
                                $bestPrice = $product->bestPriceForCurrency();

                                // Get primary image (first variant image)
                                $primaryImage = $product->primaryImage()->first();
                                $imagePath = $primaryImage
                                    ? 'images/' . $primaryImage->image_url
                                    : 'images/placeholders/product-placeholder.jpg';

                                // Get first variant
                                $variant = $product->variants()->first() ?? null;
                            @endphp

                            <article class="kb-product-card">
                                <div class="kb-product-card-media">

                                    @if($product->created_at && $product->created_at->gt(now()->subDays(30)))
                                        <span class="kb-product-badge kb-product-badge--new">New</span>
                                    @endif

                                    @if($bestPrice && $bestPrice->was && $bestPrice->was > $bestPrice->price)
                                        <span class="kb-product-badge kb-product-badge--sale">Sale</span>
                                    @endif

                                    <a href="{{ route('products.show', $product->product_id) }}">
                                        <img
                                            src="{{ asset($imagePath) }}"
                                            alt="{{ $product->product_name }}"
                                            loading="lazy"
                                            onerror="this.src='{{ asset('images/placeholders/product-placeholder.jpg') }}'">
                                    </a>
                                </div>

                                <div class="kb-product-card-body">
                                    <h3 class="kb-product-card-title">
                                        <a href="{{ route('products.show', $product->product_id) }}">
                                            {{ $product->product_name }}
                                        </a>
                                    </h3>

                                    @if($product->brand)
                                        <p class="kb-product-card-brand">
                                            {{ $product->brand->brand_name }}
                                        </p>
                                    @endif

                                    <div class="kb-product-card-prices">
                                        @if($bestPrice && $bestPrice->was && $bestPrice->was > $bestPrice->price)
                                            <span class="kb-product-card-old">
                                                {{ $bestPrice->symbol ?? '£' }}{{ number_format($bestPrice->was, 2) }}
                                            </span>
                                        @endif

                                        <span class="kb-product-card-current">
                                            @if($bestPrice && $bestPrice->price)
                                                {{ $bestPrice->symbol ?? '£' }}{{ number_format($bestPrice->price, 2) }}
                                            @else
                                                {{ $bestPrice->symbol ?? '£' }}0.00
                                            @endif
                                        </span>
                                    </div>

                                    <div class="kb-product-card-actions">
                                        @if($variant)
                                            <form method="POST" action="{{ route('cart.add') }}">
                                                @csrf
                                                <input type="hidden" name="variant_id" value="{{ $variant->variant_id }}">
                                                <input type="number" name="quantity" value="1" min="1" style="width:60px;">
                                                <button type="submit">Add to Basket</button>
                                            </form>
                                        @else
                                            <button type="button" class="kb-add-to-cart-btn" disabled title="No variant available">
                                                Add to Basket
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </article>

                        @endforeach
                    </div>

                @else
                    <div class="kb-no-products">
                        <div class="kb-no-products-icon">
                            <i class="bi bi-search"></i>
                        </div>

                        <h3>No products found</h3>

                        <p>
                            @if(request()->has('search'))
                                We couldn't find any products matching "{{ request()->search }}".
                            @else
                                No products match your filters. Try adjusting your search criteria.
                            @endif
                        </p>

                        <a href="{{ route('products.index') }}" class="kb-back-to-products">
                            Back to All Products
                        </a>
                    </div>

                @endif
                @if ($products->hasPages())
                    <div class="kb-products-pagination">
                        {{ $products->appends(request()->query())->links('vendor.pagination.kb-products') }}
                    </div>
                @endif

            </main>
            

            
        </div>
    </div>
</div>
@endsection
