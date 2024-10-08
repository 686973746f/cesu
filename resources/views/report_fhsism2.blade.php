@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">M2</div>
        <div class="card-body">
            <form action="{{route('report.fhsis')}}" method="GET">
                <label for="year">Year</label>
                <select class="form-control" name="year" id="year" required>
                    @foreach(range(date('Y'), 2019) as $y)
                    <option value="{{$y}}" {{(request()->input('year') == $y) ? 'selected' : ''}}>{{$y}}</option>
                    @endforeach
                </select>
                <div class="form-group">
                  <label for="month">Month</label>
                  <select class="form-control" name="month" id="month">
                    <option value="1" {{(request()->input('month') == 1) ? 'selected' : ''}}>JANUARY</option>
                    <option value="2" {{(request()->input('month') == 2) ? 'selected' : ''}}>FEBRUARY</option>
                    <option value="3" {{(request()->input('month') == 3) ? 'selected' : ''}}>MARCH</option>
                    <option value="4" {{(request()->input('month') == 4) ? 'selected' : ''}}>APRIL</option>
                    <option value="5" {{(request()->input('month') == 5) ? 'selected' : ''}}>MAY</option>
                    <option value="6" {{(request()->input('month') == 6) ? 'selected' : ''}}>JUNE</option>
                    <option value="7" {{(request()->input('month') == 7) ? 'selected' : ''}}>JULY</option>
                    <option value="8" {{(request()->input('month') == 8) ? 'selected' : ''}}>AUGUST</option>
                    <option value="9" {{(request()->input('month') == 9) ? 'selected' : ''}}>SEPTEMBER</option>
                    <option value="10" {{(request()->input('month') == 10) ? 'selected' : ''}}>OCTOBER</option>
                    <option value="11" {{(request()->input('month') == 11) ? 'selected' : ''}}>NOVEMBER</option>
                    <option value="12" {{(request()->input('month') == 12) ? 'selected' : ''}}>DECEMBER</option>
                  </select>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
            @if(isset($collect1))
            <hr>
            <div class="table-responsive mb-3">
                <table class="table table-bordered">
                    <thead class="thead-light text-centered">
                        <tr>
                            <th colspan="42">COVID-19 virus identified, COVID-19 NOS</th>
                        </tr>
                        <tr>
                            <th rowspan="2">Barangay</th>
                            <th colspan="2">0-6 Days</th>
                            <th colspan="2">7-28 Days</th>
                            <th colspan="2">29 days-11 mos</th>
                            <th colspan="2">1-4 y/o</th>
                            <th colspan="2">5-9 y/o</th>
                            <th colspan="2">10-14 y/o</th>
                            <th colspan="2">15-19 y/o</th>
                            <th colspan="2">20-24 y/o</th>
                            <th colspan="2">25-29 y/o</th>
                            <th colspan="2">30-34 y/o</th>
                            <th colspan="2">35-39 y/o</th>
                            <th colspan="2">40-44 y/o</th>
                            <th colspan="2">45-49 y/o</th>
                            <th colspan="2">50-54 y/o</th>
                            <th colspan="2">55-59 y/o</th>
                            <th colspan="2">60-64 y/o</th>
                            <th colspan="2">65-69 y/o</th>
                            <th colspan="2">>= 70 y/o</th>
                            <th colspan="2">Total</th>
                            <th rowspan="2">TOTAL Both Sex</th>
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
                    <tbody class="text-centered">
                        @foreach($collect1 as $c1)
                        <tr>
                            <td scope="row"><b>{{$c1['brgy']}}</b></td>
                            <td>{{$c1['item1_male']}}</td>
                            <td>{{$c1['item1_female']}}</td>
                            <td>{{$c1['item2_male']}}</td>
                            <td>{{$c1['item2_female']}}</td>
                            <td>{{$c1['item3_male']}}</td>
                            <td>{{$c1['item3_female']}}</td>
                            <td>{{$c1['item4_male']}}</td>
                            <td>{{$c1['item4_female']}}</td>
                            <td>{{$c1['item5_male']}}</td>
                            <td>{{$c1['item5_female']}}</td>
                            <td>{{$c1['item6_male']}}</td>
                            <td>{{$c1['item6_female']}}</td>
                            <td>{{$c1['item7_male']}}</td>
                            <td>{{$c1['item7_female']}}</td>
                            <td>{{$c1['item8_male']}}</td>
                            <td>{{$c1['item8_female']}}</td>
                            <td>{{$c1['item9_male']}}</td>
                            <td>{{$c1['item9_female']}}</td>
                            <td>{{$c1['item10_male']}}</td>
                            <td>{{$c1['item10_female']}}</td>
                            <td>{{$c1['item11_male']}}</td>
                            <td>{{$c1['item11_female']}}</td>
                            <td>{{$c1['item12_male']}}</td>
                            <td>{{$c1['item12_female']}}</td>
                            <td>{{$c1['item13_male']}}</td>
                            <td>{{$c1['item13_female']}}</td>
                            <td>{{$c1['item14_male']}}</td>
                            <td>{{$c1['item14_female']}}</td>
                            <td>{{$c1['item15_male']}}</td>
                            <td>{{$c1['item15_female']}}</td>
                            <td>{{$c1['item16_male']}}</td>
                            <td>{{$c1['item16_female']}}</td>
                            <td>{{$c1['item17_male']}}</td>
                            <td>{{$c1['item17_female']}}</td>
                            <td>{{$c1['item18_male']}}</td>
                            <td>{{$c1['item18_female']}}</td>
                            <td>{{$c1['item19_male']}}</td>
                            <td>{{$c1['item19_female']}}</td>
                            <td>{{$c1['item20']}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="table-responsive mb-3">
                <table class="table table-bordered">
                    <thead class="thead-light text-centered">
                        <tr>
                            <th colspan="42">Post COVID-19 condition, unspecified</th>
                        </tr>
                        <tr>
                            <th rowspan="2">Barangay</th>
                            <th colspan="2">0-6 Days</th>
                            <th colspan="2">7-28 Days</th>
                            <th colspan="2">29 days-11 mos</th>
                            <th colspan="2">1-4 y/o</th>
                            <th colspan="2">5-9 y/o</th>
                            <th colspan="2">10-14 y/o</th>
                            <th colspan="2">15-19 y/o</th>
                            <th colspan="2">20-24 y/o</th>
                            <th colspan="2">25-29 y/o</th>
                            <th colspan="2">30-34 y/o</th>
                            <th colspan="2">35-39 y/o</th>
                            <th colspan="2">40-44 y/o</th>
                            <th colspan="2">45-49 y/o</th>
                            <th colspan="2">50-54 y/o</th>
                            <th colspan="2">55-59 y/o</th>
                            <th colspan="2">60-64 y/o</th>
                            <th colspan="2">65-69 y/o</th>
                            <th colspan="2">>= 70 y/o</th>
                            <th colspan="2">Total</th>
                            <th rowspan="2">TOTAL Both Sex</th>
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
                    <tbody class="text-centered">
                        @foreach($collect2 as $c1)
                        <tr>
                            <td scope="row"><b>{{$c1['brgy']}}</b></td>
                            <td>{{$c1['item1_male']}}</td>
                            <td>{{$c1['item1_female']}}</td>
                            <td>{{$c1['item2_male']}}</td>
                            <td>{{$c1['item2_female']}}</td>
                            <td>{{$c1['item3_male']}}</td>
                            <td>{{$c1['item3_female']}}</td>
                            <td>{{$c1['item4_male']}}</td>
                            <td>{{$c1['item4_female']}}</td>
                            <td>{{$c1['item5_male']}}</td>
                            <td>{{$c1['item5_female']}}</td>
                            <td>{{$c1['item6_male']}}</td>
                            <td>{{$c1['item6_female']}}</td>
                            <td>{{$c1['item7_male']}}</td>
                            <td>{{$c1['item7_female']}}</td>
                            <td>{{$c1['item8_male']}}</td>
                            <td>{{$c1['item8_female']}}</td>
                            <td>{{$c1['item9_male']}}</td>
                            <td>{{$c1['item9_female']}}</td>
                            <td>{{$c1['item10_male']}}</td>
                            <td>{{$c1['item10_female']}}</td>
                            <td>{{$c1['item11_male']}}</td>
                            <td>{{$c1['item11_female']}}</td>
                            <td>{{$c1['item12_male']}}</td>
                            <td>{{$c1['item12_female']}}</td>
                            <td>{{$c1['item13_male']}}</td>
                            <td>{{$c1['item13_female']}}</td>
                            <td>{{$c1['item14_male']}}</td>
                            <td>{{$c1['item14_female']}}</td>
                            <td>{{$c1['item15_male']}}</td>
                            <td>{{$c1['item15_female']}}</td>
                            <td>{{$c1['item16_male']}}</td>
                            <td>{{$c1['item16_female']}}</td>
                            <td>{{$c1['item17_male']}}</td>
                            <td>{{$c1['item17_female']}}</td>
                            <td>{{$c1['item18_male']}}</td>
                            <td>{{$c1['item18_female']}}</td>
                            <td>{{$c1['item19_male']}}</td>
                            <td>{{$c1['item19_female']}}</td>
                            <td>{{$c1['item20']}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-light text-centered">
                        <tr>
                            <th colspan="42">Post COVID-19 condition, unspecified</th>
                        </tr>
                        <tr>
                            <th rowspan="2">Barangay</th>
                            <th colspan="2">0-6 Days</th>
                            <th colspan="2">7-28 Days</th>
                            <th colspan="2">29 days-11 mos</th>
                            <th colspan="2">1-4 y/o</th>
                            <th colspan="2">5-9 y/o</th>
                            <th colspan="2">10-14 y/o</th>
                            <th colspan="2">15-19 y/o</th>
                            <th colspan="2">20-24 y/o</th>
                            <th colspan="2">25-29 y/o</th>
                            <th colspan="2">30-34 y/o</th>
                            <th colspan="2">35-39 y/o</th>
                            <th colspan="2">40-44 y/o</th>
                            <th colspan="2">45-49 y/o</th>
                            <th colspan="2">50-54 y/o</th>
                            <th colspan="2">55-59 y/o</th>
                            <th colspan="2">60-64 y/o</th>
                            <th colspan="2">65-69 y/o</th>
                            <th colspan="2">>= 70 y/o</th>
                            <th colspan="2">Total</th>
                            <th rowspan="2">TOTAL Both Sex</th>
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
                    <tbody class="text-centered">
                        @foreach($collect3 as $c1)
                        <tr>
                            <td scope="row"><b>{{$c1['brgy']}}</b></td>
                            <td>{{$c1['item1_male']}}</td>
                            <td>{{$c1['item1_female']}}</td>
                            <td>{{$c1['item2_male']}}</td>
                            <td>{{$c1['item2_female']}}</td>
                            <td>{{$c1['item3_male']}}</td>
                            <td>{{$c1['item3_female']}}</td>
                            <td>{{$c1['item4_male']}}</td>
                            <td>{{$c1['item4_female']}}</td>
                            <td>{{$c1['item5_male']}}</td>
                            <td>{{$c1['item5_female']}}</td>
                            <td>{{$c1['item6_male']}}</td>
                            <td>{{$c1['item6_female']}}</td>
                            <td>{{$c1['item7_male']}}</td>
                            <td>{{$c1['item7_female']}}</td>
                            <td>{{$c1['item8_male']}}</td>
                            <td>{{$c1['item8_female']}}</td>
                            <td>{{$c1['item9_male']}}</td>
                            <td>{{$c1['item9_female']}}</td>
                            <td>{{$c1['item10_male']}}</td>
                            <td>{{$c1['item10_female']}}</td>
                            <td>{{$c1['item11_male']}}</td>
                            <td>{{$c1['item11_female']}}</td>
                            <td>{{$c1['item12_male']}}</td>
                            <td>{{$c1['item12_female']}}</td>
                            <td>{{$c1['item13_male']}}</td>
                            <td>{{$c1['item13_female']}}</td>
                            <td>{{$c1['item14_male']}}</td>
                            <td>{{$c1['item14_female']}}</td>
                            <td>{{$c1['item15_male']}}</td>
                            <td>{{$c1['item15_female']}}</td>
                            <td>{{$c1['item16_male']}}</td>
                            <td>{{$c1['item16_female']}}</td>
                            <td>{{$c1['item17_male']}}</td>
                            <td>{{$c1['item17_female']}}</td>
                            <td>{{$c1['item18_male']}}</td>
                            <td>{{$c1['item18_female']}}</td>
                            <td>{{$c1['item19_male']}}</td>
                            <td>{{$c1['item19_female']}}</td>
                            <td>{{$c1['item20']}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection