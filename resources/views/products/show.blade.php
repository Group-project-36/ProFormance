@extends('layouts.app')

@section('content')
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
    </div>
</div>
@endsection
