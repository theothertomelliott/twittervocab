<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', "StatsController@getChooseHandle");

Route::get('/angular/', function() { return "Not yet implemented"; });

Route::group(array('prefix' => '/api'), function()
{
	Route::get('{handle}/wordcount', "StatsController@getWordCount");
	Route::get('{handle}', "StatsController@getUserInfo");
	Route::get('currentuser',"StatsController@getCurrentUser");
});

Route::post('/handle', function(){
	return Redirect::to("/" . Input::get('handle'));
});

Route::get("{handle}", "StatsController@getStatsAngular");
Route::get("/old/{handle}", "StatsController@getWordStats");

