<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->namespace('\App\Http\Controllers')->group(function () {
    Route::get('/', 'HomeController@index')->name('web.home.index');

    Route::prefix('/notes')->group(function () {
        Route::get('/create', 'NoteController@create')->name('web.notes.create');
        Route::get('/show/{id}',  'NoteController@show')  ->name('web.notes.show');
        Route::get('/update/{id}','NoteController@edit')->name('web.notes.edit');
        Route::get('/delete/{id}','NoteController@delete')->name('web.notes.delete');
    });

    Route::prefix('/comments')->group(function () {
        Route::get('/create', 'CommentController@create')->name('web.comments.create');
        Route::get('/show/{id}',  'CommentController@show')  ->name('web.comments.show');
        Route::get('/update/{id}','CommentController@edit')->name('web.comments.edit');
        Route::get('/delete/{id}','CommentController@delete')->name('web.comments.delete');
    });
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
