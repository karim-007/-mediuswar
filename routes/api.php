<?php
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
Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'authentication']);

Route::group(['middleware' => ['auth:api']], function (){
    
    Route::post('/users', [App\Http\Controllers\UserController::class,'store']);
    Route::get('/show', [App\Http\Controllers\TransactionController::class,'allTransactions']);
    Route::get('/deposit', [App\Http\Controllers\TransactionController::class,'depositTransactions']);
    Route::post('/deposit', [App\Http\Controllers\TransactionController::class,'store']);
    Route::get('/withdrawal', [App\Http\Controllers\TransactionController::class,'withdrawTransactions']);
    Route::post('/withdrawal', [App\Http\Controllers\TransactionController::class,'withdraw']);

});