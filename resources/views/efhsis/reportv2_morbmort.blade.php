@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header"><b>Morb Mort</b></div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-light text-center">
                        <tr>
                            <th rowspan="2">Mortality</th>
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
                            <th>M</th>
                            <th>F</th>
                            <th>M</th>
                            <th>F</th>
                            <th>M</th>
                            <th>F</th>
                            <th>M</th>
                            <th>F</th>
                            <th>M</th>
                            <th>F</th>
                            <th>M</th>
                            <th>F</th>
                            <th>M</th>
                            <th>F</th>
                            <th>M</th>
                            <th>F</th>
                            <th>M</th>
                            <th>F</th>
                            <th>M</th>
                            <th>F</th>
                            <th>M</th>
                            <th>F</th>
                            <th>M</th>
                            <th>F</th>
                            <th>M</th>
                            <th>F</th>
                            <th>M</th>
                            <th>F</th>
                            <th>M</th>
                            <th>F</th>
                            <th>M</th>
                            <th>F</th>
                            <th>M</th>
                            <th>F</th>
                            <th>M</th>
                            <th>F</th>
                            <th>M</th>
                            <th>F</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $d)
                        <tr>
                            <td>{{$d['DISEASE']}}</td>
                            <td class="text-center">{{$d['AGE_0_6DAYS_M']}}</td>
                            <td class="text-center">{{$d['AGE_0_6DAYS_F']}}</td>
                            <td class="text-center">{{$d['AGE_7_28DAYS_M']}}</td>
                            <td class="text-center">{{$d['AGE_7_28DAYS_F']}}</td>
                            <td class="text-center">{{$d['AGE_29DAYS_11MOS_M']}}</td>
                            <td class="text-center">{{$d['AGE_29DAYS_11MOS_F']}}</td>
                            <td class="text-center">{{$d['AGE_1_4_M']}}</td>
                            <td class="text-center">{{$d['AGE_1_4_F']}}</td>
                            <td class="text-center">{{$d['AGE_5_9_M']}}</td>
                            <td class="text-center">{{$d['AGE_5_9_F']}}</td>
                            <td class="text-center">{{$d['AGE_10_14_M']}}</td>
                            <td class="text-center">{{$d['AGE_10_14_F']}}</td>
                            <td class="text-center">{{$d['AGE_15_19_M']}}</td>
                            <td class="text-center">{{$d['AGE_15_19_F']}}</td>
                            <td class="text-center">{{$d['AGE_20_24_M']}}</td>
                            <td class="text-center">{{$d['AGE_20_24_F']}}</td>
                            <td class="text-center">{{$d['AGE_25_29_M']}}</td>
                            <td class="text-center">{{$d['AGE_25_29_F']}}</td>
                            <td class="text-center">{{$d['AGE_30_34_M']}}</td>
                            <td class="text-center">{{$d['AGE_30_34_F']}}</td>
                            <td class="text-center">{{$d['AGE_35_39_M']}}</td>
                            <td class="text-center">{{$d['AGE_35_39_F']}}</td>
                            <td class="text-center">{{$d['AGE_40_44_M']}}</td>
                            <td class="text-center">{{$d['AGE_40_44_F']}}</td>
                            <td class="text-center">{{$d['AGE_45_49_M']}}</td>
                            <td class="text-center">{{$d['AGE_45_49_F']}}</td>
                            <td class="text-center">{{$d['AGE_50_54_M']}}</td>
                            <td class="text-center">{{$d['AGE_50_54_F']}}</td>
                            <td class="text-center">{{$d['AGE_55_59_M']}}</td>
                            <td class="text-center">{{$d['AGE_55_59_F']}}</td>
                            <td class="text-center">{{$d['AGE_60_64_M']}}</td>
                            <td class="text-center">{{$d['AGE_60_64_F']}}</td>
                            <td class="text-center">{{$d['AGE_65_69_M']}}</td>
                            <td class="text-center">{{$d['AGE_65_69_F']}}</td>
                            <td class="text-center">{{$d['AGE_70ABOVE_M']}}</td>
                            <td class="text-center">{{$d['AGE_70ABOVE_F']}}</td>
                            <td class="text-center">{{$d['TOTAL_M']}}</td>
                            <td class="text-center">{{$d['TOTAL_F']}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <table class="table table-bordered table-striped">
                    <thead class="thead-light text-center">
                        <tr>
                            <th rowspan="2">Morbidity</th>
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
                            <th>M</th>
                            <th>F</th>
                            <th>M</th>
                            <th>F</th>
                            <th>M</th>
                            <th>F</th>
                            <th>M</th>
                            <th>F</th>
                            <th>M</th>
                            <th>F</th>
                            <th>M</th>
                            <th>F</th>
                            <th>M</th>
                            <th>F</th>
                            <th>M</th>
                            <th>F</th>
                            <th>M</th>
                            <th>F</th>
                            <th>M</th>
                            <th>F</th>
                            <th>M</th>
                            <th>F</th>
                            <th>M</th>
                            <th>F</th>
                            <th>M</th>
                            <th>F</th>
                            <th>M</th>
                            <th>F</th>
                            <th>M</th>
                            <th>F</th>
                            <th>M</th>
                            <th>F</th>
                            <th>M</th>
                            <th>F</th>
                            <th>M</th>
                            <th>F</th>
                            <th>M</th>
                            <th>F</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data2 as $d)
                        <tr>
                            <td>{{$d['DISEASE']}}</td>
                            <td class="text-center">{{$d['AGE_0_6DAYS_M']}}</td>
                            <td class="text-center">{{$d['AGE_0_6DAYS_F']}}</td>
                            <td class="text-center">{{$d['AGE_7_28DAYS_M']}}</td>
                            <td class="text-center">{{$d['AGE_7_28DAYS_F']}}</td>
                            <td class="text-center">{{$d['AGE_29DAYS_11MOS_M']}}</td>
                            <td class="text-center">{{$d['AGE_29DAYS_11MOS_F']}}</td>
                            <td class="text-center">{{$d['AGE_1_4_M']}}</td>
                            <td class="text-center">{{$d['AGE_1_4_F']}}</td>
                            <td class="text-center">{{$d['AGE_5_9_M']}}</td>
                            <td class="text-center">{{$d['AGE_5_9_F']}}</td>
                            <td class="text-center">{{$d['AGE_10_14_M']}}</td>
                            <td class="text-center">{{$d['AGE_10_14_F']}}</td>
                            <td class="text-center">{{$d['AGE_15_19_M']}}</td>
                            <td class="text-center">{{$d['AGE_15_19_F']}}</td>
                            <td class="text-center">{{$d['AGE_20_24_M']}}</td>
                            <td class="text-center">{{$d['AGE_20_24_F']}}</td>
                            <td class="text-center">{{$d['AGE_25_29_M']}}</td>
                            <td class="text-center">{{$d['AGE_25_29_F']}}</td>
                            <td class="text-center">{{$d['AGE_30_34_M']}}</td>
                            <td class="text-center">{{$d['AGE_30_34_F']}}</td>
                            <td class="text-center">{{$d['AGE_35_39_M']}}</td>
                            <td class="text-center">{{$d['AGE_35_39_F']}}</td>
                            <td class="text-center">{{$d['AGE_40_44_M']}}</td>
                            <td class="text-center">{{$d['AGE_40_44_F']}}</td>
                            <td class="text-center">{{$d['AGE_45_49_M']}}</td>
                            <td class="text-center">{{$d['AGE_45_49_F']}}</td>
                            <td class="text-center">{{$d['AGE_50_54_M']}}</td>
                            <td class="text-center">{{$d['AGE_50_54_F']}}</td>
                            <td class="text-center">{{$d['AGE_55_59_M']}}</td>
                            <td class="text-center">{{$d['AGE_55_59_F']}}</td>
                            <td class="text-center">{{$d['AGE_60_64_M']}}</td>
                            <td class="text-center">{{$d['AGE_60_64_F']}}</td>
                            <td class="text-center">{{$d['AGE_65_69_M']}}</td>
                            <td class="text-center">{{$d['AGE_65_69_F']}}</td>
                            <td class="text-center">{{$d['AGE_70ABOVE_M']}}</td>
                            <td class="text-center">{{$d['AGE_70ABOVE_F']}}</td>
                            <td class="text-center">{{$d['TOTAL_M']}}</td>
                            <td class="text-center">{{$d['TOTAL_F']}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection