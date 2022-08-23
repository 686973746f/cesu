@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header"><b>{{$qstr}}</b></div>
                <div class="card-body">
                    <p>Total Active Cases: {{number_format($currq_active)}}</p>
                    <p>Total Active Average ({{$currq_active}}/90): {{round($currq_active/90)}} Cases per Day</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header"><b>{{date('Y')}} Hospitalization</b></div>
                <div class="card-body">
                    <p>Number of Confirmed Hospitalized Patients: <b>{{$cy_hospitalized}}</b></p>
                    <p>(Active: {{($cy_hospitalized - $cy_hospitalized_recovered - $cy_hospitalized_died)}} | Recovered: {{$cy_hospitalized_recovered}} | Died: {{$cy_hospitalized_died}})</p>
                    <ul>
                        <li>Unvaccinated: {{($cy_hospitalized - $cy_hospitalized_partialvacc - $cy_hospitalized_fullvacc - $cy_hospitalized_boostered)}}</li>
                        <li>Partially Vaccinated: {{$cy_hospitalized_partialvacc}}</li>
                        <li>Fully Vaccinated: {{$cy_hospitalized_fullvacc}}</li>
                        <li>Boostered: {{$cy_hospitalized_boostered}}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="card mb-3">
        <div class="card-header"><b>Swab Count</b></div>
        <div class="card-body">
            <table class="table table-bordered text-center">
                <thead class="thead-light">
                    <tr>
                        <th>Month</th>
                        <th>Swab</th>
                        <th>Suspected/Probable</th>
                        <th>Confirmed</th>
                        <th>Close Contact</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($swabarr as $s)
                    <tr>
                        <td><b>{{$s['month']}}</b></td>
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
        <div class="card-header font-weight-bold text-center">Accomplishment Report for PREVIOUS YEAR ({{date('Y', strtotime('-1 Year'))}})</div>
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
            
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="font-weight-bold text-center">
                        <tr class="bg-danger text-white">
                            <th colspan="5">{{date('Y', strtotime('-2 Year'))}} BARANGAY DATA</th>
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
                        @foreach($brgylist1 as $brgy)
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

    <div class="card">
        <div class="card-header">Age Group</div>
        <div class="card-body">
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th>Age</th>
                        <th>Confirmed {{date('Y', strtotime('-2 Year'))}}</th>
                        <th>Death {{date('Y', strtotime('-2 Year'))}}</th>
                        <th>Recoveries {{date('Y', strtotime('-2 Year'))}}</th>
                        <th>Confirmed {{date('Y', strtotime('-1 Year'))}}</th>
                        <th>Death {{date('Y', strtotime('-1 Year'))}}</th>
                        <th>Recoveries {{date('Y', strtotime('-1 Year'))}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($age_array as $a)
                    <tr>
                        <td scope="row">{{$a['name']}}</td>
                        <td>{{$a['c_l2y']}}</td>
                        <td>{{$a['d_l2y']}}</td>
                        <td>{{$a['r_l2y']}}</td>
                        <td>{{$a['c_l1y']}}</td>
                        <td>{{$a['d_l1y']}}</td>
                        <td>{{$a['r_l1y']}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
        </div>
    </div>
</div>
@endsection