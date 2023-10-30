<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DownloadController;
use Illuminate\Support\Facades\Route;

Route::get('/', [UploadController::class, 'index'])->name('torrents.index');

Route::resources([
    'uploads' => UploadController::class,
    'comments' => CommentController::class,
    'users' => UserController::class
]);

Route::controller(AuthController::class)->group(function () {
    Route::get('login', 'login')->name('users.login');
    Route::post('authenticate', 'authenticate')->name('users.authenticate');
    Route::get('profile', 'profile')->name('users.profile');
    Route::post('logout', 'logout')->name('users.logout');
});

Route::controller(SearchController::class)->group(function () {
    Route::get('search', 'search')->name('uploads.search');
    Route::get('user/{name}/uploads', 'uploads')->name('user.uploads');
});

Route::get('/uploads/{upload}/download', DownloadController::class)->name('uploads.download');

Route::view('help', 'help')->name('help');

Route::view('about', 'about')->name('about');

Route::view('rules', 'rules')->name('rules');