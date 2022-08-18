@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div class="font-weight-bold">Pa-Swab Links ({{$data->total()}})</div>
                    <div>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addlink">Add Link</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="{{route('paswablinks.index')}}" method="GET">
                    <div class="row">
                        <div class="col-md-8"></div>
                        <div class="col-md-4">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" name="q" value="{{request()->input('q')}}" placeholder="Search Code / ID" required>
                                <div class="input-group-append">
                                  <button class="btn btn-secondary" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}}" role="alert">
                    {{session('msg')}}
                </div>
                @endif
                @if(request()->input('q'))
                <div class="alert alert-info" role="alert">
                    <i class="fa fa-info-circle mr-2" aria-hidden="true"></i>The search returned {{$data->count()}} {{Str::plural('result', $data->count())}}.
                </div>
                @endif
                @if($data->count())
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="bg-light text-center">
                            <tr>
                                <th>#</th>
                                <th>Code</th>
                                <th>Status</th>
                                <th>URL</th>
                                <th>Date Created</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $item)
                            <tr>
                                <td style="vertical-align: middle;" scope="row" class="text-center">{{$item->id}}</td>
                                <td style="vertical-align: middle;" class="text-center">{{$item->code}}</td>
                                <td style="vertical-align: middle;" class="text-center font-weight-bold text-{{($item->active == 1) ? 'success' : 'danger'}}">{{($item->active == 1) ? 'Enabled' : 'Disabled'}}</td>
                                <td style="vertical-align: middle;" class="text-center"><small><a href="https://paswab.cesugentri.com/?rlink={{$item->code}}&s={{$item->secondary_code}}">https://paswab.cesugentri.com/?rlink={{$item->code}}&s={{$item->secondary_code}}</a></small></td>
                                <td style="vertical-align: middle;" class="text-center">{{date('m/d/Y h:i A', strtotime($item->created_at))}}</td>
                                <td style="vertical-align: middle;" class="text-center">
                                    <form action="/admin/paswablinks/{{$item->id}}/options" method="POST">
                                        @csrf
                                        @if($item->active == 1)
                                        <button type="submit" name="submit" value="activeInit" class="btn btn-warning btn-block">Disable</button>
                                        @else
                                        <button type="submit" name="submit" value="activeInit" class="btn btn-success btn-block">Enable</button>
                                        @endif
                                        <button type="submit" name="submit" value="changeSecondaryCode" class="btn btn-primary btn-block">Change Secondary Code</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="pagination justify-content-center mt-3">
                    {{$data->appends(request()->input())->links()}}
                </div>

                @else
                <p class="text-center">No data available in table.</p>
                @endif
            </div>
        </div>
    </div>

    <form action="{{route('paswablinks.store')}}" method="POST" autocomplete="off">
        @csrf
        <div class="modal fade" id="addlink" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Link</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                          <label for="code">Input Pa-swab Referral Code</label>
                          <input type="text" class="form-control" name="code" id="code" value="{{old('code')}}" required>
                        </div>
                        <div class="form-group">
                          <label for="interviewer_id">Link Referral Code to this Interviewer Account</label>
                          <select class="form-control" name="interviewer_id" id="interviewer_id">
                            @foreach($interviewers as $i)
                                <option value="{{$i->id}}">{{$i->getName()}}</option>
                            @endforeach
                          </select>
                        </div>
                        <hr>
                        <div class="form-group">
                          <label for="is_lock_code">Lock the Pa-swab Code to Specific Brgy?</label>
                          <select class="form-control" name="is_lock_code" id="is_lock_code" required>
                            <option value="No" {{(old('is_lock_code') == "No") ? 'selected' : ''}}>No</option>
                            <option value="Yes" {{(old('is_lock_code') == "Yes") ? 'selected' : ''}}>Yes</option>
                          </select>
                        </div>
                        <div id="lockBrgyDiv" class="d-none">
                            <div class="d-none">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="address_cityjson" id="address_cityjson" value="{{old('address_cityjson')}}">
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="address_provincejson" id="address_provincejson" value="{{old('address_provincejson')}}">
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="address_province" id="address_province" value="{{old('address_province')}}">
                                  </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="address_city" id="address_city" value="{{old('address_city')}}">
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="taddress_brgy" id="taddress_brgy" value="{{old('taddress_brgy')}}">
                                </div>
                            </div>
                            <div class="form-group">
                              <label for="saddress_province">Select Province</label>
                              <select class="form-control" name="saddress_province" id="saddress_province">
                                <option value="" selected disabled>Choose...</option>
                              </select>
                            </div>
                            <div class="form-group">
                              <label for="saddress_city">Select City/Municipality</label>
                              <select class="form-control" name="saddress_city" id="saddress_city">
                                <option value="" selected disabled>Choose...</option>
                              </select>
                            </div>
                            <div class="form-group">
                              <label for="address_brgy">Select Barangay</label>
                              <select class="form-control" name="address_brgy" id="address_brgy">
                                <option value="" selected disabled>Choose...</option>
                              </select>
                            </div>
                            <div class="form-group">
                              <label for="">Specify Subdivisions <small>(Optional)</small></label>
                              <input type="text" class="form-control" name="lock_subd_array" id="lock_subd_array">
                              <small class="text-muted">Note: You can separate Subdivisions using commas (,)</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-2"></i>Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        $('#saddress_province, #saddress_city, #address_brgy').select2({
			theme: "bootstrap",
		});

        $('#saddress_city').prop('disabled', true);
		$('#address_brgy').prop('disabled', true);

        $('#is_lock_code').change(function (e) { 
            e.preventDefault();
            if($(this).val() == 'Yes') {
                $('#lockBrgyDiv').removeClass('d-none');
                $('#address_cityjson').prop('required', true);
                $('#address_provincejson').prop('required', true);
                $('#saddress_province').prop('required', true);
                $('#saddress_city').prop('required', true);
                $('#address_brgy').prop('required', true);
            }
            else {
                $('#lockBrgyDiv').addClass('d-none');
                $('#address_cityjson').prop('required', false);
                $('#address_provincejson').prop('required', false);
                $('#saddress_province').prop('required', false);
                $('#saddress_city').prop('required', false);
                $('#address_brgy').prop('required', false);
            }
        }).trigger('change');

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
				$("#spermaaddress_province").append('<option value="'+val.provCode+'">'+val.provDesc+'</option>');
				$("#soccupation_province").append('<option value="'+val.provCode+'">'+val.provDesc+'</option>');
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

        $('#address_brgy').change(function (e) {
            $('#taddress_brgy').val($('#address_brgy option:selected').text());
        });

        $("#address_province").val('CAVITE');
		$("#address_provincejson").val('0421');
		$("#address_city").val('GENERAL TRIAS');
		$('#address_cityjson').val('042108');
    </script>
@endsection