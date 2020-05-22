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
    return view('content.content');
});



Route::prefix('categories')->group(function(){
    Route::get('/','CategoriesController@index')->name('category');
    Route::post('/','CategoriesController@store');
    Route::get('/edit/{categories}','CategoriesController@edit');
    Route::post('/edit','CategoriesController@update');
    Route::get('/delete/{categories}','CategoriesController@destroy');
});

Route::prefix('books')->group(function(){
    Route::get('/','BooksController@index')->name('book');
    Route::post('/','BooksController@store');
    Route::get('/edit/{books}','BooksController@edit');
    Route::post('/edit','BooksController@update');
    Route::get('/delete/{books}','BooksController@destroy');
});

Route::prefix('transaction')->group(function(){
    Route::get('/borrow','TransactionsController@borrow')->name('borrow');
    Route::post('/borrow','TransactionsController@store');
    Route::get('/return','TransactionsController@return')->name('return');
    Route::get('/return/{transactions}','TransactionsController@update');
});

Route::prefix('ajax')->group(function(){
    Route::get('/categories','CategoriesController@getCategories')->name('ajax.categories');
    Route::get('/books','BooksController@getBooks')->name('ajax.books');
    Route::get('/borrows','TransactionsController@getBorrows')->name('ajax.borrows');
    Route::get('/returns','TransactionsController@getReturns')->name('ajax.returns');
    Route::get('/chartData','HomeController@chartData');
});