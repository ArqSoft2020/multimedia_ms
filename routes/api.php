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

/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::get('multimedia','API\MultimediaController@index')->name('multimedia.index');
Route::post('multimedia/{id}/{type}','API\MultimediaController@store')->name('multimedia.store');

Route::get('multimedia/{id}','API\MultimediaController@show')->name('multimedia.show');
Route::get('multimedia/{id}/{type}/register','API\MultimediaController@show_extended_register')->name('multimedia.showregister');
Route::get('multimedia/{id}/{type}','API\MultimediaController@show_extended_file')->name('multimedia.showfile');

Route::match(['put','patch'],'multimedia/{id}/{type}','API\MultimediaController@update')->name('multimedia.update');
Route::delete('multimedia/{id}/{type}','API\MultimediaController@destroy')->name('multimedia.destroy');
