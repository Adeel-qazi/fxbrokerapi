<?php

use App\Http\Controllers\ApiDataController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::post('store', [ApiDataController::class, 'store']);
Route::post('import-file', [ApiDataController::class, 'importExcel']);
Route::get('/broker', [ApiDataController::class,'fetchBroker']);
Route::get('/scam-broker', [ApiDataController::class,'fetchScamBroker']);
Route::get('/broker-point', [ApiDataController::class,'brokerPoint']);
Route::get('/compare-broker', [ApiDataController::class,'fetchCompareBroker']);
Route::get('/fee', [ApiDataController::class,'fetchFee']);
Route::get('/highest', [ApiDataController::class,'fetchHighest']);
Route::get('fetch-image', [ApiDataController::class,'fetchImages']);
Route::get('/country-code', [ApiDataController::class,'getCountryCodeFromApi']);

Route::get('/file-import',[ApiDataController::class,'importView'])->name('import-view'); 
Route::post('/import',[ApiDataController::class,'import'])->name('import'); 
Route::get('/export-users',[ApiDataController::class,'exportUsers'])->name('export-users');
