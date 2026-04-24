<?php

use App\Http\Controllers\Api\AdminSolarSlotsApiController;
use App\Http\Controllers\Api\PurchasePlanApiController;
use App\Http\Controllers\Api\TicketsApiController;
use App\Http\Controllers\Api\UserSlotsApiController;
use App\Http\Controllers\Firebase\FirebaseCustomTokenController;
use App\Http\Controllers\User\UserSlotController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\WebsiteAuthController;
use App\Http\Controllers\WebsiteEnquiryController;
use App\Http\Controllers\FrontendController;

Route::get('/', [FrontendController::class, 'index'])->name('home');
Route::get('/about', [FrontendController::class, 'about'])->name('about');
Route::get('/service', [FrontendController::class, 'service'])->name('service');
Route::get('/project', [FrontendController::class, 'project'])->name('project');
Route::get('/feature', [FrontendController::class, 'feature'])->name('feature');
Route::get('/team', [FrontendController::class, 'team'])->name('team');
Route::get('/testimonial', [FrontendController::class, 'testimonial'])->name('testimonial');
Route::get('/contact', [FrontendController::class, 'contact'])->name('contact');
Route::get('/quote', [FrontendController::class, 'quote'])->name('quote');

// Website Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [WebsiteAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [WebsiteAuthController::class, 'login'])->name('login.post');
    Route::get('/register', [WebsiteAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [WebsiteAuthController::class, 'register'])->name('register.post');
    Route::get('/register/verify-otp', [WebsiteAuthController::class, 'showRegistrationVerifyOtp'])->name('registration.verify_otp');
    Route::post('/register/verify-otp', [WebsiteAuthController::class, 'verifyRegistrationOtp'])->name('registration.verify.post');
    
    Route::get('/forgot-password', [WebsiteAuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [WebsiteAuthController::class, 'sendOtp'])->name('password.otp');
    Route::get('/verify-otp', [WebsiteAuthController::class, 'showVerifyOtp'])->name('password.verify_otp');
    Route::post('/verify-otp', [WebsiteAuthController::class, 'verifyOtp'])->name('password.verify_otp.post');
    Route::get('/reset-password', [WebsiteAuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [WebsiteAuthController::class, 'resetPassword'])->name('password.update');
});

// User Panel Routes
Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\User\UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [App\Http\Controllers\User\UserDashboardController::class, 'profile'])->name('profile');
    Route::post('/profile/update', [App\Http\Controllers\User\UserDashboardController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/update/password', [App\Http\Controllers\User\UserDashboardController::class, 'updatePassword'])->name('profile.password.update');
    Route::get('/services', [App\Http\Controllers\User\UserDashboardController::class, 'services'])->name('services');
    Route::get('/slots', [UserSlotController::class, 'slots'])->name('slots');
    Route::post('/slots/{slot}/technician-review', [UserSlotController::class, 'storeTechnicianReview'])->name('slots.technician-review');
    Route::get('/tickets', [UserSlotController::class, 'tickets'])->name('tickets.index');
    Route::get('/tickets/{ticket}/firebase-token', [FirebaseCustomTokenController::class, 'userTicketToken'])->name('tickets.firebase-token');
    Route::get('/tickets/{ticket}', [UserSlotController::class, 'ticketShow'])->name('tickets.show');
    Route::post('/tickets', [UserSlotController::class, 'storeTicket'])->name('tickets.store');
    Route::post('/tickets/{ticket}/reply', [UserSlotController::class, 'replyTicket'])->name('tickets.reply');
    Route::post('/fcm-token', [UserSlotController::class, 'saveFcmToken'])->name('fcm_token');

    // Subscription & Payments
    Route::post('/subscription/initiate', [\App\Http\Controllers\User\SubscriptionController::class, 'initiatePayment'])->name('subscription.initiate');
    Route::post('/subscription/verify', [\App\Http\Controllers\User\SubscriptionController::class, 'verifyPayment'])->name('subscription.verify');
});

Route::middleware(['auth'])->prefix('api')->group(function () {
    Route::post('purchase-plan', PurchasePlanApiController::class)->name('api.purchase-plan');
    Route::get('user/slots', UserSlotsApiController::class)->name('api.user.slots');
    Route::post('tickets', [TicketsApiController::class, 'store'])->name('api.tickets.store');
    Route::post('ticket-reply', [TicketsApiController::class, 'reply'])->name('api.tickets.reply');
});

Route::middleware(['auth', 'isAdmin'])->prefix('api')->group(function () {
    Route::get('admin/slots', [AdminSolarSlotsApiController::class, 'index'])
        ->middleware('permission:service_assign')
        ->name('api.admin.slots');
    Route::post('admin/assign-slot', [AdminSolarSlotsApiController::class, 'assign'])
        ->middleware('permission:service_assign')
        ->name('api.admin.assign-slot');
    Route::post('admin/complete-slot', [AdminSolarSlotsApiController::class, 'complete'])
        ->name('api.admin.complete-slot');
});

Route::post('/logout', [WebsiteAuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::post('/website-enquiry', [WebsiteEnquiryController::class, 'store'])->name('website.enquiry.store');

Route::get('/firebase-messaging-sw.js', function () {
    return response()
        ->view('firebase_messaging_sw', [], 200)
        ->header('Content-Type', 'application/javascript; charset=UTF-8')
        ->header('Service-Worker-Allowed', '/');
})->name('firebase.messaging.sw');
