@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card mb-3">
        <div class="card-header font-weight-bold">Accomplishment Report Q1</div>
        <div class="card-body">
            <p>Q1 Total Active Cases: {{number_format($currq_active)}}</p>
            <p>Q1 Total Active Average ({{$currq_active}}/90): {{number_format($currq_active/90)}}</p>
        </div>
    </div>
    <div class="card">
        <div class="card-header">Swab Count</div>
        <div class="card-body">
            <table class="table">
                <tbody>
                    @foreach($swabarr as $s)
                    <tr>
                        <td>{{$s['month']}}</td>
                        <td>{{$s['count']}}</td>
                        <td>{{$s['suspro']}}</td>
                        <td>{{$s['confirmed']}}</td>
                        <td>{{$s['cc']}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <p>Last Year Swab Count: {{$lastYearSwab}}</p>
        </div>
    </div>
    <div class="card">
        <div class="card-header font-weight-bold">Accomplishment Report for PREVIOUS YEAR ({{date('Y', strtotime('-1 Year'))}})</div>
        <div class="card-body">
            <p>{{date('Y', strtotime('-1 Year'))}} Total Confirmed Swabbed by CHO: {{number_format($count1)}}</p>
            <p>{{date('Y', strtotime('-1 Year'))}} Confirmed Average: {{number_format($count2)}}</p>
            <p>{{date('Y', strtotime('-1 Year'))}} Number of Recoveries: {{number_format($count3)}}</p>
            <p>{{date('Y', strtotime('-1 Year'))}} Number of Deaths: {{number_format($count4)}}</p>
            <hr>
            <p>{{date('Y', strtotime('-1 Year'))}} Confirmed Male Total/Percentage: {{number_format($malecount)}} / {{round(($malecount/$count1) * 100)}}%</p>
            <p>{{date('Y', strtotime('-1 Year'))}} Confirmed Female Total/Percentage: {{number_format($femalecount)}} / {{round(($femalecount/$count1) * 100)}}%</p>
            <hr>
            <p>{{date('Y', strtotime('-1 Year'))}} Suspected: {{number_format($count5)}}</p>
            <p>{{date('Y', strtotime('-1 Year'))}} Probable: {{number_format($count6)}}</p>
            <p>{{date('Y', strtotime('-1 Year'))}} Close Contact: {{number_format($count7)}}</p>
            <hr>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="font-weight-bold text-center">
                        <tr class="bg-danger text-white">
                            <th colspan="5">2021 BARANGAY DATA</th>
                        </tr>
                        <tr class="thead-light">
                            <th>Barangay</th>
                            <th class="text-danger">Confirmed</th>
                            <th class="text-success">Recoveries</th>
                            <th>Deaths</th>
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
                            <td class="text-success text-center">{{number_format($brgy['recoveries'])}}</td>
                            <td class="text-center">{{number_format($brgy['deaths'])}}</td>
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
                            <td class="text-success">{{number_format($totalRecoveries)}}</td>
                            <td>{{number_format($totalDeaths)}}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection