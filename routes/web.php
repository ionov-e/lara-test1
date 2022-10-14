<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\PetController;
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
    return view('welcome');
});

Route::middleware(['auth', 'key.checked'])->group(function () {
    Route::resource('clients', ClientController::class);
    Route::get('/search', [ClientController::class, 'search']);
    Route::resource('clients.pets', PetController::class)->except(['index'])->shallow();
});

require __DIR__ . '/auth.php';
