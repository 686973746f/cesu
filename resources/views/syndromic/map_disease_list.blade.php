@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <b>Cases List Viewer ({{ucwords($type)}})</b>
            <hr>
            BRGY. {{$b->brgyName}}, {{$b->city->cityName}}, {{$b->city->province->provinceName}}
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="thead-light text-center">
                        <tr>
                            <th>#</th>
                            <th>ITR ID</th>
                            <th>Name / Patient ID</th>
                            <th>Birthdate</th>
                            <th>Age/Sex</th>
                            <th>Contact #</th>
                            <th>House/Street</th>
                            <th>Barangay</th>
                            <th>City</th>
                            <th>Province</th>
                            <th>Symptoms</th>
                            <th>Encoded At / By</th>
                            <th>Brgy At / By</th>
                            <th>CESU Verified At / By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list as $l)
                        <tr>
                            <td class="text-center">{{$loop->iteration}}</td>
                            <td class="text-center"><a href="{{route('syndromic_viewRecord', $l->id)}}">{{$l->id}}</a></td>
                            <td><a href="{{route('syndromic_viewPatient', $l->syndromic_patient->id)}}"><b>{{$l->syndromic_patient->getName()}} <small>(#{{$l->syndromic_patient->id}})</small></b></a></td>
                            <td class="text-center">{{date('m/d/Y', strtotime($l->syndromic_patient->bdate))}}</td>
                            <td class="text-center">{{$l->syndromic_patient->getAge()}} / {{substr($l->syndromic_patient->gender,0,1)}}</td>
                            <td class="text-center">{{$l->syndromic_patient->getContactNumber()}}</td>
                            <td class="text-center"><small>{{$l->syndromic_patient->address_houseno}}, {{$l->syndromic_patient->address_street}}</small></td>
                            <td class="text-center">{{$l->syndromic_patient->address_brgy_text}}</td>
                            <td class="text-center">{{$l->syndromic_patient->address_muncity_text}}</td>
                            <td class="text-center">{{$l->syndromic_patient->address_province_text}}</td>
                            <td class="text-center"><small>{{$l->listSymptoms()}}</small></td>
                            <td class="text-center"><small>{{date('m/d/Y h:i A', strtotime($l->created_at))}} by {{$l->user->name}}</small></td>
                            <td class="text-center"><small>{{$l->getBrgyVerified()}}</small></td>
                            <td class="text-center"><small>{{$l->getCesuVerified()}}</small></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection