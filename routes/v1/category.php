<?php

use App\V1\CMS\Controllers\CategoryController;

Route::group(['prefix' => 'categories'], function () {
    Route::get('/', [CategoryController::class, 'index'])->middleware('permission:view_category');
    Route::get('{id}', [CategoryController::class, 'detail'])->middleware('permission:view_category');
    Route::post('/', [CategoryController::class, 'create'])->middleware('permission:add_category');
    Route::match(['post', 'put'], '{id}', [CategoryController::class, 'update'])->middleware('permission:update_category');
    Route::delete('{id}', [CategoryController::class, 'delete'])->middleware('permission:delete_category');
});