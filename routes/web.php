<?php

/*

Include mga bagong controller dito

*/

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FormsController;
use App\Http\Controllers\RecordsController;
use App\Http\Controllers\LineListController;
use App\Http\Controllers\AdminPanelController;
use App\Http\Controllers\RegisterCodeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\InterviewersController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

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

Auth::routes(['verify' => true]);
Route::get('/referral', [RegisterCodeController::class, 'index'])->name('rcode.index');
Route::get('/referral/check', [RegisterCodeController::class, 'refCodeCheck'])->name('rcode.check');

Route::get('/email/verify', function () {
    return view('auth.verify');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/home');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::group(['middleware' => ['auth','verified']], function() {
    // your routes
    //Route::get('/addrecord', [AddRecordsController::class, 'index'])->name('addrecord');
    //Route::post('/addrecord', [AddRecordsController::class, 'store']);

    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::resource('records', RecordsController::class);
    Route::resource('/forms', FormsController::class);

    Route::get('/linelist', [LineListController::class, 'index'])->name('linelist.index');
    Route::get('/linelist/oni/create', [LineListController::class, 'createoni'])->name('linelist.createoni');
    Route::post('/linelist/oni/create', [LineListController::class, 'oniStore'])->name('linelist.oni.store');

    Route::get('/linelist/lasalle/create', [LineListController::class, 'createlasalle'])->name('linelist.createlasalle');
    Route::post('/linelist/lasalle/create', [LineListController::class, 'lasalleStore'])->name('linelist.lasalle.store');

    Route::get('/linelist/oni/print/{id}', [LineListController::class, 'printoni'])->name('linelist.oni.print');
    Route::get('/linelist/lasalle/print/{id}', [LineListController::class, 'printlasalle'])->name('linelist.lasalle.print');

    Route::get('/report', [ReportController::class, 'index'])->name('report.index'); 

    //ajax
    Route::get('/ajaxGetUserRecord/{id}', [FormsController::class, 'ajaxGetUserRecord']);
    //Route::get('/ajaxGetLineList', [LineListController::class, 'ajaxGetLineList']);

    Route::post('/export', [FormsController::class, 'export'])->name('forms.export'); //print to excel, for admin only (temporary)
});

Route::group(['middleware' => ['auth','verified','App\Http\Middleware\SuperAdminMiddleware']], function()
{
    

    Route::get('/admin', [AdminPanelController::class, 'index'])->name('adminpanel.index');
    Route::get('/admin/brgy', [AdminPanelController::class, 'brgyIndex'])->name('adminpanel.brgy.index');
    Route::post('/admin/brgy/create/data', [AdminPanelController::class, 'brgyStore'])->name('adminpanel.brgy.store');
    Route::post('/admin/brgy/create/code', [AdminPanelController::class, 'brgyCodeStore'])->name('adminpanel.brgyCode.store');
    
    Route::get('/admin/accounts', [AdminPanelController::class, 'accountIndex'])->name('adminpanel.account.index');
    Route::post('/admin/accounts/create', [AdminPanelController::class, 'adminCodeStore'])->name('adminpanel.account.create');

    Route::resource('/interviewers', InterviewersController::class);
});



//Main landing page
Route::get('/', function () {
    if(auth()->check()) {
        return redirect()->route('home');
    }
    else {
        return view('auth.login');
    }
    
});