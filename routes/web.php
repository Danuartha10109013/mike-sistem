<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AssetLabelController;
use App\Http\Controllers\AssetPublicController;

Route::get('/', static function () {
    return redirect('/admin');
});
Route::get('/asset/{asset}/label', [AssetLabelController::class, 'show'])->name('asset.label');


Route::get('/asset/public/{id}', [AssetPublicController::class, 'show'])->name('asset.public.show');
