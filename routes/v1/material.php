<?php

use App\V1\CMS\Controllers\MaterialController;

Route::group(['prefix' => 'materials'], function () {
    Route::get('{id}/warehouses', [MaterialController::class, 'getWarehouse'])->middleware('permission:update_material');
    Route::post('{id}/warehouses', [MaterialController::class, 'syncWarehouse'])->middleware('permission:update_material');
    Route::get('/', [MaterialController::class, 'index'])->middleware('permission:view_material');
    Route::get('{id}', [MaterialController::class, 'detail'])->middleware('permission:view_material');
    Route::post('/', [MaterialController::class, 'create'])->middleware('permission:add_material');
    Route::match(['post', 'put'], '{id}', [MaterialController::class, 'update'])->middleware('permission:update_material');
    Route::delete('{id}', [MaterialController::class, 'delete'])->middleware('permission:delete_material');
});