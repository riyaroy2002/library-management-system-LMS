<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\{HomeController,ContactUsController,NewsLetterController};

    Route::get('/'                   ,[HomeController::class      ,'index'])->name('home');
    Route::get('about-us'            ,[HomeController::class      ,'aboutUs'])->name('about-us');
    Route::get('gallery'             ,[HomeController::class      ,'gallery'])->name('gallery');
    Route::get('district/{state_id}' ,[HomeController::class      ,'getDistrict'])->name('district');
    Route::get('contact-us'          ,[HomeController::class      ,'contactUs'])->name('contact-us');
    Route::get('explore-now'         ,[HomeController::class      ,'exploreNow'])->name('explore-now');
    Route::post('contact-us'         ,[ContactUsController::class ,'store'])->name('contact-us.store');
    Route::post('news-letter'        ,[NewsLetterController::class,'store'])->name('news-letter.store');
