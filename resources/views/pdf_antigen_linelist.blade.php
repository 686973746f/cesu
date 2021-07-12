@extends('layouts.app_pdf')

@section('content')
<table class="table table-bordered" style="font-family: Arial, Helvetica, sans-serif;font-size: 80%">
    <thead class="text-center">
        <tr>
            <th colspan="8">Antigen Linelist for {{date('F d, Y')}}</th>
        </tr>
        <tr>
            <th>No.</th>
            <th>Name</th>
            <th>Age / Sex</th>
            <th>Address</th>
            <th>Contact No.</th>
            <th>Time</th>
            <th>Result</th>
            <th>Remarks</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $item)
        <tr>
            <td scope="row"></td>
            <td style="vertical-align: middle;">{{$item->records->getName()}}</td>
            <td class="text-center" style="vertical-align: middle;">{{$item->records->getAge()}} / {{substr($item->records->gender,0,1)}}</td>
            <td style="vertical-align: middle;"><small>{{$item->records->address_street}}, BRGY. {{$item->records->address_brgy}}, {{$item->records->address_city}}, {{$item->records->address_province}}</small></td>
            <td class="text-center" style="vertical-align: middle;">{{$item->records->mobile}}</td>
            <td class="text-center" style="vertical-align: middle;"></td>
            <td class="text-center" style="vertical-align: middle;"></td>
            <td class="text-center" style="vertical-align: middle;"></td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection