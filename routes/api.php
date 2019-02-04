<?php

use Bakerkretzmar\AssetManager\Http\Controllers\AssetManagerToolController;
use Bakerkretzmar\AssetManager\Http\Controllers\FolderController;
use Bakerkretzmar\AssetManager\Http\Controllers\FileController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('data', [AssetManagerToolController::class, 'getData']);

Route::prefix('folders')->group(function () {
    Route::post('create', [FolderController::class, 'create']);
    Route::post('delete', [FolderController::class, 'delete']);
});

Route::prefix('files')->group(function () {
    Route::post('info', [FileController::class, 'info']);
    Route::post('upload', [FileController::class, 'upload']);
    Route::post('delete', [FileController::class, 'delete']);
});


Route::prefix('actions')->group(function () {
    Route::post('create-folder', [AssetManagerToolController::class, 'createFolder']);
    Route::post('delete-folder', [AssetManagerToolController::class, 'deleteFolder']);
    Route::post('get-info', [AssetManagerToolController::class, 'getInfo']);
    Route::post('delete-file', [AssetManagerToolController::class, 'deleteFile']);
});

Route::prefix('uploads')->group(function () {
    Route::post('add', [AssetManagerToolController::class, 'upload']);
    Route::get('update', [AssetManagerToolController::class, 'updateFile']);
});

