@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header"><b>Fast Encode - Mortality and Natality</b></div>
        <div class="card-body">
            <form action="{{route('fhsis_fastlookup2')}}" method="GET">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text">Select Options first to Proceed</span>
                    </div>
                    <select class="custom-select" name="year" id="year" required>
                        <option disabled {{(is_null(request()->input('year'))) ? 'selected' : ''}}>Select Year</option>
                        @foreach(range(date('Y'), 2020) as $y)
                        <option value="{{$y}}" {{(request()->input('year') == $y) ? 'selected' : ''}}>{{$y}}</option>
                        @endforeach
                    </select>
                    <select class="custom-select" name="month" id="month" required>
                        <option disabled {{(is_null(request()->input('month'))) ? 'selected' : ''}}>Select Month</option>
                        <option value="01" {{(request()->input('month') == '01') ? 'selected' : ''}}>JANUARY</option>
                        <option value="02" {{(request()->input('month') == '02') ? 'selected' : ''}}>FEBRUARY</option>
                        <option value="03" {{(request()->input('month') == '03') ? 'selected' : ''}}>MARCH</option>
                        <option value="04" {{(request()->input('month') == '04') ? 'selected' : ''}}>APRIL</option>
                        <option value="05" {{(request()->input('month') == '05') ? 'selected' : ''}}>MAY</option>
                        <option value="06" {{(request()->input('month') == '06') ? 'selected' : ''}}>JUNE</option>
                        <option value="07" {{(request()->input('month') == '07') ? 'selected' : ''}}>JULY</option>
                        <option value="08" {{(request()->input('month') == '08') ? 'selected' : ''}}>AUGUST</option>
                        <option value="09" {{(request()->input('month') == '09') ? 'selected' : ''}}>SEPTEMBER</option>
                        <option value="10" {{(request()->input('month') == '10') ? 'selected' : ''}}>OCTOBER</option>
                        <option value="11" {{(request()->input('month') == '11') ? 'selected' : ''}}>NOVEMBER</option>
                        <option value="12" {{(request()->input('month') == '12') ? 'selected' : ''}}>DECEMBER</option>
                    </select>
                    <div class="input-group-append">
                      <button class="btn btn-primary" type="submit">Submit</button>
                    </div>
                </div>
            </form>
            @if(!is_null(request()->input('month')) && !is_null(request()->input('year')))
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-light text-center">
                        <tr>
                            <th rowspan="3">Barangay</th>
                            <th colspan="14">Part 1 - Mortality</th>
                            <th colspan="4">Part 2 - Natality</th>
                        </tr>
                        <tr>
                            <th colspan="2">Early Neonatal Deaths</th>
                            <th colspan="2">Fetal Deaths</th>
                            <th colspan="2">Neonatal Deaths</th>
                            <th colspan="2">Infant Deaths</th>
                            <th colspan="2">Under-five Deaths</th>
                            <th colspan="2">Maternal Deaths</th>
                            <th colspan="2">Total Deaths</th>
                            <th colspan="2">Livebirths</th>
                            <th colspan="2">Livebirths among 15-19 y/o women</th>
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
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($array_list as $a)
                        <tr>
                            <td>{{$a['barangay']}}</td>
                            <td class="text-center">{{$a['early_neonatal_deaths_m']}}</td>
                            <td class="text-center">{{$a['early_neonatal_deaths_f']}}</td>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                            <td class="text-center">{{$a['neonatal_deaths_m']}}</td>
                            <td class="text-center">{{$a['neonatal_deaths_f']}}</td>
                            <td class="text-center">{{$a['infant_deaths_m']}}</td>
                            <td class="text-center">{{$a['infant_deaths_f']}}</td>
                            <td class="text-center">{{$a['underfive_deaths_m']}}</td>
                            <td class="text-center">{{$a['underfive_deaths_f']}}</td>
                            <td class="text-center">N/A</td>
                            <td class="text-center"></td>
                            <td class="text-center">{{$a['total_deaths_m']}}</td>
                            <td class="text-center">{{$a['total_deaths_f']}}</td>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
        @if(!is_null(request()->input('month')) && !is_null(request()->input('year')))
        <div class="card-footer">

        </div>
        @endif
    </div>
</div>
@endsection