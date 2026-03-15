<?php

use App\Http\Controllers\Admin\{
    AdminUserController,
    AttendanceController,
    RoleController,
    VerificationController,
    BackendController,
    DocumentController,
    HomeController,
    LeadController,
    EnquiryController,
    QuotationController,
    DispatchDetailController,
    InstallationController,
    SiteVisitController,
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

        Route::resource('enquiries', EnquiryController::class);
        Route::post('enquiries/{id}/convert', [EnquiryController::class, 'convertToLead'])
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

        Route::get('enquiries/followup/{followupId}/edit', [EnquiryController::class, 'editFollowup'])->name('enquiries.followup.edit');
        Route::post('enquiries/followup/{followupId}/update', [EnquiryController::class, 'updateFollowup'])->name('enquiries.followup.update');





        Route::prefix('/leads')->name('leads.')->group(function () {
            Route::get('/create', [LeadController::class, 'create'])->name('create');
            Route::post('/store', [LeadController::class, 'store'])->name('store');
            Route::get('show/{id}', [LeadController::class, 'show'])->name('show');
            Route::put('update/{id}', [LeadController::class, 'update'])->name('update');
            Route::get('edit/{id}/edit', [LeadController::class, 'edit'])->name('edit');

            Route::get('/', [LeadController::class, 'index'])->name('index');

            Route::get('/site-visit', [LeadController::class, 'siteVisit'])->name('site_visit');
            Route::get('/quotation', [LeadController::class, 'quotation'])->name('quotation');
            Route::get('/document', [LeadController::class, 'document'])->name('document');
            Route::get('/backend', [LeadController::class, 'backend'])->name('backend');
            Route::get('/procurement', [LeadController::class, 'procurement'])->name('procurement');
            Route::get('/installation', [LeadController::class, 'installation'])->name('installation');
            Route::get('/verification', [LeadController::class, 'verification'])->name('verification');
            Route::get('/completed', [LeadController::class, 'completed'])->name('completed');

            Route::get('/{id}/{stage}/move-stage', [LeadController::class, 'moveStage'])->name('move_stage');
            Route::delete('/{id}', [LeadController::class, 'destroy'])->name('destroy');
            Route::post('/{id}/own', [LeadController::class, 'ownLead'])->name('own');

            Route::post('/{lead}/visit', [LeadController::class, 'storeVisit'])
                ->name('storeVisit');
            Route::put('visit/{id}', [LeadController::class, 'updateVisit'])
                ->name('updateVisit');
        });

        Route::post('quotations/store/{id}', [QuotationController::class, 'store'])->name('quotations.store');
        Route::delete('/quotations/{id}', [QuotationController::class, 'destroy'])->name('quotations.destroy');

        Route::post('/document-tracking/{lead}/store', [DocumentController::class, 'store'])->name('document-tracking.store');
        Route::delete('/document-tracking/{id}/destroy', [DocumentController::class, 'destroy'])->name('document-tracking.destroy');
        Route::post('/document-tracking/{id}/status', [DocumentController::class, 'changeStatus'])->name('document-tracking.status');
        Route::post('/document-tracking/{lead}/tracking', [DocumentController::class, 'updateLeadStatus'])->name('document-tracking.tracking');

        Route::post('/backend-tracking/{lead}/tracking', [BackendController::class, 'updateTracking'])->name('backend-tracking.tracking');
        Route::post('/backend-tracking/{lead}/move', [BackendController::class, 'moveToProcurement'])->name('backend-tracking.move');

        Route::post(
            '/dispatch-details/{lead}',
            [DispatchDetailController::class, 'storeOrUpdate']
        )
            ->name('procurement.store');

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



        Route::post('/punch-in', [AttendanceController::class, 'punchIn'])->name('punch.in');
        Route::post('/punch-out', [AttendanceController::class, 'punchOut'])->name('punch.out');
        Route::get('/attendance', [AttendanceController::class,'index'])->name('attendance.index');
    });
