<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ShippingMethodController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\ProductAttributeController;
use App\Http\Controllers\AttributeValueController;
use App\Http\Controllers\ProductVariantController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\TopHeadlineController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CheckoutController;

// ============================== FRONTEND ROUTES ==============================


// Frontend Routes

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/category/{category:slug}', [CategoryController::class, 'show'])->name('categories.show'); 
Route::get('/product/{product:slug}', [ProductController::class, 'details'])->name('product.details');

Route::get('/about-us', function () {
    return view('about_us'); 
})->name('about_us');

Route::get('/contact-us', function () {
    return view('contact_us'); 
})->name('contact_us');

Route::get('/cart', [CartController::class, 'showCart'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'updateCart'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'removeFromCart'])->name('cart.remove');
// Route::get('/cart/show', [CartController::class, 'removeFromCart'])->name('cart.show');

Route::get('/checkout', [CheckoutController::class, 'showCheckoutForm'])->name('checkout.show');
Route::post('/checkout', [CheckoutController::class, 'processCheckout'])->name('checkout.process');


// ============================== ADMIN ROUTES ==============================

// ========== Users =============
Route::prefix('admin/users')->middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('users');
    Route::get('/store', [UserController::class, 'store'])->name('storeuser');
    Route::get('/update', [UserController::class, 'update'])->name('updateUser');
    Route::get('/edit/{id}', [UserController::class, 'edit'])->name('edit');
    Route::get('/delete/{id}', [UserController::class, 'destroy'])->name('deleteuser');
});


// ========== Guest Users =============
Route::prefix('admin/guests')->middleware(['auth', 'verified', 'admin'])->group(function() {
    Route::get('/',[GuestController::class,'index'])->name('guestUser');
    Route::get('/delete/{id}',[GuestController::class,'destroy'])->name('deleteGuest');
});

// ========== Category =============
Route::prefix('admin/ategories')->middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/store', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/update', [CategoryController::class, 'update'])->name('categories.update');
    Route::get('/edit/{id}', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::get('/delete/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
});

Route::prefix('admin/')->middleware('auth' , 'verified', 'admin')->group(function(){
        
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('products', ProductController::class);
        Route::resource('product_attributes', ProductAttributeController::class);
        Route::resource('attribute_values', AttributeValueController::class);
        Route::resource('product_variants', ProductVariantController::class);
        Route::resource('inventories', InventoryController::class);
        Route::resource('shipping_methods', ShippingMethodController::class);
        Route::resource('coupons', CouponController::class);
        Route::resource('addresses', AddressController::class);
        Route::resource('top_headlines', TopHeadlineController::class);
        Route::resource('orders', OrderController::class);
        Route::resource('order_items', OrderItemController::class);
        Route::resource('sales', SaleController::class);
        Route::resource('reviews', ReviewController::class);
            //  route for toggling review 
            Route::patch('reviews/{review}/toggle-approval', [ReviewController::class, 'toggleApproval'])->name('reviews.toggle-approval');

        Route::resource('carts', CartController::class); 
            //  routes for cart items 
            Route::post('carts/{cart}/items', [CartController::class, 'addItem'])->name('carts.addItem');
            Route::delete('carts/{cart}/items/{item}', [CartController::class, 'removeItem'])->name('carts.removeItem');

        Route::resource('cart_items', CartItemController::class);
});


// Auth Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
