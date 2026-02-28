<?php

use App\Http\Controllers\Admin\{
    HomeController,
    LoginController,
};
use App\Http\Controllers\AirpotCsvController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

Route::controller(LoginController::class)->group(function () {
    Route::get('login', 'index')->name('login');
    Route::post('check_login', 'checkLogin')->name('check_login');
    Route::get('logout', 'logout')->name('logout');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['isAdmin'])
    ->group(function () {
        Route::get('/', [HomeController::class, 'index'])->name('dashboard');
    });
