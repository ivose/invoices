<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InvoiceController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [InvoiceController::class, 'index'])
        ->middleware(['auth'])
        ->name('home');
    Route::get('/invoice/{invoice}', [InvoiceController::class, 'show'])
        ->middleware(['auth'])
        ->name('invoice');
    //P1
    Route::get('/upload', [InvoiceController::class, 'getUpload'])->name('upload');
    Route::post('/upload', [InvoiceController::class, 'postUpload'])->name('upload');
});

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'getLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'postLogin']);
    Route::get('/register', [AuthController::class, 'getRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'postRegister']);
    Route::get('/forgot-password', [AuthController::class, 'getForgot'])
        ->name('password.request');
    Route::post('/resetpasswordemail', [AuthController::class, 'postForgot'])
        ->name('password.email');

    Route::get('password/reset/{token}', [AuthController::class, 'getReset'])
        ->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'postReset'])
        ->name('password.update');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/email/verify', [AuthController::class, 'verify'])->name('verification.notice');
    Route::get('/email/verify/{token}', [AuthController::class, 'verifyToken'])->name('verification.verify');
    Route::get('/email/resend', [AuthController::class, 'resend'])->middleware(['throttle:6,1'])->name('verification.send');

    Route::get('/password', [AuthController::class, 'getPassword'])->name('password');
    Route::post('/password', [AuthController::class, 'postPassword'])->name('password');
    Route::match(['GET', 'POST'], '/logout', [AuthController::class, 'logout'])->middleware(['auth'])->name('logout');
});

