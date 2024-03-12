<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SurveyFormController;
use App\Http\Controllers\ServiceUnitController;

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
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/form/csf', [SurveyFormController::class, 'index'])->name('csf_form');
Route::post('/csf_submission', [SurveyFormController::class, 'store']);


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');


    Route::get('/accounts', function () {
        return Inertia::render('Account/Index');
    })->name('accounts');

    Route::get('/service_units', [ServiceUnitController::class, 'index'])->name('services_units');
    Route::get('/csi', [ReportController::class , 'index']);
    Route::get('/csi/generate', [ReportController::class, 'generateCSI']);


});


