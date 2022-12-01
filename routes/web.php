<?php

use App\Http\Controllers\SalesController;
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

Route::get('/download-csv', [SalesController::class, 'download'])->name('download.csv');

Route::resource('/sales', SalesController::class);


Route::get('/batch', [SalesController::class, 'getBatch']);
