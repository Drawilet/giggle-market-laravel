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
use App\Http\Livewire\StoreProductsComponent;
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
    Route::get("/products/{id}", ProductComponent::class)->name("products.show");

    /*<──  ───────    PAYMENT   ───────  ──>*/
    Route::get("/checkout", CheckoutComponent::class)->name("checkout");

    Route::get("/user/purchases", PurchasesComponent::class)->name("user.purchases");
    Route::get("/user/billing", BillingComponent::class)->name("user.billing");

    // -> PayPal
    Route::get('/payment/paypal/execute', [PayPalController::class, "executePayment"]);
    Route::get('/payment/paypal/cancel', [PayPalController::class, "cancelPayment"]);

    // -> MercadoPago
    Route::get('/payment/mercadopago/execute', [MercadoPagoController::class, "executePayment"]);
    Route::get('/payment/mercadopago/pending', [MercadoPagoController::class, "waitingPayment"]);
    Route::get('/payment/mercadopago/cancel', [MercadoPagoController::class, "cancelPayment"]);
});

/*<──  ───────    STORE   ───────  ──>*/
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    "seller"
])->group(function () {
    Route::get("/store/products", StoreProductsComponent::class)->name('store.products');
});

/*<──  ───────    NOT STORE   ───────  ──>*/
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    "not.seller",
])->group(function () {
    Route::get("/store/new", NewStoreComponent::class)->name('store.new');
});

/*<──  ───────    STORE ADMIN   ───────  ──>*/
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    "seller",
    "store.admin"
])->group(function () {
    Route::get("/store/manage", ManageStoreComponent::class)->name('store.manage');
    Route::get("/store/dashboard", StoreDashboard::class)->name('store.dashboard');
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
