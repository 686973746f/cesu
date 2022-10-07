@extends('layouts.app')

@section('content')
<div class="container">
    <form action="{{route('ss.update')}}" method="POST">
        @csrf
        <div class="card">
            <div class="card-header"><b>Site Settings</b></div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgType')}}" role="alert">
                    {{session('msg')}}
                </div>
                @endif
                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header"><b>Server Settings</b></div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="system_type" class="form-label">System Type</label>
                                    <select class="form-control" name="system_type" id="system_type" required>
                                      <option value="regional">Regional</option>
                                      <option value="provincial">Provincial</option>
                                      <option value="municipal">Municipal</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                  <label for="default_dru_name" class="form-label">Default DRU Name</label>
                                  <input type="text" class="form-control" name="default_dru_name" id="default_dru_name" required>
                                </div>
                                <div id="address_text" class="d-none">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="text" id="address_region_text" name="address_region_text" value="{{old('address_region_text')}}" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" id="address_province_text" name="address_province_text" value="{{old('address_province_text')}}" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" id="address_muncity_text" name="address_muncity_text" value="{{old('address_muncity_text')}}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="address_region_code" class="form-label"><span class="text-danger font-weight-bold">*</span>Region</label>
                                    <select class="form-control" name="address_region_code" id="address_region_code" required>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="address_province_code" class="form-label"><span class="text-danger font-weight-bold">*</span>Province</label>
                                    <select class="form-control" name="address_province_code" id="address_province_code" required>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="address_muncity_code" class="form-label"><span class="text-danger font-weight-bold">*</span>City/Municipality</label>
                                    <select class="form-control" name="address_muncity_code" id="address_muncity_code" required>
                                    </select>
                                </div>
                                <hr>
                                <div class="mb-3">
                                  <label for="listMobiles" class="form-label">List Mobile Number/s <i>(Separated by Commas)</i></label>
                                  <input type="text" class="form-control" name="listMobiles" id="listMobiles" required>
                                </div>
                                <div class="mb-3">
                                    <label for="listTelephone" class="form-label">List Mobile Number/s <i>(Separated by Commas)</i></label>
                                    <input type="text" class="form-control" name="listTelephone" id="listTelephone" required>
                                </div>
                                <div class="mb-3">
                                    <label for="listEmail" class="form-label">List Email Addresses <i>(Separated by Commas)</i></label>
                                    <input type="text" class="form-control" name="listEmail" id="listEmail" required>
                                </div>
                                <hr>
                                <div class="mb-3">
                                    <label for="listMobiles" class="form-label">DILG Responder Name</label>
                                    <input type="text" class="form-control" name="listMobiles" id="listMobiles" required>
                                </div>
                                <div class="mb-3">
                                    <label for="dilgCustomOfficeName" class="form-label">DILG Office Name</label>
                                    <input type="text" class="form-control" name="dilgCustomOfficeName" id="dilgCustomOfficeName" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header"><b>Swab Test Settings</b></div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="paswab_enabled" class="form-label">Pa-swab Status</label>
                                    <select class="form-control" name="paswab_enabled" id="paswab_enabled">
                                        <option value="1">Enabled</option>
                                        <option value="0">Disabled</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="paswab_antigen_enabled" class="form-label">Pa-swab Antigen Mode</label>
                                    <select class="form-control" name="paswab_antigen_enabled" id="paswab_antigen_enabled">
                                        <option value="1">Enabled</option>
                                        <option value="0">Disabled</option>
                                    </select>
                                </div>
                                <hr>
                                <div class="mb-3">
                                    <label for="lockencode_enabled" class="form-label">Lock Encode Status <i>(Suspected/Probable)</i></label>
                                    <select class="form-control" name="lockencode_enabled" id="lockencode_enabled">
                                        <option value="1">Enabled</option>
                                        <option value="0">Disabled</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="lockencode_start_time" class="form-label">Lock Encode (Suspected/Probable) Start Time</label>
                                    <input type="time" class="form-control" name="lockencode_start_time" id="lockencode_start_time">
                                </div>
                                <div class="mb-3">
                                    <label for="lockencode_end_time" class="form-label">Lock Encode (Suspected/Probable) End Time</label>
                                    <input type="time" class="form-control" name="lockencode_end_time" id="lockencode_end_time">
                                </div>
                                <div class="mb-3">
                                    <label for="lockencode_positive_enabled" class="form-label">Lock Encode Status <i>(CONFIRMED)</i></label>
                                    <select class="form-control" name="lockencode_positive_enabled" id="lockencode_positive_enabled">
                                        <option value="1">Enabled</option>
                                        <option value="0">Disabled</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="lockencode_positive_start_time" class="form-label">Lock Encode (CONFIRMED) Start Time</label>
                                    <input type="time" class="form-control" name="lockencode_positive_start_time" id="lockencode_positive_start_time">
                                </div>
                                <div class="mb-3">
                                    <label for="lockencode_positive_end_time" class="form-label">Lock Encode (CONFIRMED) End Time</label>
                                    <input type="time" class="form-control" name="lockencode_positive_end_time" id="lockencode_positive_end_time">
                                </div>
                                <hr>
                                <div class="mb-3">
                                    <label for="oniStartTime_am" class="form-label">CIF Autotime Start (AM)</label>
                                    <input type="time" class="form-control" name="oniStartTime_am" id="oniStartTime_am">
                                </div>
                                <div class="mb-3">
                                  <label for="oniStartTime_pm" class="form-label">CIF Autotime Start (PM)</label>
                                  <input type="time" class="form-control" name="oniStartTime_pm" id="oniStartTime_pm">
                                </div>
                                <hr>
                                <div class="mb-3">
                                    <label for="paswab_auto_schedule_if_symptomatic" class="form-label">Auto Schedule Pa-swab if Symptomatic</label>
                                    <select class="form-control" name="paswab_auto_schedule_if_symptomatic" id="paswab_auto_schedule_if_symptomatic">
                                        <option value="1">Enabled</option>
                                        <option value="0">Disabled</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="cifpage_auto_schedule_if_symptomatic" class="form-label">Auto Schedule CIF Encode if Symptomatic</label>
                                    <select class="form-control" name="cifpage_auto_schedule_if_symptomatic" id="cifpage_auto_schedule_if_symptomatic">
                                        <option value="1">Enabled</option>
                                        <option value="0">Disabled</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header"><b>Auto-Recover Settings</b></div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="unvaccinated_days_of_recovery" class="form-label">Days of Recovery - Unvaccinated</label>
                                    <input type="number" class="form-control" name="unvaccinated_days_of_recovery" id="unvaccinated_days_of_recovery" min="1" max="100" required>
                                </div>
                                <div class="mb-3">
                                    <label for="partialvaccinated_days_of_recovery" class="form-label">Days of Recovery - Partial Vaccinated</label>
                                    <input type="number" class="form-control" name="partialvaccinated_days_of_recovery" id="partialvaccinated_days_of_recovery" min="1" max="100" required>
                                </div>
                                <div class="mb-3">
                                    <label for="fullyvaccinated_days_of_recovery" class="form-label">Days of Recovery - Fully Vaccinated</label>
                                    <input type="number" class="form-control" name="fullyvaccinated_days_of_recovery" id="fullyvaccinated_days_of_recovery" min="1" max="100" required>
                                </div>
                                <div class="mb-3">
                                    <label for="booster_days_of_recovery" class="form-label">Days of Recovery - Boostered</label>
                                    <input type="number" class="form-control" name="booster_days_of_recovery" id="booster_days_of_recovery" min="1" max="100" required>
                                </div>
                                <div class="mb-3">
                                    <label for="in_hospital_days_of_recovery" class="form-label">Days of Recovery - In Hospital</label>
                                    <input type="number" class="form-control" name="in_hospital_days_of_recovery" id="in_hospital_days_of_recovery" min="1" max="100" required>
                                </div>
                                <div class="mb-3">
                                    <label for="severe_days_of_recovery" class="form-label">Days of Recovery - Severe</label>
                                    <input type="number" class="form-control" name="severe_days_of_recovery" id="severe_days_of_recovery" min="1" max="100" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-success btn-block">Save</button>
            </div>
        </div>
    </form>
</div>
<script>
    //Select2 Init for Address Bar
    $('#address_region_code, #address_province_code, #address_muncity_code, #address_brgy_text').select2({
        theme: 'bootstrap',
    });

    //Region Select Initialize
    $.getJSON("{{asset('json/refregion.json')}}", function(data) {
        var sorted = data.sort(function(a, b) {
            if (a.regDesc > b.regDesc) {
                return 1;
            }
            if (a.regDesc < b.regDesc) {
                return -1;
            }

            return 0;
        });

        $.each(sorted, function(key, val) {
            $('#address_region_code').append($('<option>', {
                value: val.regCode,
                text: val.regDesc,
                selected: (val.regCode == '04') ? true : false, //default is Region IV-A
            }));
        });
    });

    $('#address_region_code').change(function (e) { 
        e.preventDefault();
        //Empty and Disable
        $('#address_province_code').empty();
        $("#address_province_code").append('<option value="" selected disabled>Choose...</option>');

        $('#address_muncity_code').empty();
        $("#address_muncity_code").append('<option value="" selected disabled>Choose...</option>');

        //Re-disable Select
        $('#address_muncity_code').prop('disabled', true);
        $('#address_brgy_text').prop('disabled', true);

        //Set Values for Hidden Box
        $('#address_region_text').val($('#address_region_code option:selected').text());

        $.getJSON("{{asset('json/refprovince.json')}}", function(data) {
            var sorted = data.sort(function(a, b) {
                if (a.provDesc > b.provDesc) {
                return 1;
                }
                if (a.provDesc < b.provDesc) {
                return -1;
                }
                return 0;
            });

            $.each(sorted, function(key, val) {
                if($('#address_region_code').val() == val.regCode) {
                    $('#address_province_code').append($('<option>', {
                        value: val.provCode,
                        text: val.provDesc,
                        selected: (val.provCode == '0421') ? true : false, //default for Cavite
                    }));
                }
            });
        });
    }).trigger('change');

    $('#address_province_code').change(function (e) {
        e.preventDefault();
        //Empty and Disable
        $('#address_muncity_code').empty();
        $("#address_muncity_code").append('<option value="" selected disabled>Choose...</option>');

        //Re-disable Select
        $('#address_muncity_code').prop('disabled', false);
        $('#address_brgy_text').prop('disabled', true);

        //Set Values for Hidden Box
        $('#address_province_text').val($('#address_province_code option:selected').text());

        $.getJSON("{{asset('json/refcitymun.json')}}", function(data) {
            var sorted = data.sort(function(a, b) {
                if (a.citymunDesc > b.citymunDesc) {
                    return 1;
                }
                if (a.citymunDesc < b.citymunDesc) {
                    return -1;
                }
                return 0;
            });
            $.each(sorted, function(key, val) {
                if($('#address_province_code').val() == val.provCode) {
                    $('#address_muncity_code').append($('<option>', {
                        value: val.citymunCode,
                        text: val.citymunDesc,
                        selected: (val.citymunCode == '042108') ? true : false, //default for General Trias
                    })); 
                }
            });
        });
    }).trigger('change');

    $('#address_region_text').val('REGION IV-A (CALABARZON)');
    $('#address_province_text').val('CAVITE');
    $('#address_muncity_text').val('GENERAL TRIAS');
</script>
@endsection