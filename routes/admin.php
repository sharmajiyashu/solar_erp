<?php

use App\Http\Controllers\Admin\{
    AdminUserController,
    BankController,
    DispatchDetailController,
    EnquiryController,
    HomeController,
    InstallationController,
    LeadController,
    LoginController,
    QuotationController,
    RoleController,
    VerificationController,
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

            Route::get('/site-visit', [LeadController::class, 'siteVisit'])->name('site_visit');
            Route::get('/quotation', [LeadController::class, 'quotation'])->name('quotation');
            Route::get('/bank', [LeadController::class, 'bank'])->name('bank');
            Route::get('/discom', [LeadController::class, 'discom'])->name('discom');
            Route::get('/dispatch', [LeadController::class, 'dispatch'])->name('dispatch');
            Route::get('/installation', [LeadController::class, 'installation'])->name('installation');
            Route::get('/verification', [LeadController::class, 'verification'])->name('verification');
            Route::get('/completed', [LeadController::class, 'completed'])->name('completed');

            Route::get('/{id}/{stage}/move-stage', [LeadController::class, 'moveStage'])->name('move_stage');

            Route::post('/{lead}/visit', [LeadController::class, 'storeVisit'])
                ->name('storeVisit');
            Route::put('visit/{id}', [LeadController::class, 'updateVisit'])
                ->name('updateVisit');
        });

        Route::post('quotations/store/{id}', [QuotationController::class, 'store'])->name('quotations.store');
        Route::delete('/quotations/{id}', [QuotationController::class, 'destroy'])->name('quotations.destroy');

        Route::post('/leads/{lead}/bank-documents', [BankController::class, 'store'])
            ->name('bank-documents.store');

        Route::delete(
            '/bank-documents/{id}',
            [BankController::class, 'destroy']
        )
            ->name('bank-documents.destroy');

        Route::post(
            '/bank-documents/{id}/status',
            [BankController::class, 'changeStatus']
        )
            ->name('bank-documents.status');

        Route::post(
            '/dispatch-details/{lead}',
            [DispatchDetailController::class, 'storeOrUpdate']
        )
            ->name('dispatch.store');

        Route::post(
            '/installation/store/{lead}',
            [InstallationController::class, 'store']
        )
            ->name('installation.store');

        Route::delete('/installation-attachments/{id}', [InstallationController::class, 'deleteAttachment'])
            ->name('installation.attachment.delete');

        Route::post(
            '/verification/store/{lead}',
            [VerificationController::class, 'store']
        )
            ->name('verification.store');

        Route::resource('roles', RoleController::class);
        Route::resource('admin_users', AdminUserController::class);

        Route::get('set_permissions/{id}', [RoleController::class, 'setPermission'])->name('roles.set_permissions');
        Route::post('roles-set-update_permission', [RoleController::class, 'updatePermission'])->name('roles.update_permission');
        Route::get('/', [HomeController::class, 'index'])->name('dashboard');
    });
