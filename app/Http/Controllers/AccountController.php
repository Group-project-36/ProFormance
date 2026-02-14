<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Address;
use App\Models\Review;
use App\Models\Wishlist;

class AccountController extends Controller
{
    /**
     * Account dashboard — overview page with summary stats.
     */
    public function dashboard()
    {
        $user = Auth::user();

        $recentOrders = Order::where('user_id', $user->user_id)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        $totalOrders   = Order::where('user_id', $user->user_id)->count();
        $addressCount  = Address::where('user_id', $user->user_id)->count();
        $reviewCount   = Review::where('user_id', $user->user_id)->count();

        // Count wishlist items across all user's wishlists
        $wishlistCount = 0;
        $wishlists = Wishlist::where('user_id', $user->user_id)->with('items')->get();
        foreach ($wishlists as $wl) {
            $wishlistCount += $wl->items->count();
        }

        return view('account.dashboard', compact(
            'user', 'recentOrders', 'totalOrders',
            'wishlistCount', 'reviewCount', 'addressCount'
        ));
    }

    /**
     * Show profile edit form.
     */
    public function profile()
    {
        $user = Auth::user();
        return view('account.profile', compact('user'));
    }

    /**
     * Update profile details.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'first_name'         => 'required|string|max:100',
            'last_name'          => 'required|string|max:100',
            'company_name'       => 'nullable|string|max:255',
            'country_phone_code' => 'required|integer',
            'phone_number'       => 'required|string|max:20',
        ]);

        $user->update($data);

        return redirect()->route('account.profile')
            ->with('success', 'Profile updated successfully.');
    }
}
