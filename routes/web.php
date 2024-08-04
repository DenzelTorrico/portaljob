<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PostulantesController;
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

Auth::routes();

Route::get('/', function (Request $request) {
    return redirect('/login');
});

Route::middleware('auth')->group(function () {
    Route::middleware('admin:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('jobs', [PostulantesController::class, 'listJobs'])->name('jobs.index');
        Route::get('jobsadmin', [AdminController::class, 'listview'])->name('jobs.view');
        Route::post('jobs', [AdminController::class, 'createJob'])->name('jobs.store');
        Route::get('jobs/{id}', [AdminController::class, 'showJob'])->name('jobs.show');
        Route::delete('jobs/{id}', [AdminController::class, 'deleteJob'])->name('jobs.delete');
    });

    Route::middleware('postulante:postulante')->prefix('postulante')->name('postulante.')->group(function () {
        Route::get('home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
        Route::get('jobs', [PostulantesController::class, 'listJobs'])->name('jobs.index');
        Route::get('applications', [PostulantesController::class, 'listApplications'])->name('applications.index');
        Route::get('application', [PostulantesController::class, 'Applicationsview'])->name('applications.view');
        Route::post('jobs/{id}/apply', [PostulantesController::class, 'applyJob'])->name('jobs.apply');
    });
});
// Manejar rutas no encontradas
Route::fallback(function () {
    return redirect()->back()->withErrors(['message' => 'La página que buscas no existe.']);
});