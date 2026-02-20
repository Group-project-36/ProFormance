<?php

use Illuminate\Support\Facades\Route;   
use App\Http\Controllers\PreferenceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductAutocompleteController;
use App\Http\Controllers\LoginDetailController;
use App\Http\Controllers\RegisterController;
use Illuminate\Http\Request;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\AccountInvoiceController;


use App\Http\Controllers\AccountController;
use App\Http\Controllers\AccountOrderController;
use App\Http\Controllers\AccountAddressController;
use App\Http\Controllers\AccountSecurityController;
use App\Http\Controllers\AccountReviewController;

use App\Http\Controllers\ReviewController;


Route::post('/currency', [PreferenceController::class, 'updateCurrency'])
    ->name('currency.update');

Route::post('/locale', [PreferenceController::class, 'updateLocale'])
    ->name('locale.update');

    
Route::middleware('auth')->prefix('checkout')->group(function () {
    Route::get('/',                [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/',               [CheckoutController::class, 'store'])->name('checkout.store');
    Route::post('/apply-discount', [CheckoutController::class, 'applyDiscount'])->name('checkout.discount.apply');
    Route::post('/remove-discount',[CheckoutController::class, 'removeDiscount'])->name('checkout.discount.remove');
});


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

Route::get('/category/{slug}', [ProductController::class, 'category'])->name('products.category');



Route::get('/cart/preview', [CartController::class, 'preview'])
    ->name('cart.preview');

Route::middleware('auth')->prefix('account')->name('account.')->group(function () {

    // Dashboard
    Route::get('/',        [AccountController::class, 'dashboard'])->name('dashboard');

    // Profile
    Route::get('/profile',  [AccountController::class, 'profile'])->name('profile');
    Route::post('/profile', [AccountController::class, 'updateProfile'])->name('profile.update');

    // Orders
    Route::get('/orders',            [AccountOrderController::class, 'index'])->name('orders');
    Route::get('/orders/{order_id}', [AccountOrderController::class, 'show'])->name('orders.show');

    // Addresses
    Route::get('/addresses',                   [AccountAddressController::class, 'index'])->name('addresses');
    Route::post('/addresses',                  [AccountAddressController::class, 'store'])->name('addresses.store');
    Route::put('/addresses/{address_id}',      [AccountAddressController::class, 'update'])->name('addresses.update');
    Route::delete('/addresses/{address_id}',   [AccountAddressController::class, 'destroy'])->name('addresses.destroy');

    // Reviews
    Route::get('/reviews',                  [AccountReviewController::class, 'index'])->name('reviews');
    Route::put('/reviews/{review_id}',      [AccountReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review_id}',   [AccountReviewController::class, 'destroy'])->name('reviews.destroy');

    // Security
    Route::get('/security',          [AccountSecurityController::class, 'index'])->name('security');
    Route::post('/change-password',  [AccountSecurityController::class, 'changePassword'])->name('security.password');
    Route::post('/change-email',     [AccountSecurityController::class, 'changeEmail'])->name('security.email');
    Route::post('/delete-account',   [AccountSecurityController::class, 'deleteAccount'])->name('security.delete');
});


Route::get('/product-autocomplete', [ProductAutocompleteController::class, 'index'])
    ->name('products.autocomplete');

Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])
    ->name('newsletter.subscribe');

Route::post('/account/newsletter', [NewsletterController::class, 'updatePreference'])
    ->middleware('auth')
    ->name('account.newsletter.update');


Route::post('/checkout/totals', [CheckoutController::class, 'totals'])
    ->middleware('auth')
    ->name('checkout.totals');

// Wishlist (requires login)
Route::middleware('auth')->group(function () {
    Route::get('/wishlist',                       [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist',                      [WishlistController::class, 'store'])->name('wishlist.store');
    Route::get('/wishlist/{wishlistId}',           [WishlistController::class, 'show'])->name('wishlist.show');
    Route::put('/wishlist/{wishlistId}',           [WishlistController::class, 'update'])->name('wishlist.update');
    Route::delete('/wishlist/{wishlistId}',        [WishlistController::class, 'destroy'])->name('wishlist.destroy');
    Route::post('/wishlist/{wishlistId}/share',    [WishlistController::class, 'toggleShare'])->name('wishlist.toggleShare');
    Route::post('/wishlist/toggle',               [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::post('/wishlist/remove',               [WishlistController::class, 'remove'])->name('wishlist.remove');
});

// Public shared wishlist (by slug — nice short URL)
Route::get('/w/{slug}', [WishlistController::class, 'shared'])->name('wishlist.shared');

// Gift purchase from shared wishlist
Route::post('/wishlist/gift-add', [WishlistController::class, 'giftAddToCart'])->name('wishlist.giftAdd');

// Reviews (requires login)
Route::middleware('auth')->group(function () {
    Route::post('/products/{productId}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
});

Route::get('/test-cards', function () {
    return view('test-cards');
})->name('test.cards');
