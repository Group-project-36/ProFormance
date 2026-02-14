{{-- resources/views/account/reviews.blade.php --}}
@extends('account.layout')

@section('account-content')
<div class="kb-account-section">
    <h1 class="kb-account-title">My Reviews</h1>
    <p class="kb-account-subtitle">Manage reviews you've left on products.</p>
</div>

@if($reviews->isEmpty())
    <div class="kb-account-empty">
        <i class="bi bi-star"></i>
        <p>You haven't written any reviews yet.</p>
        <a href="{{ route('products.index') }}" class="kb-account-btn kb-account-btn-primary">Browse Products</a>
    </div>
@else
    <div class="kb-account-reviews-list">
        @foreach($reviews as $review)
            <div class="kb-account-card kb-review-card">
                <div class="kb-review-header">
                    <div>
                        <a href="{{ route('products.show', $review->product->product_id ?? '#') }}" class="kb-review-product-name">
                            {{ $review->product->product_name ?? 'Unknown Product' }}
                        </a>
                        <div class="kb-review-stars">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi {{ $i <= $review->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                            @endfor
                            <span class="kb-review-date">{{ $review->created_at->format('d M Y') }}</span>
                        </div>
                    </div>
                    <div class="kb-review-actions">
                        <button type="button" class="kb-icon-btn" title="Edit"
                                onclick="toggleReviewEdit({{ $review->review_id }})">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <form method="POST" action="{{ route('account.reviews.destroy', $review->review_id) }}"
                              onsubmit="return confirm('Delete this review?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="kb-icon-btn kb-icon-btn-danger" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>

                @if($review->title)
                    <h3 class="kb-review-title">{{ $review->title }}</h3>
                @endif

                @if($review->body)
                    <p class="kb-review-body">{{ $review->body }}</p>
                @endif

                @if(!$review->is_approved)
                    <span class="kb-badge kb-badge-pending">Pending Approval</span>
                @endif

                {{-- INLINE EDIT FORM --}}
                <div id="edit-review-{{ $review->review_id }}" class="kb-review-edit-form" style="display: none;">
                    <form method="POST" action="{{ route('account.reviews.update', $review->review_id) }}">
                        @csrf
                        @method('PUT')

                        <div class="kb-form-group">
                            <label class="kb-form-label">Rating</label>
                            <select name="rating" class="kb-form-input">
                                @for($i = 5; $i >= 1; $i--)
                                    <option value="{{ $i }}" {{ $review->rating == $i ? 'selected' : '' }}>
                                        {{ $i }} {{ str_repeat('★', $i) }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <div class="kb-form-group">
                            <label class="kb-form-label">Title <span class="kb-form-optional">(optional)</span></label>
                            <input type="text" name="title" class="kb-form-input" value="{{ $review->title }}">
                        </div>

                        <div class="kb-form-group">
                            <label class="kb-form-label">Review</label>
                            <textarea name="body" class="kb-form-input kb-form-textarea" rows="4">{{ $review->body }}</textarea>
                        </div>

                        <div class="kb-form-actions">
                            <button type="submit" class="kb-account-btn kb-account-btn-primary">Update Review</button>
                            <button type="button" class="kb-account-btn kb-account-btn-outline"
                                    onclick="toggleReviewEdit({{ $review->review_id }})">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    <div class="kb-account-pagination">
        {{ $reviews->links() }}
    </div>
@endif

@endsection

@section('scripts')
<script>
    function toggleReviewEdit(id) {
        const form = document.getElementById('edit-review-' + id);
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
    }
</script>
@endsection
