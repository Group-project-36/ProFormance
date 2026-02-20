@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/wishlist-reviews.css') }}">

<div class="kb-product-page">
    <div class="kb-product-page-container">
        <div class="kb-product-page-card">
            <div class="kb-product-page-layout">
                {{-- Left: image --}}
                <div class="kb-product-page-media">
                    <img
                        src="{{ asset($imagePath) }}"
                        alt="{{ $product->product_name }}"
                        loading="lazy"
                        onerror="this.src='{{ asset('images/placeholders/product-placeholder.jpg') }}'">
                </div>

                <div class="kb-product-page-info">
                    <h1 class="kb-product-page-title">
                        {{ $product->product_name }}
                    </h1>

                    @if($product->brand)
                        <p class="kb-product-page-brand">
                            {{ $product->brand->brand_name }}
                        </p>
                    @endif

                    {{-- RATING SUMMARY --}}
                    @if($reviewStats['count'] > 0)
                        <a href="#reviews" class="kb-product-rating-summary">
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
                            <span class="kb-rating-text">
                                {{ number_format($reviewStats['avg'], 1) }} ({{ $reviewStats['count'] }} {{ $reviewStats['count'] === 1 ? 'review' : 'reviews' }})
                            </span>
                        </a>
                    @endif

                    @php
                        $vatRate = ($vatRate ?? 0);
                        $symbol  = $bestPrice->symbol ?? '£';

                        $current = ($bestPrice && $bestPrice->price)
                            ? $bestPrice->price * (1 + $vatRate)
                            : 0;

                        $old = ($bestPrice && $bestPrice->was && $bestPrice->was > $bestPrice->price)
                            ? $bestPrice->was * (1 + $vatRate)
                            : null;
                    @endphp

                    <div class="kb-product-page-prices">
                        @if($old)
                            <span class="kb-product-page-price-old">
                                {{ $symbol }}{{ number_format($old, 2) }}
                            </span>
                        @endif

                        <span class="kb-product-page-price-current">
                            {{ $symbol }}{{ number_format($current, 2) }}
                        </span>

                        <div class="kb-product-page-vat-note">
                            <small class="text-muted">{{ $vatLabel ?? '' }}</small>
                        </div>
                    </div>

                    <div class="kb-product-page-actions">
                        @if($variant)
                            <form method="POST" action="{{ route('cart.add') }}" class="kb-product-page-form">
                                @csrf
                                <input type="hidden" name="variant_id" value="{{ $variant->variant_id }}">
                                <label class="kb-product-page-qty">
                                    <span>Quantity</span>
                                    <input type="number" name="quantity" value="1" min="1">
                                </label>
                                <button type="submit" class="kb-product-page-add-btn">
                                    Add to Basket
                                </button>
                            </form>

                            {{-- WISHLIST BUTTON --}}
                            <button type="button"
                                    class="kb-wishlist-btn {{ $inWishlist ? 'kb-wishlisted' : '' }}"
                                    data-variant-id="{{ $variant->variant_id }}"
                                    onclick="toggleWishlist(this)"
                                    title="{{ $inWishlist ? 'Remove from wishlist' : 'Add to wishlist' }}">
                                <i class="bi {{ $inWishlist ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                                <span>{{ $inWishlist ? 'In Wishlist' : 'Add to Wishlist' }}</span>
                            </button>
                        @else
                            <button type="button" class="kb-product-page-add-btn kb-product-page-add-btn-disabled" disabled>
                                Not available
                            </button>
                        @endif
                    </div>

                    @if(!empty($product->description))
                        <div class="kb-product-page-description">
                            <h2>Description</h2>
                            <p>
                                {!! nl2br(e($product->description)) !!}
                            </p>
                        </div>
                    @endif

                </div>
            </div>
        </div>

        {{-- ============ REVIEWS SECTION ============ --}}
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

            {{-- WRITE A REVIEW --}}
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

            {{-- REVIEWS LIST --}}
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

{{-- Wishlist AJAX --}}
<script>
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
