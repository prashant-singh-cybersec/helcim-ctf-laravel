<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Http\Request;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FeatureRequestController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('landing');
});


Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);



Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);


Route::get('/dashboard', function () {
    if (!Auth::check()) {
        return redirect('/login');
    }

    return app(CustomerController::class)->dashboard();
});



Route::get('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/login');
})->name('logout');


Route::post('/api/edit/customer/{id}', [CustomerController::class, 'editCustomer'])->name('api_edit_customer');



Route::get('/customer_create', function () {
    if (!Auth::check()) {
        return redirect()->route('login')->withErrors(['error' => 'You must be logged in to access this page.']);
    }

    return view('customer.index');
})->name('app_customer_create');


Route::post('/api/customers', [CustomerController::class, 'createCustomer'])->name('api_create_customer');
Route::get('/api/customers', [CustomerController::class, 'getCustomer'])->name('api_get_customer');

Route::post('/api/update_settings', [SettingsController::class, 'updateSettings'])->name('api_update_settings');



Route::get('/admin/protected/flag.txt', [AdminController::class, 'protectedFlag'])->name('admin_protected_flag');
Route::get('/admin/protected/flag1.txt', [AdminController::class, 'protectedFlag1'])->name('admin_protected_flag1');

Route::get('/user_role_settings', [SettingsController::class, 'index'])->name('user_role_settings');


Route::get('/search', [HomeController::class, 'search'])->name('universal_search');


Route::post('/api/edit_user_roles', [SettingsController::class, 'editUserRoles'])->name('edit_user_roles');


Route::get('/advanced_analytics', [SettingsController::class, 'analyticsPage'])->name('advanced_analytics');

Route::get('/purchase_premium', [SettingsController::class, 'purchasePremium'])->name('purchase_premium');
Route::post('/api/apply_discount', [SettingsController::class, 'applyDiscount'])->name('apply_discount');
Route::post('/api/purchase', [SettingsController::class, 'finalizePurchase'])->name('purchase_finalize');


Route::match(['get', 'post'], '/feedback', [SettingsController::class, 'feedback'])->name('app_feedback');


Route::get('/admin/panel', [AdminController::class, 'adminPanel'])->name('admin.panel');




Route::middleware(['auth'])->group(function () {
    Route::get('/feature-request', [FeatureRequestController::class, 'create'])->name('feature-request.create');
    Route::post('/feature-request', [FeatureRequestController::class, 'store'])->name('feature-request.store');
});
Route::get('/download', [FeatureRequestController::class, 'download'])->name('feature-request.download');