<?php

use App\V1\CMS\Controllers\ProductController;

Route::group(['prefix' => 'products'], function () {
    Route::get('/', [ProductController::class, 'index'])->middleware('permission:view_product');
    Route::get('{id}', [ProductController::class, 'detail'])->middleware('permission:view_product');
    Route::post('/', [ProductController::class, 'create'])->middleware('permission:add_product');
    Route::match(['post', 'put'], '{id}', [ProductController::class, 'update'])->middleware('permission:update_product');
    Route::delete('{id}', [ProductController::class, 'delete'])->middleware('permission:delete_product');
});