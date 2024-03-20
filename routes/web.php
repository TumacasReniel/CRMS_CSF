<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\PDFController;
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

    Route::get('/csi', [ReportController::class , 'index']);
    Route::get('/csi/all-units', [ReportController::class , 'all_units']);
    Route::get('/csi/generate/ByUnit/ByDate', [ReportController::class, 'generateCSIByUnitByDate']);
    Route::get('/csi/generate/ByUnit/Monthly', [ReportController::class, 'generateCSIByUnitMonthly']);
    Route::get('/csi/generate/ByUnit/FirstQuarter', [ReportController::class, 'generateCSIByUnitFirstQuarter']);
    Route::get('/csi/generate/ByUnit/SecondQuarter', [ReportController::class, 'generateCSIByUnitSecondQuarter']);
    Route::get('/csi/generate/ByUnit/ThirdQuarter', [ReportController::class, 'generateCSIByUnitThirdQuarter']);
    Route::get('/csi/generate/ByUnit/FourthQuarter', [ReportController::class, 'generateCSIByUnitFourthQuarter']);
    Route::get('/csi/generate/ByUnit/Yearly', [ReportController::class, 'generateCSIByUnitYearly']);

});


