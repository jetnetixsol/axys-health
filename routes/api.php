<?php

use App\Http\Controllers\MioApiController;
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

// Route::post('/forwardstatus',[MioApiController::class,'']);


Route::post('/forwardstatus',[MioApiController::class,'deviceStatus']);
Route::post('/forwardtelemetry',[MioApiController::class,'telemetryData']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
