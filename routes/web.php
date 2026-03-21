<?php

use Illuminate\Support\Facades\Route;

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

Route::post('/website-enquiry', [WebsiteEnquiryController::class, 'store'])->name('website.enquiry.store');
