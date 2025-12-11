@extends('layouts.app')

@section('content')
<form action="{{route('edcs_addcase_store', request()->input('disease'))}}" method="POST">
    @csrf
    <div class="container">
        <div class="card">
            <div class="card-header">
                <b>
                    <div>{{$f->facility_name}}</div>
                    <div>Report Influenza-Like Illness Case</div>
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

                @include('pidsr.inhouse_edcs.patient_defaults')
                @include('pidsr.inhouse_edcs.patient_defaults1')
                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-check">
                            <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" name="symptoms[]" id="symptoms_1" value="FEVER">
                            Sudden Fever
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" name="symptoms[]" id="symptoms_2" value="HEADACHE">
                            Headache
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" name="symptoms[]" id="symptoms_3" value="MUSCLE PAIN">
                            Muscle Pain
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" name="symptoms[]" id="symptoms_4" value="WEAKNESS">
                            Weakness
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" name="symptoms[]" id="symptoms_5" value="RED EYES">
                            Red eyes
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" name="symptoms[]" id="symptoms_6" value="STIFF NECK">
                            Stiff neck
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" name="symptoms[]" id="symptoms_7" value="MENINGITIS SIGNS">
                            Meningitis signs
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" name="symptoms[]" id="symptoms_8" value="LITTLE OR NO PROTEIN IN URINE">
                            Little or no protein in urine
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" name="symptoms[]" id="symptoms_9" value="JAUNDICE">
                            Yellow skin (Jaundice)
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" name="symptoms[]" id="symptoms_10" value="BLEEDING">
                            Bleeding (intestines, lungs, etc.)
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" name="symptoms[]" id="symptoms_11" value="IRREGULAR HEARTBEAT OR HEART FAILURE">
                            Irregular heartbeat or heart failure
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" name="symptoms[]" id="symptoms_12" value="SKIN RASH">
                            Skin Rash
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" name="symptoms[]" id="symptoms_13" value="NAUSEA">
                            Nausea
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" name="symptoms[]" id="symptoms_14" value="VOMITING">
                            Vomiting
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" name="symptoms[]" id="symptoms_15" value="STOMACH PAIN">
                            Stomach Pain
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" name="symptoms[]" id="symptoms_16" value="DIARRHEA">
                            Diarrhea
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" name="symptoms[]" id="symptoms_17" value="JOINT PAIN">
                            Joint Pain
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="Occupation" class="form-label"><b class="text-danger">*</b>Occupation</label>
                            <input type="text" class="form-control" id="Occupation" name="Occupation" style="text-transform: uppercase;" value="{{old('Occupation')}}" required>
                        </div>
                        <div class="form-group">
                            <label for="exposure"><b class="text-danger">*</b>Exposure Type</label>
                            <select class="form-control" name="exposure" id="exposure" required>
                                <option value="" disabled {{(is_null(old('exposure'))) ? 'selected' : ''}}>Choose...</option>
                                <option value="FLOOD-REL" {{(old('exposure') == 'FLOOD-REL') ? 'selected' : ''}}>Flood related</option>
                                <option value="AGRI-REL" {{(old('exposure') == 'AGRI-REL') ? 'selected' : ''}}>Agriculture related</option>
                                <option value="MUD-EXP" {{(old('exposure') == 'MUD-EXP') ? 'selected' : ''}}>Mud exposure</option>
                                <option value="INGESTION" {{(old('exposure') == 'INGESTION') ? 'selected' : ''}}>Ingestion of contaminated foods/drinks</option>
                                <option value="OTHERS" {{(old('exposure') == 'OTHERS') ? 'selected' : ''}}>Others</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exp_address_region_code"><b class="text-danger">*</b>Exposure Region</label>
                            <select class="form-control" name="exp_address_region_code" id="exp_address_region_code" tabindex="-1" required>
                            @foreach(App\Models\Regions::orderBy('regionName', 'ASC')->get() as $a)
                            <option value="{{$a->id}}" {{($a->id == 1) ? 'selected' : ''}}>{{$a->regionName}}</option>
                            @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="address_province_code"><b class="text-danger">*</b>Exposure Province</label>
                            <select class="form-control" name="exp_address_province_code" id="exp_address_province_code" tabindex="-1" required disabled>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="address_muncity_code"><b class="text-danger">*</b>Exposure City/Municipality</label>
                            <select class="form-control" name="exp_address_muncity_code" id="exp_address_muncity_code" tabindex="-1" required disabled>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="address_brgy_code"><b class="text-danger">*</b>Exposure Barangay</label>
                            <select class="form-control" name="exp_brgy_id" id="exp_address_brgy_code" required disabled>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exp_street" class="form-label"><b class="text-danger">*</b>Exposure House/Street No.</label>
                            <input type="text" class="form-control" id="exp_street" name="exp_street" style="text-transform: uppercase;" value="{{old('Streetpurok')}}" placeholder="ex. S1 B2 L3 PHASE 4 SUBDIVISION HOMES" required>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        @include('pidsr.inhouse_edcs.patient_classification')
                    </div>
                    <div class="col-md-6">
                        @include('pidsr.inhouse_edcs.patient_outcome')
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-success btn-block" id="submitBtn">Submit (CTRL + S)</button>
            </div>
        </div>
    </div>
</form>

@include('pidsr.inhouse_edcs.patient_defaults_js')

<script>
$('#exp_address_region_code').change(function (e) { 
    e.preventDefault();

    var regionId = $(this).val();
    var getProvinceUrl = "{{ route('address_get_provinces', ['region_id' => ':regionId']) }}";

    if (regionId) {
        $('#exp_address_province_code').prop('disabled', false);
        $('#exp_address_muncity_code').prop('disabled', true);
        $('#exp_address_brgy_code').prop('disabled', true);

        $('#exp_address_province_code').empty();
        $('#exp_address_muncity_code').empty();
        $('#exp_address_brgy_code').empty();

        $.ajax({
            url: getProvinceUrl.replace(':regionId', regionId),
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#exp_address_province_code').empty();
                $('#exp_address_province_code').append('<option value="" disabled selected>Select Province</option>');

                let sortedData = Object.entries(data).sort((a, b) => {
                    return a[1].localeCompare(b[1]); // Compare province names (values)
                });

                $.each(sortedData, function(key, value) {
                    $('#exp_address_province_code').append('<option value="' + value[0] + '">' + value[1] + '</option>');
                });
            }
        });
    } else {
        $('#exp_address_province_code').empty();
    }
}).trigger('change');

$('#exp_address_province_code').change(function (e) { 
    e.preventDefault();

    var provinceId = $(this).val();
    var getCityUrl = "{{ route('address_get_citymun', ['province_id' => ':provinceId']) }}";

    if (provinceId) {
        $('#exp_address_province_code').prop('disabled', false);
        $('#exp_address_muncity_code').prop('disabled', false);
        $('#exp_address_brgy_code').prop('disabled', true);

        $('#exp_address_muncity_code').empty();
        $('#exp_address_brgy_code').empty();

        $.ajax({
            url: getCityUrl.replace(':provinceId', provinceId),
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#exp_address_muncity_code').empty();
                $('#exp_address_muncity_code').append('<option value="" disabled selected>Select City/Municipality</option>');
                
                let sortedData = Object.entries(data).sort((a, b) => {
                    return a[1].localeCompare(b[1]); // Compare province names (values)
                });

                $.each(sortedData, function(key, value) {
                    $('#exp_address_muncity_code').append('<option value="' + value[0] + '">' + value[1] + '</option>');
                });
            }
        });
    } else {
        $('#exp_address_muncity_code').empty();
    }
});

$('#exp_address_muncity_code').change(function (e) { 
    e.preventDefault();

    var cityId = $(this).val();
    var getBrgyUrl = "{{ route('address_get_brgy', ['city_id' => ':cityId']) }}";

    if (cityId) {
        $('#exp_address_province_code').prop('disabled', false);
        $('#exp_address_muncity_code').prop('disabled', false);
        $('#exp_address_brgy_code').prop('disabled', false);

        $('#exp_address_brgy_code').empty();

        $.ajax({
            url: getBrgyUrl.replace(':cityId', cityId),
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#exp_address_brgy_code').empty();
                $('#exp_address_brgy_code').append('<option value="" disabled selected>Select Barangay</option>');

                let sortedData = Object.entries(data).sort((a, b) => {
                    return a[1].localeCompare(b[1]); // Compare province names (values)
                });

                $.each(sortedData, function(key, value) {
                    $('#exp_address_brgy_code').append('<option value="' + value[0] + '">' + value[1] + '</option>');
                });
            }
        });
    } else {
        $('#exp_address_brgy_code').empty();
    }
});

if ($('#exp_address_region_code').val()) {
    $('#exp_address_region_code').trigger('change'); // Automatically load provinces on page load
}

if (provinceDefault) {
    setTimeout(function() {
        $('#exp_address_province_code').val(provinceDefault).trigger('change');
    }, 1500); // Slight delay to ensure province is loaded
}

if (cityDefault) {
    setTimeout(function() {
        $('#exp_address_muncity_code').val(cityDefault).trigger('change');
    }, 2500); // Slight delay to ensure city is loaded
}
</script>
@endsection