@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <p>Today is: {{date('M. d, Y')}} - Morbidity Week: {{date('W')}}</p>
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div><b>Encoded Epidemic-prone Disease Dashboard (BRGY. {{session('brgyName')}}) - Year: {{$year}}</b></div>
                    <div>
                        <form action="{{route('edcs_barangay_view_logout')}}" method="POST">
                            @csrf
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#filterModal">Change Year</button>
                            <button type="submit" class="btn btn-danger">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body text-center">
                @include('pidsr.epdrone_body')
            </div>
        </div>
        <p class="mt-3 text-center">©2021 - {{date('Y')}} Developed and Mainted by <u>CJH</u> for General Trias CHO - CESU</p>
    </div>

    <form action="" method="GET">
        <div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Change Year</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="year"><b class="text-danger">*</b>Select Year</label>
                            <select class="form-control" name="year" id="year" required>
                                <option disabled {{(is_null(request()->input('year'))) ? 'selected' : ''}}>Choose...</option>
                                @foreach(range(date('Y'), 2015) as $y)
                                <option value="{{$y}}" {{(request()->input('year') == $y) ? 'selected' : ''}}>{{$y}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection