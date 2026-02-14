<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;

class AccountReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::where('user_id', Auth::user()->user_id)
            ->with('product')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('account.reviews', compact('reviews'));
    }

    public function update(Request $request, $reviewId)
    {
        $review = Review::where('user_id', Auth::user()->user_id)
            ->where('review_id', $reviewId)
            ->firstOrFail();

        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title'  => 'nullable|string|max:200',
            'body'   => 'nullable|string|max:2000',
        ]);

        $review->update($data);

        return redirect()->route('account.reviews')
            ->with('success', 'Review updated.');
    }

    public function destroy($reviewId)
    {
        Review::where('user_id', Auth::user()->user_id)
            ->where('review_id', $reviewId)
            ->firstOrFail()
            ->delete();

        return redirect()->route('account.reviews')
            ->with('success', 'Review deleted.');
    }
}
