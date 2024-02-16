<?php

use App\Http\Controllers\PpwpController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [PpwpController::class, 'nasional']);

Route::get('ppwp/hitung_suara', [PpwpController::class, 'nasional']);
Route::get('ppwp/hitung_suara/{provinsi}', [PpwpController::class, 'provinsi']);
Route::get('ppwp/hitung_suara/{provinsi}/{kabupaten}', [PpwpController::class, 'kabupaten']);
Route::get('ppwp/hitung_suara/{provinsi}/{kabupaten}/{kecamatan}', [PpwpController::class, 'kecamatan']);
Route::get('ppwp/hitung_suara/{provinsi}/{kabupaten}/{kecamatan}/{kelurahan}', [PpwpController::class, 'kelurahan']);
Route::get('ppwp/hitung_suara/{provinsi}/{kabupaten}/{kecamatan}/{kelurahan}/{tps}', [PpwpController::class, 'tps']);

Route::get('credits', [PpwpController::class, 'credits']);
