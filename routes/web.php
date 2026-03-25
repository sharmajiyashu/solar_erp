<?php

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

Route::post('/logout', [WebsiteAuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::post('/website-enquiry', [WebsiteEnquiryController::class, 'store'])->name('website.enquiry.store');
