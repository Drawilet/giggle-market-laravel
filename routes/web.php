<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\MercadoPagoController;
use App\Http\Controllers\PayPalController;

use App\Http\Livewire\HomeComponent;;

use App\Http\Livewire\CatalogComponent;
use App\Http\Livewire\ProductComponent;

use App\Http\Livewire\CheckoutComponent;

use App\Http\Livewire\User\BillingComponent;
use App\Http\Livewire\User\DashboardComponent;
use App\Http\Livewire\User\PurchasesComponent;

use App\Http\Livewire\App\TaxComponent;
use App\Http\Livewire\App\CategoryComponent;
use App\Http\Livewire\App\ManageComponent;
use App\Http\Livewire\Moderator\DashboardComponent as ModeratorDashboardComponent;
use App\Http\Livewire\Moderator\ProductsComponent as ModeratorProductsComponent;
use App\Http\Livewire\Store\DashboardComponent as StoreDashboardComponent;
use App\Http\Livewire\Store\ManageComponent as StoreManageComponent;
use App\Http\Livewire\Store\ProductsComponent;
use App\Http\Livewire\Store\NewComponent;

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
    Route::get('/user/dashboard', DashboardComponent::class)->name('user.dashboard');

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
    Route::get("/store/products", ProductsComponent::class)->name('store.products');
});

/*<──  ───────    NOT STORE   ───────  ──>*/
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    "not.seller",
])->group(function () {
    Route::get("/store/new", NewComponent::class)->name('store.new');
});

/*<──  ───────    STORE ADMIN   ───────  ──>*/
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    "seller",
    "store.admin"
])->group(function () {
    Route::get("/store/manage", StoreManageComponent::class)->name('store.manage');
    Route::get("/store/dashboard", StoreDashboardComponent::class)->name('store.dashboard');
});

/*<──  ───────    ADMIN   ───────  ──>*/
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    "admin"
])->group(function () {
    Route::get("/app/categories", CategoryComponent::class)->name('app.categories');
    Route::get("/app/taxes", TaxComponent::class)->name('app.taxes');
    Route::get("/app/manage", ManageComponent::class)->name('app.manage');
});

/*<──  ───────    MODERATOR   ───────  ──>*/
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    "admin"
])->group(function () {
    Route::get("/moderator/dashboard", ModeratorDashboardComponent::class)->name('moderator.dashboard');
    Route::get("/moderator/products", ModeratorProductsComponent::class)->name('moderator.products');
});
