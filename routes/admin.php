<?php

use App\Http\Controllers\Admin\{
    AdminUserController,
    EnquiryController,
    HomeController,
    LeadController,
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





        Route::prefix('/leads')->name('leads.')->group(function () {
            Route::get('/create', [LeadController::class, 'create'])->name('create');
            Route::post('/store', [LeadController::class, 'store'])->name('store');

            Route::get('show/{id}', [LeadController::class, 'show'])->name('show');
            Route::get('edit/{id}/edit', [LeadController::class, 'edit'])->name('edit');
            Route::put('update/{id}', [LeadController::class, 'update'])->name('update');

            Route::get('/pending', [LeadController::class, 'pending'])->name('pending');
            Route::get('/site-visit', [LeadController::class, 'siteVisit'])->name('site_visit');
            Route::get('/quotation', [LeadController::class, 'quotation'])->name('quotation');
            Route::get('/bank', [LeadController::class, 'bank'])->name('bank');
            Route::get('/discom', [LeadController::class, 'discom'])->name('discom');
            Route::get('/dispatch', [LeadController::class, 'dispatch'])->name('dispatch');
            Route::get('/installation', [LeadController::class, 'installation'])->name('installation');
            Route::get('/verification', [LeadController::class, 'verification'])->name('verification');
            Route::get('/completed', [LeadController::class, 'completed'])->name('completed');

            Route::get('/{id}/{stage}/move-stage', [LeadController::class,'moveStage'])->name('move_stage');

            Route::post('/{lead}/visit', [LeadController::class, 'storeVisit'])
                ->name('storeVisit');
            Route::put('visit/{id}', [LeadController::class, 'updateVisit'])
                ->name('updateVisit');
        });


        Route::resource('roles', RoleController::class);
        Route::resource('admin_users', AdminUserController::class);

        Route::get('set_permissions/{id}', [RoleController::class, 'setPermission'])->name('roles.set_permissions');
        Route::post('roles-set-update_permission', [RoleController::class, 'updatePermission'])->name('roles.update_permission');
        Route::get('/', [HomeController::class, 'index'])->name('dashboard');
    });
