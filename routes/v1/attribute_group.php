<?php

use App\V1\CMS\Controllers\AttributeGroupController;

Route::group(['prefix' => 'attribute-groups'], function () {
    Route::get('/',  [AttributeGroupController::class, 'index'])->middleware('permission:view_attribute_group');
    Route::get('{id}',  [AttributeGroupController::class, 'detail'])->middleware('permission:view_attribute_group');
    Route::post('/',  [AttributeGroupController::class, 'create'])->middleware('permission:add_attribute_group');
    Route::match(['post', 'put'], '{id}',  [AttributeGroupController::class, 'update'])->middleware('permission:update_attribute_group');
    Route::delete('{id}',  [AttributeGroupController::class, 'delete'])->middleware('permission:delete_attribute_group');
});