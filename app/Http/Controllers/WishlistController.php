<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlist = session('wishlist', []);
        $products = [];
        
        foreach ($wishlist as $productId) {
            $product = Product::with(['primaryImage'])
                ->where('product_id', $productId)
                ->where('is_active', true)
                ->first();
                
            if ($product) {
                $products[] = $product;
            }
        }
        
        return view('wishlist.index', compact('products'));
    }
    
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,product_id'
        ]);
        
        $wishlist = session('wishlist', []);
        
        if (!in_array($request->product_id, $wishlist)) {
            $wishlist[] = $request->product_id;
            session(['wishlist' => $wishlist]);
        }
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Added to wishlist',
                'wishlist_count' => count($wishlist)
            ]);
        }
        
        return back()->with('success', 'Added to wishlist');
    }
    
    public function remove(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,product_id'
        ]);
        
        $wishlist = session('wishlist', []);
        $wishlist = array_filter($wishlist, function($id) use ($request) {
            return $id != $request->product_id;
        });
        
        session(['wishlist' => array_values($wishlist)]);
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Removed from wishlist',
                'wishlist_count' => count($wishlist)
            ]);
        }
        
        return back()->with('success', 'Removed from wishlist');
    }
    
    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,product_id'
        ]);
        
        $wishlist = session('wishlist', []);
        
        if (in_array($request->product_id, $wishlist)) {
            $wishlist = array_filter($wishlist, function($id) use ($request) {
                return $id != $request->product_id;
            });
            $message = 'Removed from wishlist';
        } else {
            $wishlist[] = $request->product_id;
            $message = 'Added to wishlist';
        }
        
        session(['wishlist' => array_values($wishlist)]);
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'wishlist_count' => count($wishlist),
                'in_wishlist' => in_array($request->product_id, $wishlist)
            ]);
        }
        
        return back()->with('success', $message);
    }
    
    public function clear()
    {
        session(['wishlist' => []]);
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Wishlist cleared'
            ]);
        }
        
        return back()->with('success', 'Wishlist cleared');
    }
}