@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div><b>OPD Summary</b></div>
                <div></div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead class="text-center thead-light">
                    <tr>
                        <th rowspan="3">
                            <h6>
                                <div><b>OPD</b></div>
                                <div><b class="text-success">{{auth()->user()->opdfacility->facility_name}}</b></div>
                            </h6>
                            <h6>Date: 
                                @if(request()->input('type') == 'Daily')
                                {{date('F d, Y', strtotime(request()->input('sdate')))}}
                                @elseif(request()->input('type') == 'Monthly')
                                {{$month_flavor}}, {{$syear}}
                                @else
                                {{$syear}}
                                @endif
                            </h6>
                        </th>
                        <th colspan="4">
                            <h6><b>Pedia</b></h6>
                            <h6><i>(0-19 y.o)</i></h6>
                        </th>
                        <th colspan="4">
                            <h6><b>Adult</b></h6>
                            <h6><i>(20-59 y.o)</i></h6>
                        </th>
                        <th colspan="4">
                            <h6><b>Geriatric</b></h6>
                            <h6><i>(60 AND ABOVE)</i></h6>
                        </th>
                        <th rowspan="3">
                            TOTAL
                        </th>
                    </tr>
                    <tr>
                        <th colspan="2" class="text-primary">M</th>
                        <th colspan="2" class="text-danger">F</th>
                        <th colspan="2" class="text-primary">M</th>
                        <th colspan="2" class="text-danger">F</th>
                        <th colspan="2" class="text-primary">M</th>
                        <th colspan="2" class="text-danger">F</th>
                    </tr>
                    <tr>
                        <th colspan="1">O</th>
                        <th colspan="1">N</th>
                        <th colspan="1">O</th>
                        <th colspan="1">N</th>
                        <th colspan="1">O</th>
                        <th colspan="1">N</th>
                        <th colspan="1">O</th>
                        <th colspan="1">N</th>
                        <th colspan="1">O</th>
                        <th colspan="1">N</th>
                        <th colspan="1">O</th>
                        <th colspan="1">N</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $pedia_old_m_total = 0;
                    $pedia_new_m_total = 0;
                    $pedia_old_f_total = 0;
                    $pedia_new_f_total = 0;
                    $adult_old_m_total = 0;
                    $adult_new_m_total = 0;
                    $adult_old_f_total = 0;
                    $adult_new_f_total = 0;
                    $senior_old_m_total = 0;
                    $senior_new_m_total = 0;
                    $senior_old_f_total = 0;
                    $senior_new_f_total = 0;

                    $main_pedia_total = 0;
                    $main_adult_total = 0;
                    $main_senior_total = 0;
                    @endphp
                    @foreach($final_arr as $f)
                    <tr>
                        <td><b>{{$f['name']}}</b></td>
                        <td class="text-center">{{$f['pedia_old_m']}}</td>
                        <td class="text-center">{{$f['pedia_new_m']}}</td>
                        <td class="text-center">{{$f['pedia_old_f']}}</td>
                        <td class="text-center">{{$f['pedia_new_f']}}</td>
                        <td class="text-center">{{$f['adult_old_m']}}</td>
                        <td class="text-center">{{$f['adult_new_m']}}</td>
                        <td class="text-center">{{$f['adult_old_f']}}</td>
                        <td class="text-center">{{$f['adult_new_f']}}</td>
                        <td class="text-center">{{$f['senior_old_m']}}</td>
                        <td class="text-center">{{$f['senior_new_m']}}</td>
                        <td class="text-center">{{$f['senior_old_f']}}</td>
                        <td class="text-center">{{$f['senior_new_f']}}</td>
                        <td class="text-center">
                            <b>{{$f['pedia_old_m'] +
                                $f['pedia_new_m'] +
                                $f['pedia_old_f'] +
                                $f['pedia_new_f'] +
                                $f['adult_old_m'] +
                                $f['adult_new_m'] +
                                $f['adult_old_f'] +
                                $f['adult_new_f'] +
                                $f['senior_old_m'] +
                                $f['senior_new_m'] +
                                $f['senior_old_f'] +
                                $f['senior_new_f'];
                            }}</b>
                        </td>
                    </tr>
                        @php
                        $pedia_old_m_total += $f['pedia_old_m'];
                        $pedia_new_m_total += $f['pedia_new_m'];
                        $pedia_old_f_total += $f['pedia_old_f'];
                        $pedia_new_f_total += $f['pedia_new_f'];
                        $adult_old_m_total += $f['adult_old_m'];
                        $adult_new_m_total += $f['adult_new_m'];
                        $adult_old_f_total += $f['adult_old_f'];
                        $adult_new_f_total += $f['adult_new_f'];
                        $senior_old_m_total += $f['senior_old_m'];
                        $senior_new_m_total += $f['senior_new_m'];
                        $senior_old_f_total += $f['senior_old_f'];
                        $senior_new_f_total += $f['senior_new_f'];

                        $main_pedia_total += $f['pedia_old_m'] + $f['pedia_new_m'] + $f['pedia_old_f'] + $f['pedia_new_f'];
                        $main_adult_total += $f['adult_old_m'] + $f['adult_new_m'] + $f['adult_old_f'] + $f['adult_new_f'];
                        $main_senior_total += $f['senior_old_m'] + $f['senior_new_m'] + $f['senior_old_f'] + $f['senior_new_f'];
                        @endphp
                    @endforeach
                    <tr class="font-weight-bold bg-light">
                        <td class="text-right">TOTAL</td>
                        <td class="text-center">{{$pedia_old_m_total}}</td>
                        <td class="text-center">{{$pedia_new_m_total}}</td>
                        <td class="text-center">{{$pedia_old_f_total}}</td>
                        <td class="text-center">{{$pedia_new_f_total}}</td>
                        <td class="text-center">{{$adult_old_m_total}}</td>
                        <td class="text-center">{{$adult_new_m_total}}</td>
                        <td class="text-center">{{$adult_old_f_total}}</td>
                        <td class="text-center">{{$adult_new_f_total}}</td>
                        <td class="text-center">{{$senior_old_m_total}}</td>
                        <td class="text-center">{{$senior_new_m_total}}</td>
                        <td class="text-center">{{$senior_old_f_total}}</td>
                        <td class="text-center">{{$senior_new_f_total}}</td>
                        <td class="text-center text-danger">{{$main_pedia_total + $main_adult_total + $main_senior_total}}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="3" class="text-right"><b>PEDIA</b></td>
                        <td class="text-center  bg-light"><b>{{$main_pedia_total}}</b></td>
                        <td colspan="3" class="text-right"><b>ADULT</b></td>
                        <td class="text-center bg-light"><b>{{$main_adult_total}}</b></td>
                        <td colspan="3" class="text-right"><b>SENIOR</b></td>
                        <td class="text-center bg-light"><b>{{$main_senior_total}}</b></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
            <ul>
                Legend:
                <li>O - Old Patient</li>
                <li>N - New Patient</li>
            </ul>
        </div>
    </div>
    <h6 class="text-center mt-3">Developed and Maintained by <b>Christian James Historillo</b> (CESU J.O Encoder) - ©2024</h6>
</div>
@endsection