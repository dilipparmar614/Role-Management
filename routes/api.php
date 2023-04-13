<?php

use Illuminate\Http\Request;
use Auth;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Artisan;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('get_all_user', [UserController::class, 'index'])->name('get_all_user');

Route::group(['middleware' => ['verifyjwttoken','user_role'], 'prefix' => 'users'], function ($request) {


Route::post('store',[UserController::class,'store'])->name('add');

Route::get('edit/{id}',[UserController::class,'edit'])->name('edit');

Route::post('update/{id}',[UserController::class,'update'])->name('update');

Route::post('delete/{id}', [UserController::class, 'destroy'])->name('delete');

Route::get('search', [UserController::class, 'search_user'])->name('search');

Route::get('get_user', [UserController::class, 'get_user'])->name('get_user');
});

Route::group(['middleware' => 'api','prefix' => 'auth'], function ($router) {

    Route::post('/login', [AuthController::class, 'login'])->name('login');

    Route::post('/register', [AuthController::class, 'register'])->name('register');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::post('/refresh', [AuthController::class, 'refresh'])->name('refresh');
    
    Route::get('/user-profile', [AuthController::class, 'userProfile']);    
});