@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<form action="{{route('abtc_encode_store', ['id' => $d->id])}}" method="POST">
    @csrf
    <div class="container">
        <div class="card">
            <div class="card-header"><strong>Create New Anti-Rabies Vaccination - Patient #{{$d->id}}</strong></div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}}" role="alert">
                    {{session('msg')}}
                </div>
                @endif
                @if($errors->any())
                <div class="alert alert-danger" role="alert">
                    <p>{{Str::plural('Error', $errors->count())}} detected on Encoding:</p>
                    <hr>
                    @foreach ($errors->all() as $error)
                        <li>{{$error}}</li>
                    @endforeach
                </div>
                @endif

                <div class="alert alert-info" role="alert">
                    <b>Note:</b> All Fields marked with an asterisk (<strong class="text-danger">*</strong>) are required fields.
                </div>
                <table class="table table-bordered">
                    <tbody class="text-center">
                        <tr>
                            <td><strong>Name / ID</strong></td>
                            <td><a href="{{route('abtc_patient_edit', ['id' => $d->id])}}">{{$d->getName()}} (#{{$d->id}})</a></td>
                        </tr>
                        <tr>
                            <td><strong>Birthdate/Age/Gender</strong></td>
                            <td>{{(!is_null($d->bdate)) ? date('m-d-Y', strtotime($d->bdate)) : 'N/A'}} / {{$d->getAge()}} / {{$d->sg()}}</td>
                        </tr>
                        <tr>
                            <td><strong>Address</strong></td>
                            <td>{{$d->getAddress()}}</td>
                        </tr>
                        <tr>
                            <td><strong>Contact No.</strong></td>
                            <td>{{(!is_null($d->contact_number)) ? $d->contact_number : 'N/A'}}</td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="vaccination_site_id" class="form-label"><strong class="text-danger">*</strong>Encoded Under</label>
                            <select class="form-select" name="vaccination_site_id" id="vaccination_site_id" required>
                                <option value="" disabled {{(is_null(old('vaccination_site_id'))) ? 'selected' : ''}}>Choose...</option>
                                @foreach($vslist as $vs)
                                <option value="{{$vs->id}}" {{(old('vaccination_site_id', auth()->user()->abtc_default_vaccinationsite_id) == $vs->id) ? 'selected' : ''}}>{{$vs->site_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="case_date" class="form-label"><strong class="text-danger">*</strong>Registration/Case Date</label>
                            <input type="date" class="form-control" name="case_date" id="case_date" min="{{date('Y-01-01', strtotime('-1 Year'))}}" max="{{date('Y-m-d')}}" value="{{old('case_date', date('Y-m-d'))}}" required autofocus>
                            <small class="text-muted">Date patient was first seen, regardless whether patient was given PEP or not.</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="is_booster" class="form-label"><strong class="text-danger">*</strong>Override: Is Booster?</label>
                            <select class="form-select" name="is_booster" id="is_booster" required>
                                <option value="N" {{(old('is_booster') == 'N') ? 'selected' : ''}}>No</option>
                                <option value="Y" {{(old('is_booster') == 'Y') ? 'selected' : ''}}>Yes</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="is_preexp" class="form-label"><strong class="text-danger">*</strong>Is Pre-Exposure?</label>
                            <select class="form-select" name="is_preexp" id="is_preexp" required>
                                <option value="N" {{(old('is_preexp') == 'N') ? 'selected' : ''}}>No</option>
                                <option value="Y" {{(old('is_preexp') == 'Y') ? 'selected' : ''}}>Yes</option>
                            </select>
                        </div>
                        <div id="preexpDiv2" class="d-none">
                            <label for="preexp_type" class="form-label"><strong class="text-danger">*</strong>Pre-Exposure Type</label>
                            <select class="form-select" name="preexp_type" id="preexp_type" required>
                                <option value="" disabled {{is_null(old('preexp_type')) ? 'selected' : ''}}>Choose...</option>
                                <option value="0" {{(old('preexp_type') == '0') ? 'selected' : ''}}>Type 1 - D0, D7, D28</option>
                                <option value="1" {{(old('preexp_type') == '1') ? 'selected' : ''}}>Type 2 - D0, D3, D7</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div id="divpostexp">
                    <hr>
                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="weight" class="form-label"><strong class="text-danger">*</strong>Patient Weight (kg)</label>
                                <input type="number" class="form-control" name="weight" id="weight" step="0.1" value="{{old('weight')}}" min="1" max="900">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div><label for="height" class="form-label"><strong class="text-danger">*</strong>Height (cm)</label></div>
                            <div class="input-group mb-3">
                                <input type="number" class="form-control" name="height" id="height" value="{{old('height')}}" min="1" max="700">
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
                                <input type="date" class="form-control" name="bite_date" id="bite_date" min="2000-01-01" max="{{date('Y-m-d')}}" value="{{old('bite_date')}}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="case_location" class="form-label"><strong id="case_location_ast" class="d-none text-danger">*</strong>Barangay/City (Where biting occured)</label>
                                <input type="text" class="form-control" name="case_location" id="case_location" value="{{old('case_location', $d->address_brgy_text)}}" style="text-transform: uppercase;">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="if_animal_vaccinated" class="form-label"><strong class="text-danger">*</strong>Is the animal already vaccinated within the year?</label>
                                <select class="form-select" name="if_animal_vaccinated" id="if_animal_vaccinated">
                                    <option value="" disabled {{is_null(old('if_animal_vaccinated')) ? 'selected' : ''}}>Choose...</option>
                                    <option value="N" {{(old('if_animal_vaccinated') == 'N') ? 'selected' : ''}}>No</option>
                                    <option value="Y" {{(old('if_animal_vaccinated') == 'Y') ? 'selected' : ''}}>Yes</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="animal_type" class="form-label"><strong class="text-danger">*</strong>Type of Animal</label>
                                <select class="form-select" name="animal_type" id="animal_type">
                                    <option value="" disabled {{is_null(old('animal_type')) ? 'selected' : ''}}>Choose...</option>
                                    <option value="PD" {{(old('animal_type') == 'PD') ? 'selected' : ''}}>Pet Dog (PD)</option>
                                    <option value="PC" {{(old('animal_type') == 'PC') ? 'selected' : ''}}>Pet Cat (PC)</option>
                                    <option value="SD" {{(old('animal_type') == 'SD') ? 'selected' : ''}}>Stray Dog (SD)</option>
                                    <option value="SC" {{(old('animal_type') == 'SC') ? 'selected' : ''}}>Stray Cat (SC)</option>
                                    <option value="O" {{(old('animal_type') == 'O') ? 'selected' : ''}}>Others</option>
                                </select>
                            </div>
                            <div id="ifanimaltype_othersdiv" class="d-none">
                                <div class="mb-3">
                                    <label for="animal_type_others" class="form-label"><strong class="text-danger">*</strong>Others, Please state Animal</label>
                                    <input type="text" class="form-control" name="animal_type_others" id="animal_type_others" value="{{old('animal_type_others')}}" style="text-transform: uppercase;">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="bite_type" class="form-label"><strong class="text-danger">*</strong>Type of Exposure</label>
                                <select class="form-select" name="bite_type" id="bite_type">
                                    <option value="" disabled {{is_null(old('bite_type')) ? 'selected' : ''}}>Choose...</option>
                                    <option value="B" {{(old('bite_type') == 'B') ? 'selected' : ''}}>Bite</option>
                                    <option value="NB" {{(old('bite_type') == 'NB') ? 'selected' : ''}}>Scratch</option>
                                    <option value="CC" {{(old('bite_type') == 'CC') ? 'selected' : ''}}>Close Contact of Rabies Patient</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                              <label for="body_site"><strong class="text-danger">*</strong>Anatomical Location (Body Parts)</label>
                              <select class="form-select" name="body_site[]" id="body_site" multiple>
                                <option value="ABDOMEN" {{ in_array('ABDOMEN', old('body_site', [])) ? 'selected' : '' }}>Abdomen/Tiyan</option>
                                <option value="FOOT" {{ in_array('FOOT', old('body_site', [])) ? 'selected' : '' }}>Foot/Paa</option>
                                <option value="SHOULDER" {{ in_array('SHOULDER', old('body_site', [])) ? 'selected' : '' }}>Shoulder/Balikat</option>
                                <option value="FOREARM/ARM" {{ in_array('FOREARM/ARM', old('body_site', [])) ? 'selected' : '' }}>Forearm/Arm/Braso</option>
                                <option value="HAND" {{ in_array('HAND', old('body_site', [])) ? 'selected' : '' }}>Hand/Kamay</option>
                                <option value="FINGER" {{ in_array('FINGER', old('body_site', [])) ? 'selected' : '' }}>Finger/Daliri</option>
                                <option value="HEAD" {{ in_array('HEAD', old('body_site', [])) ? 'selected' : '' }}>Head/Face/Ulo/Mukha</option>
                                <option value="KNEE" {{ in_array('KNEE', old('body_site', [])) ? 'selected' : '' }}>Knee/Tuhod</option>
                                <option value="THIGH" {{ in_array('KNEE', old('body_site', [])) ? 'selected' : '' }}>Thigh/Hita</option>
                                <option value="LEGS" {{ in_array('LEGS', old('body_site', [])) ? 'selected' : '' }}>Legs/Binti</option>
                                <option value="NECK" {{ in_array('NECK', old('body_site', [])) ? 'selected' : '' }}>Neck/Leeg</option>
                                <option value="GENITAL" {{ in_array('GENITAL', old('body_site', [])) ? 'selected' : '' }}>Genital/Ari</option>
                              </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="category_level" class="form-label"><strong class="text-danger">*</strong>Category</label>
                                <select class="form-select" name="category_level" id="category_level">
                                    <option value="" disabled {{is_null(old('category_level')) ? 'selected' : ''}}>Choose...</option>
                                    <!--<option value="1" {{(old('category_level') == 1) ? 'selected' : ''}}>Category 1</option>-->
                                    <option value="2" {{(old('category_level') == 2) ? 'selected' : ''}}>Category 2</option>
                                    <option value="3" {{(old('category_level') == 3) ? 'selected' : ''}}>Category 3</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="washing_of_bite" class="form-label"><strong class="text-danger">*</strong>Washing of Bite</label>
                                <select class="form-select" name="washing_of_bite" id="washing_of_bite" required>
                                    <option value="Y" {{(old('washing_of_bite') == 'Y') ? 'selected' : ''}}>Yes</option>
                                    <option value="N" {{(old('washing_of_bite') == 'N') ? 'selected' : ''}}>No</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="rig_date_given" class="form-label">RIG Date Given <small><i>(If Applicable)</i></small></label>
                                <input type="date" class="form-control" name="rig_date_given" id="rig_date_given" min="2000-01-01" max="{{date('Y-m-d')}}" value="{{old('rig_date_given')}}">
                            </div>
                        </div>
                    </div>
                    
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="d0_date" class="form-label"><strong class="text-danger">*</strong>First Vaccine / Day 0 Date</label>
                            <input type="date" class="form-control" name="d0_date" id="d0_date" min="{{$d->bdate}}" max="{{date('Y-m-d')}}" value="{{old('d0_date')}}" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="brand_name" class="form-label"><strong class="text-danger">*</strong>Brand Name</label>
                            <select class="form-select" name="brand_name" id="brand_name" required>
                                <option value="" disabled {{is_null(old('brand_name')) ? 'selected' : ''}}>Choose...</option>
                                @foreach($vblist as $v)
                                <option value="{{$v->brand_name}}" {{(old('brand_name', auth()->user()->abtcGetDefaultVaccine()->brand_name) == $v->brand_name) ? 'selected' : ''}} {{($v->ifHasStock()) ? '' : 'disabled'}}>{{$v->brand_name}} {{($v->ifHasStock()) ? '' : ' - OUT OF STOCK'}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="pep_route" class="form-label"><strong class="text-danger">*</strong>Route</label>
                            <select class="form-select" name="pep_route" id="pep_route" required>
                                <option value="ID" {{(old('pep_route') == 'ID') ? 'selected' : ''}}>ID - Intradermal</option>
                                <option value="IM" {{(old('pep_route') == 'IM') ? 'selected' : ''}}>IM - Intramuscular</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                          <label for="d0_vaccinated_inbranch"><strong class="text-danger">*</strong>D0 was vaccinated here?</label>
                          <select class="form-select" name="d0_vaccinated_inbranch" id="d0_vaccinated_inbranch" required>
                            <option value="" disabled {{is_null(old('d0_vaccinated_inbranch')) ? 'selected' : ''}}>Choose...</option>
                            <option value="Y" {{(old('d0_vaccinated_inbranch') == 'Y') ? 'selected' : ''}}>Yes</option>
                            <option value="N" {{(old('d0_vaccinated_inbranch') == 'N') ? 'selected' : ''}}>No (Other Clinic)</option>
                          </select>
                        </div>
                    </div>
                    <small class="text-muted">
                        <ul>
                            <b class="text-danger">Note:</b>
                            <li>Input the Actual Day 0 Date regardless if patient was vaccinated here or not.</li>
                            <li>Dates Day 3, Day 7 onwards will be automatically given after you finish the encoding.</li>
                        </ul>
                    </small>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="outcome" class="form-label"><strong class="text-danger">*</strong>Outcome</label>
                            <select class="form-select" name="outcome" id="outcome" required>
                                <option value="INC" {{(old('pep_route') == 'INC') ? 'selected' : ''}}>Incomplete (INC)</option>
                                <option value="D" {{(old('pep_route') == 'D') ? 'selected' : ''}}>Died (D)</option>
                            </select>
                            <small class="text-muted">Will be automatically changed based on completed doses.</small>
                        </div>
                        <div id="ifpatientdied" class="d-none">
                            <div>
                                <label for="date_died" class="form-label"><strong class="text-danger">*</strong>Date Patient Died</label>
                                <input type="date" class="form-control" name="date_died" id="date_died" min="{{$d->bdate}}" max="{{date('Y-m-d')}}" value="{{old('date_died')}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="biting_animal_status" class="form-label"><strong class="text-danger">*</strong>Biting Animal Status (After 14 Days)</label>
                            <select class="form-select" name="biting_animal_status" id="biting_animal_status" required>
                                <option value="N/A" {{(old('biting_animal_status') == 'N/A') ? 'selected' : ''}}>N/A</option>
                                <option value="ALIVE" {{(old('biting_animal_status') == 'ALIVE') ? 'selected' : ''}}>Alive</option>
                                <option value="DEAD" {{(old('biting_animal_status') == 'DEAD') ? 'selected' : ''}}>Dead</option>
                                <option value="LOST" {{(old('biting_animal_status') == 'LOST') ? 'selected' : ''}}>Lost/Unknown</option>
                            </select>
                        </div>
                        <div id="ifdogdied" class="d-none">
                            <label for="animal_died_date" class="form-label"><strong class="text-danger">*</strong>Date Animal Died</label>
                            <input type="date" class="form-control" name="animal_died_date" id="animal_died_date" min="{{$d->bdate}}" max="{{date('Y-m-d')}}" value="{{old('animal_died_date')}}">
                        </div>
                    </div>
                </div>
                <hr>
                <div class="mb-3">
                    <label for="remarks" class="form-label">Remarks <small><i>(If Applicable)</i></small></label>
                    <textarea class="form-control" name="remarks" id="remarks" rows="3">{{old('remarks')}}</textarea>
                </div>
            </div>
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-success btn-block" id="submitbtn"><i class="fas fa-save mr-2"></i>Save (CTRL + S)</button>
            </div>
        </div>
    </div>
</form>

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
            $('#rig_date_given').val("{{date('Y-m-d')}}");
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