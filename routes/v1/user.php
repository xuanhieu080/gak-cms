<?php

use App\V1\CMS\Controllers\UserController;

Route::group(['prefix' => 'users'], function () {
    Route::get('/',  [UserController::class, 'index'])->middleware('permission:view_user');
    Route::get('{id}',  [UserController::class, 'detail'])->middleware('permission:view_user');
    Route::post('/',  [UserController::class, 'create'])->middleware('permission:add_user');
    Route::match(['post', 'put'], '{id}/avatar',  [UserController::class, 'updateAvatar'])->middleware('permission:update_user');
    Route::match(['post', 'put'], '{id}/sync-permissions',  [UserController::class, 'syncPermission'])->middleware('permission:sync_permission');
    Route::match(['post', 'put'], '{id}',  [UserController::class, 'update'])->middleware('permission:update_user');
    Route::delete('{id}',  [UserController::class, 'delete'])->middleware('permission:delete_user');
});