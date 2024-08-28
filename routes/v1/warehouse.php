<?php

use App\V1\CMS\Controllers\WarehouseController;

Route::group(['prefix' => 'warehouses'], function () {
    Route::get('/', [WarehouseController::class, 'index'])->middleware('permission:view_warehouse');
    Route::get('{id}', [WarehouseController::class, 'detail'])->middleware('permission:view_warehouse');
    Route::post('/', [WarehouseController::class, 'create'])->middleware('permission:add_warehouse');
    Route::match(['post', 'put'], '{id}', [WarehouseController::class, 'update'])->middleware('permission:update_warehouse');
    Route::delete('{id}', [WarehouseController::class, 'delete'])->middleware('permission:delete_warehouse');
});