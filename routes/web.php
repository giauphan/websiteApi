<?php

use App\Http\Controllers\ConversionController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\PdfToexcelController;
use App\Http\Controllers\pdftxt;
use App\Http\Controllers\UserAPi;
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
Route::get('/login', function () {
    return view('login');
});


Route::get('/test/pdf-to-json', [pdftxt::class, 'index']);
// Route::get('/pdf-to-txt', [ConversionController::class, 'index']);

Route::post('/convert', [ConversionController::class, 'pdfToTxt'])->name('pdfToTxt');
Route::get('/pdf-to-json', 'App\Http\Controllers\FileController@index');
Route::post('/pdf-to-json', 'App\Http\Controllers\FileController@pdfToJson');

//convert pdf to txt
Route::post('/pdf-to-txt', 'App\Http\Controllers\FileController@pdfToTxt');
Route::get('/txt-to-json', 'App\Http\Controllers\FileController@txtojson');

Route::get('pdf-to-excel', [PdfToexcelController::class, 'index']);
Route::post('pdf-to-excel', [PdfToexcelController::class, 'convert']);

//api 
Route::get('/api/process-file', [FileController::class, 'index']);
Route::post('/api/process-file', [FileController::class, 'process']);

// user to web api 
Route::get('/api/user', [UserAPi::class, 'index']);
Route::post('/api/user', [UserAPi::class, 'sumbitapi']);
