@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<div class="container">
    <div class="row justify-content-center">
        <div class="card">
            <div class="card-body">
                <div class="alert alert-success text-center" role="alert">
                    Kumpleto na ang iyong registration. Paki-screenshot ang pahinang ito at maaari nang pumila.
                </div>
                <div class="text-center">
                    <h4>Ang bilang mo sa pila:</h4>
                    <h1><b>#{{$pila_count}}</b></h1>
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
                                <td class="bg-light"><strong>Day 0</strong></td>
                                <td>{{date('m/d/Y (l)', strtotime($d->d0_date))}} @if($d->d0_done == 1) - <strong class="text-success">DONE</strong> @endif</td>
                            </tr>
                            <tr>
                                <td class="bg-light"><strong>Day 3</strong></td>
                                <td>{{date('m/d/Y (l)', strtotime($d->d3_date))}} @if($d->d3_done == 1) - <strong class="text-success">DONE</strong> @endif</td>
                            </tr>
                            @if($d->is_booster == 0)
                            <tr>
                                <td class="bg-light"><strong>Day 7</strong></td>
                                <td>{{date('m/d/Y (l)', strtotime($d->d7_date))}} @if($d->d7_done == 1) - <strong class="text-success">DONE</strong> @endif</td>
                            </tr>
                            @if($d->pep_route != 'ID')
                            <tr>
                                <td class="bg-light"><strong>Day 14</strong></td>
                                <td>{{date('m/d/Y (l)', strtotime($d->d14_date))}} @if($d->d14_done == 1) - <strong class="text-success">DONE</strong> @endif</td>
                            </tr>
                            @endif
                            <tr>
                                <td class="bg-light">
                                    <p><strong>Day 28</strong> <i>(Opsyonal)</i></p>
                                    <!-- <small><i>Kapag namatay ang hayop makalipas ng 14 na araw. O kapag gala ang hayop at hindi na makita kung saan.</i></small> -->
                                </td>
                                <td>{{date('m/d/Y (l)', strtotime($d->d28_date))}} @if($d->d28_done == 1) - <strong class="text-success">DONE</strong> @endif</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer text-center">
                <p>May iba pa bang kasama na magpapabakuna rin? <a href="{{route('abtc_walkin_part1', ['v' => session('vrefcode')])}}">Mag-sumite ulit dito</a></p>
            </div>
        </div>
        <p class="mt-3 text-center">CESU/ABTC System Developed and Maintained by <u>Christian James Historillo</u> for CESU Gen. Trias, Cavite ©{{date('Y')}}</p>
    </div>
</div>
@endsection