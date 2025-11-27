@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-center">
            <div class="col-8">
                <div class="card">
                    <div class="card-header"><b>FHSIS TB-DOTS</b></div>
                    <div class="card-body">
                        @if(session('msg'))
                        <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                            {{session('msg')}}
                        </div>
                        @endif
                        @if($errors->any())
                        <div class="alert alert-danger" role="alert">
                            <p>{{Str::plural('Error', $errors->count())}} detected in importing the ITIS File:</p>
                            <hr>
                            @foreach ($errors->all() as $error)
                                <li>{{$error}}</li>
                            @endforeach
                        </div>
                        @endif
                        <button type="button" class="btn btn-success btn-block" data-toggle="modal" data-target="#importTool">Import Excel File</button>
                        <hr>
                        <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#loadDashboard">Load Dashboard</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="{{route('fhsis_tbdots_import')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal fade" id="importTool" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Import Excel Tool</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                          <label for=""><b class="text-danger">*</b>Upload Excel file from ITIS</label>
                          <input type="file" class="form-control-file" name="itis_file" id="itis_file" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-block">Upload</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <form action="{{route('fhsis_tbdots_dashboard')}}" method="GET">
        <div class="modal fade" id="loadDashboard" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Load TB-DOTS Morbidity Dashboard</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="brgy"><b class="text-danger">*</b>Select Barangay</label>
                            <select class="form-control" name="brgy" id="brgy" required>
                                <option value="" disabled selected>Choose...</option>
                                @foreach($brgy_list as $b)
                                <option value="{{$b->brgyName}}">{{$b->brgyName}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="year"><b class="text-danger">*</b>Select Year</label>
                            <select class="form-control" name="year" id="year" required>
                                <option value="" disabled selected>Choose...</option>
                                @foreach(range(date('Y'), 2023) as $y)
                                <option value="{{$y}}">{{$y}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="month"><b class="text-danger">*</b>Select Month</label>
                            <select class="form-control" name="month" id="month" required>
                                <option value="" disabled selected>Choose...</option>
                                <option value="01">January</option>
                                <option value="02">February</option>
                                <option value="03">March</option>
                                <option value="04">April</option>
                                <option value="05">May</option>
                                <option value="06">June</option>
                                <option value="07">July</option>
                                <option value="08">August</option>
                                <option value="09">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-block">Generate</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection