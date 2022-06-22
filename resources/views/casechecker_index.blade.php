@extends('layouts.app')

@section('content')
<div class="container">
    <form action="{{route('casechecker_index')}}" method="GET">
        <div class="card">
            <div class="card-header">Barangay Case Checker</div>
            <div class="card-body">
                @if(isset($msg))
                <div class="alert alert-{{$msgtype}} text-center" role="alert">
                    {{$msg}}
                </div>
                @endif
                <div id="addresstext" class="d-none">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                              <input type="text" class="form-control" name="address_province" id="address_province" value="{{old('address_province')}}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="text" class="form-control" name="address_city" id="address_city" value="{{old('address_city')}}">
                            </div>
                        </div>
                        <div class="col-md-4">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                              <input type="text" class="form-control" name="address_provincejson" id="address_provincejson" value="{{old('address_provincejson')}}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="text" class="form-control" name="address_cityjson" id="address_cityjson" value="{{old('address_cityjson')}}">
                            </div>
                        </div>
                        <div class="col-md-4">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="saddress_province">Province</label>
                            <select class="form-control" name="saddress_province" id="saddress_province" required>
                              <option value="" selected disabled>Choose...</option>
                            </select>
                                @error('saddress_province')
                                  <small class="text-danger">{{$message}}</small>
                              @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                          <label for="saddress_city">City</label>
                          <select class="form-control" name="saddress_city" id="saddress_city" required>
                            <option value="" selected disabled>Choose...</option>
                          </select>
                            @error('saddress_city')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                          <label for="address_brgy">Barangay</label>
                          <select class="form-control" name="address_brgy" id="address_brgy" required>
                            <option value="" selected disabled>Choose...</option>
                          </select>
                              @error('address_brgy')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                          <label for="sdate">Search Start Date</label>
                          <input type="date" class="form-control" name="sdate" id="sdate" min="2020-01-01" max="{{date('Y-m-d')}}" value="{{date('Y-m-d', strtotime('-28 Days'))}}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="edate">Search End Date</label>
                            <input type="date" class="form-control" name="edate" id="edate" min="2020-01-01" max="{{date('Y-m-d')}}" value="{{date('Y-m-d')}}" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </div>
    </form>

    @if(request()->input('sdate') && request()->input('edate') && isset($list) && $list->count())
    <div class="card mt-3">
        <div class="card-header">
            <p>Barangay: <b>{{request()->input('address_brgy')}}</b></p>
            <p>Reported Cases Count from <strong>{{date('m/d/Y', strtotime(request()->input('sdate')))}}</strong> to <strong>{{date('m/d/Y', strtotime(request()->input('edate')))}}</strong></p>
            <p>Total Count: <strong>{{$list->count()}}</strong></p>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="text-center thead-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>MM</th>
                            <th>DR</th>
                            <th>Age/Sex</th>
                            <th>Street</th>
                            <th>Severity</th>
                            <th>Date Swab</th>
                            <th>Classification</th>
                            <th>Quarantine Status</th>
                            <th>Vaccine</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list as $item)
                        <tr>
                            <td class="text-center">{{$loop->iteration}}</td>
                            <td scope="row"><a href="{{route('forms.edit', ['form' => $item->id])}}">{{$item->records->getName()}}</a></td>
                            <td class="text-center">{{date('m/d/Y', strtotime($item->morbidityMonth))}}</td>
                            <td class="text-center">{{date('m/d/Y', strtotime($item->dateReported))}}</td>
                            <td class="text-center">{{$item->records->getAge()}} / {{substr($item->records->gender,0,1)}}</td>
                            <td class="text-center"><small>{{$item->records->address_houseno}}, {{$item->records->address_street}}</small></td>
                            <td class="text-center">{{$item->healthStatus}}</td>
                            <td class="text-center">{{(!is_null($item->testDateCollected2)) ? date('m/d/Y', strtotime($item->testDateCollected2)) : date('m/d/Y', strtotime($item->testDateCollected1))}}</td>
                            <td class="text-center">{{$item->caseClassification}}</td>
                            <td class="text-center">{{$item->getQuarantineStatus()}}</td>
                            <td class="text-center">{{$item->records->showVaxInfo()}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
    $('#saddress_province, #saddress_city, #address_brgy').select2({
        theme: "bootstrap",
    });

    $('#saddress_city').prop('disabled', true);
	$('#address_brgy').prop('disabled', true);

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
            $('#saddress_province').append($('<option>', {
                value: val.provCode,
                text: val.provDesc,
                selected: (val.provCode == '0421') ? true : false, //default for Cavite
            }));
        });
    });

    $('#saddress_province').change(function (e) {
        e.preventDefault();
        $('#saddress_city').prop('disabled', false);
        $('#address_brgy').prop('disabled', true);
        $('#saddress_city').empty();
        $("#saddress_city").append('<option value="" selected disabled>Choose...</option>');
        $('#address_brgy').empty();
        $("#address_brgy").append('<option value="" selected disabled>Choose...</option>');
        $("#address_province").val($('#saddress_province option:selected').text());
        $("#address_provincejson").val($('#saddress_province').val());
        
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
                if($('#saddress_province').val() == val.provCode) {
                    $('#saddress_city').append($('<option>', {
                        value: val.citymunCode,
                        text: val.citymunDesc,
                        selected: (val.citymunCode == '042108') ? true : false, //default for General Trias
                    })); 
                }
            });
        });
    }).trigger('change');

    $('#saddress_city').change(function (e) {
        e.preventDefault();
        $('#address_brgy').prop('disabled', false);
        $('#address_brgy').empty();
        $("#address_brgy").append('<option value="" selected disabled>Choose...</option>');
        $("#address_city").val($('#saddress_city option:selected').text());
        $('#address_cityjson').val($('#saddress_city').val());

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
                if($('#saddress_city').val() == val.citymunCode) {
                    $("#address_brgy").append('<option value="'+val.brgyDesc.toUpperCase()+'">'+val.brgyDesc.toUpperCase()+'</option>');
                }
            });
        });
    }).trigger('change');

    //for Setting Default values on hidden address/json for Cavite - General Trias
    $("#address_province").val('CAVITE');
    $("#address_provincejson").val('0421');
    $("#address_city").val('GENERAL TRIAS');
    $('#address_cityjson').val('042108');
</script>
@endsection