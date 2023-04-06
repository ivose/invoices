<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::middleware(['web'])->group(function () {
    Auth::routes(['verify' => true]);
});
