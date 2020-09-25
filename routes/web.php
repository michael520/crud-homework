<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountInfoController;

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

Route::get('/accountinfo', function () {
    return redirect()->route('/');
});

Route::get('/', 'App\Http\Controllers\AccountInfoController@index');

Route::resource('accountinfo', AccountInfoController::class);

Route::get('excel/exportxlsx','App\Http\Controllers\ExcelController@exportxlsx');
Route::get('excel/exportcsv','App\Http\Controllers\ExcelController@exportcsv');
Route::post('excel/import','App\Http\Controllers\ExcelController@import');
