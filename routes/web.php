<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactController;
// Customer Controllers
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\Customer\ProfileController as CustomerProfileController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
// Admin Controllers
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\ContactMessageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// == HALAMAN PUBLIK =======================================================
Route::get('/', [HomeController::class, 'index'])->name('home');

// Produk & Kategori
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/category/{category}', [ProductController::class, 'category'])->name('products.category');

// Halaman Statis
Route::view('/about-us', 'pages.about')->name('pages.about');
Route::view('/contact-us', 'pages.contact')->name('pages.contact');
Route::post('/contact-us/submit', [ContactController::class, 'submit'])->name('pages.contact.submit');


// == AUTENTIKASI ==========================================================
Auth::routes();


// == HALAMAN CUSTOMER (BUTUH LOGIN & ROLE CUSTOMER) =======================
Route::middleware(['auth', 'customer'])->group(function () {

    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('customer.dashboard');

    Route::prefix('profile')->name('customer.profile.')->group(function () {
        Route::get('/', [CustomerProfileController::class, 'index'])->name('index');
        Route::put('/', [CustomerProfileController::class, 'update'])->name('update');
        Route::put('/password', [CustomerProfileController::class, 'updatePassword'])->name('password.update');
    });

    Route::prefix('my-orders')->name('customer.orders.')->group(function () {
        Route::get('/', [CustomerOrderController::class, 'index'])->name('index');
        Route::get('/{order}', [CustomerOrderController::class, 'show'])->name('show');
    });

    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::get('/count', [CartController::class, 'count'])->name('count');
        Route::post('/add', [CartController::class, 'add'])->name('add');
        Route::put('/{cart}', [CartController::class, 'update'])->name('update');
        Route::delete('/{cart}', [CartController::class, 'remove'])->name('remove');
        Route::delete('/', [CartController::class, 'clear'])->name('clear');
    });

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/payment/{order}', [CheckoutController::class, 'showPayment'])->name('payment.show');
    Route::get('/order-success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');
});


// == HALAMAN ADMIN (BUTUH LOGIN & ROLE ADMIN) =============================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('products', AdminProductController::class);
    Route::resource('categories', AdminCategoryController::class);

    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [AdminOrderController::class, 'index'])->name('index');
        Route::get('/{order}', [AdminOrderController::class, 'show'])->name('show');
        Route::put('/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('update.status');
    });

    Route::prefix('messages')->name('messages.')->group(function () {
        Route::get('/', [ContactMessageController::class, 'index'])->name('index');
        Route::get('/{message}', [ContactMessageController::class, 'show'])->name('show');
    });

    Route::get('/reports/sales', [AdminReportController::class, 'sales'])->name('reports.sales');
    Route::put('categories/{category}/toggle-status', [AdminCategoryController::class, 'toggleStatus'])->name('categories.toggle-status');
});


// == ROUTE LAIN-LAIN ========================================================
Route::post('/midtrans/notification', [CheckoutController::class, 'notification'])->name('midtrans.notification');

// Redirect /home setelah login berdasarkan role
Route::get('/home', function () {
    if (Auth::check()) {
        if (Auth::user()->role == 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif (Auth::user()->role == 'customer') {
            return redirect()->route('customer.dashboard');
        }
    }
    return redirect()->route('home'); // Fallback jika tidak ada role
})->middleware('auth');
