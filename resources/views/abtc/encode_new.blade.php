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
                    <div class="col-md-4">
                        <div>
                            <label for="vaccination_site_id" class="form-label"><strong class="text-danger">*</strong>Encoded Under</label>
                            <select class="form-select" name="vaccination_site_id" id="vaccination_site_id" required>
                                <option value="" disabled {{(is_null(old('vaccination_site_id'))) ? 'selected' : ''}}>Choose...</option>
                                @foreach($vslist as $vs)
                                <option value="{{$vs->id}}" {{(old('vaccination_site_id', auth()->user()->abtc_default_vaccinationsite_id) == $vs->id) ? 'selected' : ''}}>{{$vs->site_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div>
                            <label for="case_date" class="form-label"><strong class="text-danger">*</strong>Registration Date</label>
                            <input type="date" class="form-control" name="case_date" id="case_date" min="{{date('Y-01-01', strtotime('-1 Year'))}}" max="{{date('Y-m-d')}}" value="{{old('case_date')}}" required>
                            <small class="text-muted">Date patient was first seen, regardless whether patient was given PEP or not.</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="is_booster" class="form-label"><strong class="text-danger">*</strong>Override: Is Booster?</label>
                            <select class="form-select" name="is_booster" id="is_booster" required>
                                <option value="N" {{(old('is_booster') == 'N') ? 'selected' : ''}}>No</option>
                                <option value="Y" {{(old('is_booster') == 'Y') ? 'selected' : ''}}>Yes</option>
                            </select>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="animal_type" class="form-label"><strong class="text-danger">*</strong>Type of Animal</label>
                            <select class="form-select" name="animal_type" id="animal_type" required>
                                <option value="" disabled {{is_null(old('animal_type')) ? 'selected' : ''}}>Choose...</option>
                                <option value="PD" {{(old('animal_type') == 'PD') ? 'selected' : ''}}>Pet Dog (PD)</option>
                                <option value="SD" {{(old('animal_type') == 'SD') ? 'selected' : ''}}>Stray Dog (SD)</option>
                                <option value="PC" {{(old('animal_type') == 'PC') ? 'selected' : ''}}>Pet Cat (PC)</option>
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
                            <label for="bite_type" class="form-label"><strong class="text-danger">*</strong>Type of Bite</label>
                            <select class="form-select" name="bite_type" id="bite_type" required>
                                <option value="B" {{(old('bite_type') == 'B') ? 'selected' : ''}}>Bite (B)</option>
                                <option value="NB" {{(old('bite_type') == 'NB') ? 'selected' : ''}}>None Bite (NB)</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="if_animal_vaccinated" class="form-label"><strong class="text-danger">*</strong>Is the animal already vaccinated within the year?</label>
                            <select class="form-select" name="if_animal_vaccinated" id="if_animal_vaccinated" required>
                                <option value="" disabled {{is_null(old('if_animal_vaccinated')) ? 'selected' : ''}}>Choose...</option>
                                <option value="N" {{(old('if_animal_vaccinated') == 'N') ? 'selected' : ''}}>No</option>
                                <option value="Y" {{(old('if_animal_vaccinated') == 'Y') ? 'selected' : ''}}>Yes</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="bite_date" class="form-label"><strong class="text-danger">*</strong>Date of Exposure/Bite Date</label>
                            <input type="date" class="form-control" name="bite_date" id="bite_date" min="2000-01-01" max="{{date('Y-m-d')}}" value="{{old('bite_date')}}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="case_location" class="form-label"><strong id="case_location_ast" class="d-none text-danger">*</strong>Place (Where biting occured)</label>
                            <input type="text" class="form-control" name="case_location" id="case_location" value="{{old('case_location')}}" style="text-transform: uppercase;">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="body_site" class="form-label">Site (Body Parts)</label>
                            <input type="text" class="form-control" name="body_site" id="body_site" value="{{old('body_site')}}" style="text-transform: uppercase;">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="category_level" class="form-label"><strong class="text-danger">*</strong>Category</label>
                            <select class="form-select" name="category_level" id="category_level" required>
                                <option value="" disabled {{is_null(old('category_level')) ? 'selected' : ''}}>Choose...</option>
                                <!--<option value="1" {{(old('category_level') == 1) ? 'selected' : ''}}>Category 1</option>-->
                                <option value="2" {{(old('category_level') == 2) ? 'selected' : ''}}>Category 2 (Scratches)</option>
                                <option value="3" {{(old('category_level') == 3) ? 'selected' : ''}}>Category 3 (Bleeding)</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="washing_of_bite" class="form-label"><strong class="text-danger">*</strong>Washing of Bite</label>
                            <select class="form-select" name="washing_of_bite" id="washing_of_bite" required>
                                <option value="Y" {{(old('washing_of_bite') == 'Y') ? 'selected' : ''}}>Yes</option>
                                <option value="N" {{(old('washing_of_bite') == 'N') ? 'selected' : ''}}>No</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="rig_date_given" class="form-label">RIG Date Given <small><i>(If Applicable)</i></small></label>
                            <input type="date" class="form-control" name="rig_date_given" id="rig_date_given" min="2000-01-01" max="{{date('Y-m-d')}}" value="{{old('rig_date_given')}}">
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="d0_date" class="form-label"><strong class="text-danger">*</strong>First Vaccine / Day 0 Date</label>
                            <input type="date" class="form-control" name="d0_date" id="d0_date" min="{{date('Y-01-01', strtotime('-1 Year'))}}" max="{{date('Y-m-d')}}" value="{{old('d0_date')}}" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="brand_name" class="form-label"><strong class="text-danger">*</strong>Brand Name</label>
                            <select class="form-select" name="brand_name" id="brand_name" required>
                                <option value="" disabled {{is_null(old('brand_name')) ? 'selected' : ''}}>Choose...</option>
                                @foreach($vblist as $v)
                                <option value="{{$v->brand_name}}" {{(old('brand_name') == $v->brand_name) ? 'selected' : ''}}>{{$v->brand_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="pep_route" class="form-label"><strong class="text-danger">*</strong>Route</label>
                            <select class="form-select" name="pep_route" id="pep_route" required>
                                <option value="ID" {{(old('pep_route') == 'ID') ? 'selected' : ''}}>ID - Intradermal</option>
                                <option value="IM" {{(old('pep_route') == 'IM') ? 'selected' : ''}}>IM - Intramuscular</option>
                            </select>
                        </div>
                    </div>
                    <small class="text-muted">Note: Schedule date for Day 3, Day 7 onwards will be automatically given after you finish the encoding.</small>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <div class="">
                            <label for="outcome" class="form-label"><strong class="text-danger">*</strong>Outcome</label>
                            <select class="form-select" name="outcome" id="outcome" required>
                                <option value="INC" {{(old('pep_route') == 'INC') ? 'selected' : ''}}>Incomplete (INC)</option>
                                <option value="D" {{(old('pep_route') == 'D') ? 'selected' : ''}}>Died (D)</option>
                            </select>
                            <small class="text-muted">Will be automatically changed based on completed doses.</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="">
                            <label for="biting_animal_status" class="form-label"><strong class="text-danger">*</strong>Biting Animal Status (After 14 Days)</label>
                            <select class="form-select" name="biting_animal_status" id="biting_animal_status" required>
                                <option value="N/A" {{(old('biting_animal_status') == 'N/A') ? 'selected' : ''}}>N/A</option>
                                <option value="ALIVE" {{(old('biting_animal_status') == 'ALIVE') ? 'selected' : ''}}>Alive</option>
                                <option value="DEAD" {{(old('biting_animal_status') == 'DEAD') ? 'selected' : ''}}>Dead</option>
                                <option value="LOST" {{(old('biting_animal_status') == 'LOST') ? 'selected' : ''}}>Lost</option>
                            </select>
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
            $('#body_site').prop('required', false);

            //$('#body_site_ast').removeClass('d-none');
            $('#case_location_ast').removeClass('d-none');
        }
        else if($(this).val() == 'NB') {
            $('#case_location').prop('required', false);
            $('#body_site').prop('required', false);

            //$('#body_site_ast').addClass('d-none');
            $('#case_location_ast').addClass('d-none');
        }
        else {
            $('#case_location').prop('required', false);
            $('#body_site').prop('required', false);

            //$('#body_site_ast').addClass('d-none');
            $('#case_location_ast').addClass('d-none');
        }
    }).trigger('change');
</script>
@endsection