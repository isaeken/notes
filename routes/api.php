<?php

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

/**
 * v1 api
 */
Route::prefix('/v1')->middleware('auth:sanctum')->namespace('\App\Http\Controllers')->group(function () {

    /**
     * Note routes
     */
    Route::prefix('/note')->group(function () {
        Route::get('/', 'NoteController@index');
        Route::post('/store', 'NoteController@store');
        Route::get('/read/{id}', 'NoteController@read');
        Route::put('/update/{id}', 'NoteController@update');
        Route::delete('/destroy/{id}', 'NoteController@destroy');
    });

    /**
     * Comment routes
     */
    Route::prefix('/comment')->group(function () {
        Route::get('/', 'CommentController@index');
        Route::post('/store', 'CommentController@store');
        Route::get('/read/{id}', 'CommentController@show');
        Route::put('/update/{id}', 'CommentController@update');
        Route::delete('/destroy/{id}', 'CommentController@destroy');
    });

    /**
     * User routes
     */
    Route::prefix('/user')->group(function () {
        Route::get('/', 'UserController@index');
        Route::get('/read/{id}', 'UserController@show');
    });

});
