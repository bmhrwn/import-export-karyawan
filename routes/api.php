<?php

use App\Http\Controllers\KaryawanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// API Karyawan
Route::controller(KaryawanController::class)->prefix('karyawan')->group(function () {
    route::get('', 'index');
    route::post('', 'store');
    route::put('/{id}', 'update');
    route::delete('/{id}', 'destroy');
    route::post('/import', 'import');
    route::get('/export-csv', 'exportCsv');
    route::get('/export-pdf', 'exportPdf');
});
