<?php

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

Route::post('/api/add-user', 'UserController@addUser');
Route::get('/api/get-user-data/{userId}', 'UserController@getUserData');
Route::put('/api/update-user-data/{userId}', 'UserController@updateUserData');
Route::delete('/api/delete-user/{userId}', 'UserController@deleteUser');
Route::post('/api/upload-user-image/{userId}', 'UserController@uploadUserImage');