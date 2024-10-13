<?php

use App\V1\CMS\Controllers\UnitController;

Route::group(['prefix' => 'units'], function () {
    Route::get('/', [UnitController::class, 'index'])->middleware('permission:view_unit');
    Route::get('{id}', [UnitController::class, 'detail'])->middleware('permission:view_unit');
    Route::post('/', [UnitController::class, 'create'])->middleware('permission:add_unit');
    Route::match(['post', 'put'], '{id}', [UnitController::class, 'update'])->middleware('permission:update_unit');
    Route::delete('{id}', [UnitController::class, 'delete'])->middleware('permission:delete_unit');
});