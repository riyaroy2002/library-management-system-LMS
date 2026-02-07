<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Member\{HomeController,BooksController,FinesController,RequestBooksController};
use App\Http\Controllers\Authentication\Member\AuthController;

    Route::middleware('guest')->group(function (){
        Route::get('register-member'                ,[AuthController::class ,'registerForm'])->name('register-member');
        Route::post('register-member'               ,[AuthController::class ,'register'])->name('register-member.store');
        Route::get('verify-email'                   ,[AuthController::class ,'verifyEmailForm'])->name('verify-email');
        Route::post('resend-email-link'             ,[AuthController::class ,'resendEmailLink'])->name('resend-email-link');
        Route::get('verify-email/{token}'           ,[AuthController::class ,'verifyEmail'])->name('post.verify-email');
        Route::get('login'                          ,[AuthController::class ,'loginForm'])->name('login');
        Route::post('login'                         ,[AuthController::class ,'login'])->name('post.login');
        Route::get('forgot-password'                ,[AuthController::class ,'forgotPasswordForm'])->name('forgot-password');
        Route::post('forgot-password'               ,[AuthController::class ,'sendResetLink'])->name('reset-link');
        Route::post('resend-reset-link'             ,[AuthController::class ,'resendResetLink'])->name('resend-link');
        Route::get('reset-password/{token}'         ,[AuthController::class ,'resetPasswordForm'])->name('reset-password');
        Route::post('reset-password'                ,[AuthController::class ,'resetPassword'])->name('post.reset-password');
         Route::get('state-district/{state_id}'     ,[AuthController::class ,'getDistrictByState'])->name('state-district');
    });

    Route::middleware('auth-check:member')->group(function () {
        Route::get('member'                  ,[HomeController::class  ,'index'])->name('index');
        Route::get('books'                   ,[BooksController::class ,'index'])->name('books.index');
        Route::get('request-books'           ,[RequestBooksController::class ,'index'])->name('request-books.index');
        Route::get('books-request'           ,[RequestBooksController::class ,'books'])->name('books-request.books');
        Route::post('books-request'          ,[RequestBooksController::class ,'store'])->name('books-request.store');
        Route::get('fines'                   ,[FinesController::class ,'index'])->name('fines.index');
        Route::get('district/{state_id}'     ,[AuthController::class  ,'getDistrict'])->name('district');
        Route::get('edit-profile'            ,[AuthController::class  ,'editProfileForm'])->name('edit-profile');
        Route::post('edit-profile'           ,[AuthController::class  ,'updateProfile'])->name('profile.update');
        Route::post('logout'                 ,[AuthController::class  ,'logout'])->name('logout');
    });


