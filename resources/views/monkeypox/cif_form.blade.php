@extends('layouts.app')

@section('content')
@if($c->exists)
<!--Edit Page-->
<form action="{{route('mp.updatecif', ['mk' => $c->id])}}" method="POST">
    @php
    $morbidity_month = $c->morbidity_month;
    $date_reported = $c->date_reported;
    $epid_number = $c->epid_number;

    $dru_name = $c->dru_name;
    $dru_region = $c->dru_region;
    $dru_province = $c->dru_province;
    $dru_muncity = $c->dru_muncity;
    $dru_street = $c->dru_street;
    @endphp
@else
<!--Create Page-->
<form action="{{route('mp.storecif', ['record_id' => $d->id])}}" method="POST">
    @php
    $morbidity_month = date('Y-m-d');
    $date_reported = date('Y-m-d');
    $epid_number = NULL;

    $dru_name = 'CHO GENERAL TRIAS';
    $dru_region = 'IV-A';
    $dru_province = 'CAVITE';
    $dru_muncity = 'GENERAL TRIAS';
    $dru_street = 'PRIA RD';
    @endphp
@endif
    @csrf
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    @if($c->exists)
                    <div><b>Edit Monkeypox CIF of <a href="{{route('records.edit', $c->records->id)}}">{{$c->records->getName()}} | {{$c->records->getAge()}}/{{substr($c->records->gender,0,1)}} | {{date('m/d/Y', strtotime($c->records->bdate))}}</a> [ICD 10 - CM Code: B04]</b></div>
                    @else
                    <div><b>New Monkeypox Case Investigation Form (CIF) [ICD 10 - CM Code: B04]</b></div>
                    @endif
                    <div><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#appendix">Appendix</div>
                </div>
            </div>
            <div class="card-body">
                @if(session('msg'))
                <div class="text-center alert alert-{{session('msgtype')}}" role="alert">
                    {{session('msg')}}
                </div>
                @endif
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="morbidity_month"><span class="text-danger font-weight-bold">*</span>Morbidity Month [MM] <i>(Kung kailan na-encode)</i></label>
                            <input type="date"class="form-control" name="morbidity_month" id="morbidity_month" value="{{old('morbidity_month', $morbidity_month)}}" max="{{date('Y-m-d')}}" required>
                        </div>
                        <div class="form-group">
                            <label for="date_reported"><span class="text-danger font-weight-bold">*</span>Date Reported <i>(Kung kailan lumabas ang Swab Test Result)</i></label>
                            <input type="date"class="form-control" name="date_reported" id="date_reported" value="{{old('date_reported', $date_reported)}}" max="{{date('Y-m-d')}}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="epid_number">EPID Number</label>
                            <input type="text"class="form-control" name="epid_number" id="epid_number" value="{{old('epid_number', $epid_number)}}" style="text-transform: uppercase;">
                        </div>
                        <div class="form-group">
                            <label for="laboratory_id">Laboratory ID</label>
                            <input type="text"class="form-control" name="laboratory_id" id="laboratory_id" value="{{old('laboratory_id', $c->laboratory_id)}}" style="text-transform: uppercase;">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                  <label for="remarks">Remarks</label>
                  <textarea class="form-control" name="remarks" id="remarks" rows="3">{{old('remarks', $c->remarks)}}</textarea>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                          <label for="dru_name"><span class="text-danger font-weight-bold">*</span>Name of DRU</label>
                          <input type="text"class="form-control" name="dru_name" id="dru_name" value="{{old('dru_name', $dru_name)}}" style="text-transform: uppercase;" required>
                        </div>
                        <div class="form-group">
                            <label for="dru_region"><span class="text-danger font-weight-bold">*</span>Region of DRU</label>
                            <input type="text"class="form-control" name="dru_region" id="dru_region" value="{{old('dru_region', $dru_region)}}" style="text-transform: uppercase;" required>
                        </div>
                        <div class="form-group">
                            <label for="dru_province"><span class="text-danger font-weight-bold">*</span>Province of DRU</label>
                            <input type="text"class="form-control" name="dru_province" id="dru_province" value="{{old('dru_province', $dru_province)}}" style="text-transform: uppercase;" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="dru_muncity"><span class="text-danger font-weight-bold">*</span>Municipality/City of DRU</label>
                            <input type="text"class="form-control" name="dru_muncity" id="dru_muncity" value="{{old('dru_muncity', $dru_muncity)}}" style="text-transform: uppercase;" required>
                        </div>
                        <div class="form-group">
                            <label for="dru_street"><span class="text-danger font-weight-bold">*</span>Street of DRU</label>
                            <input type="text"class="form-control" name="dru_street" id="dru_street" value="{{old('dru_street', $dru_street)}}" style="text-transform: uppercase;" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="date_investigation"><span class="text-danger font-weight-bold">*</span>Date of Investigation</label>
                            <input type="date"class="form-control" name="date_investigation" id="date_investigation" value="{{old('date_investigation', $c->date_investigation)}}" max="{{date('Y-m-d')}}" required>
                        </div>
                        <div class="form-group">
                            <label for="type"><span class="text-danger font-weight-bold">*</span>Type of DRU</label>
                            <select class="form-control" name="type" id="type" required>
                                <option value="C/MHO" {{(old('type', $c->type) == 'C/MHO') ? 'selected' : ''}}>C/MHO</option>
                                <option value="GOVT HOSPITAL" {{(old('type', $c->type) == 'GOVT HOSPITAL') ? 'selected' : ''}}>GOV'T HOSPITAL</option>
                                <option value="PRIVATE HOSPITAL" {{(old('type', $c->type) == 'PRIVATE HOSPITAL') ? 'selected' : ''}}>PRIVATE HOSPITAL</option>
                                <option value="AIRPORT" {{(old('type', $c->type) == 'AIRPORT') ? 'selected' : ''}}>AIRPORT</option>
                                <option value="SEAPORT" {{(old('type', $c->type) == 'SEAPORT') ? 'selected' : ''}}>SEAPORT</option>
                                <option value="GOVT LABORATORY" {{(old('type', $c->type) == 'GOVT LABORATORY') ? 'selected' : ''}}>GOV'T LABORATORY</option>
                                <option value="PRIVATE LABORATORY" {{(old('type', $c->type) == 'PRIVATE LABORATORY') ? 'selected' : ''}}>PRIVATE LABORATORY</option>
                            </select>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="informant_name">Name of Informant</label>
                            <input type="text"class="form-control" name="informant_name" id="informant_name" value="{{old('informant_name', $c->informant_name)}}" style="text-transform: uppercase;">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="informant_relationship">Relationship with Patient</label>
                            <input type="text"class="form-control" name="informant_relationship" id="informant_relationship" value="{{old('informant_relationship', $c->informant_relationship)}}" style="text-transform: uppercase;">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="informant_contactnumber">Contact No. of Informant</label>
                            <input type="text"class="form-control" name="informant_contactnumber" id="informant_contactnumber" value="{{old('informant_contactnumber', $c->informant_contactnumber)}}" style="text-transform: uppercase;">
                        </div>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-header"><b>II. PATIENT STATUS</b></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date_admitted"><span class="text-danger font-weight-bold">*</span>Date Admitted/Seen/Consult</label>
                                    <input type="date"class="form-control" name="date_admitted" id="date_admitted" value="{{old('date_admitted', $c->date_admitted)}}" max="{{date('Y-m-d')}}" required>
                                </div>
                                <div class="form-group">
                                    <label for="admission_er"><span class="text-danger font-weight-bold">*</span>Admitted ER</label>
                                    <select class="form-control" name="admission_er" id="admission_er" required>
                                        <option value="N" {{(old('admission_er', $c->admission_er) == 'N') ? 'selected' : ''}}>NO</option>
                                        <option value="Y" {{(old('admission_er', $c->admission_er) == 'Y') ? 'selected' : ''}}>YES</option>
                                        <option value="U" {{(old('admission_er', $c->admission_er) == 'U') ? 'selected' : ''}}>UNKNOWN</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="admission_ward"><span class="text-danger font-weight-bold">*</span>Admitted Ward</label>
                                    <select class="form-control" name="admission_ward" id="admission_ward" required>
                                        <option value="N" {{(old('admission_ward', $c->admission_ward) == 'N') ? 'selected' : ''}}>NO</option>
                                        <option value="Y" {{(old('admission_ward', $c->admission_ward) == 'Y') ? 'selected' : ''}}>YES</option>
                                        <option value="U" {{(old('admission_ward', $c->admission_ward) == 'U') ? 'selected' : ''}}>UNKNOWN</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="admission_icu"><span class="text-danger font-weight-bold">*</span>Admitted ER</label>
                                    <select class="form-control" name="admission_icu" id="admission_icu" required>
                                        <option value="N" {{(old('admission_icu', $c->admission_icu) == 'N') ? 'selected' : ''}}>NO</option>
                                        <option value="Y" {{(old('admission_icu', $c->admission_icu) == 'Y') ? 'selected' : ''}}>YES</option>
                                        <option value="U" {{(old('admission_icu', $c->admission_icu) == 'U') ? 'selected' : ''}}>UNKNOWN</option>
                                    </select>
                                </div>
                                <hr>
                                <div class="form-group">
                                    <label for="other_medicalinformation">Any other known medical information</label>
                                    <input type="text"class="form-control" name="other_medicalinformation" id="other_medicalinformation" value="{{old('other_medicalinformation', $c->other_medicalinformation)}}" style="text-transform: uppercase;">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ifhashistory_blooddonation_transfusion"><span class="text-danger font-weight-bold">*</span>Blood Donation/Transfusion History</label>
                                    <select class="form-control" name="ifhashistory_blooddonation_transfusion" id="ifhashistory_blooddonation_transfusion">
                                        <option value="" {{(old('ifhashistory_blooddonation_transfusion', $c->ifhashistory_blooddonation_transfusion) == '') ? 'selected' : ''}}>N/A</option>
                                        <option value="DONOR" {{(old('ifhashistory_blooddonation_transfusion', $c->ifhashistory_blooddonation_transfusion) == 'DONOR') ? 'selected' : ''}}>DONOR</option>
                                        <option value="RECIPIENT" {{(old('ifhashistory_blooddonation_transfusion', $c->ifhashistory_blooddonation_transfusion) == 'RECIPIENT') ? 'selected' : ''}}>RECIPIENT</option>
                                    </select>
                                </div>
                                <div id="blooddono_div" class="d-none">
                                    <div class="form-group">
                                        <label for="ifhashistory_blooddonation_transfusion_place"><span class="text-danger font-weight-bold">*</span>Place of Donation/Transfusion</label>
                                        <input type="text"class="form-control" name="ifhashistory_blooddonation_transfusion_place" id="ifhashistory_blooddonation_transfusion_place" value="{{old('ifhashistory_blooddonation_transfusion_place', $c->ifhashistory_blooddonation_transfusion_place)}}" style="text-transform: uppercase;">
                                    </div>
                                    <div class="form-group">
                                        <label for="ifhashistory_blooddonation_transfusion_date"><span class="text-danger font-weight-bold">*</span>Date of Donation/Transfusion</label>
                                        <input type="text"class="form-control" name="ifhashistory_blooddonation_transfusion_date" id="ifhashistory_blooddonation_transfusion_date" value="{{old('ifhashistory_blooddonation_transfusion_date', $c->ifhashistory_blooddonation_transfusion_date)}}" max="{{date('Y-m-d')}}" style="text-transform: uppercase;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-header"><b>III. CLINICAL HISTORY/PRESENTATION</b></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date_onsetofillness"><span class="text-danger font-weight-bold">*</span>Date onset of illness</label>
                                    <input type="date"class="form-control" name="date_onsetofillness" id="date_onsetofillness" value="{{old('date_onsetofillness', $c->date_onsetofillness)}}" max="{{date('Y-m-d')}}" required>
                                </div>
                                <hr>
                                <div class="form-group">
                                    <label for="have_cutaneous_rash"><span class="text-danger font-weight-bold">*</span>1. Does the patient have a cutaneous rash?</label>
                                    <select class="form-control" name="have_cutaneous_rash" id="have_cutaneous_rash" required>
                                        <option value="N" {{(old('have_cutaneous_rash', $c->have_cutaneous_rash) == 'N') ? 'selected' : ''}}>NO</option>
                                        <option value="Y" {{(old('have_cutaneous_rash', $c->have_cutaneous_rash) == 'Y') ? 'selected' : ''}}>YES</option>
                                    </select>
                                </div>
                                <div class="form-group d-none" id="div_have_cutaneous_rash">
                                    <label for="have_cutaneous_rash_date"><span class="text-danger font-weight-bold">*</span>If yes, date of onset for the rash</label>
                                    <input type="date"class="form-control" name="have_cutaneous_rash_date" id="have_cutaneous_rash_date" value="{{old('have_cutaneous_rash_date', $c->have_cutaneous_rash_date)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group">
                                    <label for="have_fever"><span class="text-danger font-weight-bold">*</span>2. Did the patient have fever?</label>
                                    <select class="form-control" name="have_fever" id="have_fever" required>
                                        <option value="N" {{(old('have_fever', $c->have_fever) == 'N') ? 'selected' : ''}}>NO</option>
                                        <option value="Y" {{(old('have_fever', $c->have_fever) == 'Y') ? 'selected' : ''}}>YES</option>
                                    </select>
                                </div>
                                <div id="div_have_fever" class="d-none">
                                    <div class="form-group">
                                        <label for="have_fever_date"><span class="text-danger font-weight-bold">*</span>If yes, date of onset for the fever</label>
                                        <input type="date" class="form-control" name="have_fever_date" id="have_fever_date" value="{{old('have_fever_date', $c->have_fever_date)}}" max="{{date('Y-m-d')}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="have_fever_days_duration"><span class="text-danger font-weight-bold">*</span>Duration of fever (Days)</label>
                                        <input type="date" class="form-control" name="have_fever_days_duration" id="have_fever_days_duration" value="{{old('have_fever_days_duration', $c->have_fever_days_duration)}}" min="1" max="99">
                                    </div>
                                </div>
                                <label for="have_fever_date">3. If there is active disease,</label>
                                <ul>
                                    <li>
                                        <div class="form-group">
                                            <label for="have_activedisease_lesion_samestate"><span class="text-danger font-weight-bold">*</span>3.1 Lesions are in the same state of development on the body?</label>
                                            <select class="form-control" name="have_activedisease_lesion_samestate" id="have_activedisease_lesion_samestate" required>
                                                <option value="N" {{(old('have_activedisease_lesion_samestate', $c->have_activedisease_lesion_samestate) == 'N') ? 'selected' : ''}}>NO</option>
                                                <option value="Y" {{(old('have_activedisease_lesion_samestate', $c->have_activedisease_lesion_samestate) == 'Y') ? 'selected' : ''}}>YES</option>
                                            </select>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-group">
                                            <label for="have_activedisease_lesion_samesize"><span class="text-danger font-weight-bold">*</span>3.2 Are all of the lesions the same size?</label>
                                            <select class="form-control" name="have_activedisease_lesion_samesize" id="have_activedisease_lesion_samesize" required>
                                                <option value="N" {{(old('have_activedisease_lesion_samesize', $c->have_activedisease_lesion_samesize) == 'N') ? 'selected' : ''}}>NO</option>
                                                <option value="Y" {{(old('have_activedisease_lesion_samesize', $c->have_activedisease_lesion_samesize) == 'Y') ? 'selected' : ''}}>YES</option>
                                            </select>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-group">
                                            <label for="have_activedisease_lesion_deep"><span class="text-danger font-weight-bold">*</span>3.3 Are the lesions deep and profound?</label>
                                            <select class="form-control" name="have_activedisease_lesion_deep" id="have_activedisease_lesion_deep" required>
                                                <option value="N" {{(old('have_activedisease_lesion_deep', $c->have_activedisease_lesion_samesize) == 'N') ? 'selected' : ''}}>NO</option>
                                                <option value="Y" {{(old('have_activedisease_lesion_deep', $c->have_activedisease_lesion_samesize) == 'Y') ? 'selected' : ''}}>YES</option>
                                            </select>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-group">
                                            <label for="have_activedisease_develop_ulcers"><span class="text-danger font-weight-bold">*</span>3.4. Did the patient develop ulcers?</label>
                                            <select class="form-control" name="have_activedisease_develop_ulcers" id="have_activedisease_develop_ulcers" required>
                                                <option value="N" {{(old('have_activedisease_develop_ulcers', $c->have_activedisease_develop_ulcers) == 'N') ? 'selected' : ''}}>NO</option>
                                                <option value="Y" {{(old('have_activedisease_develop_ulcers', $c->have_activedisease_develop_ulcers) == 'Y') ? 'selected' : ''}}>YES</option>
                                            </select>
                                        </div>
                                    </li>
                                </ul>
                                <div class="form-group">
                                    <label for="have_activedisease_lesion_type"><span class="text-danger font-weight-bold">*</span>4. Type of lesions</label>
                                    <select class="form-control" name="have_activedisease_lesion_type[]" id="have_activedisease_lesion_type" multiple required>
                                        <option value="" disabled {{(old('have_activedisease_lesion_type', $c->have_activedisease_lesion_type) == '') ? 'selected' : ''}}>N/A</option>
                                        <option value="MACULE" {{(collect(old('have_activedisease_lesion_type', explode(',', $c->have_activedisease_lesion_type)))->contains('MACULE')) ? 'selected' : ''}}>MACULE</option>
                                        <option value="PAPULE" {{(collect(old('have_activedisease_lesion_type', explode(',', $c->have_activedisease_lesion_type)))->contains('PAPULE')) ? 'selected' : ''}}>PAPULE</option>
                                        <option value="VESICLE" {{(collect(old('have_activedisease_lesion_type', explode(',', $c->have_activedisease_lesion_type)))->contains('VESICLE')) ? 'selected' : ''}}>VESICLE</option>
                                        <option value="PUSTULE" {{(collect(old('have_activedisease_lesion_type', explode(',', $c->have_activedisease_lesion_type)))->contains('PUSTULE')) ? 'selected' : ''}}>PUSTULE</option>
                                        <option value="SCAB" {{(collect(old('have_activedisease_lesion_type', explode(',', $c->have_activedisease_lesion_type)))->contains('SCAB')) ? 'selected' : ''}}>SCAB</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="have_activedisease_lesion_localization"><span class="text-danger font-weight-bold">*</span>5. Localization of the lesions</label>
                                    <select class="form-control" name="have_activedisease_lesion_localization[]" id="have_activedisease_lesion_localization" multiple required>
                                        <option value="" disabled {{(old('have_activedisease_lesion_localization', $c->have_activedisease_lesion_localization) == '') ? 'selected' : ''}}>N/A</option>
                                        <option value="FACE" {{(collect(old('have_activedisease_lesion_localization', explode(',', $c->have_activedisease_lesion_localization)))->contains('FACE')) ? 'selected' : ''}}>FACE</option>
                                        <option value="PALMS OF THE HANDS" {{(collect(old('have_activedisease_lesion_localization', explode(',', $c->have_activedisease_lesion_localization)))->contains('PALMS OF THE HANDS')) ? 'selected' : ''}}>PALMS OF THE HANDS</option>
                                        <option value="THORAX" {{(collect(old('have_activedisease_lesion_localization', explode(',', $c->have_activedisease_lesion_localization)))->contains('THORAX')) ? 'selected' : ''}}>THORAX</option>
                                        <option value="ARMS" {{(collect(old('have_activedisease_lesion_localization', explode(',', $c->have_activedisease_lesion_localization)))->contains('ARMS')) ? 'selected' : ''}}>ARMS</option>
                                        <option value="LEGS" {{(collect(old('have_activedisease_lesion_localization', explode(',', $c->have_activedisease_lesion_localization)))->contains('LEGS')) ? 'selected' : ''}}>LEGS</option>
                                        <option value="SOLES OF THE FEET" {{(collect(old('have_activedisease_lesion_localization', explode(',', $c->have_activedisease_lesion_localization)))->contains('SOLES OF THE FEET')) ? 'selected' : ''}}>SOLES OF THE FEET</option>
                                        <option value="GENITALS" {{(collect(old('have_activedisease_lesion_localization', explode(',', $c->have_activedisease_lesion_localization)))->contains('GENITALS')) ? 'selected' : ''}}>GENITALS</option>
                                        <option value="ALL OVER THE BODY" {{(collect(old('have_activedisease_lesion_localization', explode(',', $c->have_activedisease_lesion_localization)))->contains('ALL OVER THE BODY')) ? 'selected' : ''}}>ALL OVER THE BODY</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="have_activedisease_lesion_localization_otherareas">List other areas of the lesions</label>
                                    <input type="text"class="form-control" name="have_activedisease_lesion_localization_otherareas" id="have_activedisease_lesion_localization_otherareas" value="{{old('have_activedisease_lesion_localization_otherareas', $c->have_activedisease_lesion_localization_otherareas)}}" style="text-transform: uppercase;">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="symptoms_list"><span class="text-danger font-weight-bold">*</span>Signs and Symptoms (Select all that apply)</label>
                                    <select class="form-control" name="symptoms_list[]" id="symptoms_list" multiple required>
                                        <option value="" disabled {{(old('symptoms_list', $c->symptoms_list) == '') ? 'selected' : ''}}>N/A</option>
                                        <option value="VOMITING/NAUSEA" {{(collect(old('symptoms_list', explode(',', $c->symptoms_list)))->contains('VOMITING/NAUSEA')) ? 'selected' : ''}}>VOMITING/NAUSEA</option>
                                        <option value="HEADACHE" {{(collect(old('symptoms_list', explode(',', $c->symptoms_list)))->contains('HEADACHE')) ? 'selected' : ''}}>HEADACHEA</option>
                                        <option value="COUGH" {{(collect(old('symptoms_list', explode(',', $c->symptoms_list)))->contains('COUGH')) ? 'selected' : ''}}>COUGH</option>
                                        <option value="MUSCLE PAIN (MYALGIA)" {{(collect(old('symptoms_list', explode(',', $c->symptoms_list)))->contains('MUSCLE PAIN (MYALGIA)')) ? 'selected' : ''}}>MUSCLE PAIN (MYALGIA)</option>
                                        <option value="ASTHENIA (WEAKNESS)" {{(collect(old('symptoms_list', explode(',', $c->symptoms_list)))->contains('ASTHENIA (WEAKNESS)')) ? 'selected' : ''}}>ASTHENIA (WEAKNESS)</option>
                                        <option value="FATIGUE" {{(collect(old('symptoms_list', explode(',', $c->symptoms_list)))->contains('FATIGUE')) ? 'selected' : ''}}>FATIGUE</option>
                                        <option value="CONJUNCTIVITIS" {{(collect(old('symptoms_list', explode(',', $c->symptoms_list)))->contains('CONJUNCTIVITIS')) ? 'selected' : ''}}>CONJUNCTIVITIS</option>
                                        <option value="CHILLS OR SWEATS" {{(collect(old('symptoms_list', explode(',', $c->symptoms_list)))->contains('CHILLS OR SWEATS')) ? 'selected' : ''}}>CHILLS OR SWEATS</option>
                                        <option value="SENSITIVITY TO LIGHT" {{(collect(old('symptoms_list', explode(',', $c->symptoms_list)))->contains('SENSITIVITY TO LIGHT')) ? 'selected' : ''}}>SENSITIVITY TO LIGHT</option>
                                        <option value="SORE THROAT WHEN SWALLOWING" {{(collect(old('symptoms_list', explode(',', $c->symptoms_list)))->contains('SORE THROAT WHEN SWALLOWING')) ? 'selected' : ''}}>SORE THROAT WHEN SWALLOWING</option>
                                        <option value="ORAL ULCERS" {{(collect(old('symptoms_list', explode(',', $c->symptoms_list)))->contains('ORAL ULCERS')) ? 'selected' : ''}}>ORAL ULCERS</option>
                                        <option value="LYMPHADENOPATHY" {{(collect(old('symptoms_list', explode(',', $c->symptoms_list)))->contains('LYMPHADENOPATHY')) ? 'selected' : ''}}>LYMPHADENOPATHY (SPECIFY LOCALIZATION)</option>
                                    </select>
                                </div>
                                <div id="div_lymp" class="d-none">
                                    <div class="form-group">
                                        <label for="symptoms_lymphadenopathy_localization"><span class="text-danger font-weight-bold">*</span>Specify Localiztion of Lymphadenopathy</label>
                                        <select class="form-control" name="symptoms_lymphadenopathy_localization[]" id="symptoms_lymphadenopathy_localization" multiple>
                                            <option value="" disabled {{(old('symptoms_lymphadenopathy_localization', $c->symptoms_lymphadenopathy_localization) == '') ? 'selected' : ''}}>Choose...</option>
                                            <option value="CERVICAL" {{(collect(old('symptoms_lymphadenopathy_localization', explode(',', $c->symptoms_lymphadenopathy_localization)))->contains('CERVICAL')) ? 'selected' : ''}}>CERVICAL</option>
                                            <option value="AXILLARY" {{(collect(old('symptoms_lymphadenopathy_localization', explode(',', $c->symptoms_lymphadenopathy_localization)))->contains('AXILLARY')) ? 'selected' : ''}}>AXILLARY</option>
                                            <option value="INGUINAL" {{(collect(old('symptoms_lymphadenopathy_localization', explode(',', $c->symptoms_lymphadenopathy_localization)))->contains('INGUINAL')) ? 'selected' : ''}}>INGUINAL</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-header"><b>IV. HISTORY OF EXPOSURE</b></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="history1_yn"><span class="text-danger font-weight-bold">*</span>1. Did the patient travel anytime in the three weeks before becoming ill?</label>
                                    <select class="form-control" name="history1_yn" id="history1_yn" required>
                                        <option value="N" {{(old('history1_yn', $c->history1_yn) == 'N') ? 'selected' : ''}}>NO</option>
                                        <option value="Y" {{(old('history1_yn', $c->history1_yn) == 'Y') ? 'selected' : ''}}>YES</option>
                                    </select>
                                </div>
                                <div id="div_history1" class="d-none">
                                    <div class="form-group">
                                        <label for="history1_specify"><span class="text-danger font-weight-bold">*</span>Specify</label>
                                        <input type="text"class="form-control" name="history1_specify" id="history1_specify" value="{{old('history1_specify', $c->history1_specify)}}" style="text-transform: uppercase;">
                                    </div>
                                    <div class="form-group">
                                        <label for="history1_date_travel"><span class="text-danger font-weight-bold">*</span>Date of Travel</label>
                                        <input type="date"class="form-control" name="history1_date_travel" id="history1_date_travel" value="{{old('history1_date_travel', $c->history1_date_travel)}}" max="{{date('Y-m-d')}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="history1_flightno"><span class="text-danger font-weight-bold">*</span>Flight/Vessel #</label>
                                        <input type="text"class="form-control" name="history1_flightno" id="history1_flightno" value="{{old('history1_flightno', $c->history1_flightno)}}" style="text-transform: uppercase;">
                                    </div>
                                    <div class="form-group">
                                        <label for="history1_date_arrival"><span class="text-danger font-weight-bold">*</span>Date of Arrival</label>
                                        <input type="date"class="form-control" name="history1_date_arrival" id="history1_date_arrival" value="{{old('history1_date_arrival', $c->history1_date_arrival)}}" max="{{date('Y-m-d')}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="history1_pointandexitentry"><span class="text-danger font-weight-bold">*</span>Point of entry and exit</label>
                                        <input type="text"class="form-control" name="history1_pointandexitentry" id="history1_pointandexitentry" value="{{old('history1_pointandexitentry', $c->history1_pointandexitentry)}}" style="text-transform: uppercase;">
                                    </div>
                                    <hr>
                                </div>
                                <div class="form-group">
                                    <label for="history2_yn"><span class="text-danger font-weight-bold">*</span>2. Did the patient travel during illness?</label>
                                    <select class="form-control" name="history2_yn" id="history2_yn" required>
                                        <option value="N" {{(old('history2_yn', $c->history2_yn) == 'N') ? 'selected' : ''}}>NO</option>
                                        <option value="Y" {{(old('history2_yn', $c->history2_yn) == 'Y') ? 'selected' : ''}}>YES</option>
                                    </select>
                                </div>
                                <div id="div_history2" class="d-none">
                                    <div class="form-group">
                                        <label for="history2_specify"><span class="text-danger font-weight-bold">*</span>Specify</label>
                                        <input type="text"class="form-control" name="history2_specify" id="history2_specify" value="{{old('history2_specify', $c->history2_specify)}}" style="text-transform: uppercase;">
                                    </div>
                                    <div class="form-group">
                                        <label for="history2_date_travel"><span class="text-danger font-weight-bold">*</span>Date of Travel</label>
                                        <input type="date"class="form-control" name="history2_date_travel" id="history2_date_travel" value="{{old('history2_date_travel', $c->history2_date_travel)}}" max="{{date('Y-m-d')}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="history2_flightno"><span class="text-danger font-weight-bold">*</span>Flight/Vessel #</label>
                                        <input type="text"class="form-control" name="history2_flightno" id="history2_flightno" value="{{old('history2_flightno', $c->history2_flightno)}}" style="text-transform: uppercase;">
                                    </div>
                                    <div class="form-group">
                                        <label for="history2_date_arrival"><span class="text-danger font-weight-bold">*</span>Date of Arrival</label>
                                        <input type="date"class="form-control" name="history2_date_arrival" id="history2_date_arrival" value="{{old('history2_date_arrival', $c->history2_date_arrival)}}" max="{{date('Y-m-d')}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="history2_pointandexitentry"><span class="text-danger font-weight-bold">*</span>Point of entry and exit</label>
                                        <input type="text"class="form-control" name="history2_pointandexitentry" id="history2_pointandexitentry" value="{{old('history2_pointandexitentry', $c->history2_pointandexitentry)}}" style="text-transform: uppercase;">
                                    </div>
                                    <hr>
                                </div>
                                <div class="form-group">
                                    <label for="history3_yn"><span class="text-danger font-weight-bold">*</span>3. Within 21 days befores symptom onset, did the patient have contact with one or more persons who had similar symptoms?</label>
                                    <select class="form-control" name="history3_yn" id="history3_yn" required>
                                        <option value="N" {{(old('history3_yn', $c->history3_yn) == 'N') ? 'selected' : ''}}>NO</option>
                                        <option value="Y" {{(old('history3_yn', $c->history3_yn) == 'Y') ? 'selected' : ''}}>YES</option>
                                    </select>
                                    <small class="text-muted d-none" id="div_history3">If YES, accomplish  Appendix A "Monkepox Contact listing Form" than can be downloaded <a href="{{asset('MONKEYPOX_APPENDIXA.pdf')}}" target="_blank">HERE</a></small>
                                </div>
                                <div class="form-group">
                                    <label for="history4_yn"><span class="text-danger font-weight-bold">*</span>4. Did the patient touch a domestic or wild animal within 21 days before symptom onset?</label>
                                    <select class="form-control" name="history4_yn" id="history4_yn" required>
                                        <option value="N" {{(old('history4_yn', $c->history4_yn) == 'N') ? 'selected' : ''}}>NO</option>
                                        <option value="Y" {{(old('history4_yn', $c->history4_yn) == 'Y') ? 'selected' : ''}}>YES</option>
                                    </select>
                                </div>
                                <div id="div_history4" class="d-none">
                                    <div class="form-group">
                                        <label for="history4_typeofanimal"><span class="text-danger font-weight-bold">*</span>What kind of animal</label>
                                        <input type="text" class="form-control" name="history4_typeofanimal" id="history4_typeofanimal" value="{{old('history4_typeofanimal', $c->history4_typeofanimal)}}" style="text-transform: uppercase;">
                                    </div>
                                    <div class="form-group">
                                        <label for="history4_firstexposure"><span class="text-danger font-weight-bold">*</span>Date of FIRST exposure/contact</label>
                                        <input type="date" class="form-control" name="history4_firstexposure" id="history4_firstexposure" value="{{old('history4_firstexposure', $c->history4_firstexposure)}}" max="{{date('Y-m-d')}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="history4_lastexposure"><span class="text-danger font-weight-bold">*</span>Date of LAST exposure/contact</label>
                                        <input type="date" class="form-control" name="history4_lastexposure" id="history4_lastexposure" value="{{old('history4_lastexposure', $c->history4_lastexposure)}}" max="{{date('Y-m-d')}}">
                                    </div>
                                    <div class="form-group">
                                      <label for="history4_type"><span class="text-danger font-weight-bold">*</span>Type of contact (Select all that apply)</label>
                                      <select class="form-control" name="history4_type[]" id="history4_type" multiple>
                                        <option value="Rodents alive in the house" {{(collect(old('history4_type', explode(',', $c->history4_type)))->contains('Rodents alive in the house')) ? 'selected' : ''}}>Rodents alive in the house</option>
                                        <option value="Dead animal found in the forest" {{(collect(old('history4_type', explode(',', $c->history4_type)))->contains('Dead animal found in the forest')) ? 'selected' : ''}}>Dead animal found in the forest</option>
                                        <option value="Alive animal living in the forest" {{(collect(old('history4_type', explode(',', $c->history4_type)))->contains('Alive animal living in the forest')) ? 'selected' : ''}}>Alive animal living in the forest</option>
                                        <option value="Animal bought for meat" {{(collect(old('history4_type', explode(',', $c->history4_type)))->contains('Animal bought for meat')) ? 'selected' : ''}}>Animal bought for meat</option>
                                        <option value="Others" {{(collect(old('history4_type', explode(',', $c->history4_type)))->contains('Others')) ? 'selected' : ''}}>Others</option>
                                      </select>
                                    </div>
                                    <div class="form-group d-none" id="div_history4_type_others">
                                        <label for="history4_type_others"><span class="text-danger font-weight-bold">*</span>Specify</label>
                                        <input type="text" class="form-control" name="history4_type_others" id="history4_type_others" value="{{old('history4_type_others', $c->history4_type_others)}}" style="text-transform: uppercase;">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="history5_genderidentity"><span class="text-danger font-weight-bold">*</span>5. Patients Gender Identity</label>
                                    <select class="form-control" name="history5_genderidentity" id="history5_genderidentity" required>
                                        <option value="" disabled {{(old('history5_genderidentity', $c->history5_genderidentity) == '') ? 'selected' : ''}}>Choose...</option>
                                        <option value="MAN" {{(old('history5_genderidentity', $c->history5_genderidentity) == 'MAN') ? 'selected' : ''}}>MAN</option>
                                        <option value="WOMAN" {{(old('history5_genderidentity', $c->history5_genderidentity) == 'WOMAN') ? 'selected' : ''}}>WOMAN</option>
                                        <option value="IN THE MIDDLE" {{(old('history5_genderidentity', $c->history5_genderidentity) == 'IN THE MIDDLE') ? 'selected' : ''}}>IN THE MIDDLE</option>
                                        <option value="NON BINARY" {{(old('history5_genderidentity', $c->history5_genderidentity) == 'NON BINARY') ? 'selected' : ''}}>NON BINARY</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="history6_yn"><span class="text-danger font-weight-bold">*</span>6. Did the patient engage in sex (vaginal, oral, or anal) within 21 days before symptom onset?</label>
                                    <select class="form-control" name="history6_yn" id="history6_yn" required>
                                        <option value="N" {{(old('history6_yn', $c->history6_yn) == 'N') ? 'selected' : ''}}>NO</option>
                                        <option value="Y" {{(old('history6_yn', $c->history6_yn) == 'Y') ? 'selected' : ''}}>YES</option>
                                    </select>
                                </div>
                                <div id="div_history6" class="d-none">
                                    <table class="table table-bordered">
                                        <thead class="text-center thead-light">
                                            <tr>
                                                <th></th>
                                                <th>History of sexual activity or close initmate contact</th>
                                                <th>No. of Sexual Partners</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Male to male</td>
                                                <td>
                                                    <div class="form-group">
                                                        <select class="form-control" name="history6_mtm" id="history6_mtm">
                                                            <option value="" disabled {{(old('history6_mtm', $c->history6_mtm) == '') ? 'selected' : ''}}>Choose...</option>
                                                            <option value="N" {{(old('history6_mtm', $c->history6_mtm) == 'N') ? 'selected' : ''}}>NO</option>
                                                            <option value="Y" {{(old('history6_mtm', $c->history6_mtm) == 'Y') ? 'selected' : ''}}>YES</option>
                                                        </select>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <input type="number" class="form-control" name="history6_mtm_nosp" id="history6_mtm_nosp" value="{{old('history6_mtm_nosp', $c->history6_mtm_nosp)}}">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Male to female</td>
                                                <td>
                                                    <div class="form-group">
                                                        <select class="form-control" name="history6_mtf" id="history6_mtf">
                                                            <option value="" disabled {{(old('history6_mtf', $c->history6_mtf) == '') ? 'selected' : ''}}>Choose...</option>
                                                            <option value="N" {{(old('history6_mtf', $c->history6_mtf) == 'N') ? 'selected' : ''}}>NO</option>
                                                            <option value="Y" {{(old('history6_mtf', $c->history6_mtf) == 'Y') ? 'selected' : ''}}>YES</option>
                                                        </select>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <input type="number" class="form-control" name="history6_mtf_nosp" id="history6_mtf_nosp" value="{{old('history6_mtf_nosp', $c->history6_mtf_nosp)}}">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Unknown</td>
                                                <td>
                                                    <div class="form-group">
                                                        <select class="form-control" name="history6_uknown" id="history6_uknown">
                                                            <option value="" disabled {{(old('history6_uknown', $c->history6_uknown) == '') ? 'selected' : ''}}>Choose...</option>
                                                            <option value="N" {{(old('history6_uknown', $c->history6_uknown) == 'N') ? 'selected' : ''}}>NO</option>
                                                            <option value="Y" {{(old('history6_uknown', $c->history6_uknown) == 'Y') ? 'selected' : ''}}>YES</option>
                                                        </select>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <input type="number" class="form-control" name="history6_uknown_nosp" id="history6_uknown_nosp" value="{{old('history6_uknown_nosp', $c->history6_uknown_nosp)}}">
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <hr>
                                </div>
                                <div class="form-group">
                                    <label for="history7_yn"><span class="text-danger font-weight-bold">*</span>7. Did the patient experience close intimate contact (cuddling, kissing, mutual masturbation, sharing sex toys) within 21 days before symptom onset?</label>
                                    <select class="form-control" name="history7_yn" id="history7_yn" required>
                                        <option value="N" {{(old('history7_yn', $c->history7_yn) == 'N') ? 'selected' : ''}}>NO</option>
                                        <option value="Y" {{(old('history7_yn', $c->history7_yn) == 'Y') ? 'selected' : ''}}>YES</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="history8_yn"><span class="text-danger font-weight-bold">*</span>8. Sharing of items (e.g towels, beddings, food, utensils, etc.) with your sexual partners within 21 days before symptom onset?</label>
                                    <select class="form-control" name="history8_yn" id="history8_yn" required>
                                        <option value="N" {{(old('history8_yn', $c->history8_yn) == 'N') ? 'selected' : ''}}>NO</option>
                                        <option value="Y" {{(old('history8_yn', $c->history8_yn) == 'Y') ? 'selected' : ''}}>YES</option>
                                        <option value="R" {{(old('history8_yn', $c->history8_yn) == 'r') ? 'selected' : ''}}>REFUSE TO ANSWER</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="history9_choice"><span class="text-danger font-weight-bold">*</span>9. Did the patient have sex and/or close intimate contact with some one who had recently traveled outside of your city or community within 21 days before symptom onset?</label>
                                    <select class="form-control" name="history9_choice" id="history9_choice" required>
                                        <option value="NO" {{(old('history9_choice') == 'NO') ? 'selected' : ''}}>NO</option>
                                        <option value="YES, TO ANOTHER COUNTRY" {{(old('history9_choice', $c->history9_choice) == 'YES, TO ANOTHER COUNTRY') ? 'selected' : ''}}>YES, TO ANOTHER COUNTRY</option>
                                        <option value="YES, TO ANOTHER PROVINCE" {{(old('history9_choice', $c->history9_choice) == 'YES, TO ANOTHER PROVINCE') ? 'selected' : ''}}>YES, TO ANOTHER PROVINCE</option>
                                        <option value="YES, TO ANOTHER CITY WITHIN MY PROVINCE" {{(old('history9_choice', $c->history9_choice) == 'YES, TO ANOTHER CITY WITHIN MY PROVINCE') ? 'selected' : ''}}>YES, TO ANOTHER CITY WITHIN MY PROVINCE</option>
                                        <option value="UNKNOWN" {{(old('history9_choice', $c->history9_choice) == 'UNKNOWN') ? 'selected' : ''}}>UNKNOWN</option>
                                    </select>
                                </div>
                                <div class="form-group d-none" id="history9_choice_div">
                                    <label for="history9_choice_othercountry"><span class="text-danger font-weight-bold">*</span>Specify Country</label>
                                    <input type="text" class="form-control" name="history9_choice_othercountry" id="history9_choice_othercountry" value="{{old('history9_choice_othercountry', $c->history9_choice)}}" style="text-transform: uppercase;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-header"><b>V. LABORATORY TEST</b> <small><i>(Note: Collect at least two types of specimens from each patient. For each specimen: place a label on this form and a label on the specimen tube. Ensure that the two labels have the same name/number of the specimen.)</i></small></div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead class="thead-light text-center">
                                <tr>
                                    <th><span class="text-danger font-weight-bold">*</span>Test Done <i>(Select all that apply)</i></th>
                                    <th>Date Collected</th>
                                    <th>Laboratory</th>
                                    <th>Results</th>
                                    <th>Date Released</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="form-group">
                                          <label for="test_npsops">Nasopharyngeal Swab (NPS) or Oropharyngeal Swab (OPS)</label>
                                          <select class="form-control" name="test_npsops" id="test_npsops">
                                            <option value="N" {{(old('test_npsops', $c->test_npsops) == 'N') ? 'selected' : ''}}>NO</option>
                                            <option value="Y" {{(old('test_npsops', $c->test_npsops) == 'Y') ? 'selected' : ''}}>YES</option>
                                          </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="date" class="form-control" name="test_npsops_date_collected" id="test_npsops_date_collected" value="{{old('test_npsops_date_collected', $c->test_npsops_date_collected)}}" max="{{date('Y-m-d')}}">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="test_npsops_laboratory" id="test_npsops_laboratory" value="{{old('test_npsops_laboratory', $c->test_npsops_laboratory)}}" style="text-transform: uppercase;">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <select class="form-control" name="test_npsops_result" id="test_npsops_result">
                                              <option value="PENDING" {{(old('test_npsops_result', $c->test_npsops_result) == 'PENDING') ? 'selected' : ''}}>PENDING</option>
                                              <option value="POSITIVE" {{(old('test_npsops_result', $c->test_npsops_result) == 'POSITIVE') ? 'selected' : ''}}>POSITIVE</option>
                                              <option value="NEGATIVE" {{(old('test_npsops_result', $c->test_npsops_result) == 'NEGATIVE') ? 'selected' : ''}}>NEGATIVE</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="date" class="form-control" name="test_npsops_date_released" id="test_npsops_date_released" value="{{old('test_npsops_date_released', $c->test_npsops_date_released)}}" max="{{date('Y-m-d')}}">
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <div class="form-group">
                                          <label for="test_lesionfluid">Lesion Fluid</label>
                                          <select class="form-control" name="test_lesionfluid" id="test_lesionfluid">
                                            <option value="N" {{(old('test_lesionfluid', $c->test_lesionfluid) == 'N') ? 'selected' : ''}}>NO</option>
                                            <option value="Y" {{(old('test_lesionfluid', $c->test_lesionfluid) == 'Y') ? 'selected' : ''}}>YES</option>
                                          </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="date" class="form-control" name="test_lesionfluid_date_collected" id="test_lesionfluid_date_collected" value="{{old('test_lesionfluid_date_collected', $c->test_lesionfluid_date_collected)}}" max="{{date('Y-m-d')}}">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="test_lesionfluid_laboratory" id="test_lesionfluid_laboratory" value="{{old('test_lesionfluid_laboratory', $c->test_lesionfluid_laboratory)}}" style="text-transform: uppercase;">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <select class="form-control" name="test_lesionfluid_result" id="test_lesionfluid_result">
                                              <option value="PENDING" {{(old('test_lesionfluid_result', $c->test_lesionfluid_result) == 'PENDING') ? 'selected' : ''}}>PENDING</option>
                                              <option value="POSITIVE" {{(old('test_lesionfluid_result', $c->test_lesionfluid_result) == 'POSITIVE') ? 'selected' : ''}}>POSITIVE</option>
                                              <option value="NEGATIVE" {{(old('test_lesionfluid_result', $c->test_lesionfluid_result) == 'NEGATIVE') ? 'selected' : ''}}>NEGATIVE</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="date" class="form-control" name="test_lesionfluid_date_released" id="test_lesionfluid_date_released" value="{{old('test_lesionfluid_date_released', $c->test_lesionfluid_date_released)}}" max="{{date('Y-m-d')}}">
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <div class="form-group">
                                          <label for="test_lesionroof">Lesion Roof</label>
                                          <select class="form-control" name="test_lesionroof" id="test_lesionroof">
                                            <option value="N" {{(old('test_lesionroof', $c->test_lesionroof) == 'N') ? 'selected' : ''}}>NO</option>
                                            <option value="Y" {{(old('test_lesionroof', $c->test_lesionroof) == 'Y') ? 'selected' : ''}}>YES</option>
                                          </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="date" class="form-control" name="test_lesionroof_date_collected" id="test_lesionroof_date_collected" value="{{old('test_lesionroof_date_collected', $c->test_lesionroof_date_collected)}}" max="{{date('Y-m-d')}}">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="test_lesionroof_laboratory" id="test_lesionroof_laboratory" value="{{old('test_lesionroof_laboratory', $c->test_lesionroof_laboratory)}}" style="text-transform: uppercase;">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <select class="form-control" name="test_lesionroof_result" id="test_lesionroof_result">
                                              <option value="PENDING" {{(old('test_lesionroof_result', $c->test_lesionroof_result) == 'PENDING') ? 'selected' : ''}}>PENDING</option>
                                              <option value="POSITIVE" {{(old('test_lesionroof_result', $c->test_lesionroof_result) == 'POSITIVE') ? 'selected' : ''}}>POSITIVE</option>
                                              <option value="NEGATIVE" {{(old('test_lesionroof_result', $c->test_lesionroof_result) == 'NEGATIVE') ? 'selected' : ''}}>NEGATIVE</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="date" class="form-control" name="test_lesionroof_date_released" id="test_lesionroof_date_released" value="{{old('test_lesionroof_date_released', $c->test_lesionroof_date_released)}}" max="{{date('Y-m-d')}}">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-group">
                                          <label for="test_lesioncrust">Lesion Crust</label>
                                          <select class="form-control" name="test_lesioncrust" id="test_lesioncrust">
                                            <option value="N" {{(old('test_lesioncrust', $c->test_lesioncrust) == 'N') ? 'selected' : ''}}>NO</option>
                                            <option value="Y" {{(old('test_lesioncrust', $c->test_lesioncrust) == 'Y') ? 'selected' : ''}}>YES</option>
                                          </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="date" class="form-control" name="test_lesioncrust_date_collected" id="test_lesioncrust_date_collected" value="{{old('test_lesioncrust_date_collected', $c->test_lesioncrust_date_collected)}}" max="{{date('Y-m-d')}}">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="test_lesioncrust_laboratory" id="test_lesioncrust_laboratory" value="{{old('test_lesioncrust_laboratory', $c->test_lesioncrust_laboratory)}}" style="text-transform: uppercase;">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <select class="form-control" name="test_lesioncrust_result" id="test_lesioncrust_result">
                                              <option value="PENDING" {{(old('test_lesioncrust_result', $c->test_lesioncrust_result) == 'PENDING') ? 'selected' : ''}}>PENDING</option>
                                              <option value="POSITIVE" {{(old('test_lesioncrust_result', $c->test_lesioncrust_result) == 'POSITIVE') ? 'selected' : ''}}>POSITIVE</option>
                                              <option value="NEGATIVE" {{(old('test_lesioncrust_result', $c->test_lesioncrust_result) == 'NEGATIVE') ? 'selected' : ''}}>NEGATIVE</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="date" class="form-control" name="test_lesioncrust_date_released" id="test_lesioncrust_date_released" value="{{old('test_lesioncrust_date_released', $c->test_lesioncrust_date_released)}}" max="{{date('Y-m-d')}}">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-group">
                                          <label for="test_serum">Serum</label>
                                          <select class="form-control" name="test_serum" id="test_serum">
                                            <option value="N" {{(old('test_serum', $c->test_serum) == 'N') ? 'selected' : ''}}>NO</option>
                                            <option value="Y" {{(old('test_serum', $c->test_serum) == 'Y') ? 'selected' : ''}}>YES</option>
                                          </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="date" class="form-control" name="test_serum_date_collected" id="test_serum_date_collected" value="{{old('test_serum_date_collected', $c->test_serum_date_collected)}}" max="{{date('Y-m-d')}}">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="test_serum_laboratory" id="test_serum_laboratory" value="{{old('test_serum_laboratory', $c->test_serum_laboratory)}}" style="text-transform: uppercase;">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <select class="form-control" name="test_serum_result" id="test_serum_result">
                                              <option value="PENDING" {{(old('test_serum_result', $c->test_serum_result) == 'PENDING') ? 'selected' : ''}}>PENDING</option>
                                              <option value="POSITIVE" {{(old('test_serum_result', $c->test_serum_result) == 'POSITIVE') ? 'selected' : ''}}>POSITIVE</option>
                                              <option value="NEGATIVE" {{(old('test_serum_result', $c->test_serum_result) == 'NEGATIVE') ? 'selected' : ''}}>NEGATIVE</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input type="date" class="form-control" name="test_serum_date_released" id="test_serum_date_released" value="{{old('test_serum_date_released', $c->test_serum_date_released)}}" max="{{date('Y-m-d')}}">
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-header"><b>VI. HEALTH STATUS</b></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="health_status"><span class="text-danger font-weight-bold">*</span>Current Health Status</label>
                                    <select class="form-control" name="health_status" id="health_status" required>
                                        <option value="ACTIVE" {{(old('health_status', $c->health_status) == 'ACTIVE') ? 'selected' : ''}}>ACTIVE</option>
                                        <option value="DISCHARGED" {{(old('health_status', $c->health_status) == 'DISCHARGED') ? 'selected' : ''}}>DISCHARGED</option>
                                    </select>
                                </div>
                                <div class="form-group d-none" id="div_health_status_date_discharged">
                                    <label for="health_status_date_discharged"><span class="text-danger font-weight-bold">*</span>Date Discharged</label>
                                    <input type="date"class="form-control" name="health_status_date_discharged" id="health_status_date_discharged" value="{{old('health_status_date_discharged', $c->health_status_date_discharged)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group">
                                    <label for="health_status_final_diagnosis">Final Diagnosis</label>
                                    <input type="text"class="form-control" name="health_status_final_diagnosis" id="health_status_final_diagnosis" value="{{old('health_status_final_diagnosis', $c->health_status_final_diagnosis)}}" style="text-transform: uppercase;">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="outcome"><span class="text-danger font-weight-bold">*</span>Outcome</label>
                                    <select class="form-control" name="outcome" id="outcome">
                                        <option value="" {{(old('outcome', $c->outcome) == '') ? 'selected' : ''}}>N/A</option>
                                        <option value="RECOVERED" {{(old('outcome', $c->outcome) == 'RECOVERED') ? 'selected' : ''}}>RECOVERED</option>
                                        <option value="DIED" {{(old('outcome', $c->outcome) == 'DIED') ? 'selected' : ''}}>DIED</option>
                                        <option value="UNKNOWN" {{(old('outcome', $c->outcome) == 'UNKNOWN') ? 'selected' : ''}}>UNKNOWN</option>
                                        <option value="TRANSFERRED TO OTHER HEALTHCARE SETTING" {{(old('outcome', $c->outcome) == 'TRANSFERRED TO OTHER HEALTHCARE SETTING') ? 'selected' : ''}}>TRANSFERRED TO OTHER HEALTHCARE SETTING</option>
                                    </select>
                                </div>
                                <div class="form-group d-none" id="div_outcome_date_recovered">
                                    <label for="outcome_date_recovered"><span class="text-danger font-weight-bold">*</span>Date Recovered</label>
                                    <input type="date"class="form-control" name="outcome_date_recovered" id="outcome_date_recovered" value="{{old('outcome_date_recovered', $c->outcome_date_recovered)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div id="div_outcome_date_died" class="d-none">
                                    <div class="form-group">
                                        <label for="outcome_date_died"><span class="text-danger font-weight-bold">*</span>Date Died</label>
                                        <input type="date"class="form-control" name="outcome_date_died" id="outcome_date_died" value="{{old('outcome_date_recovered', $c->outcome_date_recovered)}}" max="{{date('Y-m-d')}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="outcome_causeofdeath"><span class="text-danger font-weight-bold">*</span>Cause of death</label>
                                        <input type="text"class="form-control" name="outcome_causeofdeath" id="outcome_causeofdeath" value="{{old('outcome_causeofdeath', $c->outcome_causeofdeath)}}" style="text-transform: uppercase;">
                                    </div>
                                </div>
                                <div id="div_outcome_unknown_type" class="d-none">
                                    <div class="form-group">
                                        <label for="outcome_unknown_type"><span class="text-danger font-weight-bold">*</span>Type</label>
                                        <select class="form-control" name="outcome_unknown_type" id="outcome_unknown_type" required>
                                            <option value="HAMA" {{(old('outcome_unknown_type', $c->outcome_unknown_type) == 'HAMA') ? 'selected' : ''}}>HAMA</option>
                                            <option value="LOST TO FOLLOW-UP" {{(old('outcome_unknown_type', $c->outcome_unknown_type) == 'LOST TO FOLLOW-UP') ? 'selected' : ''}}>LOST TO FOLLOW-UP</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="case_classification"><span class="text-danger font-weight-bold">*</span>Case Classification</label>
                                    <select class="form-control" name="case_classification" id="case_classification" required>
                                        <option value="SUSPECT" {{(old('case_classification', $c->case_classification) == 'SUSPECT') ? 'selected' : ''}}>SUSPECT</option>
                                        <option value="PROBABLE" {{(old('case_classification', $c->case_classification) == 'PROBABLE') ? 'selected' : ''}}>PROBABLE</option>
                                        <option value="CONFIRMED" {{(old('case_classification', $c->case_classification) == 'CONFIRMED') ? 'selected' : ''}}>CONFIRMED</option>
                                        <option value="CONTACT" {{(old('case_classification', $c->case_classification) == 'CONTACT') ? 'selected' : ''}}>CONTACT</option>
                                        <option value="DISCARDED" {{(old('case_classification', $c->case_classification) == 'DISCARDED') ? 'selected' : ''}}>DISCARDED</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary btn-block" id="submitBtn">{{($c->exists) ? 'Update' : 'Save'}} (CTRL + S)</button>
            </div>
        </div>
    </div>
</form>

<div class="modal fade bd-example-modal-lg" id="appendix" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Monkeypox Appendix</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <img src="{{asset('MONKEYPOX_APPENDIX.jpg')}}" class="img-fluid" alt="">
        </div>
        </div>
    </div>
</div>

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

    $('#have_cutaneous_rash').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'Y') {
            $('#div_have_cutaneous_rash').removeClass('d-none');
            $('#have_cutaneous_rash_date').prop('required', true);
        }
        else {
            $('#div_have_cutaneous_rash').addClass('d-none');
            $('#have_cutaneous_rash_date').prop('required', false);
        }
    }).trigger('change');

    $('#have_fever').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'Y') {
            $('#div_have_fever').removeClass('d-none');
            $('#have_fever_date').prop('required', true);
            $('#have_fever_days_duration').prop('required', true);
        }
        else {
            $('#div_have_fever').addClass('d-none');
            $('#have_fever_date').prop('required', false);
            $('#have_fever_days_duration').prop('required', false);
        }
    }).trigger('change');

    $('#health_status').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'ACTIVE') {
            $('#div_health_status_date_discharged').addClass('d-none');
            $('#health_status_date_discharged').prop('required', false);
        }
        else if($(this).val() == 'DISCHARGED') {
            $('#div_health_status_date_discharged').removeClass('d-none');
            $('#health_status_date_discharged').prop('required', true);
        }
    }).trigger('change');

    $('#test_npsops').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'Y') {
            $('#test_npsops_date_collected').prop('disabled', false);
            $('#test_npsops_laboratory').prop('disabled', false);
            $('#test_npsops_result').prop('disabled', false);

            $('#test_npsops_date_collected').prop('required', true);
            $('#test_npsops_laboratory').prop('required', true);
            $('#test_npsops_result').prop('required', true);
        }
        else {
            $('#test_npsops_date_collected').prop('disabled', true);
            $('#test_npsops_laboratory').prop('disabled', true);
            $('#test_npsops_result').prop('disabled', true);

            $('#test_npsops_date_collected').prop('required', false);
            $('#test_npsops_laboratory').prop('required', false);
            $('#test_npsops_result').prop('required', false);
        }
    }).trigger('change');

    $('#test_npsops_result').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'PENDING') {
            $('#test_npsops_date_released').prop('disabled', true);
            $('#test_npsops_date_released').prop('required', false);
        }
        else {
            $('#test_npsops_date_released').prop('disabled', false);
            $('#test_npsops_date_released').prop('required', true);
        }
    }).trigger('change');

    $('#test_lesionfluid').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'Y') {
            $('#test_lesionfluid_date_collected').prop('disabled', false);
            $('#test_lesionfluid_laboratory').prop('disabled', false);
            $('#test_lesionfluid_result').prop('disabled', false);

            $('#test_npsops_date_collected').prop('required', true);
            $('#test_lesionfluid_laboratory').prop('required', true);
            $('#test_lesionfluid_result').prop('required', true);
        }
        else {
            $('#test_lesionfluid_date_collected').prop('disabled', true);
            $('#test_lesionfluid_laboratory').prop('disabled', true);
            $('#test_lesionfluid_result').prop('disabled', true);

            $('#test_lesionfluid_date_collected').prop('required', false);
            $('#test_lesionfluid_laboratory').prop('required', false);
            $('#test_lesionfluid_result').prop('required', false);
        }
    }).trigger('change');

    $('#test_lesionfluid_result').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'PENDING') {
            $('#test_lesionfluid_date_released').prop('disabled', true);
            $('#test_lesionfluid_date_released').prop('required', false);
        }
        else {
            $('#test_lesionfluid_date_released').prop('disabled', false);
            $('#test_lesionfluid_date_released').prop('required', true);
        }
    }).trigger('change');

    $('#test_lesionroof').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'Y') {
            $('#test_lesionroof_date_collected').prop('disabled', false);
            $('#test_lesionroof_laboratory').prop('disabled', false);
            $('#test_lesionroof_result').prop('disabled', false);

            $('#test_lesionroof_date_collected').prop('required', true);
            $('#test_lesionroof_laboratory').prop('required', true);
            $('#test_lesionroof_result').prop('required', true);
        }
        else {
            $('#test_lesionroof_date_collected').prop('disabled', true);
            $('#test_lesionroof_laboratory').prop('disabled', true);
            $('#test_lesionroof_result').prop('disabled', true);

            $('#test_lesionroof_date_collected').prop('required', false);
            $('#test_lesionroof_laboratory').prop('required', false);
            $('#test_lesionroof_result').prop('required', false);
        }
    }).trigger('change');

    $('#test_lesionroof_result').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'PENDING') {
            $('#test_lesionroof_date_released').prop('disabled', true);
            $('#test_lesionroof_date_released').prop('required', false);
        }
        else {
            $('#test_lesionroof_date_released').prop('disabled', false);
            $('#test_lesionroof_date_released').prop('required', true);
        }
    }).trigger('change');

    $('#test_lesioncrust').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'Y') {
            $('#test_lesioncrust_date_collected').prop('disabled', false);
            $('#test_lesioncrust_laboratory').prop('disabled', false);
            $('#test_lesioncrust_result').prop('disabled', false);

            $('#test_lesioncrust_date_collected').prop('required', true);
            $('#test_lesioncrust_laboratory').prop('required', true);
            $('#test_lesioncrust_result').prop('required', true);
        }
        else {
            $('#test_lesioncrust_date_collected').prop('disabled', true);
            $('#test_lesioncrust_laboratory').prop('disabled', true);
            $('#test_lesioncrust_result').prop('disabled', true);

            $('#test_lesioncrust_date_collected').prop('required', false);
            $('#test_lesioncrust_laboratory').prop('required', false);
            $('#test_lesioncrust_result').prop('required', false);
        }
    }).trigger('change');

    $('#test_lesioncrust_result').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'PENDING') {
            $('#test_lesioncrust_date_released').prop('disabled', true);
            $('#test_lesioncrust_date_released').prop('required', false);
        }
        else {
            $('#test_lesioncrust_date_released').prop('disabled', false);
            $('#test_lesioncrust_date_released').prop('required', true);
        }
    }).trigger('change');

    $('#test_serum').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'Y') {
            $('#test_serum_date_collected').prop('disabled', false);
            $('#test_serum_laboratory').prop('disabled', false);
            $('#test_serum_result').prop('disabled', false);

            $('#test_serum_date_collected').prop('required', true);
            $('#test_serum_laboratory').prop('required', true);
            $('#test_serum_result').prop('required', true);
        }
        else {
            $('#test_serum_date_collected').prop('disabled', true);
            $('#test_serum_laboratory').prop('disabled', true);
            $('#test_serum_result').prop('disabled', true);

            $('#test_serum_date_collected').prop('required', false);
            $('#test_serum_laboratory').prop('required', false);
            $('#test_serum_result').prop('required', false);
        }
    }).trigger('change');

    $('#test_serum_result').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'PENDING') {
            $('#test_serum_date_released').prop('disabled', true);
            $('#test_serum_date_released').prop('required', false);
        }
        else {
            $('#test_serum_date_released').prop('disabled', false);
            $('#test_serum_date_released').prop('required', true);
        }
    }).trigger('change');

    $('#history1_yn').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'Y') {
            $('#div_history1').removeClass('d-none');
            $('#history1_specify').prop('required', true);
            $('#history1_date_travel').prop('required', true);
            $('#history1_flightno').prop('required', true);
            $('#history1_date_arrival').prop('required', true);
            $('#history1_pointandexitentry').prop('required', true);
        }
        else {
            $('#div_history1').addClass('d-none');
            $('#history1_specify').prop('required', false);
            $('#history1_date_travel').prop('required', false);
            $('#history1_flightno').prop('required', false);
            $('#history1_date_arrival').prop('required', false);
            $('#history1_pointandexitentry').prop('required', false);
        }
    }).trigger('change');

    $('#history2_yn').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'Y') {
            $('#div_history2').removeClass('d-none');
            $('#history2_specify').prop('required', true);
            $('#history2_date_travel').prop('required', true);
            $('#history2_flightno').prop('required', true);
            $('#history2_date_arrival').prop('required', true);
            $('#history2_pointandexitentry').prop('required', true);
        }
        else {
            $('#div_history2').addClass('d-none');
            $('#history2_specify').prop('required', false);
            $('#history2_date_travel').prop('required', false);
            $('#history2_flightno').prop('required', false);
            $('#history2_date_arrival').prop('required', false);
            $('#history2_pointandexitentry').prop('required', false);
        }
    }).trigger('change');

    $('#history4_yn').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'Y') {
            $('#div_history4').removeClass('d-none');
            $('#history4_typeofanimal').prop('required', true);
            $('#history4_firstexposure').prop('required', true);
            $('#history4_lastexposure').prop('required', true);
            $('#history4_type').prop('required', true);
        }
        else {
            $('#div_history4').addClass('d-none');
            $('#history4_typeofanimal').prop('required', false);
            $('#history4_firstexposure').prop('required', false);
            $('#history4_lastexposure').prop('required', false);
            $('#history4_type').prop('required', false);
        }
    }).trigger('change');

    $('#ifhashistory_blooddonation_transfusion').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'DONOR' || $(this).val() == 'RECIPIENT') {
            $('#blooddono_div').removeClass('d-none');
            
            $('#ifhashistory_blooddonation_transfusion').prop('required', true);
            $('#ifhashistory_blooddonation_transfusion_place').prop('required', true);
            $('#ifhashistory_blooddonation_transfusion_date').prop('required', true);
        }
        else {
            $('#blooddono_div').addClass('d-none');

            $('#ifhashistory_blooddonation_transfusion').prop('required', false);
            $('#ifhashistory_blooddonation_transfusion_place').prop('required', false);
            $('#ifhashistory_blooddonation_transfusion_date').prop('required', false);
        }
    }).trigger('change');

    $('#outcome').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'RECOVERED') {
            $('#div_outcome_date_recovered').removeClass('d-none');
            $('#div_outcome_date_died').addClass('d-none');
            $('#div_outcome_unknown_type').addClass('d-none');

            $('#outcome_unknown_type').prop('required', false);
            $('#outcome_date_recovered').prop('required', true);
            $('#outcome_date_died').prop('required', false);
            $('#outcome_causeofdeath').prop('required', false);
        }
        else if($(this).val() == 'DIED') {
            $('#div_outcome_date_recovered').addClass('d-none');
            $('#div_outcome_date_died').removeClass('d-none');
            $('#div_outcome_unknown_type').addClass('d-none');

            $('#outcome_unknown_type').prop('required', false);
            $('#outcome_date_recovered').prop('required', false);
            $('#outcome_date_died').prop('required', true);
            $('#outcome_causeofdeath').prop('required', true);
        }
        else if($(this).val() == 'UNKNOWN') {
            $('#div_outcome_date_recovered').addClass('d-none');
            $('#div_outcome_date_died').addClass('d-none');
            $('#div_outcome_unknown_type').removeClass('d-none');

            $('#outcome_unknown_type').prop('required', true);
            $('#outcome_date_recovered').prop('required', false);
            $('#outcome_date_died').prop('required', false);
            $('#outcome_causeofdeath').prop('required', false);
        }
        else {
            $('#div_outcome_date_recovered').addClass('d-none');
            $('#div_outcome_date_died').addClass('d-none');
            $('#div_outcome_unknown_type').addClass('d-none');

            $('#outcome_unknown_type').prop('required', false);
            $('#outcome_date_recovered').prop('required', false);
            $('#outcome_date_died').prop('required', false);
            $('#outcome_causeofdeath').prop('required', false);
        }
    }).trigger('change');

    $('#history6_yn').change(function (e) { 
        e.preventDefault();
        
        if($(this).val() == 'Y') {
            $('#div_history6').removeClass('d-none');
        }
        else {
            $('#div_history6').addClass('d-none');
        }
    }).trigger('change');

    $('#history6_mtm').change(function (e) { 
        e.preventDefault();
        
        if($(this).val() == 'Y') {
            $("#history6_mtm_nosp").prop('required', true);
            $("#history6_mtm_nosp").prop('disabled', false);
        }
        else {
            $("#history6_mtm_nosp").prop('required', false);
            $("#history6_mtm_nosp").prop('disabled', true);
        }
    }).trigger('change');

    $('#history6_mtf').change(function (e) { 
        e.preventDefault();
        
        if($(this).val() == 'Y') {
            $("#history6_mtf_nosp").prop('required', true);
            $("#history6_mtf_nosp").prop('disabled', false);
        }
        else {
            $("#history6_mtf_nosp").prop('required', false);
            $("#history6_mtf_nosp").prop('disabled', true);
        }
    }).trigger('change');

    $('#history6_uknown').change(function (e) { 
        e.preventDefault();
        
        if($(this).val() == 'Y') {
            $("#history6_uknown_nosp").prop('required', true);
            $("#history6_uknown_nosp").prop('disabled', false);
        }
        else {
            $("#history6_uknown_nosp").prop('required', false);
            $("#history6_uknown_nosp").prop('disabled', true);
        }
    }).trigger('change');

    $('#history4_type').change(function (e) { 
        e.preventDefault();
        if($(this).val().includes('Others')) {
            $('#div_history4_type_others').removeClass('d-none');
            $('#history4_type_others').prop('required', true);
        }
        else {
            $('#div_history4_type_others').addClass('d-none');
            $('#history4_type_others').prop('required', false);
        }
    }).trigger('change');

    $('#history9_choice').change(function (e) { 
        e.preventDefault();
        
        if($(this).val() == 'YES, TO ANOTHER COUNTRY') {
            $('#history9_choice_div').removeClass('d-none');
            $('#history9_choice_othercountry').prop('required', true);
        }
        else {
            $('#history9_choice_div').addClass('d-none');
            $('#history9_choice_othercountry').prop('required', false);
        }
    }).trigger('change');

    $('#symptoms_list').change(function (e) { 
        e.preventDefault();

        if($(this).val().includes('LYMPHADENOPATHY')) {
            $('#div_lymp').removeClass('d-none');
            $('#symptoms_lymphadenopathy_localization').prop('required', true);
        }
        else {
            $('#div_lymp').addClass('d-none');
            $('#symptoms_lymphadenopathy_localization').prop('required', false);
        }
    }).trigger('change');

    $('#history3_yn').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'Y') {
            $('#div_history3').removeClass('d-none');
        }
        else {
            $('#div_history3').addClass('d-none');
        }
    }).trigger('change');
</script>
@endsection