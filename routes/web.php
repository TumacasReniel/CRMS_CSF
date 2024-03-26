<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SubUnitController;
use App\Http\Controllers\SurveyFormController;
use App\Http\Controllers\ServiceUnitController;
use App\Http\Controllers\SubUnitPstoController;

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

Route::get('/services/csf', [SurveyFormController::class, 'index']);
Route::get('/form/csf/msg', [SurveyFormController::class, 'msg_index'])->name('msg_index');
Route::get('captcha/{config?}', '\Mews\Captcha\CaptchaController@getCaptcha')->middleware('web');
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
    
    Route::get('/service_unit/unit', [ServiceUnitController::class , 'unit_index'])->name('units');
    Route::get('/service_unit/psto', [ServiceUnitController::class , 'psto_index'])->name('psto');
    Route::get('/service_unit/unit-psto', [ServiceUnitController::class , 'unit_psto_index'])->name('unit_psto');
    Route::get('/service_unit/sub-unit-psto', [ServiceUnitController::class , 'sub_unit_psto_index'])->name('sub_unit_psto');

    Route::get('/csi', [ReportController::class , 'index']);
    Route::get('/csi/view', [ReportController::class , 'view']);
    Route::get('/csi/all-units', [ReportController::class , 'all_units']);

    Route::get('/csi/generate', [ReportController::class, 'generateReports']);



    //Route::post('/sub-unit/psto/{sub_unit_id}', [SubUnitPstoController::class, 'index']);

});


