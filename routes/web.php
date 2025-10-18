<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

// Matikan semua middleware (kecuali untuk Telescope)
Route::middleware([])->group(function () {

    Route::get('/', [PaymentController::class, 'index'])->name('donation.index');

    Route::post('/donate', [PaymentController::class, 'createTransaction'])->name('donation.create');

    Route::get('/donation/status/{orderId}', [PaymentController::class, 'checkStatus'])->name('donation.status');

    Route::get('/donation/success/{orderId}', [PaymentController::class, 'success'])->name('donation.success');

    Route::get('/donations', [PaymentController::class, 'list'])->name('donation.list');

    Route::post('/api/midtrans/notification', [PaymentController::class, 'notification'])
        ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])
        ->name('midtrans.notification');
});
