<?php

// use Bakerkretzmar\AssetManager\Http\Controllers\AssetManagerToolController;
use Bakerkretzmar\AssetManager\Http\Controllers\FileController;
use Bakerkretzmar\AssetManager\Http\Controllers\FolderController;

use Illuminate\Support\Facades\Route;

// Route::get('data', [AssetManagerToolController::class, 'getData']);

Route::prefix('folders')->group(function () {
    Route::get('info', [FolderController::class, 'show']);
    Route::post('create', [FolderController::class, 'create']);
    Route::post('delete', [FolderController::class, 'destroy']);
});

Route::prefix('files')->group(function () {
    Route::get('info', [FileController::class, 'show']);
    Route::post('upload', [FileController::class, 'create']);
    Route::post('delete', [FileController::class, 'destroy']);
});


// Route::prefix('actions')->group(function () {
//     Route::post('create-folder', [AssetManagerToolController::class, 'createFolder']);
//     Route::post('delete-folder', [AssetManagerToolController::class, 'deleteFolder']);
//     Route::post('get-info', [AssetManagerToolController::class, 'getInfo']);
//     Route::post('delete-file', [AssetManagerToolController::class, 'deleteFile']);
// });

// Route::prefix('uploads')->group(function () {
//     Route::post('add', [AssetManagerToolController::class, 'upload']);
//     Route::get('update', [AssetManagerToolController::class, 'updateFile']);
// });

