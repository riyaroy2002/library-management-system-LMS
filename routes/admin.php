<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Authentication\Admin\AuthController;
use App\Http\Controllers\Admin\{CMSController,GalleryController,HomeController,UserController,InquiriesController,NewsLettersController};
use App\Http\Controllers\Admin\{AuthorsController,CategoriesController,BooksController,BooksIssuesController,FinesController};

    Route::prefix('admin')->name('admin.')->group(function (){

        Route::get('login'                      ,[AuthController::class       ,'loginForm'])->name('login');
        Route::post('login'                     ,[AuthController::class       ,'login'])->name('post.login');
        Route::get('forgot-password'            ,[AuthController::class       ,'forgotPasswordForm'])->name('forgot-password');
        Route::post('forgot-password'           ,[AuthController::class       ,'sendResetLink'])->name('reset-link');
        Route::post('resend-reset-link'         ,[AuthController::class       ,'resendResetLink'])->name('resend-link');
        Route::get('reset-password/{token}'     ,[AuthController::class       ,'resetPasswordForm'])->name('reset-password');
        Route::post('reset-password'            ,[AuthController::class       ,'resetPassword'])->name('post.reset-password');

        Route::middleware('auth-check:admin')->group(function () {

            Route::get('/'                             ,[HomeController::class       , 'index'])->name('index');

            Route::get('authors'                       ,[AuthorsController::class    , 'index'])->name('authors.index');
            Route::get('create-author'                 ,[AuthorsController::class    , 'create'])->name('authors.create');
            Route::post('create-author'                ,[AuthorsController::class    , 'store'])->name('authors.store');
            Route::get('edit-author/{id}'              ,[AuthorsController::class    , 'edit'])->name('authors.edit');
            Route::post('update-author/{id}'           ,[AuthorsController::class    , 'update'])->name('authors.update');
            Route::post('delete-author/{id}'           ,[AuthorsController::class    , 'destroy'])->name('authors.delete');
            Route::get('trash-author'                  ,[AuthorsController::class    , 'trash'])->name('authors.trash');
            Route::post('author-restore/{id}'          ,[AuthorsController::class    , 'restore'])->name('authors.restore');
            Route::post('author-permanent-delete/{id}' ,[AuthorsController::class ,'forceDelete'])->name('authors.permanent-delete');

            Route::get('categories'                     ,[CategoriesController::class , 'index'])->name('categories.index');
            Route::get('create-category'                ,[CategoriesController::class , 'create'])->name('categories.create');
            Route::post('create-category'               ,[CategoriesController::class , 'store'])->name('categories.store');
            Route::get('edit-category/{id}'             ,[CategoriesController::class , 'edit'])->name('categories.edit');
            Route::post('update-category/{id}'          ,[CategoriesController::class , 'update'])->name('categories.update');
            Route::post('delete-category/{id}'          ,[CategoriesController::class , 'destroy'])->name('categories.delete');
            Route::get('trash-category'                 ,[CategoriesController::class , 'trash'])->name('categories.trash');
            Route::post('category-restore/{id}'         ,[CategoriesController::class , 'restore'])->name('categories.restore');
            Route::post('category-permanent-delete/{id}',[CategoriesController::class ,'forceDelete'])->name('categories.permanent-delete');

            Route::get('books'                      ,[BooksController::class      , 'index'])->name('books.index');
            Route::get('create-book'                ,[BooksController::class      , 'create'])->name('books.create');
            Route::post('create-book'               ,[BooksController::class      , 'store'])->name('books.store');
            Route::get('edit-book/{id}'             ,[BooksController::class      , 'edit'])->name('books.edit');
            Route::post('update-book/{id}'          ,[BooksController::class      , 'update'])->name('books.update');
            Route::post('delete-book/{id}'          ,[BooksController::class      , 'destroy'])->name('books.delete');
            Route::get('trash-book'                 ,[BooksController::class      , 'trash'])->name('books.trash');
            Route::post('book-restore/{id}'         ,[BooksController::class      , 'restore'])->name('books.restore');
            Route::post('book-permanent-delete/{id}',[BooksController::class      , 'forceDelete'])->name('books.permanent-delete');

            Route::get('issued-books'               ,[BooksIssuesController::class, 'index'])->name('issued-books.index');
            Route::get('fines'                      ,[FinesController::class      , 'index'])->name('fines.index');

            Route::get('members'                    ,[UserController::class       , 'members'])->name('members.index');
            Route::post('toggle-member/{id}'        ,[UserController::class       , 'toggleBlockMember'])->name('members.toggle-member');

            Route::get('librarians'                   ,[UserController::class       , 'librarians'])->name('librarians.index');
            Route::get('create-librarian'             ,[UserController::class       , 'create'])->name('librarians.create');
            Route::post('create-librarian'            ,[UserController::class       , 'store'])->name('librarians.store');
            Route::get('edit-librarian/{id}'          ,[UserController::class       , 'edit'])->name('librarians.edit');
            Route::post('update-librarian/{id}'       ,[UserController::class       , 'update'])->name('librarians.update');
            Route::post('toggle-librarian/{id}'       ,[UserController::class       , 'toggleBlockLibrarian'])->name('librarians.toggle-librarian');
            Route::get('district-librarian/{state_id}',[UserController::class       , 'getDistrict'])->name('librarians.district');

            Route::get('cms'                        ,[CMSController::class        , 'index'])->name('cms.index');
            Route::get('create-cms'                 ,[CMSController::class        , 'create'])->name('cms.create');
            Route::post('create-cms'                ,[CMSController::class        , 'store'])->name('cms.store');
            Route::get('edit-cms/{id}'              ,[CMSController::class        , 'edit'])->name('cms.edit');
            Route::post('update-cms/{id}'           ,[CMSController::class        , 'update'])->name('cms.update');

            Route::get('gallery'                       ,[GalleryController::class    , 'index'])->name('gallery.index');
            Route::get('create-gallery'                ,[GalleryController::class    , 'create'])->name('gallery.create');
            Route::post('create-gallery'               ,[GalleryController::class    , 'store'])->name('gallery.store');
            Route::get('edit-gallery/{id}'             ,[GalleryController::class    , 'edit'])->name('gallery.edit');
            Route::post('update-gallery/{id}'          ,[GalleryController::class    , 'update'])->name('gallery.update');
            Route::post('delete-gallery/{id}'          ,[GalleryController::class    , 'destroy'])->name('gallery.delete');
            Route::get('trash-gallery'                 ,[GalleryController::class    , 'trash'])->name('gallery.trash');
            Route::post('gallery-restore/{id}'         ,[GalleryController::class    , 'restore'])->name('gallery.restore');
            Route::post('gallery-permanent-delete/{id}',[GalleryController::class    , 'forceDelete'])->name('gallery.permanent-delete');

            Route::get('inquiries'              ,[InquiriesController::class  , 'index'])->name('inquiries.index');
            Route::get('reply-back/{id}'        ,[InquiriesController::class  , 'replyBack'])->name('inquiries.reply-back');
            Route::get('view/{id}'              ,[InquiriesController::class  , 'view'])->name('inquiries.view');
            Route::post('reply-back/{id}'       ,[InquiriesController::class  , 'store'])->name('inquiries.reply-back.store');




            Route::get('news-letters'           ,[NewsLettersController::class  , 'index'])->name('news-letters.index');
            Route::post('logout'                ,[AuthController::class         , 'logout'])->name('logout');

        });
    });




