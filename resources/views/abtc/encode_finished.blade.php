@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-success">
                <div class="card-header text-center bg-success text-white"><strong><i class="fa-solid fa-circle-check me-2"></i>Success!</strong></div>
                <div class="card-body text-center">
                    <p>{{session('msg')}}</p>
                    @if(session('dose') != 5)
                    <div class="alert alert-info" role="alert">
                        <b class="text-danger">Reminders:</b> Observe the responsible animal for 14 days and report to the veterinarian any changes noted in the animal during the observation period. And please see the details below for your next doses schedule.
                    </div>
                    @endif
                    <hr>
                    {!! QrCode::size(150)->generate($f->patient->qr) !!}
                    <p><strong>Registration #:</strong> <u>{{$f->case_id}}</u></p>
                    <p><strong>Name:</strong> <u>{{$f->patient->getName()}}</u></p>
                    <p><strong>Age/Gender:</strong> <u>{{$f->patient->getAge()}} / {{$f->patient->sg()}}</u></p>
                    <p><strong>Address:</strong> <u>{{$f->patient->getAddressMini()}}</u></p>
                    <p><strong>Contact #: </strong> <u>{{(!is_null($f->patient->contact_number)) ? $f->patient->contact_number : 'N/A'}}</u></p>
                    <p><strong>Type of Animal:</strong> • <strong>Date of Bite:</strong> <u>{{date('m/d/Y (l)', strtotime($f->bite_date))}}</u> </p>
                    <p><strong>Body Part:</strong> <u>{{$f->body_site}} • <strong>Category:</strong> <u>{{$f->category_level}}</u></p>
                    <p><strong>Health Facility:</strong> <u>{{$f->vaccinationsite->site_name}}</u></p>

                    <table class="table table-bordered table-striped">
                        <thead class="bg-light">
                            <tr>
                                <th colspan="2">Vaccine Brand: {{$f->brand_name}}</th>
                            </tr>
                            <tr class="text-center">
                                <th>Dose Schedule</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            <tr>
                                <td><strong>Day 0</strong></td>
                                <td>{{date('m/d/Y (l)', strtotime($f->d0_date))}} @if($f->d0_done == 1) - <strong class="text-success">DONE</strong> @endif</td>
                            </tr>
                            <tr>
                                <td><strong>3rd Day</strong></td>
                                <td>{{date('m/d/Y (l)', strtotime($f->d3_date))}} @if($f->d3_done == 1) - <strong class="text-success">DONE</strong> @endif</td>
                            </tr>
                            @if($f->is_booster == 0)
                            <tr>
                                <td><strong>7th Day</strong></td>
                                <td>{{date('m/d/Y (l)', strtotime($f->d7_date))}} @if($f->d7_done == 1) - <strong class="text-success">DONE</strong> @endif</td>
                            </tr>
                            @if($f->pep_route != 'ID')
                            <tr>
                                <td><strong>14th Day</strong></td>
                                <td>{{date('m/d/Y (l)', strtotime($f->d14_date))}} @if($f->d14_done == 1) - <strong class="text-success">DONE</strong> @endif</td>
                            </tr>
                            @endif
                            <tr>
                                <td><strong>28th Day</strong></td>
                                <td>{{date('m/d/Y (l)', strtotime($f->d28_date))}} @if($f->d28_done == 1) - <strong class="text-success">DONE</strong> @endif</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                    <p class="text-center"><b>ABTC Offices:</b></p>
                    <ul class="text-center">
                        <li>City Health Office - Main, Pinagtipunan, General Trias City</li>
                        <li>Barangay Health Center - Manggahan, General Trias City</li>
                    </ul>
                </div>
                <div class="card-footer text-center">
                    <a href="{{route('home')}}" class="btn btn-link"><i class="fa-solid fa-house me-2"></i>Back to Home</a>
                    <hr>
                    <a href="{{route('encode_edit', ['br_id' => $f->id])}}">Back to Patient Animal Bite Record</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection