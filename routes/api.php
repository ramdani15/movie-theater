<?php

use Illuminate\Http\Request;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post('auth', 'UserController@login');
Route::post('register', 'UserController@register');

Route::group(['middleware' => ['jwt.verify']], function(){
	Route::get('/user', 'UserController@index');
	Route::get('/user/{id}', 'UserController@show');
	Route::put('/user/{id}', 'UserController@edit');
	Route::delete('/user/{id}', 'UserController@destroy');

	Route::get('/movie', 'MovieController@index');
	Route::post('/movie', 'MovieController@store');
	Route::get('/movie/{id}', 'MovieController@show');
	Route::put('/movie/{id}', 'MovieController@edit');
	Route::delete('/movie/{id}', 'MovieController@destroy');

	Route::get('/hall', 'HallController@index');
	Route::post('/hall', 'HallController@store');
	Route::get('/hall/{id}', 'HallController@show');
	Route::put('/hall/{id}', 'HallController@edit');
	Route::delete('/hall/{id}', 'HallController@destroy');

	Route::get('/seat', 'SeatController@index');
	Route::post('/seat', 'SeatController@store');
	Route::get('/seat/{id}', 'SeatController@show');
	Route::put('/seat/{id}', 'SeatController@edit');
	Route::delete('/seat/{id}', 'SeatController@destroy');

	Route::get('/show', 'ShowController@index');
	Route::post('/show', 'ShowController@store');
	Route::get('/show/{id}', 'ShowController@show');
	Route::put('/show/{id}', 'ShowController@edit');
	Route::delete('/show/{id}', 'ShowController@destroy');

	Route::get('/ticket', 'TicketController@index');
	Route::post('/ticket', 'TicketController@store');
	Route::get('/ticket/{id}', 'TicketController@show');
	Route::put('/ticket/{id}', 'TicketController@edit');
	Route::delete('/ticket/{id}', 'TicketController@destroy');
});