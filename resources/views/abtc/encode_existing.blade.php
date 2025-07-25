@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if(session('msg'))
            <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                {{session('msg')}}
            </div>
            @endif
            <div class="card border-warning">
                <div class="card-header bg-warning text-center"><strong class="text-danger"><i class="fa-solid fa-triangle-exclamation me-2"></i>Existing Vaccination Record Found!</strong></div>
                <div class="card-body">
                    @if($d)
                    <a href="{{route('abtc_print_view', $d->id)}}?t=1" class="btn btn-secondary btn-block mb-3"><i class="fas fa-print mr-2"></i>Print</a>
                    @endif
                    <div id="divToPrint">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th colspan="3"><strong class="text-info"><i class="fa-solid fa-user me-2"></i>PERSONAL INFORMATION</strong></th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    <tr>
                                        <td class="bg-light" style="vertical-align: middle"><strong>Name</td>
                                        <td><a href="{{route('abtc_patient_edit', $p->id)}}">{{$p->getName()}} (#{{$p->id}})</a></td>
                                        <td rowspan="4" style="vertical-align: middle">{!! QrCode::size(150)->generate($p->qr) !!}</td>
                                    </tr>
                                    <tr>
                                        <td class="bg-light" style="vertical-align: middle"><strong>Birthdate/Age/Gender</strong></td>
                                        <td>{{(!is_null($p->bdate)) ? date('F d, Y', strtotime($p->bdate)) : 'N/A'}} / {{$p->getAge()}} / {{$p->sg()}}</td>
                                    </tr>
                                    <tr>
                                        <td class="bg-light" style="vertical-align: middle"><strong>Address</strong></td>
                                        <td><small>{{$p->getAddress()}}</small></td>
                                    </tr>
                                    <tr>
                                        <td class="bg-light" style="vertical-align: middle"><strong>Contact No.</strong></td>
                                        <td>{{$p->contact_number ?: 'N/A'}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        @if($d)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="thead-light">
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
                                        <td class="bg-light"><strong>Day 28 Date</strong></td>
                                        <td>{{date('m/d/Y (l)', strtotime($d->d28_date))}}
                                            @if($d->d28_done == 1)
                                            - <strong class="text-success">DONE</strong>
                                            @else
                                                @if(Carbon\Carbon::parse($d->d28_date)->gte(Carbon\Carbon::parse(date('Y-m-d'))) && $d->outcome == 'C')
                                                <form action="{{route('abtc_quickprocessd28', $d->id)}}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success" onclick="return confirm('Proceed with completing the D28 of {{$d->patient->getName()}}? Click OK to Proceed.')">Quick Process D28</button>
                                                </form>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        @else
                        <hr>
                        <p class="text-center">No vaccination record found.</p>
                        @endif
                    </div>
                    @if($d)
                    <a href="{{route('abtc_encode_edit', ['br_id' => $d->id])}}" class="btn btn-primary btn-block"><i class="fas fa-file mr-2"></i>View/Edit Vaccination Details of Patient</a>
                    @endif
                </div>
                <div class="card-footer text-center">
                    <a href="{{route('abtc_home')}}" class="btn btn-link"><i class="fas fa-backward mr-2"></i>Go Back</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection