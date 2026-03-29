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
    WebsiteEnquiryController as AdminWebsiteEnquiryController,
    ProductController,
    ServicePackageController,
    AnalysisController,
    CategoryController,
    ProformaInvoiceController,
    ExpenseController,
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
        Route::resource('website-enquiries', AdminWebsiteEnquiryController::class)->names('website-enquiries');
        Route::post('website-enquiries/{id}/status', [AdminWebsiteEnquiryController::class, 'updateStatus'])->name('website-enquiries.updateStatus');

        
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
            Route::post('/{id}/cancel', [LeadController::class, 'cancel'])->name('cancel');

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

        Route::post('/procurement-items/{lead}', [DispatchDetailController::class, 'addProcurementItem'])->name('procurement.addItem');
        Route::delete('/procurement-items/{id}', [DispatchDetailController::class, 'removeProcurementItem'])->name('procurement.removeItem');

        Route::get('/leads/{id}/proforma-invoice', [ProformaInvoiceController::class, 'generate'])->name('leads.proforma.generate');
        Route::get('/leads/{id}/proforma-invoice/view', [ProformaInvoiceController::class, 'view'])->name('leads.proforma.view');

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

        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('index');
            Route::get('/export', [\App\Http\Controllers\Admin\ReportController::class, 'exportCsv'])->name('export');
            Route::get('/attendance', [\App\Http\Controllers\Admin\ReportController::class, 'attendanceReport'])->name('attendance');
            Route::get('/attendance/export', [\App\Http\Controllers\Admin\ReportController::class, 'exportAttendanceExcel'])->name('attendance.export');
            Route::get('/stock', [\App\Http\Controllers\Admin\ReportController::class, 'stockReport'])->name('stock');
            Route::get('/stock/export', [\App\Http\Controllers\Admin\ReportController::class, 'exportStockCsv'])->name('stock.export');
        });

        Route::resource('categories', CategoryController::class);
        Route::post('categories/{id}/status', [CategoryController::class, 'updateStatus'])->name('categories.status');

        Route::get('products/analysis', [AnalysisController::class, 'index'])->name('products.analysis');
        Route::post('products/{id}/stock', [ProductController::class, 'updateStock'])->name('products.updateStock');
        Route::get('products/{id}/stock-history', [ProductController::class, 'stockHistory'])->name('products.stockHistory');
        Route::resource('products', ProductController::class);
        Route::post('products/{id}/status', [ProductController::class, 'updateStatus'])->name('products.status');

        Route::resource('service-packages', ServicePackageController::class);
        Route::post('service-packages/{id}/status', [ServicePackageController::class, 'updateStatus'])->name('service-packages.status');

        Route::get('/', [HomeController::class, 'index'])->name('dashboard');



        Route::post('/punch-in', [AttendanceController::class, 'punchIn'])->name('punch.in');
        Route::post('/punch-out', [AttendanceController::class, 'punchOut'])->name('punch.out');
        Route::get('/attendance', [AttendanceController::class,'index'])->name('attendance.index');

        // Wallet & Expenses
        Route::resource('expenses', ExpenseController::class);
        Route::get('expenses-export', [ExpenseController::class, 'exportExpenses'])->name('expenses.export');
        Route::get('expense-reports', [ExpenseController::class, 'reports'])->name('expense_reports.index');
        Route::get('expense-reports/export', [ExpenseController::class, 'exportReport'])->name('expense_reports.export');
        
        // Wallet Routes
    Route::get('my-wallet', [AdminUserController::class, 'myWallet'])->name('my_wallet');
    Route::get('wallet-management', [AdminUserController::class, 'walletManagement'])->name('wallet_management');
    Route::get('wallet-history/{id}', [AdminUserController::class, 'walletHistory'])->name('wallet_history');
    Route::post('wallet/add-budget', [AdminUserController::class, 'addBudget'])->name('wallet.addBudget');
    });
