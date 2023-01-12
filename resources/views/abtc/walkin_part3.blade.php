@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="card">
            <img class="card-img-top" src="holder.js/100x180/" alt="Title">
            <div class="card-body">
                <div class="alert alert-success" role="alert">
                    Kumpleto na ang iyong registration.
                </div>
                <div id="divToPrint">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th colspan="3"><strong class="text-info"><i class="fa-solid fa-user me-2"></i>PERSONAL INFORMATION</strong></th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            <tr>
                                <td class="bg-light" style="vertical-align: middle"><strong>Name</td>
                                <td>{{$d->patient->getName()}} (#{{$d->id}})</td>
                                <td rowspan="4" style="vertical-align: middle">{!! QrCode::size(150)->generate($d->patient->qr) !!}</td>
                            </tr>
                            <tr>
                                <td class="bg-light" style="vertical-align: middle"><strong>Birthdate/Age/Gender</strong></td>
                                <td>{{date('m-d-Y', strtotime($d->patient->bdate))}} / {{$d->patient->getAge()}} / {{$d->patient->sg()}}</td>
                            </tr>
                            <tr>
                                <td class="bg-light" style="vertical-align: middle"><strong>Address</strong></td>
                                <td><small>{{$d->patient->getAddress()}}</small></td>
                            </tr>
                            <tr>
                                <td class="bg-light" style="vertical-align: middle"><strong>Contact No.</strong></td>
                                <td>{{$d->patient->contact_number}}</td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th colspan="2"><strong class="text-info"><i class="fa-solid fa-syringe me-2"></i>ANTI-RABIES VACCINATION DETAILS</strong></th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            <tr>
                                <td class="bg-light"><strong>Registration #</strong></td>
                                <td>{{$d->case_id}}</td>
                            </tr>
                            <tr>
                                <td class="bg-light"><strong>Registration Date</strong></td>
                                <td>{{date('m/d/Y (l)', strtotime($d->case_date))}}</td>
                            </tr>
                            <tr>
                                <td class="bg-light"><strong>Animal Type / Bite Type / Date of Bite</strong></td>
                                <td>{{$d->animal_type}} / {{$d->bite_type}} / {{date('m/d/Y (l)', strtotime($d->bite_date))}}</td>
                            </tr>
                            <tr>
                                <td class="bg-light"><strong>Category</strong></td>
                                <td>Category {{$d->category_level}}</td>
                            </tr>
                            <tr>
                                <td class="bg-light"><strong>1st Vaccine/Day 0 Date</strong></td>
                                <td>{{date('m/d/Y (l)', strtotime($d->d0_date))}} @if($d->d0_done == 1) - <strong class="text-success">DONE</strong> @endif</td>
                            </tr>
                            <tr>
                                <td class="bg-light"><strong>Day 3 Date</strong></td>
                                <td>{{date('m/d/Y (l)', strtotime($d->d3_date))}} @if($d->d3_done == 1) - <strong class="text-success">DONE</strong> @endif</td>
                            </tr>
                            @if($d->is_booster == 0)
                            <tr>
                                <td class="bg-light"><strong>Day 7 Date</strong></td>
                                <td>{{date('m/d/Y (l)', strtotime($d->d7_date))}} @if($d->d7_done == 1) - <strong class="text-success">DONE</strong> @endif</td>
                            </tr>
                            @if($d->pep_route != 'ID')
                            <tr>
                                <td class="bg-light"><strong>Day 14 Date</strong></td>
                                <td>{{date('m/d/Y (l)', strtotime($d->d14_date))}} @if($d->d14_done == 1) - <strong class="text-success">DONE</strong> @endif</td>
                            </tr>
                            @endif
                            <tr>
                                <td class="bg-light"><strong>Date 28 Date</strong></td>
                                <td>{{date('m/d/Y (l)', strtotime($d->d28_date))}} @if($d->d28_done == 1) - <strong class="text-success">DONE</strong> @endif</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection