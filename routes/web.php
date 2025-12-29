<?php

use App\Http\Controllers\Admin\AttachmentController;
use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\FrontEndController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Rap2hpoutre\LaravelLogViewer\LogViewerController;

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

    // Logs
    Route::get('/logs', [LogViewerController::class, 'index'])->name('logs');
    // Backup
    Route::group(['prefix' => 'backups'], function () {
        Route::get('/download/{backup}', [BackupController::class, 'downloadBackup'])->name('backups.download');
        Route::get('/', [BackupController::class, 'index'])->name('backups.index');
        Route::post('/make', [BackupController::class, 'make'])->name('backup.make');
        Route::delete('/remove', [BackupController::class, 'remove'])->name('backup.remove');
        Route::post('/restore', [BackupController::class, 'restore'])->name('backup.restore');
        Route::post('/upload', [BackupController::class, 'upload'])->name('backup.upload');
        Route::post('/get-job-status.json', [BackupController::class, 'getJobStatus']);
    });
});
