@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header"><b>Pharmacy Dispensary Report</b></div>
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="col-md-4">
                    <div class="card bg-success text-white text-center mb-3">
                        <div class="card-header"><h4>Total Patients</h4>
                        <h6>Sep. 27 - Dec. 31, 2023</h6></div>
                        <div class="card-body">
                            <h1><b>4,286</b></h1>
                        </div>
                      </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-3">
                    <div class="card bg-info text-center mb-3">
                        <div class="card-header"><h4>Male</h4></div>
                        <div class="card-body">
                            <h1><b>2,386</b></h1>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-center mb-3">
                        <div class="card-header text-danger"><h4>Female</h4></div>
                        <div class="card-body">
                            <h1 class="text-danger"><b>1,900</b></h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection