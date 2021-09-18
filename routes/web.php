<?php

/*

Include mga bagong controller dito

*/

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FormsController;
use App\Http\Controllers\PaSwabController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RecordsController;
use App\Http\Controllers\LineListController;
use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\AdminPanelController;
use App\Http\Controllers\BulkUpdateController;
use App\Http\Controllers\JsonReportController;
use App\Http\Controllers\SelfReportController;
use App\Http\Controllers\PaSwabLinksController;
use App\Http\Controllers\InterviewersController;
use App\Http\Controllers\RegisterCodeController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

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

Route::group(['middleware' => ['guest']], function () {
    Route::get('/paswab', [PaSwabController::class, 'selectLanguage'])->name('paswab.language');
    Route::get('/paswab/{locale}', [PaSwabController::class, 'index'])->name('paswab.index');
    Route::post('/paswab/{locale}', [PaSwabController::class, 'store'])->name('paswab.store');
    Route::get('/paswab/{locale}/completed', [PaSwabController::class, 'complete'])->name('paswab.complete');
    Route::post('/paswab/{locale}/check', [PaSwabController::class, 'check'])->name('paswab.check');
    
    Route::get('/selfreport/{locale}', [SelfReportController::class, 'index'])->name('selfreport.index');
    Route::get('/selfreport', [SelfReportController::class, 'selectLanguage'])->name('selfreport.language');
    Route::post('/selfreport', [SelfReportController::class, 'store'])->name('selfreport.store');
});

Route::group(['middleware' => ['auth','verified', 'isAccountEnabled', 'isCesuAccount']], function() {
    Route::get('/forms/bulkupdate', [BulkUpdateController::class, 'viewBulkUpdate'])->name('bulkupdate.index');
    Route::post('/forms/bulkupdate', [BulkUpdateController::class, 'store'])->name('bulkupdate.store');
    Route::get('/forms/bulkupdate/ajax', [BulkUpdateController::class, 'ajaxController'])->name('bulkupdate.ajax');

    Route::post('/forms/paswab/view', [PaSwabController::class, 'options'])->name('paswab.options');
    Route::get('/forms/paswab/view', [PaSwabController::class, 'view'])->name('paswab.view');
    Route::get('/forms/paswab/view/{id}', [PaSwabController::class, 'viewspecific'])->name('paswab.viewspecific');
    Route::post('/forms/paswab/{id}/approve', [PaSwabController::class, 'approve']);
    Route::post('/forms/paswab/{id}/reject', [PaSwabController::class, 'reject']);
});

Route::group(['middleware' => ['auth','verified', 'isAccountEnabled']], function() {
    // your routes
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::post('/records/check', [RecordsController::class, 'check'])->name('records.check');
    Route::resource('records', RecordsController::class);

    Route::get('/forms/ajaxList', [FormsController::class, 'ajaxList'])->name('forms.ajaxList');
    Route::get('/forms/printCIFList/', [FormsController::class, 'printCIFList'])->name('forms.ciflist.print');
    Route::get('/forms/printAntigenLinelist/', [FormsController::class, 'printAntigenLinelist'])->name('forms.antigenlinelist.print');

    Route::get('/forms/selfreport/', [SelfReportController::class, 'view'])->name('selfreport.view');
    
    Route::resource('/forms', FormsController::class);
    Route::get('/forms/{id}/new', [FormsController::class, 'new'])->name('forms.new');
    Route::post('/forms/{id}/create', [FormsController::class, 'store']);
    Route::post('/forms/{id}/edit', [FormsController::class, 'upload'])->name('forms.upload');
    Route::get('/forms/download/{id}', [FormsController::class, 'downloadDocs']);
    Route::post('/forms/singleExport/{id}', [FormsController::class, 'soloExport']);
    Route::get('/forms/printAntigen/{id}/{testType}', [FormsController::class, 'printAntigen']);

    Route::get('/linelist', [LineListController::class, 'index'])->name('linelist.index');
    Route::post('/linelist', [LineListController::class, 'createLineList'])->name('linelist.create');
    Route::post('/linelist/oni/create', [LineListController::class, 'oniStore'])->name('linelist.oni.store');
    Route::post('/linelist/lasalle/create', [LineListController::class, 'lasalleStore'])->name('linelist.lasalle.store');

    Route::get('/linelist/ajaxList', [LineListController::class, 'ajaxLineList'])->name('linelist.ajax');

    Route::get('/linelist/oni/print/{id}', [LineListController::class, 'printoni'])->name('linelist.oni.print');
    Route::get('/linelist/lasalle/print/{id}', [LineListController::class, 'printlasalle'])->name('linelist.lasalle.print');

    Route::get('/report', [ReportController::class, 'index'])->name('report.index');
    Route::get('/report/daily', [ReportController::class, 'viewDaily'])->name('report.daily');
    Route::get('/report/situational')->name('report.situational.index');
    Route::get('/report/situationalv2', [ReportController::class, 'viewSituationalv2'])->name('report.situationalv2.index');
    Route::get('/report/situational/excel', [ReportController::class, 'printSituationalv2'])->name('report.situationalv2.print');
    Route::get('/report/clustering/{city}/{brgy}', [ReportController::class, 'viewClustering']);
    Route::post('/report/export', [ReportController::class, 'reportExport'])->name('report.export');

    //ajax
    Route::get('/ajaxGetUserRecord/{id}', [FormsController::class, 'ajaxGetUserRecord']);
    //Route::get('/ajaxGetLineList', [LineListController::class, 'ajaxGetLineList']);

    Route::post('/forms', [FormsController::class, 'options'])->name('forms.options'); //print to excel, for admin only (temporary)
});

Route::group(['middleware' => ['auth','verified','isAccountEnabled', 'isAdmin']], function()
{
    Route::get('/admin', [AdminPanelController::class, 'index'])->name('adminpanel.index');
    Route::get('/admin/brgy', [AdminPanelController::class, 'brgyIndex'])->name('adminpanel.brgy.index');
    Route::post('/admin/brgy/create/data', [AdminPanelController::class, 'brgyStore'])->name('adminpanel.brgy.store');
    Route::post('/admin/brgy/create/code', [AdminPanelController::class, 'brgyCodeStore'])->name('adminpanel.brgyCode.store');

    Route::get('/admin/forms/import', [FormsController::class, 'importIndex'])->name('forms.import');
    Route::post('/admin/forms/import', [FormsController::class, 'importInit'])->name('forms.upload');
    
    Route::get('/admin/accounts', [AdminPanelController::class, 'accountIndex'])->name('adminpanel.account.index');
    Route::post('/admin/accounts/create', [AdminPanelController::class, 'adminCodeStore'])->name('adminpanel.account.create');

    Route::post('/admin/accounts/{id}/options', [AdminPanelController::class, 'accountOptions'])->name('adminpanel.account.options');

    Route::resource('/interviewers', InterviewersController::class);

    Route::post('/report', [ReportController::class, 'makeAllSuspected'])->name('report.makeAllSuspected');

    Route::resource('/companies', CompaniesController::class);
    Route::post('/companies/code/create', [CompaniesController::class, 'makeCode'])->name('companies.makecode');

    Route::get('/admin/paswablinks', [PaSwabLinksController::class, 'index'])->name('paswablinks.index');
    Route::post('/admin/paswablinks', [PaSwabLinksController::class, 'store'])->name('paswablinks.store');
    Route::post('/admin/paswablinks/{id}/options', [PaSwabLinksController::class, 'linkInit']);

    Route::get('/viewphp', [HomeController::class, 'viewphp']);
});

//Reports
Route::get('/json/brgy', [JsonReportController::class, 'brgyCases']);


//Main landing page
Route::get('/', function () {
    if(auth()->check()) {
        return redirect()->route('home');
    }
    else {
        return view('auth.login');
    }
    
})->name('main');