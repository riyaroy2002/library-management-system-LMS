<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Librarian\{HomeController,IssueBooksController};
use App\Http\Controllers\Authentication\Librarian\AuthController;

 Route::prefix('librarian')->name('librarian.')->group(function (){

    Route::middleware('guest')->group(function (){

        Route::get('register-librarian'      ,[AuthController::class ,'registerForm'])->name('register-librarian');
        Route::post('register-librarian'     ,[AuthController::class ,'register'])->name('register-librarian.store');
        Route::get('verify-email'            ,[AuthController::class ,'verifyEmailForm'])->name('verify-email');
        Route::post('resend-email-link'      ,[AuthController::class ,'resendEmailLink'])->name('resend-email-link');
        Route::get('verify-email/{token}'    ,[AuthController::class ,'verifyEmail'])->name('post.verify-email');
        Route::get('login'                   ,[AuthController::class ,'loginForm'])->name('login');
        Route::post('login'                  ,[AuthController::class ,'login'])->name('post.login');
        Route::get('forgot-password'         ,[AuthController::class ,'forgotPasswordForm'])->name('forgot-password');
        Route::post('forgot-password'        ,[AuthController::class ,'sendResetLink'])->name('reset-link');
        Route::post('resend-reset-link'      ,[AuthController::class ,'resendResetLink'])->name('resend-link');
        Route::get('reset-password/{token}'  ,[AuthController::class ,'resetPasswordForm'])->name('reset-password');
        Route::post('reset-password'         ,[AuthController::class ,'resetPassword'])->name('post.reset-password');
        Route::get('district/{state_id}'     ,[AuthController::class ,'getDistrict'])->name('district');
    });

    Route::middleware('auth-check:librarian')->group(function () {
        Route::get('/'                       ,[HomeController::class       ,'index'])->name('index');
        Route::get('books'                   ,[IssueBooksController::class ,'books'])->name('books.index');
        Route::get('issue-books'             ,[IssueBooksController::class ,'index'])->name('issue-books.index');
        Route::post('issue-books/{id}'       ,[IssueBooksController::class ,'issue'])->name('issue-books.issue');
        Route::post('return-books/{id}'      ,[IssueBooksController::class ,'return'])->name('issue-books.return');

        Route::get('edit-profile'            ,[AuthController::class  ,'editProfileForm'])->name('edit-profile');
        Route::post('edit-profile'           ,[AuthController::class  ,'editProfile'])->name('post.edit-profile');
        Route::post('logout'                 ,[AuthController::class  ,'logout'])->name('logout');
    });

 });
