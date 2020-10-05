<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\NumberController;
use App\Http\Controllers\NumberPreferenceController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

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
    return Inertia\Inertia::render('Home', [
        'user' => Auth::user(),
        'customer_amount' => count(Auth::user()->customers),
        'customer_index_url' => URL::route('customer.index')
    ]);
})->middleware('auth')->name('home');

// Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
//     return Inertia\Inertia::render('Dashboard');
// })->name('dashboard');

Route::get('/dashboard', function() {
    // redirect to home
    return redirect(URL::route('home'));
});

Route::resource('customer', CustomerController::class);
Route::resource('number', NumberController::class);
Route::resource('number-preference', NumberPreferenceController::class);
