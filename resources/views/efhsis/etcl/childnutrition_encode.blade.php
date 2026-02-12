@extends('layouts.app')

@section('content')
@if($mode == 'EDIT')
<form action="{{route('etcl_childnutrition_update', $d->id)}}" method="POST">
    @php
    $age_in_months = $d->patient->getAgeInMonths();
    $age_in_days = $d->patient->getAgeInDays();
    @endphp
@else
<form action="{{route('etcl_childnutrition_store', $patient->id)}}" method="POST">
    @php
    $age_in_months = $patient->getAgeInMonths();
    $age_in_days = $patient->getAgeInDays();
    @endphp
@endif
@csrf
<input type="hidden" name="request_uuid" value="{{Str::uuid()}}">
<div class="container">
    <div class="card">
        <div class="card-header">
            @if($mode == 'EDIT')
            <b>Edit Child Nutrition (ID: {{ $d->id }})</b>
            @else
            <b>New Child Nutrition</b>
            @endif
        </div>
        <div class="card-body">
            
            @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}}" role="alert">
                {{session('msg')}}
                </div>
            @endif

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="registration_date"><b class="text-danger">*</b>Date of Registration</label>
                        <input type="date" class="form-control" name="registration_date" id="registration_date" value="{{old('registration_date', $d->registration_date)}}" min="{{ ($mode == 'EDIT') ? Carbon\Carbon::parse($d->registration_date)->subYears(10)->format('Y-01-01') : date('Y-01-01', strtotime('-10 Years')) }}" max="{{date('Y-m-d')}}" {{($mode == 'EDIT') ? (auth()->user()->isAdminEtcl() ? '' : 'disabled') : 'required'}}>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                      <label for=""><b class="text-danger">*</b>Family Serial No.</label>
                      <input type="text" class="form-control" value="{{ ($mode == 'EDIT') ? $d->patient->inhouseFamilySerials->inhouse_familyserialno ?? 'N/A' : $patient->inhouseFamilySerials->inhouse_familyserialno ?? 'N/A' }}" readonly>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label for=""><b class="text-danger">*</b>Name of Child / Age</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" value="{{ ($mode == 'EDIT') ? $d->patient->getName().' / '.$d->patient->getAge() : $patient->getName().' / '.$patient->getAge() }}" readonly>
                        <div class="input-group-append">
                          <a class="btn btn-outline-primary" href="{{ route('syndromic_viewPatient', [($mode == 'EDIT') ? $d->patient->id : $patient->id]) }}">View Patient Profile</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for=""><b class="text-danger">*</b>Sex</label>
                                <input type="text" class="form-control" value="{{ ($mode == 'EDIT') ?  $d->patient->gender : $patient->gender }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for=""><b class="text-danger">*</b>Date of Birth</label>
                                <input type="text" class="form-control" value="{{ ($mode == 'EDIT') ? Carbon\Carbon::parse($d->patient->bdate)->format('m/d/Y') : Carbon\Carbon::parse($patient->bdate)->format('m/d/Y') }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="mother_name"><b class="text-danger">*</b>Name of Mother</label>
                        <input type="text" class="form-control" name="mother_name" id="mother_name" value="{{ ($mode == 'EDIT') ? $d->patient->mother_name : $patient->mother_name }}" style="text-transform: uppercase" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for=""><b class="text-danger">*</b>Complete Address</label>
                        <textarea class="form-control" rows="3" disabled>{{ ($mode == 'EDIT') ? $d->patient->getFullAddress() : $patient->getFullAddress() }}</textarea>
                    </div>
                </div>
            </div>
            <hr>
            <div class="card mt-3">
                <div class="card-header"><b>Newborn (0-28 days old)</b></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                              <label for="length_atbirth">Length at Birth (cm)</label>
                              <input type="number" class="form-control" name="length_atbirth" id="length_atbirth" step="0.1" value="{{old('length_atbirth', $d->length_atbirth)}}" min="1" max="900">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="weight_atbirth">Weight at Birth (kg)</label>
                                <input type="number" class="form-control" name="weight_atbirth" id="weight_atbirth" step="0.1" value="{{old('weight_atbirth', $d->weight_atbirth)}}" min="1" max="900">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                              <label for="breastfeeding">
                                <div><b class="text-danger">*</b>Initiated breastfeeding within 1 hour after birth</div>
                                <div><small><b>4 time bound interventions</b></small></div>
                                <div>
                                    <small>
                                        <ul>
                                            <li>immediate and thorough drying;</li>
                                            <li>early skin-to-skin contact;</li>
                                            <li>properly timed cord clamping and cutting; and</li>
                                            <li>non-separation of the newborn from the mother</li>
                                        </ul>
                                    </small>
                                </div>
                              </label>
                              <input type="date" class="form-control" name="breastfeeding" id="breastfeeding" max="{{date('Y-m-d')}}" value="{{old('breastfeeding', $d->breastfeeding)}}" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($age_in_months >= 1)
            <div class="card mt-3">
                <div class="card-header"><b>Newborn (1-3 Months old)</b></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="length_atnutrition2">Length</label>
                                <input type="number" class="form-control" name="length_atnutrition2" id="length_atnutrition2" step="0.1" value="{{old('length_atnutrition2', $d->length_atnutrition2)}}" min="1" max="900">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="weight_atnutrition2">Weight</label>
                                <input type="number" class="form-control" name="weight_atnutrition2" id="weight_atnutrition2" step="0.1" value="{{old('weight_atnutrition2', $d->weight_atnutrition2)}}" min="1" max="900">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="nutrition2_date">Date Taken</label>
                                <input type="date" class="form-control" name="nutrition2_date" id="nutrition2_date" max="{{date('Y-m-d')}}" value="{{old('nutrition2_date', $d->nutrition2_date)}}">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="exclusive_breastfeeding1">Exclusive Breastfeeding (1 ½ mos.)</label>
                                <select class="form-control" name="exclusive_breastfeeding1" id="exclusive_breastfeeding1">
                                  <option value="" disabled {{ old('exclusive_breastfeeding1', $d->exclusive_breastfeeding1) ? '' : 'selected' }}>Choose...</option>
                                  <option value="Y" {{ old('exclusive_breastfeeding1', $d->exclusive_breastfeeding1) == 'Y' ? 'selected' : '' }}>Yes</option>
                                  <option value="N" {{ old('exclusive_breastfeeding1', $d->exclusive_breastfeeding1) == 'N' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="exclusive_breastfeeding2">Exclusive Breastfeeding (2 ½ mos.)</label>
                                <select class="form-control" name="exclusive_breastfeeding2" id="exclusive_breastfeeding2" {{ ($age_in_months < 2) ? 'disabled' : '' }}>
                                  <option value="" disabled {{ old('exclusive_breastfeeding2', $d->exclusive_breastfeeding2) ? '' : 'selected' }}>Choose...</option>
                                  <option value="Y" {{ old('exclusive_breastfeeding2', $d->exclusive_breastfeeding2) == 'Y' ? 'selected' : '' }}>Yes</option>
                                  <option value="N" {{ old('exclusive_breastfeeding2', $d->exclusive_breastfeeding2) == 'N' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="exclusive_breastfeeding3">Exclusive Breastfeeding (3 ½ mos.)</label>
                                <select class="form-control" name="exclusive_breastfeeding3" id="exclusive_breastfeeding3" {{ ($age_in_months < 3) ? 'disabled' : '' }}>
                                  <option value="" disabled {{ old('exclusive_breastfeeding3', $d->exclusive_breastfeeding3) ? '' : 'selected' }}>Choose...</option>
                                  <option value="Y" {{ old('exclusive_breastfeeding3', $d->exclusive_breastfeeding3) == 'Y' ? 'selected' : '' }}>Yes</option>
                                  <option value="N" {{ old('exclusive_breastfeeding3', $d->exclusive_breastfeeding3) == 'N' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="card mt-3">
                <div class="card-header"><b>Newborn (1-3 Months old)</b></div>
                <div class="card-body">
                    <div class="alert alert-info" role="alert">
                        This section will be available once the child reaches 1 month old.
                    </div>
                </div>
            </div>
            @endif

            <div class="card mt-3 d-none" id="lowbw_div">
                <div class="card-header"><b>Low birth weight given Iron</b></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="lb_iron1">1 mos</label>
                                <input type="date" class="form-control" name="lb_iron1" id="lb_iron1" max="{{date('Y-m-d')}}" value="{{old('lb_iron1', $d->lb_iron1)}}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="lb_iron2">2 mos</label>
                                <input type="date" class="form-control" name="lb_iron2" id="lb_iron2" max="{{date('Y-m-d')}}" value="{{old('lb_iron2', $d->lb_iron2)}}" {{ ($age_in_months < 2) ? 'disabled' : '' }}>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                              <label for="lb_iron3">3 mos</label>
                              <input type="date" class="form-control" name="lb_iron3" id="lb_iron3" max="{{date('Y-m-d')}}" value="{{old('lb_iron3', $d->lb_iron3)}}" {{ ($age_in_months < 3) ? 'disabled' : '' }}>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($age_in_months >= 6)
            <div class="card mt-3">
                <div class="card-header"><b>Newborn (6-11 Months old)</b></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="length_atnutrition3">Length (cm)</label>
                                <input type="number" class="form-control" name="length_atnutrition3" id="length_atnutrition3" step="0.1" value="{{old('length_atnutrition3', $d->length_atnutrition3)}}" min="1" max="900">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="weight_atnutrition3">Weight (kg)</label>
                                <input type="number" class="form-control" name="weight_atnutrition3" id="weight_atnutrition3" step="0.1" value="{{old('weight_atnutrition3', $d->weight_atnutrition3)}}" min="1" max="900">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="nutrition3_date">Date Taken</label>
                                <input type="date" class="form-control" name="nutrition3_date" id="nutrition3_date" max="{{date('Y-m-d')}}" value="{{old('nutrition3_date', $d->nutrition3_date)}}">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exclusive_breastfeeding_4">Exclusive Breastfed up to 6 months</label>
                                <select class="form-control" name="exclusive_breastfeeding_4" id="exclusive_breastfeeding_4">
                                  <option value="" disabled {{ old('exclusive_breastfeeding_4', $d->exclusive_breastfeeding_4) ? '' : 'selected' }}>Choose...</option>
                                  <option value="Y" {{ old('exclusive_breastfeeding_4', $d->exclusive_breastfeeding_4) == 'Y' ? 'selected' : '' }}>Yes</option>
                                  <option value="N" {{ old('exclusive_breastfeeding_4', $d->exclusive_breastfeeding_4) == 'N' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="complementary_feeding">Introduction of Complementary Feeding at 6 months old</label>
                                <select class="form-control" name="complementary_feeding" id="complementary_feeding">
                                  <option value="" disabled {{ old('complementary_feeding', $d->complementary_feeding) ? '' : 'selected' }}>Choose...</option>
                                  <option value="Y" {{ old('complementary_feeding', $d->complementary_feeding) == 'Y' ? 'selected' : '' }}>Yes</option>
                                  <option value="N" {{ old('complementary_feeding', $d->complementary_feeding) == 'N' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                            <div class="form-group d-none" id="cf_div">
                                <label for="cf_type"><b class="text-danger">*</b></label>
                                <select class="form-control" name="cf_type" id="cf_type">
                                  <option value="" disabled {{ old('cf_type', $d->cf_type) ? '' : 'selected' }}>Choose...</option>
                                  <option value="1" {{ old('cf_type', $d->cf_type) === '1' ? 'selected' : '' }}>1 - With continued breastfeeding</option>
                                  <option value="2" {{ old('cf_type', $d->cf_type) === '2' ? 'selected' : '' }}>2 - No longer breastfeeding or never breastfed</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="card mt-3">
                <div class="card-header"><b>Newborn (6-11 Months old)</b></div>
                <div class="card-body">
                    <div class="alert alert-info" role="alert">
                        This section will be available once the child reaches 6 months old.
                    </div>
                </div>
            </div>
            @endif

            <div class="card mt-3">
                <div class="card-header"><b>Supplementation</b></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="vita1">
                                    <div>Vitamin A</div>
                                    <div>6-11 months (100,000 IU)</div>
                                </label>
                                <input type="date" class="form-control" name="vita1" id="vita1" max="{{date('Y-m-d')}}" value="{{old('vita1', $d->vita1)}}" {{ ($age_in_months < 6) ? 'disabled' : '' }}>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="vita2">
                                    <div>Vitamin A</div>
                                    <div>12-59 months (200,000 IU)</div>
                                    <div>1st Dose</div>
                                </label>
                                <input type="date" class="form-control" name="vita2" id="vita2" max="{{date('Y-m-d')}}" value="{{old('vita2', $d->vita2)}}" {{ ($age_in_months < 12) ? 'disabled' : '' }}>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="vita3">
                                    <div>Vitamin A</div>
                                    <div>12-59 months (200,000 IU)</div>
                                    <div>2nd Dose</div>
                                </label>
                              <input type="date" class="form-control" name="vita3" id="vita3" max="{{date('Y-m-d')}}" value="{{old('vita3', $d->vita3)}}" {{ ($age_in_months < 12) ? 'disabled' : '' }}>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mnp1">
                                            <div>MNP</div>
                                            <div>6-11 months</div>
                                            <div>90 sachets over a period of 6 months</div>
                                        </label>
                                      <input type="date" class="form-control" name="mnp1" id="mnp1" max="{{date('Y-m-d')}}" value="{{old('mnp1', $d->mnp1)}}" {{ ($age_in_months < 6) ? 'disabled' : '' }}>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mnp2">
                                            <div>MNP</div>
                                            <div>12-23 months (200,000 IU)</div>
                                            <div>90 sachets every 6 months for a total of 180 sachets in a year</div>
                                        </label>
                                      <input type="date" class="form-control" name="mnp2" id="mnp2" max="{{date('Y-m-d')}}" value="{{old('mnp2', $d->mnp2)}}" {{ ($age_in_months < 12) ? 'disabled' : '' }}>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="lns1">
                                            <div>LNS-SQ</div>
                                            <div>6-11 months</div>
                                            <div>1 sachet per day for 120 days</div>
                                        </label>
                                      <input type="date" class="form-control" name="lns1" id="lns1" max="{{date('Y-m-d')}}" value="{{old('lns1', $d->lns1)}}" {{ ($age_in_months < 6) ? 'disabled' : '' }}>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="lns2">
                                            <div>LNS-SQ</div>
                                            <div>12-23 months</div>
                                            <div>1 sachet per day for 120 days</div>
                                        </label>
                                      <input type="date" class="form-control" name="lns2" id="lns2" max="{{date('Y-m-d')}}" value="{{old('lns2', $d->lns2)}}" {{ ($age_in_months < 12) ? 'disabled' : '' }}>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($age_in_months >= 12)
            <div class="card mt-3">
                <div class="card-header"><b>Newborn (12 Months old)</b></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="length_atnutrition4">Length (cm)</label>
                                <input type="number" class="form-control" name="length_atnutrition4" id="length_atnutrition4" step="0.1" value="{{old('length_atnutrition4', $d->length_atnutrition4)}}" min="1" max="900">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="weight_atnutrition4">Weight (kg)</label>
                                <input type="number" class="form-control" name="weight_atnutrition4" id="weight_atnutrition4" step="0.1" value="{{old('weight_atnutrition4', $d->weight_atnutrition4)}}" min="1" max="900">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="nutrition4_date">Date Taken</label>
                                <input type="date" class="form-control" name="nutrition4_date" id="nutrition4_date" max="{{date('Y-m-d')}}" value="{{old('nutrition4_date', $d->nutrition4_date)}}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="card mt-3">
                <div class="card-header"><b>Newborn (12 Months old)</b></div>
                <div class="card-body">
                    <div class="alert alert-info" role="alert">
                        This section will be available once the child reaches 12 months old.
                    </div>
                </div>
            </div>
            @endif
            
            <div class="card mt-3">
                <div class="card-header"><b>Supplementary Feeding Program (SFP) and Outpatient Therapeutic Care (OTC)</b></div>
                <div class="card-body">
                    <div class="card">
                        <div class="card-header"><b>MAM</b></div>
                        <div class="card-body">
                            <div class="form-group">
                              <label for="mam_identified"><b class="text-danger">*</b>MAM Identified</label>
                              <select class="form-control" name="mam_identified" id="mam_identified" required>
                                <option value="" disabled {{ old('mam_identified', $d->mam_identified) ? '' : 'selected' }}>Choose...</option>
                                <option value="1" {{ old('mam_identified', $d->mam_identified) === '1' ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ old('mam_identified', $d->mam_identified) === '0' ? 'selected' : '' }}>No</option>
                              </select>
                            </div>
                            <div id="mam_div" class="d-none">
                                <div class="form-group">
                                    <label for="mam_enrolled_sfp"><b class="text-danger">*</b>Enrolled to SFP</label>
                                    <select class="form-control" name="mam_enrolled_sfp" id="mam_enrolled_sfp">
                                      <option value="" disabled {{ old('mam_enrolled_sfp', $d->mam_enrolled_sfp) ? '' : 'selected' }}>Choose...</option>
                                      <option value="1" {{ old('mam_enrolled_sfp', $d->mam_enrolled_sfp) === '1' ? 'selected' : '' }}>Yes</option>
                                      <option value="0" {{ old('mam_enrolled_sfp', $d->mam_enrolled_sfp) === '0' ? 'selected' : '' }}>No</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="mam_cured"><b class="text-danger">*</b>Cured</label>
                                    <select class="form-control" name="mam_cured" id="mam_cured">
                                      <option value="" disabled {{ old('mam_cured', $d->mam_cured) ? '' : 'selected' }}>Choose...</option>
                                      <option value="1" {{ old('mam_cured', $d->mam_cured) === '1' ? 'selected' : '' }}>Yes</option>
                                      <option value="0" {{ old('mam_cured', $d->mam_cured) === '0' ? 'selected' : '' }}>No</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="mam_noncured">
                                        <div><b class="text-danger">*</b>Non-cured</div>
                                        <div><small>Defined as not reaching discharge criteria after 4 months in the program as long as all possible Investigations and follow-up have been attempted</small></div>
                                    </label>
                                    <select class="form-control" name="mam_noncured" id="mam_noncured">
                                      <option value="" disabled {{ old('mam_noncured', $d->mam_noncured) ? '' : 'selected' }}>Choose...</option>
                                      <option value="1" {{ old('mam_noncured', $d->mam_noncured) === '1' ? 'selected' : '' }}>Yes</option>
                                      <option value="0" {{ old('mam_noncured', $d->mam_noncured) === '0' ? 'selected' : '' }}>No</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="mam_defaulted"><b class="text-danger">*</b>Defaulted</label>
                                    <select class="form-control" name="mam_defaulted" id="mam_defaulted">
                                      <option value="" disabled {{ old('mam_defaulted', $d->mam_defaulted) ? '' : 'selected' }}>Choose...</option>
                                      <option value="1" {{ old('mam_defaulted', $d->mam_defaulted) === '1' ? 'selected' : '' }}>Yes</option>
                                      <option value="0" {{ old('mam_defaulted', $d->mam_defaulted) === '0' ? 'selected' : '' }}>No</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="mam_died"><b class="text-danger">*</b>Died</label>
                                    <select class="form-control" name="mam_died" id="mam_died">
                                      <option value="" disabled {{ old('mam_died', $d->mam_died) ? '' : 'selected' }}>Choose...</option>
                                      <option value="1" {{ old('mam_died', $d->mam_died) === '1' ? 'selected' : '' }}>Yes</option>
                                      <option value="0" {{ old('mam_died', $d->mam_died) === '0' ? 'selected' : '' }}>No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-3" id="sam_main_div">
                        <div class="card-header"><b>SAM</b></div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="sam_identified"><b class="text-danger">*</b>SAM Identified</label>
                                <select class="form-control" name="sam_identified" id="sam_identified" required>
                                  <option value="" disabled {{ old('sam_identified', $d->sam_identified) ? '' : 'selected' }}>Choose...</option>
                                  <option value="1" {{ old('sam_identified', $d->sam_identified) === '1' ? 'selected' : '' }}>Yes</option>
                                  <option value="0" {{ old('sam_identified', $d->sam_identified) === '0' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                            <div id="sam_div" class="d-none">
                                <div class="form-group">
                                    <label for="sam_complication"><b class="text-danger">*</b>Without complication admitted to OTC</label>
                                    <select class="form-control" name="sam_complication" id="sam_complication">
                                      <option value="" disabled {{ old('sam_complication', $d->sam_complication) ? '' : 'selected' }}>Choose...</option>
                                      <option value="1" {{ old('sam_complication', $d->sam_complication) === '1' ? 'selected' : '' }}>Yes</option>
                                      <option value="0" {{ old('sam_complication', $d->sam_complication) === '0' ? 'selected' : '' }}>No</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="sam_cured"><b class="text-danger">*</b>Cured</label>
                                    <select class="form-control" name="sam_cured" id="sam_cured">
                                      <option value="" disabled {{ old('sam_cured', $d->sam_cured) ? '' : 'selected' }}>Choose...</option>
                                      <option value="1" {{ old('sam_cured', $d->sam_cured) === '1' ? 'selected' : '' }}>Yes</option>
                                      <option value="0" {{ old('sam_cured', $d->sam_cured) === '0' ? 'selected' : '' }}>No</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="sam_noncured">
                                        <div><b class="text-danger">*</b>Non-cured</div>
                                        <div><small>Defined as not reaching discharge criteria after 4 months in the program as long as all possible Investigations and follow-up have been attempted</small></div>
                                    </label>
                                    <select class="form-control" name="sam_noncured" id="sam_noncured">
                                      <option value="" disabled {{ old('sam_noncured', $d->sam_noncured) ? '' : 'selected' }}>Choose...</option>
                                      <option value="1" {{ old('sam_noncured', $d->sam_noncured) === '1' ? 'selected' : '' }}>Yes</option>
                                      <option value="0" {{ old('sam_noncured', $d->sam_noncured) === '0' ? 'selected' : '' }}>No</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="sam_defaulted"><b class="text-danger">*</b>Defaulted</label>
                                    <select class="form-control" name="sam_defaulted" id="sam_defaulted">
                                      <option value="" disabled {{ old('sam_defaulted', $d->sam_defaulted) ? '' : 'selected' }}>Choose...</option>
                                      <option value="1" {{ old('sam_defaulted', $d->sam_defaulted) === '1' ? 'selected' : '' }}>Yes</option>
                                      <option value="0" {{ old('sam_defaulted', $d->sam_defaulted) === '0' ? 'selected' : '' }}>No</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="sam_died"><b class="text-danger">*</b>Died</label>
                                    <select class="form-control" name="sam_died" id="sam_died">
                                      <option value="" disabled {{ old('sam_died', $d->sam_died) ? '' : 'selected' }}>Choose...</option>
                                      <option value="1" {{ old('sam_died', $d->sam_died) === '1' ? 'selected' : '' }}>Yes</option>
                                      <option value="0" {{ old('sam_died', $d->sam_died) === '0' ? 'selected' : '' }}>No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group mt-3">
                <label for="remarks">Remarks/Actions Taken</label>
                <input type="text" class="form-control" name="remarks" id="remarks" value="{{old('remarks', $d->remarks)}}">
            </div>
            <hr>
            <div class="form-group">
                <label for="system_remarks">System Remarks (Optional)</label>
                <textarea class="form-control" name="system_remarks" id="system_remarks" rows="3">{{old('system_remarks', $d->system_remarks)}}</textarea>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" id="submitBtn" class="btn btn-success btn-block" {{($d->is_locked == 'Y') ? 'disabled' : ''}}>
                @if($mode == 'EDIT')
                Update (CTRL + S)
                @else
                Save (CTRL + S)
                @endif
            </button>
        </div>
    </div>
</div>

</form>

<script>
    $(document).ready(function () {
        $('form').on('submit', function () {
            $('#submitBtn')
                .prop('disabled', true)
                .text('Please wait... Do not refresh or close the page.');
        });
    });

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

    $('#weight_atbirth').on('change', function () {
        if (parseFloat($(this).val()) < 2.5) {
            $('#lowbw_div').removeClass('d-none');
        } else {
            $('#lowbw_div').addClass('d-none');
            $('#lb_iron1').val('');
            $('#lb_iron2').val('');
            $('#lb_iron3').val('');
        }
    }).trigger('change');

    $('#lb_iron1').on('change', function () {
        $('#lb_iron2').prop('min', $(this).val());
    }).trigger('change');

    $('#lb_iron2').on('change', function () {
        $('#lb_iron3').prop('min', $(this).val());
    }).trigger('change');

    $('#vita1').on('change', function () {
        $('#vita2').prop('min', $(this).val());
    }).trigger('change');

    $('#vita2').on('change', function () {
        $('#vita3').prop('min', $(this).val());
    }).trigger('change');

    $('#mnp1').on('change', function () {
        $('#mnp2').prop('min', $(this).val());
    }).trigger('change');

    $('#lns1').on('change', function () {
        $('#lns2').prop('min', $(this).val());
    }).trigger('change');

    @if($age_in_months >= 6)
    $('#complementary_feeding').change(function (e) { 
        e.preventDefault();
        $('#cf_div').addClass('d-none');
        $('#cf_type').prop('required', false);

        if ($(this).val() == 'Y') {
            $('#cf_div').removeClass('d-none');
            $('#cf_type').prop('required', true);
        }
    }).trigger('change');
    @endif

    $('#mam_identified').change(function (e) { 
        e.preventDefault();
        $('#mam_div').addClass('d-none');
        $('#mam_enrolled_sfp').prop('required', false);
        $('#mam_cured').prop('required', false);
        $('#mam_noncured').prop('required', false);
        $('#mam_defaulted').prop('required', false);
        $('#mam_died').prop('required', false);

        if ($(this).val() == '1') {
            $('#mam_div').removeClass('d-none');
            $('#mam_enrolled_sfp').prop('required', true);
            $('#mam_cured').prop('required', true);
            $('#mam_noncured').prop('required', true);
            $('#mam_defaulted').prop('required', true);
            $('#mam_died').prop('required', true);
        }
    }).trigger('change');

    $('#mam_died').change(function (e) { 
        e.preventDefault();
        $('#sam_main_div').removeClass('d-none');

        if ($(this).val() == '1') {
            $('#sam_main_div').addClass('d-none');
            $('#sam_identified').prop('required', false);
            $('#sam_identified').val('').trigger('change');
        }
    }).trigger('change');

    $('#sam_identified').change(function (e) { 
        e.preventDefault();
        $('#sam_div').addClass('d-none');
        $('#sam_complication').prop('required', false);
        $('#sam_cured').prop('required', false);
        $('#sam_noncured').prop('required', false);
        $('#sam_defaulted').prop('required', false);
        $('#sam_died').prop('required', false);

        if ($(this).val() == '1') {
            $('#sam_div').removeClass('d-none');
            $('#sam_complication').prop('required', true);
            $('#sam_cured').prop('required', true);
            $('#sam_noncured').prop('required', true);
            $('#sam_defaulted').prop('required', true);
            $('#sam_died').prop('required', true);
        }
    }).trigger('change');
</script>
@endsection