<?php

use App\Http\Controllers\PayPalController;
use App\Http\Livewire\CatalogComponent;
use App\Http\Livewire\CategoryComponent;
use App\Http\Livewire\CheckoutComponent;
use App\Http\Livewire\DashboardComponent;
use App\Http\Livewire\HomeComponent;
use App\Http\Livewire\PaymentCancelComponent;
use App\Http\Livewire\PaymentSuccessComponent;
use App\Http\Livewire\ProductComponent;
use App\Http\Livewire\PurchasesComponent;
use App\Http\Livewire\SalesComponent;
use App\Http\Livewire\TaxComponent;
use App\Http\Livewire\TenantComponent;
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
});

/*<──  ───────    CUSTOMER   ───────  ──>*/
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    "customer"
])->group(function () {
    Route::get("/catalog", CatalogComponent::class)->name('catalog');

    Route::get("/checkout", CheckoutComponent::class)->name("checkout");
    Route::get("/purchases", PurchasesComponent::class)->name("purchases");

    /*<──  ───────    PAYMENT   ───────  ──>*/
    Route::get('/payment/cancel', PaymentCancelComponent::class)->name('payment.cancel');
    Route::get('/payment/success', PaymentSuccessComponent::class)->name('payment.success');

    //Paypal
    Route::get('/payment/paypal/execute', [PayPalController::class, "executePayment"]);
    Route::get('/payment/paypal/cancel', [PayPalController::class, "cancelPayment"]);
});

/*<──  ───────    SELLER   ───────  ──>*/
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    "seller"
])->group(function () {
    Route::get("/products", ProductComponent::class)->name('products');
    Route::get("/categories", CategoryComponent::class)->name('categories');
    Route::get("/taxes", TaxComponent::class)->name('taxes');

    Route::get("/sales", SalesComponent::class)->name("sales");
});

/*<──  ───────    ADMIN   ───────  ──>*/
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    "tenant.admin"
])->group(function () {
    Route::get("/tenants/{id}", TenantComponent::class)->name('tenant.manage');
});
