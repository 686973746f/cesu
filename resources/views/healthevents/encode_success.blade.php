@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header"><b>General Trias CESU - {{$he->event_name}}</b></div>
                    <div class="card-body">
                        <div class="alert alert-success text-center" role="alert">
                            The form was successfully submitted. Thank you for using the program.
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <a href="{{route('he_index', [$event_code, $facility_code])}}" class="btn btn-link btn-block">Submit Another</a>
                    </div>
                </div>
                <p class="mt-3 text-center">©2021 - 2024 Developed and Maintained by <u>CJH</u></p>
            </div>
        </div>
    </div>
@endsection