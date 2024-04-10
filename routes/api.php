<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GetController;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\OrdersController;

use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\FavoritesController;
use App\Http\Controllers\Api\AuthorizationController;




Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['middleware' =>'auth:sanctum'], function(){
Route::get('get', [GetController::class, 'index']);

});




Route::get('items', [ItemController::class, 'index']);
Route::post('items', [ItemController::class, 'store']);
Route::get('items/{id}', [ItemController::class, 'show']);
Route::get('items/{id}/edit', [ItemController::class, 'edit']);
Route::put('items/{id}/edit', [ItemController::class, 'update']);
Route::delete('items/{id}/delete', [ItemController::class, 'destroy']);

//Favorites
Route::get('favorites', [FavoritesController::class, 'index']);
Route::post('favorites', [FavoritesController::class, 'store']);
Route::delete('favorites/{id}/delete', [FavoritesController::class, 'destroy']);
Route::get('favorites-relations', [FavoritesController::class, 'relations_items']);


//orders
Route::get('orders', [OrdersController::class, 'index']);
Route::post('orders', [OrdersController::class, 'store']);

//Авторизация
Route::post('authorization', [AuthorizationController::class, 'authenticate']);

// Регистрация
Route::post('registration', [RegisterController::class, 'registration']);










