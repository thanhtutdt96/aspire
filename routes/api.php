<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\V1\LoanController;
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
    Route::apiResource('users', 'UserController');
    Route::apiResource('loans', 'LoanController');
    Route::apiResource('packages', 'PackageController');
    Route::apiResource('repayments', 'RepaymentController');
    Route::post('/make-repayment/{id}', [RepaymentController::class, 'makeRepayment']);
    Route::get('/get-loans', [LoanController::class, 'getLoansByUser']);
    Route::post('/approve-loan/{id}', [LoanController::class, 'approveLoan']);
});
