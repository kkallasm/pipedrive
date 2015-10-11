<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('data', [
    'as' => 'data', 'uses' => 'OrganizationController@generateData'
]);

Route::post('relations', [
    'as' => 'post.org.relations', 'uses' => 'RelationController@create'
]);

Route::get('relations', [
    'as' => 'get.org.relations', 'uses' => 'RelationController@show'
]);

Route::delete('relations', [
    'as' => 'delete.org.relations', 'uses' => 'RelationController@delete'
]);
