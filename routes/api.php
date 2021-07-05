<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\V1\RepaymentController;
use Illuminate\Http\Request;
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

Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);

Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'App\Http\Controllers\Api\V1', 'middleware' => ['auth:sanctum']], function () {
//Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'App\Http\Controllers\Api\V1'], function () {
    Route::apiResource('users', 'UserController');
    Route::apiResource('loans', 'LoanController');
    Route::apiResource('packages', 'PackageController');
    Route::apiResource('repayments', 'RepaymentController');
    Route::post('/makeRepayment/{id}', [RepaymentController::class, 'makeRepayment']);
});
