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
    return view('welcome')->with('records', new \Illuminate\Database\Eloquent\Collection());
});

//
// Categories CRUD
//
Route::get('/categories', ['as' => 'categories.index', 'uses' => 'CategoriesController@index']);
Route::get('/categories/create', ['as' => 'categories.create', 'uses' => 'CategoriesController@create']);
Route::post('/categories/create', ['as' => 'categories.store', 'uses' => 'CategoriesController@store']);
Route::get('/categories/{category}/edit', ['as' => 'categories.edit', 'uses' => 'CategoriesController@edit']);
Route::post('/categories/{category}/edit', ['as' => 'categories.update', 'uses' => 'CategoriesController@update']);
Route::delete('/categories/{category}/edit', ['as' => 'categories.destroy', 'uses' => 'CategoriesController@destroy']);
Route::get('/categories/engine', ['as' => 'categories.engine', 'uses' => 'CategoriesEngineController@engine']);
Route::get('/categories/engine/teach', ['as' => 'categories.engine.teach', 'uses' => 'CategoriesEngineController@teach']);
Route::get('/categories/engine/wipe', ['as' => 'categories.engine.wipe', 'uses' => 'CategoriesEngineController@wipe']);
Route::post('/categories/engine/suggest', ['as' => 'categories.engine.suggest', 'uses' => 'CategoriesEngineController@suggest']);

//
// Accounts CRUD
//
Route::get('/accounts', ['as' => 'accounts.index', 'uses' => 'AccountsController@index']);
Route::get('/accounts/create', ['as' => 'accounts.create', 'uses' => 'AccountsController@create']);
Route::post('/accounts/create', ['as' => 'accounts.store', 'uses' => 'AccountsController@store']);
Route::get('/accounts/{account}/edit', ['as' => 'accounts.edit', 'uses' => 'AccountsController@edit']);
Route::post('/accounts/{account}/edit', ['as' => 'accounts.update', 'uses' => 'AccountsController@update']);

//
// Tranactions CRUD
//
Route::get('/accounts/{account}/transactions', ['as' => 'accounts.transactions', 'uses' => 'AccountTransactionsController@index']);
Route::get('/accounts/{account}/transactions/create', ['as' => 'accounts.transactions.create', 'uses' => 'AccountTransactionsController@create']);
Route::post('/accounts/{account}/transactions/create', ['as' => 'accounts.transactions.store', 'uses' => 'AccountTransactionsController@store']);

Route::post('/accounts/{account}/transactions/group-edit', ['as' => 'accounts.transactions.edit-group', 'uses' => 'AccountTransactionsController@editGroup']);

Route::get('/accounts/{account}/transactions/{transaction}/edit', ['as' => 'accounts.transactions.edit', 'uses' => 'AccountTransactionsController@edit']);
Route::post('/accounts/{account}/transactions/{transaction}/edit', ['as' => 'accounts.transactions.update', 'uses' => 'AccountTransactionsController@update']);
//|
// Transactions Import
//
Route::get('/accounts/{account}/transactions/import', ['as' => 'accounts.transactions.import', 'uses' => 'TransactionsImportController@import']);
Route::post('/accounts/{account}/transactions/import', ['as' => 'accounts.transactions.import.begin', 'uses' => 'TransactionsImportController@importBegin']);
Route::get('/accounts/{account}/transactions/import/report', ['as' => 'accounts.transactions.import.finish', 'uses' => 'TransactionsImportController@importFinish']);

//
// Reports
//
Route::get('/reports', ['as' => 'reports', 'uses' => 'ReportsController@index']);
Route::get('/reports/{identifier}/create', ['as' => 'reports.create', 'uses' => 'ReportsController@create']);
Route::post('/reports/{identifier}/preview', ['as' => 'reports.preview', 'uses' => 'ReportsController@preview']);
Route::post('/reports/{identifier}/store', ['as' => 'reports.store', 'uses' => 'ReportsController@store']);
Route::post('/reports', ['as' => 'reports.update', 'uses' => 'ReportsController@update']);

//
// Budget
//
Route::get('/budgets', ['as' => 'budgets', 'uses' => 'BudgetsController@index']);