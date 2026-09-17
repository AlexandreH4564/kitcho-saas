<?php

use App\Models\RestaurantTable;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;



Route::get('/menu/{slug}', [MenuController::class, 'show'])
    ->name('menu.show');

Route::get('/menu/{slug}/checkout', [MenuController::class, 'checkout'])
    ->name('menu.checkout');

Route::model('table', RestaurantTable::class);

Route::get('/menu/{slug}', [MenuController::class, 'show'])
    ->name('menu.show');

Route::get('/menu/{slug}/checkout', [MenuController::class, 'checkout'])
    ->name('menu.checkout');

Route::post('/menu/{slug}/checkout', [MenuController::class, 'storeOrder'])
    ->name('menu.checkout.store');

    Route::get(
    '/menu/{slug}/pedido/{order}/sucesso',
    [MenuController::class, 'orderSuccess']
)->name('menu.order-success');

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::resource(
        'establishments',
        \App\Http\Controllers\EstablishmentController::class
    );

    Route::resource(
        'establishments.categories',
        \App\Http\Controllers\CategoryController::class
    )->except(['show']);

    Route::resource(
        'establishments.products',
        \App\Http\Controllers\ProductController::class
    )->except(['show']);

    Route::resource(
        'establishments.tables',
        \App\Http\Controllers\TableController::class
    )->except(['show']);

    Route::resource(
    'establishments.orders',
    \App\Http\Controllers\OrderController::class
    );

    Route::get('/menu/{slug}', [MenuController::class, 'show'])
    ->name('menu.show');

    Route::get(
    'establishments/{establishment}/qr-codes',
    [\App\Http\Controllers\TableQrCodeController::class, 'index']
)->name('establishments.qr-codes');

Route::resource(
    'establishments.employees',
    \App\Http\Controllers\EmployeeController::class
)->except(['show']);
});

require __DIR__.'/settings.php';