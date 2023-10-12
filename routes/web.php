<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home/index page
Route::get('/', [UploadController::class, 'index'])->name('torrents.index');

/*
|--------------------------------------------------------------------------
| Resource controller routing
|--------------------------------------------------------------------------
*/

Route::resource('uploads', UploadController::class);

Route::resource('comments', CommentController::class);

Route::resource('users', UserController::class);

/*
|--------------------------------------------------------------------------
| Simple view routing
|--------------------------------------------------------------------------
*/

Route::view('help', 'help')->name('help');

Route::view('about', 'about')->name('about');

Route::view('rules', 'rules')->name('rules');

/*
|--------------------------------------------------------------------------
| Special upload routing
|--------------------------------------------------------------------------
*/

Route::get('/uploads/{upload}/download', [UploadController::class, 'download'])->name('uploads.download');

Route::get('search', [UploadController::class, 'search'])->name('uploads.search');

/*
|--------------------------------------------------------------------------
| Special user routing
|--------------------------------------------------------------------------
*/

Route::get('/user/{user}/profile', [UserController::class, 'show'])->name('user.show');

Route::get('profile', [UserController::class, 'profile'])->name('users.profile');

Route::get('login', [UserController::class, 'login'])->name('users.login');

Route::get('{name}/uploads', [UserController::class, 'uploads'])->name('user.uploads');

Route::post('authenticate', [UserController::class, 'authenticate'])->name('users.authenticate');

Route::post('logout', [UserController::class, 'logout'])->name('users.logout');

/*
|--------------------------------------------------------------------------
| Special comment routing
|--------------------------------------------------------------------------
*/

Route::patch('/update/{comment}', [CommentController::class, 'update'])->name('comments.update');