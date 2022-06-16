<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\Package\EmailController;

//index
Route::get('/', [HomeController::class,'index'])->name('index');

//email packages

Route::prefix('packages')->name('packages.')->group(function () {
    Route::get('/email', [EmailController::class,'index'])->name('email');
    Route::post('/email', [EmailController::class,'store'])->name('email.store');
});

// https://49295.ir/fononi_new
// adeln1368
// 12345678