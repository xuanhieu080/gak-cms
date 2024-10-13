<?php

use App\V1\CMS\Controllers\CustomerController;

Route::group(['prefix' => 'customers'], function () {
    Route::get('/', [CustomerController::class, 'index'])->middleware('permission:view_customer');
    Route::get('{id}', [CustomerController::class, 'detail'])->middleware('permission:view_customer');
    Route::post('/', [CustomerController::class, 'create'])->middleware('permission:add_customer');
    Route::match(['post', 'put'], '{id}', [CustomerController::class, 'update'])->middleware('permission:update_customer');
    Route::delete('{id}', [CustomerController::class, 'delete'])->middleware('permission:delete_customer');
});