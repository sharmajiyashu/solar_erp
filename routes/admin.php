<?php

use App\Http\Controllers\Admin\{
    AdminUserController,
    EnquiryController,
    HomeController,
    LoginController,
    RoleController,
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

        Route::resource('roles', RoleController::class);
        Route::resource('admin_users', AdminUserController::class);


        Route::resource('enquiries', EnquiryController::class);
        Route::get('enquiries/{id}/convert', [EnquiryController::class, 'convertToLead'])
            ->name('enquiries.convert');
        Route::post(
            'enquiries/{id}/followup',
            [EnquiryController::class, 'storeFollowup']
        )
            ->name('enquiries.storeFollowup');

        // Close Enquiry
        Route::get('enquiries/{id}/close', [EnquiryController::class, 'close'])
            ->name('enquiries.close');

        Route::get('/enquiries/{id}/mark-to-close', [EnquiryController::class, 'markToClose'])
            ->name('enquiries.markToClose');

        Route::get('set_permissions/{id}', [RoleController::class, 'setPermission'])->name('roles.set_permissions');
        Route::post('roles-set-update_permission', [RoleController::class, 'updatePermission'])->name('roles.update_permission');
        Route::get('/', [HomeController::class, 'index'])->name('dashboard');
    });
