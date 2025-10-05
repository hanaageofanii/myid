<?php

use App\Http\Controllers\ProfileController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/gcvmasterdajam/print', function () {
    $ids = session('print_records', []);
    $records = \App\Models\GcvMasterDajam::whereIn('id', $ids)->get();

    return view('print.gcvmasterdajam', compact('records'));
})->name('gcvmasterdajam.print');

Route::get('/datasiteplan/print', function () {
    $ids = session('print_records', []);
    $records = \App\Models\GcvDataSiteplan::whereIn('id', $ids)->get();

    return view('print.datasiteplan', compact('records'));
})->name('datasiteplan.print');

Route::get('/datatandateerima/print', function () {
    $ids = session('print_records', []);
    $records = \App\Models\gcv_datatandaterima::whereIn('id', $ids)->get();

    return view('print.datatandateerima', compact('records'));
})->name('datatandateerima.print');

Route::get('/datalegalitas/print', function () {
    $ids = session('print_records', []);
    $records = \App\Models\gcv_legalitas::whereIn('id', $ids)->get();

    return view('print.datalegalitas', compact('records'));
})->name('datalegalitas.print');

Route::get('/databooking/print', function () {
    $ids = session('print_records', []);
    $records = \App\Models\gcv_stok::whereIn('id', $ids)->get();

    return view('print.databooking', compact('records'));
})->name('databooking.print');

Route::get('/datakpr/print', function () {
    $ids = session('print_records', []);
    $records = \App\Models\gcv_kpr::whereIn('id', $ids)->get();

    return view('print.datakpr', compact('records'));
})->name('datakpr.print');

Route::get('/datapencairanakad/print', function () {
    $ids = session('print_records', []);
    $records = \App\Models\gcv_pencairan_akad::whereIn('id', $ids)->get();

    return view('print.datapencairanakad', compact('records'));
})->name('datapencairanakad.print');

Route::get('/datapencairandajam/print', function () {
    $ids = session('print_records', []);
    $records = \App\Models\gcv_pencairan_dajam::whereIn('id', $ids)->get();

    return view('print.datapencairandajam', compact('records'));
})->name('datapencairandajam.print');

Route::get('/datauangmuka/print', function () {
    $ids = session('print_records', []);
    $records = \App\Models\gcv_uang_muka::whereIn('id', $ids)->get();

    return view('print.datauangmuka', compact('records'));
})->name('datauangmuka.print');

Route::get('/datakaskecil/print', function () {
    $ids = session('print_records', []);
    $records = \App\Models\gcv_kaskecil::whereIn('id', $ids)->get();

    return view('print.datakaskecil', compact('records'));
})->name('datakaskecil.print');

Route::get('/pengajuanbn/print', function () {
    $ids = session('print_records', []);
    $records = \App\Models\gcv_pengajuan_bn::whereIn('id', $ids)->get();

    return view('print.pengajuanbn', compact('records'));
})->name('pengajuanbn.print');

Route::get('/validasipph/print', function () {
    $ids = session('print_records', []);
    $records = \App\Models\gcv_validasi_pph::whereIn('id', $ids)->get();

    return view('print.validasipph', compact('records'));
})->name('validasipph.print');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';