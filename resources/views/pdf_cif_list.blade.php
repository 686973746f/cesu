@extends('layouts.app_pdf')

@section('content')
<table class="table table-bordered table-sm" style="font-family: Arial, Helvetica, sans-serif;font-size: 65%">
    <thead class="text-center">
        <tr>
            <th colspan="10">For Swab List for {{date('F d, Y - l')}}</th>
        </tr>
        <tr>
            <th>No.</th>
            <th>Name / Test Type</th>
            <th>Philhealth</th>
            <th>Client Type</th>
            <th>Birthdate</th>
            <th>Age / Sex</th>
            <th>Address</th>
            <th>Contact No.</th>
            <th>Remarks</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $item)
        @php
        if($item->pType == "PROBABLE") {
            $pTypeStr = "SUSPECTED";
        }
        else if($item->pType == 'CLOSE CONTACT') {
            $pTypeStr = "CLOSE CONTACT";
        }
        else {
            $pTypeStr = "NON-COVID CASE";
        }
        @endphp
        <tr>
            <td scope="row" class="text-center">{{$loop->iteration}}</td>
            <td class="font-weight-bold" style="vertical-align: middle;">{{$item->records->getName()}} - <span class="text-primary">{{(!is_null($item->testDateCollected2)) ? $item->testType2 : $item->testType1}}</span></td>
            <td class="text-center" style="vertical-align: middle;">{{(!is_null($item->records->philhealth)) ? $item->records->philhealth : "N/A"}}</td>
            <td class="text-center" style="vertical-align: middle;">{{$pTypeStr}}</td>
            <td class="text-center" style="vertical-align: middle;">{{date('m/d/Y', strtotime($item->records->bdate))}}</td>
            <td class="text-center" style="vertical-align: middle;">{{$item->records->getAge()}} / {{substr($item->records->gender,0,1)}}</td>
            <td style="vertical-align: middle;"><small>{{$item->records->address_street}}, BRGY. {{$item->records->address_brgy}}, {{$item->records->address_city}}, {{$item->records->address_province}}</small></td>
            <td class="text-center" style="vertical-align: middle;">{{$item->records->mobile}}</td>
            <td></td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection