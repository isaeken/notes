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

Route::middleware('auth')->namespace('\App\Http\Controllers\Web')->group(function () {
    Route::get('/', 'HomeController@index')->name('web.home.index');
    Route::prefix('/notes')->group(function () {
        Route::get('/create', 'NoteController@create')->name('web.notes.create');
        Route::get('/show/{id}',  'NoteController@show')  ->name('web.notes.show');
        Route::get('/edit/{id}','NoteController@edit')->name('web.notes.edit');
        Route::get('/delete/{id}','NoteController@delete')->name('web.notes.delete');
    });
});
