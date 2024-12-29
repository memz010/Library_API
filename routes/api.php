<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\RateController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\SellController;
use App\Http\Controllers\UserController;
use App\Models\Book;
use App\Models\BookCategory;
use App\Models\Category;

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

Route::group(['prefix' => 'auth'], function () {
    Route::post('/register', [ AuthController::class ,'register']);
    Route::post('/login', [ AuthController::class ,'login'])->name('lgoin');
    Route::middleware('auth:api')->get('/logout', [ AuthController::class ,'logout']);

    Route::group(['prefix' => '/google'], function() {
        Route::get('redirect', [ GoogleController::class, 'redirect']);
        Route::get('callback', [ GoogleController::class, 'callback']);
    });
});
Route::post('books/{book}/buy',[SellController::class,'store']);
Route::post('books/{book}/rate',[RateController::class,'rate']);
Route::post('books/{book}/favorite',[FavoriteController::class,'favorite']);
Route::get('books/{book}/favorite',[FavoriteController::class,'isfavorite']);

Route::post('books/{book}/comment',[CommentController::class,'store']);
Route::post('comments/{comment}',[CommentController::class,'update']);
Route::delete('comments/{comment}',[CommentController::class,'destroy']);


Route::get('authors/search', [AuthorController::class,'search']);
Route::apiResource('authors',AuthorController::class);
Route::post('authors/{author}',[AuthorController::class, 'update']);

Route::apiResource('categories',CategoryController::class);
Route::post('categories/{category}',[CategoryController::class, 'update']);

Route::apiResource('users',UserController::class);
Route::post('users/{category}',[UserController::class, 'update']);


Route::get('books/favorite',[BookController::class, 'favorite']);
Route::get('books/free',[BookController::class, 'free']);
Route::get('books/new',[BookController::class, 'new']);
Route::get('books/bestsells',[BookController::class, 'bestSells']);
Route::get('books/bestrate',[BookController::class, 'bestRate']);
Route::get('books/search', [BookController::class,'search']);
Route::apiResource('books',BookController::class);
Route::post('books/{book}',[BookController::class, 'update']);


Route::post('users/{user}/addpoint',[UserController::class, 'addbalance']);
Route::post('users/{user}/removepoint',[UserController::class,'removebalance']);