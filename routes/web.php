<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\GoogleLoginController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductDetailController;
use App\Http\Controllers\SePayController;

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\HomeController as AdminHomeController;

use App\Http\Middleware\CheckAccountStatus;
use App\Http\Middleware\CheckAdmin;

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('dang-xuat', [LoginController::class, 'logout'])->name('logout');

Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

Route::get('/auth/google', [GoogleLoginController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [GoogleLoginController::class, 'handleGoogleCallback']);

Route::get('email/verify', [VerificationController::class, 'show'])->name('verification.notice');
Route::get('email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');
Route::post('email/resend', [VerificationController::class, 'resend'])->name('verification.resend');

Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/trangchu', [HomeController::class, 'index'])->name('trangchu');

Route::get('/theloai', [CategoryController::class, 'index'])->name('categories');
Route::get('/danh-muc/{slug}', [CategoryController::class, 'show'])->name('frontend.category.detail');
Route::get('/chi-tiet-san-pham/{id}', [ProductDetailController::class, 'show'])->name('product.detail');

Route::get('/sale', [ProductController::class, 'sale'])->name('shop.sale');
Route::get('/tim-kiem', [ProductController::class, 'search'])->name('search');
Route::get('/uu-dai', [ProductController::class, 'sale'])->name('products.sale');

Route::get('/gio-hang/count', [CartController::class, 'count'])->name('cart.count');
Route::post('/them-vao-gio', [CartController::class, 'store'])->name('cart.store');

Route::middleware([CheckAccountStatus::class])->group(function () {
    
    Route::get('/gio-hang', [CartController::class, 'index'])->name('cart');
    Route::put('/gio-hang/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/gio-hang/{id}', [CartController::class, 'destroy'])->name('cart.destroy');

    Route::get('/thanh-toan', [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/dat-hang', [OrderController::class, 'store'])->name('order.store');
    Route::get('/thanh-toan-qr/{order}', function ($order) {
        return view('users.cart.qr', ['order' => \App\Models\Order::findOrFail($order)]);
    })->name('payment.qr');
    Route::post('/orders/{id}/confirm-qr', [OrderController::class, 'confirmQr'])->name('orders.confirm_qr');
    Route::get('/dat-hang-thanh-cong', [OrderController::class, 'success'])->name('checkout.success');

    Route::get('/don-hang-cua-toi', [OrderController::class, 'index'])->name('user.orders');
    Route::get('/don-hang-cua-toi/{id}', [OrderController::class, 'show'])->name('user.orders.show');
});

Route::middleware([CheckAccountStatus::class, CheckAdmin::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        
        Route::get('/dashboard', [AdminHomeController::class, 'index'])->name('dashboard');
        
        Route::resource('categories', AdminCategoryController::class);
        Route::resource('products', AdminProductController::class);

        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::put('/orders/{id}', [AdminOrderController::class, 'update'])->name('orders.update');

        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('/users/{id}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{id}', [AdminUserController::class, 'update'])->name('users.update');
        Route::put('/users/{id}/lock', [AdminUserController::class, 'toggleLock'])->name('users.lock');
    });

    Route::get('/thanh-toan-qr/{order}/status', [OrderController::class, 'paymentStatus'])
    ->name('payment.status');