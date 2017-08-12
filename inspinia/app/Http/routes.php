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

//Administration
Route::resource("administrations","AdministrationController");
Route::get('administrations.status/{id}', ['as' => 'administrations.status', 'uses' => 'AdmininstrationController@status']);

//Authorization
Route::resource("authorizations","AuthorizationController");
Route::get('authorizations.status/{id}', ['as' => 'authorizations.status', 'uses' => 'AuthorizationController@status']);

//Citizen
Route::resource("citizens","CitizenController");
Route::get('citizens.status/{id}', ['as' => 'citizens.status', 'uses' => 'CitizenController@status']);

//Company
Route::resource("company","CompanyController");

//*** Combos anidados ***
Route::get('get_municipalities/{id}', 'MunicipalityController@getMunicipalities');

//Contract
Route::resource("contracts","ContractController");
Route::get('contracts.create/{id}', ['as' => 'contracts.create', 'uses' => 'ContractController@create']);
Route::get('contracts.status/{id}', ['as' => 'contracts.status', 'uses' => 'ContractController@status']);
Route::get('contracts.citizen_contracts/{id}', ['as' => 'contracts.citizen_contracts', 'uses' => 'ContractController@citizen_contracts']);

//Home
Route::get('/', 'HomeController@index');
Route::get('home', ['as' => 'home', 'uses' => 'HomeController@index']);

//Img
Route::get('user_avatar/{id}', 'ImgController@showUserAvatar');
Route::get('authorization_avatar/{id}', 'ImgController@showAuthorizationAvatar');
Route::get('citizen_avatar/{id}', 'ImgController@showCitizenAvatar');
Route::get('inspector_avatar/{id}', 'ImgController@showInspectorAvatar');
Route::get('company_logo/{id}', 'ImgController@showCompanyLogo');

//Inspector
Route::resource("inspectors","InspectorController");
Route::get('inspectors.status/{id}', ['as' => 'inspectors.status', 'uses' => 'InspectorController@status']);

//Login
Route::auth();

//Municipality
Route::resource("municipalities","MunicipalityController");
Route::get('municipalities.status/{id}', ['as' => 'municipalities.status', 'uses' => 'MunicipalityController@status']);

//RateType
Route::resource("rate_types","RateTypeController");
Route::get('rate_types.status/{id}', ['as' => 'rate_types.status', 'uses' => 'RateTypeController@status']);

//Rate
Route::resource("rates","RateController");
Route::get('rates.status/{id}', ['as' => 'rates.status', 'uses' => 'RateController@status']);

//Setting
Route::resource("settings","SettingController");

//State
Route::resource("states","StateController");
Route::get('states.status/{id}', ['as' => 'states.status', 'uses' => 'StateController@status']);

//User
Route::resource("users","UserController");
Route::get('users.status/{id}', ['as' => 'users.status', 'uses' => 'UserController@status']);

