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
Route::get('/changestatus/mangkir', [KrsController::class, 'changestatus'])->name('mangkir');
Route::get('/changestatus/baseonpembayaran', [KrsController::class, 'baseonpembayaran'])->name('baseonpembayaran');
Route::get('/moveaktif', [KrsController::class, 'MoveAktif']);
