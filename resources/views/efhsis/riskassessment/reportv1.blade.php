@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div><b>Risk Assessment Report</b></div>
                <div>BRGY. {{$b->name}} - Year: {{request()->input('year')}}</div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="thead-light text-center">
                            <tr>
                                <th rowspan="3">Month</th>
                                <th colspan="2" rowspan="2">Total number of adults 20 years old and above who were risk-assessed using PhilPEN Protocol</th>
                                <th colspan="6">Number of adults risk-assessed using PhilPEN</th>
                                <th rowspan="3">Number of adult women screened for Cervical Cancer using VIA/Pap Smear</th>
                                <th rowspan="3">No. Adult women found +/suspect for Cervical Cancer using VIA/P. Smear</th>
                                <th rowspan="3">No. Adult women screened for Breast Mass</th>
                                <th rowspan="3">No. Adult women with Suspicious Breast Mass</th>
                                <th colspan="2" rowspan="2">No. Newly Identified Hypertensive Adults</th>
                                <th colspan="2" rowspan="2">No. Newly Identified Adults with Type 2 DM</th>
                                <th colspan="2" rowspan="2">No. Senior Citizens screened for Visual Acuity</th>
                                <th colspan="2" rowspan="2">No. Senior Citizens diagnosed with eye disease/s</th>
                                <th colspan="2" rowspan="2">No. Senior Citizens who received one (1) dose of PPV</th>
                                <th colspan="2" rowspan="2">No. Senior Citizens who received one (1) of Influenza Vaccine</th>
                            </tr>
                            <tr>
                                <th colspan="2">Current Smokers</th>
                                <th colspan="2">Alcohol Binge Drinkers</th>
                                <th colspan="2">Overweight/Obese</th>
                            </tr>
                            <tr>
                                <th>Male</th>
                                <th>Female</th>
                                <th>Male</th>
                                <th>Female</th>
                                <th>Male</th>
                                <th>Female</th>
                                <th>Male</th>
                                <th>Female</th>
                                <th>Male</th>
                                <th>Female</th>
                                <th>Male</th>
                                <th>Female</th>
                                <th>Male</th>
                                <th>Female</th>
                                <th>Male</th>
                                <th>Female</th>
                                <th>Male</th>
                                <th>Female</th>
                                <th>Male</th>
                                <th>Female</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($final_arr as $d)
                            <tr class="text-center">
                                <td><b>{{$d['month']}}</b></td>
                                <td>{{$d['pen_m']}}</td>
                                <td>{{$d['pen_f']}}</td>
                                <td>{{$d['current_smoker_m']}}</td>
                                <td>{{$d['current_smoker_f']}}</td>
                                <td>{{$d['binge_drinker_m']}}</td>
                                <td>{{$d['binge_drinker_f']}}</td>
                                <td>{{$d['over_obese_m']}}</td>
                                <td>{{$d['over_obese_f']}}</td>
                                <td class="bg-secondary"></td>
                                <td class="bg-secondary"></td>
                                <td class="bg-secondary"></td>
                                <td>{{$d['susp_breastmass']}}</td>
                                <td>{{$d['newly_hypertensive_m_new'] + $d['newly_hypertensive_m_updated']}}</td>
                                <td>{{$d['newly_hypertensive_f_new'] + $d['newly_hypertensive_f_updated']}}</td>
                                <td>{{$d['newly_diabetes_m_new'] + $d['newly_diabetes_m_updated']}}</td>
                                <td>{{$d['newly_diabetes_f_new'] + $d['newly_diabetes_f_updated']}}</td>
                                <td>{{$d['senior_visual_m']}}</td>
                                <td>{{$d['senior_visual_f']}}</td>
                                <td>{{$d['senior_eyedisease_m']}}</td>
                                <td>{{$d['senior_eyedisease_f']}}</td>
                                <td class="bg-secondary"></td>
                                <td class="bg-secondary"></td>
                                <td class="bg-secondary"></td>
                                <td class="bg-secondary"></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection