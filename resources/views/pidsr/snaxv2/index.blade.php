@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card mb-3">
        <div class="card-header bg-transparent"><b>sNaX Version 2 - Page 1/3</b></div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-2">
                    <div class="text-center">
                        <img src="{{asset('assets/images/cesu_icon.png')}}" style="width: 8rem;" class="img-responsive">
                    </div>
                </div>
                <div class="col-md-6">
                    <h6>Republic of the Philippines</h6>
                    <h6><b>GENERAL TRIAS CITY HEALTH OFFICE</b></h6>
                    <h6><b>CITY EPIDEMIOLOGY AND SURVEILLANCE UNIT (CESU)</b></h6>
                    <h6>Hospital Rd., Brgy. Pinagtipunan, City of General Trias, Cavite</h6>
                    <h6>Telephone No.: (046) 509-5289 / (046) 437-9195</h6>
                    <h6>Email: <a href="">cesu.gentrias@gmail.com</a></h6>
                </div>
                <div class="col-md-4">
                    <table class="table table-bordered text-center">
                        <tbody>
                            <tr style="background-color: rgba(242,221,218,255)">
                                <td><b>Surveillance Monitoring Dashboard</b></td>
                            </tr>
                            <tr>
                                <td><h3><b>{{$flavor_title}}</b></h3></td>
                            </tr>
                            <tr>
                                <td style="background-color: rgba(151,55,52,255)" class="text-white"><b>MW {{$sel_mweek}} ({{$startDateBasedOnMw}} - {{$endDateBasedOnMw}})</b></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <hr>
            <ul>
                <h6><b>Summary:</b></h6>
                <li>There were {{$current_grand_total}} {{Str::plural('case', $current_grand_total)}} of {{$flavor_title}} reported for Morbidity Week 1-{{$sel_mweek}} ({{$startDate->format('M d, Y')}} - {{$endDate->format('M d, Y')}}), with {{$death_count}} {{Str::plural('death', $death_count)}} (CFR {{($current_grand_total != 0) ? round($death_count / $current_grand_total * 100, 2) : 0}}%)</li>
                @if($current_grand_total < $previous_grand_total)
                <li>This year's number of cases is {{round(100 - ($current_grand_total / $previous_grand_total * 100))}}% lower compared to the same period last year ({{$previous_grand_total}} cases).</li>
                @else
                <li>This year's number of cases is {{(($current_grand_total - $previous_grand_total) / $previous_grand_total) * 100}}% higher compared to the same period  last year ({{$previous_grand_total}} cases).</li>
                @endif
                <li>Of the total cases reported this period, {{$hospitalized_count}} ({{($current_grand_total != 0) ? round($hospitalized_count / $current_grand_total * 100) :0}}%) were hospitalized and {{$current_confirmed_grand_total}} ({{($current_grand_total != 0) ? round($current_confirmed_grand_total / $current_grand_total * 100,0) : 0}}%) were laboratory confirmed.</li>
                <li>The Barangay with mose reported number of cases is {{$top10Brgys[0]['brgy_name']}} ({{$top10Brgys[0]['brgy_grand_total_cases']}} {{Str::plural('case', $top10Brgys[0]['brgy_grand_total_cases'])}} [{{($current_grand_total != 0) ? round($top10Brgys[0]['brgy_grand_total_cases'] / $current_grand_total * 100) : 0}}%])</li>
                <li>Age ranged from {{$min_age}} to {{$max_age}} years (Median {{$median_age}} {{Str::plural('year', $median_age)}}. Majority of the cases were {{strtolower($majority_flavor)}} ({{$majority_percent}}%).</li>
            </ul>
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <h6><b>Distribution of {{$sel_disease}} Cases by Morbidity Week</b></h6>
                    <h6>GENERAL TRIAS, MW{{$sel_mweek}}, {{$sel_year}}</h6>
                    <h6>N = {{$current_grand_total}}</h6>
                    <canvas id="myChart" width="400" height="400"></canvas>
                    <hr>
                    <h6><b>Distribution of Dengue Cases by Barangay for the Previous 3 MWs</b></h6>
                    <h6>GENERAL TRIAS, MW {{$sel_mweek-2}}-{{$sel_mweek}}, {{$sel_year}}</h6>
                    <h6>N={{$threemws_total}}</h6>
                    @php
                    $pob_count =
                    collect($brgy_cases_array)->firstWhere('brgy_name', 'ARNALDO POB. (BGY. 7)')['brgy_last3mw'] +
                    collect($brgy_cases_array)->firstWhere('brgy_name', 'BAGUMBAYAN POB. (BGY. 5)')['brgy_last3mw'] +
                    collect($brgy_cases_array)->firstWhere('brgy_name', 'CORREGIDOR POB. (BGY. 10)')['brgy_last3mw'] +
                    collect($brgy_cases_array)->firstWhere('brgy_name', 'DULONG BAYAN POB. (BGY. 3)')['brgy_last3mw'] +
                    collect($brgy_cases_array)->firstWhere('brgy_name', 'GOV. FERRER POB. (BGY. 1)')['brgy_last3mw'] +
                    collect($brgy_cases_array)->firstWhere('brgy_name', 'NINETY SIXTH POB. (BGY. 8)')['brgy_last3mw'] +
                    collect($brgy_cases_array)->firstWhere('brgy_name', 'PRINZA POB. (BGY. 9)')['brgy_last3mw'] +
                    collect($brgy_cases_array)->firstWhere('brgy_name', 'SAMPALUCAN POB. (BGY. 2)')['brgy_last3mw'] +
                    collect($brgy_cases_array)->firstWhere('brgy_name', 'SAN GABRIEL POB. (BGY. 4)')['brgy_last3mw'] +
                    collect($brgy_cases_array)->firstWhere('brgy_name', 'VIBORA POB. (BGY. 6)')['brgy_last3mw'];
                    @endphp
                    <div class="text-center" style="margin-bottom: 200px; margin-left: -100px;">
                        <img src="{{asset('assets/gentri_maps/BACAO2_'.\App\Http\Controllers\PidsrController::setMapColor(collect($brgy_cases_array)->firstWhere('brgy_name', 'BACAO II')['brgy_last3mw']))}}" alt="">
                        <img src="{{asset('assets/gentri_maps/BACAO1_'.\App\Http\Controllers\PidsrController::setMapColor(collect($brgy_cases_array)->firstWhere('brgy_name', 'BACAO I')['brgy_last3mw']))}}" style="margin-bottom: -70px;margin-left:-138px;" alt="">
                        <img src="{{asset('assets/gentri_maps/TEJERO_'.\App\Http\Controllers\PidsrController::setMapColor(collect($brgy_cases_array)->firstWhere('brgy_name', 'TEJERO')['brgy_last3mw']))}}" style="margin-bottom: -70px;margin-left:-230px;" alt="">
                        <img src="{{asset('assets/gentri_maps/SANJUAN2_'.\App\Http\Controllers\PidsrController::setMapColor(collect($brgy_cases_array)->firstWhere('brgy_name', 'SAN JUAN II')['brgy_last3mw']))}}" style="margin-bottom: -118px;margin-left:-76px;" alt="">
                        <img src="{{asset('assets/gentri_maps/NAVARRO_'.\App\Http\Controllers\PidsrController::setMapColor(collect($brgy_cases_array)->firstWhere('brgy_name', 'NAVARRO')['brgy_last3mw']))}}" style="margin-bottom: -137px;margin-left:30px;" alt="">
                        <img src="{{asset('assets/gentri_maps/POB_'.\App\Http\Controllers\PidsrController::setMapColor($pob_count))}}" style="margin-bottom: -130px;margin-left:-220px;" alt="">
                        <img src="{{asset('assets/gentri_maps/SANJUAN1_'.\App\Http\Controllers\PidsrController::setMapColor(collect($brgy_cases_array)->firstWhere('brgy_name', 'SAN JUAN I')['brgy_last3mw']))}}" style="margin-bottom: -155px;margin-left:-78px;" alt="">
                        <img src="{{asset('assets/gentri_maps/STACLARA_'.\App\Http\Controllers\PidsrController::setMapColor(collect($brgy_cases_array)->firstWhere('brgy_name', 'SANTA CLARA')['brgy_last3mw']))}}" style="margin-bottom: -165px;margin-left:-6px;" alt="">
                        <img src="{{asset('assets/gentri_maps/PINAGTIPUNAN_'.\App\Http\Controllers\PidsrController::setMapColor(collect($brgy_cases_array)->firstWhere('brgy_name', 'PINAGTIPUNAN')['brgy_last3mw']))}}" style="margin-bottom: -205px;margin-left:-95px;" alt="">
                        <img src="{{asset('assets/gentri_maps/PASCAM1_'.\App\Http\Controllers\PidsrController::setMapColor(collect($brgy_cases_array)->firstWhere('brgy_name', 'PASONG CAMACHILE I')['brgy_last3mw']))}}" style="margin-bottom: -210px;margin-left:-30px;" alt="">
                        <img src="{{asset('assets/gentri_maps/TAPIA_'.\App\Http\Controllers\PidsrController::setMapColor(collect($brgy_cases_array)->firstWhere('brgy_name', 'TAPIA')['brgy_last3mw']))}}" style="margin-bottom: -270px;margin-left:-250px;" alt="">
                        <img src="{{asset('assets/gentri_maps/PASCAM2_'.\App\Http\Controllers\PidsrController::setMapColor(collect($brgy_cases_array)->firstWhere('brgy_name', 'PASONG CAMACHILE II')['brgy_last3mw']))}}" style="margin-bottom: -255px;margin-left:-40px;" alt="">
                        <img src="{{asset('assets/gentri_maps/PK1_'.\App\Http\Controllers\PidsrController::setMapColor(collect($brgy_cases_array)->firstWhere('brgy_name', 'PASONG KAWAYAN I')['brgy_last3mw']))}}" style="margin-bottom: -330px;margin-left: -300px;" alt="">
                        <div><img src="{{asset('assets/gentri_maps/SANTIAGO_'.\App\Http\Controllers\PidsrController::setMapColor(collect($brgy_cases_array)->firstWhere('brgy_name', 'SANTIAGO')['brgy_last3mw']))}}" style="margin-bottom: -280px;margin-left: 240px;" alt=""></div>
                        <div><img src="{{asset('assets/gentri_maps/BUENA1_'.\App\Http\Controllers\PidsrController::setMapColor(collect($brgy_cases_array)->firstWhere('brgy_name', 'BUENAVISTA I')['brgy_last3mw']))}}" style="margin-bottom: -290px;margin-left: 200px;" alt=""></div>
                        <div><img src="{{asset('assets/gentri_maps/PK2_'.\App\Http\Controllers\PidsrController::setMapColor(collect($brgy_cases_array)->firstWhere('brgy_name', 'PASONG KAWAYAN II')['brgy_last3mw']))}}" style="margin-bottom: -255px;margin-left: -20px;" alt=""></div>
                        <div><img src="{{asset('assets/gentri_maps/BUENA2_'.\App\Http\Controllers\PidsrController::setMapColor(collect($brgy_cases_array)->firstWhere('brgy_name', 'BUENAVISTA II')['brgy_last3mw']))}}" style="margin-bottom: -280px;margin-left: 120px;" alt=""></div>
                        <div><img src="{{asset('assets/gentri_maps/BUENA3_'.\App\Http\Controllers\PidsrController::setMapColor(collect($brgy_cases_array)->firstWhere('brgy_name', 'BUENAVISTA III')['brgy_last3mw']))}}" style="margin-bottom: -268px;margin-left: 200px;" alt=""></div>
                        <div><img src="{{asset('assets/gentri_maps/SF_'.\App\Http\Controllers\PidsrController::setMapColor(collect($brgy_cases_array)->firstWhere('brgy_name', 'SAN FRANCISCO')['brgy_last3mw']))}}" style="margin-bottom: -183px;margin-left: 367px;" alt=""></div>
                        <div><img src="{{asset('assets/gentri_maps/MANGGAHAN_'.\App\Http\Controllers\PidsrController::setMapColor(collect($brgy_cases_array)->firstWhere('brgy_name', 'MANGGAHAN')['brgy_last3mw']))}}" style="margin-bottom: -220px;margin-left: 250px;" alt=""></div>
                        <div><img src="{{asset('assets/gentri_maps/BICLATAN_'.\App\Http\Controllers\PidsrController::setMapColor(collect($brgy_cases_array)->firstWhere('brgy_name', 'BICLATAN')['brgy_last3mw']))}}" style="margin-bottom: -275px;margin-left: 285px;" alt=""></div>
                        <div><img src="{{asset('assets/gentri_maps/JAVALERA_'.\App\Http\Controllers\PidsrController::setMapColor(collect($brgy_cases_array)->firstWhere('brgy_name', 'JAVALERA')['brgy_last3mw']))}}" style="margin-bottom: -325px;margin-left: 275px;" alt=""></div>
                        <div><img src="{{asset('assets/gentri_maps/ALINGARO_'.\App\Http\Controllers\PidsrController::setMapColor(collect($brgy_cases_array)->firstWhere('brgy_name', 'ALINGARO')['brgy_last3mw']))}}" style="margin-bottom: -375px;margin-left: 200px;" alt=""></div>
                        <div><img src="{{asset('assets/gentri_maps/PANUNGYANAN_'.\App\Http\Controllers\PidsrController::setMapColor(collect($brgy_cases_array)->firstWhere('brgy_name', 'PANUNGYANAN')['brgy_last3mw']))}}" style="margin-bottom: -340px;margin-left: 370px;" alt=""></div>
                    </div>
                    <div style="margin-top: -150px;margin-left: 30px;">
                        <h6><b>LEGEND:</b></h6>
                        <h6><span style="color: rgba(238,236,234,255);">■</span> &nbsp;&nbsp;&nbsp;0 Case</h6>
                        <h6><span style="color: rgba(254,254,153,255);">■</span> &nbsp;&nbsp;&nbsp;1 Case</h6>
                        <h6><span style="color: rgba(254,153,50,255);">■</span> &nbsp;&nbsp;&nbsp;2 Cases</h6>
                        <h6><span style="color: rgba(251,1,0,255);">■</span> &nbsp;&nbsp;&nbsp;3 Cases</h6>
                        <h6><span style="color: rgba(150,0,50,255);">■</span> &nbsp;&nbsp;&nbsp;>= 4 Cases</h6>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-dark" style="background-color: rgba(242,221,218,255)">
                        <div class="card-body">
                            <table class="table table-sm">
                                <thead class="text-center">
                                    <tr>
                                        <th>Cases & Deaths</th>
                                        <th>Year</th>
                                        <th>All Cases</th>
                                        <th>Deaths</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td rowspan="3"><h6><b>Morbidity Week</b></h6>
                                            <h6>From <b>1</b> to <b class="text-danger">{{$sel_mweek}}</b></h6>
                                        </td>
                                        <td class="text-muted text-center">{{$sel_year-1}}</td>
                                        <td class="text-muted text-center">{{$previous_grand_total}}</td>
                                        <td class="text-muted text-center"><span class="text-danger">{{$previous_death_count}}</span></td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"><b>{{$sel_year}}</b></td>
                                        <td class="text-center"><b>{{$current_grand_total}}</b></td>
                                        <td class="text-center"><b class="text-danger">{{$death_count}}</b></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><b>Case Fatality Rate</b></td>
                                        <td><b><u>{{($current_grand_total != 0) ? round($death_count / $current_grand_total * 100, 2) : 0}}%</u></b></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">Age</td>
                                        <td class="text-center">{{$min_age}}-{{$max_age}} yrs</td>
                                        <td class="text-center">(median {{$median_age}})</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">Sex</td>
                                        <td class="text-center">{{Str::plural($majority_flavor, $majority_count)}}: {{$majority_count}}</td>
                                        <td class="text-center">({{($current_grand_total != 0) ? round($majority_count / $current_grand_total * 100) : 0}}%)</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">Hospitalized</td>
                                        <td class="text-center">{{$hospitalized_count}} cases</td>
                                        <td class="text-center">({{($current_grand_total != 0) ? round($hospitalized_count / $current_grand_total * 100) :0}}%)</td>
                                    </tr>
                                </tbody>
                            </table>
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th colspan="3" class="text-center text-white" style="background-color: rgba(151,55,52,255)">Classification of Cases</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($classification_titles as $ind => $cclass)
                                    @php
                                    if($cclass == 'S') {
                                        $cclass_string = 'SUSPECTED';
                                    }
                                    else if($cclass == 'P') {
                                        $cclass_string = 'PROBABLE';
                                    }
                                    else if($cclass == 'C') {
                                        $cclass_string = 'CONFIRMED';
                                    }
                                    else {
                                        $cclass_string = $cclass;
                                    }
                                    @endphp
                                    <tr>
                                        <td>{{$cclass_string}}</td>
                                        <td class="text-center">{{$classification_counts[$ind]}}</td>
                                        <td class="text-center">{{($current_grand_total != 0) ? round($classification_counts[$ind] / $current_grand_total * 100) : 0}}%</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr>
                    <h6><b>Top 10 Barangays with Dengue Cases</b></h6>
                    <h6>GENERAL TRIAS, MW 1-{{$sel_mweek}}, {{$sel_year}}</h6>
                    <h6>N={{$current_grand_total}}</h6>
                    <canvas id="topten" width="" height=""></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header bg-transparent">
            <div class="d-flex justify-content-between">
                <div><b>Page 2/3 - Dengue Monitoring Dashboard</b></div>
                <div><b>MW {{$sel_mweek}} ({{$startDate->format('M d, Y')}} - {{$endDate->format('M d, Y')}})</b></div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered table-sm">
                        <thead class="text-center thead-light">
                            <tr>
                                <th rowspan="2">Barangays</th>
                                <th rowspan="2">
                                    <h6><b>MWs</b></h6>
                                    @if($sel_mweek == 1)
                                    <h6><b>1</b></h6>
                                    @elseif($sel_mweek == 2)
                                    <h6><b>1-2</b></h6>
                                    @else
                                    <h6><b>{{$sel_mweek-2}}-{{$sel_mweek}}</b></h6>
                                    @endif
                                </th>
                                <th colspan="2">MWs 1-52</th>
                            </tr>
                            <tr>
                                <th>2023</th>
                                <th class="text-muted">2022</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $last3mw_total = 0;
                            $currentyear_total = 0;
                            $lastyear_total = 0;
                            @endphp
                            @foreach($brgy_sortedtohighestweek_array as $b)
                            <tr>
                                <td>{{$b['brgy_name']}}</td>
                                <td class="text-center" style="{{\App\Http\Controllers\PidsrController::setBgColor($b['brgy_last3mw'])}}">{{$b['brgy_last3mw']}}</td>
                                <td class="text-center"><b>{{$b['brgy_grand_total_cases']}}</b></td>
                                <td class="text-center text-muted">{{$b['brgy_previousyear_total_cases']}}</td>
                            </tr>
                            @php
                            $last3mw_total += $b['brgy_last3mw'];
                            $currentyear_total += $b['brgy_grand_total_cases'];
                            $lastyear_total += $b['brgy_previousyear_total_cases'];
                            @endphp
                            @endforeach
                            <tr class="text-center">
                                <td><b>TOTAL</b></td>
                                <td><b>{{$last3mw_total}}</b></td>
                                <td><b>{{$currentyear_total}}</b></td>
                                <td class="text-muted"><b>{{$lastyear_total}}</b></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6><b>Proportion of Cases by Case Classification</b></h6>
                    <h6>GENERAL TRIAS, MW 1-{{$sel_mweek}}, {{$sel_year}}</h6>
                    <h6>N={{$current_grand_total}}</h6>
                    <div class="chart-container" style="position: relative; height:60vh; width:80vw">
                    <canvas id="pieChart"></canvas>
                    </div>
                    <hr>
                    <h6><b>Proportion of Cases by Sex and Age Group</b></h6>
                    <h6>GENERAL TRIAS, MW 1-{{$sel_mweek}}, {{$sel_year}}</h6>
                    <h6>N={{$current_grand_total}}</h6>
                    <canvas id="ageGroup" width="50" height=""></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header bg-transparent">
            <div class="d-flex justify-content-between">
                <div><b>Page 3/3 - Dengue Monitoring Dashboard</b></div>
                <div><b>MW {{$sel_mweek}} ({{$startDate->format('M d, Y')}} - {{$endDate->format('M d, Y')}})</b></div>
            </div>
        </div>
        <div class="card-body">
            <h6><b>Distribution of Dengue Cases by Barangay for the Previous 4 MWs</b></h6>
            <h6>GENERAL TRIAS, MW {{$sel_mweek-3}}-{{$sel_mweek}}, {{$sel_year}}</h6>
            <h6>N={{$fourmws_total}}</h6>
            <table class="table table-bordered table-sm">
                <thead class="text-center">
                    <tr>
                        <th></th>
                        @if($sel_mweek == 1)
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>
                            <h6><small>MW</small></h6>
                            <h6><b>1</b></h6>
                        </th>
                        @elseif($sel_mweek == 2)
                        <th></th>
                        <th></th>
                        <th>
                            <h6><small>MW</small></h6>
                            <h6><b>2</b></h6>
                        </th>
                        <th>
                            <h6><small>MW</small></h6>
                            <h6><b>1</b></h6>
                        </th>
                        @elseif($sel_mweek == 3)
                        <th></th>
                        <th>
                            <h6><small>MW</small></h6>
                            <h6><b>3</b></h6>
                        </th>
                        <th>
                            <h6><small>MW</small></h6>
                            <h6><b>2</b></h6>
                        </th>
                        <th>
                            <h6><small>MW</small></h6>
                            <h6><b>1</b></h6>
                        </th>
                        @else
                        <th>
                            <h6><small>MW</small></h6>
                            <h6><b>{{$sel_mweek-3}}</b></h6>
                        </th>
                        <th>
                            <h6><small>MW</small></h6>
                            <h6><b>{{$sel_mweek-2}}</b></h6>
                        </th>
                        <th>
                            <h6><small>MW</small></h6>
                            <h6><b>{{$sel_mweek-1}}</b></h6>
                        </th>
                        <th>
                            <h6><small>MW</small></h6>
                            <h6><b>{{$sel_mweek}}</b></h6>
                        </th>
                        @endif
                        
                        <th>{{$sel_year}}</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $mw1_total = 0;
                    $mw2_total = 0;
                    $mw3_total = 0;
                    $mw4_total = 0;
                    $currentyear_total = 0;
                    @endphp
                    @foreach($brgy_cases_array as $b)
                    @php

                    @endphp
                    <tr>
                        <td>{{$b['brgy_name']}}</td>
                        <td class="text-center" style="{{\App\Http\Controllers\PidsrController::setBgColor($b['brgy_mw1'])}}">{{$b['brgy_mw1']}}</td>
                        <td class="text-center" style="{{\App\Http\Controllers\PidsrController::setBgColor($b['brgy_mw2'])}}">{{$b['brgy_mw2']}}</td>
                        <td class="text-center" style="{{\App\Http\Controllers\PidsrController::setBgColor($b['brgy_mw3'])}}">{{$b['brgy_mw3']}}</td>
                        <td class="text-center" style="{{\App\Http\Controllers\PidsrController::setBgColor($b['brgy_mw4'])}}">{{$b['brgy_mw4']}}</td>
                        <td class="text-center" style="{{\App\Http\Controllers\PidsrController::setBgColor($b['brgy_grand_total_cases'])}}">{{$b['brgy_grand_total_cases']}}</td>
                    </tr>
                    @php
                    $mw1_total += $b['brgy_mw1'];
                    $mw2_total += $b['brgy_mw2'];
                    $mw3_total += $b['brgy_mw3'];
                    $mw4_total += $b['brgy_mw4'];
                    $currentyear_total += $b['brgy_grand_total_cases'];
                    @endphp
                    @endforeach
                    <tr>
                        <td><b>TOTAL</b></td>
                        <td class="text-center" style="{{\App\Http\Controllers\PidsrController::setBgColor($mw1_total)}}"><b>{{$mw1_total}}</b></td>
                        <td class="text-center" style="{{\App\Http\Controllers\PidsrController::setBgColor($mw2_total)}}"><b>{{$mw2_total}}</b></td>
                        <td class="text-center" style="{{\App\Http\Controllers\PidsrController::setBgColor($mw3_total)}}"><b>{{$mw3_total}}</b></td>
                        <td class="text-center" style="{{\App\Http\Controllers\PidsrController::setBgColor($mw4_total)}}"><b>{{$mw4_total}}</b></td>
                        <td class="text-center" style="{{\App\Http\Controllers\PidsrController::setBgColor($currentyear_total)}}"><b>{{$currentyear_total}}</b></td>
                    </tr>
                </tbody>
            </table>
            <div class="row">
                <div class="col-md-6">
                    <div class="card border-dark">
                        <div class="card-body" style="background-color: rgba(238,237,224,255);">
                            <h6><b>DISCLAIMER:</b> This automated report was made possible using <b class="text-danger">sNaX V2</b> (Simplified Nested Analytics thru Excel). Every effort has been made to provide accurate and updated information, however, errors can still occur. By using the information contained in this report, the reader assumes all risks in connection with such use. The General Trias City Epidemiology and Surveillance Unit (CESU) shall not be held responsible for errors, nor liable for damage(s) resulting from use or reliance upon this material.</h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header text-center"><b>REPORT FOR: MW {{$sel_mweek}} ({{$startDate->format('M d, Y')}} - {{$endDate->format('M d, Y')}})</b></div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <td>Date Published:</td>
                                        <td class="text-center"><u>{{date('F d, Y')}}</u></td>
                                    </tr>
                                    <tr>
                                        <td>Prepared by:</td>
                                        <td>
                                            <img src="{{asset('assets/images/ANALYN_PIRMA.png')}}" class="img-fluid" style="margin-bottom:-60px;width: 20rem;margin-left:-100px;" id="signature1">
                                            <h6><b><u>ANALYN C. BARZAGA</u></b></h6>
                                            <h6><small>PIDSR Encoder</small></h6>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Noted by:</td>
                                        <td>
                                            <img src="{{asset('assets/images/SIR_LOU_PIRMA.png')}}" class="img-fluid" style="margin-bottom:-80px;width: 20rem;margin-left:-30px;" id="signature2">
                                            <h6><b><u>LUIS P. BROAS, RN, RPh, MAN, CAE</u></b></h6>
                                            <h6><small>Nurse II-CESU Designated Head</small></h6>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Approved by:</td>
                                        <td>
                                            <img src="{{asset('assets/images/signatureonly_docathan.png')}}" class="img-fluid" style="margin-bottom:-30px;width:10rem;" id="signature3">
                                            <h6><b><u>JONATHAN P. LUSECO, MD</u></b></h6>
                                            <h6><small>City Health Officer II</small></h6>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-check">
        <label class="form-check-label">
          <input type="checkbox" class="form-check-input" name="checker1" id="checker1" value="checkedValue">
          Signature 1
        </label>
    </div>
    <div class="form-check">
        <label class="form-check-label">
          <input type="checkbox" class="form-check-input" name="checker2" id="checker2" value="checkedValue">
          Signature 2
        </label>
    </div>
    <div class="form-check">
        <label class="form-check-label">
          <input type="checkbox" class="form-check-input" name="checker3" id="checker3" value="checkedValue">
          Signature 3
        </label>
    </div>
</div>

@php
//FOR PIE GRAPH
$finalpie_titles = [];

foreach($classification_titles as $ind => $ctitle) {
    if($ctitle == 'S') {
        $ctitle_str = 'SUSPECTED';
    }
    else if($ctitle == 'P') {
        $ctitle_str = 'PROBABLE';
    }
    else if($ctitle == 'C') {
        $ctitle_str = 'CONFIRMED';
    }
    else {
        $ctitle_str = $ctitle;
    }

    $cgetpercentage = ($current_grand_total != 0) ? round($classification_counts[$ind] / $current_grand_total * 100) : 0;
    $finalpie_titles[] = $ctitle_str.', '.$cgetpercentage.'% ('.$classification_counts[$ind].')';
}
@endphp

<script>
    $('#checker1').change(function (e) { 
        e.preventDefault();
        if($(this).is(':checked')) {
            $('#signature1').show();
        }
        else {
            $('#signature1').hide();
        }
    }).trigger('change');

    $('#checker2').change(function (e) { 
        e.preventDefault();
        if($(this).is(':checked')) {
            $('#signature2').show();
        }
        else {
            $('#signature2').hide();
        }
    }).trigger('change');

    $('#checker3').change(function (e) { 
        e.preventDefault();
        if($(this).is(':checked')) {
            $('#signature3').show();
        }
        else {
            $('#signature3').hide();
        }
    }).trigger('change');

    var ctx = document.getElementById('myChart').getContext('2d');

    var barData = {!! json_encode($currentmw_array) !!};
    var lineData = {!! json_encode($epidemicmw_array) !!};
    var dottedLineData = {!! json_encode($alertmw_array) !!};

    var chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['MW1', 'MW2', 'MW3', 'MW4', 'MW5', 'MW6', 'MW7', 'MW8', 'MW9', 'MW10', 'MW11', 'MW12', 'MW13', 'MW14', 'MW15', 'MW16', 'MW17', 'MW18', 'MW19', 'MW20', 'MW21', 'MW22', 'MW23', 'MW24', 'MW25', 'MW26', 'MW27', 'MW28', 'MW29', 'MW30', 'MW31', 'MW32', 'MW33', 'MW34', 'MW35', 'MW36', 'MW37', 'MW38', 'MW39', 'MW40', 'MW41', 'MW42', 'MW43', 'MW44', 'MW45', 'MW46', 'MW47', 'MW48', 'MW49', 'MW50', 'MW51', 'MW52'], // Replace with your actual labels
            datasets: [{
                label: 'Current Year - {{$sel_year}}',
                data: barData,
                backgroundColor: 'rgba(255, 236, 0, 1)', // Customize bar color
                borderColor: 'rgba(0, 0, 0, 1)', // Customize border color
                borderWidth: 1
            },
            {
                label: 'Epidemic Threshold',
                data: lineData,
                fill: false,
                borderColor: 'rgba(255, 0, 0, 1)', // Customize line color
                borderWidth: 2,
                type: 'line',
                lineTension: 0 // Remove line tension for straight lines
            },
            {
                label: 'Alert Threshold',
                data: dottedLineData,
                fill: false,
                borderColor: 'rgba(54, 162, 235, 1)', // Customize dotted line color
                borderWidth: 1,
                type: 'line',
                borderDash: [5, 5] // Make the line dotted
            }]
        },
        options: {
            plugins: {
                datalabels: {
                display: false
                }
            },
        }
    });

    var pieTitles = {!! json_encode($finalpie_titles) !!};
    var pieDatas = {!! json_encode($classification_counts) !!};

    var ctx = document.getElementById('pieChart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'pie',

        data: {
            labels: pieTitles,
            datasets: [{
                label: "My Chart",
                data: pieDatas,
                //backgroundColor: ['rgba(148,55,52,255)', 'rgba(119,146,61,255)', 'rgba(166,167,167,255)']
            }]
        },

        options: {
            title: {
                text: "My Chart",
                display: true,
            },
            events: [],
            tooltips: {
                mode: ''
            },
            layout: {},
            plugins: {
                legend: {
                    display: true,
                    position: 'left',
                },
                datalabels: {
                display: false
                }
            },
            animation: {}
        }
    });

    const labels = [];
    const data = [];

    @foreach($top10Brgys as $barangay)
        labels.push("{{ $barangay['brgy_name'] }}");
        data.push({{ $barangay['brgy_grand_total_cases'] }});
    @endforeach

    var ctx = document.getElementById('topten').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'bar',

        data: {
            labels: labels,
            datasets: [{
                label: "Number of Cases",
                data: data,
                backgroundColor: 'rgba(254,192,1,255)', // Customize bar color
                borderColor: 'rgba(0, 0, 0, 1)', // Customize border color
                borderWidth: 1,
            }]
        },

        options: {
            indexAxis: 'y',
            title: {
                text: "Barangay",
                display: true,
            },
            events: [],
            tooltips: {
                mode: ''
            },
            plugins: {
                legend: {
                    display: false,
                },
                datalabels: {
                    anchor: 'end',
                    align: 'end',
                }
            },
            layout: {},
            animation: {}
        }
    });

    var male_set = {{json_encode($ag_male)}};
    var female_set = {{json_encode($ag_female)}};

    var ctx = document.getElementById('ageGroup').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'bar',

        data: {
            labels: ['50+', '41-50', '31-40', '21-30', '11-20', '1-10', '<1'],
            datasets: [
                {
                    label: "Male",
                    data: male_set,
                    backgroundColor: 'rgba(143,181,227,255)', // Customize bar color
                    borderColor: 'rgba(0, 0, 0, 1)', // Customize border color
                    borderWidth: 1,
                },
                {
                    label: "Female",
                    data: female_set,
                    backgroundColor: 'rgba(230,184,185,255)', // Customize bar color
                    borderColor: 'rgba(0, 0, 0, 1)', // Customize border color
                    borderWidth: 1,
                },
            ]
        },

        options: {
            indexAxis: 'y',
            title: {
                text: "Barangay",
                display: true,
            },
            events: [],
            tooltips: {
                mode: ''
            },
            layout: {},
            scales: {
                x: {
                    stacked: true,
                },
                y: {
                    stacked: true,
                },
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                },
                datalabels: {
                display: false
                }
            },
            animation: {}
        }
    });
</script>
@endsection