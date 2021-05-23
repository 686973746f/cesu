@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div>
                    Companies
                </div>
                <div>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addCompany">Add Company</button>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createCompanyCode" {{($list->count()) ? '' : 'disabled'}}>Create Company Account</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if(session('status'))
                <div class="alert alert-{{session('statustype')}}" role="alert">
                    {{session('status')}}
                </div>
                <hr>
            @endif
            @if($list)
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name of Company</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list as $item)
                        <tr>
                            <td scope="row">{{$loop->iteration}}</td>
                            <td>{{$item->companyName}}</td>
                            <td></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-center">There are no companies registered.</p>
            @endif
        </div>
    </div>
</div> 


<form action="{{route('companies.store')}}" method="POST">
    @csrf
    <div class="modal fade" id="addCompany" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Company</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                      <label for="companyName"><span class="text-danger font-weight-bold">*</span>Company Name</label>
                      <input type="text" class="form-control" name="companyName" id="companyName" required>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="contactNumber"><span class="text-danger font-weight-bold">*</span>Contact Number</label>
                                <input type="text" class="form-control" name="contactNumber" id="contactNumber" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email"><span class="text-danger font-weight-bold">*</span>Email</label>
                                <input type="email" class="form-control" name="email" id="email" required>
                            </div>
                        </div>
                    </div>
                    <div id="divHide1">
                        <div class="form-group">
                          <input type="text" class="form-control" name="loc_region" id="loc_region" required>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="loc_province" id="loc_province" required>
                          </div>
                          <div class="form-group">
                            <input type="text" class="form-control" name="loc_city" id="loc_city" required>
                          </div>
                    </div>
                    <div class="form-group">
                      <label for="loc_regionjson"><span class="text-danger font-weight-bold">*</span>Region</label>
                      <select class="form-control" name="loc_regionjson" id="loc_regionjson" required>
                          <option value="" selected disabled>Choose...</option>
                      </select>
                    </div>
                    <div class="form-group">
                        <label for="loc_provincejson"><span class="text-danger font-weight-bold">*</span>Province</label>
                        <select class="form-control" name="loc_provincejson" id="loc_provincejson" required>
                            <option value="" selected disabled>Choose...</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="loc_cityjson"><span class="text-danger font-weight-bold">*</span>Municipality/City</label>
                        <select class="form-control" name="loc_cityjson" id="loc_cityjson" required>
                            <option value="" selected disabled>Choose...</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="loc_brgy"><span class="text-danger font-weight-bold">*</span>Barangay</label>
                        <select class="form-control" name="loc_brgy" id="loc_brgy" required>
                            <option value="" selected disabled>Choose...</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="loc_lotbldg"><span class="text-danger font-weight-bold">*</span>Lot/Building</label>
                        <input type="text" class="form-control" name="loc_lotbldg" id="loc_lotbldg" required>
                    </div>
                    <div class="form-group">
                        <label for="loc_street"><span class="text-danger font-weight-bold">*</span>Street</label>
                        <input type="text" class="form-control" name="loc_street" id="loc_street" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>
</form>

<form action="{{route('companies.makecode')}}" method="POST">
    @csrf
    <div class="modal fade" id="createCompanyCode" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                      <label for="company_id">Select Company to Create Account</label>
                      <select name="company_id" id="company_id">
                          <option value="" selected disabled></option>
                          @foreach($list as $data)
                          <option value="{{$data->id}}">{{$data->companyName}}</option>
                          @endforeach
                      </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Next</button>
                </div>
            </div>
        </div>
    </div>
</form>

@if(session('process') == 'createCode')
    <div class="modal fade" id="showCode" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Barangay Referral Code has been created!</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <p>You can now share this referral code to the respective user to gain access inside the website.</p>
                    <p></p>
                    <p><code>{{route('rcode.check')}}?refCode={{session('bCode')}}</code></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#showCode').modal('show');
        });
    </script>
@endif

<script>
    $(document).ready(function () {

        $('#company_id').selectize();
        
        $('#loc_provincejson').prop('disabled', true);
        $('#loc_cityjson').prop('disabled', true);
        $('#loc_brgy').prop('disabled', true);
        $('#divHide1').hide();

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
				$("#loc_regionjson").append('<option value="'+val.regCode+'">'+val.regDesc+'</option>');
			});
        });
    });

    $('#loc_regionjson').change(function (e) {
        e.preventDefault();
        $('#loc_provincejson').prop('disabled', false);
        $('#loc_cityjson').prop('disabled', true);
        $('#loc_brgy').prop('disabled', true);

        $('#loc_provincejson').empty();
        $("#loc_provincejson").append('<option value="" selected disabled>Choose...</option>');

        $('#loc_cityjson').empty();
        $("#loc_cityjson").append('<option value="" selected disabled>Choose...</option>');
        
        $('#loc_brgy').empty();
        $("#loc_brgy").append('<option value="" selected disabled>Choose...</option>');

        $("#loc_region").val($('#loc_regionjson option:selected').text());
        
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
                if($('#loc_regionjson').val() == val.regCode) {
                    $("#loc_provincejson").append('<option value="'+val.provCode+'">'+val.provDesc+'</option>');
                }
            });
        });
	});

    $('#loc_provincejson').change(function (e) {
        e.preventDefault();
        $('#loc_cityjson').prop('disabled', false);
        $('#loc_brgy').prop('disabled', true);

        $('#loc_cityjson').empty();
        $("#loc_cityjson").append('<option value="" selected disabled>Choose...</option>');

        $('#loc_brgy').empty();
        $("#loc_brgy").append('<option value="" selected disabled>Choose...</option>');

        $("#loc_province").val($('#loc_provincejson option:selected').text());
        
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
                if($('#loc_provincejson').val() == val.provCode) {
                    $("#loc_cityjson").append('<option value="'+val.citymunCode+'">'+val.citymunDesc+'</option>');
                }
            });
        });
    });

    $('#loc_cityjson').change(function (e) { 
			e.preventDefault();
			$('#loc_brgy').prop('disabled', false);
			$('#loc_brgy').empty();
			$("#loc_brgy").append('<option value="" selected disabled>Choose...</option>');
			$("#loc_city").val($('#loc_cityjson option:selected').text());

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
					if($('#loc_cityjson').val() == val.citymunCode) {
						$("#loc_brgy").append('<option value="'+val.brgyDesc.toUpperCase()+'">'+val.brgyDesc.toUpperCase()+'</option>');
					}
				});
			});
		});
</script>
@endsection