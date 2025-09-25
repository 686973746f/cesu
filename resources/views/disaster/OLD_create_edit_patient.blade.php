@extends('layouts.app')

@section('content')
    @if($p->exists)
    <form action="{{route('gtsecure_updatepatient', $d->id)}}" method="POST">
        @php
        $date_registered_default = $p->date_registered;
        @endphp
    @else
    <form action="{{route('gtsecure_storepatient', $d->id)}}" method="POST">
        @php
        $date_registered_default = date('Y-m-d H:i:s');
        @endphp
    @endif
    @csrf
        <div class="container">
            <div class="card">
                <div class="card-header"><b>New Patient</b></div>
                <div class="card-body">
                    
                    
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success btn-block" id="submitbtn">Save (CTRL + S)</button>
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

    $(document).ready(function () {
        function calculateAge(birthdate) {
            const today = new Date();
            const birthDate = new Date(birthdate);
            let age = today.getFullYear() - birthDate.getFullYear();
            const m = today.getMonth() - birthDate.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
            age--;
            }
            return age;
        }

        function toggleIsPregnantDiv() {
            const gender = $("#sex").val();
            const birthdate = $("#bdate").val();
            const age = calculateAge(birthdate);

            if (gender === "F" && age > 10) {
                $("#femaleDiv").removeClass('d-none');
                $('#is_pregnant').prop('required', true);
                $('#is_lactating').prop('required', true);

            } else {
                $("#femaleDiv").addClass('d-none');
                $('#is_pregnant').prop('required', false);
                $('#is_lactating').prop('required', false);
            }
        }

        $("#sex, #bdate").on("change", function () {
            toggleIsPregnantDiv();
        });
    });

    $('#is_headoffamily').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'Y' || $(this).val() == null) {
            $('#isHeadOfFamily').addClass('d-none');
            $('#family_patient_id').prop('required', false);
        }
        else {
            $('#isHeadOfFamily').removeClass('d-none');
            $('#family_patient_id').prop('required', true);
        }
    }).trigger('change');

    //Select2 Init for Address Bar
    $('#address_region_code, #address_province_code, #address_muncity_code, #address_brgy_code, #family_patient_id').select2({
        theme: 'bootstrap',
    });

    
    @if($p->exists)
    //Default Values for Gentri
    var regionDefault = {{$p->brgy->city->province->region->id}};
    var provinceDefault = {{$p->brgy->city->province->id}};
    var cityDefault = {{$p->brgy->city->id}};
    var brgyDefault = {{$p->address_brgy_code}};
    @else
    //Default Values for Gentri
    var regionDefault = 1;
    var provinceDefault = 18;
    var cityDefault = 388;
    @endif

    $('#address_region_code').change(function (e) { 
        e.preventDefault();

        var regionId = $(this).val();

        if (regionId) {
            $('#address_province_code').prop('disabled', false);
            $('#address_muncity_code').prop('disabled', true);
            $('#address_brgy_code').prop('disabled', true);

            $('#address_province_code').empty();
            $('#address_muncity_code').empty();
            $('#address_brgy_code').empty();

            $.ajax({
                url: '/ga/province/' + regionId,
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
    });

    $('#address_province_code').change(function (e) { 
        e.preventDefault();

        var provinceId = $(this).val();

        if (provinceId) {
            $('#address_province_code').prop('disabled', false);
            $('#address_muncity_code').prop('disabled', false);
            $('#address_brgy_code').prop('disabled', true);

            $('#address_muncity_code').empty();
            $('#address_brgy_code').empty();

            $.ajax({
                url: '/ga/city/' + provinceId,
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

        if (cityId) {
            $('#address_province_code').prop('disabled', false);
            $('#address_muncity_code').prop('disabled', false);
            $('#address_brgy_code').prop('disabled', false);

            $('#address_brgy_code').empty();

            $.ajax({
                url: '/ga/brgy/' + cityId,
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

    if (provinceDefault) {
        setTimeout(function() {
            $('#address_province_code').val(provinceDefault).trigger('change');
        }, 500); // Slight delay to ensure province is loaded
    }
    if (cityDefault) {
        setTimeout(function() {
            $('#address_muncity_code').val(cityDefault).trigger('change');
        }, 1000); // Slight delay to ensure city is loaded
    }
    @if($p->exists)
    if (brgyDefault) {
        setTimeout(function() {
            $('#address_brgy_code').val(brgyDefault).trigger('change');
        }, 1500); // Slight delay to ensure city is loaded
    }
    @endif
</script>
@endsection