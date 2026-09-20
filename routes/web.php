<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EstablishmentController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\TableQrCodeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DashboardController;
use App\Models\RestaurantTable;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rotas públicas
|--------------------------------------------------------------------------
*/

Route::model('table', RestaurantTable::class);

Route::view('/', 'welcome')->name('home');

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


/*
|--------------------------------------------------------------------------
| Área autenticada
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get(
    'dashboard',
    [DashboardController::class, 'index']
)->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Criação de estabelecimento
    |--------------------------------------------------------------------------
    */

    Route::middleware('owner.only')->group(function () {
        Route::get(
            'establishments/create',
            [EstablishmentController::class, 'create']
        )->name('establishments.create');

        Route::post(
            'establishments',
            [EstablishmentController::class, 'store']
        )->name('establishments.store');
    });

    /*
    |--------------------------------------------------------------------------
    | Estabelecimentos
    |--------------------------------------------------------------------------
    */

    Route::resource(
        'establishments',
        EstablishmentController::class
    )->only([
        'index',
        'show',
    ]);

    /*
    |--------------------------------------------------------------------------
    | Owner
    |--------------------------------------------------------------------------
    */

    Route::middleware('establishment.role:owner')->group(function () {

        Route::get(
            'establishments/{establishment}/edit',
            [EstablishmentController::class, 'edit']
        )->name('establishments.edit');

        Route::put(
            'establishments/{establishment}',
            [EstablishmentController::class, 'update']
        )->name('establishments.update');

        Route::patch(
            'establishments/{establishment}',
            [EstablishmentController::class, 'update']
        );

        Route::delete(
            'establishments/{establishment}',
            [EstablishmentController::class, 'destroy']
        )->name('establishments.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Owner + Manager
    |--------------------------------------------------------------------------
    */

    Route::middleware('establishment.role:owner,manager')->group(function () {

        Route::resource(
            'establishments.categories',
            CategoryController::class
        )->except(['show']);

        Route::resource(
            'establishments.products',
            ProductController::class
        )->except(['show']);

        Route::resource(
            'establishments.employees',
            EmployeeController::class
        )->except(['show']);

        Route::get(
            'establishments/{establishment}/qr-codes',
            [TableQrCodeController::class, 'index']
        )->name('establishments.qr-codes');
        
        Route::get(
            'establishments/{establishment}/reports',
            [ReportController::class, 'index']
        )->name('establishments.reports');
    });

    /*
    |--------------------------------------------------------------------------
    | Owner + Manager + Employee
    |--------------------------------------------------------------------------
    */

    Route::middleware(
        'establishment.role:owner,manager,employee'
    )->group(function () {

        Route::resource(
            'establishments.tables',
            TableController::class
        )->except(['show']);

        Route::resource(
            'establishments.orders',
            OrderController::class
        );

        
    });
});

require __DIR__.'/settings.php';