@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                      <div>Welcome, {{auth()->user()->name}}</div>
                      <div>Date: {{date('m/d/Y (D)')}} | Morbidity Week: {{date('W')}}</div>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                      <div><b>INTEGRATED PIDSR / EDCS-IS MENU</b></div>
                      <div>
                        <a href="{{route('pidsr_notif_index')}}" class="btn btn-primary"><i class="fas fa-bell"></i></i>@if($notif_count != 0)<span class="badge badge-danger ml-1">{{number_format($notif_count)}}</span>@endif</a>
                        @if(auth()->user()->canaccess_covid == 1)
                        <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#changemenu">Change</button>
                        @endif
                      </div>
                  </div>
                </div>
                <div class="card-body">
                    @if(session('msg'))
                    <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                        {{session('msg')}}
                    </div>
                    @endif
                    <button type="button" class="btn btn-success btn-block" data-toggle="modal" data-target="#addCase">Add New Case</button>
                    <hr>
                    <a href="{{route('pidsr_epdrone_home')}}" class="btn btn-primary btn-block">Case Viewer/Checker</a>
                    <a href="{{route('pidsr_weeklymonitoring')}}" class="btn btn-primary btn-block">Weekly Submissions Monitoring</a>
                    <!--<a href="{{route('pidsr_forvalidation_index')}}?year={{date('Y')}}" class="btn btn-primary btn-block">For Validation @if($forverification_count != 0)<span class="badge badge-danger ml-1">{{number_format($forverification_count)}}</span>@endif</a> -->
                    <!--<button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#thresh">Threshold Count</button>-->
                    <hr>
                    @if(auth()->user()->canAccessQes())
                    <a href="{{route('qes_home')}}" class="btn btn-block btn-primary">QES for Diarrhea Cases Module</a>
                    @endif
                    <a href="{{route('pidsr_laboratory_home')}}" class="btn btn-block btn-primary">Lab Results Encoding</a>
                    @if(auth()->user()->canAccessFwri())
                    <a href="{{route('fwri_home')}}" class="btn btn-block btn-primary">Fireworks-Related Injury (FWRI)</a>
                    <hr>
                    @endif
                    <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#report">Report</button>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#snax">sNaX v2</button>
                        </div>
                        <div class="col-md-6">
                            <a href="{{route('edcs_disease_summary_view')}}" class="btn btn-primary btn-block">Weekly Summary of Notifiable Diseases</a>
                        </div>
                    </div>
                    <hr>
                    <!--
                        <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#zipexport">EDCS Importer Tool V2</button>
                    -->
                    <a href="{{route('dengue_clustering_viewer')}}" class="btn btn-block btn-primary">Dengue Clustering Schedule</a>
                    <hr>
                    <a href="{{route('sbs_adminpanel')}}" class="btn btn-block btn-primary"><i class="fas fa-chalkboard mr-2"></i>School-Based Disease Surveillance System</a>
                    <hr>
                    @if(auth()->user()->isGlobalAdmin() && $unlockweeklyreport)
                    <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#dailyexport">EDCS-IS Daily Import</button>
                    @endif
                    @if($unlockweeklyreport || request()->input('override'))
                    <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#export">EDCS-IS Weekly Import Task (every Tuesday)</button>
                    @else
                    <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#dailyexport">EDCS-IS Daily Import</button>
                    @endif
                    @if(in_array('GLOBAL_ADMIN', auth()->user()->getPermissions()))
                    <button type="button" class="btn btn-secondary btn-block" data-toggle="modal" data-target="#settings">Settings</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<form action="{{route('edcs_addcase_check')}}" method="GET">
    <div class="modal fade" id="addCase" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Case</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if(session('modalmsg'))
                    <div class="alert alert-{{session('modalmsgtype')}} text-center" role="alert">
                        {{ session('modalmsg') }}
                    </div>
                    @endif
                    <div class="form-group d-none">
                        <label for="facility_code"><b class="text-danger">*</b>Facility Code</label>
                        <input type="text" class="form-control" name="facility_code" id="facility_code" value="{{old('facility_code')}}" readonly>
                    </div>
                    <div class="form-group">
                      <label for="disease"><b class="text-danger">*</b>Select Case</label>
                      <select class="form-control" name="disease" id="disease" required>
                        <option value="" disabled selected>Choose...</option>
                        @foreach(\App\Http\Controllers\PIDSRController::listReportableDiseasesBackEnd() as $disease)
                        <option value="{{ $disease['value'] }}" {{ (collect(old('disease'))->contains($disease['value'])) ? 'selected' : '' }}>{{ $disease['text'] }}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="form-group">
                        <label for="lname"><b class="text-danger">*</b>Last Name</label>
                        <input type="text" class="form-control" name="lname" id="lname" value="{{old('lname')}}" minlength="2" maxlength="50" placeholder="ex: DELA CRUZ" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" required>
                    </div>
                    <div class="form-group">
                        <label for="fname"><b class="text-danger">*</b>First Name</label>
                        <input type="text" class="form-control" name="fname" id="fname" value="{{old('fname')}}" minlength="2" maxlength="50" placeholder="ex: JUAN" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="mname">Middle Name <i>(If Applicable)</i></label>
                                <input type="text" class="form-control" name="mname" id="mname" value="{{old('mname')}}" minlength="2" maxlength="50" placeholder="ex: SANCHEZ" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="suffix">Suffix <i>(If Applicable)</i></label>
                                <input type="text" class="form-control" name="suffix" id="suffix" value="{{old('suffix')}}" minlength="2" maxlength="3" placeholder="ex: JR, SR, III, IV" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="bdate"><b class="text-danger">*</b>Birthdate</label>
                        <input type="date" class="form-control" name="bdate" id="bdate" value="{{old('bdate')}}" min="1900-01-01" max="{{date('Y-m-d', strtotime('yesterday'))}}" required>
                    </div>
                    <hr>
                    <div class="form-group">
                        <label for="entry_date"><b class="text-danger">*</b>Date Admitted/Seen/Consulted</label>
                        <input type="date" class="form-control" name="entry_date" id="entry_date" value="{{old('entry_date')}}" min="{{date('Y-m-d', strtotime('-1 Year'))}}" max="{{date('Y-m-d')}}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-block">Next</button>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="modal fade" id="changemenu" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Menu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
              <a href="{{route('main')}}" class="btn btn-primary btn-block">COVID-19</a>
              <a href="{{route('vaxcert_home')}}" class="btn btn-primary btn-block">VaxCert Concerns</a>
              <a href="{{route('syndromic_home')}}" class="btn btn-primary btn-block">Syndromic (ITR)</a>
              <hr>
              <a href="{{route('abtc_home')}}" class="btn btn-primary btn-block">Animal Bite (ABTC)</a>
              <a href="{{route('fhsis_home')}}" class="btn btn-primary btn-block">eFHSIS</a>
            </div>
        </div>
    </div>
</div>

<form action="{{route('pidsr_snaxv2')}}" method="GET">
    <div class="modal fade" id="snax" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">sNaX v2</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="disease"><b class="text-danger">*</b>Select Disease</label>
                        <select class="form-control" name="disease" id="disease" required>
                            
                            <optgroup label="EDCS-IS">
                                <option value="Abd">Acute Bloody Diarrhea</option>
                                <option value="Afp">Acute Flaccid Paralysis (AFP)</option>
                                <option value="Ames">Acute Meningitis Encephalitis Syndrome (AMES)</option>
                                <option value="Chikv">Chikungunya</option>
                                <option value="Cholera">Cholera</option>
                                <option value="Dengue" selected>Dengue</option>
                                <option value="Diph">Diptheria</option>
                                <option value="Hepatitis">Hepatitis</option> 
                                <option value="Hfmd">Hand, Foot and Mouth Disease (HFMD)</option>
                                <option value="Influenza">Influenza-like Illness</option>
                                <option value="Leptospirosis">Leptospirosis</option>
                                <option value="Measles">Measles</option>
                                <option value="Meningo">Meningococcal Disease</option>
                                <option value="Nnt">Non-Neonatal Tetanus</option>
                                <option value="Nt">Neonatal Tetanus</option>
                                <option value="Pert">Pertussis</option>
                                <option value="Rabies">Rabies</option>
                                <option value="Rotavirus">RotaVirus</option>
                                <option value="Typhoid">Typhoid and Parathypoid Fever</option>
                            </optgroup>
                            <optgroup label="PIDSR (Old)">
                                <option value="Aes">Acute Encephalitis Syndrome</option>
                                <option value="Ahf">Acute Hemorrhagic-Fever Syndrome</option>
                                <option value="Aefi">Adverse Event Following Immunization (AEFI)</option>
                                <option value="Anthrax">Anthrax</option>
                                <option value="Malaria">Malaria</option>
                                <option value="Meningitis">Meningitis</option>
                                <option value="Psp">Paralytic Shellfish Poisoning</option>
                            </optgroup>
                            <optgroup label="Others">
                                <option value="COVID">COVID-19</option>
                                <option value="ABTC">Animal Bite</option>
                            </optgroup>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="year"><b class="text-danger">*</b>Select Year</label>
                        <select class="form-control" name="year" id="snax_year" required>
                            @foreach(range(date('Y'), 2018) as $y)
                            <option value="{{$y}}">{{$y}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="mweek"><b class="text-danger">*</b>Morbidity Week</label>
                        <input type="number" class="form-control" name="mweek" id="snax_mweek" value="{{(date('W') > 1) ? date('W')-1 : 1}}" min="1" max="53" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-block">Submit</button>
                </div>
            </div>
        </div>
    </div>
</form>

@if(in_array('GLOBAL_ADMIN', auth()->user()->getPermissions()))
<div class="modal fade" id="settings" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Settings</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @if(request()->input('hidden'))
                <form action="{{route('pidsr_reset_sent')}}" method="GET">
                    <div class="card">
                        <div class="card-header"><b>Reset Resending</b></div>
                        <div class="card-body">
                            <div class="form-group">
                              <label for="dtr">Select Date to Reset</label>
                              <input type="date" class="form-control" name="dtr" id="dtr" value="{{date('Y-m-d')}}" required>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary btn-block">Process Reset</button>
                        </div>
                    </div>
                </form>
                @endif
                <form action="{{route('pidsr_import_ftp')}}" method="GET">
                    <div class="card">
                        <div class="card-header"><b>MDB Rebuilder</b></div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="year"><b class="text-danger">*</b>Select Year</label>
                                <select class="form-control" name="year" id="year" required>
                                    @foreach(range(date('Y'), 2018) as $y)
                                    <option value="{{$y}}">{{$y}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary btn-block" name="toggleRebuildMdb" value="1">Submit</button>
                        </div>
                    </div>
                </form>
                <form action="{{route('edcs_manualgenerate_threshold')}}" method="POST">
                    @csrf
                    <div class="card mt-3">
                        <div class="card-header"><b>Case Threshold Generator</b></div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="disease"><b class="text-danger">*</b>Select Disease</label>
                              <select class="form-control" name="disease" id="disease" required>
                                <option value="" disabled {{(is_null(old('disease'))) ? 'selected' : ''}}>Choose...</option>
                                <option value="Abd">Acute Bloody Diarrhea (ABD)</option>
                                <option value="Afp">Acute Flaccid Paralysis (AFP)</option>
                                <option value="Ames">Acute Meningitis Encephalitis (AMES)</option>
                                <option value="Chikv">Chikungunya</option>
                                <option value="Cholera">Cholera</option>
                                <option value="COVID">COVID-19</option>
                                <option value="Dengue">Dengue</option>
                                <option value="Diph">Diphtheria</option>
                                <option value="Hepatitis">Hepatitis</option>
                                <option value="Hfmd">Hand, Foot & Mouth Disease</option>
                                <option value="Influenza">Influenza-like Illness</option>
                                <option value="Leptospirosis">Leptospirosis</option>
                                <option value="Measles">Measles</option>
                                <option value="Meningitis">Meningitis</option>
                                <option value="Meningo">Meningococcal Disease</option>
                                <option value="Nnt">Non-Neonatal Tetanus</option>
                                <option value="Nt">Neonatal Tetanus</option>
                                <option value="Pert">Pertussis</option>
                                <option value="Rabies">Rabies</option>
                                <option value="Rotavirus">Rotavirus</option>
                                <option value="SevereAcuteRespiratoryInfection">Severe Acute Respiratory Infection (SARI)</option>
                                <option value="Typhoid">Typhoid and Paratyphoid Fever</option>
                              </select>
                            </div>
                            <div class="form-group">
                                <label for="year"><b class="text-danger">*</b>Select Year to Generate</label>
                                <select class="form-control" name="year" id="year" required>
                                    @foreach(range(date('Y', strtotime('-1 Year')), 2018) as $y)
                                    <option value="{{$y}}">{{$y}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary btn-block">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

<form action="{{route('pidsr.threshold')}}" method="GET">
    <div class="modal fade" id="thresh" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Threshold</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="sd">Select Disease</label>
                        <select class="form-control" name="sd" id="sd" required>
                            <optgroup label="Category 1 (Immediately Notifiable)">
                                <option value="AFP">Acute Flaccid Paralysis (AFP)</option>
                                <option value="AEFI">Adverse Event Following Immunization (AEFI)</option>
                                <option value="ANTHRAX">Anthrax</option>
                                <option value="HFMD">Hand, Foot and Mouth Disease (HFMD)</option>
                                <option value="MEASLES">Measles</option>
                                <option value="MENINGO">Meningococcal Disease</option>
                                <option value="NT">Neonatal Tetanus</option>
                                <option value="PSP">Paralytic Shellfish Poisoning</option>
                                <option value="RABIES">Rabies</option>
                            </optgroup>
                            <optgroup label="Category 2 (Weekly Notifiable)">
                                <option value="ABD">Acute Bloody Diarrhea</option>
                                <option value="AES">Acute Encephalitis Syndrome</option>
                                <option value="AHF">Acute Hemorrhagic-Fever Syndrome</option>
                                <option value="HEPATITIS">Acute Viral Hepatitis</option>
                                <option value="AMES">AMES</option>
                                <option value="MENINGITIS">Bacterial Meningitis</option>
                                <option value="ChikV">Chikungunya</option>
                                <option value="CHOLERA">Cholera</option>
                                <option value="DENGUE">Dengue</option>
                                <option value="DIPH">Diptheria</option>
                                <option value="INFLUENZA">Influenza-like Illness</option>
                                <option value="LEPTOSPIROSIS">Leptospirosis</option>
                                <option value="MALARIA">Malaria</option>
                                <option value="NNT">Non-Neonatal Tetanus</option>
                                <option value="PERT">Pertussis</option>
                                <option value="ROTAVIRUS">RotaVirus</option>
                                <option value="TYPHOID">Typhoid and Parathypoid Fever</option>
                            </optgroup>
                            <optgroup label="Others">
                                <option value="MONKEYPOX">Monkeypox</option>
                            </optgroup>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="year">Select Year</label>
                        <select class="form-control" name="year" id="year" required>
                            @foreach(range(date('Y'), 2018) as $y)
                            <option value="{{$y}}">{{$y}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>
</form>

<form action="{{route('pidsr_dailymerge_start')}}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="modal fade" id="dailyexport" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">EDCS-IS Daily Import of Cases</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                      <label for="excel_file"><b class="text-danger">*</b>Select Excel File (.XLSX)</label>
                      <input type="file" class="form-control-file" name="excel_file" id="excel_file" accept=".xlsx" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-block">Start Merging</button>
                </div>
            </div>
        </div>
    </div>
</form>

<form action="{{ route('pidsr_initializemwcalendar') }}" method="POST">
    @csrf
    <div class="modal fade" id="initializeMwCalendar" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Initialize Morbidity Week</h5>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="year"><b class="text-danger">*</b>Year</label>
                        <input type="number" class="form-control" name="year" id="year" value="{{ date('Y') }}" {{ (!request()->input('trigger_mwcalendar')) ? 'readonly' : '' }} max="{{ date('Y') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="start_date"><b class="text-danger">*</b>Start Date</label>
                        <input type="date" class="form-control" name="start_date" id="start_date" value="{{ date('Y') }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-block">Submit</button>
                </div>
            </div>
        </div>
    </div>
</form>

<!--
<form action="{{route('edcs_importv2')}}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="modal fade" id="zipexport" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">EDCS-IS Import Tool v2</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                      <label for="zip_file"><b class="text-danger">*</b>Select .ZIP File downloaded from EDCS-IS</label>
                      <input type="file" class="form-control-file" name="zip_file" id="zip_file" accept=".zip" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-block" name="submit" value="daily">Start Merging</button>
                </div>
            </div>
        </div>
    </div>
</form>
-->

@if($unlockweeklyreport || request()->input('override'))
<div class="modal fade" id="export" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">EDCS-IS Weekly Import Task</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body text-center">
                <p><b class="text-primary">Note:</b> Steps are recommended to process every Tuesday, before 11AM.</p>
            </div>
            <!--
                <div class="modal-footer">
                    <p class="text-center"><b>Step 1</b> - i-Merge ang mga MDB Feedbacks galing sa RESU/PESU + Hospitals gamit ng OLD PIDSR Program.</p>
                    <p class="text-center"><b>Step 2</b> - gamitin ang MDB Importer tool na nasa [C:\cesu_tools\MDBToExcelv2] na Folder. Pindutin ang [Startv2.bat] at antayin matapos ang process.</p>
                    <a href="{{route('pidsr.import')}}?m=1" class="btn btn-primary btn-block"><b>Step 3</b> - Simulan ang pag-import ng Current MDB papunta sa System</a>
                    <p class="text-center"><b>Step 4</b> - gamitin ang EDCS Excel Importer tool na nasa [C:\cesu_tools\EDCS_IMPORTER] at piliin ang Excel File (XLSX) file na galing sa RESU/PESU.</p>
                    <a href="{{route('pidsr_import_edcs')}}" class="btn btn-primary btn-block"><b>Step 5</b> - Simulan ang pag-import ng EDCS Feedbacks papunta sa System</a>
                    <a href="{{route('pidsr_import_ftp')}}" class="btn btn-primary btn-block"><b>Step 6</b> - i-Export ang mga Kaso papunta sa FTP Server</a>
                    <p class="text-center"><b>Step 7</b> - gamitin ang PIDSR & EDCS Submitter Tool na nasa [C:\cesu_tools\EDCS_SUBMITTER], pindutin ang Start.ps1 at antayin matapos ang Powershell process.</p>
                    <a href="{{route('pidsr.sendmail')}}" class="btn btn-primary btn-block"><b>Step 8</b> - i-send ang Email Report</a>
                    <p class="text-center"><b>Step 9</b> - Submit MW({{date('W', strtotime('-1 Week'))}}) Report, make email message to PESU and RESU Email and attach <b>1.</b> PIDSR weekly Report PDF, <b>2.</b> ZIP File from the Submitter Tool, <b>3.</b> SnaX PDF</p>
                </div>
            -->
            <div class="modal-footer">
                <p><b>Step 1</b> - Sa EDCS-IS Website, pumunta sa <img src="{{asset('assets/images/epidemic_prone.jpg')}}" style="width: 150px;"> at I-filter ang Result sa "Current Address". Piliin ang mga kasong may bilang na lalabas.</p>
                <p><b>Step 2</b> - Pindutin ang Export icon <img src="{{asset('assets/images/export.png')}}" style="width: 30px;"> at i-save bilang CSV. Ulitin ito sa iba pang mga kaso.</p>
                <p><b>Step 3</b> - Pumunta sa <img src="{{asset('assets/images/lab_data.jpg')}}" style="width: 150px;"> at gamitin din ang Export icon <img src="{{asset('assets/images/export.png')}}" style="width: 30px;">, i-save din ito bilang CSV.</p>
                <p><b>Step 4</b> - Gumawa ng bagong Excel file (.XLSX) at ilagay ang sheet ng mga na-download na CSV na naaayon sa kanilang sheet names.</p>
                <form action="{{route('pidsr_mergev2_start')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <p><b>Step 5</b> - Select the consolidated Excel file and start the Merging Process.</p>
                    <div class="form-group">
                      <input type="file" class="form-control-file" name="excel_file" id="excel_file" accept=".xlsx" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block" onclick="return confirm('Please double check if you selected the correct file. After processing, automated email will be sent at cesu.gentrias@gmail.com. Click OK to Confirm.')">Upload and Start the Merge</button>
                    <small>Antayin dumating ang Automated Email bago mag-proceed sa next step.</small>
                    <hr>
                </form>
                <div>
                    <p><b>Step 6</b> - i-submit ang Report gamit ang Email</p>
                    <ul>
                        <li>Subject: CESU General Trias - MW({{date('W', strtotime('-1 Week'))}}) - Year {{date('Y', strtotime('-1 Week'))}}</li>
                        <li>To: pesucavite@gmail.com; resu4a.edcs@gmail.com</li>
                        <li>Attach: EDCS Summary Report .XLSX <i>(galing sa Automated Mail)</i>, sNaX Dengue PDF</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<form action="{{route('pidsr.report')}}" method="GET">
    <div class="modal fade" id="report" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Generate Report</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="year">Select Year</label>
                        <select class="form-control" name="year" id="year" required>
                          @foreach(range(date('Y'), 2020) as $y)
                              <option value="{{$y}}">{{$y}}</option>
                          @endforeach
                        </select>
                    </div>
                      <div class="form-group">
                        <label for="rtype">Select Type</label>
                        <select class="form-control" name="rtype" id="rtype" required>
                          <option value="" disabled selected>Choose...</option>
                          <option value="YEARLY">Yearly</option>
                          <option value="QUARTERLY">Quarterly</option>
                          <option value="MONTHLY">Monthly</option>
                          <option value="WEEKLY">Weekly</option>
                        </select>
                      </div>
                      <div class="form-group d-none" id="squarter">
                        <label for="quarter">Select Quarter</label>
                        <select class="form-control" name="quarter" id="quarter">
                          <option value="1">1st Quarter</option>
                          <option value="2">2nd Quarter</option>
                          <option value="3">3rd Quarter</option>
                          <option value="4">4th Quarter</option>
                        </select>
                      </div>
                      <div class="form-group d-none" id="smonth">
                        <label for="month">Select Month</label>
                        <select class="form-control" name="month" id="month">
                          <option value="1">January</option>
                          <option value="2">February</option>
                          <option value="3">March</option>
                          <option value="4">April</option>
                          <option value="5">May</option>
                          <option value="6">June</option>
                          <option value="7">July</option>
                          <option value="8">August</option>
                          <option value="9">September</option>
                          <option value="10">October</option>
                          <option value="11">November</option>
                          <option value="12">December</option>
                        </select>
                      </div>
                      <div class="form-group d-none" id="sweek">
                        <label for="week">Select Week</label>
                        <input type="number" min="1" max="53" class="form-control" name="week" id="week" value="{{date('W')}}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-block" name="submit" value="report1">Report 1 (Per Barangay)</button>
                    <button type="submit" class="btn btn-primary btn-block">Report 2</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    @if(!$init_mw || request()->input('trigger_mwcalendar'))
    $('#initializeMwCalendar').modal({backdrop: 'static', keyboard: false});
    $('#initializeMwCalendar').modal('show');
    @endif
</script>

@if(session('openEncodeModal'))
<script>
    $(document).ready(function(){
        $('#addCase').modal('show');
    });
</script>
@endif

@if(request()->input('encode_again'))
<script>
    let disease = "{{request()->input('encode_again')}}";
    $(document).ready(function(){
        $('#addCase').modal('show');
        $('#disease').val(disease);
    });
</script>
@endif

<script>
    $('#rtype').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'YEARLY') {
            $('#squarter').addClass('d-none');
            $('#smonth').addClass('d-none');
            $('#sweek').addClass('d-none');

            $('#quarter').prop('required', false);
            $('#month').prop('required', false);
            $('#week').prop('required', false);
        }
        else if($(this).val() == 'QUARTERLY') {
            $('#squarter').removeClass('d-none');
            $('#smonth').addClass('d-none');
            $('#sweek').addClass('d-none');

            $('#quarter').prop('required', true);
            $('#month').prop('required', false);
            $('#week').prop('required', false);
        }
        else if($(this).val() == 'MONTHLY') {
            $('#squarter').addClass('d-none');
            $('#smonth').removeClass('d-none');
            $('#sweek').addClass('d-none');

            $('#quarter').prop('required', false);
            $('#month').prop('required', true);
            $('#week').prop('required', false);
        }
        else if($(this).val() == 'WEEKLY') {
            $('#squarter').addClass('d-none');
            $('#smonth').addClass('d-none');
            $('#sweek').removeClass('d-none');

            $('#quarter').prop('required', false);
            $('#month').prop('required', false);
            $('#week').prop('required', true);
        }
    }).trigger('change');

    $('#snax_year').change(function (e) { 
        e.preventDefault();
        if($(this).val() == {{date("Y")}}) {
            $('#snax_mweek').attr('max', {{(int)date('W')}});
        }
        else {
            $('#snax_mweek').attr('max', 52);
        }
    }).trigger('change');
</script>
@endsection