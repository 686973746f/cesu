@extends('layouts.app')

@section('content')
<form action="{{route('edcs_addcase_store', 'DENGUE')}}" method="POST">
    @csrf
    <div class="container">
        <div class="card">
            <div class="card-header">
                <b>
                    <div>{{$f->facility_name}}</div>
                    <div>Report Dengue Case</div>
                </b>
            </div>
            <div class="card-body">
                <div class="alert alert-info" role="alert">
                    <b>Note:</b> All fields marked with <b class="text-danger">*</b> are required. By filling out this form, the patient agrees to the collection of their data in accordance to the Data Privacy Act of 2012 and Republic Act 11332.
                </div>
                @if(!auth()->check())
                <div class="form-group d-none">
                    <label for="facility_code">Facility Code</label>
                    <input type="text" class="form-control" name="facility_code" id="facility_code" value="{{request()->input('facility_code')}}" readonly>
                  </div>
                @else
                <div class="form-group">
                    <label for="facility_list"><b class="text-danger">*</b>Override Facility</label>
                    <select class="form-control" name="facility_list" id="facility_list" required>
                        @foreach($facility_list as $f)
                        <option value="{{$f->id}}" {{(old('facility_list', auth()->user()->itr_facility_id) == $f->id) ? 'selected' : ''}}>{{$f->facility_name}}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="entry_date"><b class="text-danger">*</b>Date Admitted/Seen/Consulted</label>
                            <input type="date" class="form-control" name="entry_date" id="entry_date" value="{{request()->input('entry_date')}}" min="{{date('Y-m-d', strtotime('-1 Year'))}}" max="{{date('Y-m-d')}}" tabindex="-1" readonly>
                        </div>
                        <div class="form-group">
                            <label for="PatientNumber">Patient No.</label>
                            <input type="text" class="form-control" name="PatientNumber" id="PatientNumber" value="{{old('PatientNumber')}}" style="text-transform: uppercase;">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sys_interviewer_name"><b class="text-danger">*</b>Name of Reporter/Interviewer</label>
                            <input type="text" class="form-control" name="sys_interviewer_name" id="sys_interviewer_name" value="{{old('sys_interviewer_name', $f->edcs_defaultreporter_name)}}" style="text-transform: uppercase;" required>
                        </div>
                        <div class="form-group">
                            <label for="sys_interviewer_contactno"><b class="text-danger">*</b>Contact No. of Reporter/Interviewer</label>
                            <input type="text" class="form-control" id="sys_interviewer_contactno" name="sys_interviewer_contactno" value="{{old('sys_interviewer_contactno', $f->edcs_defaultreporter_contactno)}}" pattern="[0-9]{11}" placeholder="09*********" required>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="lname"><b class="text-danger">*</b>Last Name</label>
                            <input type="text" class="form-control" name="lname" id="lname" value="{{request()->input('lname')}}" minlength="2" maxlength="50" placeholder="ex: DELA CRUZ" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" required readonly tabindex="-1">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fname"><b class="text-danger">*</b>First Name</label>
                            <input type="text" class="form-control" name="fname" id="fname" value="{{request()->input('fname')}}" minlength="2" maxlength="50" placeholder="ex: JUAN" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" required readonly tabindex="-1">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="mname">Middle Name</label>
                            <input type="text" class="form-control" name="mname" id="mname" value="{{request()->input('mname')}}" minlength="2" maxlength="50" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" readonly tabindex="-1">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="suffix">Suffix</label>
                            <input type="text" class="form-control" name="suffix" id="suffix" value="{{request()->input('suffix')}}" minlength="2" maxlength="3" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" readonly tabindex="-1">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="bdate"><b class="text-danger">*</b>Birthdate</label>
                            <input type="date" class="form-control" name="bdate" id="bdate" value="{{request()->input('bdate')}}" min="1900-01-01" max="{{date('Y-m-d', strtotime('yesterday'))}}" required readonly tabindex="-1">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="sex"><span class="text-danger font-weight-bold">*</span>Gender</label>
                            <select class="form-control" name="sex" id="sex" required>
                                <option value="" disabled {{(is_null(old('gender'))) ? 'selected' : ''}}>Choose...</option>
                                <option value="M" {{(old('gender') == 'M') ? 'selected' : ''}}>Male</option>
                                <option value="F" {{(old('gender') == 'F') ? 'selected' : ''}}>Female</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="contact_number"><b class="text-danger">*</b>Contact Number</label>
                            <input type="text" class="form-control" id="contact_number" name="contact_number" value="{{old('contact_number')}}" pattern="[0-9]{11}" placeholder="09*********" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group" id="brgyDiv">
                            <label for="brgy_id" class="form-label"><b class="text-danger">*</b>Barangay</label>
                            <select class="form-control" name="brgy_id" id="brgy_id" required>
                                <option value="" disabled {{(is_null(old('brgy_id'))) ? 'selected' : ''}}>Choose...</option>
                                @foreach ($brgy_list as $b)
                                    <option value="{{$b->id}}">{{$b->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="Streetpurok" class="form-label"><b class="text-danger">*</b>House/Lot No./Street/Purok/Subdivision</label>
                            <input type="text" class="form-control" id="Streetpurok" name="Streetpurok" style="text-transform: uppercase;" value="{{old('Streetpurok')}}" placeholder="ex. S1 B2 L3 PHASE 4 SUBDIVISION HOMES" required>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="sys_occupationtype"><span class="text-danger font-weight-bold">*</span>Has Occupation/Student?</label>
                    <select class="form-control" name="sys_occupationtype" id="sys_occupationtype" required>
                        <option value="" disabled {{(is_null(old('sys_occupationtype'))) ? 'selected' : ''}}>Choose...</option>
                        <option value="WORKING" {{(old('sys_occupationtype') == 'WORKING') ? 'selected' : ''}}>Has Occupation/Work</option>
                        <option value="STUDENT" {{(old('sys_occupationtype') == 'STUDENT') ? 'selected' : ''}}>Student</option>
                        <option value="NONE" {{(old('sys_occupationtype') == 'NONE') ? 'selected' : ''}}>Not Applicable (N/A)</option>
                    </select>
                </div>
                <div class="row d-none" id="hasOccupation">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sys_businessorschool_name" class="form-label"><b class="text-danger">*</b><span id="occupationNameText"></span></label>
                            <input type="text" class="form-control" id="sys_businessorschool_name" name="sys_businessorschool_name" style="text-transform: uppercase;" value="{{old('sys_businessorschool_name')}}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sys_businessorschool_address" class="form-label"><b class="text-danger">*</b><span id="occupationAddressText"></span></label>
                            <input type="text" class="form-control" id="sys_businessorschool_address" name="sys_businessorschool_address" style="text-transform: uppercase;" value="{{old('sys_businessorschool_address')}}" pattern="(^[a-zA-Z0-9 ]+$)+">
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="sys_fever" name="sys_fever" required>
                            <label class="form-check-label" for="sys_fever">
                              Fever (Lagnat)
                            </label>
                            <div id="fever_div" class="d-none">
                                <div class="form-group">
                                    <label for="DOnset"><b class="text-danger">*</b>Fever Onset Date (Kailan nagkaroon ng lagnat)</label>
                                    <input type="date" class="form-control" name="DOnset" id="DOnset" value="{{old('DOnset')}}" min="{{date('Y-m-d', strtotime('-1 Year'))}}" max="{{date('Y-m-d', strtotime('yesterday'))}}" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="sys_headache" name="sys_headache">
                            <label class="form-check-label" for="sys_headache">
                              Headache (Masakit ang ulo)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="sys_bodymalaise" name="sys_bodymalaise">
                            <label class="form-check-label" for="sys_bodymalaise">
                              Body Malaise (Nanghihina ang katawan)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="sys_musclepain" name="sys_musclepain">
                            <label class="form-check-label" for="sys_musclepain">
                              Muscle Pain (Masakit ang kalamnan)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="sys_" name="sys_jointpain">
                            <label class="form-check-label" for="sys_jointpain">
                              Joint Pain (Masakit ang kasukasuan)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="sys_jointswelling" name="sys_jointswelling">
                            <label class="form-check-label" for="sys_jointswelling">
                              Joint Swelling (Namamaga ang kasukasuan)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="sys_retropain" name="sys_retropain">
                            <label class="form-check-label" for="sys_retropain">
                              Retro-orbital Pain (Masakit ang likod ng mata)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="sys_anorexia" name="sys_anorexia">
                            <label class="form-check-label" for="sys_anorexia">
                              Anorexia (Walang gana kumain)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="sys_nausea" name="sys_nausea">
                            <label class="form-check-label" for="sys_nausea">
                              Nausea (Nahihilo/Naduduwal)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="sys_vomiting" name="sys_vomiting">
                            <label class="form-check-label" for="sys_vomiting">
                              Vomiting (Nagsusuka)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="sys_diarrhea" name="sys_diarrhea">
                            <label class="form-check-label" for="sys_diarrhea">
                              Diarrhea (Pagdurumi)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="sys_flushedskin" name="sys_flushedskin">
                            <label class="form-check-label" for="sys_flushedskin">
                              Flushed Skin (Namumula ang balat)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="sys_maculopapularrash" name="sys_maculopapularrash">
                            <label class="form-check-label" for="sys_maculopapularrash">
                              Rash (May batik at butlig sa balat)
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="sys_abdominalpain" name="sys_abdominalpain">
                            <label class="form-check-label" for="sys_abdominalpain">
                              Abdominal Pain or Tenderness (Masakit ang Tiyan)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="sys_persistent_vomiting" name="sys_persistent_vomiting">
                            <label class="form-check-label" for="sys_persistent_vomiting">
                              Persistent Vomiting (Patuloy na pagsusuka)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="sys_fluid_accumulation" name="sys_fluid_accumulation">
                            <label class="form-check-label" for="sys_fluid_accumulation">
                              Clinical Signs of Fluid Accumulation
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="sys_petechiae" name="sys_petechiae">
                            <label class="form-check-label" for="sys_petechiae">
                              Petechiae (May pulang batik sa balat)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="sys_echhymosis" name="sys_echhymosis">
                            <label class="form-check-label" for="sys_echhymosis">
                              Echhymosis (May mga pasa sa katawan)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="sys_gumbleeding" name="sys_gumbleeding">
                            <label class="form-check-label" for="sys_gumbleeding">
                              Gum Bleeding (Nagdudugo ang gilagid)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="sys_gibleeding" name="sys_gibleeding">
                            <label class="form-check-label" for="sys_gibleeding">
                                Gastrointestinal Bleeding (Pagdurugo ng tiyan o dumi)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="sys_nosebleeding" name="sys_nosebleeding">
                            <label class="form-check-label" for="sys_nosebleeding">
                              Nose Bleeding (Pagdurugo ng Ilong)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="sys_lethargy_restlessness" name="sys_lethargy_restlessness">
                            <label class="form-check-label" for="sys_lethargy_restlessness">
                              Lethargy/Restlessness (Pagkabalisa)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="sys_hepatomegaly" name="sys_hepatomegaly">
                            <label class="form-check-label" for="sys_hepatomegaly">
                              Hepatomegaly (Paglaki/pamamaga ng Atay)
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="sys_hepatomegaly" name="sys_hepatomegaly">
                            <label class="form-check-label" for="sys_lymphadenopathy">
                              Lymphadenopathy (Pamamaga ng Lymph Node)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="sys_hepatomegaly" name="sys_hepatomegaly">
                            <label class="form-check-label" for="sys_leucopenia">
                              Leucopenia (Mababa ang White Blood Cells)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="sys_hepatomegaly" name="sys_hepatomegaly">
                            <label class="form-check-label" for="sys_thrombocytopenia">
                              Thrombocytopenia (Mababa ang Platelet)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="sys_hepatomegaly" name="sys_hepatomegaly">
                            <label class="form-check-label" for="sys_hemaconcentration">
                              Haemaconcentration (Pagkapal ng Dugo)
                            </label>
                        </div>
                    </div>
                </div>

                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="is_ns1positive"><span class="text-danger font-weight-bold">*</span>Is Dengue NS1 Positive?</label>
                            <select class="form-control" name="is_ns1positive" id="is_ns1positive" required>
                                <option value="" disabled {{(is_null(old('is_ns1positive'))) ? 'selected' : ''}}>Choose...</option>
                                <option value="Y" {{(old('is_ns1positive') == 'Y') ? 'selected' : ''}}>Yes</option>
                                <option value="N" {{(old('is_ns1positive') == 'N') ? 'selected' : ''}}>No</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="is_igmpositive"><span class="text-danger font-weight-bold">*</span>Is Dengue IgM Positive?</label>
                            <select class="form-control" name="is_igmpositive" id="is_igmpositive" required>
                                <option value="" disabled {{(is_null(old('is_igmpositive'))) ? 'selected' : ''}}>Choose...</option>
                                <option value="Y" {{(old('is_igmpositive') == 'Y') ? 'selected' : ''}}>Yes</option>
                                <option value="N" {{(old('is_igmpositive') == 'N') ? 'selected' : ''}}>No</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="Admitted"><span class="text-danger font-weight-bold">*</span>Hospitalized/Admitted?</label>
                    <select class="form-control" name="Admitted" id="Admitted" required>
                        <option value="" disabled {{(is_null(old('Admitted'))) ? 'selected' : ''}}>Choose...</option>
                        <option value="Y" {{(old('Admitted') == 'Y') ? 'selected' : ''}}>Yes</option>
                        <option value="N" {{(old('Admitted') == 'N') ? 'selected' : ''}}>No</option>
                    </select>
                </div>
                <div id="hospitalizedDiv" class="d-none">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="sys_hospitalized_name"><b class="text-danger">*</b>Name of Hospital</label>
                                <input type="text" class="form-control" name="sys_hospitalized_name" id="sys_hospitalized_name" style="text-transform: uppercase;">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="sys_hospitalized_datestart"><b class="text-danger">*</b>Date Admitted</label>
                                <input type="date" class="form-control" name="sys_hospitalized_datestart" id="sys_hospitalized_datestart" value="{{old('sys_hospitalized_datestart')}}" min="{{date('Y-m-d', strtotime('-1 Year'))}}" max="{{date('Y-m-d')}}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="sys_hospitalized_dateend"><b class="text-danger">*</b>Date Discharged</label>
                                <input type="date" class="form-control" name="sys_hospitalized_dateend" id="sys_hospitalized_dateend" value="{{old('sys_hospitalized_dateend')}}" min="{{date('Y-m-d', strtotime('-1 Year'))}}" max="{{date('Y-m-d')}}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sys_medication_taken">Medication Taken</label>
                            <textarea class="form-control" name="sys_medication_taken" id="sys_medication_taken" rows="3">{{old('sys_medication_taken')}}</textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sys_outcome"><span class="text-danger font-weight-bold">*</span>Outcome</label>
                            <select class="form-control" name="sys_outcome" id="sys_outcome" required>
                                <option value="" disabled {{(is_null(old('sys_outcome'))) ? 'selected' : ''}}>Choose...</option>
                                <option value="ALIVE" {{(old('sys_outcome') == 'ALIVE') ? 'selected' : ''}}>Alive</option>
                                <option value="DIED" {{(old('sys_outcome') == 'DIED') ? 'selected' : ''}}>Died</option>
                            </select>
                        </div>
                        <div id="outcomeDiv" class="d-none">
                            <div class="form-group">
                                <label for="sys_outcome_date"><b class="text-danger">*</b><span id="outcomeText"></span></label>
                                <input type="date" class="form-control" name="sys_outcome_date" id="sys_outcome_date" value="{{old('sys_outcome_date')}}" min="{{date('Y-m-d', strtotime('-1 Year'))}}" max="{{date('Y-m-d')}}">
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sys_historytravel2weeks"><span class="text-danger font-weight-bold">*</span>History of Travel the Past 2 Weeks?</label>
                            <select class="form-control" name="sys_historytravel2weeks" id="sys_historytravel2weeks" required>
                                <option value="" disabled {{(is_null(old('sys_historytravel2weeks'))) ? 'selected' : ''}}>Choose...</option>
                                <option value="Y" {{(old('sys_historytravel2weeks') == 'Y') ? 'selected' : ''}}>Yes</option>
                                <option value="N" {{(old('sys_historytravel2weeks') == 'N') ? 'selected' : ''}}>No</option>
                            </select>
                        </div>
                        <div id="historyTravelDiv" class="d-none">
                            <div class="form-group">
                                <label for="sys_historytravel2weeks_where"><b class="text-danger">*</b>Where?</label>
                                <input type="text" class="form-control" name="sys_historytravel2weeks_where" id="sys_historytravel2weeks_where" style="text-transform: uppercase;">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sys_exposedtosimilarcontact"><span class="text-danger font-weight-bold">*</span>Exposed to Person of Similar Manifestation?</label>
                            <select class="form-control" name="sys_exposedtosimilarcontact" id="sys_exposedtosimilarcontact" required>
                                <option value="" disabled {{(is_null(old('sys_exposedtosimilarcontact'))) ? 'selected' : ''}}>Choose...</option>
                                <option value="Y" {{(old('sys_exposedtosimilarcontact') == 'Y') ? 'selected' : ''}}>Yes</option>
                                <option value="N" {{(old('sys_exposedtosimilarcontact') == 'N') ? 'selected' : ''}}>No</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">List Name and Address of Close Contacts (Mga nakasalamuha sa bahay/school/trabaho, Mga kasama sa bahay, etc.)</div>
                    <div class="card-body">
                        <div id="toCloneDiv">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="sys_contactnames"><b class="text-danger">*</b>Name</label>
                                        <input type="text" class="form-control" name="sys_contactnames[]" style="text-transform: uppercase;">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="sys_contactaddress"><b class="text-danger">*</b>Address</label>
                                        <input type="text" class="form-control" name="sys_contactaddress[]" style="text-transform: uppercase;">
                                    </div>
                                </div>
                                <div class="col-md-2 text-center d-flex align-items-center justify-content-center">
                                    <button type="button" class="btn btn-danger removeContact">Remove</button>
                                </div>
                            </div>
                        </div>
                        <div id="cloneDivsHere">

                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="button" name="addMoreContact" id="addMoreContact" class="btn btn-primary btn-block">Add More</button>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group" id="s1_div">
                            <label for="sys_animal_presence_list">Select Presence of animal/vectors around the house or within the neighborhood 50 meters from the house of Patient</label>
                            <select class="form-control" name="sys_animal_presence_list[]" id="sys_animal_presence_list" multiple>
                                <option value="CHICKEN">Chicken</option>
                                <option value="MOSQUITO">Mosquito</option>
                                <option value="RATS">Rats</option>
                                <option value="BIRDS">Birds</option>
                                <option value="DOG">Dog</option>
                                <option value="CAT">Cat</option>
                                <option value="FLIES">Flies</option>
                                <option value="OTHER FORMS OF BIRDS">Other forms of birds (Specify)</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" id="s2_div">
                            <label for="sys_water_presence_inside_list">Select Presence of water containers INSIDE the house of Patient</label>
                            <select class="form-control" name="sys_water_presence_inside_list[]" id="sys_water_presence_inside_list" multiple>
                                <option value="WATER STORAGE CONTAINERS">Water Storage Containers</option>
                                <option value="FLOWER VASE">Flower Vase</option>
                                <option value="OTHERS">Other (Specify)</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" id="s3_div">
                            <label for="sys_water_presence_outside_list">Select Presence of water containers OUTSIDE the house of the Patient or within 10 meters within the neighborhood</label>
                            <select class="form-control" name="sys_water_presence_outside_list[]" id="sys_water_presence_outside_list" multiple>
                                <option value="TIN CANS">Tin Cans</option>
                                <option value="LAGOONS">Lagoons</option>
                                <option value="DRUMS">Drums</option>
                                <option value="CANALS">Canals</option>
                                <option value="WATER JARS">Water Jars</option>
                                <option value="COCONUT SHELLS/HUSKS">Coconut Shells/Husks</option>
                                <option value="USED TIRES">Used Tires</option>
                                <option value="OTHERS">Others (Specify)</option>
                            </select>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="form-group">
                  <label for="system_remarks">Remarks</label>
                  <textarea class="form-control" name="system_remarks" id="system_remarks" rows="3">{{old('system_remarks')}}</textarea>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-success btn-block" id="submitBtn">Submit (CTRL + S)</button>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).bind('keydown', function(e) {
		if(e.ctrlKey && (e.which == 83)) {
			e.preventDefault();
			$('#submitBtn').trigger('click');
			$('#submitBtn').prop('disabled', true);
			setTimeout(function() {
				$('#submitBtn').prop('disabled', false);
			}, 2000);
			return false;
		}
	});

    $('#sys_animal_presence_list').select2({
        theme: 'bootstrap',
    });

    $('#sys_water_presence_inside_list').select2({
        theme: 'bootstrap',
    });

    $('#sys_water_presence_outside_list').select2({
        theme: 'bootstrap',
    });

    $(document).ready(function() {
        $('#addMoreContact').click(function() {
            var clonedDiv = $('#toCloneDiv').clone().removeAttr('id');
            clonedDiv.find('input').attr('required', true);
            var wrapperDiv = $('<div class="clonedWrapper"></div>').append(clonedDiv);
            wrapperDiv.find('.removeContact').click(function() {
                $(this).closest('.clonedWrapper').remove();
            });
            $('#cloneDivsHere').append(wrapperDiv);
        });

        $(document).on('click', '.removeContact', function() {
            $(this).closest('.clonedWrapper').remove();
        });
    });

    $('#sys_occupationtype').change(function (e) { 
        e.preventDefault();

        if($(this).val() == 'WORKING') {
            $('#hasOccupation').removeClass('d-none');
            $('#occupationNameText').text('Name of Workplace/Business');
            $('#occupationAddressText').text('Workplace/Business Address');

            $('#sys_businessorschool_name').prop('required', true);
            $('#sys_businessorschool_address').prop('required', true);
        }
        else if($(this).val() == 'STUDENT') {
            $('#hasOccupation').removeClass('d-none');
            $('#occupationNameText').text('Name of School');
            $('#occupationAddressText').text('School Address');

            $('#sys_businessorschool_name').prop('required', true);
            $('#sys_businessorschool_address').prop('required', true);
        }
        else {
            $('#hasOccupation').addClass('d-none');
            $('#sys_businessorschool_name').prop('required', false);
            $('#sys_businessorschool_address').prop('required', false);
        }
    }).trigger('change');
    
    $('#Admitted').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'Y') {
            $('#hospitalizedDiv').removeClass('d-none');
            $('#sys_hospitalized_name').prop('required', true);
            $('#sys_hospitalized_datestart').prop('required', true);
            $('#sys_hospitalized_dateend').prop('required', true);
        }
        else {
            $('#hospitalizedDiv').addClass('d-none');
            $('#sys_hospitalized_name').prop('required', false);
            $('#sys_hospitalized_datestart').prop('required', false);
            $('#sys_hospitalized_dateend').prop('required', false);
        }
    }).trigger('change');

    $('#sys_outcome').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'ALIVE') {
            $('#outcomeDiv').addClass('d-none');
            $('#sys_outcome_date').prop('required', false);
        }
        else if($(this).val() == 'RECOVERED') {
            $('#outcomeText').text('Date Recovered');
            $('#outcomeDiv').removeClass('d-none');
            $('#sys_outcome_date').prop('required', true);
        }
        else if($(this).val() == 'NOT IMPROVING') {
            $('#outcomeText').text('Outcome Date');
            $('#outcomeDiv').removeClass('d-none');
            $('#sys_outcome_date').prop('required', true);
        }
        else if($(this).val() == 'DIED') {
            $('#outcomeText').text('Date Died');
            $('#outcomeDiv').removeClass('d-none');
            $('#sys_outcome_date').prop('required', true);
        }
        else {
            $('#outcomeDiv').addClass('d-none');
            $('#sys_outcome_date').prop('required', false);
        }
    }).trigger('change');

    $('#sys_historytravel2weeks').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'Y') {
            $('#historyTravelDiv').removeClass('d-none');
            $('sys_historytravel2weeks_where').prop('required', true);
        }
        else {
            $('#historyTravelDiv').addClass('d-none');
            $('sys_historytravel2weeks_where').prop('required', false);
        }
    }).trigger('change');

    $('#sys_fever').change(function (e) { 
        e.preventDefault();
        if ($(this).is(':checked')) {
            $('#fever_div').removeClass('d-none');
            $('#DOnset').prop('required', true);
        } else {
            $('#fever_div').addClass('d-none');
            $('#DOnset').prop('required', true);
        }
    }).trigger('change');
</script>
@endsection