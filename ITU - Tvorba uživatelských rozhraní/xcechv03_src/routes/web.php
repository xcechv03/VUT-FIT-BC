<?php

// web.php
//
// autor: Jan Procházka (xproch0g)
//

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\ItemController;
use \App\Http\Controllers\HomepageController;
use \App\Http\Controllers\CartController;
use \App\Http\Controllers\HistoryController;
use App\Models\Item;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [ItemController::class, 'index']);

Route::get('/print', [HomepageController::class, 'print']);
Route::get('/pay', [HomepageController::class, 'pay']);

Route::get('/item/{id}', [ItemController::class, 'show']);
Route::get('/create_item', [ItemController::class, 'create']);
Route::post('/create_item/create', [ItemController::class, 'createInDb']);
Route::get('/add_item/{id}', [ItemController::class, 'add']); // to cart
Route::get('/delete_item/{id}', [ItemController::class, 'delete']);
Route::post('/update_item/{id}', [ItemController::class, 'update']);
Route::get('removeItem/{id}', 'CartController@remove');
Route::post('/add_to_cart/{id}', [CartController::class, 'add']);
Route::get('/remove_from_cart/{id}', [CartController::class, 'remove']);

Route::get('/history/{id}', [HistoryController::class, 'show']);

Route::view('items_refresh', 'items_refresh', [
    'items' => Item::latest()->filter(\request(['search', 'category', 'price', 'weight']))->get()
]);

require __DIR__.'/auth.php';
