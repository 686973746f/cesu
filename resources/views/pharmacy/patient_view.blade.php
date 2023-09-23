@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="{{route('pharmacy_update_patient', $d->id)}}" method="POST">
            @csrf
            <div class="card mb-3">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <div><b>View Patient</b></div>
                        <div><a href="{{route('pharmacy_print_patient_card', $d->id)}}" class="btn btn-primary">Print Card</a></div>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('msg'))
                    <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                        {{session('msg')}}
                    </div>
                    @endif
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td class="bg-light">Date Encoded / By</td>
                                <td class="text-center">{{date('m/d/Y (D) h:i A', strtotime($d->created_at))}} / {{$d->user->name}}</td>
                                <td class="bg-light">Date Updated / By</td>
                                <td class="text-center">{{($d->getUpdatedBy()) ? date('m/d/Y h:i A', strtotime($d->updated_at)).' / '.$d->getUpdatedBy->name : 'N/A'}}</td>
                            </tr>
                            <tr>
                                <td class="bg-light">Encoded on Branch</td>
                                <td class="text-center">{{$d->pharmacybranch->name}}</td>
                                <td class="bg-light">QR</td>
                                <td class="text-center">
                                    <div>{!! QrCode::size(70)->generate('PATIENT_'.$d->qr) !!}</div>
                                    <div>{{$d->qr}}</div>
                                    <div>ID: #{{$d->id}}</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <hr>
                    <div class="form-group">
                        <label for="status"><b class="text-danger">*</b>Account Status</label>
                        <select class="form-control" name="status" id="status" required>
                          <option value="ENABLED" {{(old('status', $d->status) == 'ENABLED') ? 'selected' : ''}}>Enabled</option>
                          <option value="DISABLED" {{(old('status', $d->status) == 'DISABLED') ? 'selected' : ''}}>Disabled</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="lname"><span class="text-danger font-weight-bold">*</span>Last Name</label>
                                <input type="text" class="form-control" id="lname" name="lname" value="{{old('lname', $d->lname)}}" max="50" style="text-transform: uppercase;" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="fname"><span class="text-danger font-weight-bold">*</span>First Name</label>
                                <input type="text" class="form-control" id="fname" name="fname" value="{{old('fname', $d->fname)}}" max="50" style="text-transform: uppercase;" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="mname">Middle Name</label>
                                <input type="text" class="form-control" id="mname" name="mname" value="{{old('mname', $d->mname)}}" max="50" style="text-transform: uppercase;">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="suffix">Suffix</label>
                                <input type="text" class="form-control" id="suffix" name="suffix" value="{{old('suffix', $d->suffix)}}" max="50" style="text-transform: uppercase;">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="bdate"><span class="text-danger font-weight-bold">*</span>Birthdate</label>
                                <input type="date" class="form-control" id="bdate" name="bdate" value="{{old('bdate', $d->bdate)}}">
                                <small>Age: {{Carbon\Carbon::parse($d->bdate)->age}}</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="gender"><span class="text-danger font-weight-bold">*</span>Sex</label>
                                  <select class="form-control" name="gender" id="gender" required>
                                      <option value="MALE" {{(old('gender', $d->gender) == 'MALE') ? 'selected' : ''}}>Male</option>
                                      <option value="FEMALE" {{(old('gender', $d->gender) == 'FEMALE') ? 'selected' : ''}}>Female</option>
                                  </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="philhealth">Philhealth No. (Optional)</label>
                                <input type="text" class="form-control" id="philhealth" name="philhealth" value="{{old('philhealth', $d->philhealth)}}" pattern="[0-9]{12}">
                            </div>
                            <div class="form-group">
                              <label for="">Email Address (Optional)</label>
                              <input type="email" class="form-control" name="email" id="email" value="{{old('email', $d->email)}}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="contact_number"><span class="text-danger font-weight-bold">*</span>Contact Number</label>
                                <input type="text" class="form-control" id="contact_number" name="contact_number" value="{{old('contact_number', $d->contact_number)}}" pattern="[0-9]{11}" placeholder="09*********" required>
                            </div>
                            <div class="form-group">
                                <label for="contact_number2">Contact Number 2 (Optional)</label>
                                <input type="text" class="form-control" id="contact_number2" name="contact_number2" value="{{old('contact_number2', $d->contact_number2)}}" pattern="[0-9]{11}" placeholder="09*********">
                            </div>
                        </div>
                    </div>
                    <div id="address_text" class="d-none">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" id="address_region_text" name="address_region_text" value="{{old('address_region_text', $d->address_region_text)}}" readonly>
                            </div>
                            <div class="col-md-6">
                                <input type="text" id="address_province_text" name="address_province_text" value="{{old('address_region_text', $d->address_region_text)}}" readonly>
                            </div>
                            <div class="col-md-6">
                                <input type="text" id="address_muncity_text" name="address_muncity_text" value="{{old('address_region_text', $d->address_region_text)}}" readonly>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="address_region_code" class="form-label"><span class="text-danger font-weight-bold">*</span>Region</label>
                                <select class="form-control" name="address_region_code" id="address_region_code" required>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="address_province_code" class="form-label"><span class="text-danger font-weight-bold">*</span>Province</label>
                                <select class="form-control" name="address_province_code" id="address_province_code" required>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="address_muncity_code" class="form-label"><span class="text-danger font-weight-bold">*</span>City/Municipality</label>
                                <select class="form-control" name="address_muncity_code" id="address_muncity_code" required>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="address_brgy_text" class="form-label"><span class="text-danger font-weight-bold">*</span>Barangay</label>
                                <select class="form-control" name="address_brgy_text" id="address_brgy_text" required>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="address_houseno" class="form-label">House No./Lot/Building</label>
                                <input type="text" class="form-control" id="address_houseno" name="address_houseno" style="text-transform: uppercase;" value="{{old('address_houseno', $d->address_houseno)}}" pattern="(^[a-zA-Z0-9 ]+$)+">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="address_street" class="form-label">Street/Subdivision/Purok/Sitio</label>
                                <input type="text" class="form-control" id="address_street" name="address_street" style="text-transform: uppercase;" value="{{old('address_street', $d->address_street)}}" pattern="(^[a-zA-Z0-9 ]+$)+">
                            </div>
                        </div>
                    </div>   
                </div>
                <div class="card-header">
                    <button type="submit" class="btn btn-primary btn-block">Update</button>
                </div>
            </div>
        </form>

        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div><b>Stock Card / Transactions</b></div>
                    <div><a href="{{route('pharmacy_modify_patient_stock', $d->id)}}" class="btn btn-success">New Transaction</a></div>
                </div>
            </div>
            <div class="card-body">
                @if($scard->count() != 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="card_tbl">
                        <thead class="thead-light text-center">
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Name of Meds</th>
                                <th>QTY Issued</th>
                                <th>Branch</th>
                                <th>Processed by</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($scard as $ind => $s)
                            <tr class="text-center">
                                <td class="text-center">{{$ind+1}}</td>
                                <td>{{date('m/d/Y h:i A', strtotime($s->created_at))}}</td>
                                <td>{{$s->pharmacysub->pharmacysupplymaster->name}}</td>
                                <td>{{($s->type == 'ISSUED') ? $s->getQtyAndType() : ''}}</td>
                                <td>{{$s->pharmacysub->pharmacybranch->name}}</td>
                                <td>{{$s->user->name}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <h6 class="text-center">No Results found.</h6>
                @endif
            </div>
        </div>
    </div>

    <script>
        //Select2 Init for Address Bar
        $('#address_region_code, #address_province_code, #address_muncity_code, #address_brgy_text, #concerns_list').select2({
            theme: 'bootstrap',
        });

        var rdefault = "{{old('address_region_code', $d->address_region_code)}}";
        var pdefault = "{{old('address_province_code', $d->address_province_code)}}";
        var cdefault = "{{old('address_muncity_code', $d->address_muncity_code)}}";
        var bdefault = "{{old('address_brgy_text', $d->address_brgy_text)}}";

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
                    selected: (val.regCode == rdefault) ? true : false, //default is Region IV-A
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
                            selected: (val.provCode == pdefault) ? true : false, //default for Cavite
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
                            selected: (val.citymunCode == cdefault) ? true : false, //default for General Trias
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
                            selected: (val.brgyDesc.toUpperCase() == bdefault) ? true : false,
                        }));
                    }
                });
            });
        }).trigger('change');

        $('#address_region_text').val('{{$d->address_region_text}}');
        $('#address_province_text').val('{{$d->address_province_text}}');
        $('#address_muncity_text').val('{{$d->address_muncity_text}}');
    </script>
@endsection