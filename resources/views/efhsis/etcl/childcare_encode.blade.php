@extends('layouts.app')

@section('content')
@if($mode == 'EDIT')
<form action="{{route('etcl_childcare_update', $d->id)}}" method="POST">
@else
<form action="{{route('etcl_childcare_store', $patient->id)}}" method="POST">
@endif
@csrf
<input type="hidden" name="request_uuid" value="{{Str::uuid()}}">
    <div class="container">
        <div class="card">
            <div class="card-header">
                @if($mode == 'EDIT')
                <b>Edit Child Care (ID: {{ $d->id }})</b>
                @else
                <b>New Child Care</b>
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
                            <input type="text" class="form-control" value="{{ ($mode == 'EDIT') ?  $d->patient->inhouseFamilySerials->inhouse_familyserialno ?? 'N/A' : $patient->inhouseFamilySerials->inhouse_familyserialno ?? 'N/A' }}" readonly>
                          </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for=""><b class="text-danger">*</b>Name of Child / Age</label>
                            <input type="text" class="form-control" value="{{ ($mode == 'EDIT') ? $d->patient->getName().' / '.$d->patient->getAge() : $patient->getName().' / '.$patient->getAge() }}" readonly>
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
                            <label for="mother_type"><b class="text-danger">*</b>Mother has Maternal Care Record?</label>
                            <select class="form-control" name="mother_type" id="mother_type" required>
                              <option value="" disabled {{ old('mother_type', $d->mother_type) ? '' : 'selected' }}>Choose...</option>
                              <option value="Y" {{ old('mother_type', $d->mother_type) == 'Y' ? 'selected' : '' }}>Yes</option>
                              <option value="N" {{ old('mother_type', $d->mother_type) == 'N' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div id="maternalcare_div" class="d-none">
                            <input type="hidden" value="{{old('maternalcare_id', $d->maternalcare_id)}}" id="maternalcare_id" name="maternalcare_id">
                            <div class="mb-2"><b class="text-danger">*</b>Link to Maternal Care ID</div>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" value="{{old('display_maternal', ($d->maternalcare) ? $d->maternalcare->patient->getName() : NULL)}}" id="display_maternal" name="display_maternal" readonly>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-primary" id="mother_search_btn" type="button"><i class="fa fa-search" aria-hidden="true"></i></button>
                                </div>
                            </div>
                            <small class="text-muted">Note: CPAB will be automatically checked based on the selected Maternal Care Record of the mother.</small>
                        </div>
                        <div id="mother_name_div" class="d-none">
                            <div class="form-group">
                                <label for="mother_name"><b class="text-danger">*</b>Name of Mother</label>
                                <input type="text" class="form-control" name="mother_name" id="mother_name" value="{{ ($mode == 'EDIT') ? $d->patient->mother_name : $patient->mother_name }}" style="text-transform: uppercase" readonly>
                            </div>
                            <div class="form-group">
                              <label for="cpab_manual"><b class="text-danger">*</b>Child Protected at Birth (CPAB) from neonatal tetanus</label>
                              <select class="form-control" name="cpab_manual" id="cpab_manual">
                                <option value="" disabled {{old('cpab_manual', $d->cpab) ? '' : 'selected'}}>Choose...</option>
                                <option value="0" {{old('cpab_manual', $d->cpab) == '0' ? 'selected' : ''}}>None</option>
                                <option value="1" {{old('cpab_manual', $d->cpab) == '1' ? 'selected' : ''}}>Received at least 2 doses of Tetanus Toxoid (TT)-containing vaccine at least one month prior to delivery</option>
                                <option value="2" {{old('cpab_manual', $d->cpab) == '2' ? 'selected' : ''}}>TT3/Td3 to TT5/Td5 (or TT1/Td1 to TT5/Td5) given to the mother anytime prior to delivery</option>
                              </select>
                            </div>
                            <div class="form-group">
                                <label for="cpab_type"><b class="text-danger">*</b>Specify Last TT/Td Dose</label>
                                <input type="text" class="form-control" name="cpab_type" id="cpab_type" value="{{old('cpab_type', $d->cpab_type)}}" style="text-transform: uppercase">
                            </div>
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
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                          <label for="bcg1">BCG (within 0-28 days)</label>
                          <input type="date" class="form-control" name="bcg1" id="bcg1" value="{{old('bcg1', $d->bcg1)}}" min="{{ ($mode == 'EDIT') ? Carbon\Carbon::parse($d->bcg1)->subYears(2)->format('Y-01-01') : date('Y-01-01', strtotime('-2 Years')) }}"  max="{{date('Y-m-d')}}">
                        </div>
                        <div class="form-group">
                            <label for="bcg1_type">Type</label>
                            <select class="form-control" name="bcg1_type" id="bcg1_type">
                              <option value="" disabled {{old('bcg1_type', $d->bcg1_type) ? '' : 'selected'}}>Choose...</option>
                              <option value="YOUR BHS" {{old('bcg1_type', $d->bcg1_type) == 'YOUR BHS' ? 'selected' : ''}}>{{auth()->user()->tclbhs->facility_name}}</option>
                              <option value="PUBLIC" {{old('bcg1_type', $d->bcg1_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                              <option value="PRIVATE" {{old('bcg1_type', $d->bcg1_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                              <option value="OTHER RHU/BHS" {{old('bcg1_type', $d->bcg1_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="bcg2">BCG (within 29 days to 1 year old)</label>
                            <input type="date" class="form-control" name="bcg2" id="bcg2" value="{{old('bcg2', $d->bcg2)}}" min="{{ ($mode == 'EDIT') ? Carbon\Carbon::parse($d->bcg2)->subYears(2)->format('Y-01-01') : date('Y-01-01', strtotime('-2 Years')) }}" max="{{date('Y-m-d')}}">
                        </div>
                        <div class="form-group">
                            <label for="bcg2_type">Type</label>
                            <select class="form-control" name="bcg2_type" id="bcg2_type">
                              <option value="" disabled {{old('bcg2_type', $d->bcg2_type) ? '' : 'selected'}}>Choose...</option>
                              <option value="YOUR BHS" {{old('bcg2_type', $d->bcg2_type) == 'YOUR BHS' ? 'selected' : ''}}>{{auth()->user()->tclbhs->facility_name}}</option>
                              <option value="PUBLIC" {{old('bcg2_type', $d->bcg2_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                              <option value="PRIVATE" {{old('bcg2_type', $d->bcg2_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                              <option value="OTHER RHU/BHS" {{old('bcg2_type', $d->bcg2_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="hepab1">Hepa B within 24 hours after birth</label>
                            <input type="date" class="form-control" name="hepab1" id="hepab1" value="{{old('hepab1', $d->hepab1)}}" min="{{ ($mode == 'EDIT') ? Carbon\Carbon::parse($d->hepab1)->subYears(2)->format('Y-01-01') : date('Y-01-01', strtotime('-2 Years')) }}" max="{{date('Y-m-d')}}">
                        </div>
                        <div class="form-group">
                            <label for="hepab1_type">Type</label>
                            <select class="form-control" name="hepab1_type" id="hepab1_type">
                              <option value="" disabled {{old('hepab1_type', $d->hepab1_type) ? '' : 'selected'}}>Choose...</option>
                              <option value="YOUR BHS" {{old('hepab1_type', $d->hepab1_type) == 'YOUR BHS' ? 'selected' : ''}}>{{auth()->user()->tclbhs->facility_name}}</option>
                              <option value="PUBLIC" {{old('hepab1_type', $d->hepab1_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                              <option value="PRIVATE" {{old('hepab1_type', $d->hepab1_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                              <option value="OTHER RHU/BHS" {{old('hepab1_type', $d->hepab1_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="hepab2">Hepa B more than 24 hours up to 14 days</label>
                            <input type="date" class="form-control" name="hepab2" id="hepab2" value="{{old('hepab2', $d->hepab2)}}" min="{{ ($mode == 'EDIT') ? Carbon\Carbon::parse($d->hepab2)->subYears(2)->format('Y-01-01') : date('Y-01-01', strtotime('-2 Years')) }}" max="{{date('Y-m-d')}}">
                        </div>
                        <div class="form-group">
                            <label for="hepab2_type">Type</label>
                            <select class="form-control" name="hepab2_type" id="hepab2_type">
                              <option value="" disabled {{old('hepab2_type', $d->hepab2_type) ? '' : 'selected'}}>Choose...</option>
                              <option value="YOUR BHS" {{old('hepab2_type', $d->hepab2_type) == 'YOUR BHS' ? 'selected' : ''}}>{{auth()->user()->tclbhs->facility_name}}</option>
                              <option value="PUBLIC" {{old('hepab2_type', $d->hepab2_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                              <option value="PRIVATE" {{old('hepab2_type', $d->hepab2_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                              <option value="OTHER RHU/BHS" {{old('hepab2_type', $d->hepab2_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                            </select>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="dpt1">DPT-HiB-HepB 1st Dose</label>
                            <input type="date" class="form-control" name="dpt1" id="dpt1" value="{{old('dpt1', $d->dpt1)}}" min="{{ ($mode == 'EDIT') ? Carbon\Carbon::parse($d->dpt1)->subYears(2)->format('Y-01-01') : date('Y-01-01', strtotime('-2 Years')) }}" max="{{date('Y-m-d')}}">
                            <small class="text-muted">1 ½ mos</small>
                        </div>
                        <div class="form-group">
                            <label for="dpt1_type">Type</label>
                            <select class="form-control" name="dpt1_type" id="dpt1_type">
                              <option value="" disabled {{old('dpt1_type', $d->dpt1_type) ? '' : 'selected'}}>Choose...</option>
                              <option value="YOUR BHS" {{old('dpt1_type', $d->dpt1_type) == 'YOUR BHS' ? 'selected' : ''}}>{{auth()->user()->tclbhs->facility_name}}</option>
                              <option value="PUBLIC" {{old('dpt1_type', $d->dpt1_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                              <option value="PRIVATE" {{old('dpt1_type', $d->dpt1_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                              <option value="OTHER RHU/BHS" {{old('dpt1_type', $d->dpt1_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="dpt2">DPT-HiB-HepB 2nd Dose</label>
                            <input type="date" class="form-control" name="dpt2" id="dpt2" value="{{old('dpt2', $d->dpt2)}}" min="{{ ($mode == 'EDIT') ? Carbon\Carbon::parse($d->dpt2)->subYears(2)->format('Y-01-01') : date('Y-01-01', strtotime('-2 Years')) }}" max="{{date('Y-m-d')}}">
                            <small class="text-muted">2 ½ mos</small>
                        </div>
                        <div class="form-group">
                            <label for="dpt2_type">Type</label>
                            <select class="form-control" name="dpt2_type" id="dpt2_type">
                              <option value="" disabled {{old('dpt2_type', $d->dpt2_type) ? '' : 'selected'}}>Choose...</option>
                              <option value="YOUR BHS" {{old('dpt2_type', $d->dpt2_type) == 'YOUR BHS' ? 'selected' : ''}}>{{auth()->user()->tclbhs->facility_name}}</option>
                              <option value="PUBLIC" {{old('dpt2_type', $d->dpt2_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                              <option value="PRIVATE" {{old('dpt2_type', $d->dpt2_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                              <option value="OTHER RHU/BHS" {{old('dpt2_type', $d->dpt2_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="dpt3">DPT-HiB-HepB 3rd Dose</label>
                            <input type="date" class="form-control" name="dpt3" id="dpt3" value="{{old('dpt3', $d->dpt3)}}" min="{{ ($mode == 'EDIT') ? Carbon\Carbon::parse($d->dpt3)->subYears(2)->format('Y-01-01') : date('Y-01-01', strtotime('-2 Years')) }}" max="{{date('Y-m-d')}}">
                            <small class="text-muted">3 ½ mos</small>
                        </div>
                        <div class="form-group">
                            <label for="dpt3_type">Type</label>
                            <select class="form-control" name="dpt3_type" id="dpt3_type">
                              <option value="" disabled {{old('dpt3_type', $d->dpt3_type) ? '' : 'selected'}}>Choose...</option>
                              <option value="YOUR BHS" {{old('dpt3_type', $d->dpt3_type) == 'YOUR BHS' ? 'selected' : ''}}>{{auth()->user()->tclbhs->facility_name}}</option>
                              <option value="PUBLIC" {{old('dpt3_type', $d->dpt3_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                              <option value="PRIVATE" {{old('dpt3_type', $d->dpt3_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                              <option value="OTHER RHU/BHS" {{old('dpt3_type', $d->dpt3_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                            </select>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="opv1">OPV 1st Dose</label>
                            <input type="date" class="form-control" name="opv1" id="opv1" value="{{old('opv1', $d->opv1)}}" min="{{ ($mode == 'EDIT') ? Carbon\Carbon::parse($d->opv1)->subYears(2)->format('Y-01-01') : date('Y-01-01', strtotime('-2 Years')) }}" max="{{date('Y-m-d')}}">
                            <small class="text-muted">1 ½ mos</small>
                        </div>
                        <div class="form-group">
                            <label for="opv1_type">Type</label>
                            <select class="form-control" name="opv1_type" id="opv1_type">
                              <option value="" disabled {{old('opv1_type', $d->opv1_type) ? '' : 'selected'}}>Choose...</option>
                              <option value="YOUR BHS" {{old('opv1_type', $d->opv1_type) == 'YOUR BHS' ? 'selected' : ''}}>{{auth()->user()->tclbhs->facility_name}}</option>
                              <option value="PUBLIC" {{old('opv1_type', $d->opv1_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                              <option value="PRIVATE" {{old('opv1_type', $d->opv1_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                              <option value="OTHER RHU/BHS" {{old('opv1_type', $d->opv1_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="opv2">OPV 2nd Dose</label>
                            <input type="date" class="form-control" name="opv2" id="opv2" value="{{old('opv2', $d->opv2)}}" min="{{ ($mode == 'EDIT') ? Carbon\Carbon::parse($d->opv2)->subYears(2)->format('Y-01-01') : date('Y-01-01', strtotime('-2 Years')) }}" max="{{date('Y-m-d')}}">
                            <small class="text-muted">2 ½ mos</small>
                        </div>
                        <div class="form-group">
                            <label for="opv2_type">Type</label>
                            <select class="form-control" name="opv2_type" id="opv2_type">
                              <option value="" disabled {{old('opv2_type', $d->opv2_type) ? '' : 'selected'}}>Choose...</option>
                              <option value="YOUR BHS" {{old('opv2_type', $d->opv2_type) == 'YOUR BHS' ? 'selected' : ''}}>{{auth()->user()->tclbhs->facility_name}}</option>
                              <option value="PUBLIC" {{old('opv2_type', $d->opv2_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                              <option value="PRIVATE" {{old('opv2_type', $d->opv2_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                              <option value="OTHER RHU/BHS" {{old('opv2_type', $d->opv2_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="opv3">OPV 3rd Dose</label>
                            <input type="date" class="form-control" name="opv3" id="opv3" value="{{old('opv3', $d->opv3)}}" min="{{ ($mode == 'EDIT') ? Carbon\Carbon::parse($d->opv3)->subYears(2)->format('Y-01-01') : date('Y-01-01', strtotime('-2 Years')) }}" max="{{date('Y-m-d')}}">
                            <small class="text-muted">3 ½ mos</small>
                        </div>
                        <div class="form-group">
                            <label for="opv3_type">Type</label>
                            <select class="form-control" name="opv3_type" id="opv3_type">
                              <option value="" disabled {{old('opv3_type', $d->opv3_type) ? '' : 'selected'}}>Choose...</option>
                              <option value="YOUR BHS" {{old('opv3_type', $d->opv3_type) == 'YOUR BHS' ? 'selected' : ''}}>{{auth()->user()->tclbhs->facility_name}}</option>
                              <option value="PUBLIC" {{old('opv3_type', $d->opv3_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                              <option value="PRIVATE" {{old('opv3_type', $d->opv3_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                              <option value="OTHER RHU/BHS" {{old('opv3_type', $d->opv3_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                            </select>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="pcv1">PCV 1st Dose</label>
                            <input type="date" class="form-control" name="pcv1" id="pcv1" value="{{old('pcv1', $d->pcv1)}}" min="{{ ($mode == 'EDIT') ? Carbon\Carbon::parse($d->pcv1)->subYears(2)->format('Y-01-01') : date('Y-01-01', strtotime('-2 Years')) }}" max="{{date('Y-m-d')}}">
                            <small class="text-muted">1 ½ mos</small>
                        </div>
                        <div class="form-group">
                            <label for="pcv1_type">Type</label>
                            <select class="form-control" name="pcv1_type" id="pcv1_type">
                              <option value="" disabled {{old('pcv1_type', $d->pcv1_type) ? '' : 'selected'}}>Choose...</option>
                              <option value="YOUR BHS" {{old('pcv1_type', $d->pcv1_type) == 'YOUR BHS' ? 'selected' : ''}}>{{auth()->user()->tclbhs->facility_name}}</option>
                              <option value="PUBLIC" {{old('pcv1_type', $d->pcv1_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                              <option value="PRIVATE" {{old('pcv1_type', $d->pcv1_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                              <option value="OTHER RHU/BHS" {{old('pcv1_type', $d->pcv1_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="pcv2">PCV 2nd Dose</label>
                            <input type="date" class="form-control" name="pcv2" id="pcv2" value="{{old('pcv2', $d->pcv2)}}" min="{{ ($mode == 'EDIT') ? Carbon\Carbon::parse($d->pcv2)->subYears(2)->format('Y-01-01') : date('Y-01-01', strtotime('-2 Years')) }}" max="{{date('Y-m-d')}}">
                            <small class="text-muted">2 ½ mos</small>
                        </div>
                        <div class="form-group">
                            <label for="pcv2_type">Type</label>
                            <select class="form-control" name="pcv2_type" id="pcv2_type">
                              <option value="" disabled {{old('pcv2_type', $d->pcv2_type) ? '' : 'selected'}}>Choose...</option>
                              <option value="YOUR BHS" {{old('pcv2_type', $d->pcv2_type) == 'YOUR BHS' ? 'selected' : ''}}>{{auth()->user()->tclbhs->facility_name}}</option>
                              <option value="PUBLIC" {{old('pcv2_type', $d->pcv2_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                              <option value="PRIVATE" {{old('pcv2_type', $d->pcv2_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                              <option value="OTHER RHU/BHS" {{old('pcv2_type', $d->pcv2_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="pcv3">PCV 3rd Dose</label>
                            <input type="date" class="form-control" name="pcv3" id="pcv3" value="{{old('pcv3', $d->pcv3)}}" min="{{ ($mode == 'EDIT') ? Carbon\Carbon::parse($d->pcv3)->subYears(2)->format('Y-01-01') : date('Y-01-01', strtotime('-2 Years')) }}" max="{{date('Y-m-d')}}">
                            <small class="text-muted">3 ½ mos</small>
                        </div>
                        <div class="form-group">
                            <label for="pcv3_type">Type</label>
                            <select class="form-control" name="pcv3_type" id="pcv3_type">
                              <option value="" disabled {{old('pcv3_type', $d->pcv3_type) ? '' : 'selected'}}>Choose...</option>
                              <option value="YOUR BHS" {{old('pcv3_type', $d->pcv3_type) == 'YOUR BHS' ? 'selected' : ''}}>{{auth()->user()->tclbhs->facility_name}}</option>
                              <option value="PUBLIC" {{old('pcv3_type', $d->pcv3_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                              <option value="PRIVATE" {{old('pcv3_type', $d->pcv3_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                              <option value="OTHER RHU/BHS" {{old('pcv3_type', $d->pcv3_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                            </select>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="ipv1">IPV 1st Dose</label>
                            <input type="date" class="form-control" name="ipv1" id="ipv1" value="{{old('ipv1', $d->ipv1)}}" min="{{ ($mode == 'EDIT') ? Carbon\Carbon::parse($d->ipv1)->subYears(2)->format('Y-01-01') : date('Y-01-01', strtotime('-2 Years')) }}" max="{{date('Y-m-d')}}">
                            <small class="text-muted">3 ½ mos</small>
                        </div>
                        <div class="form-group">
                            <label for="ipv1_type">Type</label>
                            <select class="form-control" name="ipv1_type" id="ipv1_type">
                              <option value="" disabled {{old('ipv1_type', $d->ipv1_type) ? '' : 'selected'}}>Choose...</option>
                              <option value="YOUR BHS" {{old('ipv1_type', $d->ipv1_type) == 'YOUR BHS' ? 'selected' : ''}}>{{auth()->user()->tclbhs->facility_name}}</option>
                              <option value="PUBLIC" {{old('ipv1_type', $d->ipv1_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                              <option value="PRIVATE" {{old('ipv1_type', $d->ipv1_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                              <option value="OTHER RHU/BHS" {{old('ipv1_type', $d->ipv1_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="ipv2">IPV 2nd Dose</label>
                            <input type="date" class="form-control" name="ipv2" id="ipv2" value="{{old('ipv2', $d->ipv2)}}" min="{{ ($mode == 'EDIT') ? Carbon\Carbon::parse($d->ipv2)->subYears(2)->format('Y-01-01') : date('Y-01-01', strtotime('-2 Years')) }}" max="{{date('Y-m-d')}}">
                            <small class="text-muted">9 mos</small>
                        </div>
                        <div class="form-group">
                            <label for="ipv2_type">Type</label>
                            <select class="form-control" name="ipv2_type" id="ipv2_type">
                              <option value="" disabled {{old('ipv2_type', $d->ipv2_type) ? '' : 'selected'}}>Choose...</option>
                              <option value="YOUR BHS" {{old('ipv2_type', $d->ipv2_type) == 'YOUR BHS' ? 'selected' : ''}}>{{auth()->user()->tclbhs->facility_name}}</option>
                              <option value="PUBLIC" {{old('ipv2_type', $d->ipv2_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                              <option value="PRIVATE" {{old('ipv2_type', $d->ipv2_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                              <option value="OTHER RHU/BHS" {{old('ipv2_type', $d->ipv2_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                            </select>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="mmr1">MMR 1st Dose</label>
                            <input type="date" class="form-control" name="mmr1" id="mmr1" value="{{old('mmr1', $d->mmr1)}}" min="{{ ($mode == 'EDIT') ? Carbon\Carbon::parse($d->mmr1)->subYears(2)->format('Y-01-01') : date('Y-01-01', strtotime('-2 Years')) }}" max="{{date('Y-m-d')}}">
                            <small class="text-muted">9  mos</small>
                        </div>
                        <div class="form-group">
                            <label for="mmr1_type">Type</label>
                            <select class="form-control" name="mmr1_type" id="mmr1_type">
                              <option value="" disabled {{old('mmr1_type', $d->mmr1_type) ? '' : 'selected'}}>Choose...</option>
                              <option value="YOUR BHS" {{old('mmr1_type', $d->mmr1_type) == 'YOUR BHS' ? 'selected' : ''}}>{{auth()->user()->tclbhs->facility_name}}</option>
                              <option value="PUBLIC" {{old('mmr1_type', $d->mmr1_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                              <option value="PRIVATE" {{old('mmr1_type', $d->mmr1_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                              <option value="OTHER RHU/BHS" {{old('mmr1_type', $d->mmr1_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="mmr2">MMR 2nd Dose</label>
                            <input type="date" class="form-control" name="mmr2" id="mmr2" value="{{old('mmr2', $d->mmr2)}}" min="{{ ($mode == 'EDIT') ? Carbon\Carbon::parse($d->mmr2)->subYears(2)->format('Y-01-01') : date('Y-01-01', strtotime('-2 Years')) }}" max="{{date('Y-m-d')}}">
                            <small class="text-muted">12 mos</small>
                        </div>
                        <div class="form-group">
                            <label for="mmr2_type">Type</label>
                            <select class="form-control" name="mmr2_type" id="mmr2_type">
                              <option value="" disabled {{old('mmr2_type', $d->mmr2_type) ? '' : 'selected'}}>Choose...</option>
                              <option value="YOUR BHS" {{old('mmr2_type', $d->mmr2_type) == 'YOUR BHS' ? 'selected' : ''}}>{{auth()->user()->tclbhs->facility_name}}</option>
                              <option value="PUBLIC" {{old('mmr2_type', $d->mmr2_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                              <option value="PRIVATE" {{old('mmr2_type', $d->mmr2_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                              <option value="OTHER RHU/BHS" {{old('mmr2_type', $d->mmr2_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="remarks">Remarks/Actions Taken</label>
                    <input type="text" class="form-control" name="remarks" id="remarks" value="{{old('remarks', $d->remarks)}}">
                </div>
                <div class="alert alert-primary" role="alert">
                    <strong class="text-danger">Note: </strong>FIC/CIC will be computed automatically based on the data entered.
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

<div class="modal fade" id="mother_search_modal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Link to Maternal Care Record</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <input type="text" class="form-control" id="mother_search_input" placeholder="Type to search mother name...">
                </div>
          
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="thead-light text-center">
                        <tr>
                            <th>Maternal Care ID</th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Date Delivered</th>
                            <th>Delivery Type</th>
                        </tr>
                        </thead>
                        <tbody id="mother_table_body">
                        <tr><td colspan="3" class="text-center text-muted">Loading...</td></tr>
                        </tbody>
                    </table>
                </div>
        
                <small class="text-muted">Click a mother name to select.</small>
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

    function loadMothers(query) {
        $('#mother_table_body').html('<tr><td colspan="5" class="text-center text-muted">Loading...</td></tr>');

        $.ajax({
            url: "{{ route('inhouse_maternalcare_search') }}",
            method: "GET",
            data: { q: query },
            success: function(rows) {
                if (!rows || rows.length === 0) {
                $('#mother_table_body').html('<tr><td colspan="5" class="text-center text-muted">No results</td></tr>');
                return;
                }

                let html = '';
                rows.forEach(function (r) {
                let fullname = r.patient
                    ? `${r.patient.lname}, ${r.patient.fname} ${r.patient.mname ?? ''}`
                    : '—';

                html += `
                    <tr>
                    <td class="text-center">${r.id}</td>
                    <td>
                        <a href="#" class="pick-mother" data-motherid="${r.id}" data-mothername="${fullname}">
                        ${fullname}
                        </a>
                    </td>
                    <td class="text-center"></td>
                    <td class="text-center">${r.delivery_date}</td>
                    <td class="text-center">${r.outcome}</td>
                    </tr>
                `;
                });

                $('#mother_table_body').html(html);
            },
            error: function() {
                $('#mother_table_body').html('<tr><td colspan="5" class="text-center text-danger">Failed to load</td></tr>');
            }
        });
    }

    $('#mother_search_btn').on('click', function() {
        $('#mother_search_modal').modal('toggle');
        $('#mother_search_input').val('');
        loadMothers('');
        setTimeout(() => $('#mother_search_input').trigger('focus'), 200);
    });

    // Search as you type (simple debounce)
    let motherSearchTimer = null;
    $('#mother_search_input').on('keyup', function() {
        clearTimeout(motherSearchTimer);
        const q = $(this).val();
        motherSearchTimer = setTimeout(function() {
        loadMothers(q);
        }, 300);
    });

    // Click household no -> set input then close modal
    $(document).on('click', '.pick-mother', function(e) {
        e.preventDefault();
        const motherid = $(this).data('motherid');
        const mothername = $(this).data('mothername');
        $('#display_maternal').val(mothername);
        $('#maternalcare_id').val(motherid);
        $('#mother_search_modal').modal('toggle');
    });

    $('#bcg1').on('change', function () {
        $('#bcg1_type').prop('required', !!$(this).val());
        $('#bcg2').prop('min', $(this).val());
    }).trigger('change');

    $('#bcg2').on('change', function () {
        $('#bcg2_type').prop('required', !!$(this).val());
    }).trigger('change');

    $('#hepab1').on('change', function () {
        $('#hepab1_type').prop('required', !!$(this).val());
        $('#hepab2').prop('min', $(this).val());
    }).trigger('change');

    $('#hepab2').on('change', function () {
        $('#hepab2_type').prop('required', !!$(this).val());
    }).trigger('change');

    $('#dpt1').on('change', function () {
        $('#dpt1_type').prop('required', !!$(this).val());
        $('#dpt2').prop('min', $(this).val());
    }).trigger('change');

    $('#dpt2').on('change', function () {
        $('#dpt2_type').prop('required', !!$(this).val());
        $('#dpt3').prop('min', $(this).val());
    }).trigger('change');

    $('#dpt3').on('change', function () {
        $('#dpt3_type').prop('required', !!$(this).val());
    }).trigger('change');

    $('#opv1').on('change', function () {
        $('#opv1_type').prop('required', !!$(this).val());
        $('#opv2').prop('min', $(this).val());
    }).trigger('change');

    $('#opv2').on('change', function () {
        $('#opv2_type').prop('required', !!$(this).val());
        $('#opv3').prop('min', $(this).val());
    }).trigger('change');

    $('#opv3').on('change', function () {
        $('#opv3_type').prop('required', !!$(this).val());
    }).trigger('change');

    $('#ipv1').on('change', function () {
        $('#ipv1_type').prop('required', !!$(this).val());
        $('#ipv2').prop('min', $(this).val());
    }).trigger('change');

    $('#ipv2').on('change', function () {
        $('#ipv2_type').prop('required', !!$(this).val());
    }).trigger('change');

    $('#pcv1').on('change', function () {
        $('#pcv1_type').prop('required', !!$(this).val());
        $('#pcv2').prop('min', $(this).val());
    }).trigger('change');

    $('#pcv2').on('change', function () {
        $('#pcv2_type').prop('required', !!$(this).val());
        $('#pcv3').prop('min', $(this).val());
    }).trigger('change');

    $('#pcv3').on('change', function () {
        $('#pcv3_type').prop('required', !!$(this).val());
    }).trigger('change');

    $('#mmr1').on('change', function () {
        $('#mmr1_type').prop('required', !!$(this).val());
        $('#mmr2').prop('min', $(this).val());
    }).trigger('change');

    $('#mmr2').on('change', function () {
        $('#mmr2_type').prop('required', !!$(this).val());
    }).trigger('change');

    $('#mother_type').change(function (e) { 
        e.preventDefault();
        $('#maternalcare_div').addClass('d-none');
        $('#display_maternal').prop('required', false);
        $('#mother_name_div').addClass('d-none');
        $('#mother_name').prop('required', false);
        $('#cpab_manual').prop('required', false);
        $('#cpab_type').prop('required', false);
            
        if($(this).val() == 'Y') {
            $('#maternalcare_div').removeClass('d-none');
            $('#display_maternal').prop('required', true);
        } else if($(this).val() == 'N') {
            $('#display_maternal').val('');
            $('#maternalcare_id').val('');
            $('#mother_name_div').removeClass('d-none');
            $('#mother_name').prop('required', true);
            $('#cpab_manual').prop('required', true);
            $('#cpab_type').prop('required', true);
        }
    }).trigger('change');
</script>
@endsection