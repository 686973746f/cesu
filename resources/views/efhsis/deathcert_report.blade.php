@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header"><b>Mortality Report</b></div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <h3>Barangay: {{request()->input('brgy')}}</h3>
                    <h3>Date: {{Carbon\Carbon::createFromDate(request()->input('year'), request()->input('month'), 1)->format('m/Y')}}</h3>
                    <thead class="thead-light text-center">
                        <tr>
                            <th>Part 1 - Mortality</th>
                            <th style="background-color: #8fa2bd;">Male</th>
                            <th style="background-color: #dea6a5">Female</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Early Neonatal Deaths</td>
                            <td style="background-color: #8fa2bd;" class="text-center">{{$early_neonatal_deaths_finaltotal_m}}</td>
                            <td style="background-color: #dea6a5" class="text-center">{{$early_neonatal_deaths_finaltotal_f}}</td>
                        </tr>
                        <tr>
                            <td>Fetal Deaths</td>
                            <td style="background-color: #8fa2bd;" class="text-center">{{$fetal_deaths_finaltotal_m}}</td>
                            <td style="background-color: #dea6a5" class="text-center">{{$fetal_deaths_finaltotal_f}}</td>
                        </tr>
                        <tr>
                            <td>Neonatal Deaths</td>
                            <td style="background-color: #8fa2bd;" class="text-center">{{$neonatal_deaths_finaltotal_m}}</td>
                            <td style="background-color: #dea6a5" class="text-center">{{$neonatal_deaths_finaltotal_f}}</td>
                        </tr>
                        <tr>
                            <td>Infant Deaths</td>
                            <td style="background-color: #8fa2bd;" class="text-center">{{$infant_deaths_finaltotal_m}}</td>
                            <td style="background-color: #dea6a5" class="text-center">{{$infant_deaths_finaltotal_f}}</td>
                        </tr>
                        <tr>
                            <td>Under-five Deaths</td>
                            <td style="background-color: #8fa2bd;" class="text-center">{{$uf_deaths_finaltotal_m}}</td>
                            <td style="background-color: #dea6a5" class="text-center">{{$uf_deaths_finaltotal_f}}</td>
                        </tr>
                        <tr>
                            <td>Maternal Deaths</td>
                            <td class="bg-secondary"></td>
                            <td style="background-color: #dea6a5" class="text-center">{{$mat_deaths_finaltotal}}</td>
                        </tr>
                        <tr>
                            <td>Originating Maternal Deaths</td>
                            <td class="bg-secondary"></td>
                            <td style="background-color: #dea6a5" class="text-center">{{$ormat_deaths_finaltotal}}</td>
                        </tr>
                        <tr>
                            <td>Total Deaths</td>
                            <td style="background-color: #8fa2bd;" class="text-center">{{$total_deaths_m}}</td>
                            <td style="background-color: #dea6a5" class="text-center">{{$total_deaths_f}}</td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <h4>Mortality Cause of Death</h4>
                <table class="table table-bordered">
                    <thead class="thead-light text-center">
                        <tr>
                            <th colspan="2">0-6 Days</th>
                            <th colspan="2">7-28 Days</th>
                            <th colspan="2">29 Days - 11 Mos.</th>
                            <th colspan="2">1-4</th>
                            <th colspan="2">5-9</th>
                            <th colspan="2">10-14</th>
                            <th colspan="2">15-19</th>
                            <th colspan="2">20-24</th>
                            <th colspan="2">25-29</th>
                            <th colspan="2">30-34</th>
                            <th colspan="2">35-39</th>
                            <th colspan="2">40-44</th>
                            <th colspan="2">45-49</th>
                            <th colspan="2">50-54</th>
                            <th colspan="2">55-59</th>
                            <th colspan="2">60-64</th>
                            <th colspan="2">65-69</th>
                            <th colspan="2">70 and Above</th>
                            <th colspan="2">TOTAL</th>
                        </tr>
                        <tr>
                            <th style="background-color: #8fa2bd;" class="text-right">M</th>
                            <th style="background-color: #dea6a5">F</th>
                            <th style="background-color: #8fa2bd;">M</th>
                            <th style="background-color: #dea6a5">F</th>
                            <th style="background-color: #8fa2bd;">M</th>
                            <th style="background-color: #dea6a5">F</th>
                            <th style="background-color: #8fa2bd;">M</th>
                            <th style="background-color: #dea6a5">F</th>
                            <th style="background-color: #8fa2bd;">M</th>
                            <th style="background-color: #dea6a5">F</th>
                            <th style="background-color: #8fa2bd;">M</th>
                            <th style="background-color: #dea6a5">F</th>
                            <th style="background-color: #8fa2bd;">M</th>
                            <th style="background-color: #dea6a5">F</th>
                            <th style="background-color: #8fa2bd;">M</th>
                            <th style="background-color: #dea6a5">F</th>
                            <th style="background-color: #8fa2bd;">M</th>
                            <th style="background-color: #dea6a5">F</th>
                            <th style="background-color: #8fa2bd;">M</th>
                            <th style="background-color: #dea6a5">F</th>
                            <th style="background-color: #8fa2bd;">M</th>
                            <th style="background-color: #dea6a5">F</th>
                            <th style="background-color: #8fa2bd;">M</th>
                            <th style="background-color: #dea6a5">F</th>
                            <th style="background-color: #8fa2bd;">M</th>
                            <th style="background-color: #dea6a5">F</th>
                            <th style="background-color: #8fa2bd;">M</th>
                            <th style="background-color: #dea6a5">F</th>
                            <th style="background-color: #8fa2bd;">M</th>
                            <th style="background-color: #dea6a5">F</th>
                            <th style="background-color: #8fa2bd;">M</th>
                            <th style="background-color: #dea6a5">F</th>
                            <th style="background-color: #8fa2bd;">M</th>
                            <th style="background-color: #dea6a5">F</th>
                            <th style="background-color: #8fa2bd;">M</th>
                            <th style="background-color: #dea6a5">F</th>
                            <th style="background-color: #8fa2bd;">M</th>
                            <th style="background-color: #dea6a5">F</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($final_arr as $d)
                        <tr>
                            <td colspan="3">DISEASE</td>
                            <td colspan="16">{{$d['disease']}}</td>
                        </tr>
                        <tr>
                            <td style="background-color: #8fa2bd;" class="text-right">{{$d['age1_m']}}</td>
                            <td style="background-color: #dea6a5" class="text-right">{{$d['age1_f']}}</td>
                            <td style="background-color: #8fa2bd;" class="text-right">{{$d['age2_m']}}</td>
                            <td style="background-color: #dea6a5" class="text-right">{{$d['age2_f']}}</td>
                            <td style="background-color: #8fa2bd;" class="text-right">{{$d['age3_m']}}</td>
                            <td style="background-color: #dea6a5" class="text-right">{{$d['age3_f']}}</td>
                            <td style="background-color: #8fa2bd;" class="text-right">{{$d['age4_m']}}</td>
                            <td style="background-color: #dea6a5" class="text-right">{{$d['age4_f']}}</td>
                            <td style="background-color: #8fa2bd;" class="text-right">{{$d['age5_m']}}</td>
                            <td style="background-color: #dea6a5" class="text-right">{{$d['age5_f']}}</td>
                            <td style="background-color: #8fa2bd;" class="text-right">{{$d['age6_m']}}</td>
                            <td style="background-color: #dea6a5" class="text-right">{{$d['age6_f']}}</td>
                            <td style="background-color: #8fa2bd;" class="text-right">{{$d['age7_m']}}</td>
                            <td style="background-color: #dea6a5" class="text-right">{{$d['age7_f']}}</td>
                            <td style="background-color: #8fa2bd;" class="text-right">{{$d['age8_m']}}</td>
                            <td style="background-color: #dea6a5" class="text-right">{{$d['age8_f']}}</td>
                            <td style="background-color: #8fa2bd;" class="text-right">{{$d['age9_m']}}</td>
                            <td style="background-color: #dea6a5" class="text-right">{{$d['age9_f']}}</td>
                            <td style="background-color: #8fa2bd;" class="text-right">{{$d['age10_m']}}</td>
                            <td style="background-color: #dea6a5" class="text-right">{{$d['age10_f']}}</td>
                            <td style="background-color: #8fa2bd;" class="text-right">{{$d['age11_m']}}</td>
                            <td style="background-color: #dea6a5" class="text-right">{{$d['age11_f']}}</td>
                            <td style="background-color: #8fa2bd;" class="text-right">{{$d['age12_m']}}</td>
                            <td style="background-color: #dea6a5" class="text-right">{{$d['age12_f']}}</td>
                            <td style="background-color: #8fa2bd;" class="text-right">{{$d['age13_m']}}</td>
                            <td style="background-color: #dea6a5" class="text-right">{{$d['age13_f']}}</td>
                            <td style="background-color: #8fa2bd;" class="text-right">{{$d['age14_m']}}</td>
                            <td style="background-color: #dea6a5" class="text-right">{{$d['age14_f']}}</td>
                            <td style="background-color: #8fa2bd;" class="text-right">{{$d['age15_m']}}</td>
                            <td style="background-color: #dea6a5" class="text-right">{{$d['age15_f']}}</td>
                            <td style="background-color: #8fa2bd;" class="text-right">{{$d['age16_m']}}</td>
                            <td style="background-color: #dea6a5" class="text-right">{{$d['age16_f']}}</td>
                            <td style="background-color: #8fa2bd;" class="text-right">{{$d['age17_m']}}</td>
                            <td style="background-color: #dea6a5" class="text-right">{{$d['age17_f']}}</td>
                            <td style="background-color: #8fa2bd;" class="text-right">{{$d['age18_m']}}</td>
                            <td style="background-color: #dea6a5" class="text-right">{{$d['age18_f']}}</td>
                            <td style="background-color: #8fa2bd;" class="text-right">{{$d['total_m']}}</td>
                            <td style="background-color: #dea6a5" class="text-right">{{$d['total_f']}}</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td colspan="3">DISEASE</td>
                            <td colspan="16"></td>
                        </tr>
                        <tr>
                            <td style="background-color: #8fa2bd;" class="text-right">0</td>
                            <td style="background-color: #dea6a5" class="text-right">0</td>
                            <td style="background-color: #8fa2bd;" class="text-right">0</td>
                            <td style="background-color: #dea6a5" class="text-right">0</td>
                            <td style="background-color: #8fa2bd;" class="text-right">0</td>
                            <td style="background-color: #dea6a5" class="text-right">0</td>
                            <td style="background-color: #8fa2bd;" class="text-right">0</td>
                            <td style="background-color: #dea6a5" class="text-right">0</td>
                            <td style="background-color: #8fa2bd;" class="text-right">0</td>
                            <td style="background-color: #dea6a5" class="text-right">0</td>
                            <td style="background-color: #8fa2bd;" class="text-right">0</td>
                            <td style="background-color: #dea6a5" class="text-right">0</td>
                            <td style="background-color: #8fa2bd;" class="text-right">0</td>
                            <td style="background-color: #dea6a5" class="text-right">0</td>
                            <td style="background-color: #8fa2bd;" class="text-right">0</td>
                            <td style="background-color: #dea6a5" class="text-right">0</td>
                            <td style="background-color: #8fa2bd;" class="text-right">0</td>
                            <td style="background-color: #dea6a5" class="text-right">0</td>
                            <td style="background-color: #8fa2bd;" class="text-right">0</td>
                            <td style="background-color: #dea6a5" class="text-right">0</td>
                            <td style="background-color: #8fa2bd;" class="text-right">0</td>
                            <td style="background-color: #dea6a5" class="text-right">0</td>
                            <td style="background-color: #8fa2bd;" class="text-right">0</td>
                            <td style="background-color: #dea6a5" class="text-right">0</td>
                            <td style="background-color: #8fa2bd;" class="text-right">0</td>
                            <td style="background-color: #dea6a5" class="text-right">0</td>
                            <td style="background-color: #8fa2bd;" class="text-right">0</td>
                            <td style="background-color: #dea6a5" class="text-right">0</td>
                            <td style="background-color: #8fa2bd;" class="text-right">0</td>
                            <td style="background-color: #dea6a5" class="text-right">0</td>
                            <td style="background-color: #8fa2bd;" class="text-right">0</td>
                            <td style="background-color: #dea6a5" class="text-right">0</td>
                            <td style="background-color: #8fa2bd;" class="text-right">0</td>
                            <td style="background-color: #dea6a5" class="text-right">0</td>
                            <td style="background-color: #8fa2bd;" class="text-right">0</td>
                            <td style="background-color: #dea6a5" class="text-right">0</td>
                            <td style="background-color: #8fa2bd;" class="text-right">0</td>
                            <td style="background-color: #dea6a5" class="text-right">0</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection