<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;

class ReviewController extends Controller
{
    public function store(Request $request, $productId)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Please sign in to leave a review.');
        }

        $user = Auth::user();

        $existing = Review::where('user_id', $user->user_id)
            ->where('product_id', $productId)
            ->first();

        if ($existing) {
            return back()->withErrors(['review' => 'You have already reviewed this product.']);
        }

        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title'  => 'nullable|string|max:200',
            'body'   => 'nullable|string|max:2000',
        ]);

        Review::create([
            'user_id'     => $user->user_id,
            'product_id'  => $productId,
            'rating'      => $data['rating'],
            'title'       => $data['title'],
            'body'        => $data['body'],
            'is_approved' => true,
        ]);

        return back()->with('success', 'Thank you for your review!');
    }
}
