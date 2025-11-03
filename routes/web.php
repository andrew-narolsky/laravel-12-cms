<?php

use App\Http\Controllers\Admin\AttachmentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\FrontEndController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [FrontEndController::class, 'show']);

Route::group(['prefix' => 'auth'], function () {
    Auth::routes([
        'register' => false,
        'reset'    => true,
        'verify'   => false,
    ]);
});

Route::group(['middleware' => 'auth', 'prefix' => 'admin'], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin');
    Route::resource('/users', UserController::class)->except(['show']);

    Route::post('/attachments/upload', [AttachmentController::class, 'upload'])->name('attachments.upload');
    Route::delete('/attachments/{attachment}', [AttachmentController::class, 'destroy'])->name('attachments.destroy');
});
