@extends('layouts.app')

@section('content')
<div class="kb-pdp">
    <div class="kb-pdp-container">

        {{-- ── Breadcrumb ── --}}
        <nav class="kb-pdp-breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <i class="bi bi-chevron-right"></i>
            <a href="{{ route('products.index') }}">Products</a>
            @if($product->categories->first())
                <i class="bi bi-chevron-right"></i>
                <a href="{{ route('products.category', $product->categories->first()->slug) }}">
                    {{ $product->categories->first()->category_name }}
                </a>
            @endif
            <i class="bi bi-chevron-right"></i>
            <span>{{ $product->product_name }}</span>
        </nav>

        {{-- ══════════════════════════════════════════════
             TOP SECTION: Images + Product Info
        ══════════════════════════════════════════════ --}}
        <div class="kb-pdp-top">

            {{-- ── Left: Image Gallery ── --}}
            <div class="kb-pdp-gallery">
                <div class="kb-pdp-thumbs" id="pdp-thumbs">
                    @foreach($allImages as $i => $img)
                        <button class="kb-pdp-thumb {{ $i === 0 ? 'active' : '' }}"
                                data-index="{{ $i }}"
                                data-variant="{{ $img['variant_id'] }}"
                                onclick="setMainImage({{ $i }})">
                            <img src="{{ asset('images/' . $img['url']) }}" alt="{{ $img['alt'] }}"
                                 onerror="this.src='{{ asset('images/placeholders/product-placeholder.jpg') }}'">
                        </button>
                    @endforeach
                </div>

                <div class="kb-pdp-main-image" id="pdp-main-image">
                    <img src="{{ asset('images/' . $allImages[0]['url']) }}"
                         alt="{{ $allImages[0]['alt'] }}"
                         id="pdp-main-img"
                         onerror="this.src='{{ asset('images/placeholders/product-placeholder.jpg') }}'">
                </div>
            </div>

            {{-- ── Right: Product Info ── --}}
            <div class="kb-pdp-info">

                @if($product->brand)
                    <a href="{{ route('products.index', ['brand' => $product->brand->slug]) }}" class="kb-pdp-brand">
                        {{ $product->brand->brand_name }}
                    </a>
                @endif

                <h1 class="kb-pdp-title">{{ $product->product_name }}</h1>

                {{-- Rating summary (from existing reviews) --}}
                @if($reviewStats['count'] > 0)
                    <a href="#reviews" class="kb-pdp-rating">
                        <span class="kb-pdp-stars">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= floor($reviewStats['avg']))
                                    <i class="bi bi-star-fill"></i>
                                @elseif($i - $reviewStats['avg'] < 1 && $i - $reviewStats['avg'] > 0)
                                    <i class="bi bi-star-half"></i>
                                @else
                                    <i class="bi bi-star"></i>
                                @endif
                            @endfor
                        </span>
                        <span class="kb-pdp-review-count">
                            {{ number_format($reviewStats['avg'], 1) }} ({{ $reviewStats['count'] }} {{ $reviewStats['count'] === 1 ? 'review' : 'reviews' }})
                        </span>
                    </a>
                @endif

                {{-- Price --}}
                @php
                    $currentPrice = ($bestPrice && $bestPrice->price) ? $bestPrice->price * (1 + $vatRate) : 0;
                    $oldPrice     = ($bestPrice && $bestPrice->was && $bestPrice->was > $bestPrice->price)
                                  ? $bestPrice->was * (1 + $vatRate) : null;
                    $symbol       = $bestPrice->symbol ?? '£';
                @endphp

                <div class="kb-pdp-price-block">
                    @if($oldPrice)
                        <span class="kb-pdp-price-was" id="pdp-price-was">{{ $symbol }}{{ number_format($oldPrice, 2) }}</span>
                    @endif
                    <span class="kb-pdp-price-now" id="pdp-price-now">{{ $symbol }}{{ number_format($currentPrice, 2) }}</span>
                    @if($oldPrice)
                        @php $savings = round((1 - $currentPrice / $oldPrice) * 100); @endphp
                        <span class="kb-pdp-savings" id="pdp-savings">Save {{ $savings }}%</span>
                    @endif
                    <small class="kb-pdp-vat">{{ $vatLabel }}</small>
                </div>

                {{-- Stock status --}}
                <div class="kb-pdp-stock" id="pdp-stock">
                    @if($defaultStock > 0)
                        <span class="kb-pdp-stock-in">
                            <i class="bi bi-check-circle-fill"></i>
                            In Stock{{ $defaultStock <= 5 ? " — only {$defaultStock} left" : '' }}
                        </span>
                    @else
                        <span class="kb-pdp-stock-out">
                            <i class="bi bi-x-circle-fill"></i>
                            Out of Stock
                        </span>
                    @endif
                </div>

                {{-- ── Variant Selectors ── --}}
                @if(count($attributeMap) > 0)
                    <div class="kb-pdp-variants" id="pdp-variants">
                        @foreach($attributeMap as $attrId => $attr)
                            <div class="kb-pdp-variant-group" data-attribute-id="{{ $attrId }}">
                                <label class="kb-pdp-variant-label">
                                    {{ $attr['name'] }}:
                                    <strong id="pdp-selected-{{ $attrId }}">
                                        {{ collect($attr['options'])->first()['value'] ?? '' }}
                                    </strong>
                                </label>
                                <div class="kb-pdp-variant-options">
                                    @foreach($attr['options'] as $optId => $opt)
                                        <button type="button"
                                                class="kb-pdp-opt {{ $loop->first ? 'active' : '' }}"
                                                data-attribute-id="{{ $attrId }}"
                                                data-option-id="{{ $optId }}"
                                                data-value="{{ $opt['value'] }}"
                                                onclick="selectOption(this)">
                                            {{ $opt['value'] }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- ── Add to Cart / Notify Me / Wishlist ── --}}
                <div class="kb-pdp-actions" id="pdp-actions">

                    {{-- Add to Cart (shown when in stock) --}}
                    <form method="POST" action="{{ route('cart.add') }}" class="kb-pdp-cart-form" id="pdp-cart-form"
                          style="{{ $defaultStock <= 0 ? 'display:none;' : '' }}">
                        @csrf
                        <input type="hidden" name="variant_id" id="pdp-variant-id"
                               value="{{ $defaultVariant->variant_id ?? '' }}">

                        <div class="kb-pdp-qty-row">
                            <label class="kb-pdp-qty-label">Qty</label>
                            <div class="kb-pdp-qty-control">
                                <button type="button" class="kb-pdp-qty-btn" onclick="adjustQty(-1)">−</button>
                                <input type="number" name="quantity" id="pdp-qty" value="1" min="1"
                                       max="{{ $defaultStock }}" class="kb-pdp-qty-input">
                                <button type="button" class="kb-pdp-qty-btn" onclick="adjustQty(1)">+</button>
                            </div>
                        </div>

                        <button type="submit" class="kb-pdp-add-btn">
                            <i class="bi bi-bag-plus"></i>
                            Add to Basket
                        </button>
                    </form>

                    {{-- Wishlist button (existing) --}}
                    @if($defaultVariant)
                        <button type="button"
                                class="kb-wishlist-btn {{ $inWishlist ? 'kb-wishlisted' : '' }}"
                                id="pdp-wishlist-btn"
                                data-variant-id="{{ $defaultVariant->variant_id }}"
                                onclick="toggleWishlist(this)"
                                title="{{ $inWishlist ? 'Remove from wishlist' : 'Add to wishlist' }}">
                            <i class="bi {{ $inWishlist ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                            <span>{{ $inWishlist ? 'In Wishlist' : 'Add to Wishlist' }}</span>
                        </button>
                    @endif

                    {{-- Notify Me (shown when out of stock) --}}
                    <div class="kb-pdp-notify" id="pdp-notify-block"
                         style="{{ $defaultStock > 0 ? 'display:none;' : '' }}">
                        <p class="kb-pdp-notify-text">
                            <i class="bi bi-bell"></i>
                            Get notified when this item is back in stock
                        </p>
                        <form class="kb-pdp-notify-form" id="pdp-notify-form">
                            @csrf
                            <input type="hidden" name="variant_id" id="pdp-notify-variant-id"
                                   value="{{ $defaultVariant->variant_id ?? '' }}">
                            <input type="email" name="email" class="kb-pdp-notify-email"
                                   placeholder="Enter your email" required
                                   value="{{ auth()->check() ? auth()->user()->loginDetail->email_address ?? '' : '' }}">
                            <button type="submit" class="kb-pdp-notify-btn">Notify Me</button>
                        </form>
                        <p class="kb-pdp-notify-success" id="pdp-notify-success" style="display:none;">
                            <i class="bi bi-check-circle-fill"></i>
                            We'll email you when it's back!
                        </p>
                    </div>
                </div>

                {{-- Short Description --}}
                @if(!empty($product->short_description))
                    <div class="kb-pdp-short-desc">
                        <p>{{ $product->short_description }}</p>
                    </div>
                @endif

                {{-- SKU / Category --}}
                <div class="kb-pdp-meta">
                    @if($defaultVariant)
                        <span>SKU: <strong id="pdp-sku">{{ $defaultVariant->sku }}</strong></span>
                    @endif
                    @if($product->categories->first())
                        <span>Category: <a href="{{ route('products.category', $product->categories->first()->slug) }}">
                            {{ $product->categories->first()->category_name }}
                        </a></span>
                    @endif
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════
             TABS: Description / Specs
        ══════════════════════════════════════════════ --}}
        <div class="kb-pdp-bottom">
            <div class="kb-pdp-tabs">
                <button class="kb-pdp-tab active" data-tab="description" onclick="switchTab(this)">Description</button>
                @if($specifications->count() > 0)
                    <button class="kb-pdp-tab" data-tab="specifications" onclick="switchTab(this)">Specifications</button>
                @endif
            </div>

            <div class="kb-pdp-tab-content active" id="tab-description">
                <div class="kb-pdp-description">
                    {!! nl2br(e($product->description)) !!}
                </div>
            </div>

            @if($specifications->count() > 0)
                <div class="kb-pdp-tab-content" id="tab-specifications">
                    @foreach($specifications as $group => $specs)
                        <table class="kb-pdp-specs-table">
                            @if($group)
                                <thead>
                                    <tr><th colspan="2">{{ $group }}</th></tr>
                                </thead>
                            @endif
                            <tbody>
                                @foreach($specs as $spec)
                                    <tr>
                                        <td class="kb-pdp-spec-name">{{ $spec->spec_name }}</td>
                                        <td class="kb-pdp-spec-value">{{ $spec->spec_value }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- ══════════════════════════════════════════════
             REVIEWS SECTION (existing, unchanged)
        ══════════════════════════════════════════════ --}}
        <div class="kb-reviews-section" id="reviews">
            <h2 class="kb-reviews-title">Customer Reviews</h2>

            @if($reviewStats['count'] > 0)
                <div class="kb-reviews-overview">
                    <div class="kb-reviews-avg">
                        <span class="kb-reviews-avg-num">{{ number_format($reviewStats['avg'], 1) }}</span>
                        <div class="kb-stars-display">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= floor($reviewStats['avg']))
                                    <i class="bi bi-star-fill"></i>
                                @elseif($i - $reviewStats['avg'] < 1 && $i - $reviewStats['avg'] > 0)
                                    <i class="bi bi-star-half"></i>
                                @else
                                    <i class="bi bi-star"></i>
                                @endif
                            @endfor
                        </div>
                        <span class="kb-reviews-count">Based on {{ $reviewStats['count'] }} {{ $reviewStats['count'] === 1 ? 'review' : 'reviews' }}</span>
                    </div>

                    <div class="kb-reviews-breakdown">
                        @for($star = 5; $star >= 1; $star--)
                            @php
                                $starCount = $reviews->where('rating', $star)->count();
                                $pct = $reviewStats['count'] > 0 ? ($starCount / $reviewStats['count']) * 100 : 0;
                            @endphp
                            <div class="kb-reviews-bar-row">
                                <span class="kb-reviews-bar-label">{{ $star }} <i class="bi bi-star-fill"></i></span>
                                <div class="kb-reviews-bar-track">
                                    <div class="kb-reviews-bar-fill" style="width: {{ $pct }}%"></div>
                                </div>
                                <span class="kb-reviews-bar-count">{{ $starCount }}</span>
                            </div>
                        @endfor
                    </div>
                </div>
            @endif

            @auth
                @if(!$userReview)
                    <div class="kb-review-form-card">
                        <h3>Write a Review</h3>

                        @if($errors->has('review'))
                            <div class="kb-form-error" style="margin-bottom: 1rem;">{{ $errors->first('review') }}</div>
                        @endif

                        <form method="POST" action="{{ route('reviews.store', $product->product_id) }}">
                            @csrf

                            <div class="kb-form-group">
                                <label class="kb-form-label">Your Rating</label>
                                <div class="kb-star-rating-input">
                                    @for($i = 5; $i >= 1; $i--)
                                        <input type="radio" name="rating" value="{{ $i }}" id="star-{{ $i }}"
                                               {{ old('rating') == $i ? 'checked' : '' }}>
                                        <label for="star-{{ $i }}" title="{{ $i }} stars">
                                            <i class="bi bi-star-fill"></i>
                                        </label>
                                    @endfor
                                </div>
                                @error('rating') <div class="kb-form-error">{{ $message }}</div> @enderror
                            </div>

                            <div class="kb-form-group">
                                <label class="kb-form-label">Title <span class="kb-form-optional">(optional)</span></label>
                                <input type="text" name="title" class="kb-form-input"
                                       value="{{ old('title') }}" placeholder="Summarise your experience">
                            </div>

                            <div class="kb-form-group">
                                <label class="kb-form-label">Your Review</label>
                                <textarea name="body" class="kb-form-input kb-form-textarea" rows="4"
                                          placeholder="What did you like or dislike?">{{ old('body') }}</textarea>
                            </div>

                            <button type="submit" class="kb-account-btn kb-account-btn-primary">Submit Review</button>
                        </form>
                    </div>
                @else
                    <div class="kb-review-already">
                        <i class="bi bi-check-circle"></i>
                        <span>You've already reviewed this product. <a href="{{ route('account.reviews') }}">Edit it from your account</a>.</span>
                    </div>
                @endif
            @else
                <div class="kb-review-login-prompt">
                    <a href="{{ route('login') }}">Sign in</a> to leave a review.
                </div>
            @endauth

            @if($reviews->isNotEmpty())
                <div class="kb-reviews-list">
                    @foreach($reviews as $review)
                        <div class="kb-review-card">
                            <div class="kb-review-card-header">
                                <div class="kb-review-card-user">
                                    <i class="bi bi-person-circle"></i>
                                    <span class="kb-review-card-name">{{ $review->user->first_name ?? 'Anonymous' }} {{ substr($review->user->last_name ?? '', 0, 1) }}.</span>
                                </div>
                                <span class="kb-review-card-date">{{ $review->created_at->format('d M Y') }}</span>
                            </div>

                            <div class="kb-stars-display kb-stars-sm">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi {{ $i <= $review->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                                @endfor
                            </div>

                            @if($review->title)
                                <h4 class="kb-review-card-title">{{ $review->title }}</h4>
                            @endif

                            @if($review->body)
                                <p class="kb-review-card-body">{{ $review->body }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="kb-reviews-empty">
                    <i class="bi bi-chat-square-text"></i>
                    <p>No reviews yet. Be the first to review this product!</p>
                </div>
            @endif
        </div>

    </div>
</div>

<script>
// ── Variant data from server ──
const variants    = @json($variantsJson);
const allImages   = @json($allImages);
const symbol      = '{{ $symbol }}';
let   currentImg  = 0;
let   selectedOpts = {};

// Initialise with first option of each attribute
document.querySelectorAll('.kb-pdp-variant-group').forEach(group => {
    const firstBtn = group.querySelector('.kb-pdp-opt.active');
    if (firstBtn) {
        selectedOpts[firstBtn.dataset.attributeId] = parseInt(firstBtn.dataset.optionId);
    }
});

// ── Image Gallery ──
function setMainImage(index) {
    currentImg = index;
    const img = allImages[index];
    document.getElementById('pdp-main-img').src = '{{ asset("images") }}/' + img.url;
    document.getElementById('pdp-main-img').alt = img.alt;
    document.querySelectorAll('.kb-pdp-thumb').forEach((t, i) => {
        t.classList.toggle('active', i === index);
    });
}

// ── Variant Selection ──
function selectOption(btn) {
    const attrId = btn.dataset.attributeId;
    const optId  = parseInt(btn.dataset.optionId);
    const value  = btn.dataset.value;

    btn.closest('.kb-pdp-variant-options').querySelectorAll('.kb-pdp-opt').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    const label = document.getElementById('pdp-selected-' + attrId);
    if (label) label.textContent = value;

    selectedOpts[attrId] = optId;

    const match = findVariant();
    if (match) updateProductForVariant(match);
}

function findVariant() {
    const selectedIds = Object.values(selectedOpts).sort((a, b) => a - b);

    for (const v of variants) {
        const vOpts = [...v.options].sort((a, b) => a - b);
        if (vOpts.length === selectedIds.length && vOpts.every((val, i) => val === selectedIds[i])) {
            return v;
        }
    }

    let bestMatch = null, bestScore = 0;
    for (const v of variants) {
        const score = Object.values(selectedOpts).filter(id => v.options.includes(id)).length;
        if (score > bestScore) { bestScore = score; bestMatch = v; }
    }
    return bestMatch;
}

function updateProductForVariant(v) {
    document.getElementById('pdp-variant-id').value = v.id;
    document.getElementById('pdp-notify-variant-id').value = v.id;

    // Update wishlist button variant id
    const wlBtn = document.getElementById('pdp-wishlist-btn');
    if (wlBtn) wlBtn.dataset.variantId = v.id;

    // Price
    if (v.price !== null) {
        document.getElementById('pdp-price-now').textContent = symbol + v.price.toFixed(2);

        const wasEl = document.getElementById('pdp-price-was');
        const saveEl = document.getElementById('pdp-savings');

        if (v.was_price && v.was_price > v.price) {
            if (wasEl) { wasEl.textContent = symbol + v.was_price.toFixed(2); wasEl.style.display = ''; }
            if (saveEl) {
                saveEl.textContent = 'Save ' + Math.round((1 - v.price / v.was_price) * 100) + '%';
                saveEl.style.display = '';
            }
        } else {
            if (wasEl) wasEl.style.display = 'none';
            if (saveEl) saveEl.style.display = 'none';
        }
    }

    // Stock
    const stockEl = document.getElementById('pdp-stock');
    const cartForm = document.getElementById('pdp-cart-form');
    const notifyBlock = document.getElementById('pdp-notify-block');

    if (v.stock > 0) {
        const lowStock = v.stock <= 5 ? ` — only ${v.stock} left` : '';
        stockEl.innerHTML = `<span class="kb-pdp-stock-in"><i class="bi bi-check-circle-fill"></i> In Stock${lowStock}</span>`;
        cartForm.style.display = '';
        notifyBlock.style.display = 'none';
        document.getElementById('pdp-qty').max = v.stock;
        document.getElementById('pdp-qty').value = 1;
    } else {
        stockEl.innerHTML = '<span class="kb-pdp-stock-out"><i class="bi bi-x-circle-fill"></i> Out of Stock</span>';
        cartForm.style.display = 'none';
        notifyBlock.style.display = '';
    }

    // SKU
    const skuEl = document.getElementById('pdp-sku');
    if (skuEl) skuEl.textContent = v.sku;

    // Swap to this variant's images
    if (v.images && v.images.length > 0) {
        const firstIdx = allImages.findIndex(img => img.variant_id === v.id);
        if (firstIdx >= 0) setMainImage(firstIdx);
    }
}

// ── Quantity ──
function adjustQty(delta) {
    const input = document.getElementById('pdp-qty');
    input.value = Math.max(1, Math.min(parseInt(input.max) || 99, parseInt(input.value) + delta));
}

// ── Tabs ──
function switchTab(btn) {
    document.querySelectorAll('.kb-pdp-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.kb-pdp-tab-content').forEach(c => c.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('tab-' + btn.dataset.tab).classList.add('active');
}

// ── Notify Me (AJAX) ──
document.getElementById('pdp-notify-form')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);

    fetch('{{ route("stock.notify") }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
        body: formData,
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            this.style.display = 'none';
            document.getElementById('pdp-notify-success').style.display = 'flex';
        } else {
            alert(data.message || 'Something went wrong.');
        }
    })
    .catch(() => alert('Could not submit. Please try again.'));
});

// ── Wishlist toggle (existing) ──
function toggleWishlist(btn) {
    const variantId = btn.dataset.variantId;
    const icon = btn.querySelector('i');
    const text = btn.querySelector('span');

    fetch('{{ route("wishlist.toggle") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify({ variant_id: variantId }),
    })
    .then(res => {
        if (res.status === 401) { window.location.href = '{{ route("login") }}'; return; }
        return res.json();
    })
    .then(data => {
        if (!data) return;
        if (data.in_wishlist) {
            btn.classList.add('kb-wishlisted');
            icon.className = 'bi bi-heart-fill';
            if (text) text.textContent = 'In Wishlist';
        } else {
            btn.classList.remove('kb-wishlisted');
            icon.className = 'bi bi-heart';
            if (text) text.textContent = 'Add to Wishlist';
        }
    })
    .catch(err => console.error('Wishlist error:', err));
}
</script>
@endsection
