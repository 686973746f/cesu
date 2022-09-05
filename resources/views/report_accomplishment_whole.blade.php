@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="font-weight-bold text-center">
                        <tr class="bg-danger text-white">
                            <th colspan="5">OVERALL BRGY DATA</th>
                        </tr>
                        <tr class="thead-light">
                            <th>Barangay</th>
                            <th class="text-danger">Confirmed</th>
                            <th>Deaths</th>
                            <th class="text-success">Recoveries</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $totalConfirmed = 0;
                        $totalDeaths = 0;
                        $totalRecoveries = 0;
                        @endphp
                        @foreach($brgylist as $brgy)
                        <tr>
                            <td class="font-weight-bold">{{$brgy['name']}}</td>
                            <td class="text-danger text-center">{{number_format($brgy['confirmed'])}}</td>
                            <td class="text-center">{{number_format($brgy['deaths'])}}</td>
                            <td class="text-success text-center">{{number_format($brgy['recoveries'])}}</td>
                        </tr>
                        @php
                        $totalConfirmed += $brgy['confirmed'];
                        $totalDeaths += $brgy['deaths'];
                        $totalRecoveries += $brgy['recoveries'];
                        @endphp
                        @endforeach
                    </tbody>
                    <tfoot class="bg-light text-center font-weight-bold">
                        <tr>
                            <td>TOTAL</td>
                            <td class="text-danger">{{number_format($totalConfirmed)}}</td>
                            <td>{{number_format($totalDeaths)}}</td>
                            <td class="text-success">{{number_format($totalRecoveries)}}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection