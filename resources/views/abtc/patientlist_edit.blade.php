@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
@if(auth()->user()->isAdmin == 1)
<div class="container">
    <form action="{{route('abtc_patient_destroy', [$d->id])}}" method="POST">
        @csrf
        @method('delete')
        <div class="text-right mb-3">
            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to DELETE? Click OK to Confirm.')"><i class="fa fa-trash mr-2" aria-hidden="true"></i>Delete Patient Record</button>
        </div>
    </form>
</div>
@endif
<form action="{{route('abtc_patient_update', ['id' => $d->id])}}" method="POST">
    @csrf
    <div class="container">
        <div class="card">
            <div class="card-header"><strong><i class="fa-solid fa-user-gear me-2"></i>Edit Patient</strong></div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}}" role="alert">
                    {{session('msg')}}
                </div>
                @endif
                @if($bcheck)
                <div class="d-grid gap-2">
                    <a href="{{route('abtc_patient_viewbakunarecords', ['id' => $d->id])}}" class="btn btn-primary"><i class="fas fa-syringe mr-2"></i>View Bakuna Records of Patient</a>
                </div>
                <hr>
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
                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="lname" class="form-label"><b class="text-danger">*</b>Last Name</label>
                            <input type="text" class="form-control" name="lname" id="lname" value="{{old('lname', $d->lname)}}" maxlength="50" placeholder="e.g DELA CRUZ" style="text-transform: uppercase;" required autofocus>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="fname" class="form-label"><b class="text-danger">*</b>First Name</label>
                            <input type="text" class="form-control" name="fname" id="fname" value="{{old('fname' , $d->fname)}}" maxlength="50" placeholder="e.g JUAN" style="text-transform: uppercase;" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="mname" class="form-label">Middle Name</label>
                            <input type="text" class="form-control" name="mname" id="mname" value="{{old('mname' , $d->mname ?: 'N/A')}}" placeholder="e.g SANCHEZ" style="text-transform: uppercase;" maxlength="50" required>
                            <i><small>(Type <span class="text-danger">N/A</span> if Not Applicable)</small></i>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="suffix"><b class="text-danger">*</b>Suffix</label>
                            <select class="form-select" name="suffix" id="suffix" required>
                                <option value="I" {{(old('suffix', $d->suffix) == 'I') ? 'selected' : ''}}>I</option>
                                <option value="II" {{(old('suffix', $d->suffix) == 'II') ? 'selected' : ''}}>II</option>
                                <option value="III" {{(old('suffix', $d->suffix) == 'III') ? 'selected' : ''}}>III</option>
                                <option value="IV" {{(old('suffix', $d->suffix) == 'IV') ? 'selected' : ''}}>IV</option>
                                <option value="V" {{(old('suffix', $d->suffix) == 'V') ? 'selected' : ''}}>V</option>
                                <option value="VI" {{(old('suffix', $d->suffix) == 'VI') ? 'selected' : ''}}>VI</option>
                                <option value="VII" {{(old('suffix', $d->suffix) == 'VII') ? 'selected' : ''}}>VII</option>
                                <option value="VIII" {{(old('suffix', $d->suffix) == 'VIII') ? 'selected' : ''}}>VIII</option>
                                <option value="JR" {{(old('suffix', $d->suffix) == 'JR') ? 'selected' : ''}}>JR</option>
                                <option value="JR II" {{(old('suffix', $d->suffix) == 'JR II') ? 'selected' : ''}}>JR II</option>
                                <option value="SR" {{(old('suffix', $d->suffix) == 'SR') ? 'selected' : ''}}>SR</option>
                                <option value="N/A" {{(old('suffix', $d->suffix ?: 'N/A') == 'N/A') ? 'selected' : ''}}>N/A (NOT APPLICABLE)</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3 d-none">
                          <label for="has_bday" class="form-label"><span class="text-danger font-weight-bold">*</span>Has Birthday</label>
                          <select class="form-select" name="has_bday" id="has_bday" required>
                            <option value="Yes" {{(old('has_bday', !is_null($d->bdate)) == 'Yes') ? 'selected' : ''}}>Yes</option>
                          </select>
                        </div>
                        <div class="mb-3 d-none" id="ybday">
                            <label for="bdate" class="form-label"><b class="text-danger">*</b>Birthdate</label>
                            <input type="date" class="form-control" name="bdate" id="bdate" value="{{old('bdate', $d->bdate)}}" min="1900-01-01" max="{{date('Y-m-d', strtotime('-21 Days'))}}" required>  
                        </div>
                        <div class="mb-3 d-none" id="nbday">
                            <label for="age" class="form-label"><b class="text-danger">*</b>Age (In Years)</label>
                            <input type="number" class="form-control" name="age" id="age" value="{{old('age', $d->age)}}" min="0" max="150">  
                        </div>
                        <p>Age: {{$d->getAge()}}</p>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="gender" class="form-label"><b class="text-danger">*</b>Gender</label>
                            <select class="form-select" name="gender" id="gender" required>
                                <option value="" disabled {{(is_null(old('gender', $d->gender))) ? 'selected' : ''}}>Choose...</option>
                                <option value="MALE" {{(old('gender', $d->gender) == 'MALE') ? 'selected' : ''}}>Male</option>
                                <option value="FEMALE" {{(old('gender', $d->gender) == 'FEMALE') ? 'selected' : ''}}>Female</option>
                            </select>
                        </div>
                        <div class="form-group d-none" id="pregnantDiv">
                            <label for="is_pregnant"><b class="text-danger">*</b>Is Pregnant?</label>
                            <select class="form-select" name="is_pregnant" id="is_pregnant">
                              <option value="" disabled {{(is_null(old('is_pregnant'))) ? 'selected' : ''}}>Choose...</option>
                              <option value="Y" {{(old('is_pregnant') == 'Y') ? 'selected' : ''}}>Yes</option>
                              <option value="N" {{(old('is_pregnant') == 'N') ? 'selected' : ''}}>No</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="contact_number" class="form-label"><b class="text-danger">*</b>Contact Number</label>
                            <input type="text" class="form-control" id="contact_number" name="contact_number" value="{{old('contact_number', $d->contact_number)}}" pattern="[0-9]{11}" placeholder="09xxxxxxxxx" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="philhealth" class="form-label">Philhealth <small><i>(If Applicable)</i></small></label>
                            <input type="text" class="form-control" id="philhealth" name="philhealth" value="{{old('philhealth', $d->philhealth)}}" pattern="[0-9]{12}">
                        </div>
                    </div>
                </div>
                <hr>
                <div id="address_text" class="d-none">
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" id="address_region_text" name="address_region_text" value="{{old('address_region_text', $d->address_region_text)}}" readonly>
                        </div>
                        <div class="col-md-6">
                            <input type="text" id="address_province_text" name="address_province_text" value="{{old('address_province_text', $d->address_province_text)}}" readonly>
                        </div>
                        <div class="col-md-6">
                            <input type="text" id="address_muncity_text" name="address_muncity_text" value="{{old('address_muncity_text', $d->address_muncity_text)}}"readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                          <label for="address_region_code" class="form-label"><b class="text-danger">*</b>Region</label>
                          <select class="form-select" name="address_region_code" id="address_region_code" tabindex="-1" required>
                          </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="address_province_code" class="form-label"><b class="text-danger">*</b>Province</label>
                            <select class="form-select" name="address_province_code" id="address_province_code" tabindex="-1" required>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="address_muncity_code" class="form-label"><b class="text-danger">*</b>City/Municipality</label>
                            <select class="form-select" name="address_muncity_code" id="address_muncity_code" tabindex="-1" required>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="address_brgy_text" class="form-label"><b class="text-danger">*</b>Barangay</label>
                            <select class="form-select" name="address_brgy_text" id="address_brgy_text" required>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="address_houseno" class="form-label"><b class="text-danger">*</b>House No./Lot/Building</label>
                            <input type="text" class="form-control" id="address_houseno" name="address_houseno" style="text-transform: uppercase;" value="{{old('address_houseno', $d->address_houseno)}}" pattern="(^[a-zA-Z0-9 ]+$)+" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="address_street" class="form-label"><b class="text-danger">*</b>Street/Subdivision/Purok/Sitio</label>
                            <input type="text" class="form-control" id="address_street" name="address_street" style="text-transform: uppercase;" value="{{old('address_street', $d->address_street)}}" pattern="(^[a-zA-Z0-9 ]+$)+" required>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-9">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="is_indg" name="is_indg" value="Y" {{(old('is_indg', $d->is_indg) == 'Y') ? 'checked' : ''}}>
                            <label class="form-check-label">Indigenous People</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="is_4ps" name="is_4ps" value="Y" {{(old('is_4ps', $d->is_indg) == 'Y') ? 'checked' : ''}}>
                            <label class="form-check-label">4Ps Member</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="is_nhts" name="is_nhts" value="Y" {{(old('is_nhts', $d->is_nhts) == 'Y') ? 'checked' : ''}}>
                            <label class="form-check-label">NHTS</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="is_pwd" name="is_pwd" value="Y" {{(old('is_pwd', $d->is_pwd) == 'Y') ? 'checked' : ''}}>
                            <label class="form-check-label">PWD</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="is_singleparent" name="is_singleparent" value="Y" {{(old('is_singleparent', $d->is_singleparent) == 'Y') ? 'checked' : ''}}>
                            <label class="form-check-label">Single Parent</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="is_others" name="is_others" value="Y" {{(old('is_others', $d->is_others) == 'Y') ? 'checked' : ''}}>
                            <label class="form-check-label">Others</label>
                        </div>
                    </div>
                    <div class="col-3">
                        <div id="ifCheckboxOthersDiv" class="d-none">
                            <div class="form-group">
                                <label for="is_others_specify"><b class="text-danger">*</b>Specify</label>
                                <input type="text" class="form-control" name="is_others_specify" id="is_others_specify" minlength="1" maxlength="100" value="{{old('is_others_specify', $d->is_others_specify)}}" style="text-transform: uppercase;" >
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="mb-3">
                  <label for="remarks" class="form-label">Remarks <i>(If Applicable)</i></label>
                  <textarea class="form-control" name="remarks" id="remarks" rows="3">{{old('remarks', $d->remarks)}}</textarea>
                </div>
            </div>
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-success btn-block" id="submitbtn"><i class="fas fa-save mr-2"></i>Update (CTRL + S)</button>
            </div>
        </div>
    </div>
</form>

<script>
    // --- helper (put ABOVE $(document).ready) ---
    const jsonCache = {};
    function getJSONWithRetry(url, { retries = 3, timeout = 20000, backoff = 600 } = {}) {
        if (jsonCache[url]) return jsonCache[url];

        const attempt = (n) => $.ajax({ url, dataType: "json", cache: true, timeout })
            .catch((jqxhr, textStatus, error) => {
            if (n <= 0) return $.Deferred().reject(jqxhr, textStatus, error).promise();
            const delay = backoff * Math.pow(2, (retries - n));
            return $.Deferred(d => setTimeout(() => attempt(n - 1).then(d.resolve).catch(d.reject), delay)).promise();
            });

        jsonCache[url] = attempt(retries);
        return jsonCache[url];
    }

    function setLoading($el, disabled = true) {
        $el.empty()
        .append('<option value="" selected disabled>Loading...</option>')
        .prop('disabled', disabled);
    }

    function setChoose($el, disabled = false) {
        $el.empty()
        .append('<option value="" selected disabled>Choose...</option>')
        .prop('disabled', disabled);
    }

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

    //Select2 Init for Address Bar
    $('#address_region_code, #address_province_code, #address_muncity_code, #address_brgy_text, #suffix').select2({
        theme: 'bootstrap',
    });

    $(document).ready(function () {
        // EDIT defaults (works also with old())
        var rdefault = "{{ old('address_region_code', $d->address_region_code) }}";
        var pdefault = "{{ old('address_province_code', $d->address_province_code) }}";
        var cdefault = "{{ old('address_muncity_code', $d->address_muncity_code) }}";
        var bdefault = "{{ strtoupper(old('address_brgy_text', $d->address_brgy_text)) }}";

        const regionUrl  = "{{ asset('json/refregion.json') }}";
        const provUrl    = "{{ asset('json/refprovince.json') }}";
        const citymunUrl = "{{ asset('json/refcitymun.json') }}";
        const brgyUrl    = "{{ asset('json/refbrgy.json') }}";

        // Init placeholders
        setLoading($('#address_region_code'), true);
        setChoose($('#address_province_code'), true);
        setChoose($('#address_muncity_code'), true);
        setChoose($('#address_brgy_text'), true);

        function loadRegions(selectedReg) {
            return getJSONWithRetry(regionUrl).then(function (regions) {
            const $reg = $('#address_region_code');
            setChoose($reg, false);

            regions.sort((a,b) => (a.regDesc||'').localeCompare(b.regDesc||''));
            regions.forEach(r => $reg.append(new Option(r.regDesc, r.regCode)));

            if (selectedReg) $reg.val(selectedReg);

            $('#address_region_text').val($('#address_region_code option:selected').text() || '');
            return $reg.val();
            });
        }

        function loadProvinces(regCode, selectedProv) {
            const $prov = $('#address_province_code');
            const $city = $('#address_muncity_code');
            const $brgy = $('#address_brgy_text');

            setLoading($prov, false);
            setChoose($city, true);
            setChoose($brgy, true);

            return getJSONWithRetry(provUrl).then(function (provinces) {
            setChoose($prov, false);

            provinces.sort((a,b) => (a.provDesc||'').localeCompare(b.provDesc||''));
            provinces.forEach(p => {
                if (p.regCode == regCode) $prov.append(new Option(p.provDesc, p.provCode));
            });

            if (selectedProv) $prov.val(selectedProv);

            $('#address_province_text').val($('#address_province_code option:selected').text() || '');
            return $prov.val();
            });
        }

        function loadCityMuns(provCode, selectedCity) {
            const $city = $('#address_muncity_code');
            const $brgy = $('#address_brgy_text');

            setLoading($city, false);
            setChoose($brgy, true);

            return getJSONWithRetry(citymunUrl).then(function (cities) {
            setChoose($city, false);

            cities.sort((a,b) => (a.citymunDesc||'').localeCompare(b.citymunDesc||''));
            cities.forEach(c => {
                if (c.provCode == provCode) $city.append(new Option(c.citymunDesc, c.citymunCode));
            });

            if (selectedCity) $city.val(selectedCity);

            $('#address_muncity_text').val($('#address_muncity_code option:selected').text() || '');
            return $city.val();
            });
        }

        function loadBrgys(cityCode, selectedBrgyUpper) {
            const $brgy = $('#address_brgy_text');

            setLoading($brgy, false);

            return getJSONWithRetry(brgyUrl).then(function (brgys) {
            setChoose($brgy, false);

            brgys.sort((a,b) => (a.brgyDesc||'').localeCompare(b.brgyDesc||''));
            brgys.forEach(b => {
                if (b.citymunCode == cityCode) {
                const upper = (b.brgyDesc || '').toUpperCase();
                $brgy.append(new Option(upper, upper));
                }
            });

            if (selectedBrgyUpper) $brgy.val(selectedBrgyUpper);

            return $brgy.val();
            });
        }

        // ---- EDIT PAGE INITIAL LOAD (sequential) ----
        loadRegions(rdefault)
            .then(regCode => loadProvinces(regCode, pdefault))
            .then(provCode => loadCityMuns(provCode, cdefault))
            .then(cityCode => loadBrgys(cityCode, bdefault))
            .catch(function (jqxhr, textStatus, error) {
            console.log("Address JSON load failed:", textStatus, error);
            alert("Failed to load address references. Please check internet and refresh.");
            });

        // ---- USER CHANGES (this is what you were missing) ----
        $('#address_region_code').on('change', function () {
            const regCode = $(this).val();
            $('#address_region_text').val($('#address_region_code option:selected').text() || '');

            // when user changes region, clear defaults downstream
            loadProvinces(regCode, null)
            .then(provCode => loadCityMuns(provCode, null))
            .then(cityCode => loadBrgys(cityCode, null));
        });

        $('#address_province_code').on('change', function () {
            const provCode = $(this).val();
            $('#address_province_text').val($('#address_province_code option:selected').text() || '');

            loadCityMuns(provCode, null)
            .then(cityCode => loadBrgys(cityCode, null));
        });

        $('#address_muncity_code').on('change', function () {
            const cityCode = $(this).val();
            $('#address_muncity_text').val($('#address_muncity_code option:selected').text() || '');

            loadBrgys(cityCode, null);
        });

    });

    var patientAge = {{$patientAge}};

    $('#gender').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'FEMALE' && patientAge >= 10) {
            $('#pregnantDiv').removeClass('d-none');
            $('#is_pregnant').prop('required', true);
        }
        else {
            $('#pregnantDiv').addClass('d-none');
            $('#is_pregnant').prop('required', false);
        }
    }).trigger('change');

    $('#has_bday').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'Yes') {
            $('#ybday').removeClass('d-none');
            $('#nbday').addClass('d-none');

            $('#bdate').prop('required', true);
            $('#age').prop('required', false);
        }
        else {
            $('#ybday').addClass('d-none');
            $('#nbday').removeClass('d-none');

            $('#bdate').prop('required', false);
            $('#age').prop('required', true);
        }
    }).trigger('change');

    $('#is_others').change(function (e) { 
        e.preventDefault();
        if($(this).prop('checked')) {
            $('#ifCheckboxOthersDiv').removeClass('d-none');
            $('#is_others_specify').prop('required', true);
        }
        else {
            $('#ifCheckboxOthersDiv').addClass('d-none');
            $('#is_others_specify').prop('required', false);
        }
    }).trigger('change');
</script>
@endsection