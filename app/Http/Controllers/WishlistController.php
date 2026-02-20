<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Wishlist;
use App\Models\WishlistItem;
use App\Models\Address;

class WishlistController extends Controller
{
    /**
     * Show all user wishlists (overview).
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Please sign in to view your wishlists.');
        }

        $user = Auth::user();

        $wishlists = Wishlist::where('user_id', $user->user_id)
            ->withCount('items')
            ->orderByDesc('created_at')
            ->get();

        return view('wishlist.index', compact('wishlists'));
    }

    /**
     * Show a single wishlist's items.
     */
    public function show($wishlistId)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $wishlist = Wishlist::where('user_id', Auth::user()->user_id)
            ->where('wishlist_id', $wishlistId)
            ->firstOrFail();

        $items = WishlistItem::where('wishlists_id', $wishlist->wishlist_id)
            ->with(['variant.product.brand', 'variant.images', 'variant.prices'])
            ->orderByDesc('created_at')
            ->get();

        $addresses = Address::where('user_id', Auth::user()->user_id)->get();

        $shareUrl = $wishlist->getShareUrl();

        return view('wishlist.show', compact('wishlist', 'items', 'addresses', 'shareUrl'));
    }

    /**
     * Create a new wishlist.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'wishlist_name' => 'required|string|max:100',
            'slug'          => 'nullable|string|max:150',
        ]);

        $slug = !empty($data['slug'])
            ? Wishlist::generateUniqueSlug($data['slug'])
            : Wishlist::generateUniqueSlug($data['wishlist_name']);

        Wishlist::create([
            'user_id'       => Auth::user()->user_id,
            'wishlist_name' => $data['wishlist_name'],
            'is_public'     => false,
            'slug'          => $slug,
        ]);

        return back()->with('success', 'Wishlist created!');
    }

    /**
     * Update wishlist settings (name, slug, public, delivery address).
     */
    public function update(Request $request, $wishlistId)
    {
        $wishlist = Wishlist::where('user_id', Auth::user()->user_id)
            ->where('wishlist_id', $wishlistId)
            ->firstOrFail();

        $data = $request->validate([
            'wishlist_name'       => 'required|string|max:100',
            'slug'                => 'nullable|string|max:150',
            'is_public'           => 'sometimes|boolean',
            'delivery_address_id' => 'nullable|integer|exists:addresses,address_id',
        ]);

        // Handle slug
        $newSlug = $data['slug'] ?? null;
        if ($newSlug && $newSlug !== $wishlist->slug) {
            // Sanitise and ensure unique
            $newSlug = Str::slug($newSlug);
            if (empty($newSlug)) {
                $newSlug = Wishlist::generateUniqueSlug($data['wishlist_name'], $wishlist->wishlist_id);
            } else {
                $newSlug = Wishlist::generateUniqueSlug($newSlug, $wishlist->wishlist_id);
            }
        } elseif (!$wishlist->slug) {
            $newSlug = Wishlist::generateUniqueSlug($data['wishlist_name'], $wishlist->wishlist_id);
        } else {
            $newSlug = $wishlist->slug;
        }

        // Validate delivery address belongs to this user
        if (!empty($data['delivery_address_id'])) {
            $addressBelongsToUser = Address::where('address_id', $data['delivery_address_id'])
                ->where('user_id', Auth::user()->user_id)
                ->exists();

            if (!$addressBelongsToUser) {
                return back()->withErrors(['delivery_address_id' => 'Invalid address.']);
            }
        }

        $wishlist->update([
            'wishlist_name'       => $data['wishlist_name'],
            'slug'                => $newSlug,
            'is_public'           => $data['is_public'] ?? $wishlist->is_public,
            'delivery_address_id' => $data['delivery_address_id'] ?? null,
        ]);

        return back()->with('success', 'Wishlist updated!');
    }

    /**
     * Delete a wishlist.
     */
    public function destroy($wishlistId)
    {
        $wishlist = Wishlist::where('user_id', Auth::user()->user_id)
            ->where('wishlist_id', $wishlistId)
            ->firstOrFail();

        // Delete items first
        WishlistItem::where('wishlists_id', $wishlist->wishlist_id)->delete();
        $wishlist->delete();

        return redirect()->route('wishlist.index')->with('success', 'Wishlist deleted.');
    }

    /**
     * Toggle share (public/private).
     */
    public function toggleShare($wishlistId)
    {
        $wishlist = Wishlist::where('user_id', Auth::user()->user_id)
            ->where('wishlist_id', $wishlistId)
            ->firstOrFail();

        // Generate slug if going public and has none
        if (!$wishlist->is_public && !$wishlist->slug) {
            $wishlist->slug = Wishlist::generateUniqueSlug($wishlist->wishlist_name, $wishlist->wishlist_id);
        }

        $wishlist->is_public = !$wishlist->is_public;
        $wishlist->save();

        return back()->with('success',
            $wishlist->is_public
                ? 'Wishlist is now public — share the link!'
                : 'Wishlist is now private.'
        );
    }

    /**
     * Toggle a variant in/out of a wishlist (AJAX).
     */
    public function toggle(Request $request)
    {
        if (!Auth::check()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Please sign in'], 401);
            }
            return redirect()->route('login')
                ->with('error', 'Please sign in to use the wishlist.');
        }

        $request->validate([
            'variant_id'  => 'required|exists:product_variants,variant_id',
            'wishlist_id' => 'nullable|integer',
        ]);

        // Use specified wishlist or default
        $wishlist = $this->resolveWishlist($request->wishlist_id);

        $existing = WishlistItem::where('wishlists_id', $wishlist->wishlist_id)
            ->where('variant_id', $request->variant_id)
            ->first();

        if ($existing) {
            $existing->delete();
            $message    = 'Removed from wishlist';
            $inWishlist = false;
        } else {
            WishlistItem::create([
                'wishlists_id' => $wishlist->wishlist_id,
                'variant_id'   => $request->variant_id,
            ]);
            $message    = 'Added to wishlist';
            $inWishlist = true;
        }

        $count = WishlistItem::where('wishlists_id', $wishlist->wishlist_id)->count();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success'        => true,
                'message'        => $message,
                'wishlist_count' => $count,
                'in_wishlist'    => $inWishlist,
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * Remove item from a specific wishlist.
     */
    public function remove(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $request->validate([
            'variant_id'  => 'required|exists:product_variants,variant_id',
            'wishlist_id' => 'required|integer',
        ]);

        $wishlist = Wishlist::where('user_id', Auth::user()->user_id)
            ->where('wishlist_id', $request->wishlist_id)
            ->firstOrFail();

        WishlistItem::where('wishlists_id', $wishlist->wishlist_id)
            ->where('variant_id', $request->variant_id)
            ->delete();

        return back()->with('success', 'Removed from wishlist');
    }

    /**
     * Show shared wishlist by slug (public page).
     */
    public function shared($slug)
    {
        $wishlist = Wishlist::where('slug', $slug)
            ->where('is_public', true)
            ->firstOrFail();

        $items = WishlistItem::where('wishlists_id', $wishlist->wishlist_id)
            ->with(['variant.product.brand', 'variant.images', 'variant.prices'])
            ->orderByDesc('created_at')
            ->get();

        $owner = $wishlist->user;

        // Check if wishlist has a delivery address set (for gift purchasing)
        $canGift = $wishlist->delivery_address_id !== null;

        return view('wishlist.shared', compact('wishlist', 'items', 'owner', 'canGift'));
    }

    /**
     * Add item to cart as a gift (from shared wishlist).
     * Sets session data so checkout knows to ship to wishlist owner.
     */
    public function giftAddToCart(Request $request)
    {
        $request->validate([
            'variant_id'  => 'required|exists:product_variants,variant_id',
            'wishlist_id' => 'required|integer',
        ]);

        $wishlist = Wishlist::where('wishlist_id', $request->wishlist_id)
            ->where('is_public', true)
            ->whereNotNull('delivery_address_id')
            ->firstOrFail();

        $address = $wishlist->deliveryAddress;
        if (!$address) {
            return back()->withErrors(['gift' => 'The wishlist owner has not set a delivery address.']);
        }

        // Store gift info in session so checkout can use it
        $giftData = session('gift_orders', []);
        $giftData[$request->variant_id] = [
            'wishlist_id'         => $wishlist->wishlist_id,
            'wishlist_owner_name' => $wishlist->user->first_name,
            'address_id'          => $address->address_id,
            'recipient_name'      => $address->recipient_name,
            'house_number'        => $address->house_number,
            'address_line_one'    => $address->address_line_one,
            'address_line_two'    => $address->address_line_two,
            'city'                => $address->city,
            'county'              => $address->county,
            'postcode'            => $address->postcode,
            'country_code'        => $address->country_code,
            'phone_number'        => $address->phone_number,
        ];
        session(['gift_orders' => $giftData]);

        // Add to cart (use existing cart logic)
        // Add to cart manually (same logic as CartController@add)
        app(\App\Http\Controllers\CartController::class)->add(
            new \Illuminate\Http\Request([
                'variant_id' => $request->variant_id,
                'quantity'   => 1,
            ])
        );

        return redirect()->route('cart.index')
            ->with('success', 'Gift item added to basket! It will be delivered to ' . $wishlist->user->first_name . '.');
    }

    /**
     * Get or create the user's default wishlist.
     */
    private function resolveWishlist(?int $wishlistId = null): Wishlist
    {
        $user = Auth::user();

        if ($wishlistId) {
            $wishlist = Wishlist::where('user_id', $user->user_id)
                ->where('wishlist_id', $wishlistId)
                ->first();

            if ($wishlist) return $wishlist;
        }

        // Get or create default
        $wishlist = Wishlist::where('user_id', $user->user_id)
            ->orderBy('created_at')
            ->first();

        if (!$wishlist) {
            $wishlist = Wishlist::create([
                'user_id'       => $user->user_id,
                'wishlist_name' => 'My Wishlist',
                'is_public'     => false,
                'slug'          => Wishlist::generateUniqueSlug('my-wishlist'),
            ]);
        }

        return $wishlist;
    }
}
