<?php

use App\V1\CMS\Controllers\AttributeController;

Route::group(['prefix' => 'attributes'], function () {
    Route::get('/', [AttributeController::class, 'index'])->middleware('permission:view_attribute');
    Route::get('{id}', [AttributeController::class, 'detail'])->middleware('permission:view_attribute');
    Route::post('/', [AttributeController::class, 'create'])->middleware('permission:add_attribute');
    Route::match(['post', 'put'], '{id}', [AttributeController::class, 'update'])->middleware('permission:update_attribute');
    Route::delete('{id}', [AttributeController::class, 'delete'])->middleware('permission:delete_attribute');
});