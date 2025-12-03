@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header"><b>OPD SP</b></div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead class="thead-light text-center">
                        <tr>
                            <th colspan="4">Demographics BRGY. {{$brgy->alt_name ?: $brgy->name}} {{Carbon\Carbon::parse($date1)->format('M. d, Y')}} to {{Carbon\Carbon::parse($date2)->format('M. d, Y')}}</th>
                        </tr>
                        <tr>
                            <th>Age Group</th>
                            <th>Male</th>
                            <th>Female</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($demographics as $label => $counts)
                            <tr>
                                <td>'{{ $label }}</td>
                                <td class="text-center">{{ $counts['male'] }}</td>
                                <td class="text-center">{{ $counts['female'] }}</td>
                                <td class="text-center"><b>{{ $counts['total'] }}</b></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection