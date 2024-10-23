<?php

use App\V1\CMS\Controllers\VariantController;

Route::group(['prefix' => 'variants'], function () {
    Route::get('/', [VariantController::class, 'index'])->middleware('permission:view_product');

    Route::get('{productId}/detail/{id}/warehouses', [VariantController::class, 'getWarehouse'])->middleware('permission:view_product');
    Route::post('{productId}/detail/{id}/warehouses', [VariantController::class, 'syncWarehouse'])->middleware('permission:update_product');

    Route::get('{id}', [VariantController::class, 'detail'])->middleware('permission:view_product');
    Route::post('/', [VariantController::class, 'create'])->middleware('permission:add_product');
    Route::match(['post', 'put'], '{id}', [VariantController::class, 'update'])->middleware('permission:update_product');
    Route::delete('{id}', [VariantController::class, 'delete'])->middleware('permission:delete_product');
});