@extends('layouts.app')
@section('content')
    <section class="kb-hero">
        <div class="kb-hero-inner">
            <div class="kb-hero-slider">
                <article class="kb-hero-slide kb-hero-slide--deal is-active">
                <div class="kb-hero-slide-content">
                    <h2 class="kb-hero-title">Today's Top Deals</h2>
                    <p class="kb-hero-subtitle">Don't miss these exclusive offers</p>

                    <div class="kb-hero-products">
                        @forelse ($heroProducts as $product)
                            @php
                                // Get best price for this product
                                $bestPrice = $product->bestPriceForCurrency();

                                // Get primary image (first image of first variant)
                                $primaryImage = $product->primaryImage()->first();
                                $imagePath = $primaryImage
                                    ? 'images/' . $primaryImage->image_url
                                    : 'images/placeholders/product-placeholder.jpg';
                            @endphp

                            <article class="kb-product-card kb-product-card--hero">
                                <a href="{{ route('products.index') }}" class="kb-product-card-link">
                                    <div class="kb-product-media">
                                        <img
                                            src="{{ asset($imagePath) }}"
                                            alt="{{ $product->product_name }}"
                                            loading="lazy"
                                        >
                                    </div>

                                    <div class="kb-product-body">
                                        <h3 class="kb-product-name">
                                            {{ $product->product_name }}
                                        </h3>

                                        @if($bestPrice)
                                            <div class="kb-product-prices">
                                                @if($bestPrice->was && $bestPrice->was > $bestPrice->price)
                                                    <span class="kb-product-old">
                                                        {{ $bestPrice->symbol }}{{ number_format($bestPrice->was, 2) }}
                                                    </span>
                                                @endif
                                                <span class="kb-product-new">
                                                    {{ $bestPrice->symbol }}{{ number_format($bestPrice->price, 2) }}
                                                </span>
                                            </div>
                                        @else
                                            <div class="kb-product-prices">
                                                <span class="kb-product-new">Price unavailable</span>
                                            </div>
                                        @endif
                                    </div>
                                </a>
                            </article>
                        @empty
                            <p class="kb-hero-empty">No featured products yet. Check back soon.</p>
                        @endforelse
                    </div>
                    <a href="{{ route('products.index') }}">
                        <button class="kb-hero-cta">View All Deals</button>
                    </a>
                </div>
            </article>


                <article class="kb-hero-slide kb-hero-slide--promises">
                    <div class="kb-hero-slide-content">
                        <h2 class="kb-hero-title">Why Choose Us</h2>
                        <div class="kb-hero-promises">
                            <div class="kb-hero-promise">
                                <span class="kb-hero-promise-icon"><i class="bi bi-truck"></i></span>
                                <h3>Free shipping</h3>
                                <p>On qualifying orders over £50 in the UK.</p>
                            </div>
                            <div class="kb-hero-promise">
                                <span class="kb-hero-promise-icon"><i class="bi bi-arrow-counterclockwise"></i></span>
                                <h3>Easy returns</h3>
                                <p>30-day hassle-free return & replacement.</p>
                            </div>
                            <div class="kb-hero-promise">
                                <span class="kb-hero-promise-icon"><i class="bi bi-chat-right-text"></i></span>
                                <h3>Dedicated support</h3>
                                <p>Real humans, 7 days a week.</p>
                            </div>
                        </div>
                    </div>
                </article>

                <article class="kb-hero-slide kb-hero-slide--new">
                    <div class="kb-hero-slide-content">
                        <div class="kb-hero-col kb-hero-col-text">
                            <h2 class="kb-hero-title">Get ready for what's next</h2>
                            <p class="kb-hero-subtitle">
                                Fresh drops in gym wear, limited edition flavours, and collabs landing soon.
                            </p>
                            <button class="kb-hero-cta">Discover upcoming releases</button>
                        </div>
                    </div>
                </article>
            </div>

            <button class="kb-hero-nav kb-hero-nav--prev" aria-label="Previous slide">‹</button>
            <button class="kb-hero-nav kb-hero-nav--next" aria-label="Next slide">›</button>

            <div class="kb-hero-dots">
                <button class="kb-hero-dot is-active" data-slide="0"></button>
                <button class="kb-hero-dot" data-slide="1"></button>
                <button class="kb-hero-dot" data-slide="2"></button>
            </div>
        </div>
    </section>
    <section class="kb-section kb-new-products">
        <div class="kb-new-products-inner">
            <div class="kb-section-header">
                <h2 class="kb-section-title">New Arrivals</h2>
                <div class="kb-section-controls">
                    <button class="kb-carousel-btn kb-carousel-btn--prev" aria-label="Previous products">‹</button>
                    <button class="kb-carousel-btn kb-carousel-btn--next" aria-label="Next products">›</button>
                </div>
            </div>
            <!-- <form method="POST" action="{{ route('cart.add') }}" style="padding: 2rem; border: 1px solid #ccc;">
                @csrf

                <h2>Test Add to Cart</h2>

                <input type="hidden" name="product_id" value="123">
                <input type="hidden" name="name" value="Debug Product">
                <input type="hidden" name="price" value="12.34">
                <input type="hidden" name="variant_name" value="">
                <input type="hidden" name="image" value="">

                <label>
                    Quantity:
                    <input type="number" name="quantity" value="1" min="1">
                </label>

                <button type="submit">Add debug product to cart</button>
            </form> -->


            <div class="kb-new-carousel-container">
                <div class="kb-new-carousel-track">
                    @forelse ($newProducts as $product)
                        @php
                            // Get best price for this product
                            $bestPrice = $product->bestPriceForCurrency();

                            // Get primary image (first image of first variant)
                            $primaryImage = $product->primaryImage()->first();
                            $imagePath = $primaryImage
                                ? 'images/' . $primaryImage->image_url
                                : 'images/placeholders/product-placeholder.jpg';

                            // Debug this product
                            // dd($bestPrice); // Uncomment to see price structure
                        @endphp
                        @php
                            // Get best price for this product
                            $bestPrice = $product->bestPriceForCurrency();

                            // Get primary image (first image of first variant)
                            $primaryImage = $product->primaryImage()->first();
                            $imagePath = $primaryImage
                                ? 'images/' . $primaryImage->image_url
                                : 'images/placeholders/product-placeholder.jpg';

                            // Pick a variant to add to cart (e.g. first active variant)
                            $variant = $product->activeVariants->first();
                        @endphp

                        <article class="kb-new-card">
                            <div class="kb-new-card-media">
                                {{-- New badge: if created in last 30 days --}}
                                @if($product->created_at && $product->created_at->gt(now()->subDays(30)))
                                    <span class="kb-new-badge kb-new-badge--new">New</span>
                                @endif

                                {{-- Sale badge: if has old price --}}
                                @if($bestPrice && isset($bestPrice->was) && $bestPrice->was > $bestPrice->price)
                                    <span class="kb-new-badge kb-new-badge--sale">Sale</span>
                                @endif

                                <a href="{{ route('products.index') }}">
                                    @if($primaryImage)
                                        <img
                                            src="{{ asset($imagePath) }}"
                                            alt="{{ $product->product_name }}"
                                            loading="lazy"
                                            onerror="this.src='{{ asset('images/placeholders/product-placeholder.jpg') }}'"
                                        >
                                    @else
                                        <img
                                            src="{{ asset('images/placeholders/product-placeholder.jpg') }}"
                                            alt="{{ $product->product_name }}"
                                            loading="lazy"
                                        >
                                    @endif
                                </a>
                            </div>

                            <div class="kb-new-card-body">
                                <h3 class="kb-new-card-title">
                                    <a href="{{ route('products.index') }}">
                                        {{ $product->product_name }}
                                    </a>
                                </h3>

                                <div class="kb-new-card-prices">
                                    @if($bestPrice && isset($bestPrice->was) && $bestPrice->was > $bestPrice->price)
                                        <span class="kb-new-price-old">
                                            {{ $bestPrice->symbol }}{{ number_format($bestPrice->was, 2) }}
                                        </span>
                                    @endif

                                    <span class="kb-new-price-current">
                                        @if($bestPrice && isset($bestPrice->price))
                                            {{ $bestPrice->symbol }}{{ number_format($bestPrice->price, 2) }}
                                        @else
                                            £0.00
                                        @endif
                                    </span>
                                </div>
                            </div>
                            <div class="kb-new-card-actions">
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

                                <button
                                    type="button"
                                    class="kb-new-icon-btn"
                                    aria-label="Add to wishlist"
                                >
                                    <i class="bi bi-heart"></i>
                                </button>

                                <a
                                    href="{{ route('products.index') }}"
                                    class="kb-new-icon-btn"
                                    aria-label="View product"
                                >
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </article>
                    @empty
                        <div class="kb-no-products">
                            <p>No new products yet. Check back soon.</p>
                        </div>
                    @endforelse
                </div>

                <div class="kb-carousel-dots">

                </div>
            </div>

            <div class="kb-view-all-container">
                <a href="{{ route('products.index') }}" class="kb-view-all-btn">
                    View All New Products
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>

    <section class="kb-section kb-category-section">
        <div class="kb-category-inner">
            <h2 class="kb-section-title">Shop by Category</h2>

            <div class="kb-category-grid">
                <a href="{{ route('products.category', 'unisex-apparel') }}" class="kb-category-card">
                    <div class="kb-category-media">
                        <img src="{{ asset('images/categories/apparel.jpeg') }}" alt="Unisex Apparel">
                        <div class="kb-category-overlay">
                            <span class="kb-category-name">Unisex Apparel</span>
                            <span class="kb-category-cta">Shop clothing</span>
                        </div>
                    </div>
                </a>

                <a href="{{ route('products.category', 'performance-nutrition') }}" class="kb-category-card">
                    <div class="kb-category-media">
                        <img src="{{ asset('images/categories/nutrition.jpg') }}" alt="Performance Nutrition">
                        <div class="kb-category-overlay">
                            <span class="kb-category-name">Performance Nutrition</span>
                            <span class="kb-category-cta">Shop protein &amp; more</span>
                        </div>
                    </div>
                </a>

                <a href="{{ route('products.category', 'advanced-supplements') }}" class="kb-category-card">
                    <div class="kb-category-media">
                        <img src="{{ asset('images/categories/supplements.jpg') }}" alt="Advanced Supplements">
                        <div class="kb-category-overlay">
                            <span class="kb-category-name">Advanced Supplements</span>
                            <span class="kb-category-cta">Shop pre-workout, creatine</span>
                        </div>
                    </div>
                </a>

                <a href="{{ route('products.category', 'gym-accessories') }}" class="kb-category-card">
                    <div class="kb-category-media">
                        <img src="{{ asset('images/categories/accessories.jpg') }}" alt="Gym Accessories">
                        <div class="kb-category-overlay">
                            <span class="kb-category-name">Gym Accessories</span>
                            <span class="kb-category-cta">Shop gloves, belts &amp; more</span>
                        </div>
                    </div>
                </a>

                <a href="{{ route('products.category', 'bags-equipments') }}" class="kb-category-card">
                    <div class="kb-category-media">
                        <img src="{{ asset('images/categories/equipment.jpg') }}" alt="Bags & Equipment">
                        <div class="kb-category-overlay">
                            <span class="kb-category-name">Bags &amp; Equipment</span>
                            <span class="kb-category-cta">Shop bags &amp; home gym</span>
                        </div>
                    </div>
                </a>

                <a href="#" class="kb-category-card">
                    <div class="kb-category-media">
                        <img src="{{ asset('images/categories/deals.png') }}" alt="Deals">
                        <div class="kb-category-overlay">
                            <span class="kb-category-name">Deals</span>
                            <span class="kb-category-cta">Save on top picks</span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </section>
    <section class="kb-section kb-testimonials">
        <div class="kb-testimonials-inner">
            <div class="kb-testimonials-header">
                <h2 class="kb-section-title">What our customers say</h2>
                <p class="kb-testimonials-subtitle">
                    Real feedback from people training, lifting and recovering with ProFormance.
                </p>
            </div>

            <div class="kb-testimonials-grid">
                <article class="kb-testimonial-card">
                    <div class="kb-testimonial-rating">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                    </div>

                    <p class="kb-testimonial-text">
                        “Best protein I’ve tried so far. Mixes well, tastes great and doesn’t upset my stomach.
                        Delivery was fast too.”
                    </p>

                    <div class="kb-testimonial-footer">
                        <div class="kb-testimonial-avatar">
                            <span>J</span>
                        </div>
                        <div class="kb-testimonial-meta">
                            <span class="kb-testimonial-name">James F.</span>
                            <span class="kb-testimonial-role">Verified buyer · Performance Nutrition</span>
                        </div>
                    </div>
                </article>

                <article class="kb-testimonial-card">
                    <div class="kb-testimonial-rating">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-half"></i>
                    </div>

                    <p class="kb-testimonial-text">
                        “Love the gym wear – fits well, doesn’t ride up during squats and the fabric feels premium
                        without the crazy price tag.”
                    </p>

                    <div class="kb-testimonial-footer">
                        <div class="kb-testimonial-avatar">
                            <span>S</span>
                        </div>
                        <div class="kb-testimonial-meta">
                            <span class="kb-testimonial-name">Sophia R.</span>
                            <span class="kb-testimonial-role">Verified buyer · Unisex Apparel</span>
                        </div>
                    </div>
                </article>

                <article class="kb-testimonial-card">
                    <div class="kb-testimonial-rating">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                    </div>

                    <p class="kb-testimonial-text">
                        “Customer support was super helpful when I ordered the wrong size. Got an easy exchange and
                        my new gear arrived in two days.”
                    </p>

                    <div class="kb-testimonial-footer">
                        <div class="kb-testimonial-avatar">
                            <span>L</span>
                        </div>
                        <div class="kb-testimonial-meta">
                            <span class="kb-testimonial-name">Liam H.</span>
                            <span class="kb-testimonial-role">Verified buyer · Accessories</span>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </section>
@endsection
