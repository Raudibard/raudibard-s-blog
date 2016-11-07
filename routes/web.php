<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Auth::routes();

Route::get('/', 'HomeController@index');

Route::get('/articles', ['middleware' => 'ajax', 'uses' => 'ArticlesController@index']);
Route::post('/articles/create', 'ArticlesController@store');
Route::get('/articles/{id}', ['middleware' => 'ajax', 'uses' => 'ArticlesController@show']);
Route::get('/articles/{id}/edit', ['middleware' => 'ajax', 'uses' => 'ArticlesController@edit']);
Route::patch('/articles/{id}/update', 'ArticlesController@update');
Route::delete('/articles/{id}/delete', 'ArticlesController@delete');
Route::post('/articles/{id}/ignore', 'ArticlesController@ignore');
Route::post('/articles/{id}/like', 'ArticlesController@like');

Route::post('/comments/create', 'CommentsController@store');
Route::get('/comments/{id}', ['middleware' => 'ajax', 'uses' => 'CommentsController@show']);
Route::get('/comments/{id}/edit', ['middleware' => 'ajax', 'uses' => 'CommentsController@edit']);
Route::patch('/comments/{id}/update', 'CommentsController@update');
Route::delete('/comments/{id}/delete', 'CommentsController@delete');
Route::post('/comments/{id}/like', 'CommentsController@like');

Route::post('/upload', 'ArticlesController@uploadPhoto');
Route::delete('/remove/{id}', 'ArticlesController@deletePhoto');