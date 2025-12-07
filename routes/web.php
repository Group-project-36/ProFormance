<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PreferenceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LoginDetailController;
use App\Http\Controllers\RegisterController;
use Illuminate\Http\Request;
use App\Http\Controllers\ContactController;

Route::post('/currency', [PreferenceController::class, 'updateCurrency'])
    ->name('currency.update');

Route::post('/locale', [PreferenceController::class, 'updateLocale'])
    ->name('locale.update');

Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');


Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])
    ->name('products.show');

Route::get('/category/{slug}', [ProductController::class, 'category'])->name('products.category');

Route::get('/search', function(Request $request) {
    return redirect()->route('products.index', ['search' => $request->q]);
})->name('search');

Route::get('/category/{category}', function($category) {
    return redirect()->route('products.index', ['category' => $category]);
})->name('category.show');

Route::get('/brand/{brand}', function($brand) {
    return redirect()->route('products.index', ['brand' => $brand]);
})->name('brand.show');



Route::prefix('wishlist')->name('wishlist.')->group(function () {
    Route::post('/add', function() {
        return response()->json(['success' => true, 'message' => 'Wishlist feature coming soon']);
    })->name('add');
    
    Route::post('/remove', function() {
        return response()->json(['success' => true, 'message' => 'Wishlist feature coming soon']);
    })->name('remove');
    
    Route::get('/', function() {
        return 'Wishlist page - Coming soon';
    })->name('index');
});


Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    
    Route::get('/register', [RegisterController::class, 'showRegister'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
});


Route::get('/cart',                        [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add',                   [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{lineId}',       [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove/{lineId}',       [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear',                 [CartController::class, 'clear'])->name('cart.clear');



Route::get('/contact', [ContactController::class, 'create'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::view('/about', 'about')->name('about');

Route::post('/logout', function () {
    session()->forget('user_id');
    session()->invalidate();
    session()->regenerateToken();
    return redirect('/');
})->name('logout');