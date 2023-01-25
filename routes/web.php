<?php

/*

Include mga bagong controller dito

*/

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\FormsController;
use App\Http\Controllers\DengueController;
use App\Http\Controllers\PaSwabController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AntigenController;
use App\Http\Controllers\OptionsController;
use App\Http\Controllers\OutsideController;
use App\Http\Controllers\RecordsController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\LineListController;
use App\Http\Controllers\ReportV2Controller;
use App\Http\Controllers\ABTCAdminController;
use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\MonkeyPoxController;
use App\Http\Controllers\ABTCReportController;
use App\Http\Controllers\AdminPanelController;
use App\Http\Controllers\BulkUpdateController;
use App\Http\Controllers\JsonReportController;
use App\Http\Controllers\SelfReportController;
use App\Http\Controllers\ABTCPatientController;
use App\Http\Controllers\PaSwabLinksController;
use App\Http\Controllers\InterviewersController;
use App\Http\Controllers\RegisterCodeController;
use App\Http\Controllers\SiteSettingsController;
use App\Http\Controllers\MorbidityWeekController;
use App\Http\Controllers\OnlineMedCertController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\ContactTracingController;
use App\Http\Controllers\ABTCVaccinationController;
use App\Http\Controllers\MonitoringSheetController;
use App\Http\Controllers\ABTCUserSettingsController;
use App\Http\Controllers\AcceptanceLetterController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\ABTCWalkInRegistrationController;
use App\Http\Controllers\SecondaryTertiaryRecordsController;

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

Route::get('/verify/{qr}', [OutsideController::class, 'qrcodeverify'])->name('qrcodeverify.index');

//Route::get('/test', [TestController::class, 'index']);

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

    //Route::get('/medcert', [OnlineMedCertController::class, 'index'])->name('onlinemedcert_index');
    //Route::post('/medcert', [OnlineMedCertController::class, 'check'])->name('onlinemedcert_check');
});

Route::group(['middleware' => ['auth','verified', 'isAccountEnabled']], function() {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/account/changepw', [ChangePasswordController::class, 'index'])->name('changepw.index');
    Route::post('/account/changepw', [ChangePasswordController::class, 'initChangePw'])->name('changepw.init');
});

Route::group(['middleware' => ['auth','verified', 'isAccountEnabled', 'isCesuOrBrgyAccount', 'canAccessCovid']], function() {
    //Pa-swab
    Route::post('/forms/paswab/view', [PaSwabController::class, 'options'])->name('paswab.options');
    Route::get('/forms/paswab/view', [PaSwabController::class, 'view'])->name('paswab.view');
    Route::get('/forms/paswab/view/{id}', [PaSwabController::class, 'viewspecific'])->name('paswab.viewspecific');
    Route::post('/forms/paswab/{id}/approve', [PaSwabController::class, 'approve']);
    Route::post('/forms/paswab/{id}/reject', [PaSwabController::class, 'reject']);

    Route::get('/ct/report', [ReportV2Controller::class, 'viewCtReport'])->name('report.ct.index');

    Route::get('/check_pending', [HomeController::class, 'pendingSchedChecker'])->name('pendingshedchecker.index');
});

Route::group(['middleware' => ['auth','verified', 'isAccountEnabled', 'isLevel1', 'canAccessCovid']], function() {
    // your routes
    Route::get('/records/duplicatechecker', [RecordsController::class, 'duplicateCheckerDashboard'])->name('records.duplicatechecker');
    Route::post('/records/check', [RecordsController::class, 'check'])->name('records.check');
    Route::resource('records', RecordsController::class);

    Route::get('/forms/ajaxList', [FormsController::class, 'ajaxList'])->name('forms.ajaxList');
    Route::get('/forms/ajaxRecordList/{current_record_id}', [FormsController::class, 'recordajaxlist'])->name('forms.ajaxRecordList');
    Route::get('/forms/ajaxcclist/', [FormsController::class, 'ajaxcclist'])->name('forms.ajaxcclist');
    Route::get('/forms/printCIFList/', [FormsController::class, 'printCIFList'])->name('forms.ciflist.print');
    Route::get('/forms/printAntigenLinelist/', [FormsController::class, 'printAntigenLinelist'])->name('forms.antigenlinelist.print');

    Route::get('/forms/selfreport/', [SelfReportController::class, 'view'])->name('selfreport.view');
    Route::get('/forms/selfreport/assess/{id}', [SelfReportController::class, 'edit'])->name('selfreport.edit');
    Route::post('/forms/selfreport/assess/{id}', [SelfReportController::class, 'finishAssessment'])->name('selfreport.finishAssessment');
    Route::post('/forms/selfreport/convert_to_suspected/{id}', [SelfReportController::class, 'convertToSuspected'])->name('selfreport_convertToSuspected');
    Route::get('/forms/selfreport/viewdoc/{id}', [SelfReportController::class, 'viewDocument'])->name('selfreport.viewdocument');
    Route::match(array('GET','POST') ,'/forms/reswab/{id}', [FormsController::class, 'reswab'])->name('forms.reswab');
    Route::resource('/forms', FormsController::class);
    Route::get('/forms/{id}/existing', [FormsController::class, 'viewExistingForm'])->name('forms.existing');
    Route::get('/forms/{id}/new', [FormsController::class, 'new'])->name('forms.new');
    Route::post('/forms/{id}/create', [FormsController::class, 'store']);
    Route::post('/forms/{id}/edit', [FormsController::class, 'upload'])->name('forms.upload');
    Route::post('/forms/{id}/edit/qrecovered', [FormsController::class, 'qSetRecovered'])->name('forms.qSetRecovered');
    Route::post('/forms/{id}/edit/tempsched', [FormsController::class, 'setTempSched'])->name('forms.setTempSched');
    Route::post('/forms/{id}/edit/changedispo', [FormsController::class, 'cChangeDispo'])->name('forms.cChangeDispo');
    Route::post('/forms/{id}/edit/transfer', [FormsController::class, 'transfercif'])->name('forms.transfercif');

    Route::get('/forms/download/{id}', [FormsController::class, 'downloadDocs']);
    Route::post('/forms/singleExport/{id}', [FormsController::class, 'soloExport'])->name('forms.soloprint.cif');
    Route::get('/forms/printAntigen/{id}/{testType}', [FormsController::class, 'printAntigen'])->name('forms.soloprint.antigen');

    Route::post('/forms/medcert/{form_id}', [FormsController::class, 'generateMedCert'])->name('generate_medcert');

    Route::get('/forms/ct_exposure/{form_id}/create', [ContactTracingController::class, 'ctFormsExposureCreate'])->name('ct_exposure_create');
    Route::post('/forms/ct_exposure/{form_id}/create', [ContactTracingController::class, 'ctFormsExposureStore'])->name('ct_exposure_store');
    Route::get('/forms/ct_exposure/{form_id}/{ct_id}/edit', [ContactTracingController::class, 'ctFormsExposureEdit'])->name('ct_exposure_edit');
    Route::post('/forms/ct_exposure/{form_id}/{ct_id}/edit', [ContactTracingController::class, 'ctFormsExposureUpdate'])->name('ct_exposure_update');
    Route::post('/forms/ct_exposure/{ct_id}/delete', [ContactTracingController::class, 'ctFormsExposureDelete'])->name('ct_exposure_delete');

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
    Route::get('/report/clustering', [ReportV2Controller::class, 'clustering_index'])->name('clustering_index');
    Route::get('/report/clustering/{city}/{brgy}', [ReportController::class, 'viewClustering'])->name('clustering_view');
    Route::get('/report/clustering/{city}/{brgy}/view_list/q={subd}', [ReportV2Controller::class, 'clustering_viewlist'])->name('clustering_viewlist');
    Route::post('/report/dohExportAll/', [ReportController::class, 'dohExportAll'])->name('report.DOHExportAll');
    Route::get('/report/dilgExportAll/', [ReportController::class, 'dilgExportAll'])->name('report.dilgExportAll');
    Route::post('/report/export', [ReportController::class, 'reportExport'])->name('report.export');
    Route::get('/report/v2/dashboard', [ReportV2Controller::class, 'viewDashboard'])->name('reportv2.dashboard');

    Route::get('/report/cm', [ReportV2Controller::class, 'cmIndex'])->name('report_cm_index');

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
    Route::get('/ct/sp/index', [SecondaryTertiaryRecordsController::class, 'index'])->name('sc_index');
    Route::get('/ct/sp/create', [SecondaryTertiaryRecordsController::class, 'create'])->name('sc_create');
    Route::post('/ct/sp/create', [SecondaryTertiaryRecordsController::class, 'store'])->name('sc_store');
    Route::get('/ct/{id}/edit', [SecondaryTertiaryRecordsController::class, 'edit'])->name('sc_edit');
    Route::put('/ct/{id}/edit', [SecondaryTertiaryRecordsController::class, 'update'])->name('sc_update');

    Route::get('/report/encoding_calendar', [ReportV2Controller::class, 'encodingCalendar'])->name('encoding_calendar');

    Route::get('/report/ctreport2', [ContactTracingController::class, 'ctlgureport'])->name('ctlgu_report');

    Route::get('/casechecker', [ReportV2Controller::class, 'casechecker_index'])->name('casechecker_index');
    
    Route::get('/report/accomplishment', [ReportV2Controller::class, 'accomplishment_index'])->name('report.accomplishment');
    Route::get('/report/fhsis', [ReportV2Controller::class, 'm2fhsis'])->name('report.fhsis');

    //Monkeypox
    Route::get('/monkeypox', [MonkeyPoxController::class, 'home'])->name('mp.home');
    Route::get('/monkeypox/ajaxlist', [MonkeyPoxController::class, 'ajaxlist'])->name('mp.ajaxlist');
    Route::get('/monkeypox/cif/{record_id}/new', [MonkeyPoxController::class, 'create_cif'])->name('mp.newcif');
    Route::post('/monkeypox/cif/{record_id}/new', [MonkeyPoxController::class, 'store_cif'])->name('mp.storecif');
    Route::get('/monkeypox/cif/{mk}/edit', [MonkeyPoxController::class, 'edit_cif'])->name('mp.editcif');
    Route::post('/monkeypox/cif/{mk}/update', [MonkeyPoxController::class, 'update_cif'])->name('mp.updatecif');

    //Dengue
    Route::get('/dengue', [DengueController::class, 'home'])->name('dg.home');
    Route::get('/dengue/cif', [DengueController::class, 'cifhome'])->name('dg.cifhome');
    Route::get('/dengue/cif/{record_id}/new', [DengueController::class, 'create_cif'])->name('dg.newcif');
    Route::post('/dengue/cif/{record_id}/new', [DengueController::class, 'store_cif'])->name('dg.storecif');
    Route::get('/dengue/cif/{cif_id}/edit', [DengueController::class, 'edit_cif'])->name('dg.editcif');
    Route::post('/dengue/cif/{cif_id}/update', [DengueController::class, 'update_cif'])->name('dg.updatecif');

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

Route::group(['middleware' => ['auth','verified','isAccountEnabled', 'isAdmin', 'canAccessCovid']], function()
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

    //Encoder Stats
    Route::get('/admin/encoder_stats', [AdminPanelController::class, 'encoderStatsIndex'])->name('encoder_stats_index');

    Route::get('/admin/antigen', [AntigenController::class, 'index'])->name('antigen_index');
    Route::get('/admin/antigen/create', [AntigenController::class, 'create'])->name('antigen_create');
    Route::post('/admin/antigen/create', [AntigenController::class, 'store'])->name('antigen_store');
    Route::get('/admin/antigen/{id}/edit', [AntigenController::class, 'edit'])->name('antigen_edit');
    Route::post('/admin/antigen/{id}/edit', [AntigenController::class, 'update'])->name('antigen_update');

    //Acceptance Letter
    Route::get('/acceptance', [AcceptanceLetterController::class, 'index'])->name('acceptance.index');
    Route::post('/acceptance/store', [AcceptanceLetterController::class, 'store'])->name('acceptance.store');
    Route::get('/acceptance/print/{id}', [AcceptanceLetterController::class, 'printview'])->name('acceptance.print');

    //MW
    Route::get('/report/mw', [MorbidityWeekController::class, 'index'])->name('mw.index');
    Route::post('/report/mw/process', [MorbidityWeekController::class, 'process'])->name('mw.process');

    //Site Settings
    Route::get('/settings/site', [SiteSettingsController::class, 'index'])->name('ss.index');
    Route::post('/settings/site', [SiteSettingsController::class, 'update'])->name('ss.update');
});

//ANIMAL BITE ROUTES
Route::group(['middleware' => ['auth','verified', 'isAccountEnabled', 'isLevel1', 'canAccessAbtc']], function () {
    Route::get('/abtc', [ABTCPatientController::class, 'home'])->name('abtc_home');
    Route::get('/abtc/patient', [ABTCPatientController::class, 'index'])->name('abtc_patient_index');
    Route::get('/abtc/patient/create', [ABTCPatientController::class, 'create'])->name('abtc_patient_create');
    Route::post('/abtc/patient/create', [ABTCPatientController::class, 'store'])->name('abtc_patient_store');
    Route::get('/abtc/patient/{id}/edit', [ABTCPatientController::class, 'edit'])->name('abtc_patient_edit');
    Route::post('/abtc/patient/{id}/edit', [ABTCPatientController::class, 'update'])->name('abtc_patient_update');
    Route::get('/abtc/patient/ajaxList', [ABTCPatientController::class, 'ajaxList'])->name('abtc_patient_ajaxlist');
    
    Route::get('/abtc/patient/bakuna_records/{id}', [ABTCPatientController::class, 'patient_viewbakunarecords'])->name('abtc_patient_viewbakunarecords');
    Route::post('/abtc/patient/quickscan', [ABTCVaccinationController::class, 'qr_quicksearch'])->name('abtc_qr_quicksearch');

    Route::get('/abtc/vaccination_site', [ABTCAdminController::class, 'vaccinationsite_index'])->name('abtc_vaccinationsite_index');
    Route::post('/abtc/vaccination_site', [ABTCAdminController::class, 'vaccinationsite_store'])->name('abtc_vaccinationsite_store');

    Route::get('/abtc/vaccine_brand', [ABTCAdminController::class, 'vaccinebrand_index'])->name('abtc_vaccinebrand_index');
    Route::post('/abtc/vaccine_brand', [ABTCAdminController::class, 'vaccinebrand_store'])->name('abtc_vaccinebrand_store');

    Route::post('/abtc/encode_search', [ABTCVaccinationController::class, 'search_init'])->name('abtc_search_init');
    Route::get('/abtc/encode/existing/{id}', [ABTCVaccinationController::class, 'encode_existing'])->name('abtc_encode_existing');
    
    Route::get('/abtc/encode/new/{id}', [ABTCVaccinationController::class, 'create_new'])->name('abtc_encode_create_new');
    Route::post('/abtc/encode/new/{id}', [ABTCVaccinationController::class, 'create_store'])->name('abtc_encode_store');
    Route::get('/abtc/encode/print/{id}', [ABTCVaccinationController::class, 'print_view'])->name('abtc_print_view');

    Route::get('/abtc/encode/edit/{br_id}', [ABTCVaccinationController::class, 'encode_edit'])->name('abtc_encode_edit');
    Route::post('/abtc/encode/edit/{br_id}', [ABTCVaccinationController::class, 'encode_update'])->name('abtc_encode_update');

    Route::get('/abtc/encode/edit/{br_id}/override_schedule', [ABTCVaccinationController::class, 'override_schedule'])->name('abtc_override_schedule');
    Route::post('/abtc/encode/edit/{br_id}/override_schedule', [ABTCVaccinationController::class, 'override_schedule_process'])->name('abtc_override_schedule_process');

    Route::get('/abtc/encode/process_vaccination/{br_id}/{dose}', [ABTCVaccinationController::class, 'encode_process'])->name('abtc_encode_process');

    Route::get('/abtc/encode/rebakuna/{patient_id}', [ABTCVaccinationController::class, 'bakuna_again'])->name('abtc_bakuna_again');

    Route::get('/abtc/report/linelist', [ABTCReportController::class, 'linelist_index'])->name('abtc_report_linelist_index');
    Route::get('/abtc/report/linelist2', [ABTCReportController::class, 'linelist2'])->name('abtc_report_linelist2_index');
    Route::get('/abtc/report/cho', [ABTCReportController::class, 'choreport1'])->name('abtc_report_cho');
    Route::post('/abtc/report/export1', [ABTCReportController::class, 'export1'])->name('abtc_report_export1');

    Route::post('/abtc/settings/save', [ABTCUserSettingsController::class, 'save_settings'])->name('abtc_save_settings');

    Route::get('/abtc/sched', [ABTCVaccinationController::class, 'schedule_index'])->name('abtc_schedule_index');
    Route::get('/abtc/report/dashboard', [ABTCReportController::class, 'dashboard'])->name('abtc_dashboard');
});

Route::group(['middleware' => ['guest']], function() {
    Route::get('/abtc/walkin', [ABTCWalkInRegistrationController::class, 'walkin_part1'])->name('abtc_walkin_part1');
    Route::get('/abtc/walkin/register', [ABTCWalkInRegistrationController::class, 'walkin_part2'])->name('abtc_walkin_part2');
    Route::post('/abtc/walkin/register', [ABTCWalkInRegistrationController::class, 'walkin_part3'])->name('abtc_walkin_part3');
});

//JSON Reports
Route::get('/json/brgy', [JsonReportController::class, 'brgyCases']);
Route::get('/json/totalCases', [JsonReportController::class, 'totalCases']);
Route::get('/json/genderBreakdown', [JsonReportController::class, 'genderBreakdown']);
Route::get('json/conditionBreakdown', [JsonReportController::class, 'conditionBreakdown']);
Route::get('json/lastYearCasesDist', [JsonReportController::class, 'lastYearCasesDist']);
Route::get('json/currentYearCasesDist', [JsonReportController::class, 'currentYearCasesDist']);
Route::get('json/facilityCount', [JsonReportController::class, 'facilityCount']);
Route::get('json/ageDistribution', [JsonReportController::class, 'ageDistribution']);
Route::get('json/workDistribution', [JsonReportController::class, 'workDistribution']);
Route::get('json/activeVaccineList', [JsonReportController::class, 'activeVaccineList']);
Route::get('json/currentDate', [JsonReportController::class, 'currentDate']);
Route::get('json/mwly', [JsonReportController::class, 'mwly']);
Route::get('json/mwcy', [JsonReportController::class, 'mwcy']);
Route::get('json/mwcombine', [JsonReportController::class, 'mwcombine']);

//Main landing page
Route::get('/', function () {
    if(auth()->check()) {
        return redirect()->route('home');
    }
    else {
        return view('auth.login');
    }
    
})->name('main');