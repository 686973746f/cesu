@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div><strong>Acceptance Letter</strong></div>
                <div><button type="button" class="btn btn-success" data-toggle="modal" data-target="#createal"><i class="fa fa-plus mr-2" aria-hidden="true"></i>Add</button></div>
            </div>
        </div>
        <div class="card-body">
            @if($errors->any())
            <div class="alert alert-danger" role="alert">
                <p>{{Str::plural('Error', $errors->count())}} while creating Acceptance Letter:</p>
                <hr>
                @foreach ($errors->all() as $error)
                    <li>{{$error}}</li>
                @endforeach
            </div>
            @endif
            @if(session('msg'))
            <div class="alert alert-{{session('msgType')}}" role="alert">
                {{session('msg')}}
            </div>
            @endif
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="text-center thead-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Travel To</th>
                            <th>Date Processed / By</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list as $item)
                        <tr>
                            <td class="text-center">{{$loop->iteration}}</td>
                            <td>{{$item->getName()}}</td>
                            <td><small>{{$item->getAddress()}}</small></td>
                            <td class="text-center">{{$item->travelto}}</td>
                            <td class="text-center">{{date('m/d/Y h:i A', strtotime($item->created_at))}} / {{$item->user->name}}</td>
                            <td class="text-center">
                                <a href="{{route('acceptance.print', ['id' => $item->id])}}">View/Print</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="pagination justify-content-center mt-3">
                {{$list->appends(request()->input())->links()}}
            </div>
        </div>
    </div>

    <form action="{{route('acceptance.store')}}" method="POST">
        @csrf
        <div class="modal fade" id="createal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Create Acceptance Letter</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info" role="alert">
                            <strong>Note:</strong> All fields marked with an asterisk (<span class="text-danger font-weight-bold">*</span>) are required.
                        </div>
                        <div class="form-group">
                            <label for="lname"><span class="text-danger font-weight-bold">*</span>Last Name</label>
                            <input type="text"class="form-control" name="lname" id="lname" value="{{old('lname')}}" maxlength="50" style="text-transform: uppercase;" required>
                        </div>
                        <div class="form-group">
                            <label for="fname"><span class="text-danger font-weight-bold">*</span>First Name</label>
                            <input type="text"class="form-control" name="fname" id="fname" value="{{old('fname')}}" maxlength="50" style="text-transform: uppercase;" required>
                        </div>
                        <div class="form-group">
                            <label for="mname">Middle Name <small><i>(If Applicable)</i></small></label>
                            <input type="text"class="form-control" name="mname" id="mname" value="{{old('mname')}}" style="text-transform: uppercase;" maxlength="50">
                        </div>
                        <div class="form-group">
                            <label for="suffix">Suffix <small><i>(e.g Jr, Sr, III, IV)</i></small></label>
                            <input type="text"class="form-control" name="suffix" id="suffix" value="{{old('suffix')}}" style="text-transform: uppercase;" maxlength="4">
                        </div>
                        <div class="form-group">
                          <label for="sex"><span class="text-danger font-weight-bold">*</span>Gender</label>
                          <select class="form-control" name="sex" id="sex" required>
                                <option value="" disabled {{is_null(old('sex')) ? 'selected' : ''}}>Choose...</option>
                                <option value="M" {{(old('sex') == 'M') ? 'selected' : ''}}>Male</option>
                                <option value="F" {{(old('sex') == 'F') ? 'selected' : ''}}>Female</option>
                          </select>
                        </div>
                        <hr>
                        <div id="address_text" class="d-none">
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="text" id="address_region_text" name="address_region_text" readonly>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" id="address_province_text" name="address_province_text" readonly>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" id="address_muncity_text" name="address_muncity_text" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="address_region_code" class="form-label"><span class="text-danger font-weight-bold">*</span>Region</label>
                            <select class="form-control" name="address_region_code" id="address_region_code" required>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="address_province_code" class="form-label"><span class="text-danger font-weight-bold">*</span>Province</label>
                            <select class="form-control" name="address_province_code" id="address_province_code" required>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="address_muncity_code" class="form-label"><span class="text-danger font-weight-bold">*</span>City/Municipality</label>
                            <select class="form-control" name="address_muncity_code" id="address_muncity_code" required>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="address_brgy_text" class="form-label"><span class="text-danger font-weight-bold">*</span>Barangay</label>
                            <select class="form-control" name="address_brgy_text" id="address_brgy_text" required>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="address_houseno" class="form-label"><span class="text-danger font-weight-bold">*</span>House No./Lot/Building & Street/Subdivision/Purok/Sitio</label>
                            <input type="text" class="form-control" id="address_houseno" name="address_houseno" style="text-transform: uppercase;" value="{{old('address_houseno')}}" pattern="(^[a-zA-Z0-9 ]+$)+" required>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="travelto"><span class="text-danger font-weight-bold">*</span>Will Travel To (Hotel/Isolation Facility Name)</label>
                            <input type="text"class="form-control" name="travelto" id="travelto" value="{{old('travelto')}}" maxlength="50" style="text-transform: uppercase;" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-2"></i>Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
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

    $('#address_muncity_code').change(function (e) {
        e.preventDefault();
        //Empty and Disable
        $('#address_brgy_text').empty();
        $("#address_brgy_text").append('<option value="" selected disabled>Choose...</option>');

        //Re-disable Select
        $('#address_muncity_code').prop('disabled', false);
        $('#address_brgy_text').prop('disabled', false);

        //Set Values for Hidden Box
        $('#address_muncity_text').val($('#address_muncity_code option:selected').text());

        $.getJSON("{{asset('json/refbrgy.json')}}", function(data) {
            var sorted = data.sort(function(a, b) {
                if (a.brgyDesc > b.brgyDesc) {
                return 1;
                }
                if (a.brgyDesc < b.brgyDesc) {
                return -1;
                }
                return 0;
            });
            $.each(sorted, function(key, val) {
                if($('#address_muncity_code').val() == val.citymunCode) {
                    $('#address_brgy_text').append($('<option>', {
                        value: val.brgyDesc.toUpperCase(),
                        text: val.brgyDesc.toUpperCase(),
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