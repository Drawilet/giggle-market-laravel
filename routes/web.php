<?php

use App\Http\Controllers\MercadoPagoController;
use App\Http\Controllers\PayPalController;
use App\Http\Livewire\BillingComponent;
use App\Http\Livewire\CatalogComponent;
use App\Http\Livewire\CategoryComponent;
use App\Http\Livewire\CheckoutComponent;
use App\Http\Livewire\DashboardComponent;
use App\Http\Livewire\HomeComponent;
use App\Http\Livewire\ManageStoreComponent;
use App\Http\Livewire\NewStoreComponent;
use App\Http\Livewire\ProductComponent;
use App\Http\Livewire\PurchasesComponent;
use App\Http\Livewire\TaxComponent;
use App\Http\Livewire\StoreDashboard;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if (auth()->check()) return redirect("/home");
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/home', HomeComponent::class)->name('home');
    Route::get('/dashboard', DashboardComponent::class)->name('dashboard');

    Route::get("/catalog", CatalogComponent::class)->name('catalog');

    Route::get("/checkout", CheckoutComponent::class)->name("checkout");
    Route::get("/purchases", PurchasesComponent::class)->name("purchases");

    /*<──  ───────    BILLING   ───────  ──>*/
    Route::get("/billing", BillingComponent::class)->name("billing");

    // -> PayPal
    Route::get('/payment/paypal/execute', [PayPalController::class, "executePayment"]);
    Route::get('/payment/paypal/cancel', [PayPalController::class, "cancelPayment"]);

    // -> MercadoPago
    Route::get('/payment/mercadopago/execute', [MercadoPagoController::class, "executePayment"]);
    Route::get('/payment/mercadopago/pending', [MercadoPagoController::class, "waitingPayment"]);
    Route::get('/payment/mercadopago/cancel', [MercadoPagoController::class, "cancelPayment"]);
});

/*<──  ───────    SELLER   ───────  ──>*/
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    "seller"
])->group(function () {
    Route::get("/products", ProductComponent::class)->name('products');
});

/*<──  ───────    NOT SELLER   ───────  ──>*/
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    "not.seller",
])->group(function () {
    Route::get("/stores/new", NewStoreComponent::class)->name('store.new');
});

/*<──  ───────    ADMIN   ───────  ──>*/
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    "seller",
    "store.admin"
])->group(function () {
    Route::get("/stores/manage", ManageStoreComponent::class)->name('store.manage');
    Route::get("/stores/dashboard", StoreDashboard::class)->name('store.dashboard');
});

/*<──  ───────    ADMIN   ───────  ──>*/
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    "admin"
])->group(function () {
    Route::get("/categories", CategoryComponent::class)->name('categories');
    Route::get("/taxes", TaxComponent::class)->name('taxes');
});
