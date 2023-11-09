<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KrsController;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [KrsController::class, 'index']);
Route::get('/data', [KrsController::class, 'getdata']);
Route::get('/table', [KrsController::class, 'createTable']);
Route::get('/changestatus/mangkir', [KrsController::class, 'changestatusmangkir'])->name('updatemangkir');
Route::get('/restore/mangkir', [KrsController::class, 'restoreStatusMangkir'])->name('restoremangkir');
Route::get('/changestatus/baseonpembayaran', [KrsController::class, 'baseonpembayaran'])->name('baseonpembayaran');
Route::get('/restore/baseonpembayaran', [KrsController::class, 'restorebaseonpembayaran'])->name('restorebaseonpembayaran');
Route::get('/changestatus/mhscuti', [KrsController::class, 'changestatuscuti'])->name('changestatuscuti');
Route::get('/restore/mhscuti', [KrsController::class, 'restoreDataCuti'])->name('restoredatacuti');
Route::get('/moveaktif', [KrsController::class, 'MoveAktif']);
