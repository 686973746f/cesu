@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div><b>Summary of Notifiable Diseases</b></div>
                    <div><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#filterModal">Filter</button></div>
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
                <div class="row my-3 bg-info">
                    <div class="col-9 d-flex justify-content-center align-items-center text-center"><h4><b>Epidemic-prone Disease Case Surveillance (EDCS) Weekly Report</b></h4></div>
                    <div class="col-3">
                        <h6>Morbidity Week {{$currentDay->format('W')}}</h6>
                        <h6>January 1 - {{$currentDay->format('F d, Y')}}</h6>
                    </div>
                </div>
                <h4><b>Summary</b></h4>
                <h5>This report provides cumulative data for Notifiable Diseases reported in EDCS from the period of January 1 to {{$currentDay->format('F d, Y')}} (Morbidity Week {{$currentDay->format('W')}}).</h5>
                <h5>The number of cases shown in the summary table of Reported Notifiable Diseases or Conditions <b><i>DO NOT</i></b> represent the final number and are subject to change after inclusion of delayed reports and review of cases.</h5>
                
                <h4 class="text-center mt-5"><b>Summary of Notifiable Diseases</b></h4>
                <h5 class="text-center">City of General Trias, Morbidity Week 1-{{$currentDay->format('W')}} (January 1 - {{$currentDay->format('F d, Y')}})</h5>
                
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="text-center thead-light">
                            <tr>
                                <th rowspan="2">Notifiable Diseases</th>
                                <th colspan="3">Morbidity Week {{$current_week}}</th>
                                <th colspan="4">Cumulative Week (1-{{$current_week}})</th>
                                <th>Comparison</th>
                            </tr>
                            <tr>
                                <th>Cases</th>
                                <th>Death</th>
                                <th>CFR %</th>
                                <th>{{$current_year}} Cases</th>
                                <th>Death</th>
                                <th>CFR %</th>
                                <th>{{$last_year}} Cases</th>
                                <th>{{$current_year}} vs. {{$last_year}} Percentage Change*</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="bg-light">
                                <td colspan="9"><b>Vaccine Preventable Diseases</b></td>
                            </tr>
                            @foreach($vpd_arr as $r)
                            <tr>
                                <td>{{$r['name']}}</td>
                                <td class="text-center {{$r['text_style']}}">{{$r['currentmw_count']}}</td>
                                <td class="text-center">{{$r['currentmw_died_count']}}</td>
                                <td class="text-center">{{$r['currentmw_cfr']}}%</td>
                                <td class="text-center">{{$r['currentyear_count']}}</td>
                                <td class="text-center">{{$r['currentyear_died_count']}}</td>
                                <td class="text-center">{{$r['currentyear_cfr']}}%</td>
                                <td class="text-center">{{$r['lastyear_count']}}</td>
                                <td class="text-center">
                                    {{abs($r['percentageChange'])}}% @if($r['compare_type'] == 'HIGHER' && $r['percentageChange'] != 0)<span class="text-danger">▲</span>@elseif($r['compare_type'] == 'LOWER')<span class="text-success">▼</span>@else @endif
                                </td>
                            </tr>
                            @endforeach
                            <tr class="bg-light">
                                <td colspan="9"><b>Vector-borne Diseases</b></td>
                            </tr>
                            @foreach($vectorborn_arr as $r)
                            <tr>
                                <td>{{$r['name']}}</td>
                                <td class="text-center {{$r['text_style']}}">{{$r['currentmw_count']}}</td>
                                <td class="text-center">{{$r['currentmw_died_count']}}</td>
                                <td class="text-center">{{$r['currentmw_cfr']}}%</td>
                                <td class="text-center">{{$r['currentyear_count']}}</td>
                                <td class="text-center">{{$r['currentyear_died_count']}}</td>
                                <td class="text-center">{{$r['currentyear_cfr']}}%</td>
                                <td class="text-center">{{$r['lastyear_count']}}</td>
                                <td class="text-center">
                                    {{abs($r['percentageChange'])}}% @if($r['compare_type'] == 'HIGHER' && $r['percentageChange'] != 0)<span class="text-danger">▲</span>@elseif($r['compare_type'] == 'LOWER')<span class="text-success">▼</span>@else @endif
                                </td>
                            </tr>
                            @endforeach
                            <tr class="bg-light">
                                <td colspan="9"><b>Zoonotic Disease</b></td>
                            </tr>
                            @foreach($zoonotic_arr as $r)
                            <tr>
                                <td>{{$r['name']}}</td>
                                <td class="text-center {{$r['text_style']}}">{{$r['currentmw_count']}}</td>
                                <td class="text-center">{{$r['currentmw_died_count']}}</td>
                                <td class="text-center">{{$r['currentmw_cfr']}}%</td>
                                <td class="text-center">{{$r['currentyear_count']}}</td>
                                <td class="text-center">{{$r['currentyear_died_count']}}</td>
                                <td class="text-center">{{$r['currentyear_cfr']}}%</td>
                                <td class="text-center">{{$r['lastyear_count']}}</td>
                                <td class="text-center">
                                    {{abs($r['percentageChange'])}}% @if($r['compare_type'] == 'HIGHER' && $r['percentageChange'] != 0)<span class="text-danger">▲</span>@elseif($r['compare_type'] == 'LOWER')<span class="text-success">▼</span>@else @endif
                                </td>
                            </tr>
                            @endforeach
                            <tr class="bg-light">
                                <td colspan="9"><b>Food and Water-borne</b></td>
                            </tr>
                            @foreach($foodnwaterborn_arr as $r)
                            <tr>
                                <td>{{$r['name']}}</td>
                                <td class="text-center {{$r['text_style']}}">{{$r['currentmw_count']}}</td>
                                <td class="text-center">{{$r['currentmw_died_count']}}</td>
                                <td class="text-center">{{$r['currentmw_cfr']}}%</td>
                                <td class="text-center">{{$r['currentyear_count']}}</td>
                                <td class="text-center">{{$r['currentyear_died_count']}}</td>
                                <td class="text-center">{{$r['currentyear_cfr']}}%</td>
                                <td class="text-center">{{$r['lastyear_count']}}</td>
                                <td class="text-center">
                                    {{abs($r['percentageChange'])}}% @if($r['compare_type'] == 'HIGHER' && $r['percentageChange'] != 0)<span class="text-danger">▲</span>@elseif($r['compare_type'] == 'LOWER')<span class="text-success">▼</span>@else @endif
                                </td>
                            </tr>
                            @endforeach
                            <tr class="bg-light">
                                <td colspan="9"><b>Other Diseases</b></td>
                            </tr>
                            @foreach($other_arr as $r)
                            
                            <tr>
                                <td>{{$r['name']}}</td>
                                <td class="text-center {{$r['text_style']}}">{{$r['currentmw_count']}}</td>
                                <td class="text-center">{{$r['currentmw_died_count']}}</td>
                                <td class="text-center">{{$r['currentmw_cfr']}}%</td>
                                <td class="text-center">{{$r['currentyear_count']}}</td>
                                <td class="text-center">{{$r['currentyear_died_count']}}</td>
                                <td class="text-center">{{$r['currentyear_cfr']}}%</td>
                                <td class="text-center">{{$r['lastyear_count']}}</td>
                                <td class="text-center">
                                    {{abs($r['percentageChange'])}}% @if($r['compare_type'] == 'HIGHER' && $r['percentageChange'] != 0)<span class="text-danger">▲</span>@elseif($r['compare_type'] == 'LOWER')<span class="text-success">▼</span>@else @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="row justify-content-between mx-2 mt-3">
                    <div>
                        <h6>Prepared by:</h6>
                        <h6 class="mt-5"><b>Christian James M. Historillo</b></h6>
                        <h6>Administrative Aide III</h6>
                    </div>
                    <div>
                        <h6>Noted by:</h6>
                        <h6 class="mt-5"><b>Luis P. Broas, RN, RPh, MAN, CAE</b></h6>
                        <h6>Nurse III-CESU Designated Head</h6>
                    </div>
                    <div>
                        <h6>Approved by:</h6>
                        <h6 class="mt-5"><b>Jonathan P. Luseco, MD</b></h6>
                        <h6>City Health Officer II</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="" method="GET">
        <div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Filter</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="year"><b class="text-danger">*</b>Year</label>
                            <input type="number" class="form-control" name="year" id="year" min="{{(date('Y')-5)}}" max="{{date('Y')}}" value="{{date('Y')}}" required>
                        </div>
                        <div class="form-group">
                            <label for="mw"><b class="text-danger">*</b>Morbidity Week</label>
                            <input type="number" class="form-control" name="mw" id="mw" value="{{date('W')}}" min="1" max="52" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-block">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection