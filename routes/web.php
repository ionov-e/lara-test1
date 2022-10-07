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

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [ClientController::class, 'index'])->name('dashboard');
    Route::resource('clients', ClientController::class);
    Route::get('/search', 'App\Http\Controllers\ClientController@search');
    Route::resource('pet', PetController::class)->except(['create', 'index']);
    Route::get('/create-pet-for-owner/{id}', [PetController::class, 'create'])->name('pet.create');
});

require __DIR__ . '/auth.php';
