<div class="container">
    @php
        $queryString = request()->getQueryString();
    @endphp
    @if(request()->input('print'))
    <div class="alert alert-info text-center" role="alert">
        <b class="text-danger">Note:</b> Use <a href="https://gofullpage.com/"><b>GoFullPage</b></a> Browser Extension to save the page as JPEG and paste every page on Microsoft Word for proper printing.
    </div>
    @endif
    <div class="card mb-3">
        <div class="card-header bg-transparent">
            <div class="d-flex justify-content-between">
                <div><b>sNaX Version 2 - Page 1/3</b></div>
                <div>
                    @if(!(request()->input('print')))
                    <a href="{{route('pidsr_snaxv2')}}?{{$queryString}}&print=1" class="btn btn-success">Print</a>
                    @endif
                </div>
            </div>
            
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-2 text-center">
                    <img src="{{asset('assets/images/cesu_icon.png')}}" style="width: 10rem;" class="img-responsive">
                </div>
                <div class="col-8 text-center">
                    <h5>Republic of the Philippines</h5>
                    <h5><b>GENERAL TRIAS CITY HEALTH OFFICE</b></h5>
                    <h5><b>CITY EPIDEMIOLOGY AND SURVEILLANCE UNIT (CESU)</b></h5>
                    <h5>Hospital Rd., Brgy. Pinagtipunan, City of General Trias, Cavite</h5>
                    <h5>Telephone No.: (046) 509-5289 / (046) 437-9195</h5>
                    <h5>Email: <a href="">cesu.gentrias@gmail.com</a></h5>
                </div>
                <div class="col-2 text-center">
                    <img src="{{asset('assets/images/cho_icon_large.png')}}" style="width: 10rem;" class="img-responsive">
                </div>
            </div>
            <div class="row my-3" style="background-color: rgba(255,242,204,255)">
                <div class="col-2"></div>
                <div class="col-8 d-flex justify-content-center align-items-center text-center"><h4><b>{{mb_strtoupper($flavor_title)}} SURVEILLANCE REPORT</b></h4></div>
                <div class="col-2 text-center">
                    <h6>{{date('F d, Y')}}</h6>
                    <h6>Morbidity Week 1-{{$sel_mweek}}</h6>
                </div>
            </div>
            <ul>
                <h5 style="margin-bottom: -2px;"><b><u>Summary:</u></b></h5>
                <li style="margin-bottom: -5px;"><h5>There were {{$current_grand_total}} {{Str::plural('case', $current_grand_total)}} of {{$flavor_name}} reported for Morbidity Week 1-{{$sel_mweek}} (Jan 01 - {{$flavor_enddate->format('M d, Y')}}), with {{$death_count}} {{Str::plural('death', $death_count)}} (CFR {{($current_grand_total != 0) ? round($death_count / $current_grand_total * 100, 2) : 0}}%)</h5></li>
                @if($death_count != 0 && $sel_disease == 'Pert')
                <li style="margin-bottom: -5px;"><h5>Profile of death/s:</h5>
                    <ul>
                        <li style="margin-bottom: -5px; margin-top: -5px;"><h5>{{$death_count}} deaths, with the {{Str::plural('age', $death_count)}} of: @foreach($died_age_list as $da){{($loop->last) ? ' and ' : ''}}{{$da}}{{($loop->last) ? '.' : ', '}}@endforeach</h5></li>
                        <li style="margin-bottom: -5px;">
                            <h5>
                                They were from
                                @foreach($died_brgy_list as $db)
                                {{($loop->last) ? ' and ' : ''}}Brgy. {{$db}}{{($loop->last) ? '.' : ','}}
                                @endforeach
                            </h5>
                        </li>
                        <li style="margin-bottom: -5px;">
                            <h5>
                                {{($died_unvaccinated == $death_count) ? 'All ' : ''}}{{$died_unvaccinated}} {{Str::plural('death', $died_unvaccinated)}} were unvaccinated. {{(($died_penta1 + $died_penta2 + $died_penta3) != 0) ? 'And' : ''}}
                                @if($died_penta1 != 0)
                                {{$died_penta1}} {{Str::plural('death', $died_penta1)}} were vaccinated with Penta1.
                                @endif
                                @if($died_penta2 != 0)
                                {{$died_penta2}} {{Str::plural('death', $died_penta2)}} were vaccinated with Penta2.
                                @endif
                                @if($died_penta3 != 0)
                                {{$died_penta3}} {{Str::plural('death', $died_penta3)}} were vaccinated with Penta3.
                        @endif
                            </h5>
                        </li>
                        <li style="margin-bottom: -5px;"><h5>Lab Results were: {{($died_confirmed != 0) ? $died_confirmed.' Confirmed. ' : ''}}{{($died_negative != 0) ? $died_negative.' Negative. ' : ''}}{{($died_waitresult != 0) ? $died_waitresult.' Waiting for Result. ' : ''}}</h5></li>
                    </ul>
                </li>
                @endif
                @if($current_grand_total < $previous_grand_total)
                <li style="margin-bottom: -5px;"><h5>This year's cases are {{round(100 - ($current_grand_total / $previous_grand_total * 100))}}% lower than last year ({{$previous_grand_total}} cases).</h5></li>
                @else
                <li style="margin-bottom: -5px;"><h5>This year's cases are {{($previous_grand_total != 0) ? round((($current_grand_total - $previous_grand_total) / $previous_grand_total) * 100) : 0}}% higher than last year ({{$previous_grand_total}} cases).</h5></li>
                @endif
                <li style="margin-bottom: -5px;"><h5>Of the total cases reported this period, {{$hospitalized_count}} ({{($current_grand_total != 0) ? round($hospitalized_count / $current_grand_total * 100) :0}}%) were hospitalized and {{$current_confirmed_grand_total}} ({{($current_grand_total != 0) ? round($current_confirmed_grand_total / $current_grand_total * 100,0) : 0}}%) were laboratory confirmed.</h5></li>
                @if($current_grand_total > 1)
                <li style="margin-bottom: -5px;"><h5>The Barangay with the most reported number of cases is {{$top10Brgys[0]['brgy_name']}} ({{$top10Brgys[0]['brgy_grand_total_cases']}} {{Str::plural('case', $top10Brgys[0]['brgy_grand_total_cases'])}} [{{($current_grand_total != 0) ? round($top10Brgys[0]['brgy_grand_total_cases'] / $current_grand_total * 100) : 0}}%])</h5></li>
                <li style="margin-bottom: -5px;"><h5>Age ranged from {{$min_age_display}} to {{$max_age}} years (Median={{$median_display}}). The Majority of the cases were {{strtolower($majority_flavor)}} ({{$majority_percent}}%).</h5></li>
                @else
                <li style="margin-bottom: -5px;"><h5>The individual case is from BRGY. {{$top10Brgys[0]['brgy_name']}}</h5></li>
                <li style="margin-bottom: -5px;"><h5>The invididual case is {{$max_age}} {{Str::plural('year', $max_age)}} old {{$majority_flavor_singular}}.</h5></li>
                @endif
            </ul>
            <hr>
            @php
            if($set_display_params == 'yearly') {
                $map_count = 'brgy_grand_total_cases';
            }
            else {
                $map_count = 'brgy_last3mw';
            }
            @endphp
            <div class="row">
                <div class="col-md-6">
                    <h5><b>Distribution of {{$flavor_name}} Cases by Morbidity Week</b></h5>
                    <h6>GENERAL TRIAS, MW{{$sel_mweek}}, {{$sel_year}} (N={{$current_grand_total}})</h6>
                    <div>
                    <canvas id="myChart" style="height: 600px"></canvas>
                    </div>
                    <hr>
                    <h5><b>Spot Map of {{$flavor_name}} Cases</b></h5>
                    @if($set_display_params == 'yearly')
                    <h6>GENERAL TRIAS, MW 1-{{$sel_mweek}}, {{$sel_year}} (N={{$current_grand_total}})</h6>
                    @else
                    <h6>GENERAL TRIAS, MW {{$sel_mweek-2}}-{{$sel_mweek}}, {{$sel_year}} (N={{$threemws_total}})</h6>
                    @endif
                    @php
                    $pob_count =
                    collect($brgy_cases_array)->firstWhere('brgy_name', 'ARNALDO POB. (BGY. 7)')[$map_count] +
                    collect($brgy_cases_array)->firstWhere('brgy_name', 'BAGUMBAYAN POB. (BGY. 5)')[$map_count] +
                    collect($brgy_cases_array)->firstWhere('brgy_name', 'CORREGIDOR POB. (BGY. 10)')[$map_count] +
                    collect($brgy_cases_array)->firstWhere('brgy_name', 'DULONG BAYAN POB. (BGY. 3)')[$map_count] +
                    collect($brgy_cases_array)->firstWhere('brgy_name', 'GOV. FERRER POB. (BGY. 1)')[$map_count] +
                    collect($brgy_cases_array)->firstWhere('brgy_name', 'NINETY SIXTH POB. (BGY. 8)')[$map_count] +
                    collect($brgy_cases_array)->firstWhere('brgy_name', 'PRINZA POB. (BGY. 9)')[$map_count] +
                    collect($brgy_cases_array)->firstWhere('brgy_name', 'SAMPALUCAN POB. (BGY. 2)')[$map_count] +
                    collect($brgy_cases_array)->firstWhere('brgy_name', 'SAN GABRIEL POB. (BGY. 4)')[$map_count] +
                    collect($brgy_cases_array)->firstWhere('brgy_name', 'VIBORA POB. (BGY. 6)')[$map_count];
                    @endphp
                    <div class="text-center" style="margin-bottom: 200px; margin-left: -100px;">
                        <img src="{{asset('assets/gentri_maps/BACAOII_'.\App\Http\Controllers\PidsrController::setMapColor(collect($brgy_cases_array)->firstWhere('brgy_name', 'BACAO II')[$map_count]))}}" alt="">
                        <img src="{{asset('assets/gentri_maps/BACAO1_'.\App\Http\Controllers\PidsrController::setMapColor(collect($brgy_cases_array)->firstWhere('brgy_name', 'BACAO I')[$map_count]))}}" style="margin-bottom: -70px;margin-left:-138px;" alt="">
                        <img src="{{asset('assets/gentri_maps/TEJERO_'.\App\Http\Controllers\PidsrController::setMapColor(collect($brgy_cases_array)->firstWhere('brgy_name', 'TEJERO')[$map_count]))}}" style="margin-bottom: -70px;margin-left:-230px;" alt="">
                        <img src="{{asset('assets/gentri_maps/SANJUAN2_'.\App\Http\Controllers\PidsrController::setMapColor(collect($brgy_cases_array)->firstWhere('brgy_name', 'SAN JUAN II')[$map_count]))}}" style="margin-bottom: -118px;margin-left:-76px;" alt="">
                        <img src="{{asset('assets/gentri_maps/NAVARRO_'.\App\Http\Controllers\PidsrController::setMapColor(collect($brgy_cases_array)->firstWhere('brgy_name', 'NAVARRO')[$map_count]))}}" style="margin-bottom: -137px;margin-left:30px;" alt="">
                        <img src="{{asset('assets/gentri_maps/POB_'.\App\Http\Controllers\PidsrController::setMapColor($pob_count))}}" style="margin-bottom: -130px;margin-left:-220px;" alt="">
                        <img src="{{asset('assets/gentri_maps/SANJUAN1_'.\App\Http\Controllers\PidsrController::setMapColor(collect($brgy_cases_array)->firstWhere('brgy_name', 'SAN JUAN I')[$map_count]))}}" style="margin-bottom: -155px;margin-left:-78px;" alt="">
                        <img src="{{asset('assets/gentri_maps/STACLARA_'.\App\Http\Controllers\PidsrController::setMapColor(collect($brgy_cases_array)->firstWhere('brgy_name', 'SANTA CLARA')[$map_count]))}}" style="margin-bottom: -165px;margin-left:-6px;" alt="">
                        <img src="{{asset('assets/gentri_maps/PINAGTIPUNAN_'.\App\Http\Controllers\PidsrController::setMapColor(collect($brgy_cases_array)->firstWhere('brgy_name', 'PINAGTIPUNAN')[$map_count]))}}" style="margin-bottom: -205px;margin-left:-95px;" alt="">
                        <img src="{{asset('assets/gentri_maps/PASCAM1_'.\App\Http\Controllers\PidsrController::setMapColor(collect($brgy_cases_array)->firstWhere('brgy_name', 'PASONG CAMACHILE I')[$map_count]))}}" style="margin-bottom: -210px;margin-left:-30px;" alt="">
                        <img src="{{asset('assets/gentri_maps/TAPIA_'.\App\Http\Controllers\PidsrController::setMapColor(collect($brgy_cases_array)->firstWhere('brgy_name', 'TAPIA')[$map_count]))}}" style="margin-bottom: -270px;margin-left:-250px;" alt="">
                        <img src="{{asset('assets/gentri_maps/PASCAM2_'.\App\Http\Controllers\PidsrController::setMapColor(collect($brgy_cases_array)->firstWhere('brgy_name', 'PASONG CAMACHILE II')[$map_count]))}}" style="margin-bottom: -255px;margin-left:-40px;" alt="">
                        <img src="{{asset('assets/gentri_maps/PK1_'.\App\Http\Controllers\PidsrController::setMapColor(collect($brgy_cases_array)->firstWhere('brgy_name', 'PASONG KAWAYAN I')[$map_count]))}}" style="margin-bottom: -330px;margin-left: -300px;" alt="">
                        <div><img src="{{asset('assets/gentri_maps/SANTIAGO_'.\App\Http\Controllers\PidsrController::setMapColor(collect($brgy_cases_array)->firstWhere('brgy_name', 'SANTIAGO')[$map_count]))}}" style="margin-bottom: -280px;margin-left: 240px;" alt=""></div>
                        <div><img src="{{asset('assets/gentri_maps/BUENA1_'.\App\Http\Controllers\PidsrController::setMapColor(collect($brgy_cases_array)->firstWhere('brgy_name', 'BUENAVISTA I')[$map_count]))}}" style="margin-bottom: -290px;margin-left: 200px;" alt=""></div>
                        <div><img src="{{asset('assets/gentri_maps/PK2_'.\App\Http\Controllers\PidsrController::setMapColor(collect($brgy_cases_array)->firstWhere('brgy_name', 'PASONG KAWAYAN II')[$map_count]))}}" style="margin-bottom: -255px;margin-left: -20px;" alt=""></div>
                        <div><img src="{{asset('assets/gentri_maps/BUENA2_'.\App\Http\Controllers\PidsrController::setMapColor(collect($brgy_cases_array)->firstWhere('brgy_name', 'BUENAVISTA II')[$map_count]))}}" style="margin-bottom: -280px;margin-left: 120px;" alt=""></div>
                        <div><img src="{{asset('assets/gentri_maps/BUENA3_'.\App\Http\Controllers\PidsrController::setMapColor(collect($brgy_cases_array)->firstWhere('brgy_name', 'BUENAVISTA III')[$map_count]))}}" style="margin-bottom: -268px;margin-left: 200px;" alt=""></div>
                        <div><img src="{{asset('assets/gentri_maps/SF_'.\App\Http\Controllers\PidsrController::setMapColor(collect($brgy_cases_array)->firstWhere('brgy_name', 'SAN FRANCISCO')[$map_count]))}}" style="margin-bottom: -183px;margin-left: 367px;" alt=""></div>
                        <div><img src="{{asset('assets/gentri_maps/MANGGAHAN_'.\App\Http\Controllers\PidsrController::setMapColor(collect($brgy_cases_array)->firstWhere('brgy_name', 'MANGGAHAN')[$map_count]))}}" style="margin-bottom: -220px;margin-left: 250px;" alt=""></div>
                        <div><img src="{{asset('assets/gentri_maps/BICLATAN_'.\App\Http\Controllers\PidsrController::setMapColor(collect($brgy_cases_array)->firstWhere('brgy_name', 'BICLATAN')[$map_count]))}}" style="margin-bottom: -275px;margin-left: 285px;" alt=""></div>
                        <div><img src="{{asset('assets/gentri_maps/JAVALERA_'.\App\Http\Controllers\PidsrController::setMapColor(collect($brgy_cases_array)->firstWhere('brgy_name', 'JAVALERA')[$map_count]))}}" style="margin-bottom: -325px;margin-left: 275px;" alt=""></div>
                        <div><img src="{{asset('assets/gentri_maps/ALINGARO_'.\App\Http\Controllers\PidsrController::setMapColor(collect($brgy_cases_array)->firstWhere('brgy_name', 'ALINGARO')[$map_count]))}}" style="margin-bottom: -375px;margin-left: 200px;" alt=""></div>
                        <div><img src="{{asset('assets/gentri_maps/PANUNGYANAN_'.\App\Http\Controllers\PidsrController::setMapColor(collect($brgy_cases_array)->firstWhere('brgy_name', 'PANUNGYANAN')[$map_count]))}}" style="margin-bottom: -340px;margin-left: 370px;" alt=""></div>
                    </div>
                    <div style="margin-top: -150px;margin-left: 30px;">
                        <h6><b>LEGEND:</b></h6>
                        <h6><span style="color: rgba(238,236,234,255);">■</span> &nbsp;&nbsp;&nbsp;0 Case</h6>
                        <h6><span style="color: rgba(254,254,153,255);">■</span> &nbsp;&nbsp;&nbsp;1 Case</h6>
                        <h6><span style="color: rgba(254,153,50,255);">■</span> &nbsp;&nbsp;&nbsp;2 Cases</h6>
                        <h6><span style="color: rgba(251,1,0,255);">■</span> &nbsp;&nbsp;&nbsp;3 Cases</h6>
                        <h6><span style="color: rgba(150,0,50,255);">■</span> &nbsp;&nbsp;&nbsp;>= 4 Cases</h6>
                    </div>
                    @if($sel_disease == 'Pert')
                    <h5 class="mt-3"><b>{{$flavor_name}} Cases by Classification, Outcome, and Lab Results</b></h5>
                    <h6>GENERAL TRIAS, Jan 01 - {{$flavor_enddate->format('M d, Y')}} (N={{$current_grand_total}})</h6>
                    <table class="table table-bordered table-sm" style="border: 1px solid #000;">
                        <thead class="thead-light text-center">
                            <tr>
                                <th rowspan="2" style="vertical-align: middle;border: 1px solid #000;">Outcome</th>
                                <th colspan="2" style="vertical-align: middle;border: 1px solid #000;">Classification</th>
                                <th rowspan="2" style="vertical-align: middle;border: 1px solid #000;">Total</th>
                                <th colspan="5" style="vertical-align: middle;border: 1px solid #000;">Lab Results</th>
                                <th rowspan="2" style="vertical-align: middle;border: 1px solid #000;">Total</th>
                            </tr>
                            <tr>
                                <th style="vertical-align: middle;border: 1px solid #000;">Suspected</th>
                                <th style="vertical-align: middle;border: 1px solid #000;">Confirmed</th>
                                <th style="vertical-align: middle;border: 1px solid #000;">Positive</th>
                                <th style="vertical-align: middle;border: 1px solid #000;">Negative</th>
                                <th style="vertical-align: middle;border: 1px solid #000;">Waiting for Result</th>
                                <th style="vertical-align: middle;border: 1px solid #000;">No Swab</th>
                                <th style="vertical-align: middle;border: 1px solid #000;">Unknown</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="border: 1px solid #000;"><b>Alive</b></td>
                                <td class="text-center" style="border: 1px solid #000;">{{$alive_suspect}}</td>
                                <td class="text-center" style="border: 1px solid #000;">{{$alive_confirmed}}</td>
                                <td class="text-center" style="border: 1px solid #000;"><b>{{$alive_suspect + $alive_confirmed}}</b></td>
                                <td class="text-center" style="border: 1px solid #000;">{{$alive_positive}}</td>
                                <td class="text-center" style="border: 1px solid #000;">{{$alive_negative}}</td>
                                <td class="text-center" style="border: 1px solid #000;">{{$alive_waitresult}}</td>
                                <td class="text-center" style="border: 1px solid #000;">{{$alive_noswab}}</td>
                                <td class="text-center" style="border: 1px solid #000;">{{$alive_unknown}}</td>
                                <td class="text-center" style="border: 1px solid #000;"><b>{{$alive_positive + $alive_negative + $alive_waitresult + $alive_noswab + $alive_unknown}}</b></td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid #000;"><b class="text-danger">Died</b></td>
                                <td class="text-center" style="border: 1px solid #000;">{{$died_suspect}}</td>
                                <td class="text-center" style="border: 1px solid #000;">{{$died_confirmed}}</td>
                                <td class="text-center" style="border: 1px solid #000;"><b>{{$died_suspect + $died_confirmed}}</b></td>
                                <td class="text-center" style="border: 1px solid #000;">{{$died_positive}}</td>
                                <td class="text-center" style="border: 1px solid #000;">{{$died_negative}}</td>
                                <td class="text-center" style="border: 1px solid #000;">{{$died_waitresult}}</td>
                                <td class="text-center" style="border: 1px solid #000;">{{$died_noswab}}</td>
                                <td class="text-center" style="border: 1px solid #000;">{{$died_unknown}}</td>
                                <td class="text-center" style="border: 1px solid #000;"><b>{{$died_positive + $died_negative + $died_waitresult + $died_noswab + $died_unknown}}</b></td>
                            </tr>
                            <tr>
                                <td style="border: 1px solid #000;"><b class="text-success">Recovered</b></td>
                                <td class="text-center" style="border: 1px solid #000;">{{$recovered_suspect}}</td>
                                <td class="text-center" style="border: 1px solid #000;">{{$recovered_confirmed}}</td>
                                <td class="text-center" style="border: 1px solid #000;"><b>{{$recovered_suspect + $recovered_confirmed}}</b></td>
                                <td class="text-center" style="border: 1px solid #000;">{{$recovered_positive}}</td>
                                <td class="text-center" style="border: 1px solid #000;">{{$recovered_negative}}</td>
                                <td class="text-center" style="border: 1px solid #000;">{{$recovered_waitresult}}</td>
                                <td class="text-center" style="border: 1px solid #000;">{{$recovered_noswab}}</td>
                                <td class="text-center" style="border: 1px solid #000;">{{$recovered_unknown}}</td>
                                <td class="text-center" style="border: 1px solid #000;"><b>{{$recovered_positive + $recovered_negative + $recovered_waitresult + $recovered_noswab + $recovered_unknown}}</b></td>
                            </tr>
                            <tr class="bg-light">
                                <td style="border: 1px solid #000;"><b>Total</b></td>
                                <td class="text-center font-weight-bold" style="border: 1px solid #000;">{{$alive_suspect + $died_suspect + $recovered_suspect}}</td>
                                <td class="text-center font-weight-bold" style="border: 1px solid #000;">{{$alive_confirmed + $died_confirmed + $recovered_confirmed}}</td>
                                <td class="text-center font-weight-bold" style="border: 1px solid #000;">{{$alive_suspect + $died_suspect + $recovered_suspect + $alive_confirmed + $died_confirmed + $recovered_confirmed}}</td>
                                <td class="text-center font-weight-bold" style="border: 1px solid #000;">{{$alive_positive + $died_positive + $recovered_positive}}</td>
                                <td class="text-center font-weight-bold" style="border: 1px solid #000;">{{$alive_negative + $died_negative + $recovered_negative}}</td>
                                <td class="text-center font-weight-bold" style="border: 1px solid #000;">{{$alive_waitresult + $died_waitresult + $recovered_waitresult}}</td>
                                <td class="text-center font-weight-bold" style="border: 1px solid #000;">{{$alive_noswab + $died_noswab + $recovered_noswab}}</td>
                                <td class="text-center font-weight-bold" style="border: 1px solid #000;">{{$alive_unknown + $died_unknown + $recovered_unknown}}</td>
                                <td class="text-center font-weight-bold" style="border: 1px solid #000;">{{$alive_positive + $died_positive + $recovered_positive + $alive_negative + $died_negative + $recovered_negative + $alive_waitresult + $died_waitresult + $alive_noswab + $died_noswab + $recovered_noswab + $alive_unknown + $died_unknown + $recovered_unknown}}</td>
                            </tr>
                        </tbody>
                    </table>
                    @endif
                </div>
                <div class="col-md-6">
                    @if($sel_disease != 'Pert')
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
                                        <td class="text-center">{{$min_age_display}}-{{$max_age}} yrs</td>
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
                    @endif
                    <h5><b>Top 10 Barangays with {{$flavor_name}} Cases</b></h5>
                    <h6>GENERAL TRIAS, MW 1-{{$sel_mweek}}, {{$sel_year}} (N={{$current_grand_total}})</h6>
                    <div style="height: 400px">
                    <canvas id="topten" width="" height=""></canvas>
                    </div>
                    @if($sel_disease == 'Pert' || $sel_disease == 'Measles')
                    <h5 class="mt-3"><b>Proportion of {{$flavor_name}} Cases by Sex and Age</b></h5>
                    <h6>GENERAL TRIAS, MW 1-{{$sel_mweek}}, {{$sel_year}} (N={{$current_grand_total}})</h6>
                    <canvas id="ageGroup" width="50" height=""></canvas>
                    @if($sel_disease == 'Pert')
                    <hr>
                    <div class="text-center" style="margin-left: 200px;">
                        <h5 class="mt-3"><b>Vaccination Status of {{$flavor_name}} Cases</b></h5>
                        <h6>GENERAL TRIAS, Jan 01 - {{$flavor_enddate->format('M d, Y')}}</h6>
                        <div class="d-flex justify-content-center align-items-center text-center">
                            <canvas id="vaccinePie" style="width: 250px;"></canvas>
                        </div>
                    </div>
                    @endif
                    @endif
                </div>
            </div>
            @if($sel_disease == 'Pert' || $sel_disease == 'Measles')
            <div class="row">
                <div class="col-4">
                    <h6 class="mb-5">Prepared by:</h6>
                    <div class="text-center">
                        <h6><b>CHRISTIAN JAMES M. HISTORILLO</b></h6>
                        <h6>Administrative Aide III</h6>
                    </div>
                </div>
                <div class="col-4">
                    <h6 class="mb-5">Noted by:</h6>
                    <div class="text-center">
                        <h6><b>LUIS P. BROAS, RN, RPh, MAN, CAE</b></h6>
                        <h6>Nurse III-CESU Designated Head</h6>
                    </div>
                </div>
                <div class="col-4">
                    <h6 class="mb-5">Approved by:</h6>
                    <div class="text-center">
                        <h6><b>JONATHAN P. LUSECO, MD</b></h6>
                        <h6>City Health Officer II</h6>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    
    <div class="card mb-3 {{($sel_disease == 'Pert' || $sel_disease == 'Measles') ? 'd-none' : ''}}">
        <div class="card-header bg-transparent">
            <div class="d-flex justify-content-between">
                <div><b>Page 2/3 - {{$flavor_name}} Monitoring Dashboard</b></div>
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
                                <th>{{$sel_year}}</th>
                                <th class="text-muted">{{($sel_year - 1)}}</th>
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
                    <h5><b>Proportion of Cases by Case Classification</b></h5>
                    <h6>GENERAL TRIAS, MW 1-{{$sel_mweek}}, {{$sel_year}}</h6>
                    <h6>N={{$current_grand_total}}</h6>
                    <canvas id="pieChart" style="width: 500px;"></canvas>
                    <hr>
                    @if($sel_disease != 'Pert')
                    <h5><b>Proportion of Cases by Sex and Age Group</b></h5>
                    <h6>GENERAL TRIAS, MW 1-{{$sel_mweek}}, {{$sel_year}}</h6>
                    <h6>N={{$current_grand_total}}</h6>
                    <canvas id="ageGroup" width="50" height=""></canvas>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3 {{($sel_disease == 'Pert' || $sel_disease == 'Measles') ? 'd-none' : ''}}">
        <div class="card-header bg-transparent">
            <div class="d-flex justify-content-between">
                <div><b>Page 3/3 - {{$flavor_name}} Monitoring Dashboard</b></div>
                <div><b>MW {{$sel_mweek}} ({{$startDate->format('M d, Y')}} - {{$endDate->format('M d, Y')}})</b></div>
            </div>
        </div>
        <div class="card-body">
            <h5><b>Distribution of {{$flavor_name}} Cases by Barangay for the Previous 4 MWs</b></h5>
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
                                            <h6><small>PIDSR J.O Encoder</small></h6>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Noted by:</td>
                                        <td>
                                            <img src="{{asset('assets/images/SIR_LOU_PIRMA.png')}}" class="img-fluid" style="margin-bottom:-80px;width: 20rem;margin-left:-30px;" id="signature2">
                                            <h6><b><u>LUIS P. BROAS, RN, RPh, MAN, CAE</u></b></h6>
                                            <h6><small>Nurse III-CESU Designated Head</small></h6>
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
    <div class="{{($sel_disease == 'Pert') ? 'd-none' : ''}}">
        <div class="form-check">
            <label class="form-check-label">
              <input type="checkbox" class="form-check-input" name="checker1" id="checker1" value="checkedValue" checked>
              Signature 1 (Prepared by)
            </label>
        </div>
        <div class="form-check">
            <label class="form-check-label">
              <input type="checkbox" class="form-check-input" name="checker2" id="checker2" value="checkedValue" checked>
              Signature 2 (Noted by)
            </label>
        </div>
        <div class="form-check">
            <label class="form-check-label">
              <input type="checkbox" class="form-check-input" name="checker3" id="checker3" value="checkedValue" checked>
              Signature 3 (Approved by)
            </label>
        </div>
    </div>
</div>

@php
//FOR PIE GRAPH
$finalpie_titles = [];
$finalpie_counts = [];

foreach($classification_titles as $ind => $ctitle) {
    if($classification_counts[$ind] != 0) {
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
        $finalpie_titles[] = $ctitle_str.' ('.$classification_counts[$ind].')';
        $finalpie_counts[] = $classification_counts[$ind];
    }
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
            labels: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31', '32', '33', '34', '35', '36', '37', '38', '39', '40', '41', '42', '43', '44', '45', '46', '47', '48', '49', '50', '51', '52'], // Replace with your actual labels
            datasets: [
            {
                label: 'Alert Threshold',
                data: dottedLineData,
                fill: false,
                borderColor: 'rgba(8, 0, 255, 0.8)', // Customize dotted line color
                borderWidth: 2,
                type: 'line',
                borderDash: [5, 5], // Make the line dotted
                pointRadius: 0,  // Remove the circles
            },
            {
                label: 'Epidemic Threshold',
                data: lineData,
                fill: false,
                borderColor: 'rgba(255, 0, 0, 1)', // Customize line color
                borderWidth: 2,
                type: 'line',
                lineTension: 0, // Remove line tension for straight lines
                pointRadius: 0,  // Remove the circles
            },
            {
                label: '{{$sel_year}}',
                data: barData,
                backgroundColor: 'rgba(255, 236, 0, 1)', // Customize bar color
                borderColor: 'rgba(0, 0, 0, 1)', // Customize border color
                borderWidth: 1,
                barPercentage: 1,
                categoryPercentage: 1,
            },
            ]
        },
        options: {
            plugins: {
                datalabels: {
                    display: false
                },
            },
            scales: {
                x: {
                    grid: {
                        display: false,
                    },
                    ticks: {
                        maxRotation: 0,
                        font: {
                            size: 9,
                        }
                    },
                    title: {
                        display: true,
                        text: 'Morbidity Week',  // Title for the y-axis
                        position: 'bottom'  // Place the title on bottom
                    },
                },
                y: {
                    grid: {
                        display: false,
                    },
                    ticks: {
                        stepSize: 1,  // Display only whole numbers
                    },
                    title: {
                        display: true,
                        text: 'No. of Cases',  // Title for the y-axis
                    },
                },
            }
        }
    });

    var pieTitles = {!! json_encode($finalpie_titles) !!};
    var pieDatas = {!! json_encode($finalpie_counts) !!};

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
            responsive: false,
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
                display: true,
                formatter: (value, ctx) => {
                    const datapoints = ctx.chart.data.datasets[0].data
                    const total = datapoints.reduce((total, datapoint) => total + datapoint, 0)
                    const percentage = value / total * 100
                    return percentage.toFixed(0) + "%";
                },
                }
            },
            animation: {}
        }
    });
    
    @if($sel_disease == 'Pert')
    @php
    $vaccinepie_titles = [];
    $vaccinepie_counts = [];
    foreach($vaccine_array as $v) {
        if($v['count'] != 0) {
            $vaccinepie_titles[] = $v['name'];
            $vaccinepie_counts[] = $v['count'];
        }
    }
    @endphp

    var pieTitles = {!! json_encode($vaccinepie_titles) !!};
    var pieDatas = {!! json_encode($vaccinepie_counts) !!};

    var ctx = document.getElementById('vaccinePie').getContext('2d');
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
            responsive: false,
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
                    position: 'bottom',
                },
                datalabels: {
                    display: true,
                    formatter: (value, ctx) => {
                        const datapoints = ctx.chart.data.datasets[0].data
                        const total = datapoints.reduce((total, datapoint) => total + datapoint, 0)
                        const percentage = value / total * 100
                        return percentage.toFixed(0) + "%";
                    },
                    font: {
                        weight: 'bold',
                        size: 14,
                    }
                }
            },
            animation: {}
        }
    });

    @endif

    const labels = [];
    const data = [];

    @foreach($top10Brgys as $barangay)
        @if($barangay['brgy_grand_total_cases'] != 0)
        labels.push("{{ $barangay['brgy_name'] }}");
        data.push({{ $barangay['brgy_grand_total_cases'] }});
        @endif
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
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y',
            title: {
                text: "Barangay",
                display: true,
            },
            scales: {
                y: {
                    ticks: {
                        font: {
                            size: 16 //this change the font size
                        }
                    },
                    grid: {
                        display: false,
                    },
                },
                x: {
                    grid: {
                        display: false,
                    },
                    ticks: {
                        stepSize: 1,
                    },
                    title: {
                        display: true,
                        text: 'No. of Cases',  // Title for the y-axis
                    },
                    
                }
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
            layout: {
                padding: {
                    right: 30,
                },
            },
            animation: {}
        }
    });

    var male_set = {{json_encode($ag_male)}};
    var female_set = {{json_encode($ag_female)}};
    var age_labels = {!! $age_display_string !!};

    var ctx = document.getElementById('ageGroup').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'bar',

        data: {
            labels: age_labels,
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
            barPercentage: 1.2,
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
                    grid: {
                        display: false,
                    },
                    ticks: {
                        stepSize: 1,  // Display only whole numbers
                        callback: function(value, index, values) {
                            return Math.abs(value);  // Return the absolute value to hide the negative sign
                        }
                    },
                    title: {
                        display: true,
                        text: 'No. of Cases',  // Title for the y-axis
                        position: 'bottom'  // Place the title on bottom
                    },
                    @if($age_highest_value == 'EQUAL')
                    
                    @elseif($age_highest_value == 'MALE')
                    suggestedMax: {{$suggestedMaxAge}},
                    @else
                    suggestedMin: {{$suggestedMinAge}},
                    @endif
                },
                y: {
                    stacked: true,
                    grid: {
                        display: false,
                    },
                },
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                },
                datalabels: {
                display: false
                }
            },
            animation: {},
        }
    });
</script>