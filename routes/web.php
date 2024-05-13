<?php

/*

Include mga bagong controller dito

*/

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItrController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\FhsisController;
use App\Http\Controllers\FormsController;
use App\Http\Controllers\PIDSRController;
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
use App\Http\Controllers\FwriController;
use App\Http\Controllers\PharmacyController;
use App\Http\Controllers\PregnancyTrackingController;
use App\Http\Controllers\QesController;
use App\Http\Controllers\SecondaryTertiaryRecordsController;
use App\Http\Controllers\SubdivisionController;
use App\Http\Controllers\SyndromicAdminController;
use App\Http\Controllers\SyndromicController;
use App\Http\Controllers\VaxcertController;

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

Route::get('/test', [TestController::class, 'index']);

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

//MAIN LOGIN ROUTES
Route::group(['middleware' => ['auth','verified', 'isAccountEnabled']], function() {
    Route::get('/main_menu', [HomeController::class, 'index'])->name('home');
    
    Route::get('/account/changepw', [ChangePasswordController::class, 'index'])->name('changepw.index');
    Route::post('/account/changepw', [ChangePasswordController::class, 'initChangePw'])->name('changepw.init');

    Route::get('getSubdivisions/{brgy_id}', [SubdivisionController::class, 'getSubdivisions'])->name('getSubdivisions');

    //Encoder Stats
    Route::get('/admin/encoder_stats', [AdminPanelController::class, 'encoderStatsIndex'])->name('encoder_stats_index');
});

//PASWAB INTERNAL
Route::group(['middleware' => ['auth','verified', 'isAccountEnabled', 'isCesuOrBrgyAccount', 'canAccessCovid']], function() {
    Route::post('/covid/forms/paswab/view', [PaSwabController::class, 'options'])->name('paswab.options');
    Route::get('/covid/forms/paswab/view', [PaSwabController::class, 'view'])->name('paswab.view');
    Route::get('/covid/forms/paswab/view/{id}', [PaSwabController::class, 'viewspecific'])->name('paswab.viewspecific');
    Route::post('/covid/forms/paswab/{id}/approve', [PaSwabController::class, 'approve']);
    Route::post('/covid/forms/paswab/{id}/reject', [PaSwabController::class, 'reject']);

    Route::get('/covid/ct/report', [ReportV2Controller::class, 'viewCtReport'])->name('report.ct.index');

    Route::get('/covid/check_pending', [HomeController::class, 'pendingSchedChecker'])->name('pendingshedchecker.index');
});

//COVID
Route::group(['middleware' => ['auth','verified', 'isAccountEnabled', 'isLevel1', 'canAccessCovid']], function() {
    Route::get('/covid/home', [HomeController::class, 'covid_home'])->name('covid_home');

    Route::get('/covid/records/duplicatechecker', [RecordsController::class, 'duplicateCheckerDashboard'])->name('records.duplicatechecker');
    Route::post('/covid/records/check', [RecordsController::class, 'check'])->name('records.check');
    Route::resource('/covid/records', RecordsController::class);

    Route::get('/covid/forms/ajaxList', [FormsController::class, 'ajaxList'])->name('forms.ajaxList');
    Route::get('/covid/forms/ajaxRecordList/{current_record_id}', [FormsController::class, 'recordajaxlist'])->name('forms.ajaxRecordList');
    Route::get('/covid/forms/ajaxcclist/', [FormsController::class, 'ajaxcclist'])->name('forms.ajaxcclist');
    Route::get('/covid/forms/printCIFList/', [FormsController::class, 'printCIFList'])->name('forms.ciflist.print');
    Route::get('/covid/forms/printAntigenLinelist/', [FormsController::class, 'printAntigenLinelist'])->name('forms.antigenlinelist.print');

    Route::get('/covid/forms/selfreport/', [SelfReportController::class, 'view'])->name('selfreport.view');
    Route::get('/covid/forms/selfreport/assess/{id}', [SelfReportController::class, 'edit'])->name('selfreport.edit');
    Route::post('/covid/forms/selfreport/assess/{id}', [SelfReportController::class, 'finishAssessment'])->name('selfreport.finishAssessment');
    Route::post('/covid/forms/selfreport/convert_to_suspected/{id}', [SelfReportController::class, 'convertToSuspected'])->name('selfreport_convertToSuspected');
    Route::get('/covid/forms/selfreport/viewdoc/{id}', [SelfReportController::class, 'viewDocument'])->name('selfreport.viewdocument');
    Route::match(array('GET','POST') ,'/covid/forms/reswab/{id}', [FormsController::class, 'reswab'])->name('forms.reswab');
    Route::resource('/covid/forms', FormsController::class);
    Route::get('/covid/forms/{id}/existing', [FormsController::class, 'viewExistingForm'])->name('forms.existing');
    Route::get('/covid/forms/{id}/new', [FormsController::class, 'new'])->name('forms.new');
    Route::post('/covid/forms/{id}/create', [FormsController::class, 'store'])->name('covid_forms_create');
    Route::post('/covid/forms/{id}/edit', [FormsController::class, 'upload'])->name('forms.upload');
    Route::post('/covid/forms/{id}/edit/qrecovered', [FormsController::class, 'qSetRecovered'])->name('forms.qSetRecovered');
    Route::post('/covid/forms/{id}/edit/tempsched', [FormsController::class, 'setTempSched'])->name('forms.setTempSched');
    Route::post('/covid/forms/{id}/edit/changedispo', [FormsController::class, 'cChangeDispo'])->name('forms.cChangeDispo');
    Route::post('/covid/forms/{id}/edit/transfer', [FormsController::class, 'transfercif'])->name('forms.transfercif');

    Route::get('/covid/forms/download/{id}', [FormsController::class, 'downloadDocs']);
    Route::post('/covid/forms/singleExport/{id}', [FormsController::class, 'soloExport'])->name('forms.soloprint.cif');
    Route::get('/covid/forms/printAntigen/{id}/{testType}', [FormsController::class, 'printAntigen'])->name('forms.soloprint.antigen');

    Route::post('/covid/forms/medcert/{form_id}', [FormsController::class, 'generateMedCert'])->name('generate_medcert');

    Route::get('/covid/forms/ct_exposure/{form_id}/create', [ContactTracingController::class, 'ctFormsExposureCreate'])->name('ct_exposure_create');
    Route::post('/covid/forms/ct_exposure/{form_id}/create', [ContactTracingController::class, 'ctFormsExposureStore'])->name('ct_exposure_store');
    Route::get('/covid/forms/ct_exposure/{form_id}/{ct_id}/edit', [ContactTracingController::class, 'ctFormsExposureEdit'])->name('ct_exposure_edit');
    Route::post('/covid/forms/ct_exposure/{form_id}/{ct_id}/edit', [ContactTracingController::class, 'ctFormsExposureUpdate'])->name('ct_exposure_update');
    Route::post('/covid/forms/ct_exposure/{ct_id}/delete', [ContactTracingController::class, 'ctFormsExposureDelete'])->name('ct_exposure_delete');

    Route::get('/covid/linelist', [LineListController::class, 'index'])->name('linelist.index');
    Route::post('/covid/linelist', [LineListController::class, 'createLineList'])->name('linelist.create');
    Route::post('/covid/linelist/oni/create', [LineListController::class, 'oniStore'])->name('linelist.oni.store');
    Route::post('/covid/linelist/lasalle/create', [LineListController::class, 'lasalleStore'])->name('linelist.lasalle.store');

    Route::get('/covid/linelist/ajaxList', [LineListController::class, 'ajaxLineList'])->name('linelist.ajax');
    Route::get('/covid/linelist/{link}/print/{id}', [LineListController::class, 'print'])->name('linelist.print');

    //Linelist V2
    Route::post('covid/linelistv2/create', [LineListController::class, 'createlinelistv2'])->name('llv2.create');
    Route::get('covid/linelistv2/view/{masterid}', [LineListController::class, 'viewlinelistv2'])->name('llv2.view');
    Route::post('covid/linelistv2/view/{masterid}/add', [LineListController::class, 'linelistv2addsub'])->name('llv2.add');
    Route::post('covid/linelistv2/view/{masterid}/process/{subid}', [LineListController::class, 'processlinelistv2'])->name('llv2.process');
    Route::post('covid/linelistv2/view/{masterid}/close', [LineListController::class, 'linelistv2close'])->name('llv2.close');

    Route::get('/covid/report', [ReportController::class, 'index'])->name('report.index');
    Route::get('/covid/report/daily', [ReportController::class, 'viewDaily'])->name('report.daily');
    Route::get('/covid/report/situational')->name('report.situational.index');
    Route::get('/covid/report/situationalv2', [ReportController::class, 'viewSituationalv2'])->name('report.situationalv2.index');
    Route::get('/covid/report/situational/excel', [ReportController::class, 'printSituationalv2'])->name('report.situationalv2.print');
    Route::get('/covid/report/clustering', [ReportV2Controller::class, 'clustering_index'])->name('clustering_index');
    Route::get('/covid/report/clustering/{city}/{brgy}', [ReportController::class, 'viewClustering'])->name('clustering_view');
    Route::get('/covid/report/clustering/{city}/{brgy}/view_list/q={subd}', [ReportV2Controller::class, 'clustering_viewlist'])->name('clustering_viewlist');
    Route::post('/covid/report/dohExportAll/', [ReportController::class, 'dohExportAll'])->name('report.DOHExportAll');
    Route::get('/covid/report/dilgExportAll/', [ReportController::class, 'dilgExportAll'])->name('report.dilgExportAll');
    Route::post('/covid/report/export', [ReportController::class, 'reportExport'])->name('report.export');
    Route::get('/covid/report/v2/dashboard', [ReportV2Controller::class, 'viewDashboard'])->name('reportv2.dashboard');

    Route::get('/covid/report/cm', [ReportV2Controller::class, 'cmIndex'])->name('report_cm_index');

    //ajax
    Route::get('/covid/ajaxGetUserRecord/{id}', [FormsController::class, 'ajaxGetUserRecord']);
    //Route::get('/ajaxGetLineList', [LineListController::class, 'ajaxGetLineList']);

    Route::post('/covid/options', [OptionsController::class, 'submit'])->name('options.submit');
    Route::get('/covid/options', [OptionsController::class, 'index'])->name('options.index');

    Route::post('/covid/forms', [FormsController::class, 'options'])->name('forms.options'); //print to excel, for admin only (temporary)

    Route::post('/covid/msheet/{forms_id}/create', [MonitoringSheetController::class, 'create'])->name('msheet.create');
    Route::get('/covid/msheet/{id}/view', [MonitoringSheetController::class, 'view'])->name('msheet.view');
    Route::get('/covid/msheet/{id}/print', [MonitoringSheetController::class, 'print'])->name('msheet.print');
    Route::get('/covid/msheet/{id}/{date}/{mer}', [MonitoringSheetController::class, 'viewdate'])->name('msheet.viewdate');
    Route::post('/covid/msheet/{id}/{date}/{mer}', [MonitoringSheetController::class, 'updatemonitoring'])->name('msheet.updatemonitoring');

    Route::get('/covid/ct/index', [ContactTracingController::class, 'dashboard_index'])->name('ct.dashboard.index');
    Route::get('/covid/ct/sp/index', [SecondaryTertiaryRecordsController::class, 'index'])->name('sc_index');
    Route::get('/covid/ct/sp/create', [SecondaryTertiaryRecordsController::class, 'create'])->name('sc_create');
    Route::post('/covid/ct/sp/create', [SecondaryTertiaryRecordsController::class, 'store'])->name('sc_store');
    Route::get('/covid/ct/{id}/edit', [SecondaryTertiaryRecordsController::class, 'edit'])->name('sc_edit');
    Route::put('/covid/ct/{id}/edit', [SecondaryTertiaryRecordsController::class, 'update'])->name('sc_update');

    Route::get('/covid/report/encoding_calendar', [ReportV2Controller::class, 'encodingCalendar'])->name('encoding_calendar');

    Route::get('/covid/report/ctreport2', [ContactTracingController::class, 'ctlgureport'])->name('ctlgu_report');

    Route::get('/covid/casechecker', [ReportV2Controller::class, 'casechecker_index'])->name('casechecker_index');
    
    Route::get('/covid/report/accomplishment', [ReportV2Controller::class, 'accomplishment_index'])->name('report.accomplishment');
    Route::get('/covid/report/fhsis', [ReportV2Controller::class, 'm2fhsis'])->name('report.fhsis');

    //Monkeypox
    /*
    Route::get('/monkeypox', [MonkeyPoxController::class, 'home'])->name('mp.home');
    Route::get('/monkeypox/ajaxlist', [MonkeyPoxController::class, 'ajaxlist'])->name('mp.ajaxlist');
    Route::get('/monkeypox/cif/{record_id}/new', [MonkeyPoxController::class, 'create_cif'])->name('mp.newcif');
    Route::post('/monkeypox/cif/{record_id}/new', [MonkeyPoxController::class, 'store_cif'])->name('mp.storecif');
    Route::get('/monkeypox/cif/{mk}/edit', [MonkeyPoxController::class, 'edit_cif'])->name('mp.editcif');
    Route::post('/monkeypox/cif/{mk}/update', [MonkeyPoxController::class, 'update_cif'])->name('mp.updatecif');
    */

    //Dengue
    /*
    Route::get('/dengue', [DengueController::class, 'home'])->name('dg.home');
    Route::get('/dengue/cif', [DengueController::class, 'cifhome'])->name('dg.cifhome');
    Route::get('/dengue/cif/{record_id}/new', [DengueController::class, 'create_cif'])->name('dg.newcif');
    Route::post('/dengue/cif/{record_id}/new', [DengueController::class, 'store_cif'])->name('dg.storecif');
    Route::get('/dengue/cif/{cif_id}/edit', [DengueController::class, 'edit_cif'])->name('dg.editcif');
    Route::post('/dengue/cif/{cif_id}/update', [DengueController::class, 'update_cif'])->name('dg.updatecif');
    */
});

//SYSTEM ADMIN
Route::group(['middleware' => ['auth','verified','isAccountEnabled', 'isAdmin']], function()
{
    //Route::get('/exportcesu', [ReportV2Controller::class, 'exportdb'])->name('edb');
    Route::get('/admin/settings/home', [SiteSettingsController::class, 'settingsHome'])->name('settings_home');
    Route::get('/admin/settings/general', [SiteSettingsController::class, 'generalSettings'])->name('settings_general_view');
    Route::post('/admin/settings/update', [SiteSettingsController::class, 'generalSettingsUpdate'])->name('settings_general_update');

    Route::get('/admin/settings/subdivision', [SubdivisionController::class, 'index'])->name('subdivision_index');
    Route::post('/admin/settings/subdivision/import', [SubdivisionController::class, 'import'])->name('subdivision_import');
});

//COVID ADMIN
Route::group(['middleware' => ['auth','verified','isAccountEnabled', 'isAdmin', 'canAccessCovid']], function()
{
    //Admin Page
    Route::get('/covid/admin', [AdminPanelController::class, 'index'])->name('adminpanel.index');

    //Barangay
    Route::get('/covid/admin/brgy', [AdminPanelController::class, 'brgyIndex'])->name('adminpanel.brgy.index');
    Route::get('/covid/admin/brgy/view/{id}', [AdminPanelController::class, 'brgyView'])->name('adminpanel.brgy.view');
    Route::post('/covid/admin/brgy/view/{id}', [AdminPanelController::class, 'brgyUpdate'])->name('adminpanel.brgy.update');
    Route::get('/covid/admin/brgy/view/{brgy_id}/user/{user_id}', [AdminPanelController::class, 'brgyViewUser'])->name('adminpanel.brgy.view.user');
    Route::post('/covid/admin/brgy/view/{brgy_id}/user/{user_id}', [AdminPanelController::class, 'brgyUpdateUser'])->name('adminpanel.brgy.update.user');

    //Referral Code
    Route::get('/covid/admin/referral_code/', [AdminPanelController::class, 'referralCodeView'])->name('adminpanel.code.view');
    Route::post('/covid/admin/brgy/create/data', [AdminPanelController::class, 'brgyStore'])->name('adminpanel.brgy.store');
    Route::post('/covid/admin/brgy/create/code/{brgy_id}/', [AdminPanelController::class, 'brgyCodeStore'])->name('adminpanel.brgyCode.store');

    //Admin Accounts
    Route::get('/covid/admin/accounts', [AdminPanelController::class, 'accountIndex'])->name('adminpanel.account.index');
    Route::get('/covid/admin/accounts/view/{id}', [AdminPanelController::class, 'accountView'])->name('adminpanel.account.view');
    Route::post('/covid/admin/accounts/view/{id}', [AdminPanelController::class, 'accountUpdate'])->name('adminpanel.account.update');
    Route::post('/covid/admin/accounts/create', [AdminPanelController::class, 'adminCodeStore'])->name('adminpanel.account.create');
    Route::post('/covid/admin/accounts/{id}/options', [AdminPanelController::class, 'accountOptions'])->name('adminpanel.account.options');

    //Interviewers
    Route::post('/covid/admin/interviewers/options/{id}', [InterviewersController::class, 'options'])->name('adminpanel.interviewers.options');
    Route::resource('/covid/admin/interviewers', InterviewersController::class);

    //Companies
    Route::resource('/covid/companies', CompaniesController::class);
    Route::post('/covid/companies/code/create', [CompaniesController::class, 'makeCode'])->name('companies.makecode');

    //Paswablinks
    Route::get('/covid/admin/paswablinks', [PaSwabLinksController::class, 'index'])->name('paswablinks.index');
    Route::post('/covid/admin/paswablinks', [PaSwabLinksController::class, 'store'])->name('paswablinks.store');
    Route::post('/covid/admin/paswablinks/{id}/options', [PaSwabLinksController::class, 'linkInit']);

    Route::get('/covid/admin/antigen', [AntigenController::class, 'index'])->name('antigen_index');
    Route::get('/covid/admin/antigen/create', [AntigenController::class, 'create'])->name('antigen_create');
    Route::post('/covid/admin/antigen/create', [AntigenController::class, 'store'])->name('antigen_store');
    Route::get('/covid/admin/antigen/{id}/edit', [AntigenController::class, 'edit'])->name('antigen_edit');
    Route::post('/covid/admin/antigen/{id}/edit', [AntigenController::class, 'update'])->name('antigen_update');

    //Acceptance Letter
    Route::get('/covid/acceptance_letter', [AcceptanceLetterController::class, 'index'])->name('acceptance.index');
    Route::post('/covid/acceptance_letter/store', [AcceptanceLetterController::class, 'store'])->name('acceptance.store');
    Route::get('/covid/acceptance_letter/print/{id}', [AcceptanceLetterController::class, 'printview'])->name('acceptance.print');

    //MW
    Route::get('/covid/report/mw', [MorbidityWeekController::class, 'index'])->name('mw.index');
    Route::post('/covid/report/mw/process', [MorbidityWeekController::class, 'process'])->name('mw.process');

    //Site Settings
    Route::get('/covid/settings/site', [SiteSettingsController::class, 'index'])->name('ss.index');
    Route::post('/covid/settings/site', [SiteSettingsController::class, 'update'])->name('ss.update');
});

Route::group(['middleware' => ['auth','verified','isAccountEnabled', 'isLevel2']], function() {
    
});

//PIDSR
Route::group(['middleware' => ['auth','verified','isAccountEnabled', 'canAccessPidsr']], function() {
    Route::get('/pidsr', [PIDSRController::class, 'home'])->name('pidsr.home');
    Route::get('/pidsr/threshold', [PIDSRController::class, 'threshold_index'])->name('pidsr.threshold');
    Route::get('/pidsr/import', [PIDSRController::class, 'import_start'])->name('pidsr.import');
    Route::post('/pidsr/daily_merge', [PIDSRController::class, 'dailyMergeProcess'])->name('pidsr_dailymerge_start');
    Route::post('/pidsr/mergev2_start', [PIDSRController::class, 'weeklyMergeProcess'])->name('pidsr_mergev2_start');
    Route::get('/pidsr/import_edcs', [PIDSRController::class, 'edcsImportExcelProcess'])->name('pidsr_import_edcs');
    Route::get('/pidsr/import_ftp', [PIDSRController::class, 'importToFtp'])->name('pidsr_import_ftp');

    Route::get('/pidsr/notification', [PIDSRController::class, 'notifIndex'])->name('pidsr_notif_index');
    Route::get('/pidsr/notification/{id}/view', [PIDSRController::class, 'notifView'])->name('pidsr_notif_view');
    
    Route::get('/pidsr/report', [PIDSRController::class, 'report_generate'])->name('pidsr.report');
    Route::get('/pidsr/import/sendmail', [PIDSRController::class, 'manualsend'])->name('pidsr.sendmail');
    Route::get('/pidsr/epdrone', [PIDSRController::class, 'epDroneHome'])->name('pidsr_epdrone_home');
    Route::get('/pidsr/casechecker', [PIDSRController::class, 'casechecker'])->name('pidsr.casechecker');
    Route::get('/pidsr/casechecker/{disease}/{epi_id}/edit', [PIDSRController::class, 'caseCheckerEdit'])->name('pidsr_casechecker_edit');
    Route::post('/pidsr/casechecker/{disease}/{epi_id}/update', [PIDSRController::class, 'caseCheckerUpdate'])->name('pidsr_casechecker_update');
    
    Route::get('/pidsr/view/{year}/{mw}', [PIDSRController::class, 'weeklycaseviewer'])->name('pidsr.weeklyviewer');

    Route::get('/pidsr/snaxv2', [PIDSRController::class, 'snaxVersionTwoController'])->name('pidsr_snaxv2');
    Route::get('/pidsr/generate_threshold', [PIDSRController::class, 'generateThreshold'])->name('pidsr_generate_threshold');

    Route::get('/pidsr/for_validation', [PIDSRController::class, 'forValidationIndex'])->name('pidsr_forvalidation_index');
    Route::get('/pidsr/laboratory/', [PIDSRController::class, 'labLogbook'])->name('pidsr_laboratory_home');
    Route::post('/pidsr/laboratory/store_group', [PIDSRController::class, 'storeLogBookGroup'])->name('pidsr_laboratory_groups_store');
    Route::post('/pidsr/laboratory/{group}/update', [PIDSRController::class, 'updateLabLogBookGroup'])->name('pidsr_laboratory_groups_update');
    Route::get('/pidsr/laboratory/{group}/', [PIDSRController::class, 'viewLogBookGroup'])->name('pidsr_laboratory_group_home');
    Route::get('/pidsr/laboratory/{group}/print', [PIDSRController::class, 'printLabLogBook'])->name('pidsr_laboratory_print');

    Route::get('/pidsr/casechecker/link_edcs', [PIDSRController::class, 'linkEdcs'])->name('pidsr_laboratory_linkedcs');
    Route::post('/pidsr/casechecker/link_edcs/process', [PIDSRController::class, 'linkEdcsProcess'])->name('pidsr_laboratory_linkedcs_process');

    Route::post('/pidsr/laboratory/{group}/store_patient', [PIDSRController::class, 'storePatientLabLogBook'])->name('pidsr_laboratory_group_patient_store');
    Route::get('/pidsr/laboratory/{group}/view_patient/{id}', [PIDSRController::class, 'viewPatientLabLogBook'])->name('pidsr_laboratory_group_patient_view');
    Route::post('/pidsr/laboratory/{group}/view_patient/{id}/update', [PIDSRController::class, 'updatePatientLabLogBook'])->name('pidsr_laboratory_group_patient_update');
    //Route::delete('/pidsr/laboratory/{id}/delete', [PIDSRController::class, 'deleteLabLogBook'])->name('pidsr_laboratory_delete');
    Route::get('/pidsr/viewcif/{case}/{epi_id}', [PIDSRController::class, 'viewCif'])->name('pidsr_viewcif');

    Route::get('/pidsr/map_viewer/{case}', [PIDSRController::class, 'mapViewerIndex'])->name('pidsr_case_mapviewer');
    Route::get('/pidsr/mpGetColor', [PIDSRController::class, 'mapViewerGetColor'])->name('pidsr_case_mapviewerGetColor');
});

Route::get('/edcs/barangayportal', [PIDSRController::class, 'brgyCaseViewerWelcome'])->name('edcs_barangay_welcome');
Route::get('/edcs/barangayportal/quicklogin', [PIDSRController::class, 'brgyCaseViewerQuickLogin'])->name('edcs_barangay_quicklogin');
Route::post('/edcs/barangayportal/login', [PIDSRController::class, 'brgyCaseViewerLogin'])->name('edcs_barangay_login');

Route::group(['middleware' => ['isLoggedInEdcsBrgyPortal']], function() {
    Route::get('/edcs/barangayportal/home', [PIDSRController::class, 'brgyCaseViewerHome'])->name('edcs_barangay_home');
    Route::get('/edcs/barangayportal/{case}/view', [PIDSRController::class, 'brgyCaseViewerViewList'])->name('edcs_barangay_view_list');
    Route::get('/edcs/barangayportal/viewcif/{case}/{epi_id}', [PIDSRController::class, 'viewCif'])->name('edcs_barangay_view_cif');
    Route::post('/edcs/barangayportal/logout', [PIDSRController::class, 'brgyCaseViewerLogout'])->name('edcs_barangay_view_logout');
});

Route::group(['middleware' => ['auth','verified','isAccountEnabled', 'canAccessPidsrAdminMode']], function() {
    Route::get('/pidsr/casechecker/action', [PIDSRController::class, 'casechecker_action'])->name('pidsr_casechecker_action');
    Route::get('/pidsr/reset_sent', [PIDSRController::class, 'resetSendingStatus'])->name('pidsr_reset_sent');
});

//PREGNANCY TRACKING
Route::group(['middleware' => ['auth','verified','isAccountEnabled', 'canAccessPregnancyTracking']], function() {
    Route::get('/pregnancy_tracking', [PregnancyTrackingController::class, 'index'])->name('ptracking_index');

    Route::get('/pregnancy_tracking/new', [PregnancyTrackingController::class, 'new'])->name('ptracking_new');
    Route::post('/pregnancy_tracking/store', [PregnancyTrackingController::class, 'store'])->name('ptracking_store');

    Route::get('/pregnancy_tracking/{id}/edit', [PregnancyTrackingController::class, 'edit'])->name('ptracking_edit');
    Route::post('/pregnancy_tracking/{id}/update', [PregnancyTrackingController::class, 'update'])->name('ptracking_update');
    
    Route::post('/pregnancy_tracking/monthly_report1', [PregnancyTrackingController::class, 'monthlyreport1'])->name('ptracking_monthlyreport1');
});

//SYNDROMIC
Route::group(['middleware' => ['auth','verified','isAccountEnabled', 'canAccessSyndromic']], function() {
    Route::get('/syndromic', [SyndromicController::class, 'index'])->name('syndromic_home');
    Route::get('/syndromic/dengue', [SyndromicController::class, 'dengue'])->name('syndromic_dengue');
    Route::get('/syndromic/download_opd_excel', [SyndromicController::class, 'downloadOpdExcel'])->name('syndromic_download_opd_excel');
    Route::get('/syndromic/patient/new', [SyndromicController::class, 'newPatient'])->name('syndromic_newPatient');
    Route::post('/syndromic/patient/store', [SyndromicController::class, 'storePatient'])->name('syndromic_storePatient');
    Route::get('/syndromic/patient/{patient_id}/records/new', [SyndromicController::class, 'newRecord'])->name('syndromic_newRecord');
    Route::post('/syndromic/patient/{patient_id}/records/store', [SyndromicController::class, 'storeRecord'])->name('syndromic_storeRecord');

    Route::get('/syndromic/icd10list', [SyndromicController::class, 'icd10list'])->name('syndromic_icd10list');
    Route::get('/syndromic/icd10list/select/{code}', [SyndromicController::class, 'icd10list_getcode'])->name('syndromic_icd10list_getcode');

    Route::get('/syndromic/pharmacy/meds_list', [SyndromicController::class, 'pharmacyMedsList'])->name('syndromic_medsList');
    
    Route::get('/syndromic/patient/{patient_id}/view', [SyndromicController::class, 'viewPatient'])->name('syndromic_viewPatient');
    Route::post('/syndromic/patient/{patient_id}/delete', [SyndromicController::class, 'deletePatient'])->name('syndromic_deletePatient');
    Route::get('/syndromic/patient/{patient_id}/record_list', [SyndromicController::class, 'viewExistingRecordList'])->name('syndromic_viewItrList');
    Route::get('/syndromic/records/{records_id}/view', [SyndromicController::class, 'viewRecord'])->name('syndromic_viewRecord');
    Route::get('/syndromic/records/{records_id}/download_itr', [SyndromicController::class, 'downloadItrDocx'])->name('syndromic_downloadItr');
    Route::post('/syndromic/patient/{patient_id}/update', [SyndromicController::class, 'updatePatient'])->name('syndromic_updatePatient');
    Route::post('/syndromic/records/{records_id}/update', [SyndromicController::class, 'updateRecord'])->name('syndromic_updateRecord');
    Route::post('/syndromic/records/{records_id}/delete', [SyndromicController::class, 'deleteRecord'])->name('syndromic_deleteRecord');
    Route::get('/syndromic/records/lab/{record_id}/create', [SyndromicController::class, 'createLabResult'])->name('syndromic_create_labresult');

    Route::get('/syndromic/map', [SyndromicController::class, 'diseasemap'])->name('syndromic_map');
    Route::get('/syndromic/disease_list', [SyndromicController::class, 'viewDiseaseList'])->name('syndromic_disease_list');

    Route::post('/syndromic/records/{records_id}/medcert/generate', [SyndromicController::class, 'generateMedCert'])->name('syndromic_generate_medcert');
    Route::get('/syndromic/records/{records_id}/medcert', [SyndromicController::class, 'viewMedCert'])->name('syndromic_view_medcert');

    Route::get('/syndromic/disease_checker', [SyndromicController::class, 'diseaseCheckerMain'])->name('syndromic_diseasechecker');
    Route::get('/syndromic/disease_checker/list', [SyndromicController::class, 'diseaseCheckerList'])->name('syndromic_diseasechecker_specific');

    Route::get('/syndromic/hospital/daily_summary', [SyndromicController::class, 'hospDailyReport'])->name('opd_hospital_dailysummary');
    Route::get('/syndromic/hospital/monthly_summary', [SyndromicController::class, 'hospSummaryReport'])->name('opd_hospital_monthlysummary');
    Route::get('/syndromic/hospital/download_alphalist', [SyndromicController::class, 'downloadAlphaList'])->name('opd_hospital_downloadalphalist');

    Route::post('/syndromic/medical_event/store', [SyndromicController::class, 'storeMedicalEvent'])->name('opd_medicalevent_store');
    Route::post('/syndromic/medical_event/join', [SyndromicController::class, 'joinMedicalEvent'])->name('opd_medicalevent_join');
    Route::post('/syndromic/medical_event/unjoin', [SyndromicController::class, 'unJoinMedicalEvent'])->name('opd_medicalevent_unjoin');
});

Route::group(['middleware' => ['auth','verified','isAccountEnabled', 'isGlobalAdmin']], function() {
    //SYNDROMIC ADMIN
    Route::get('/syndromic/admin/doctors', [SyndromicAdminController::class, 'doctors_index'])->name('syndromic_admin_doctors_index');
    Route::post('/syndromic/admin/doctors/store', [SyndromicAdminController::class, 'doctors_store'])->name('syndromic_admin_doctors_store');
    Route::get('/syndromic/admin/doctors/{id}/edit', [SyndromicAdminController::class, 'doctors_edit'])->name('syndromic_admin_doctors_edit');
    Route::post('/syndromic/admin/doctors/{id}/update', [SyndromicAdminController::class, 'doctors_update'])->name('syndromic_admin_doctors_update');
});

Route::group(['middleware' => ['auth','verified','isAccountEnabled', 'isLevel3']], function() {
    //Facility Account Middleware
    Route::get('facility/home', [FacilityController::class, 'index'])->name('facility.home');
    Route::get('facility/{id}/viewPatient', [FacilityController::class, 'viewPatient'])->name('facility.viewdischarge');
    Route::post('facility/{id}/update', [FacilityController::class, 'update'])->name('facility.update');
    Route::post('facility/{id}/viewPatient', [FacilityController::class, 'initDischarge'])->name('facility.initdischarge');
});

//ANIMAL BITE ABTC
Route::group(['middleware' => ['auth','verified', 'isAccountEnabled', 'canAccessAbtc']], function () {
    Route::get('/abtc', [ABTCPatientController::class, 'home'])->name('abtc_home');
    Route::post('/abtc/init_vbrand', [ABTCPatientController::class, 'initVaccineBrand'])->name('abtc_init_vbrand');
    Route::post('/abtc/init_stocks', [ABTCPatientController::class, 'initVaccineStocks'])->name('abtc_init_vstocks');
    Route::post('/abtc/init_wastage', [ABTCPatientController::class, 'initDailyWastage'])->name('abtc_init_wastage');
    
    Route::get('/abtc/patient', [ABTCPatientController::class, 'index'])->name('abtc_patient_index');
    Route::get('/abtc/patient/create', [ABTCPatientController::class, 'create'])->name('abtc_patient_create');
    Route::post('/abtc/patient/create', [ABTCPatientController::class, 'store'])->name('abtc_patient_store');
    Route::get('/abtc/patient/{id}/edit', [ABTCPatientController::class, 'edit'])->name('abtc_patient_edit');
    Route::post('/abtc/patient/{id}/edit', [ABTCPatientController::class, 'update'])->name('abtc_patient_update');
    Route::delete('/abtc/patient/{id}/delete', [ABTCPatientController::class, 'destroy'])->name('abtc_patient_destroy');
    Route::get('/abtc/patient/ajaxList', [ABTCPatientController::class, 'ajaxList'])->name('abtc_patient_ajaxlist');
    
    Route::get('/abtc/patient/bakuna_records/{id}', [ABTCPatientController::class, 'patient_viewbakunarecords'])->name('abtc_patient_viewbakunarecords');
    Route::post('/abtc/patient/quickscan', [ABTCVaccinationController::class, 'qr_quicksearch'])->name('abtc_qr_quicksearch');

    Route::get('/abtc/vaccination_site', [ABTCAdminController::class, 'vaccinationsite_index'])->name('abtc_vaccinationsite_index');
    Route::post('/abtc/vaccination_site', [ABTCAdminController::class, 'vaccinationsite_store'])->name('abtc_vaccinationsite_store');

    Route::get('/abtc/vaccine_brand', [ABTCAdminController::class, 'vaccinebrand_index'])->name('abtc_vaccinebrand_index');
    Route::post('/abtc/vaccine_brand', [ABTCAdminController::class, 'vaccinebrand_store'])->name('abtc_vaccinebrand_store');
    Route::get('/abtc/gupdate', [ABTCAdminController::class, 'gupdate'])->name('gupdate');

    Route::post('/abtc/encode_search', [ABTCVaccinationController::class, 'search_init'])->name('abtc_search_init');
    Route::get('/abtc/encode/existing/{id}', [ABTCVaccinationController::class, 'encode_existing'])->name('abtc_encode_existing');
    
    Route::get('/abtc/encode/new/{id}', [ABTCVaccinationController::class, 'create_new'])->name('abtc_encode_create_new');
    Route::post('/abtc/encode/new/{id}', [ABTCVaccinationController::class, 'create_store'])->name('abtc_encode_store');
    Route::get('/abtc/encode/print/{id}', [ABTCVaccinationController::class, 'print_view'])->name('abtc_print_view');
    Route::get('/abtc/encode/print/new/{id}', [ABTCVaccinationController::class, 'newprint'])->name('abtc_print_new'); //Card

    Route::get('/abtc/encode/edit/{br_id}', [ABTCVaccinationController::class, 'encode_edit'])->name('abtc_encode_edit');
    Route::post('/abtc/encode/edit/{br_id}', [ABTCVaccinationController::class, 'encode_update'])->name('abtc_encode_update');
    Route::delete('/abtc/encode/edit/{br_id}', [ABTCVaccinationController::class, 'destroy'])->name('abtc_encode_destroy');

    Route::get('/abtc/encode/edit/{br_id}/override_schedule', [ABTCVaccinationController::class, 'override_schedule'])->name('abtc_override_schedule');
    Route::post('/abtc/encode/edit/{br_id}/override_schedule', [ABTCVaccinationController::class, 'override_schedule_process'])->name('abtc_override_schedule_process');

    Route::get('/abtc/encode/process_vaccination/{br_id}/{dose}', [ABTCVaccinationController::class, 'encode_process'])->name('abtc_encode_process');
    Route::get('/abtc/encode/process_vaccination/{br_id}/{dose}/late_process', [ABTCVaccinationController::class, 'encode_processLate'])->name('abtc_encode_process_late');

    Route::get('/abtc/encode/rebakuna/{patient_id}', [ABTCVaccinationController::class, 'bakuna_again'])->name('abtc_bakuna_again');
    Route::get('/abtc/encode/animaldead/{br_id}', [ABTCVaccinationController::class, 'markdead'])->name('abtc_mark_dead');

    Route::get('/abtc/report/linelist', [ABTCReportController::class, 'linelist_index'])->name('abtc_report_linelist_index');
    Route::get('/abtc/report/linelist2', [ABTCReportController::class, 'linelist2'])->name('abtc_report_linelist2_index');
    Route::get('/abtc/report/cho', [ABTCReportController::class, 'choreport1'])->name('abtc_report_cho');
    Route::post('/abtc/report/export1', [ABTCReportController::class, 'export1'])->name('abtc_report_export1');
    Route::get('/abtc/report/main', [ABTCReportController::class, 'mainreport'])->name('abtc_report_main');

    Route::post('/abtc/settings/save', [ABTCUserSettingsController::class, 'save_settings'])->name('abtc_save_settings');

    Route::get('/abtc/sched', [ABTCVaccinationController::class, 'schedule_index'])->name('abtc_schedule_index');
    Route::get('/abtc/report/dashboard', [ABTCReportController::class, 'dashboard'])->name('abtc_dashboard');

    Route::get('/abtc/ffsms', [ABTCVaccinationController::class, 'ffsms'])->name('abtc_ffsms');
    Route::get('/abtc/rslip/{br_id}', [ABTCVaccinationController::class, 'referralslip'])->name('abtc_referralslip');
    Route::get('/abtc/itr/{br_id}', [ABTCVaccinationController::class, 'itr'])->name('abtc_itr');
    Route::get('/abtc/medcert/{br_id}', [ABTCVaccinationController::class, 'medcert'])->name('abtc_medcert');

    Route::post('/abtc/xlimport', [ABTCAdminController::class, 'xlimport'])->name('abtc_xlimport');
    Route::get('/abtc/remaining_pt', [ABTCVaccinationController::class, 'remainingPt'])->name('abtc_remainingpt');
});

//FHSIS
Route::group(['middleware' => ['auth','verified','isAccountEnabled', 'canAccessFhsis']], function()
{
    Route::get('/fhsis', [FhsisController::class, 'home'])->name('fhsis_home');

    Route::get('/fhsis/report', [FhsisController::class, 'report'])->name('fhsis_report');
    Route::get('/fhsis/fastlookup2', [FhsisController::class, 'fastlookuptwo'])->name('fhsis_fastlookup2');

    Route::get('/fhsis/cesum2', [FhsisController::class, 'cesum2'])->name('fhsis_cesum2');
    Route::get('/fhsis/timeliness', [FhsisController::class, 'timelinesscheck'])->name('fhsis_timeliness');
    Route::get('/fhsis/import', [FhsisController::class, 'pquery'])->name('fhsis_pquery');
    Route::get('/fhsis/livebirths/encode', [FhsisController::class, 'liveBirthsEncode'])->name('fhsis_livebirth_encode');
    Route::post('/fhsis/livebirths/encode/store', [FhsisController::class, 'liveBirthsStore'])->name('fhsis_livebirth_encode_store');
    Route::get('/fhsis/livebirths/report', [FhsisController::class, 'liveBirthsReport'])->name('fhsis_livebirth_report');
});

//ABTC (WALK IN)
Route::group(['middleware' => ['guest']], function() {
    Route::get('/abtc/walkin', [ABTCWalkInRegistrationController::class, 'walkin_part1'])->name('abtc_walkin_part1');
    Route::get('/abtc/walkin/register', [ABTCWalkInRegistrationController::class, 'walkin_part2'])->name('abtc_walkin_part2');
    Route::post('/abtc/walkin/register', [ABTCWalkInRegistrationController::class, 'walkin_part3'])->name('abtc_walkin_part3');
    
    Route::get('/itr', [SyndromicController::class, 'walkin_part1'])->name('syndromic_walkin1');
    Route::get('/itr/register', [SyndromicController::class, 'walkin_part2'])->name('syndromic_walkin2');
    Route::post('/itr/register', [SyndromicController::class, 'walkin_part3'])->name('syndromic_walkin3');
});

//PASWAB GUEST, SELF REPORT, MONITORING SHEET
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

//VAXCERT
Route::group(['middleware' => ['auth','verified', 'isAccountEnabled', 'canAccessVaxcert']], function() {
    Route::get('/vaxcert/home', [VaxcertController::class, 'home'])->name('vaxcert_home');
    Route::get('/vaxcert/vquery', [VaxcertController::class, 'vquery'])->name('vaxcert_vquery');
    Route::get('/vaxcert/report', [VaxcertController::class, 'report'])->name('vaxcert_report');
    Route::get('/vaxcert/ticket/view/{id}', [VaxcertController::class, 'view_patient'])->name('vaxcert_viewpatient');
    Route::post('/vaxcert/ticket/view/{id}/process', [VaxcertController::class, 'process_patient'])->name('vaxcert_processpatient');

    Route::get('/vaxcert/ticket/view/{id}/basedl', [VaxcertController::class, 'dlbase_template'])->name('vaxcert_basedl');
    Route::get('/vaxcert/vquery/export/{id}', [VaxcertController::class, 'dloff_template'])->name('vaxcert_vquery_template');

    Route::get('/vaxcert/vquery/template_maker', [VaxcertController::class, 'templateMaker'])->name('vaxcert_vquery_templatemaker');
    Route::post('/vaxcert/vquery/template_maker/process', [VaxcertController::class, 'templateMakerProcess'])->name('vaxcert_vquery_templatemakerprocess');
});

//PHARMACY
Route::group(['middleware' => ['auth','verified', 'isAccountEnabled', 'canAccessPharmacy']], function() {
    Route::get('/pharmacy/home', [PharmacyController::class, 'home'])->name('pharmacy_home');
    Route::get('/pharmacy/item_list', [PharmacyController::class, 'viewItemList'])->name('pharmacy_itemlist');
    Route::get('/pharmacy/item_list/masterlist', [PharmacyController::class, 'viewMasterlist2'])->name('pharmacy_itemlist_masterlist');
    Route::post('/pharmacy/item_list/add_master_item', [PharmacyController::class, 'addMasterItem'])->name('pharmacy_add_master_item');
    Route::post('/pharmacy/item_list/add_item', [PharmacyController::class, 'addItem'])->name('pharmacy_additem');
    
    Route::get('/pharmacy/process/scan', [PharmacyController::class, 'modifyStockQr'])->name('pharmacy_modify_qr');
    Route::get('/pharmacy/process/{subsupply_id}', [PharmacyController::class, 'modifyStockView'])->name('pharmacy_modify_view');
    Route::post('/pharmacy/process/{subsupply_id}/submit', [PharmacyController::class, 'modifyStockProcess'])->name('pharmacy_modify_process');

    Route::get('/pharmacy/process/patient/{id}', [PharmacyController::class, 'modifyStockPatientView'])->name('pharmacy_modify_patient_stock');
    Route::post('/pharmacy/process/patient/{id}/add_cart', [PharmacyController::class, 'addCartItem'])->name('pharmacy_patient_addcart');
    Route::post('/pharmacy/process/patient/{id}/process_cart', [PharmacyController::class, 'processCartItem'])->name('pharmacy_patient_process_cart');
    //Route::post('/pharmacy/process/patient/{id}', [PharmacyController::class, 'modifyStockPatientProcess'])->name('pharmacy_modify_patient_stock_process');

    Route::get('/pharmacy/cart/branch/{branch_id}', [PharmacyController::class, 'modifyStockBranchView'])->name('pharmacy_viewBranchCart');
    Route::post('/pharmacy/cart/branch/{branch_id}/add_cart', [PharmacyController::class, 'addCartBranch'])->name('pharmacy_addCartBranch');
    Route::post('/pharmacy/cart/branch/{branch_id}/process_cart', [PharmacyController::class, 'processCartBranch'])->name('pharmacy_processCartBranch');
    
    Route::get('/pharmacy/item_list/{item_id}/view', [PharmacyController::class, 'viewItem'])->name('pharmacy_itemlist_viewitem');
    Route::get('/pharmacy/item_list/{item_id}/print', [PharmacyController::class, 'printQrItem'])->name('pharmacy_itemlist_printqr');
    Route::post('/pharmacy/item_list/{item_id}/export_stockcard', [PharmacyController::class, 'exportStockCard'])->name('pharmacy_itemlist_export_stockcard');
    Route::get('/pharmacy/item_list/{item_id}/monthly_stock', [PharmacyController::class, 'viewItemMonthlyStock'])->name('pharmacy_view_monthlystock');
    Route::post('/pharmacy/item_list/{item_id}/view/update', [PharmacyController::class, 'updateItem'])->name('pharmacy_itemlist_updateitem');

    Route::get('/pharmacy/report', [PharmacyController::class, 'viewReport'])->name('pharmacy_viewreport');
    Route::get('/pharmacy/report2', [PharmacyController::class, 'report2'])->name('pharmacy_viewreport2');
    Route::post('/pharmacy/report/get_medsdispensary', [PharmacyController::class, 'generateMedicineDispensary'])->name('pharmacy_getdispensary');

    Route::get('/pharmacy/patients', [PharmacyController::class, 'viewPatientList'])->name('pharmacy_view_patient_list');
    Route::get('/pharmacy/patients/view/{id}', [PharmacyController::class, 'viewPatient'])->name('pharmacy_view_patient');
    
    Route::post('/pharmacy/patients/view/{id}', [PharmacyController::class, 'updatePatient'])->name('pharmacy_update_patient');
    Route::post('/pharmacy/patients/view/{id}/delete', [PharmacyController::class, 'deletePatient'])->name('pharmacy_delete_patient');

    Route::get('/pharmacy/patients/create', [PharmacyController::class, 'newPatient'])->name('pharmacy_add_patient');
    Route::post('/pharmacy/patients/create', [PharmacyController::class, 'storePatient'])->name('pharmacy_store_patient');

    Route::get('/pharmacy/item_list/substock/{id}', [PharmacyController::class, 'viewSubStock'])->name('pharmacy_view_substock');
    Route::get('/pharmacy/item_list/substock/{id}/print_qr', [PharmacyController::class, 'printQrSubStock'])->name('pharmacy_printqr_substock');
    Route::post('/pharmacy/item_list/substock/{id}', [PharmacyController::class, 'updateSubStock'])->name('pharmacy_update_substock');

    Route::get('/pharmacy/prescription/{id}/view', [PharmacyController::class, 'viewPrescription'])->name('pharmacy_view_prescription');
    Route::post('/pharmacy/prescription/{id}/update', [PharmacyController::class, 'updatePrescription'])->name('pharmacy_update_prescription');
});

Route::group(['middleware' => ['auth','verified', 'isAccountEnabled', 'canAccessFwri']], function() {
    Route::get('fwri_admin/home', [FwriController::class, 'home'])->name('fwri_home');
    Route::get('fwri_admin/{id}/view', [FwriController::class, 'viewCif'])->name('fwri_view');
    Route::post('fwri_admin/{id}/update', [FwriController::class, 'updateCif'])->name('fwri_update');
    Route::get('fwri_admin/{id}/print', [FwriController::class, 'printCif'])->name('fwri_print');
    Route::get('fwri_admin/report', [FwriController::class, 'report'])->name('fwri_report');
    Route::get('fwri_admin/export', [FwriController::class, 'export'])->name('fwri_export');
});

//QES DIARRHEA
Route::group(['middleware' => ['auth','verified','isAccountEnabled', 'canAccessQes']], function() {
    Route::get('qes', [QesController::class, 'index'])->name('qes_home');
    Route::post('qes/store_main', [QesController::class, 'storeMain'])->name('qes_store_main');
    Route::get('qes/{main_id}/view', [QesController::class, 'viewMain'])->name('qes_view_main');
    Route::get('qes/{main_id}/new_record', [QesController::class, 'newRecord'])->name('qes_new_record');
    Route::post('qes/{main_id}/store_record', [QesController::class, 'storeRecord'])->name('qes_store_record');

    Route::get('qes/{main_id}/report1', [QesController::class, 'report1'])->name('qes_report1');
});

Route::group(['middleware' => ['auth','verified', 'isAccountEnabled']], function() {
    Route::get('/pharmacy/patients/view/{id}/print_card', [PharmacyController::class, 'printPatientCard'])->name('pharmacy_print_patient_card');
});

Route::group(['middleware' => ['auth','verified', 'isAccountEnabled', 'canAccessPharmacy', 'canAccessPharmacyAdminMode']], function() {
    Route::get('/pharmacy/master_item/', [PharmacyController::class, 'masterItemHome'])->name('pharmacy_masteritem_list');
    Route::get('/pharmacy/master_item/view/{id}', [PharmacyController::class, 'viewMasterItem'])->name('pharmacy_view_masteritem');
    Route::post('/pharmacy/master_item/{id}', [PharmacyController::class, 'updateMasterItem'])->name('pharmacy_update_masteritem');

    Route::get('/pharmacy/branches', [PharmacyController::class, 'listBranch'])->name('pharmacy_list_branch');
    Route::post('/pharmacy/branches/add', [PharmacyController::class, 'storeBranch'])->name('pharmacy_store_branch');
    Route::get('/pharmacy/branches/{id}', [PharmacyController::class, 'viewBranch'])->name('pharmacy_view_branch');
    Route::get('/pharmacy/branches/{id}/print_card', [PharmacyController::class, 'printBranchCard'])->name('pharmacy_print_branch_card');
    Route::post('/pharmacy/branches/{id}/new_transaction', [PharmacyController::class, 'newBranchTransaction'])->name('pharmacy_branch_newtransaction');
    Route::post('/pharmacy/branches/{id}', [PharmacyController::class, 'updateBranch'])->name('pharmacy_update_branch');
});

//VAXCERT (WALK IN)
Route::get('/vaxcert', [VaxcertController::class, 'walkinmenu'])->name('vaxcert_walkin');
Route::get('/vaxcert/sendticket', [VaxcertController::class, 'walkin'])->name('vaxcert_walkin_file');
Route::post('/vaxcert/process', [VaxcertController::class, 'walkin_process'])->name('vaxcert_walkin_process');
Route::get('/vaxcert/track', [VaxcertController::class, 'walkin_track'])->name('vaxcert_track');
Route::post('/vaxcert/followup', [VaxcertController::class, 'followUp'])->name('vaxcert_followup');

//PHARMACY (WALK IN)
Route::get('/pharmacy/register/{branch_qr}', [PharmacyController::class, 'walkinpart1'])->name('pharmacy_walkin');
Route::get('/pharmacy/register/{branch_qr}/p2', [PharmacyController::class, 'walkinpart2'])->name('pharmacy_walkin2');
Route::post('/pharmacy/register/{branch_qr}/p3', [PharmacyController::class, 'walkinpart3'])->name('pharmacy_walkin3');
Route::get('/pharmacy/search_card', [PharmacyController::class, 'searchcard'])->name('pharmacy_searchcard');
Route::get('/pharmacy/get_card', [PharmacyController::class, 'globalcard'])->name('pharmacy_getcard');

//ABTC QR
Route::get('/abtc/qr/{qr}', [ABTCWalkInRegistrationController::class, 'qr_process'])->name('abtc_qr_process');

//ABTC SELF-REPORT
Route::get('/abtc/selfreport', [ABTCWalkInRegistrationController::class, 'selfReportIndex'])->name('abtc_selfreport_index');
Route::post('/abtc/selfreport/store', [ABTCWalkInRegistrationController::class, 'selfReportStore'])->name('abtc_selfreport_store');

//SYNDROMIC ONLINE MEDCERT
Route::get('/medcert/verify/{qr}', [SyndromicController::class, 'medcertOnlineVerify'])->name('medcert_online_verify');

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

//FIREWORK RELATED INJURY FWRI
Route::get('fwri/{code}', [FwriController::class, 'index'])->name('fwri_index');
Route::post('fwri/{code}/add', [FwriController::class, 'store'])->name('fwri_store');
Route::get('fwri/{code}/success', [FwriController::class, 'success'])->name('fwri_success');

//Route::get('/vaxcert/import', [VaxcertController::class, 'remoteimport'])->name('vaxcert_import');

//Main landing page
Route::get('/', function () {
    if(auth()->check()) {
        return redirect()->route('home');
    }
    else {
        return view('auth.login');
    }
    
})->name('main');