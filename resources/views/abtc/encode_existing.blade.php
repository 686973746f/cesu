@extends('layouts.app')

@section('content')
<style>
    @media print {
        @page {
            margin: 0;
        }
        
        body * {
            visibility: hidden;
        }

        #divToPrint, #divToPrint * {
            visibility: visible;
        }

        #divToPrint {
            position: absolute;
            left: 0;
            top: 0;
        }
    }
</style>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-warning">
                <div class="card-header bg-warning text-center"><strong class="text-danger"><i class="fa-solid fa-triangle-exclamation me-2"></i>Existing Vaccination Record Found</strong></div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-secondary btn-sm mb-3" onclick="window.print()"><i class="fa-solid fa-print me-2"></i>Print Form</button>
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
                                    <td>{{(!is_null($d->bdate)) ? date('m-d-Y', strtotime($d->patient->bdate)) : 'N/A'}} / {{$d->patient->getAge()}} / {{$d->patient->sg()}}</td>
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
                                    <td class="bg-light"><strong>Body Part / Category</strong></td>
                                    <td>{{$d->body_site}} / Category {{$d->category_level}}</td>
                                </tr>
                                <tr>
                                    <td class="bg-light"><strong>Outcome</strong></td>
                                    <td>{{$d->outcome}}</td>
                                </tr>
                                <tr>
                                    <td class="bg-light"><strong>Vaccine Brand Name</strong></td>
                                    <td>{{$d->brand_name}}</td>
                                </tr>
                                <tr>
                                    <td class="bg-light"><strong>Day 0 Date</strong></td>
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
                    <div class="d-grid gap-2">
                        <a href="{{route('encode_edit', ['br_id' => $d->id])}}" class="btn btn-primary"><i class="fa-solid fa-eye me-2"></i>View/Edit Vaccination Details of Patient</a>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <a href="{{route('home')}}" class="btn btn-link"><i class="fa-solid fa-backward-step me-2"></i>Go Back</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection