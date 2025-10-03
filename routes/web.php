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




Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';