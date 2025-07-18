@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
@if(auth()->user()->isAdmin == 1)
<div class="container">
    <form action="{{route('abtc_encode_destroy', [$d->id])}}" method="POST">
        @csrf
        @method('delete')
        <div class="text-right mb-3">
            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to DELETE? Click OK to Confirm.')"><i class="fa fa-trash mr-2"></i>Delete Vaccination Record</button>
        </div>
    </form>
</div>
@endif
<form action="{{route('abtc_encode_update', ['br_id' => $d->id])}}" method="POST">
    @csrf
    <div class="container">
        @if($d->ifOldCase())
        <div class="alert alert-info" role="alert">
            <h4><b>OLD RECORD</b>, only an administrator could modify information.</h4>
        </div>
        @else
            @if($d->outcome != 'INC')
                @if($d->outcome == 'C')
                <div class="alert alert-info" role="alert">
                    <h4>This case was marked as <b class="text-success">FINISHED</b></h4>
                    <hr>
                    <form class="form-inline">
                        <p>Options:</p>
                        <a href="{{route('abtc_bakuna_again', ['patient_id' => $d->patient->id])}}" class="btn btn-success">Create Booster Vaccination</a>
                        @if($d->d28_done == 0 && $d->is_booster == 0)
                        <a href="{{route('abtc_mark_dead', [$d->id])}}" class="btn btn-danger">Mark Animal as Dead (Open Day 28 Vaccination)</a>
                        @endif
                    </form>
                </div>
                @elseif($d->outcome == 'D')
                <div class="alert alert-info" role="alert">
                    <h4>The case was marked as closed as the patient was declare <b>Dead.</b></h4>
                    <hr>
                    <p>Modifying details can only be done by an administrator.</p>
                </div>
                @endif
            @else
                @if($d->rebakunaIncompleteCheck() == true)
                <div class="alert alert-info" role="alert">
                    <h4>The patient record was marked with <b class="text-warning">INCOMPLETE</b></h4>
                    <h6>The patient did not arrived here for <b>{{$d->getDidNotArriveIn()}}</b></h6>
                    <hr>
                    <form class="form-inline">
                        <a href="{{route('abtc_bakuna_again', ['patient_id' => $d->patient->id])}}" class="btn btn-success">Create a New Vaccination Record</a>
                    </form>
                </div>
                @endif
            @endif
        @endif
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div><strong>Edit Anti-Rabies Vaccination Details</strong> (Patient #{{$d->patient->id}})</div>
                    <div>
                        <a href="{{route('abtc_itr', $d->id)}}" class="btn btn-primary"><i class="fas fa-print mr-2"></i>Print ITR</a>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#rfrbtn">Print Referral Slip</button>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#printMedCert"><i class="fas fa-print mr-2"></i>Print MedCert</button>
                        <a href="{{route('abtc_print_view', $d->id)}}?t=1" class="btn btn-primary"><i class="fas fa-print mr-2"></i>Print Card</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}}" role="alert">
                    {{session('msg')}}
                </div>
                @endif
                @if($errors->any())
                <div class="alert alert-danger" role="alert">
                    <p>{{Str::plural('Error', $errors->count())}} detected on Updating:</p>
                    <hr>
                    @foreach ($errors->all() as $error)
                        <li>{{$error}}</li>
                    @endforeach
                </div>
                @endif
                <div class="alert alert-info" role="alert">
                    Note: All Fields marked with an asterisk (<strong class="text-danger">*</strong>) are required fields.
                </div>
                <table class="table table-bordered text-center">
                    <tbody>
                        <tr>
                            <td class="bg-light"><b>Created At / By</b></td>
                            <td>{{date('m/d/Y H:i A', strtotime($d->created_at))}} ({{$d->getCreatedBy()}})</td>
                        </tr>
                        @if($d->updated_at != $d->created_at)
                        <tr>
                            <td class="bg-light"><b>Updated At / By</b></td>
                            <td>{{date('m/d/Y H:i A', strtotime($d->updated_at))}} ({{$d->getUpdatedBy()}})</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
                <hr>
                <table class="table table-bordered">
                    <tbody class="text-center">
                        <tr>
                            <td class="bg-light"><strong>Registration # {{(auth()->user()->isAdmin != 1) ? ' / Encoded Under' : ''}}</strong></td>
                            <td>{{$d->case_id}} {{(auth()->user()->isAdmin != 1) ? ' / '.$d->getBranch() : ''}}</td>
                        </tr>
                        <tr>
                            <td class="bg-light"><strong>Name / ID</strong></td>
                            <td><a href="{{route('abtc_patient_edit', ['id' => $d->patient->id])}}">{{$d->patient->getName()}} (#{{$d->patient->id}})</a></td>
                        </tr>
                        <tr>
                            <td class="bg-light"><strong>Birthdate/Age/Gender</strong></td>
                            <td>{{!is_null($d->bdate) ? date('m-d-Y', strtotime($d->patient->bdate)) : 'N/A'}} / {{$d->patient->getAge()}} / {{$d->patient->sg()}}</td>
                        </tr>
                        <tr>
                            <td class="bg-light"><strong>Address</strong></td>
                            <td>{{$d->patient->getAddress()}}</td>
                        </tr>
                        <tr>
                            <td class="bg-light"><strong>Contact No.</strong></td>
                            <td>{{(!is_null($d->contact_number)) ? $d->contact_number : 'N/A'}}</td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <div class="row">
                    <div class="col-md-{{(auth()->user()->isAdmin == 1) ? '4' : '6 d-none'}}">
                        <div>
                            <label for="vaccination_site_id" class="form-label"><strong class="text-danger">*</strong>Encoded Under</label>
                            <select class="form-select" name="vaccination_site_id" id="vaccination_site_id" required>
                                @foreach($vslist as $vs)
                                <option value="{{$vs->id}}" {{(old('vaccination_site_id', $d->vaccination_site_id) == $vs->id) ? 'selected' : ''}}>{{$vs->site_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-{{(auth()->user()->isAdmin == 1) ? '4' : '6'}}">
                        <div>
                            <label for="case_date" class="form-label"><strong class="text-danger">*</strong>Registration/Case Date</label>
                            <input type="date" class="form-control" name="case_date" id="case_date" min="2000-01-01" max="{{date('Y-m-d')}}" value="{{old('case_date', $d->case_date)}}" required autofocus>
                            <small class="text-muted">Date patient was first seen, regardless whether patient was given PEP or not.</small>
                        </div>
                    </div>
                    <div class="col-md-{{(auth()->user()->isAdmin == 1) ? '4' : '6'}}">
                        <div class="mb-3">
                            <label for="is_booster" class="form-label"><strong class="text-danger">*</strong>Override: Is Booster?</label>
                            <select class="form-select" name="is_booster" id="is_booster" required>
                                <option value="N" {{(old('is_booster', $d->is_booster) == 0) ? 'selected' : ''}}>No</option>
                                <option value="Y" {{(old('is_booster', $d->is_booster) == 1) ? 'selected' : ''}}>Yes</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="is_preexp" class="form-label"><strong class="text-danger">*</strong>Is Pre-Exposure?</label>
                            <select class="form-select" name="is_preexp" id="is_preexp" required>
                                <option value="N" {{(old('is_preexp', $d->is_preexp) == 0) ? 'selected' : ''}}>No</option>
                                <option value="Y" {{(old('is_preexp', $d->is_preexp) == 1) ? 'selected' : ''}}>Yes</option>
                            </select>
                        </div>
                        <div id="preexpDiv2" class="d-none">
                            <label for="preexp_type" class="form-label"><strong class="text-danger">*</strong>Pre-Exposure Type</label>
                            <select class="form-select" name="preexp_type" id="preexp_type" required>
                                <option value="0" {{(old('preexp_type', $d->preexp_type) == '0') ? 'selected' : ''}}>Type 1 - D0, D7, D28</option>
                                <option value="1" {{(old('preexp_type', $d->preexp_type) == '1') ? 'selected' : ''}}>Type 2 - D0, D3, D7</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div id="divpostexp">
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="weight" class="form-label"><strong class="text-danger">*</strong>Weight (kg)</label>
                                <input type="number" class="form-control" name="weight" id="weight" value="{{old('weight', $d->weight)}}" min="1" max="700">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div><label for="height" class="form-label"><strong class="text-danger">*</strong>Height (cm)</label></div>
                            <div class="input-group mb-3">
                                <input type="number" class="form-control" name="height" id="height" value="{{old('height', $d->height)}}" min="1" max="700">
                                <div class="input-group-append">
                                  <button class="btn btn-outline-primary" type="button" data-toggle="modal" data-target="#heightConverter">Convert feet to cm</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="bite_date" class="form-label"><strong class="text-danger">*</strong>Date of Exposure/Bite Date</label>
                                <input type="date" class="form-control" name="bite_date" id="bite_date" min="2000-01-01" max="{{date('Y-m-d')}}" value="{{old('bite_date', $d->bite_date)}}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="case_location" class="form-label"><strong id="case_location_ast" class="d-none text-danger">*</strong>Barangay/City (Where biting occured)</label>
                                <input type="text" class="form-control" name="case_location" id="case_location" value="{{old('case_location', $d->case_location)}}" style="text-transform: uppercase;">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="if_animal_vaccinated" class="form-label"><strong class="text-danger">*</strong>Is the animal already vaccinated within the year?</label>
                                <select class="form-select" name="if_animal_vaccinated" id="if_animal_vaccinated">
                                    <option value="" disabled {{is_null(old('if_animal_vaccinated', $d)) ? 'selected' : ''}}>Choose...</option>
                                    <option value="N" {{(old('if_animal_vaccinated', $d) == 'N') ? 'selected' : ''}}>No</option>
                                    <option value="Y" {{(old('if_animal_vaccinated', $d) == 'Y') ? 'selected' : ''}}>Yes</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="animal_type" class="form-label"><strong class="text-danger">*</strong>Type of Animal</label>
                                <select class="form-select" name="animal_type" id="animal_type">
                                    <option value="" disabled {{is_null(old('animal_type', $d->animal_type)) ? 'selected' : ''}}>Choose...</option>
                                    <option value="PD" {{(old('animal_type', $d->animal_type) == 'PD') ? 'selected' : ''}}>Pet Dog (PD)</option>
                                    <option value="PC" {{(old('animal_type', $d->animal_type) == 'PC') ? 'selected' : ''}}>Pet Cat</option>
                                    <option value="SD" {{(old('animal_type', $d->animal_type) == 'SD') ? 'selected' : ''}}>Stray Dog (SD)</option>
                                    <option value="SC" {{(old('animal_type', $d->animal_type) == 'SC') ? 'selected' : ''}}>Stray Cat</option>
                                    <option value="O" {{(old('animal_type', $d->animal_type) == 'O') ? 'selected' : ''}}>Others</option>
                                </select>
                            </div>
                            <div id="ifanimaltype_othersdiv" class="d-none">
                                <div class="mb-3">
                                    <label for="animal_type_others" class="form-label"><strong class="text-danger">*</strong>Others, Please state Animal</label>
                                    <input type="text" class="form-control" name="animal_type_others" id="animal_type_others" value="{{old('animal_type_others', $d->animal_type_others)}}" style="text-transform: uppercase;">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="bite_type" class="form-label"><strong class="text-danger">*</strong>Type of Exposure</label>
                                <select class="form-select" name="bite_type" id="bite_type">
                                    <option value="B" {{(old('bite_type', $d->bite_type) == 'B') ? 'selected' : ''}}>Bite</option>
                                    <option value="NB" {{(old('bite_type', $d->bite_type) == 'NB') ? 'selected' : ''}}>Scratch</option>
                                    <option value="CC" {{(old('bite_type', $d->bite_type) == 'CC') ? 'selected' : ''}}>Close Contact of Rabies Patient</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="body_site"><strong class="text-danger">*</strong>Anatomical Location (Body Parts)</label>
                                <select class="form-select" name="body_site[]" id="body_site" multiple>
                                  <option value="ABDOMEN" {{ in_array('ABDOMEN', old('body_site', explode(",", $d->body_site))) ? 'selected' : '' }}>Abdomen/Tiyan</option>
                                  <option value="FOOT" {{ in_array('FOOT', old('body_site', explode(",", $d->body_site))) ? 'selected' : '' }}>Foot/Paa</option>
                                  <option value="SHOULDER" {{ in_array('SHOULDER', old('body_site', explode(",", $d->body_site))) ? 'selected' : '' }}>Shoulder/Balikat</option>
                                  <option value="FOREARM/ARM" {{ in_array('FOREARM/ARM', old('body_site', explode(",", $d->body_site))) ? 'selected' : '' }}>Forearm/Arm/Braso</option>
                                  <option value="HAND" {{ in_array('HAND', old('body_site', explode(",", $d->body_site))) ? 'selected' : '' }}>Hand/Kamay</option>
                                  <option value="FINGER" {{ in_array('FINGER', old('body_site', explode(",", $d->body_site))) ? 'selected' : '' }}>Finger/Daliri</option>
                                  <option value="HEAD" {{ in_array('HEAD', old('body_site', explode(",", $d->body_site))) ? 'selected' : '' }}>Head/Face/Ulo/Mukha</option>
                                  <option value="KNEE" {{ in_array('KNEE', old('body_site', explode(",", $d->body_site))) ? 'selected' : '' }}>Knee/Tuhod</option>
                                  <option value="THIGH" {{ in_array('THIGH', old('body_site', explode(",", $d->body_site))) ? 'selected' : '' }}>Thigh/Hita</option>
                                  <option value="LEGS" {{ in_array('LEGS', old('body_site', explode(",", $d->body_site))) ? 'selected' : '' }}>Legs/Binti</option>
                                  <option value="NECK" {{ in_array('NECK', old('body_site', explode(",", $d->body_site))) ? 'selected' : '' }}>Neck/Leeg</option>
                                  <option value="GENITAL" {{ in_array('GENITAL', old('body_site', explode(",", $d->body_site))) ? 'selected' : '' }}>Genital/Ari</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="category_level" class="form-label"><strong class="text-danger">*</strong>Category</label>
                                <select class="form-select" name="category_level" id="category_level">
                                    <option value="" disabled {{is_null(old('category_level', $d->category_level)) ? 'selected' : ''}}>Choose...</option>
                                    <!--<option value="1" {{(old('category_level', $d->category_level) == 1) ? 'selected' : ''}}>Category 1</option>-->
                                    <option value="2" {{(old('category_level', $d->category_level) == 2) ? 'selected' : ''}}>Category 2</option>
                                    <option value="3" {{(old('category_level', $d->category_level) == 3) ? 'selected' : ''}}>Category 3</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="washing_of_bite" class="form-label"><strong class="text-danger">*</strong>Washing of Bite</label>
                                <select class="form-select" name="washing_of_bite" id="washing_of_bite" required>
                                    <option value="Y" {{(old('washing_of_bite', $d->washing_of_bite) == 'Y' || $d->washing_of_bite == 1) ? 'selected' : ''}}>Yes</option>
                                    <option value="N" {{(old('washing_of_bite', $d->washing_of_bite) == 'N' || $d->washing_of_bite == 0) ? 'selected' : ''}}>No</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="rig_date_given" class="form-label">RIG Date Given <small><i>(If Applicable)</i></small></label>
                                <input type="date" class="form-control" name="rig_date_given" id="rig_date_given" min="2000-01-01" max="{{date('Y-m-d')}}" value="{{old('rig_date_given', $d->rig_date_given)}}">
                            </div>
                        </div>
                    </div>
                    
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="pep_route" class="form-label"><strong class="text-danger">*</strong>Route</label>
                            <select class="form-select" name="pep_route" id="pep_route" required>
                                <option value="ID" {{(old('pep_route', $d->pep_route) == 'ID') ? 'selected' : ''}}>ID - Intradermal</option>
                                <option value="IM" {{(old('pep_route', $d->pep_route) == 'IM') ? 'selected' : ''}}>IM - Intramuscular</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="brand_name" class="form-label"><strong class="text-danger">*</strong>Brand Name</label>
                            <select class="form-select" name="brand_name" id="brand_name" required>
                                <option value="" disabled {{is_null(old('brand_name', $d->brand_name)) ? 'selected' : ''}}>Choose...</option>
                                @foreach($vblist as $v)
                                <option value="{{$v->brand_name}}" {{(old('brand_name', $d->brand_name) == $v->brand_name) ? 'selected' : ''}}>{{$v->brand_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped text-center">
                        <thead class="bg-light">
                            <tr class="text-end">
                                <th colspan="4"><a class="btn btn-primary" href="{{route('abtc_override_schedule', ['br_id' => $d->id])}}" role="button"><i class="fa-solid fa-clock-rotate-left me-2"></i>Manually Change Schedule</a></th>
                            </tr>
                            <tr>
                                <th>Schedule</th>
                                <th>Date</th>
                                <th>Brand</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Day 0</td>
                                <td>{{date('m/d/Y (l)', strtotime($d->d0_date))}}</td>
                                <td>{{$d->d0_brand}}</td>
                                <td>
                                    @if($d->d0_done == 1)
                                    <strong class="text-success">DONE</strong>
                                    @else
                                        @if($d->ifAbleToProcessD0() == 'Y')
                                        <a href="{{route('abtc_encode_process', ['br_id' => $d->id, 'dose' => 1])}}?fsc=1" class="btn btn-primary" onclick="return confirm('The patient should be present and injected with the 0 Day Dose. Click OK to Continue.')">Mark as Done</a>
                                        @elseif($d->ifAbleToProcessD0() == 'D')
                                        <p class="text-danger"><b>DID NOT ARRIVED</b></p>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Day 3</td>
                                <td>{{date('m/d/Y (l)', strtotime($d->d3_date))}}</td>
                                <td>{{$d->d3_brand}}</td>
                                <td>
                                    @if($d->d3_done == 1)
                                    <strong class="text-success">DONE</strong>
                                    @else
                                        @if($d->ifAbleToProcessD3() == 'Y')
                                        <a href="{{route('abtc_encode_process', ['br_id' => $d->id, 'dose' => 2])}}" class="btn btn-primary" onclick="return confirm('The patient should be present and injected with the 3rd Day Dose. Click OK to Continue.')">Mark as Done</a>
                                        @elseif($d->ifAbleToProcessD3() == 'D')
                                        <p class="text-danger"><b>DID NOT ARRIVED</b></p>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                            @if($d->is_booster == 0)
                            <tr>
                                <td>Day 7</td>
                                <td>{{date('m/d/Y (l)', strtotime($d->d7_date))}}</td>
                                <td>{{$d->d7_brand}}</td>
                                <td>
                                    @if($d->d7_done == 1)
                                    <strong class="text-success">DONE</strong>
                                    @else
                                        @if($d->ifAbleToProcessD7() == 'Y')
                                        <a href="{{route('abtc_encode_process', ['br_id' => $d->id, 'dose' => 3])}}" class="btn btn-primary" onclick="return confirm('The patient should be present and injected with the 7th Day Dose. Click OK to Continue.')">Mark as Done</a>
                                        @elseif($d->ifAbleToProcessD7() == 'D')
                                        <p class="text-danger"><b>DID NOT ARRIVED</b></p>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                            @if($d->pep_route != 'ID')
                            <tr>
                                <td>Day 14</td>
                                <td>{{date('m/d/Y (l)', strtotime($d->d14_date))}}</td>
                                <td>{{$d->d14_brand}}</td>
                                <td>
                                    @if($d->d14_done == 1)
                                    <strong class="text-success">DONE</strong>
                                    @else
                                        @if($d->ifAbleToProcessD14() == 'Y')
                                        <a href="{{route('abtc_encode_process', ['br_id' => $d->id, 'dose' => 4])}}" class="btn btn-primary" onclick="return confirm('The patient should be present and injected with the 14th Day Dose. Click OK to Continue.')">Mark as Done</a>
                                        @elseif($d->ifAbleToProcessD14() == 'D')
                                        <p class="text-danger"><b>DID NOT ARRIVED</b></p>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <td>Day 28</td>
                                <td>{{date('m/d/Y (l)', strtotime($d->d28_date))}}</td>
                                <td>{{$d->d28_brand}}</td>
                                <td>
                                    @if($d->d28_done == 1)
                                    <strong class="text-success">DONE</strong>
                                    @else
                                        @if($d->ifAbleToProcessD28() == 'Y')
                                        <a href="{{route('abtc_encode_process', ['br_id' => $d->id, 'dose' => 5])}}" class="btn btn-primary" onclick="return confirm('The patient should be present and injected with the 28th Day Dose. Click OK to Continue.')">Mark as Done</a>
                                        @elseif($d->ifAbleToProcessD28() == 'D')
                                        <p class="text-danger"><b>DID NOT ARRIVED</b></p>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="">
                            <label for="outcome" class="form-label"><strong class="text-danger">*</strong>Outcome</label>
                            <select class="form-select" name="outcome" id="outcome" required>
                                @if($d->outcome == 'C')
                                <option value="C" {{(old('outcome', $d->outcome) == 'C') ? 'selected' : ''}}>Completed (C)</option>
                                @endif
                                <option value="INC" {{(old('outcome', $d->outcome) == 'INC') ? 'selected' : ''}}>Incomplete (INC)</option>
                                <option value="D" {{(old('outcome', $d->outcome) == 'D') ? 'selected' : ''}}>Died (D)</option>
                                <!--<option value="C" {{(old('outcome', $d->outcome) == 'C') ? 'selected' : ''}}>Complete (C)</option>-->
                            </select>
                            <small class="text-muted">Will be automatically changed based on completed doses.</small>
                        </div>
                        <div id="ifpatientdied" class="d-none">
                            <div>
                                <label for="date_died" class="form-label"><strong class="text-danger">*</strong>Date Patient Died</label>
                                <input type="date" class="form-control" name="date_died" id="date_died" min="{{$d->patient->bdate}}" max="{{date('Y-m-d')}}" value="{{old('date_died', $d->date_died)}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="">
                            <label for="biting_animal_status" class="form-label"><strong class="text-danger">*</strong>Biting Animal Status (After 14 Days)</label>
                            <select class="form-select" name="biting_animal_status" id="biting_animal_status" required>
                                <option value="N/A" {{(old('biting_animal_status', $d->biting_animal_status) == 'N/A') ? 'selected' : ''}}>N/A</option>
                                <option value="ALIVE" {{(old('biting_animal_status', $d->biting_animal_status) == 'ALIVE') ? 'selected' : ''}}>Alive</option>
                                <option value="DEAD" {{(old('biting_animal_status', $d->biting_animal_status) == 'DEAD') ? 'selected' : ''}}>Dead</option>
                                <option value="LOST" {{(old('biting_animal_status', $d->biting_animal_status) == 'LOST') ? 'selected' : ''}}>Lost/Unknown</option>
                            </select>
                        </div>
                        <div id="ifdogdied" class="d-none">
                            <label for="animal_died_date" class="form-label"><strong class="text-danger">*</strong>Date Animal Died</label>
                            <input type="date" class="form-control" name="animal_died_date" id="animal_died_date" min="2000-01-01" max="{{date('Y-m-d')}}" value="{{old('animal_died_date', $d->animal_died_date)}}">
                        </div>
                    </div>
                </div>
                <hr>
                <div class="mb-3">
                    <label for="remarks" class="form-label">Remarks <small><i>(If Applicable)</i></small></label>
                    <textarea class="form-control" name="remarks" id="remarks" rows="3">{{old('remarks', $d->remarks)}}</textarea>
                </div>
            </div>
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-success btn-block" id="submitbtn"><i class="fas fa-save mr-2"></i>Update (CTRL + S)</button>
            </div>
        </div>
    </div>
</form>

<div class="modal fade" id="rfrbtn" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Referral Slip</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span >&times;</span>
                    </button>
            </div>
            <div class="modal-body text-center">
                <p>Select a Reason:</p>
                <a href="{{route('abtc_referralslip', $d->id)}}?reas=1" class="btn btn-primary mb-3">No Available Vaccine in CHO ABTC</a>
                <a href="{{route('abtc_referralslip', $d->id)}}?reas=2" class="btn btn-primary">No Available ERIG in CHO ABTC</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="heightConverter" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Foot to Centimeter Converter</h5>
                <button type="button" id="heightCloseBtn" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="feet"><b class="text-danger">*</b>Feet</label>
                            <input type="number" class="form-control" name="feet" id="feet">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="inches"><b class="text-danger">*</b>Inches</label>
                            <input type="number" class="form-control" name="inches" id="inches">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" name="convertBtn" id="convertBtn" class="btn btn-success btn-block">Convert</button>
            </div>
        </div>
    </div>
</div>

<form action="{{route('abtc_medcert', $d->id)}}" method="GET">
    <div class="modal fade" id="printMedCert" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Print Medical Certificate</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="vaccinator"><b class="text-danger">*</b>Select Vaccinator</label>
                        <select class="form-select" name="vaccinator" id="vaccinator" required>
                          <option value="" disabled selected>Choose...</option>
                          @foreach($vaccinator_list as $v)
                          <option value="{{$v->getNameWithPr()}}">{{$v->getNameWithPr()}}</option>
                          @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                      <label for="doctor"><b class="text-danger">*</b>Select Doctor</label>
                      <select class="form-select" name="doctor" id="doctor" required>
                        <option value="" disabled selected>Choose...</option>
                        <option value="DOC_ATHAN">Jonathan P. Luseco, MD</option>
                        <option value="DOC_ABE">Abe D. Escario, MD</option>
                        <option value="DOC_YVES">Yves M. Talosig, MD</option>
                        <option value="DRA_CHERRY">Cherry L. Aspuria, MD</option>
                        <option value="DOC_ED">Edgardo R. Figueroa, MD</option>
                      </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-block">Submit</button>
                </div>
            </div>
        </div>
    </div>
</form>
  
<script>
$(document).ready(function () {
    $('#convertBtn').click(function () {
        // Get values from input fields
        const feet = parseInt($('#feet').val());
        const inches = parseInt($('#inches').val());

        // Validate input
        if (isNaN(feet) || isNaN(inches) || feet < 0 || inches < 0) {
            alert('Please enter valid values for feet and inches.');
            return;
        }

        // Convert height to centimeters
        const totalInches = (feet * 12) + inches;
        const cm = totalInches * 2.54;

        // Display result
        $('#height').val(cm.toFixed(2));

        $('#heightCloseBtn').click();
    });
});
</script>

<script>
    $(document).bind('keydown', function(e) {
		if(e.ctrlKey && (e.which == 83)) {
			e.preventDefault();
			$('#submitbtn').trigger('click');
			$('#submitbtn').prop('disabled', true);
			setTimeout(function() {
				$('#submitbtn').prop('disabled', false);
			}, 2000);
			return false;
		}
	});

    $('#is_preexp').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'Y') {
            $('#divpostexp').addClass('d-none');
            $('#bite_date').prop('required', false);
            $('#case_location').prop('required', false);
            $('#if_animal_vaccinated').prop('required', false);
            $('#animal_type').prop('required', false);
            $('#bite_type').prop('required', false);
            $('#category_level').prop('required', false);
            $('#body_site').prop('required', false);

            $('#height').prop('required', false);
            $('#weight').prop('required', false);

            $('#preexpDiv2').removeClass('d-none');
            $('#preexp_type').prop('required', true);
        }
        else {
            $('#divpostexp').removeClass('d-none');
            $('#bite_date').prop('required', true);
            $('#case_location').prop('required', true);
            $('#if_animal_vaccinated').prop('required', true);
            $('#animal_type').prop('required', true);
            $('#bite_type').prop('required', true);
            $('#category_level').prop('required', true);
            $('#body_site').prop('required', true);

            $('#height').prop('required', true);
            $('#weight').prop('required', true);

            $('#preexpDiv2').addClass('d-none');
            $('#preexp_type').prop('required', false);
        }
    }).trigger('change');

    $('#body_site').select2({
        theme: "bootstrap",
    });

    $('#category_level').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 3) {
            if($('#rig_date_given').val() != "{{date('Y-m-d')}}") {
                $('#rig_date_given').val("{{date('Y-m-d')}}");
            }
        }
        else {
            $('#rig_date_given').val('');
        }
    }).trigger('change');

    $('#animal_type').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'O') {
            $('#ifanimaltype_othersdiv').removeClass('d-none');
            $('#animal_type_others').prop('required', true);
        }
        else {
            $('#ifanimaltype_othersdiv').addClass('d-none');
            $('#animal_type_others').prop('required', false);
        }
    }).trigger('change');

    $('#bite_type').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'B') {
            $('#case_location').prop('required', true);
            //$('#body_site').prop('required', false);

            //$('#body_site_ast').removeClass('d-none');
            $('#case_location_ast').removeClass('d-none');
        }
        else if($(this).val() == 'NB') {
            $('#case_location').prop('required', false);
            //$('#body_site').prop('required', false);

            //$('#body_site_ast').addClass('d-none');
            $('#case_location_ast').addClass('d-none');
        }
        else {
            $('#case_location').prop('required', false);
            //$('#body_site').prop('required', false);

            //$('#body_site_ast').addClass('d-none');
            $('#case_location_ast').addClass('d-none');
        }
    }).trigger('change');

    $('#outcome').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'D') {
            $('#ifpatientdied').removeClass('d-none');
            $('#date_died').prop('required', true);
        }
        else {
            $('#ifpatientdied').addClass('d-none');
            $('#date_died').prop('required', false);
        }
    }).trigger('change');

    $('#biting_animal_status').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'DEAD') {
            $('#ifdogdied').removeClass('d-none');
            $('#animal_died_date').prop('required', true);
        }
        else {
            $('#ifdogdied').addClass('d-none');
            $('#animal_died_date').prop('required', false);
        }
    }).trigger('change');
</script>
@endsection