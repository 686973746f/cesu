@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<form action="{{route('abtc_walkin_part2')}}" method="GET">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header"><b>Anti-Rabies Vaccination - Walk in Registration ({{session('vaccination_site_name')}})</b></div>
                    <div class="card-body">
                        <div class="alert alert-info" role="alert">
                            <b>Note:</b> All fields marked with an asterisk (<strong class="text-danger">*</strong>) are required fields.
                        </div>
                        @if(session('msg'))
                        <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                            {{session('msg')}}
                        </div>
                        @endif
                        <div class="mb-3">
                            <label for="fname" class="form-label"><b class="text-danger">*</b>Unang Pangalan/First Name</label>
                            <input type="text" class="form-control" name="fname" id="fname" value="{{old('fname')}}" minlength="2" maxlength="50" style="text-transform: uppercase;" placeholder="JUAN JR" required>
                        </div>
                        <div class="mb-3">
                            <label for="mname" class="form-label">Gitnang Pangalan/Middle Name <small>(If Applicable)</small></label>
                            <input type="text" class="form-control" name="mname" id="mname" value="{{old('mname')}}" minlength="2" maxlength="50" style="text-transform: uppercase;" placeholder="SANCHEZ">
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="lname" class="form-label"><b class="text-danger">*</b>Apelyido/Surname</label>
                                    <input type="text" class="form-control" name="lname" id="lname" value="{{old('lname')}}" minlength="2" maxlength="50" style="text-transform: uppercase;" placeholder="DELA CRUZ" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="suffix" class="form-label">Suffix <i><small>(If Applicable)</small></i></label>
                                    <input type="text" class="form-control" name="suffix" id="suffix" value="{{old('suffix')}}" maxlength="3" placeholder="e.g JR, SR, III, IV">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="bdate" class="form-label"><b class="text-danger">*</b>Birthdate/Araw ng Kapanganakan</label>
                            <input type="date" class="form-control" name="bdate" id="bdate" value="{{old('bdate')}}" min="1900-01-01" max="{{date('Y-m-d', strtotime('yesterday'))}}" required>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-primary">Next</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="modal fade" id="announcement" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center">Maligayang pagdating sa <b>ABTC Online Registration System</b> <i>({{session('vaccination_site_name')}})</i></h5>
            </div>
            <div class="modal-body">
                <h4 class="text-center text-danger"><b>PAALALA</b></h4>
                <p>Ang registration link ay para lamang sa mga bagong pasyente na hindi pa nababakunahan ng anti-rabies dito sa {{session('vaccination_site_name')}}.</p>
                <p>Hindi na kailangang mag-rehistro ulit ang mga follow-up na pasyente.</p>
            </div>
            <div class="modal-footer text-center">
                <button type="button" class="btn btn-secondary btn-block" data-dismiss="modal">Naiintindihan ko, magpatuloy</button>
            </div>
        </div>
    </div>
</div>

<script>
    $('#announcement').modal({backdrop: 'static', keyboard: false});
    $('#announcement').modal('show');
</script>
@endsection