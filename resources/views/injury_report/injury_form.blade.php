@extends('layouts.app')

@section('content')
    <form action="{{ route('injury_add_store', $f->sys_code1) }}" method="POST">
        @csrf
        <div class="container">
            <div class="card">
                <div class="card-header"><b>New Injury</b></div>
                <div class="card-body">
                    <div class="card">
                        <div class="card-header"><b>General Data</b></div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="sys_interviewer_name"><b class="text-danger">*</b>Name of Reporter/Interviewer</label>
                                        <input type="text" class="form-control" name="sys_interviewer_name" id="sys_interviewer_name" value="{{old('sys_interviewer_name', $f->edcs_defaultreporter_name)}}" style="text-transform: uppercase;" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="sys_interviewer_contactno"><b class="text-danger">*</b>Contact No. of Reporter/Interviewer</label>
                                        <input type="text" class="form-control" id="sys_interviewer_contactno" name="sys_interviewer_contactno" value="{{old('sys_interviewer_contactno', $f->edcs_defaultreporter_contactno)}}" pattern="[0-9]{11}" placeholder="09*********" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="patient_no" class="form-label">Patient ID No.</label>
                                                <input type="text" class="form-control" id="patient_no" name="patient_no" style="text-transform: uppercase;" value="{{old('patient_no')}}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="case_no" class="form-label">Hospital/Facility Case No.</label>
                                                <input type="text" class="form-control" id="case_no" name="case_no" style="text-transform: uppercase;" value="{{old('case_no')}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="patient_type"><b class="text-danger">*</b>Type of Patient</label>
                                        <select class="form-control" name="patient_type" id="sex" required>
                                            <option value="" disabled {{(is_null(old('patient_type'))) ? 'selected' : ''}}>Choose...</option>
                                            <option value="ER" {{(old('patient_type') == 'ER') ? 'selected' : ''}}>ER</option>
                                            <option value="OPD" {{(old('patient_type') == 'OPD') ? 'selected' : ''}}>OPD</option>
                                            <option value="IN-PATIENT" {{(old('patient_type') == 'IN-PATIENT') ? 'selected' : ''}}>In-Patient (Injury sustained during confinement)</option>
                                            <option value="BHS" {{(old('patient_type') == 'BHS') ? 'selected' : ''}}>BHS</option>
                                            <option value="RHU" {{(old('patient_type') == 'RHU') ? 'selected' : ''}}>RHU</option>
                                        </select>
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
                                    @if(request()->input('bdate_available') == 'Y')
                                    <div class="form-group">
                                        <label for="bdate"><b class="text-danger">*</b>Birthdate</label>
                                        <input type="date" class="form-control" id="bdate" name="bdate" value="{{request()->input('bdate')}}" readonly required>
                                    </div>
                                    @else
                                    <div class="form-group">
                                        <label for="age_display"><b class="text-danger">*</b>Age</label>
                                        <input type="text" class="form-control" id="age_display" name="age_display" value="{{request()->input('age')}}" readonly required>
                                    </div>
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="sex"><b class="text-danger">*</b>Sex</label>
                                        <select class="form-control" name="sex" id="sex" required>
                                            <option value="" disabled {{(is_null(old('sex'))) ? 'selected' : ''}}>Choose...</option>
                                            <option value="M" {{(old('sex') == 'M') ? 'selected' : ''}}>Male</option>
                                            <option value="F" {{(old('sex') == 'F') ? 'selected' : ''}}>Female</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="philhealth">Philhealth</label>
                                        <input type="text" class="form-control" id="philhealth" name="philhealth" value="{{old('philhealth')}}" pattern="[0-9]{12}">
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header"><b>Permanent Address</b></div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="address_region_code"><b class="text-danger">*</b>Region</label>
                                                <select class="form-control" name="address_region_code" id="address_region_code" tabindex="-1" required>
                                                @foreach(App\Models\Regions::orderBy('regionName', 'ASC')->get() as $a)
                                                <option value="{{$a->id}}" {{($a->id == 1) ? 'selected' : ''}}>{{$a->regionName}}</option>
                                                @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="address_province_code"><b class="text-danger">*</b>Province</label>
                                                <select class="form-control" name="address_province_code" id="address_province_code" tabindex="-1" required disabled>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="address_muncity_code"><b class="text-danger">*</b>City/Municipality</label>
                                                <select class="form-control" name="address_muncity_code" id="address_muncity_code" tabindex="-1" required disabled>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="address_brgy_code"><b class="text-danger">*</b>Barangay</label>
                                                <select class="form-control" name="perm_brgy_code" id="address_brgy_code" required disabled>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header"><b>Temporary Address</b></div>
                                            <div class="card-body">
                                                <div class="form-group">
                                                <label for="same_address"><b class="text-danger">*</b>Permanent Address same as Temporary Address?</label>
                                                <select class="form-control" name="same_address" id="same_address" required>
                                                    <option value="" disabled {{(is_null(old('same_address'))) ? 'selected' : ''}}>Choose...</option>
                                                    <option value="Y" {{(old('same_address') == 'Y') ? 'selected' : ''}}>Yes</option>
                                                    <option value="N" {{(old('same_address') == 'N') ? 'selected' : ''}}>No</option>
                                                </select>
                                            </div>
                                            <div id="temp_div" class="d-none">
                                                <div class="form-group">
                                                    <label for="temp_address_region_code"><b class="text-danger">*</b>Region</label>
                                                    <select class="form-control" name="temp_address_region_code" id="temp_address_region_code" tabindex="-1">
                                                    @foreach(App\Models\Regions::orderBy('regionName', 'ASC')->get() as $a)
                                                    <option value="{{$a->id}}" {{($a->id == 1) ? 'selected' : ''}}>{{$a->regionName}}</option>
                                                    @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="temp_address_province_code"><b class="text-danger">*</b>Province</label>
                                                    <select class="form-control" name="temp_address_province_code" id="temp_address_province_code" tabindex="-1" disabled>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="temp_address_muncity_code"><b class="text-danger">*</b>City/Municipality</label>
                                                    <select class="form-control" name="temp_address_muncity_code" id="temp_address_muncity_code" tabindex="-1" disabled>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="temp_address_brgy_code"><b class="text-danger">*</b>Barangay</label>
                                                    <select class="form-control" name="temp_brgy_code" id="temp_address_brgy_code" disabled>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mt-3">
                        <div class="card-header"><b>Pre-Admission Data</b></div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="injury_datetime"><b class="text-danger">*</b>Date of Injury</label>
                                        <input type="datetime-local" class="form-control" name="injury_datetime" id="injury_datetime" value="{{old('injury_datetime', $d->injury_datetime)}}" required>
                                    </div>
                                    <h6><b>Location of Injury:</b></h6>
                                    <div class="form-group">
                                        <label for="inj_address_region_code"><b class="text-danger">*</b>Injury Region</label>
                                        <select class="form-control" name="inj_address_region_code" id="inj_address_region_code" tabindex="-1" required>
                                        @foreach(App\Models\Regions::orderBy('regionName', 'ASC')->get() as $a)
                                        <option value="{{$a->id}}" {{($a->id == 1) ? 'selected' : ''}}>{{$a->regionName}}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="inj_address_province_code"><b class="text-danger">*</b>Injury Province</label>
                                        <select class="form-control" name="inj_address_province_code" id="inj_address_province_code" tabindex="-1" required disabled>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="inj_address_muncity_code"><b class="text-danger">*</b>Injury City/Municipality</label>
                                        <select class="form-control" name="injury_city_code" id="inj_address_muncity_code" required disabled>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="consultation_datetime"><b class="text-danger">*</b>Date of Consultation</label>
                                        <input type="datetime-local" class="form-control" name="consultation_datetime" id="consultation_datetime" value="{{request()->input('consultation_datetime')}}" readonly required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="injury_intent"><b class="text-danger">*</b>Injury Intent</label>
                                        <select class="form-control" name="injury_intent" id="injury_intent" required>
                                            <option value="" disabled {{(is_null(old('injury_intent'))) ? 'selected' : ''}}>Choose...</option>
                                            <option value="UNINTENTIONAL/ACCIDENTAL" {{(old('injury_intent') == 'UNINTENTIONAL/ACCIDENTAL') ? 'selected' : ''}}>Unintentional/Accidental</option>
                                            <option value="INTENTIONAL (VIOLENCE)" {{(old('injury_intent') == 'INTENTIONAL (VIOLENCE)') ? 'selected' : ''}}>Intentional (Violence)</option>
                                            <option value="VAWC" {{(old('injury_intent') == 'VAWC') ? 'selected' : ''}}>VAWC Patient</option>
                                            <option value="INTENTIONAL (SELF-INFLICTED)" {{(old('injury_intent') == 'INTENTIONAL (SELF-INFLICTED)') ? 'selected' : ''}}>Intentional (Self-Inflicted)</option>
                                            <option value="UNDETERMINED" {{(old('injury_intent') == 'UNDETERMINED') ? 'selected' : ''}}>Undetermined</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="firstaid_given"><b class="text-danger">*</b>First Aid Given?</label>
                                        <select class="form-control" name="firstaid_given" id="firstaid_given" required>
                                            <option value="" disabled {{(is_null(old('firstaid_given'))) ? 'selected' : ''}}>Choose...</option>
                                            <option value="Y" {{(old('firstaid_given') == 'Y') ? 'selected' : ''}}>Yes</option>
                                            <option value="N" {{(old('firstaid_given') == 'N') ? 'selected' : ''}}>No</option>
                                        </select>
                                    </div>
                                    <div id="firstaid_div" class="d-none">
                                        <div class="form-group">
                                            <label for="firstaid_type" class="form-label"><b class="text-danger">*</b>Specify First Aid Given</label>
                                            <input type="text" class="form-control" id="firstaid_type" name="firstaid_type" style="text-transform: uppercase;" value="{{old('firstaid_type')}}">
                                        </div>
                                        <div class="form-group">
                                            <label for="firstaid_bywho" class="form-label"><b class="text-danger">*</b>By whom</label>
                                            <input type="text" class="form-control" id="firstaid_bywho" name="firstaid_bywho" style="text-transform: uppercase;" value="{{old('firstaid_bywho')}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <h6><b>Nature of Injury/ies</b></h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input injury-check" name="abrasion" id="injury1" value="Y">
                                        Abrasion
                                        </label>
                                    </div>
                                    <div class="form-group d-none mt-3" id="injurydiv1">
                                        <label for="abrasion_site" class="form-label"><b class="text-danger">*</b>Specify Body (Site) Location of the Abrasion</label>
                                        <input type="text" class="form-control" id="injury_site1" name="abrasion_site" style="text-transform: uppercase;" value="{{old('abrasion_site')}}">
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input injury-check" name="avulsion" id="injury2" value="Y">
                                        Avulsion
                                        </label>
                                    </div>
                                    <div class="form-group d-none mt-3" id="injurydiv2">
                                        <label for="avulsion_site" class="form-label"><b class="text-danger">*</b>Specify Body (Site) Location of the Avulsion</label>
                                        <input type="text" class="form-control" id="injury_site2" name="avulsion_site" style="text-transform: uppercase;" value="{{old('avulsion_site')}}">
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input injury-check" name="burn" id="injury3" value="Y">
                                        Burn
                                        </label>
                                    </div>
                                    <div id="injurydiv3" class="d-none mt-3">
                                        <div class="form-group" id="burn_div">
                                            <label for="burn_degree"><b class="text-danger">*</b>Degree</label>
                                            <select class="form-control" name="burn_degree" id="burn_degree">
                                                <option value="" disabled {{(is_null(old('burn_degree'))) ? 'selected' : ''}}>Choose...</option>
                                                <option value="1" {{(old('burn_degree') == '1') ? 'selected' : ''}}>1st Degree</option>
                                                <option value="2" {{(old('burn_degree') == '2') ? 'selected' : ''}}>2nd Degree</option>
                                                <option value="3" {{(old('burn_degree') == '3') ? 'selected' : ''}}>3rd Degree</option>
                                                <option value="4" {{(old('burn_degree') == '4') ? 'selected' : ''}}>4th Degree</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="burn_site" class="form-label"><b class="text-danger">*</b>Specify Body (Site) Location of the Burn</label>
                                            <input type="text" class="form-control" id="injury_site3" name="burn_site" style="text-transform: uppercase;" value="{{old('burn_site')}}">
                                        </div>
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input injury-check" name="concussion" id="injury4" value="Y">
                                        Concussion
                                        </label>
                                    </div>
                                    <div class="form-group d-none mt-3" id="injurydiv4">
                                        <label for="concussion_site" class="form-label"><b class="text-danger">*</b>Specify Body (Site) Location of the Concussion</label>
                                        <input type="text" class="form-control" id="injury_site4" name="concussion_site" style="text-transform: uppercase;" value="{{old('concussion_site')}}">
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input injury-check" name="contusion" id="injury5" value="Y">
                                        Contusion
                                        </label>
                                    </div>
                                    <div class="form-group d-none mt-3" id="injurydiv5">
                                        <label for="contusion_site" class="form-label"><b class="text-danger">*</b>Specify Body (Site) Location of the Contusion</label>
                                        <input type="text" class="form-control" id="injury_site5" name="contusion_site" style="text-transform: uppercase;" value="{{old('contusion_site')}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input injury-check" name="fracture" id="injury6" value="Y">
                                        Fracture
                                        </label>
                                    </div>
                                    <div id="injurydiv6" class="d-none mt-2">
                                        <div class="form-check ml-3">
                                            <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input fracture-checkbox" name="fracture_closed" id="fracture1" value="Y">
                                            Closed Type
                                            </label>
                                        </div>
                                        <div class="form-group d-none mt-3" id="injurydiv6_1">
                                            <label for="fracture_closed_site" class="form-label"><b class="text-danger">*</b>Specify Body (Site) Location of the Closed Fracture</label>
                                            <input type="text" class="form-control" id="injury_site6_1" name="fracture_closed_site" style="text-transform: uppercase;" value="{{old('fracture_closed_site')}}">
                                        </div>
        
                                        <div class="form-check ml-3">
                                            <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input fracture-checkbox" name="fracture_open" id="fracture2" value="Y">
                                            Open Type
                                            </label>
                                        </div>
                                        <div class="form-group d-none mt-3" id="injurydiv6_2">
                                            <label for="fracture_open_site" class="form-label"><b class="text-danger">*</b>Specify Body (Site) Location of the Open Fracture</label>
                                            <input type="text" class="form-control" id="injury_site6_2" name="fracture_open_site" style="text-transform: uppercase;" value="{{old('fracture_open_site')}}">
                                        </div>
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input injury-check" name="open_wound" id="injury7" value="Y">
                                        Open Wound
                                        </label>
                                    </div>
                                    <div class="form-group d-none" id="injurydiv7">
                                        <label for="open_wound_site" class="form-label"><b class="text-danger">*</b>Specify Body (Site) Location of the Open Wound</label>
                                        <input type="text" class="form-control" id="injury_site7" name="open_wound_site" style="text-transform: uppercase;" value="{{old('open_wound_site')}}">
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input injury-check" name="traumatic_amputation" id="injury8" value="Y">
                                        Traumatic Amputation
                                        </label>
                                    </div>
                                    <div class="form-group d-none" id="injurydiv8">
                                        <label for="traumatic_amputation_site" class="form-label"><b class="text-danger">*</b>Specify Body (Site) Location of the Traumatic Amputation</label>
                                        <input type="text" class="form-control" id="injury_site8" name="traumatic_amputation_site" style="text-transform: uppercase;" value="{{old('traumatic_amputation_site')}}">
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input injury-check" name="others" id="injury9" value="Y">
                                        Others
                                        </label>
                                    </div>
                                    <div class="form-group d-none" id="injurydiv9">
                                        <label for="others_site" class="form-label"><b class="text-danger">*</b>Specify other injury and site</label>
                                        <input type="text" class="form-control" id="injury_site9" name="others_site" style="text-transform: uppercase;" value="{{old('others_site')}}">
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <h6><b>External Cause/s of Injury/ies</b></h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input ext-injury-check" name="bites_stings" id="external1" value="Y">
                                        Bites/stings, Specify animal/insect
                                        </label>
                                    </div>
                                    <div class="form-group d-none" id="external1_div">
                                        <label for="bites_stings_specify" class="form-label"><b class="text-danger">*</b>Specify Animal/Insect</label>
                                        <input type="text" class="form-control" id="external1_specify" name="bites_stings_specify" style="text-transform: uppercase;" value="{{old('bites_stings_specify')}}">
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input ext-injury-check" name="ext_burns" id="external2" value="Y">
                                        Burns
                                        </label>
                                    </div>
                                    <div id="external2_div" class="mt-3 d-none">
                                        <div class="ml-3">
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input burns-type-checkbox" name="ext_burns_type[]" id="burncheck1" value="HEAT">
                                                Heat
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input burns-type-checkbox" name="ext_burns_type[]" id="burncheck2" value="FIRE">
                                                Fire
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input burns-type-checkbox" name="ext_burns_type[]" id="burncheck3" value="ELECTRICITY">
                                                Electricity
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input burns-type-checkbox" name="ext_burns_type[]" id="burncheck4" value="OIL">
                                                Oil
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input burns-type-checkbox" name="ext_burns_type[]" id="burncheck5" value="FRICTION">
                                                Friction
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input burns-type-checkbox" name="ext_burns_type[]" id="burncheck6" value="OTHERS">
                                                Others
                                                </label>
                                            </div>
                                            <div class="form-group mt-3 d-none" id="extburnsoth_div">
                                                <label for="ext_burns_others_specify" class="form-label"><b class="text-danger">*</b>Specify other burns</label>
                                                <input type="text" class="form-control" id="external2_specify" name="ext_burns_others_specify" style="text-transform: uppercase;" value="{{old('ext_burns_others_specify')}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input ext-injury-check" name="chemical_substance" id="external3" value="Y">
                                        Chemical/substance
                                        </label>
                                    </div>
                                    <div id="external3_div" class="mt-3 d-none">
                                        <div class="form-group">
                                            <label for="chemical_substance_specify" class="form-label"><b class="text-danger">*</b>Specify</label>
                                            <input type="text" class="form-control" id="external3_specify" name="chemical_substance_specify" style="text-transform: uppercase;" value="{{old('chemical_substance_specify')}}">
                                        </div>
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input ext-injury-check" name="contact_sharpobject" id="external4" value="Y">
                                        Contact with sharp objects
                                        </label>
                                    </div>
                                    <div id="external4_div" class="mt-3 d-none">
                                        <div class="form-group">
                                            <label for="contact_sharpobject_specify" class="form-label"><b class="text-danger">*</b>Specify object</label>
                                            <input type="text" class="form-control" id="external4_specify" name="contact_sharpobject_specify" style="text-transform: uppercase;" value="{{old('contact_sharpobject_specify')}}">
                                        </div>
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input ext-injury-check" name="drowning" id="external5" value="Y">
                                        Drowning
                                        </label>
                                    </div>
                                    <div id="external5_div" class="mt-3 d-none">
                                        <div class="ml-3">
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input drowning-types" name="drowning_type[]" id="drowningcheck1" value="SEA">
                                                Sea
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input drowning-types" name="drowning_type[]" id="drowningcheck2" value="RIVER">
                                                River
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input drowning-types" name="drowning_type[]" id="drowningcheck3" value="LAKE">
                                                Lake
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input drowning-types" name="drowning_type[]" id="drowningcheck4" value="POOL">
                                                Pool
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input drowning-types" name="drowning_type[]" id="drowningcheck5" value="BATH TUB">
                                                Bath Tub
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input drowning-types" name="drowning_type[]" id="drowningcheck6" value="OTHERS">
                                                Others
                                                </label>
                                            </div>
                                            <div class="form-group" id="drowningoth_div">
                                                <label for="drowning_other_specify" class="form-label"><b class="text-danger">*</b>Specify other drowning area</label>
                                                <input type="text" class="form-control" id="external5_specify" name="drowning_other_specify" style="text-transform: uppercase;" value="{{old('drowning_other_specify')}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="exposure_forcesofnature" id="external6" value="Y">
                                        Exposure to forces of nature
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="fall" id="external7" value="Y">
                                        Fall
                                        </label>
                                    </div>
                                    <div id="external7_div" class="mt-3 d-none">
                                        <div class="form-group">
                                            <label for="fall_specify" class="form-label"><b class="text-danger">*</b>Specify</label>
                                            <input type="text" class="form-control" id="external7_specify" name="fall_specify" style="text-transform: uppercase;" value="{{old('fall_specify')}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="firecracker" id="external8" value="Y">
                                        Firecracker
                                        </label>
                                    </div>
                                    <div id="external8_div" class="mt-3 d-none">
                                        <div class="form-group">
                                            <label for="firecracker_specify" class="form-label"><b class="text-danger">*</b>Specify type</label>
                                            <input type="text" class="form-control" id="external8_specify" name="firecracker_specify" style="text-transform: uppercase;" value="{{old('firecracker_specify')}}">
                                        </div>
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="sexual_assault" id="external9" value="Y">
                                        Sexual Assault/Sexual Abuse/Rape (Alleged)
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="gunshot" id="external10" value="Y">
                                        Gunshot
                                        </label>
                                    </div>
                                    <div id="external10_div" class="mt-3 d-none">
                                        <div class="form-group">
                                            <label for="gunshot_specifyweapon" class="form-label"><b class="text-danger">*</b>Specify Weapon</label>
                                            <input type="text" class="form-control" id="external10_specify" name="gunshot_specifyweapon" style="text-transform: uppercase;" value="{{old('gunshot_specifyweapon')}}">
                                        </div>
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="hanging_strangulation" id="external11" value="Y">
                                        Hanging/Strangulation
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="mauling_assault" id="external12" value="Y">
                                        Mauling/Assault
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="transport_vehicular_accident" id="external13" value="Y">
                                        Transport/Vehicular Accident
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="ext_others" id="external14" value="Y">
                                        Others
                                        </label>
                                    </div>
                                    <div id="external14_div" class="mt-3 d-none">
                                        <div class="form-group">
                                            <label for="ext_others_specify" class="form-label"><b class="text-danger">*</b>Specify other causes</label>
                                            <input type="text" class="form-control" id="external14_specify" name="ext_others_specify" style="text-transform: uppercase;" value="{{old('ext_others_specify')}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="va_div" class="d-none">
                        <div class="card mt-3">
                            <div class="card-header"><b>For Transport/Vehicular Accident</b></div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="firstaid_given"><b class="text-danger">*</b>Type of Vehicular Accident</label>
                                            <select class="form-control" name="vehicle_type" id="vehicle_type">
                                                <option value="" disabled {{(is_null(old('vehicle_type'))) ? 'selected' : ''}}>Choose...</option>
                                                <option value="LAND" {{(old('vehicle_type') == 'LAND') ? 'selected' : ''}}>Land</option>
                                                <option value="WATER" {{(old('vehicle_type') == 'WATER') ? 'selected' : ''}}>Water</option>
                                                <option value="AIR" {{(old('vehicle_type') == 'AIR') ? 'selected' : ''}}>Air</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="patients_vehicle_involved"><b class="text-danger">*</b>Type of Vehicular Accident</label>
                                            <select class="form-control" name="patients_vehicle_involved" id="patients_vehicle_involved">
                                                <option value="" disabled {{(is_null(old('patients_vehicle_involved'))) ? 'selected' : ''}}>Choose...</option>
                                                <option value="NONE (PEDESTRIAN)" {{(old('patients_vehicle_involved') == 'NONE (PEDESTRIAN)') ? 'selected' : ''}}>None (Pedestrian)</option>
                                                <option value="CAR" {{(old('patients_vehicle_involved') == 'CAR') ? 'selected' : ''}}>Car</option>
                                                <option value="VAN" {{(old('patients_vehicle_involved') == 'VAN') ? 'selected' : ''}}>Van</option>
                                                <option value="BUS" {{(old('patients_vehicle_involved') == 'BUS') ? 'selected' : ''}}>Bus</option>
                                                <option value="MOTORCYCLE" {{(old('patients_vehicle_involved') == 'MOTORCYCLE') ? 'selected' : ''}}>Motorcycle</option>
                                                <option value="BICYCLE" {{(old('patients_vehicle_involved') == 'BICYCLE') ? 'selected' : ''}}>Bicycle</option>
                                                <option value="TRICYCLE" {{(old('patients_vehicle_involved') == 'TRICYCLE') ? 'selected' : ''}}>Tricycle</option>
                                                <option value="JEEPNEY" {{(old('patients_vehicle_involved') == 'JEEPNEY') ? 'selected' : ''}}>Jeepney</option>
                                                <option value="TRUCK" {{(old('patients_vehicle_involved') == 'TRUCK') ? 'selected' : ''}}>Truck</option>
                                                <option value="OTHERS" {{(old('patients_vehicle_involved') == 'OTHERS') ? 'selected' : ''}}>Others</option>
                                                <option value="UNKNOWN" {{(old('patients_vehicle_involved') == 'UNKNOWN') ? 'selected' : ''}}>Unknown</option>
                                            </select>
                                        </div>
                                        <div class="form-group d-none" id="vi_others_div">
                                            <label for="patients_vehicle_involved_others" class="form-label"><b class="text-danger">*</b>Please specify other vehicle/objects</label>
                                            <input type="text" class="form-control" id="patients_vehicle_involved_others" name="patients_vehicle_involved_others" style="text-transform: uppercase;" value="{{old('patients_vehicle_involved_others')}}">
                                        </div>

                                        <div class="form-group d-none" id="other_vehicle_div">
                                            <label for="other_vehicle_involved"><b class="text-danger">*</b>Other Vehicle/Object Involved (for COLLISION Accident ONLY)</label>
                                            <select class="form-control" name="other_vehicle_involved" id="other_vehicle_involved">
                                                <option value="" disabled {{(is_null(old('other_vehicle_involved'))) ? 'selected' : ''}}>Choose...</option>
                                                <option value="NONE" {{(old('other_vehicle_involved') == 'NONE (PEDESTRIAN)') ? 'selected' : ''}}>None</option>
                                                <option value="CAR" {{(old('other_vehicle_involved') == 'CAR') ? 'selected' : ''}}>Car</option>
                                                <option value="VAN" {{(old('other_vehicle_involved') == 'VAN') ? 'selected' : ''}}>Van</option>
                                                <option value="BUS" {{(old('other_vehicle_involved') == 'BUS') ? 'selected' : ''}}>Bus</option>
                                                <option value="MOTORCYCLE" {{(old('other_vehicle_involved') == 'MOTORCYCLE') ? 'selected' : ''}}>Motorcycle</option>
                                                <option value="BICYCLE" {{(old('other_vehicle_involved') == 'BICYCLE') ? 'selected' : ''}}>Bicycle</option>
                                                <option value="TRICYCLE" {{(old('other_vehicle_involved') == 'TRICYCLE') ? 'selected' : ''}}>Tricycle</option>
                                                <option value="JEEPNEY" {{(old('other_vehicle_involved') == 'JEEPNEY') ? 'selected' : ''}}>Jeepney</option>
                                                <option value="TRUCK" {{(old('other_vehicle_involved') == 'TRUCK') ? 'selected' : ''}}>Truck</option>
                                                <option value="OTHERS" {{(old('other_vehicle_involved') == 'OTHERS') ? 'selected' : ''}}>Others</option>
                                                <option value="UNKNOWN" {{(old('other_vehicle_involved') == 'UNKNOWN') ? 'selected' : ''}}>Unknown</option>
                                            </select>
                                        </div>
                                        <div class="form-group d-none" id="ovi_others_div">
                                            <label for="other_vehicle_involved_others" class="form-label"><b class="text-danger">*</b>Please specify other vehicle/objects</label>
                                            <input type="text" class="form-control" id="other_vehicle_involved_others" name="other_vehicle_involved_others" style="text-transform: uppercase;" value="{{old('patients_vehicle_involved_others')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="collision_type"><b class="text-danger">*</b>Type</label>
                                            <select class="form-control" name="collision_type" id="collision_type">
                                                <option value="" disabled {{(is_null(old('collision_type'))) ? 'selected' : ''}}>Choose...</option>
                                                <option value="COLLISION" {{(old('collision_type') == 'COLLISION') ? 'selected' : ''}}>Collision</option>
                                                <option value="NON-COLLISION" {{(old('collision_type') == 'NON-COLLISION') ? 'selected' : ''}}>Non-Collision</option>
                                            </select>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="patient_position"><b class="text-danger">*</b>Position of Patient</label>
                                                    <select class="form-control" name="patient_position" id="patient_position">
                                                        <option value="" disabled {{(is_null(old('patient_position'))) ? 'selected' : ''}}>Choose...</option>
                                                        <option value="PEDESTRIAN" {{(old('patient_position') == 'PEDESTRIAN') ? 'selected' : ''}}>Pedestrian</option>
                                                        <option value="DRIVER" {{(old('patient_position') == 'DRIVER') ? 'selected' : ''}}>Driver</option>
                                                        <option value="CAPTAIN" {{(old('patient_position') == 'CAPTAIN') ? 'selected' : ''}}>Captain</option>
                                                        <option value="PILOT" {{(old('patient_position') == 'PILOT') ? 'selected' : ''}}>Pilot</option>
                                                        <option value="FRONT PASSENGER" {{(old('patient_position') == 'FRONT PASSENGER') ? 'selected' : ''}}>Front Passenger</option>
                                                        <option value="REAR PASSENGER" {{(old('patient_position') == 'REAR PASSENGER') ? 'selected' : ''}}>Rear Passenger</option>
                                                        <option value="OTHERS" {{(old('patient_position') == 'OTHERS') ? 'selected' : ''}}>Others</option>
                                                        <option value="UNKNOWN" {{(old('patient_position') == 'UNKNOWN') ? 'selected' : ''}}>Unknown</option>
                                                    </select>
                                                </div>
                                                <div class="form-group d-none" id="patient_position_others_div">
                                                    <label for="patient_position_others" class="form-label"><b class="text-danger">*</b>Specify other position of the patient</label>
                                                    <input type="text" class="form-control" id="patient_position_others" name="patient_position_others" style="text-transform: uppercase;" value="{{old('patient_position_others')}}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="placeof_occurrence"><b class="text-danger">*</b>Place of Occurence</label>
                                                    <select class="form-control" name="placeof_occurrence" id="placeof_occurrence">
                                                        <option value="" disabled {{(is_null(old('placeof_occurrence'))) ? 'selected' : ''}}>Choose...</option>
                                                        <option value="HOME" {{(old('placeof_occurrence') == 'HOME') ? 'selected' : ''}}>Home</option>
                                                        <option value="SCHOOL" {{(old('placeof_occurrence') == 'SCHOOL') ? 'selected' : ''}}>School</option>
                                                        <option value="ROAD" {{(old('placeof_occurrence') == 'ROAD') ? 'selected' : ''}}>Road</option>
                                                        <option value="VIDEOKE BARS" {{(old('placeof_occurrence') == 'VIDEOKE BARS') ? 'selected' : ''}}>Videoke Bars</option>
                                                        <option value="WORKPLACE" {{(old('placeof_occurrence') == 'WORKPLACE') ? 'selected' : ''}}>Workplace</option>
                                                        <option value="OTHERS" {{(old('placeof_occurrence') == 'OTHERS') ? 'selected' : ''}}>Others</option>
                                                        <option value="UNKNOWN" {{(old('placeof_occurrence') == 'UNKNOWN') ? 'selected' : ''}}>Unknown</option>
                                                    </select>
                                                </div>
                                                <div class="form-group d-none" id="ppos_workplace_div">
                                                    <label for="placeof_occurrence_workplace_specify" class="form-label"><b class="text-danger">*</b>Name of Workplace</label>
                                                    <input type="text" class="form-control" id="placeof_occurrence_workplace_specify" name="placeof_occurrence_workplace_specify" style="text-transform: uppercase;" value="{{old('placeof_occurrence_workplace_specify')}}">
                                                </div>
                                                <div class="form-group d-none" id="ppos_others_div">
                                                    <label for="placeof_occurrence_others_specify" class="form-label"><b class="text-danger">*</b>Specify other position of the patient</label>
                                                    <input type="text" class="form-control" id="placeof_occurrence_others_specify" name="placeof_occurrence_others_specify" style="text-transform: uppercase;" value="{{old('patient_position_others')}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <h6>Activity of the Patient at the time of the incident:</h6>
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input va-patient-activity" name="activitypatient_duringincident[]" id="patient_activity1" value="SPORTS">
                                                    Sports
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input va-patient-activity" name="activitypatient_duringincident[]" id="patient_activity2" value="LEISURE">
                                                    Leisure
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input va-patient-activity" name="activitypatient_duringincident[]" id="patient_activity3" value="WORK RELATED">
                                                    Work related
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input va-patient-activity" name="activitypatient_duringincident[]" id="patient_activity4" value="OTHERS">
                                                    Others
                                                    </label>
                                                </div>
                                                <div class="form-group mt-3 d-none" id="act_others_div">
                                                    <label for="act_others" class="form-label"><b class="text-danger">*</b>Specify</label>
                                                    <input type="text" class="form-control" id="act_others" name="act_others" style="text-transform: uppercase;" value="{{old('act_others')}}">
                                                </div>
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input va-patient-activity" name="activitypatient_duringincident[]" id="patient_activity5" value="UNKNOWN">
                                                    Unknown
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <h6>Other risk factors at the time of the incident (check all that apply)</h6>
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input va-risk-factor" name="otherrisk_factors[]" id="otherrisk_factors1" value="ALCOHOL/LIQUOR">
                                                    Alcohol/liquor
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input va-risk-factor" name="otherrisk_factors[]" id="otherrisk_factors2" value="USING MOBILE PHONE">
                                                    Using mobile phone
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input va-risk-factor" name="otherrisk_factors[]" id="otherrisk_factors3" value="SLEEPY">
                                                    Sleepy
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input va-risk-factor" name="otherrisk_factors[]" id="otherrisk_factors4" value="SMOKING">
                                                    Smoking
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input va-risk-factor" name="otherrisk_factors[]" id="otherrisk_factors5" value="OTHERS">
                                                    Others
                                                    </label>
                                                </div>
                                                <div class="form-group d-none mt-3" id="rf_others_div">
                                                    <label for="oth_factors_specify" class="form-label"><b class="text-danger">*</b>Specify</label>
                                                    <input type="text" class="form-control" id="oth_factors_specify" name="oth_factors_specify" style="text-transform: uppercase;" value="{{old('oth_factors_specify')}}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <h6>Safety (Check all that apply)</h6>
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input safety-check" name="safety[]" id="safety1" value="NONE">
                                                    None
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input safety-check" name="safety[]" id="safety2" value="AIRBAG">
                                                    Airbag
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input safety-check" name="safety[]" id="safety3" value="HELMET">
                                                    Helmet
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input safety-check" name="safety[]" id="safety4" value="CHILDSEAT">
                                                    Childseat
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input safety-check" name="safety[]" id="safety5" value="SEATBELT">
                                                    Seatbelt
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input safety-check" name="safety[]" id="safety6" value="LIFEJACKET/FLOATATION">
                                                    Life vest/Lifejacker/Floatation device (for drowning)
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input safety-check" name="safety[]" id="safety7" value="OTHERS">
                                                    Others
                                                    </label>
                                                </div>
                                                <div class="form-group mt-3 d-none" id="safety_others_div">
                                                    <label for="safety_others" class="form-label"><b class="text-danger">*</b>Specify</label>
                                                    <input type="text" class="form-control" id="safety_others" name="safety_others" style="text-transform: uppercase;" value="{{old('safety_others')}}">
                                                </div>
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input safety-check" name="safety[]" id="safety8" value="UNKNOWN">
                                                    Unknown
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mt-3">
                        <div class="card-header">
                            <div><b>HOSPITAL/FACILITY DATA</b></div>
                            <div><b>A. ER/OPD/BHS/RHU</b></div>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="transfer_hospital"><b class="text-danger">*</b>Transferred from another hospital/facility</label>
                                <select class="form-control" name="transfer_hospital" id="transfer_hospital" required>
                                    <option value="" disabled {{(is_null(old('transfer_hospital'))) ? 'selected' : ''}}>Choose...</option>
                                    <option value="Y" {{(old('transfer_hospital') == 'Y') ? 'selected' : ''}}>Yes</option>
                                    <option value="N" {{(old('transfer_hospital') == 'N') ? 'selected' : ''}}>No</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="referred_hospital"><b class="text-danger">*</b>Referred by another hospital/facility for laboratory and/or other medical procedures?</label>
                                <select class="form-control" name="referred_hospital" id="referred_hospital" required>
                                    <option value="" disabled {{(is_null(old('referred_hospital'))) ? 'selected' : ''}}>Choose...</option>
                                    <option value="Y" {{(old('referred_hospital') == 'Y') ? 'selected' : ''}}>Yes</option>
                                    <option value="N" {{(old('referred_hospital') == 'N') ? 'selected' : ''}}>No</option>
                                </select>
                            </div>
                            <div id="otherhp_div" class="d-none">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="orig_hospital" class="form-label"><b class="text-danger">*</b>Name of Origination Hospital</label>
                                            <input type="text" class="form-control" id="orig_hospital" name="orig_hospital" style="text-transform: uppercase;" value="{{old('orig_hospital')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="orig_physician" class="form-label">Name of Origination Physician</label>
                                            <input type="text" class="form-control" id="orig_physician" name="orig_physician" value="{{old('orig_physician')}}" style="text-transform: uppercase;">
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            
                            <div class="form-group">
                                <label for="status_reachingfacility"><b class="text-danger">*</b>Status upon reaching Facility/Hospital</label>
                                <select class="form-control" name="status_reachingfacility" id="status_reachingfacility" required>
                                    <option value="" disabled {{(is_null(old('status_reachingfacility'))) ? 'selected' : ''}}>Choose...</option>
                                    <option value="DEAD ON ARRIVAL" {{(old('status_reachingfacility') == 'Y') ? 'selected' : ''}}>Dead On Arrival</option>
                                    <option value="ALIVE" {{(old('status_reachingfacility') == 'N') ? 'selected' : ''}}>Alive</option>
                                </select>
                            </div>
                            <div class="form-group d-none" id="status_alive_div">
                                <label for="ifalive_type"><b class="text-danger">*</b>If alive, specify type</label>
                                <select class="form-control" name="ifalive_type" id="ifalive_type">
                                    <option value="" disabled {{(is_null(old('ifalive_type'))) ? 'selected' : ''}}>Choose...</option>
                                    <option value="CONSCIOUS" {{(old('ifalive_type') == 'CONSCIOUS') ? 'selected' : ''}}>Conscious</option>
                                    <option value="UNCONSCIOUS" {{(old('ifalive_type') == 'UNCONSCIOUS') ? 'selected' : ''}}>Unconscious</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="modeof_transport"><b class="text-danger">*</b>Mode of transport to the Hospital/Facility</label>
                                <select class="form-control" name="modeof_transport" id="modeof_transport" required>
                                    <option value="" disabled {{(is_null(old('modeof_transport'))) ? 'selected' : ''}}>Choose...</option>
                                    <option value="AMBULANCE" {{(old('modeof_transport') == 'AMBULANCE') ? 'selected' : ''}}>Ambulance</option>
                                    <option value="POLICE VEHICLE" {{(old('modeof_transport') == 'POLICE VEHICLE') ? 'selected' : ''}}>Police Vehicle</option>
                                    <option value="PRIVATE VEHICLE" {{(old('modeof_transport') == 'PRIVATE VEHICLE') ? 'selected' : ''}}>Private Vehicle</option>
                                    <option value="OTHERS" {{(old('modeof_transport') == 'OTHERS') ? 'selected' : ''}}>Others</option>
                                </select>
                            </div>
                            <div class="form-group d-none mt-3" id="transport_others_div">
                                <label for="modeof_transport_others" class="form-label"><b class="text-danger">*</b>Specify other mode of transport</label>
                                <input type="text" class="form-control" id="modeof_transport_others" name="modeof_transport_others" style="text-transform: uppercase;" value="{{old('modeof_transport_others')}}">
                            </div>
                            <div class="form-group">
                                <label for="initial_impression">Initial Impression</label>
                                <textarea class="form-control" name="initial_impression" id="initial_impression" rows="3">{{old('initial_impression')}}</textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="icd10_nature" class="form-label">ICD-10 Code/s Nature of Injury</label>
                                        <input type="text" class="form-control" id="icd10_nature" name="icd10_nature" value="{{old('icd10_nature')}}" style="text-transform: uppercase;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="icd10_external" class="form-label">ICD-10 Code/s External Cause of Injury</label>
                                        <input type="text" class="form-control" id="icd10_external" name="icd10_external" value="{{old('icd10_external')}}" style="text-transform: uppercase;">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="disposition"><b class="text-danger">*</b>Disposition</label>
                                        <select class="form-control" name="disposition" id="disposition" required>
                                            <option value="" disabled {{(is_null(old('disposition'))) ? 'selected' : ''}}>Choose...</option>
                                            <option value="ADMITTED" {{(old('disposition') == 'ADMITTED') ? 'selected' : ''}}>Admitted</option>
                                            <option value="TREATED AND SENT HOME" {{(old('disposition') == 'TREATED AND SENT HOME') ? 'selected' : ''}}>Treated and Sent Home</option>
                                            <option value="HAMA" {{(old('disposition') == 'HAMA') ? 'selected' : ''}}>HAMA</option>
                                            <option value="TRANSFERRED TO ANOTHER FACILITY/HOSPITAL" {{(old('disposition') == 'TRANSFERRED TO ANOTHER FACILITY/HOSPITAL') ? 'selected' : ''}}>Transferred to another hospital/facility</option>
                                            <option value="ABSCONDED" {{(old('disposition') == 'ABSCONDED') ? 'selected' : ''}}>Absconded</option>
                                            <option value="REFUSED ADMISSION" {{(old('disposition') == 'REFUSED ADMISSION') ? 'selected' : ''}}>Refused Admission</option>
                                            <option value="DIED" {{(old('disposition') == 'DIED') ? 'selected' : ''}}>Died</option>
                                        </select>
                                    </div>
                                    <div class="form-group d-none" id="dispo_div">
                                        <label for="disposition_transferred" class="form-label"><b class="text-danger">*</b>Specify hospital/facility</label>
                                        <input type="text" class="form-control" id="disposition_transferred" name="disposition_transferred" style="text-transform: uppercase;" value="{{old('disposition_transferred')}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="outcome"><b class="text-danger">*</b>Outcome</label>
                                        <select class="form-control" name="outcome" id="outcome" required>
                                            <option value="" disabled {{(is_null(old('outcome'))) ? 'selected' : ''}}>Choose...</option>
                                            <option value="IMPROVED" {{(old('outcome') == 'IMPROVED') ? 'selected' : ''}}>Improved</option>
                                            <option value="UNIMPROVED" {{(old('outcome') == 'UNIMPROVED') ? 'selected' : ''}}>Unimproved</option>
                                            <option value="DIED" {{(old('outcome') == 'DIED') ? 'selected' : ''}}>Died</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mt-3 d-none" id="inpatient_div">
                        <div class="card-header"><b>IN-PATIENT (for admitted hospital cases only)</b></div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="inp_completefinal_diagnosis">Complete Final Diagnosis</label>
                                <textarea class="form-control" name="inp_completefinal_diagnosis" id="inp_completefinal_diagnosis" rows="3">{{old('inp_completefinal_diagnosis')}}</textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="inp_disposition"><b class="text-danger">*</b>Disposition</label>
                                        <select class="form-control" name="inp_disposition" id="inp_disposition">
                                            <option value="" disabled {{(is_null(old('inp_disposition'))) ? 'selected' : ''}}>Choose...</option>
                                            <option value="ADMITTED" {{(old('inp_disposition') == 'ADMITTED') ? 'selected' : ''}}>Admitted</option>
                                            <option value="TREATED AND SENT HOME" {{(old('inp_disposition') == 'TREATED AND SENT HOME') ? 'selected' : ''}}>Treated and Sent Home</option>
                                            <option value="HAMA" {{(old('inp_disposition') == 'HAMA') ? 'selected' : ''}}>HAMA</option>
                                            <option value="TRANSFERRED TO ANOTHER FACILITY/HOSPITAL" {{(old('inp_disposition') == 'TRANSFERRED TO ANOTHER FACILITY/HOSPITAL') ? 'selected' : ''}}>Transferred to another hospital/facility</option>
                                            <option value="ABSCONDED" {{(old('inp_disposition') == 'ABSCONDED') ? 'selected' : ''}}>Absconded</option>
                                            <option value="REFUSED ADMISSION" {{(old('inp_disposition') == 'REFUSED ADMISSION') ? 'selected' : ''}}>Refused Admission</option>
                                            <option value="DIED" {{(old('inp_disposition') == 'DIED') ? 'selected' : ''}}>Died</option>
                                            <option value="OTHERS" {{(old('inp_disposition') == 'OTHERS') ? 'selected' : ''}}>Others</option>
                                        </select>
                                    </div>
                                    <div class="form-group d-none" id="inp_dispo_other_div">
                                        <label for="inp_disposition_others" class="form-label"><b class="text-danger">*</b>Specify disposition</label>
                                        <input type="text" class="form-control" id="inp_disposition_others" name="inp_disposition_others" style="text-transform: uppercase;" value="{{old('inp_disposition_others')}}">
                                    </div>
                                    <div class="form-group d-none" id="inp_dispo_transferred_div">
                                        <label for="inp_disposition_transferred" class="form-label"><b class="text-danger">*</b>Specify hospital/facility</label>
                                        <input type="text" class="form-control" id="inp_disposition_transferred" name="inp_disposition_transferred" style="text-transform: uppercase;" value="{{old('inp_disposition_transferred')}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="inp_outcome"><b class="text-danger">*</b>Outcome</label>
                                        <select class="form-control" name="inp_outcome" id="inp_outcome">
                                            <option value="" disabled {{(is_null(old('inp_outcome'))) ? 'selected' : ''}}>Choose...</option>
                                            <option value="IMPROVED" {{(old('inp_outcome') == 'IMPROVED') ? 'selected' : ''}}>Improved</option>
                                            <option value="UNIMPROVED" {{(old('inp_outcome') == 'UNIMPROVED') ? 'selected' : ''}}>Unimproved</option>
                                            <option value="DIED" {{(old('inp_outcome') == 'DIED') ? 'selected' : ''}}>Died</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="inp_icd10_nature" class="form-label">ICD-10 Code/s Nature of Injury</label>
                                        <input type="text" class="form-control" id="inp_icd10_nature" name="inp_icd10_nature" value="{{old('inp_icd10_nature')}}" style="text-transform: uppercase;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="inp_icd10_external" class="form-label">ICD-10 Code/s External Cause of Injury</label>
                                        <input type="text" class="form-control" id="inp_icd10_external" name="inp_icd10_external" value="{{old('inp_icd10_external')}}" style="text-transform: uppercase;">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="comments">Comments</label>
                                <textarea class="form-control" name="comments" id="comments" rows="3">{{old('comments')}}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success btn-block">Submit</button>
                </div>
            </div>
        </div>
    </form>

    <script>
        $('form').on('submit', function(e) {
            if ($('.burns-type-checkbox:checked').length === 0 && $('#external2').is(':checked')) {
                e.preventDefault();
                alert('Please check at least one type of burn.');
            }

            if ($('.drowning-types:checked').length === 0 && $('#external5').is(':checked')) {
                e.preventDefault();
                alert('Please check at least one type of drowning.');
            }

            if($('#external13').is(':checked')) {
                if ($('.va-patient-activity:checked').length === 0) {
                    e.preventDefault();
                    alert('Please check at least one Activity of the Patient at the time of the incident in Vehicular Accident Section.');
                }

                if ($('.va-risk-factor:checked').length === 0) {
                    e.preventDefault();
                    alert('Please check at least one Other risk factors at the tiem of the incident in Vehicular Accident Section.');
                }

                if ($('.safety-check:checked').length === 0) {
                    e.preventDefault();
                    alert('Please check at least one Safety in Vehicular Accident Section.');
                }
            }
        });

        $('#external13').change(function (e) { 
            e.preventDefault();
            $('#va_div').addClass('d-none');

            if($(this).is(':checked')) {
                $('#va_div').removeClass('d-none');
                $('#vehicle_type').prop('required', true);
                $('#patients_vehicle_involved').prop('required', true);
                $('#collision_type').prop('required', true);
                $('#patient_position').prop('required', true);
                $('#placeof_occurrence').prop('required', true);
            }

        }).trigger('change');

        $('#collision_type').change(function (e) { 
            e.preventDefault();
            $('#other_vehicle_div').addClass('d-none');
            $('#other_vehicle_involved').prop('required', false);
            
            if($(this).val() == 'COLLISION') {
                $('#other_vehicle_div').removeClass('d-none');
                $('#other_vehicle_involved').prop('required', true);
            }
        }).trigger('change');

        $('#patients_vehicle_involved').change(function (e) { 
            e.preventDefault();
            $('#vi_others_div').addClass('d-none');
            $('#patients_vehicle_involved_others').prop('required', false);

            if($(this).val() == 'OTHERS') {
                $('#vi_others_div').removeClass('d-none');
                $('#patients_vehicle_involved_others').prop('required', true);
            }
        }).trigger('change');

        $('#other_vehicle_involved').change(function (e) { 
            e.preventDefault();
            $('#ovi_others_div').addClass('d-none');
            $('#other_vehicle_involved_others').prop('required', false);

            if($(this).val() == 'OTHERS') {
                $('#ovi_others_div').removeClass('d-none');
                $('#other_vehicle_involved_others').prop('required', true);
            }
        }).trigger('change');

        $('#patient_position').change(function (e) { 
            e.preventDefault();
            $('#patient_position_others_div').addClass('d-none');
            $('#patient_position_others').prop('required', false);

            if($(this).val() == 'OTHERS') {
                $('#patient_position_others_div').removeClass('d-none');
                $('#patient_position_others').prop('required', true);
            }
        }).trigger('change');

        $('#placeof_occurrence').change(function (e) { 
            e.preventDefault();
            $('#ppos_workplace_div').addClass('d-none');
            $('#placeof_occurrence_workplace_specify').prop('required', false);

            $('#ppos_others_div').addClass('d-none');
            $('#placeof_occurrence_others_specify').prop('required', false);

            if($(this).val() == 'WORKPLACE') {
                $('#ppos_workplace_div').removeClass('d-none');
                $('#placeof_occurrence_workplace_specify').prop('required', true);
            }
            else if($(this).val() == 'OTHERS') {
                $('#ppos_others_div').removeClass('d-none');
                $('#placeof_occurrence_others_specify').prop('required', true);
            }
        }).trigger('change');

        $('#patient_activity4').change(function (e) { 
            e.preventDefault();
            $('#act_others_div').addClass('d-none');
            $('#act_others').prop('required', false);

            if($(this).is(':checked')) {
                $('#act_others_div').removeClass('d-none');
                $('#act_others').prop('required', true);
            }
        }).trigger('change');

        $('#otherrisk_factors5').change(function (e) { 
            e.preventDefault();
            $('#rf_others_div').addClass('d-none');
            $('#oth_factors_specify').prop('required', false);

            if($(this).is(':checked')) {
                $('#rf_others_div').removeClass('d-none');
                $('#oth_factors_specify').prop('required', true);
            }
        }).trigger('change');

        $('#safety7').change(function (e) { 
            e.preventDefault();
            $('#safety_others_div').addClass('d-none');
            $('#safety_others').prop('required', false);

            if($(this).is(':checked')) {
                $('#safety_others_div').removeClass('d-none');
                $('#safety_others').prop('required', true);
            }
        }).trigger('change');

        $('#safety1').change(function (e) { 
            e.preventDefault();
            $('#safety2').prop('disabled', false);
            $('#safety3').prop('disabled', false);
            $('#safety4').prop('disabled', false);
            $('#safety5').prop('disabled', false);
            $('#safety6').prop('disabled', false);
            $('#safety7').prop('disabled', false);
            $('#safety8').prop('disabled', false);

            if($(this).is(':checked')) {
                $('#safety2').prop('disabled', true);
                $('#safety3').prop('disabled', true);
                $('#safety4').prop('disabled', true);
                $('#safety5').prop('disabled', true);
                $('#safety6').prop('disabled', true);
                $('#safety7').prop('disabled', true);
                $('#safety8').prop('disabled', true);

                $('#safety2').prop('checked', false);
                $('#safety3').prop('checked', false);
                $('#safety4').prop('checked', false);
                $('#safety5').prop('checked', false);
                $('#safety6').prop('checked', false);
                $('#safety7').prop('checked', false);
                $('#safety8').prop('checked', false);
            }
        }).trigger('change');

        $('#safety8').change(function (e) { 
            e.preventDefault();
            $('#safety2').prop('disabled', false);
            $('#safety3').prop('disabled', false);
            $('#safety4').prop('disabled', false);
            $('#safety5').prop('disabled', false);
            $('#safety6').prop('disabled', false);
            $('#safety7').prop('disabled', false);
            $('#safety1').prop('disabled', false);

            if($(this).is(':checked')) {
                $('#safety2').prop('disabled', true);
                $('#safety3').prop('disabled', true);
                $('#safety4').prop('disabled', true);
                $('#safety5').prop('disabled', true);
                $('#safety6').prop('disabled', true);
                $('#safety7').prop('disabled', true);
                $('#safety1').prop('disabled', true);

                $('#safety2').prop('checked', false);
                $('#safety3').prop('checked', false);
                $('#safety4').prop('checked', false);
                $('#safety5').prop('checked', false);
                $('#safety6').prop('checked', false);
                $('#safety7').prop('checked', false);
                $('#safety1').prop('checked', false);
            }
        }).trigger('change');

        $(document).bind('keydown', function(e) {
            if(e.ctrlKey && (e.which == 83)) {
                e.preventDefault();
                $('#submitBtn').trigger('click');
                $('#submitBtn').prop('disabled', true);
                setTimeout(function() {
                    $('#submitBtn').prop('disabled', false);
                }, 1600);
                return false;
            }
        });

        //Default Values for Gentri
        var regionDefault = 1;
        var provinceDefault = 18;
        var cityDefault = 388;

        $('#address_region_code').change(function (e) { 
            e.preventDefault();

            var regionId = $(this).val();
            var getProvinceUrl = "{{ route('address_get_provinces', ['region_id' => ':regionId']) }}";

            if (regionId) {
                $('#address_province_code').prop('disabled', false);
                $('#address_muncity_code').prop('disabled', true);
                $('#address_brgy_code').prop('disabled', true);

                $('#address_province_code').empty();
                $('#address_muncity_code').empty();
                $('#address_brgy_code').empty();

                $.ajax({
                    url: getProvinceUrl.replace(':regionId', regionId),
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#address_province_code').empty();
                        $('#address_province_code').append('<option value="" disabled selected>Select Province</option>');

                        let sortedData = Object.entries(data).sort((a, b) => {
                            return a[1].localeCompare(b[1]); // Compare province names (values)
                        });

                        $.each(sortedData, function(key, value) {
                            $('#address_province_code').append('<option value="' + value[0] + '">' + value[1] + '</option>');
                        });
                    }
                });
            } else {
                $('#address_province_code').empty();
            }
        }).trigger('change');

        $('#address_province_code').change(function (e) { 
            e.preventDefault();

            var provinceId = $(this).val();
            var getCityUrl = "{{ route('address_get_citymun', ['province_id' => ':provinceId']) }}";

            if (provinceId) {
                $('#address_province_code').prop('disabled', false);
                $('#address_muncity_code').prop('disabled', false);
                $('#address_brgy_code').prop('disabled', true);

                $('#address_muncity_code').empty();
                $('#address_brgy_code').empty();

                $.ajax({
                    url: getCityUrl.replace(':provinceId', provinceId),
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#address_muncity_code').empty();
                        $('#address_muncity_code').append('<option value="" disabled selected>Select City/Municipality</option>');
                        
                        let sortedData = Object.entries(data).sort((a, b) => {
                            return a[1].localeCompare(b[1]); // Compare province names (values)
                        });

                        $.each(sortedData, function(key, value) {
                            $('#address_muncity_code').append('<option value="' + value[0] + '">' + value[1] + '</option>');
                        });
                    }
                });
            } else {
                $('#address_muncity_code').empty();
            }
        });

        $('#address_muncity_code').change(function (e) { 
            e.preventDefault();

            var cityId = $(this).val();
            var getBrgyUrl = "{{ route('address_get_brgy', ['city_id' => ':cityId']) }}";

            if (cityId) {
                $('#address_province_code').prop('disabled', false);
                $('#address_muncity_code').prop('disabled', false);
                $('#address_brgy_code').prop('disabled', false);

                $('#address_brgy_code').empty();

                $.ajax({
                    url: getBrgyUrl.replace(':cityId', cityId),
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#address_brgy_code').empty();
                        $('#address_brgy_code').append('<option value="" disabled selected>Select Barangay</option>');

                        let sortedData = Object.entries(data).sort((a, b) => {
                            return a[1].localeCompare(b[1]); // Compare province names (values)
                        });

                        $.each(sortedData, function(key, value) {
                            $('#address_brgy_code').append('<option value="' + value[0] + '">' + value[1] + '</option>');
                        });
                    }
                });
            } else {
                $('#address_brgy_code').empty();
            }
        });

        if ($('#address_region_code').val()) {
            $('#address_region_code').trigger('change'); // Automatically load provinces on page load
        }

        $('#temp_address_region_code').change(function (e) { 
            e.preventDefault();

            var regionId = $(this).val();
            var getProvinceUrl = "{{ route('address_get_provinces', ['region_id' => ':regionId']) }}";

            if (regionId) {
                $('#temp_address_province_code').prop('disabled', false);
                $('#temp_address_muncity_code').prop('disabled', true);
                $('#temp_address_brgy_code').prop('disabled', true);

                $('#temp_address_province_code').empty();
                $('#temp_address_muncity_code').empty();
                $('#temp_address_brgy_code').empty();

                $.ajax({
                    url: getProvinceUrl.replace(':regionId', regionId),
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#temp_address_province_code').empty();
                        $('#temp_address_province_code').append('<option value="" disabled selected>Select Province</option>');

                        let sortedData = Object.entries(data).sort((a, b) => {
                            return a[1].localeCompare(b[1]); // Compare province names (values)
                        });

                        $.each(sortedData, function(key, value) {
                            $('#temp_address_province_code').append('<option value="' + value[0] + '">' + value[1] + '</option>');
                        });
                    }
                });
            } else {
                $('#temp_address_province_code').empty();
            }
        }).trigger('change');

        $('#temp_address_province_code').change(function (e) { 
            e.preventDefault();

            var provinceId = $(this).val();
            var getCityUrl = "{{ route('address_get_citymun', ['province_id' => ':provinceId']) }}";

            if (provinceId) {
                $('#temp_ddress_province_code').prop('disabled', false);
                $('#temp_address_muncity_code').prop('disabled', false);
                $('#temp_address_brgy_code').prop('disabled', true);

                $('#temp_address_muncity_code').empty();
                $('#temp_address_brgy_code').empty();

                $.ajax({
                    url: getCityUrl.replace(':provinceId', provinceId),
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#temp_address_muncity_code').empty();
                        $('#temp_address_muncity_code').append('<option value="" disabled selected>Select City/Municipality</option>');
                        
                        let sortedData = Object.entries(data).sort((a, b) => {
                            return a[1].localeCompare(b[1]); // Compare province names (values)
                        });

                        $.each(sortedData, function(key, value) {
                            $('#temp_address_muncity_code').append('<option value="' + value[0] + '">' + value[1] + '</option>');
                        });
                    }
                });
            } else {
                $('#temp_address_muncity_code').empty();
            }
        });

        $('#temp_address_muncity_code').change(function (e) { 
            e.preventDefault();

            var cityId = $(this).val();
            var getBrgyUrl = "{{ route('address_get_brgy', ['city_id' => ':cityId']) }}";

            if (cityId) {
                $('#temp_address_province_code').prop('disabled', false);
                $('#temp_address_muncity_code').prop('disabled', false);
                $('#temp_address_brgy_code').prop('disabled', false);

                $('#temp_address_brgy_code').empty();

                $.ajax({
                    url: getBrgyUrl.replace(':cityId', cityId),
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#temp_address_brgy_code').empty();
                        $('#temp_address_brgy_code').append('<option value="" disabled selected>Select Barangay</option>');

                        let sortedData = Object.entries(data).sort((a, b) => {
                            return a[1].localeCompare(b[1]); // Compare province names (values)
                        });

                        $.each(sortedData, function(key, value) {
                            $('#temp_address_brgy_code').append('<option value="' + value[0] + '">' + value[1] + '</option>');
                        });
                    }
                });
            } else {
                $('#temp_address_brgy_code').empty();
            }
        });

        if ($('#temp_address_region_code').val()) {
            $('#temp_address_region_code').trigger('change'); // Automatically load provinces on page load
        }

        if (provinceDefault) {
            setTimeout(function() {
                $('#address_province_code').val(provinceDefault).trigger('change');
                $('#temp_address_province_code').val(provinceDefault).trigger('change');
            }, 1500); // Slight delay to ensure province is loaded
        }
        if (cityDefault) {
            setTimeout(function() {
                $('#address_muncity_code').val(cityDefault).trigger('change');
            }, 2500); // Slight delay to ensure city is loaded
        }

        $('#inj_address_region_code').change(function (e) { 
            e.preventDefault();

            var regionId = $(this).val();
            var getProvinceUrl = "{{ route('address_get_provinces', ['region_id' => ':regionId']) }}";

            if (regionId) {
                $('#inj_address_province_code').prop('disabled', false);
                $('#inj_address_muncity_code').prop('disabled', true);
                $('#inj_address_brgy_code').prop('disabled', true);

                $('#inj_address_province_code').empty();
                $('#inj_address_muncity_code').empty();
                $('#inj_address_brgy_code').empty();

                $.ajax({
                    url: getProvinceUrl.replace(':regionId', regionId),
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#inj_address_province_code').empty();
                        $('#inj_address_province_code').append('<option value="" disabled selected>Select Province</option>');

                        let sortedData = Object.entries(data).sort((a, b) => {
                            return a[1].localeCompare(b[1]); // Compare province names (values)
                        });

                        $.each(sortedData, function(key, value) {
                            $('#inj_address_province_code').append('<option value="' + value[0] + '">' + value[1] + '</option>');
                        });
                    }
                });
            } else {
                $('#inj_address_province_code').empty();
            }
        }).trigger('change');

        $('#inj_address_province_code').change(function (e) { 
            e.preventDefault();

            var provinceId = $(this).val();
            var getCityUrl = "{{ route('address_get_citymun', ['province_id' => ':provinceId']) }}";

            if (provinceId) {
                $('#inj_ddress_province_code').prop('disabled', false);
                $('#inj_address_muncity_code').prop('disabled', false);
                $('#inj_address_brgy_code').prop('disabled', true);

                $('#inj_address_muncity_code').empty();
                $('#inj_address_brgy_code').empty();

                $.ajax({
                    url: getCityUrl.replace(':provinceId', provinceId),
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#inj_address_muncity_code').empty();
                        $('#inj_address_muncity_code').append('<option value="" disabled selected>Select City/Municipality</option>');
                        
                        let sortedData = Object.entries(data).sort((a, b) => {
                            return a[1].localeCompare(b[1]); // Compare province names (values)
                        });

                        $.each(sortedData, function(key, value) {
                            $('#inj_address_muncity_code').append('<option value="' + value[0] + '">' + value[1] + '</option>');
                        });
                    }
                });
            } else {
                $('#inj_address_muncity_code').empty();
            }
        });

        $('#sys_occupationtype').change(function (e) { 
            e.preventDefault();
            if($(this).val() == 'WORKING' || $(this).val() == 'STUDENT') {
                $('#hasOccupation').removeClass('d-none');

                $('#sys_businessorschool_name').prop('required', true);
                $('#sys_businessorschool_address').prop('required', true);
            }
            else {
                $('#hasOccupation').addClass('d-none');

                $('#sys_businessorschool_name').prop('required', false);
                $('#sys_businessorschool_address').prop('required', false);
            }

            if($(this).val() == 'WORKING') {
                $('#occupationNameText').text('Name of Workplace');
                $('#occupationAddressText').text('Address of Workplace');
            }
            else if($(this).val() == 'STUDENT') {
                $('#occupationNameText').text('Name of School');
                $('#occupationAddressText').text('Address of School');
            }
        }).trigger('change');

        $('#ip').change(function (e) { 
            e.preventDefault();
            if($(this).val() == 'Y') {
                $('#ip_div').removeClass('d-none');
                $('#ipgroup').prop('required', true);
            }
            else {
                $('#ip_div').addClass('d-none');
                $('#ipgroup').prop('required', false);
            }
        }).trigger('change');

        $('#same_address').change(function (e) { 
            e.preventDefault();
            $('#temp_div').addClass('d-none');
            $('#temp_address_region_code').prop('required', false);
            $('#temp_address_province_code').prop('required', false);
            $('#temp_address_muncity_code').prop('required', false);
            $('#temp_brgy_code').prop('required', false);

            if($(this).val() == 'N') {
                $('#temp_div').removeClass('d-none');
                $('#temp_address_region_code').prop('required', true);
                $('#temp_address_province_code').prop('required', true);
                $('#temp_address_muncity_code').prop('required', true);
                $('#temp_brgy_code').prop('required', true);
            }
        }).trigger('change');

        $('#firstaid_given').change(function (e) { 
            e.preventDefault();
            $('#firstaid_div').addClass('d-none');
            $('#firstaid_type').prop('required', false);
            $('#firstaid_bywho').prop('required', false);

            if($(this).val() == 'Y') {
                $('#firstaid_div').removeClass('d-none');
                $('#firstaid_type').prop('required', true);
                $('#firstaid_bywho').prop('required', true);
            }
        }).trigger('change');

        $('#injury1').change(function (e) { 
            e.preventDefault();
            if($(this).is(':checked')) {
                $('#injurydiv1').removeClass('d-none');
                $('#injury_site1').prop('required', true);
            }
            else {
                $('#injurydiv1').addClass('d-none');
                $('#injury_site1').prop('required', false);
            }
        }).trigger('change');

        $('#injury2').change(function (e) { 
            e.preventDefault();
            if($(this).is(':checked')) {
                $('#injurydiv2').removeClass('d-none');
                $('#injury_site2').prop('required', true);
            }
            else {
                $('#injurydiv2').addClass('d-none');
                $('#injury_site2').prop('required', false);
            }
        }).trigger('change');

        $('#injury3').change(function (e) { 
            e.preventDefault();
            if($(this).is(':checked')) {
                $('#injurydiv3').removeClass('d-none');
                $('#burn_degree').prop('required', true);
                $('#injury_site3').prop('required', true);
            }
            else {
                $('#injurydiv3').addClass('d-none');
                $('#burn_degree').prop('required', false);
                $('#injury_site3').prop('required', false);
            }
        }).trigger('change');

        $('#injury4').change(function (e) { 
            e.preventDefault();
            if($(this).is(':checked')) {
                $('#injurydiv4').removeClass('d-none');
                $('#injury_site4').prop('required', true);
            }
            else {
                $('#injurydiv4').addClass('d-none');
                $('#injury_site4').prop('required', false);
            }
        }).trigger('change');

        $('#injury5').change(function (e) { 
            e.preventDefault();
            if($(this).is(':checked')) {
                $('#injurydiv5').removeClass('d-none');
                $('#injury_site5').prop('required', true);
            }
            else {
                $('#injurydiv5').addClass('d-none');
                $('#injury_site5').prop('required', false);
            }
        }).trigger('change');

        $('#injury6').change(function (e) { 
            e.preventDefault();
            if($(this).is(':checked')) {
                $('#injurydiv6').removeClass('d-none');
            }
            else {
                $('#injurydiv6').addClass('d-none');
            }
        }).trigger('change');

        $('#fracture1').change(function (e) { 
            e.preventDefault();
            if($(this).is(':checked')) {
                $('#injurydiv6_1').removeClass('d-none');
                $('#injury_site6_1').prop('required', true);
            }
            else {
                $('#injurydiv6_1').addClass('d-none');
                $('#injury_site6_1').prop('required', false);
            }
        }).trigger('change');

        $('#fracture2').change(function (e) { 
            e.preventDefault();
            if($(this).is(':checked')) {
                $('#injurydiv6_2').removeClass('d-none');
                $('#injury_site6_2').prop('required', true);
            }
            else {
                $('#injurydiv6_2').addClass('d-none');
                $('#injury_site6_2').prop('required', false);
            }
        }).trigger('change');

        $('#injury7').change(function (e) { 
            e.preventDefault();
            if($(this).is(':checked')) {
                $('#injurydiv7').removeClass('d-none');
                $('#injury_site7').prop('required', true);
            }
            else {
                $('#injurydiv7').addClass('d-none');
                $('#injury_site7').prop('required', false);
            }
        }).trigger('change');

        $('#injury8').change(function (e) { 
            e.preventDefault();
            if($(this).is(':checked')) {
                $('#injurydiv8').removeClass('d-none');
                $('#injury_site8').prop('required', true);
            }
            else {
                $('#injurydiv8').addClass('d-none');
                $('#injury_site8').prop('required', false);
            }
        }).trigger('change');

        $('#injury9').change(function (e) { 
            e.preventDefault();
            if($(this).is(':checked')) {
                $('#injurydiv9').removeClass('d-none');
                $('#injury_site9').prop('required', true);
            }
            else {
                $('#injurydiv9').addClass('d-none');
                $('#injury_site9').prop('required', false);
            }
        }).trigger('change');

        $('#external1').change(function (e) { 
            e.preventDefault();
            $('#external1_div').addClass('d-none');
            $('#external1_specify').prop('required', false);

            if($(this).is(':checked')) {
                $('#external1_div').removeClass('d-none');
                $('#external1_specify').prop('required', true);
            }
        }).trigger('change');

        $('#external2').change(function (e) { 
            e.preventDefault();
            $('#external2_div').addClass('d-none');

            if($(this).is(':checked')) {
                $('#external2_div').removeClass('d-none');
            }
        }).trigger('change');

        $('#burncheck6').change(function (e) { 
            e.preventDefault();
            $('#extburnsoth_div').addClass('d-none');
            $('#external2_specify').prop('required', false);

            if($(this).is(':checked') && $('#external2').is(':checked')) {
                $('#extburnsoth_div').removeClass('d-none');
                $('#external2_specify').prop('required', true);
            }
        }).trigger('change');

        $('#external3').change(function (e) { 
            e.preventDefault();
            $('#external3_div').addClass('d-none');
            $('#external3_specify').prop('required', false);

            if($(this).is(':checked')) {
                $('#external3_div').removeClass('d-none');
                $('#external3_specify').prop('required', true);
            }
        }).trigger('change');

        $('#external4').change(function (e) { 
            e.preventDefault();
            $('#external4_div').addClass('d-none');
            $('#external4_specify').prop('required', false);

            if($(this).is(':checked')) {
                $('#external4_div').removeClass('d-none');
                $('#external4_specify').prop('required', true);
            }
        }).trigger('change');

        $('#external5').change(function (e) { 
            e.preventDefault();
            $('#external5_div').addClass('d-none');

            if($(this).is(':checked')) {
                $('#external5_div').removeClass('d-none');
            }
        }).trigger('change');

        $('#drowningcheck6').change(function (e) { 
            e.preventDefault();
            $('#drowningoth_div').addClass('d-none');
            $('#external5_specify').prop('required', false);

            if($(this).is(':checked') && $('#external2').is(':checked')) {
                $('#drowningoth_div').removeClass('d-none');
                $('#external5_specify').prop('required', true);
            }
        }).trigger('change');

        $('#external7').change(function (e) { 
            e.preventDefault();
            $('#external7_div').addClass('d-none');
            $('#external7_specify').prop('required', false);

            if($(this).is(':checked')) {
                $('#external7_div').removeClass('d-none');
                $('#external7_specify').prop('required', true);
            }
        }).trigger('change');

        $('#external8').change(function (e) { 
            e.preventDefault();
            $('#external8_div').addClass('d-none');
            $('#external8_specify').prop('required', false);

            if($(this).is(':checked')) {
                $('#external8_div').removeClass('d-none');
                $('#external8_specify').prop('required', true);
            }
        }).trigger('change');

        $('#external10').change(function (e) { 
            e.preventDefault();
            $('#external10_div').addClass('d-none');
            $('#external10_specify').prop('required', false);

            if($(this).is(':checked')) {
                $('#external10_div').removeClass('d-none');
                $('#external10_specify').prop('required', true);
            }
        }).trigger('change');

        $('#external14').change(function (e) { 
            e.preventDefault();
            $('#external14_div').addClass('d-none');
            $('#external14_specify').prop('required', false);

            if($(this).is(':checked')) {
                $('#external14_div').removeClass('d-none');
                $('#external14_specify').prop('required', true);
            }
        }).trigger('change');

        $('#status_reachingfacility').change(function (e) { 
            e.preventDefault();
            $('#ifalive_type').prop('required', false);
            $('#status_alive_div').addClass('d-none');

            if($(this).val() == 'ALIVE') {
                $('#ifalive_type').prop('required', true);
                $('#status_alive_div').removeClass('d-none');
            }
        }).trigger('change');

        $('#modeof_transport').change(function (e) { 
            e.preventDefault();
            $('#transport_others_div').addClass('d-none');
            $('#modeof_transport_others').prop('required', false);

            if($(this).val() == 'OTHERS') {
                $('#transport_others_div').removeClass('d-none');
                $('#modeof_transport_others').prop('required', true);
            }
        }).trigger('change');

        $('#disposition').change(function (e) { 
            e.preventDefault();
            $('#dispo_div').addClass('d-none');
            $('#disposition_transferred').prop('required', false);

            $('#inpatient_div').addClass('d-none');
            $('#inp_completefinal_diagnosis').prop('required', false);
            $('#inp_disposition').prop('required', false);
            $('#inp_outcome').prop('required', false);

            if($(this).val() == 'OTHERS') {
                $('#dispo_div').removeClass('d-none');
                $('#disposition_transferred').prop('required', true);
            }
            else if($(this).val() == 'ADMITTED') {
                $('#inpatient_div').removeClass('d-none');
                $('#inp_completefinal_diagnosis').prop('required', true);
                $('#inp_disposition').prop('required', true);
                $('#inp_outcome').prop('required', true);
            }
        }).trigger('change');
        
        $('#inp_disposition').change(function (e) { 
            e.preventDefault();
            $('#inp_dispo_other_div').addClass('d-none');
            $('#inp_disposition_others').prop('required', false);

            $('#inp_dispo_transferred_div').addClass('d-none');
            $('#inp_disposition_transferred').prop('required', false);

            if($(this).val() == 'OTHERS') {
                $('#inp_dispo_other_div').removeClass('d-none');
                $('#inp_disposition_others').prop('required', true);
            }
            else if($(this).val() == 'TRANSFERRED TO ANOTHER FACILITY/HOSPITAL') {
                $('#inp_dispo_transferred_div').removeClass('d-none');
                $('#inp_disposition_transferred').prop('required', true);
            }
        }).trigger('change');

        $('#transfer_hospital').change(function (e) { 
            e.preventDefault();
            
            $('#otherhp_div').removeClass('d-none');
            $('#orig_hospital').prop('required', false);

            if($(this).val() == 'Y' || $('#referred_hospital').val() == 'Y') {
                $('#otherhp_div').addClass('d-none');
                $('#orig_hospital').prop('required', true);
            }
        }).trigger('change');

        $('#referred_hospital').change(function (e) { 
            e.preventDefault();
            
            $('#otherhp_div').removeClass('d-none');
            $('#orig_hospital').prop('required', false);

            if($(this).val() == 'Y' || $('#transfer_hospital').val() == 'Y') {
                $('#otherhp_div').addClass('d-none');
                $('#orig_hospital').prop('required', true);
            }
        }).trigger('change');
    </script>
@endsection