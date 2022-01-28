@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="{{route('sc_edit', ['id' => $item->id])}}" method="POST">
            @csrf
            @method('PUT')
            <div class="card">
                <div class="card-header font-weight-bold">Add Health Declaration Record</div>
                <div class="card-body">
                    @if($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <p>{{Str::plural('Error', $errors->count())}} detected in creating new record:</p>
                        <hr>
                        @foreach ($errors->all() as $error)
                            <li>{{$error}}</li>
                        @endforeach
                    </div>
                    @endif
                    @if(session('msg'))
                    <div class="alert alert-{{session('msgtype')}}" role="alert">
                        {{session('msg')}}
                    </div>
                    @endif
                    <div class="alert alert-info" role="alert">
                        Note: All fields marked with an asterisk (<span class="text-danger font-weight-bold">*</span>) are required.
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="lname"><span class="text-danger font-weight-bold">*</span>Last Name</label>
                                <input type="text" class="form-control" id="lname" name="lname" value="{{old('lname', $item->lname)}}" max="50" style="text-transform: uppercase;" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="fname"><span class="text-danger font-weight-bold">*</span>First Name (and Suffix)</label>
                                <input type="text" class="form-control" id="fname" name="fname" value="{{old('fname', $item->fname)}}" max="50" style="text-transform: uppercase;" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="mname">Middle Name <small>(Optional)</small></label>
                                <input type="text" class="form-control" id="mname" name="mname" value="{{old('mname', $item->fname)}}" max="50" style="text-transform: uppercase;" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="gender"><span class="text-danger font-weight-bold">*</span>Gender</label>
                                <select class="form-control" name="gender" id="gender" required>
                                    <option value="" disabled {{(is_null(old('gender', $item->gender))) ? 'selected' : ''}}>Choose...</option>
                                    <option value="MALE" {{(old('gender', $item->gender) == 'MALE') ? 'selected' : ''}}>Male</option>
                                    <option value="FEMALE" {{(old('gender', $item->gender) == 'FEMALE') ? 'selected' : ''}}>Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="bdate">Birthdate <small>(Optional)</small></label>
                                <input type="date" class="form-control" id="bdate" name="bdate" value="{{old('bdate', $item->bdate)}}" min="1900-01-01" max="{{date('Y-m-d', strtotime('yesterday'))}}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email Address <small>(Optional)</small></label>
                                <input type="email" class="form-control" name="email" id="email" value="{{old('email', $item->email)}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="mobile">Cellphone No. <small>(Optional)</small></label>
                                <input type="text" class="form-control" id="mobile" name="mobile" value="{{old('mobile', $item->mobile)}}" pattern="[0-9]{11}" placeholder="0917xxxxxxx">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div id="addresstext" class="d-none">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                <input type="text" class="form-control" name="address_province" id="address_province" value="{{old('address_province', $item->address_province)}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="address_city" id="address_city" value="{{old('address_city', $item->address_city)}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                <input type="text" class="form-control" name="address_provincejson" id="address_provincejson" value="{{old('address_provincejson', $item->address_provincejson)}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="address_cityjson" id="address_cityjson" value="{{old('address_cityjson', $item->address_cityjson)}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="saddress_province">Province <small>(Optional)</small></label>
                                <select class="form-control" name="saddress_province" id="saddress_province">
                                    <option value="" selected disabled>Choose...</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="saddress_city">City <small>(Optional)</small></label>
                                <select class="form-control" name="saddress_city" id="saddress_city">
                                    <option value="" selected disabled>Choose...</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="address_brgy">Barangay <small>(Optional)</small></label>
                                <select class="form-control" name="address_brgy" id="address_brgy">
                                    <option value="" selected disabled>Choose...</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="address_houseno">House No./Lot/Building <small>(Optional)</small></label>
                                <input type="text" class="form-control" id="address_houseno" name="address_houseno" style="text-transform: uppercase;" value="{{old('address_houseno', $item->address_houseno)}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="address_street">Street/Purok/Sitio <small>(Optional)</small></label>
                                <input type="text" class="form-control" id="address_street" name="address_street" style="text-transform: uppercase;" value="{{old('address_street', $item->address_street)}}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                              <label for="morbidityMonth"><span class="text-danger font-weight-bold">*</span>Morbidity Month</label>
                              <input type="date" class="form-control" name="morbidityMonth" id="morbidityMonth" min="{{date('Y-m-d')}}" max="{{date('Y-m-d')}}" value="{{old('morbidityMonth', date('Y-m-d'))}}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="dateReported"><span class="text-danger font-weight-bold">*</span>Date Reported</label>
                                <input type="date" class="form-control" name="dateReported" id="dateReported" min="{{date('Y-m-d')}}" max="{{date('Y-m-d')}}" value="{{old('dateReported', date('Y-m-d'))}}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                              <label for="temperature">Temperature <small>(Optional)</small></label>
                              <input type="number" class="form-control" name="temperature" id="temperature" min="1" max="90" step=".1" value="{{old('temperature', $item->temperature)}}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        $('#saddress_province, #saddress_city, #address_brgy').select2({
			theme: "bootstrap",
		});

        $(document).ready(function () {
            $('#saddress_city').prop('disabled', true);
		    $('#address_brgy').prop('disabled', true);
        });

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
    </script>
@endsection