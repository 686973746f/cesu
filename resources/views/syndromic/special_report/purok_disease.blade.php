@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header"><b>OPD SP</b></div>
            <div class="card-body">
                <table class="table table-striped table-bordered">
                    <thead class="thead-light text-center">
                        <tr>
                            <th colspan="4">Disease Case per Purok BRGY. {{$brgy->alt_name ?: $brgy->name}} {{Carbon\Carbon::parse($date1)->format('M. d, Y')}} to {{Carbon\Carbon::parse($date2)->format('M. d, Y')}}</th>
                        </tr>
                        <tr>
                            <th>Street / Subdivision / Purok</th>
                            <th>Male</th>
                            <th>Female</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($streetCounts as $row)
                            <tr>
                                <td>{{ $row->address_street }}</td>
                                <td class="text-center">{{ $row->male }}</td>
                                <td class="text-center">{{ $row->female }}</td>
                                <td class="text-center">{{ $row->total }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection