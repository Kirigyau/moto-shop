<?php

use App\Http\Controllers\Admin\HeroSlideController as AdminHeroSlideController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

// Если symlink public/storage недоступен (Docker/Railway), отдаём файлы через Laravel.
Route::get('/storage/{path}', function (string $path) {
    $path = str_replace(['..', '\\'], '', $path);
    if ($path === '' || ! Storage::disk('public')->exists($path)) {
        abort(404);
    }

    return Storage::disk('public')->response($path);
})->where('path', '.*')->name('storage.public');

Route::get('/', [ShopController::class, 'home'])->name('home');

Route::get('/search', [ShopController::class, 'search'])->name('search');
Route::get('/search/suggest', [ShopController::class, 'searchSuggest'])->name('search.suggest');

Route::get('/catalog/{category}', [ShopController::class, 'catalog'])
    ->whereIn('category', array_keys(ShopController::CATEGORIES))
    ->name('catalog.category');

Route::get('/tovar/{product}', [ShopController::class, 'show'])->name('product.show');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove/{product}', [CartController::class, 'remove'])->name('cart.remove');

Route::get('/checkout', [CheckoutController::class, 'create'])->name('checkout.create');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/thanks', [CheckoutController::class, 'thanks'])->name('checkout.thanks');

Route::get('/pages/dostavka-i-oplata', [ShopController::class, 'pageDelivery'])->name('pages.delivery');
Route::get('/pages/rassrochka', [ShopController::class, 'pageInstallment'])->name('pages.installment');
Route::get('/pages/garantiya', [ShopController::class, 'pageWarranty'])->name('pages.warranty');
Route::get('/pages/o-magazine', [ShopController::class, 'pageAbout'])->name('pages.about');
Route::get('/pages/kontakty', [ShopController::class, 'pageContacts'])->name('pages.contacts');

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.store');
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('hero-slides', [AdminHeroSlideController::class, 'edit'])->name('hero-slides.edit');
    Route::put('hero-slides', [AdminHeroSlideController::class, 'update'])->name('hero-slides.update');
    Route::resource('products', AdminProductController::class)->except(['show']);
});
