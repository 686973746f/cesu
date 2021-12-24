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
use App\Http\Controllers\OptionsController;
use App\Http\Controllers\RecordsController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\LineListController;
use App\Http\Controllers\ReportV2Controller;
use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\AdminPanelController;
use App\Http\Controllers\BulkUpdateController;
use App\Http\Controllers\JsonReportController;
use App\Http\Controllers\SelfReportController;
use App\Http\Controllers\PaSwabLinksController;
use App\Http\Controllers\InterviewersController;
use App\Http\Controllers\RegisterCodeController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\ContactTracingController;
use App\Http\Controllers\MonitoringSheetController;
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
    Route::get('/selfreport/{locale}/completed', [SelfReportController::class, 'storeComplete'])->name('selfreport.storeComplete');

    Route::get('/msheet/guest/{id}', [MonitoringSheetController::class, 'view'])->name('msheet.guest.view');
    Route::get('/msheet/guest/{id}/{date}/{mer}', [MonitoringSheetController::class, 'viewdate'])->name('msheet.guest.viewdate');
    Route::post('/msheet/guest/{id}/{date}/{mer}', [MonitoringSheetController::class, 'updatemonitoring'])->name('msheet.guest.updatemonitoring');
    Route::get('/msheet/guest/{id}/print', [MonitoringSheetController::class, 'print'])->name('msheet.guest.print');
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

    Route::get('/ct/report', [ReportV2Controller::class, 'viewCtReport'])->name('report.ct.index');

    Route::get('/check_pending', [HomeController::class, 'pendingSchedChecker'])->name('pendingshedchecker.index');
});

Route::group(['middleware' => ['auth','verified', 'isAccountEnabled']], function() {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/account/changepw', [ChangePasswordController::class, 'index'])->name('changepw.index');
    Route::post('/account/changepw', [ChangePasswordController::class, 'initChangePw'])->name('changepw.init');
});

Route::group(['middleware' => ['auth','verified', 'isAccountEnabled', 'isLevel1']], function() {
    // your routes
    Route::get('/records/duplicatechecker', [RecordsController::class, 'duplicateCheckerDashboard'])->name('records.duplicatechecker');
    Route::post('/records/check', [RecordsController::class, 'check'])->name('records.check');
    Route::resource('records', RecordsController::class);

    Route::get('/forms/ajaxList', [FormsController::class, 'ajaxList'])->name('forms.ajaxList');
    Route::get('/forms/ajaxcclist/', [FormsController::class, 'ajaxcclist'])->name('forms.ajaxcclist');
    Route::get('/forms/printCIFList/', [FormsController::class, 'printCIFList'])->name('forms.ciflist.print');
    Route::get('/forms/printAntigenLinelist/', [FormsController::class, 'printAntigenLinelist'])->name('forms.antigenlinelist.print');

    Route::get('/forms/selfreport/', [SelfReportController::class, 'view'])->name('selfreport.view');
    Route::get('/forms/selfreport/assess/{id}', [SelfReportController::class, 'edit'])->name('selfreport.edit');
    Route::post('/forms/selfreport/assess/{id}', [SelfReportController::class, 'finishAssessment'])->name('selfreport.finishAssessment');
    Route::post('/forms/reswab/{id}', [FormsController::class, 'reswab'])->name('forms.reswab');
    Route::resource('/forms', FormsController::class);
    Route::get('/forms/{id}/existing', [FormsController::class, 'viewExistingForm'])->name('forms.existing');
    Route::get('/forms/{id}/new', [FormsController::class, 'new'])->name('forms.new');
    Route::post('/forms/{id}/create', [FormsController::class, 'store']);
    Route::post('/forms/{id}/edit', [FormsController::class, 'upload'])->name('forms.upload');
    Route::get('/forms/download/{id}', [FormsController::class, 'downloadDocs']);
    Route::post('/forms/singleExport/{id}', [FormsController::class, 'soloExport'])->name('forms.soloprint.cif');
    Route::get('/forms/printAntigen/{id}/{testType}', [FormsController::class, 'printAntigen'])->name('forms.soloprint.antigen');

    Route::get('/linelist', [LineListController::class, 'index'])->name('linelist.index');
    Route::post('/linelist', [LineListController::class, 'createLineList'])->name('linelist.create');
    Route::post('/linelist/oni/create', [LineListController::class, 'oniStore'])->name('linelist.oni.store');
    Route::post('/linelist/lasalle/create', [LineListController::class, 'lasalleStore'])->name('linelist.lasalle.store');

    Route::get('/linelist/ajaxList', [LineListController::class, 'ajaxLineList'])->name('linelist.ajax');
    Route::get('/linelist/{link}/print/{id}', [LineListController::class, 'print'])->name('linelist.print');

    Route::get('/report', [ReportController::class, 'index'])->name('report.index');
    Route::get('/report/daily', [ReportController::class, 'viewDaily'])->name('report.daily');
    Route::get('/report/situational')->name('report.situational.index');
    Route::get('/report/situationalv2', [ReportController::class, 'viewSituationalv2'])->name('report.situationalv2.index');
    Route::get('/report/situational/excel', [ReportController::class, 'printSituationalv2'])->name('report.situationalv2.print');
    Route::get('/report/clustering/{city}/{brgy}', [ReportController::class, 'viewClustering']);
    Route::post('/report/dohExportAll/', [ReportController::class, 'dohExportAll'])->name('report.DOHExportAll');
    Route::get('/report/dilgExportAll/', [ReportController::class, 'dilgExportAll'])->name('report.dilgExportAll');
    Route::post('/report/export', [ReportController::class, 'reportExport'])->name('report.export');
    Route::get('/report/v2/dashboard', [ReportV2Controller::class, 'viewDashboard'])->name('reportv2.dashboard');

    //ajax
    Route::get('/ajaxGetUserRecord/{id}', [FormsController::class, 'ajaxGetUserRecord']);
    //Route::get('/ajaxGetLineList', [LineListController::class, 'ajaxGetLineList']);

    Route::post('/options', [OptionsController::class, 'submit'])->name('options.submit');
    Route::get('/options', [OptionsController::class, 'index'])->name('options.index');

    Route::post('/forms', [FormsController::class, 'options'])->name('forms.options'); //print to excel, for admin only (temporary)

    Route::post('/msheet/{forms_id}/create', [MonitoringSheetController::class, 'create'])->name('msheet.create');
    Route::get('/msheet/{id}/view', [MonitoringSheetController::class, 'view'])->name('msheet.view');
    Route::get('/msheet/{id}/print', [MonitoringSheetController::class, 'print'])->name('msheet.print');
    Route::get('/msheet/{id}/{date}/{mer}', [MonitoringSheetController::class, 'viewdate'])->name('msheet.viewdate');
    Route::post('/msheet/{id}/{date}/{mer}', [MonitoringSheetController::class, 'updatemonitoring'])->name('msheet.updatemonitoring');

    Route::get('/ct/index', [ContactTracingController::class, 'dashboard_index'])->name('ct.dashboard.index');
});

Route::group(['middleware' => ['auth','verified','isAccountEnabled', 'isLevel2']], function() {

});

Route::group(['middleware' => ['auth','verified','isAccountEnabled', 'isLevel3']], function() {
    //Facility Account Middleware
    Route::get('facility/home', [FacilityController::class, 'index'])->name('facility.home');
    Route::get('facility/{id}/viewPatient', [FacilityController::class, 'viewPatient'])->name('facility.viewdischarge');
    Route::post('facility/{id}/update', [FacilityController::class, 'update'])->name('facility.update');
    Route::post('facility/{id}/viewPatient', [FacilityController::class, 'initDischarge'])->name('facility.initdischarge');
});

Route::group(['middleware' => ['auth','verified','isAccountEnabled', 'isAdmin']], function()
{
    //Admin Page
    Route::get('/admin', [AdminPanelController::class, 'index'])->name('adminpanel.index');

    //Barangay
    Route::get('/admin/brgy', [AdminPanelController::class, 'brgyIndex'])->name('adminpanel.brgy.index');
    Route::get('/admin/brgy/view/{id}', [AdminPanelController::class, 'brgyView'])->name('adminpanel.brgy.view');
    Route::post('/admin/brgy/view/{id}', [AdminPanelController::class, 'brgyUpdate'])->name('adminpanel.brgy.update');
    Route::get('/admin/brgy/view/{brgy_id}/user/{user_id}', [AdminPanelController::class, 'brgyViewUser'])->name('adminpanel.brgy.view.user');
    Route::post('/admin/brgy/view/{brgy_id}/user/{user_id}', [AdminPanelController::class, 'brgyUpdateUser'])->name('adminpanel.brgy.update.user');

    //Referral Code
    Route::get('/admin/referral_code/', [AdminPanelController::class, 'referralCodeView'])->name('adminpanel.code.view');
    Route::post('/admin/brgy/create/data', [AdminPanelController::class, 'brgyStore'])->name('adminpanel.brgy.store');
    Route::post('/admin/brgy/create/code/{brgy_id}/', [AdminPanelController::class, 'brgyCodeStore'])->name('adminpanel.brgyCode.store');

    //Admin Accounts
    Route::get('/admin/accounts', [AdminPanelController::class, 'accountIndex'])->name('adminpanel.account.index');
    Route::get('/admin/accounts/view/{id}', [AdminPanelController::class, 'accountView'])->name('adminpanel.account.view');
    Route::post('/admin/accounts/view/{id}', [AdminPanelController::class, 'accountUpdate'])->name('adminpanel.account.update');
    Route::post('/admin/accounts/create', [AdminPanelController::class, 'adminCodeStore'])->name('adminpanel.account.create');
    Route::post('/admin/accounts/{id}/options', [AdminPanelController::class, 'accountOptions'])->name('adminpanel.account.options');

    //Interviewers
    Route::post('/admin/interviewers/options/{id}', [InterviewersController::class, 'options'])->name('adminpanel.interviewers.options');
    Route::resource('/admin/interviewers', InterviewersController::class);

    //Companies
    Route::resource('/companies', CompaniesController::class);
    Route::post('/companies/code/create', [CompaniesController::class, 'makeCode'])->name('companies.makecode');

    //Paswablinks
    Route::get('/admin/paswablinks', [PaSwabLinksController::class, 'index'])->name('paswablinks.index');
    Route::post('/admin/paswablinks', [PaSwabLinksController::class, 'store'])->name('paswablinks.store');
    Route::post('/admin/paswablinks/{id}/options', [PaSwabLinksController::class, 'linkInit']);
});

//JSON Reports
Route::get('/json/brgy', [JsonReportController::class, 'brgyCases']);
Route::get('/json/totalCases', [JsonReportController::class, 'totalCases']);
Route::get('/json/genderBreakdown', [JsonReportController::class, 'genderBreakdown']);
Route::get('json/conditionBreakdown', [JsonReportController::class, 'conditionBreakdown']);
Route::get('json/currentYearCasesDist', [JsonReportController::class, 'currentYearCasesDist']);
Route::get('json/facilityCount', [JsonReportController::class, 'facilityCount']);
Route::get('json/ageDistribution', [JsonReportController::class, 'ageDistribution']);
Route::get('json/workDistribution', [JsonReportController::class, 'workDistribution']);
Route::get('json/activeVaccineList', [JsonReportController::class, 'activeVaccineList']);

//Main landing page
Route::get('/', function () {
    if(auth()->check()) {
        return redirect()->route('home');
    }
    else {
        return view('auth.login');
    }
    
})->name('main');