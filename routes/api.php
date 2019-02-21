<?php

use Bakerkretzmar\AssetManager\Http\Controllers\FileController;
use Bakerkretzmar\AssetManager\Http\Controllers\FolderController;

use Illuminate\Support\Facades\Route;

Route::prefix('folders')->group(function () {
    Route::get('info', [FolderController::class, 'show']);
    Route::post('create', [FolderController::class, 'create']);
    Route::post('delete', [FolderController::class, 'destroy']);
});

Route::prefix('files')->group(function () {
    Route::post('upload', [FileController::class, 'create']);
    Route::post('delete', [FileController::class, 'destroy']);
});
