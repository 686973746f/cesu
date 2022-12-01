@extends('layouts.app')

@section('content')
<div class="container">
    @if(!(request()->input('choice')))
    <form action="{{route('report.accomplishment')}}" method="GET">
        <div class="card">
            <div class="card-header">ACCOMPLISHMENT MENU</div>
            <div class="card-body">
                <button type="submit" name="choice" value="c1"  class="btn btn-primary">GET TOTAL ACTIVE CASES & HOSPITALIZATION</button>
                <button type="submit" name="choice" value="c2"  class="btn btn-primary">GET PREVIOUS YEAR ACCOMPLISHMENT COUNT</button>
                <button type="submit" name="choice" value="c4"  class="btn btn-primary">GET PREVIOUS/CURRENT YEAR PER BRGY COUNT</button>
                <button type="submit" name="choice" value="c3"  class="btn btn-primary">GET SWAB COUNT</button>
                <button type="submit" name="choice" value="c5"  class="btn btn-primary">GET AGE GROUP</button>
            </div>
        </div>
    </form>
    @else
        @if(request()->input('choice') == 'c1')
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
                    <div class="card-header"><b>{{date('Y', strtotime('-1 Year'))}} Hospitalization</b></div>
                    <div class="card-body">
                        <p>Number of Confirmed Hospitalized Patients: <b>{{$lastyear_hospitalized}}</b></p>
                        <p>(Recovered: {{$lastyear_hospitalized_recovered}} | Died: {{$lastyear_hospitalized_died}})</p>
                        <ul>
                            <li>Unvaccinated: {{($lastyear_hospitalized - $lastyear_hospitalized_partialvacc - $lastyear_hospitalized_fullvacc - $lastyear_hospitalized_boostered)}}</li>
                            <li>Partially Vaccinated: {{$lastyear_hospitalized_partialvacc}}</li>
                            <li>Fully Vaccinated: {{$lastyear_hospitalized_fullvacc}}</li>
                            <li>Boostered: {{$lastyear_hospitalized_boostered}}</li>
                        </ul>
                    </div>
                </div>
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
        @elseif(request()->input('choice') == 'c2')
        <div class="card">
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
            </div>
        </div>
        @elseif(request()->input('choice') == 'c3')
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
        @elseif(request()->input('choice') == 'c4')
        <div class="card mb-3">
            <div class="card-header font-weight-bold text-center">Accomplishment Report for PREVIOUS YEAR ({{date('Y', strtotime('-1 Year'))}})</div>
            <div class="card-body">
                <form action="{{route('report.accomplishment')}}" method="GET">
                    <div class="input-group mb-3">
                        <select class="custom-select" name="year" id="year" required>
                            <option value="" {{(request()->input('year')) ? '' : 'selected'}}>Select Year...</option>
                            @foreach(range(date('Y'), 2019) as $y)
                            <option value="{{$y}}" {{(request()->input('year') == $y) ? 'selected' : ''}}>{{$y}}</option>
                            @endforeach
                        </select>
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="submit" name="choice" value="c4">Filter</button>
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="font-weight-bold text-center">
                            <tr class="bg-danger text-white">
                                <th colspan="10">{{$year}} BARANGAY DATA</th>
                            </tr>
                            <tr class="thead-light">
                                <th>Barangay</th>
                                <th class="text-danger">Confirmed</th>
                                <th class="text-danger">Confirmed Male</th>
                                <th class="text-danger">Confirmed Female</th>
                                <th>Deaths</th>
                                <th>Deaths Male</th>
                                <th>Deaths Female</th>
                                <th class="text-success">Recoveries</th>
                                <th class="text-success">Recoveries Male</th>
                                <th class="text-success">Recoveries Female</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $totalConfirmed = 0;
                            $totalConfirmed_male = 0;
                            $totalConfirmed_female = 0;
                            $totalDeaths = 0;
                            $totalDeaths_male = 0;
                            $totalDeaths_female = 0;
                            $totalRecoveries = 0;
                            $totalRecoveries_male = 0;
                            $totalRecoveries_female = 0;
                            @endphp
                            @foreach($brgylist as $brgy)
                            <tr>
                                <td class="font-weight-bold">{{$brgy['name']}}</td>
                                <td class="text-danger text-center">{{number_format($brgy['confirmed'])}}</td>
                                <td class="text-danger text-center">{{number_format($brgy['confirmed_male'])}}</td>
                                <td class="text-danger text-center">{{number_format($brgy['confirmed_female'])}}</td>
                                <td class="text-center">{{number_format($brgy['deaths'])}}</td>
                                <td class="text-center">{{number_format($brgy['deaths_male'])}}</td>
                                <td class="text-center">{{number_format($brgy['deaths_female'])}}</td>
                                <td class="text-success text-center">{{number_format($brgy['recoveries'])}}</td>
                                <td class="text-success text-center">{{number_format($brgy['recoveries_male'])}}</td>
                                <td class="text-success text-center">{{number_format($brgy['recoveries_female'])}}</td>
                            </tr>
                            @php
                            $totalConfirmed += $brgy['confirmed'];
                            $totalConfirmed_male += $brgy['confirmed_male'];
                            $totalConfirmed_female += $brgy['confirmed_female'];
                            $totalDeaths += $brgy['deaths'];
                            $totalDeaths_male += $brgy['deaths_male'];
                            $totalDeaths_female += $brgy['deaths_female'];
                            $totalRecoveries += $brgy['recoveries'];
                            $totalRecoveries_male += $brgy['recoveries_male'];
                            $totalRecoveries_female += $brgy['recoveries_female'];
                            @endphp
                            @endforeach
                        </tbody>
                        <tfoot class="bg-light text-center font-weight-bold">
                            <tr>
                                <td>TOTAL</td>
                                <td class="text-danger">{{number_format($totalConfirmed)}}</td>
                                <td class="text-danger">{{number_format($totalConfirmed_male)}}</td>
                                <td class="text-danger">{{number_format($totalConfirmed_female)}}</td>
                                <td>{{number_format($totalDeaths)}}</td>
                                <td>{{number_format($totalDeaths_male)}}</td>
                                <td>{{number_format($totalDeaths_female)}}</td>
                                <td class="text-success">{{number_format($totalRecoveries)}}</td>
                                <td class="text-success">{{number_format($totalRecoveries_male)}}</td>
                                <td class="text-success">{{number_format($totalRecoveries_female)}}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        @elseif(request()->input('choice') == 'c5')
        <div class="card">
            <div class="card-header"><b>Age Group</b></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered text-center table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th>Age</th>
                                <th>Confirmed {{date('Y', strtotime('-2 Year'))}}</th>
                                <th>Confirmed {{date('Y', strtotime('-2 Year'))}} Male</th>
                                <th>Confirmed {{date('Y', strtotime('-2 Year'))}} Female</th>
                                <th>Death {{date('Y', strtotime('-2 Year'))}}</th>
                                <th>Death {{date('Y', strtotime('-2 Year'))}} Male</th>
                                <th>Death {{date('Y', strtotime('-2 Year'))}} Female</th>
                                <th>Recoveries {{date('Y', strtotime('-2 Year'))}}</th>
                                <th>Recoveries {{date('Y', strtotime('-2 Year'))}} Male</th>
                                <th>Recoveries {{date('Y', strtotime('-2 Year'))}} Female</th>
                                <th>Confirmed {{date('Y', strtotime('-1 Year'))}}</th>
                                <th>Confirmed {{date('Y', strtotime('-1 Year'))}} Male</th>
                                <th>Confirmed {{date('Y', strtotime('-1 Year'))}} Female</th>
                                <th>Death {{date('Y', strtotime('-1 Year'))}}</th>
                                <th>Death {{date('Y', strtotime('-1 Year'))}} Male</th>
                                <th>Death {{date('Y', strtotime('-1 Year'))}} Female</th>
                                <th>Recoveries {{date('Y', strtotime('-1 Year'))}}</th>
                                <th>Recoveries {{date('Y', strtotime('-1 Year'))}} Male</th>
                                <th>Recoveries {{date('Y', strtotime('-1 Year'))}} Female</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($age_array as $a)
                            <tr>
                                <td scope="row"><b>{{$a['name']}}</b></td>
                                <td>{{$a['c_l2y']}}</td>
                                <td>{{$a['c_l2y_male']}}</td>
                                <td>{{$a['c_l2y_female']}}</td>
                                <td>{{$a['d_l2y']}}</td>
                                <td>{{$a['d_l2y_male']}}</td>
                                <td>{{$a['d_l2y_female']}}</td>
                                <td>{{$a['r_l2y']}}</td>
                                <td>{{$a['r_l2y_male']}}</td>
                                <td>{{$a['r_l2y_female']}}</td>
                                <td>{{$a['c_l1y']}}</td>
                                <td>{{$a['c_l1y_male']}}</td>
                                <td>{{$a['c_l1y_female']}}</td>
                                <td>{{$a['d_l1y']}}</td>
                                <td>{{$a['d_l1y_male']}}</td>
                                <td>{{$a['d_l1y_female']}}</td>
                                <td>{{$a['r_l1y']}}</td>
                                <td>{{$a['r_l1y_male']}}</td>
                                <td>{{$a['r_l1y_female']}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    @endif
</div>
@endsection