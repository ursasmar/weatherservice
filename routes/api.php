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

Route::namespace('API\V1')->prefix('v1')->group(function() {
    Route::get('/wind/{zipCode}', 'WindController@show')->name('api.v1.wind.show');
});

