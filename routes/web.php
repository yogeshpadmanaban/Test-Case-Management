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

Auth::routes();

Route::post('/login', 'Auth\LoginController@login')->name('login.submit');

Route::get('/auth/redirect/{provider}', 'SocialController@redirect');
Route::get('/callback/{provider}', 'SocialController@callback');

Route::get('/section', 'SectionController@section_listing');
Route::post('/store_section', 'SectionController@store')->name('store_section');
Route::get('/section_details', 'SectionController@fetch_section_details');
Route::get('/section_update/{id}', 'SectionController@update');
Route::post('/section_delete/{id}', 'SectionController@delete');
Route::post('/module_name_check', 'SectionController@module_name_check');

Route::get('/test_case', 'TestCaseController@test_case_listing');
Route::post('/store_test_case', 'TestCaseController@store')->name('store_test_case');
Route::get('/test_case_details', 'TestCaseController@fetch_test_case_details');
Route::get('/test_case_update/{id}', 'TestCaseController@update');
Route::post('/test_case_delete/{id}', 'TestCaseController@delete');

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/logout', 'Auth\LoginController@logout')->name('logout');
