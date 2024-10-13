<?php


use App\V1\CMS\Controllers\AuthController;
use App\V1\CMS\Controllers\TokenController;
use App\V1\CMS\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::pattern('id', '[0-9]+');
Route::post('/sanctum/token', TokenController::class);


Route::post('auth/login',  function () {
    $user = \App\Models\User::first();
    $token = $user->createToken('user')->plainTextToken;
    return response()->json(['token' => $token], 200);
})->middleware('guest');
Route::middleware(['auth:sanctum', 'apply_locale'])->group(function () {

    /**
     * Auth related
     */
    Route::get('/users/auth', AuthController::class);


    require __DIR__ . '/v1/user.php';
    require __DIR__ . '/v1/attribute.php';
    require __DIR__ . '/v1/attribute_group.php';
    require __DIR__ . '/v1/warehouse.php';
    require __DIR__ . '/v1/material.php';
    require __DIR__ . '/v1/category.php';
    require __DIR__ . '/v1/customer.php';
    require __DIR__ . '/v1/unit.php';
});
